<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_plans_model extends CI_Model {
	
	public function coaching_test_plans ($coaching_id=0) {
	    $this->db->select ('TP.plan_id, TP.title');
	    $this->db->from ('coaching_test_plans CTP');
	    $this->db->join ('test_plans TP', 'TP.plan_id=CTP.plan_id');
	    $this->db->where ('CTP.coaching_id', $coaching_id);
	    $sql = $this->db->get ();
	    return $sql->result_array ();
	}
	
	public function categories_in_plan ($coaching_id=0, $plan_id=0) {
	    $this->db->where ('CTP.coaching_id', $coaching_id);
	    $this->db->where ('CTP.plan_id', $plan_id);
	    $sql = $this->db->get ('coaching_test_categories CTP');
	    return $sql->result_array ();
	}
	
	
	public function subscribe_plan ($coaching_id=0, $plan_id=0) {
		// Get tests in plan
		$this->db->select ('test_id');
		$this->db->where ('plan_id', $plan_id);
		$sql = $this->db->get ('tests_in_plan');
		$result = $sql->result_array ();
		if ($sql->num_rows() > 0) {
		    foreach ($result as $row) {
		        $this->db->select ('T.*, TC.title AS cat_title');
		        $this->db->from ('tests T');
		        $this->db->join ('tests_categories TC', 'TC.id=T.category_id', 'left');
        		$this->db->where ('T.test_id', $row['test_id']);
        		$sql_tests = $this->db->get ();
		        $tests_results = $sql_tests->result_array ();
		        // Copy tests to coaching
		        if (! empty ($tests_results)) {
		            foreach ($tests_results as $tr) {
		                // Copy Test Categories
		                $tc_data['id'] = NULL;
		                $tc_data['title'] = $tr['title'];
		                $tc_data['creation_date'] = time ();
		                $tc_data['created_by'] = $this->session->userdata ('member_id');
		                $tc_data['status'] = 1;
		                $tc_data['parent_id'] = 0;
		                $tc_data['plan_id'] = $plan_id;
		                $tc_data['coaching_id'] = $coaching_id;
		                // if not already inserted
		                $sql_ctc = $this->db->get_where ('coaching_test_categories', array('coaching_id'=>$coaching_id, 'plan_id'=>$plan_id, 'title'=>$tr['title']));
		                if ($sql_ctc->num_rows() == 0) {
    		                $this->db->insert ('coaching_test_categories', $tc_data);
    		                $cat_id = $this->db->insert_id ();
		                } else {
		                    $row_ctc = $sql_ctc->row_array ();
		                    $cat_id = $row_ctc['id'];
		                }
		                
		                // Copy Tests
		                $t_data = $tr;
		                $t_data['test_id'] = NULL;
		                unset($t_data['master_id'], $t_data['cat_title']);
		                $t_data['category_id'] = $cat_id;
		                $t_data['plan_id'] = $plan_id;
		                $t_data['coaching_id'] = $coaching_id;
		                $t_data['creation_date'] = time ();
		                $t_data['created_by'] = $this->session->userdata ('member_id');
		                $this->db->insert ('coaching_tests', $t_data);
		                $test_id = $this->db->insert_id ();
                        
                        // Get all questions in this test 
                        $questions = [];
                        $this->db->select ('question_id');
                        $this->db->where ('test_id', $test_id);
                        $sql_tq = $this->db->get ('test_questions');
                        if ($sql_tq->num_rows() > 0) {
                            foreach ($sql_tq->result_array() as $tq_row) {
                                // Get questions
                                $this->db->where ('question_id', $tq_row['question_id']);
                                $sql_q = $this->db->get ('questions');
                                $row_q = $sql_q->row_array ();
                               
                                // Get parent_question
                                $this->db->where ('question_id', $row_q['parent_id']);
                                $sql_qp = $this->db->get ('questions');
                                $row_qp = $sql_q->row_array ();
                                
                                // Insert parent question, if not present
                                $sql_check = $this->db->get_where ('coaching_questions', array('coaching_id'=>$coaching_id, 'plan_id'=>$plan_id, 'question'=>$row_qp['question']));
                                if ($sql_check->num_rows() == 0) {
                                    $data_qp = $row_qp;
                                    unset ($data_qp['master_id']);
                                    $data_qp['question_id'] = NULL;
                                    $data_qp['parent_id'] = 0;
                                    $data_qp['coaching_id'] = $coaching_id;
                                    $data_qp['plan_id'] = $plan_id;
                                    $this->db->insert ('coaching_questions', $data_qp);
                                    $parent_id = $this->db->insert_id ();
                                } else {
                                    $row_check = $sql_check->row_array ();
                                    $parent_id = $row_check['parent_id'];
                                }
                                // Insert question
                                $data_q = $row_q;
                                unset ($data_q['master_id']);
                                $data_q['question_id'] = NULL;
                                $data_q['parent_id'] = $parent_id;
                                $data_q['coaching_id'] = $coaching_id;
                                $data_q['plan_id'] = $plan_id;
                                $this->db->insert ('coaching_questions', $data_q);
                                $question_id = $this->db->insert_id ();
                                // Add question to test
                                $data_tq = ['coaching_id'=>$coaching_id, 'plan_id'=>$plan_id, 'test_id'=>$test_id, 'question_id'=>$question_id];
                                $sql_check_tq = $this->db->get_where ('coaching_test_questions', $data_tq);
                                if ($sql_check_tq->num_rows() == 0) {
                                    $this->db->insert ('coaching_test_questions', $data_tq);
                                }
                            }
                        }
		            }
		        }
		    }
		}
		
		// Subscribe to this plan
		$sql = $this->db->get_where ('coaching_test_plans', array('coaching_id'=>$coaching_id, 'plan_id'=>$plan_id));
		if ($sql->num_rows() == 0) {
		    $data['coaching_id'] = $coaching_id;
		    $data['plan_id'] = $plan_id;
		    $data['start_date'] = time ();
		    $data['end_date'] = 0;
		    $data['status'] = 1;
		    $data['amount_paid'] = 0;
		    $data['creation_date'] = time ();
		    $data['created_by'] = $this->session->userdata ('member_id');
		    $this->db->insert ('coaching_test_plans', $data);
		}
	}

}