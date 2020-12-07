<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans_model extends CI_Model {
	
	/* Test Plan Categories */
	public function test_plan_categories ($status='-1') {
		if ($status > '-1') {
			$this->db->where ('status', $status);
		}
		$sql = $this->db->get ('test_plan_categories');
		return $sql->result_array ();
	}

	public function get_category ($category_id=0) {
		$this->db->where ('id', $category_id);
		$sql = $this->db->get ('test_plan_categories');
		return $sql->row_array ();
	}

	public function create_category ($category_id=0) {

		$data['title'] 				= $this->input->post ('title');
		$data['description'] 		= $this->input->post ('description');
		$data['status'] 			= $this->input->post ('status');

		if ($category_id > 0 ) {
			$this->db->where ('id', $category_id);
			$this->db->update ('test_plan_categories', $data);
		} else {
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$this->db->insert ('test_plan_categories', $data);
			$category_id = $this->db->insert_id ();
		}
		
		return $category_id;		
	}

	// Add ITS Categories to a plan
	public function remove_category ($category_id=0) {

		$this->db->where ('id', $category_id);
		$sql = $this->db->delete ('test_plan_categories');

		$this->db->set ('category_id', 0);
		$this->db->where ($category_id);
		$this->db->update ('test_plans');
	}
	
	
	/*-----===== Test Plans =====-----*/
	public function test_plans ($category_id=0, $status='', $amount='-1') {
		
		
		$this->db->select ('TP.*, TPC.id, TPC.title AS cat_title');
		$this->db->from ('test_plans TP');
		$this->db->join ('test_plan_categories TPC', 'TP.category_id=TPC.id', 'left');
		if ($category_id > 0) {
			$this->db->where ('TP.category_id', $category_id);
		}
		if ($status != '') {
			$this->db->where ('TP.status', $status);
		}
		if ($amount == 0) {
			$this->db->where ('TP.amount', $amount);
		} else if ($amount > 0) {
			$this->db->where ('TP.amount >', $amount);
		}
		
		$this->db->order_by ('TP.creation_date', 'DESC');
		$sql = $this->db->get ();
		// echo $this->db->last_query ();
		//exit;
		if ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
		} else {
			$result = false;
		}
		
		return $result;
	}


	// Save test plan details
	public function create_plan ($plan_id=0) {

		$data['title'] 				= $this->input->post ('title');
		$data['category_id'] 		= $this->input->post ('category');
		$data['description'] 		= $this->input->post ('description');
		$data['status'] 			= $this->input->post ('status');
		$data['amount'] 			= $this->input->post ('price');

		if ($plan_id > 0 ) {
			$this->db->where ('plan_id', $plan_id);
			$this->db->update ('test_plans', $data);
		} else {
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$this->db->insert ('test_plans', $data);
			$plan_id = $this->db->insert_id ();
		}
		
		return $plan_id;		
	}

	// Get test plan details
	public function get_plan ($plan_id=0, $status='') {
		$this->db->where ('plan_id', $plan_id);
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		$sql = $this->db->get ('test_plans');
		$result = $sql->row_array ();		
		return $result;
	}

	public function tests_in_plan ($plan_id=0, $category_id=0) {

		$prefix = $this->db->dbprefix;
  		$query = 'SELECT T.* FROM '.$prefix.'tests T INNER JOIN '.$prefix.'tests_in_plan TIP ON T.test_id=TIP.test_id WHERE TIP.plan_id='.$plan_id;
		$sql = $this->db->query ($query);
		return $sql->result_array ();
	}
	
	public function active_subscribers($plan_id=0) {
		$prefix = $this->db->dbprefix;
  		$query = 'SELECT COUNT(*) as `active_subscribers` FROM '.$prefix.'coaching_test_plans WHERE plan_id='.$plan_id;
		$sql = $this->db->query ($query);
		//print_r($this->db->last_query());
		$result = $sql->row_array();
		return $result['active_subscribers'];
	}
	
	public function tests_not_in_plan ($plan_id=0, $category_id=0, $offset=0, $count=false) {
		$prefix = $this->db->dbprefix; 
		
		$categories = $this->common_model->_get_all_children ($category_id, SYS_TREE_TYPE_TEST);
		if ( ! empty ($categories)) {
			$categories = implode (',', $categories);
			$where_clause = 'T.category_id IN ('.$categories.') AND ';
		} else {
			$where_clause = 'T.category_id = '.$category_id.' AND ';
		}
		/*-- Search criteria */
	    $status = $this->input->post ('search_status');
	    if ($status > '-1') {
			$where_clause .= ' T.finalized = '.$status.' AND ';
	    }

		if ($this->input->post ('search_text')) {
		    $search = $this->input->post ('search_text');
			$where_clause .= ' T.title LIKE "%'.$search.'%" AND';
		}
		/*--// Search criteria */

		$query = 'SELECT T.* FROM '.$prefix.'tests T WHERE '.$where_clause.' T.test_id NOT IN (SELECT TIP.test_id FROM '.$prefix.'tests_in_plan TIP WHERE TIP.plan_id='.$plan_id.')';
		if ($count == false) {
			$query .= ' LIMIT '.RECORDS_PER_PAGE.' OFFSET '.$offset;
		}
        $sql = $this->db->query ($query); 
		return $sql->result_array ();
	}
	
	public function remove_plan ($plan_id) {
		// remove all tests in plan 
		$this->db->where ('plan_id', $plan_id);
		$this->db->delete ('tests_in_plan');
		
		// remove plan 
		$this->db->where ('plan_id', $plan_id);
		$this->db->delete ('test_plans');
	}
	
	// Add ITS Categories to a plan
	public function add_tests ($plan_id=0) {

		// User submitted categories
		$tests = $this->input->post ('coaching_tests');
		$creation_dt = time ();
		$created_by = $this->session->userdata ('member_id');
		$data = [];
		if (! empty($tests)) {
			foreach ($tests as $test_id) {
				$data = ['test_id'=>$test_id, 'plan_id'=>$plan_id, 'creation_dt'=>$creation_dt, 'created_by'=>$created_by];
				$this->db->insert ('tests_in_plan', $data);
			}
		}		
	}
	
	// Add ITS Categories to a plan
	public function remove_tests ($plan_id=0) {

		// User submitted categories
		$tests = $this->input->post ('coaching_tests');
		if (! empty($tests)) {
			foreach ($tests as $test_id) {
				$data = ['test_id'=>$test_id, 'plan_id'=>$plan_id];
				$this->db->where ($data);
				$this->db->delete ('tests_in_plan');
			}
		}		
	}
	
	
	/*-----=====  ITS TEST PLANS =====-----*/
	public function its_test_plan_categories () {
		// Connect to ITS database
		$its_db = $this->load->database ('its', true);
		
		// Run query
		$its_db->select ('TPC.*');
		$its_db->from ('test_plan_categories TPC');
		$its_db->where ('TPC.status', 1);
		$sql = $its_db->get ();
		return $sql->result_array ();
	}

	public function its_test_plan_cat_exists ($id=0) {
		$this->db->where ('TPC.master_id', $id);
		$sql = $this->db->get ('test_plan_categories TPC');
		return $sql->row_array ();
	}

	public function its_import_category ($category_id=0) {
	    // Connect to ITS database
		$its_db = $this->load->database ('its', true);
		
		// Get test plan categories (ITS)
		$its_db->select ('TPC.*');
		$its_db->from ('test_plan_categories TPC');
		$its_db->where ('TPC.status', 1);
		$its_db->where ('TPC.id', $category_id);
		$sql = $its_db->get ();
        $row = $sql->row_array ();
        
		// Copy Plan categories (If master id not exists)
		$this->db->where ('master_id', $category_id);
		$sql = $this->db->get ('test_plan_categories');
		if ($sql->num_rows () == 0 ) {
			$cat['id']  =  NULL;
			$cat['master_id'] = $row['id'];
			$cat['title'] = $row['title'];
			$cat['description'] = $row['description'];
			$cat['plan_details'] = $row['plan_details'];
			$cat['status'] = 0;
			$cat['creation_date'] = time();
			$cat['created_by'] = intval($this->session->userdata('member_id'));
			$sql = $this->db->insert ('test_plan_categories', $cat);
			$category_id = $this->db->insert_id ();			
		} else {
			exit;
			// Below script will not run if master_id is already present 
		}
        
		// Get all plans in this category (ITS)
		$its_db->select ('TP.*');
		$its_db->from ('test_plans TP');
		$its_db->where ('TP.status', 1);
		$its_db->where ('TP.category_id', $row['id']);
		$sql = $its_db->get ();
        $result = $sql->result_array ();
			
		if (! empty ($result)) {
			foreach ($result as $row) {
				// Insert Plans
				$plan['plan_id']  =  NULL;
				$plan['category_id'] = $category_id;
				$plan['master_id'] = $row['plan_id'];
				$plan['title'] = $row['title'];
				$plan['description'] = $row['description'];
				$plan['amount'] = $row['amount'];
				$plan['status'] = 0;
				$plan['creation_date'] = time();
				$plan['created_by'] = intval($this->session->userdata('member_id'));
				$this->db->insert ('test_plans', $plan);
				$plan_id = $this->db->insert_id ();
				
				// Get all tests (ITS) in this plan 
				$its_db->select ('T.*, TC.id as cat_id, TC.title as cat_title, TC.parent_id as cat_parent_id');
				$its_db->from ('tests T, test_categories TC, tests_in_plan TIP');
				$its_db->where ('T.test_id=TIP.test_id');
				$its_db->where ('TC.id=T.category_id');
				$its_db->where ('TIP.plan_id', $plan_id);
				$sql_tip = $its_db->get ();
				$tests_in_plan = $sql_tip->result_array ();
				
				if (! empty ($tests_in_plan)) {
					foreach ($tests_in_plan as $tip) {
						$test_id = $tip['test_id'];
						
						// Get Test Categories With Parent
						// $new_test_cat_id = $this->copy_parent ($tip['cat_id']);
						// Insert test categories
						$this->db->where ('master_id', $tip['cat_id']);
						$q = $this->db->get ('tests_categories');
						if ($q->num_rows () == 0) {
							$test_cat_data['id'] = NULL;
							$test_cat_data['title'] = $tip['cat_title'];
							$test_cat_data['parent_id'] = 0;
							$test_cat_data['master_id'] = $tip['cat_id'];
							$test_cat_data['level'] = 4;
							$test_cat_data['status'] = 1;
							$test_cat_data['creation_date'] = time ();
							$test_cat_data['created_by'] = intval ($this->session->userdata('member_id'));
							$this->db->insert ('tests_categories', $test_cat_data);
							$new_test_cat_id = $this->db->insert_id ();							
						} else {
							$row = $q->row_array ();
							$new_test_cat_id = $row['id'];
						}

						// Insert tests
						unset ($tip['cat_id'], $tip['cat_title'], $tip['cat_parent_id']);
						$test_data = $tip;
						$test_data['test_id'] = NULL;
						$test_data['category_id'] = $new_test_cat_id;
						$test_data['master_id'] = $test_id;
						$this->db->insert ('tests', $test_data);
						$new_test_id = $this->db->insert_id ();
						
						// Insert tests in plan
						$tip_data['plan_id'] = $plan_id;
						$tip_data['test_id'] = $new_test_id;
						$tip_data['creation_date'] = time ();
						$tip_data['created_by'] = intval($this->session->userdata('member_id'));
						$this->db->insert ('tests_in_plan', $tip_data);
						
						// Get all questions in test (ITS)
						$its_db->select ('Q.*');
						$its_db->from ('questions Q');
						$its_db->join ('tests_questions TQ', 'TQ.question_id=Q.question_id');
						$its_db->where ('TQ.test_id', $test_id);
						$sql_tq = $its_db->get ();
						$test_questions = $sql_tq->result_array ();
						$qp_parent = [];
						if (! empty ($test_questions)) {
							foreach ($test_questions as $tq) {
								// Get question parent
								$its_db->where ('question_id', $tq['parent_id']);
								$sql_qp = $its_db->get ('questions');
								$row_qp = $sql_qp->row_array ();
								$parent_id = $this->insert_parent_question ($row_qp);
								
								// Insert questions
								$q_data = $tq;
								$q_data['question_id'] = NULL;
								$q_data['parent_id'] = $parent_id;
								$q_data['master_id'] = $tq['question_id'];
								$q_data['creation_date'] = time();
								$q_data['created_by'] = intval($this->session->userdata('member_id'));
								$this->db->insert ('questions', $q_data);
								$new_question_id = $this->db->insert_id ();
								
								// Insert Test Questions
								$tq_data['test_id'] = $new_test_id;
								$tq_data['question_id'] = $new_question_id;
								$this->db->insert ('test_questions', $tq_data);
							}
						}
					}
				}
					
			}
		}
	}
	
	public function insert_parent_question ($data=[]) {
		// Check if question exists
		$master_id = $data['question_id'];
		$this->db->where ('master_id', $master_id);
		$sql = $this->db->get ('questions');
		$row = $sql->row_array ();
		if ($sql->num_rows () == 0) {
			$data['master_id'] = $master_id;
			$data['question_id'] = NULL;
			$data['creation_date'] = time ();
			$data['created_by'] = intval($this->session->userdata ('member_id'));
			$this->db->insert ('questions', $data);
			$parent_id = $this->db->insert_id ();
		} else {
			$parent_id = $row['question_id'];
		}
		return $parent_id;
	}
	
	/**************************************************/
	public function get_its_test_plans ($category_id=0) {
		// Connect to ITS database
		$its_db = $this->load->database ('its', true);
		
		// Run query
		$its_db->select ('TP.*, TPC.title as cat_title, TPC.id AS cat_id');
		$its_db->from ('test_plans TP');
		$its_db->join ('test_plan_categories TPC', 'TPC.id=TP.category_id');
		$its_db->where ('TP.status', 1);
		if ($category_id > 0) {
			$its_db->where ('TP.category_id', $category_id);
		}
		$sql = $its_db->get ();
		return $sql->result_array ();
	}

	
	public function its_test_plan_exists ($plan_id=0) {
		$this->db->where ('TP.master_id', $plan_id);
		$sql = $this->db->get ('test_plans TP');
		return $sql->row_array ();
	}
	
	public function import_its_plan ($plan_id=0) {
	    // Connect to ITS database
		$its_db = $this->load->database ('its', true);
		
		// Run query
		$its_db->select ('TP.*, TPC.title as cat_title, TPC.description AS cat_description, TPC.plan_details AS cat_plan_details, TPC.id AS cat_id');
		$its_db->from ('test_plans TP');
		$its_db->join ('test_plan_categories TPC', 'TPC.id=TP.category_id');
		$its_db->where ('TP.status', 1);
		$its_db->where ('TP.plan_id', $plan_id);
		$sql = $its_db->get ();
        $row = $sql->row_array ();
                
        // Copy/Insert Plan categories
        $cat['id']  =  NULL;
        $cat['master_id'] = $row['cat_id'];
        $cat['title'] = $row['cat_title'];
        $cat['description'] = $row['cat_description'];
        $cat['plan_details'] = $row['cat_plan_details'];
        $cat['status'] = 0;
        $cat['creation_date'] = time();
        $cat['created_by'] = intval($this->session->userdata('member_id'));
        $this->db->insert ('test_plan_categories', $cat);
        $category_id = $its_db->insert_id ();
        
 
        // Copy/Insert Plan categories
        $plan['id']  =  NULL;
        $plan['category_id'] = $category_id;
        $plan['title'] = $row['title'];
        $plan['description'] = $row['description'];
        $plan['amount'] = $row['amount'];
        $plan['status'] = 0;
        $plan['creation_date'] = time();
        $plan['created_by'] = intval($this->session->userdata('member_id'));
        $this->db->insert ('test_plans', $plan);
	}
	
	public function copy_parent ($id=0, $parent_id=0, $start_id=0) {
		if ($id == 0) {
			$this->db->where ('master_id', $start_id);
			$query = $this->db->get ('tests_categories');
			$row = $query->row_array ();
			return $row['id'];
			//exit;
		}
		
	    // Connect to ITS database
		$its_db = $this->load->database ('its', true);
		
		$its_db->where ('id', $id);
		$sql = $its_db->get ('test_categories');
		$row = $sql->row_array ();
		$new_id = $row['parent_id'];
		
		$this->db->where ('master_id', $id);
		$query = $this->db->get ('tests_categories');
		if ($query->num_rows() == 0) {
			$data = [];
			$data['id'] = NULL;
			$data['parent_id'] = $parent_id;
			$data['master_id'] = $id;
			$data['title'] = $row['title'];
			$data['status'] = 1;
			$data['level'] = $row['level']; 
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$sql = $this->db->insert ('tests_categories', $data);
			$new_parent_id = $this->db->insert_id ();
			$this->copy_parent ($new_id, $new_parent_id, $start_id);
		}
	}
}