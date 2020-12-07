
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Tests_model extends CI_Model {	

	public function __construct() {
		parent::__construct();
	} 
	
	
	/* Test Plan Categories */
	public function test_categories ($coaching_id=0, $plan_id=0, $status='-1') {
		if ($status > '-1') {
			$this->db->where ('status', $status);
		}
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('plan_id', $plan_id);
		$sql = $this->db->get ('coaching_test_categories');
		return $sql->result_array ();
	}

	public function get_category ($category_id=0) {
		$this->db->where ('id', $category_id);
		$sql = $this->db->get ('coaching_test_categories');
		return $sql->row_array ();
	}

	public function create_category ($coaching_id=0, $category_id=0) {

		$data['title'] 				= $this->input->post ('title');
		
		if ($category_id > 0 ) {
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('id', $category_id);
			$this->db->update ('coaching_test_categories', $data);
		} else {
			$data['level'] 			= 0;
			$data['parent_id'] 		= 0;
			$data['status'] 		= 1;
			$data['coaching_id'] 	= $coaching_id;
			$data['creation_date'] = time ();
			$data['created_by'] = $this->session->userdata ('member_id');
			$this->db->insert ('coaching_test_categories', $data);
			$category_id = $this->db->insert_id ();
		}		
		return $category_id;		
	}

	// Add ITS Categories to a plan
	public function remove_category ($coaching_id=0, $category_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('id', $category_id);
		$sql = $this->db->delete ('coaching_test_categories');
	}

	/*--------------- // Category // ----------------------*/


	//=========== Model for list tests =======================
	public function get_latest_tests ($limit=5) {
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->where ('finalized', 1);
		$this->db->order_by ('creation_date', 'DESC');
		if ($limit > 0)	
			$this->db->limit ($limit);
		$query = $this->db->get ("coaching_tests");
		if ($query->num_rows() > 0)	{
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	//=========== Model for list tests =======================
	public function get_all_tests ($coaching_id=0, $category_id=0, $status='-1', $type=0) {
		
		if ( $status > '-1' ) {
			$this->db->where ('finalized', $status);
		}
		if ( $category_id > 0 ) {
			$this->db->where ('category_id', $category_id);
		}
		if ( $type > 0 ) {
			$this->db->where ('test_type', $type);
		}
		
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->order_by ('creation_date', 'DESC');
		
		$query = $this->db->get ("coaching_tests");
		$results = $query->result_array();	
		$data = [];
		if (! empty ($results))	{
			foreach ($results as $row) {
                $questions = $this->getTestQuestions ($coaching_id, $row['test_id']);
                $testMarks = $this->getTestQuestionMarks ($coaching_id, $row['test_id']);

                if (! empty ($questions)) {
                    $num_test_questions = count ($questions);
                } else {
                    $num_test_questions = 0;
                }

                $row['test_marks'] = $testMarks;
                $row['num_test_questions'] = $num_test_questions;

                $data[] = $row;
			}
		}
		return $data;
	}
	
	
	//=========== Model for search tests =======================
	public function search_tests ($coaching_id=0) {
		
		$status 	= $this->input->post ('status');
		$category_id = $this->input->post ('category');
		$search 	= $this->input->post ('search_text');
				
		if ($search != '') {
			$this->db->like ('title', $search, 'both');
		}
		if ($status > '-1') {
			$this->db->where ('finalized', $status);
		}
		if ($category_id > 0) {
			$this->db->where ('category_id', $category_id);
		}
		$this->db->where ('coaching_id', $coaching_id);
		$query = $this->db->get ("coaching_tests");
		return $query->result_array();
	}	
	
	
	//=========== Model for View a details of test =====================
	public function view_tests ($tid=0) {
		//$this->db->where ('coaching_id', $coaching_id);		
		$this->db->where ('test_id', $tid);
		$query = $this->db->get ('coaching_tests');
		if ($query->num_rows() > 0)	{
			// prepare the array with key value to be displayed
			$results = $query->row_array();
			return $results;
		} else {
			return false;
		}
	}
	
	//=========== Model for Create/Edit tests =======================
	public function create_test ($coaching_id, $category_id=0, $tid=0) {
		$now = time ();
		$test_type = $this->input->post ('test_type');
		$test_mode = TEST_MODE_ONLINE;
		$data = array(				
				'title'    		  	=>ascii_to_entities ($this->input->post('title')),
				'time_hour' 	  	=>0,
				'time_min'	      	=>$this->input->post('time_min'),
				'max_marks'  	  	=>0, 
				'pass_marks'      	=>$this->input->post('pass_marks'),
				'category_id'      	=>$this->input->post('category'),
				'test_mode'      	=>$test_mode,
				'test_type'      	=>$test_type,
            );
		
		if ($tid > 0) {
			$this->db->where ('test_id', $tid);
			$this->db->update ('coaching_tests', $data);
		} else {
			$data['num_takes']			= 0;
			$data['result_release']		= RELEASE_EXAM_NEVER;
			$data['coaching_id']	 	= $coaching_id;
			$data['created_by']	  		= intval ($this->session->userdata('member_id'));
			$data['creation_date']	  	= $now;
			$this->db->insert('coaching_tests', $data); 
			$tid = $this->db->insert_id();
		}

		$return = ['test_id'=>$tid, 'category_id'=>$category_id];
		return $return;
	}	
	
	
	//============ Model for ` test==============================
	public function delete_tests ($tid) {
		$this->db->where ('coaching_id', $coaching_id);		
		$this->db->where('test_id', $tid);
		$this->db->delete('coaching_test_questions');
		//========== delete fro test enrolment table
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->where('test_id', $tid);
		$this->db->delete('coaching_test_enrolments');
		//========== delete from test attempts log table
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->where('test_id', $tid);
		$this->db->delete('coaching_test_attempts');
		//========== delete from tests answers table
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->where('test_id', $tid);
		$this->db->delete('coaching_test_answers');
		//========== delete from tests table
		$this->db->where('test_id', $tid);
		$this->db->delete('coaching_tests'); 	
	}
	
	public function delete_attempt ($coaching_id=0, $attempt_id=0, $member_id=0, $test_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('id', $attempt_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('test_id', $test_id);
		$this->db->delete ('coaching_test_attempts');
		
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('attempt_id', $attempt_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('test_id', $test_id);
		$this->db->delete ('coaching_test_answers');		
	}
	
	public function add_to_test ($coaching_id=0, $question_id=0, $test_id=0) {
		$data = array (	'test_id'		=>	$test_id,
						'coaching_id'	=>	$coaching_id,
						'question_id'	=>	$question_id,
					  );

		$this->db->where ($data);
		$query = $this->db->get ('coaching_test_questions');
		if ($query->num_rows () == 0) {
			$this->db->insert("coaching_test_questions", $data);
		}
	} 
	
	//============ Model for release results of a test==============================
	public function release_result ($coaching_id=0, $test_id=0) {
		$this->db->set('release_result', RELEASE_EXAM_IMMEDIATELY);
		$this->db->where('coaching_id', $coaching_id);
		$this->db->where('test_id', $test_id);
		$this->db->update('coaching_test_enrolments');
	}	


	// checks if a question is already present in a test
	public function questionInTest ($test_id, $question_id = 0) {
		$this->db->where ('coaching_id', $coaching_id);
		
		$query = $this->db->get_where('coaching_test_questions', array('question_id'=>$question_id, 'test_id'=>$test_id) );
		if ($query->num_rows() > 0)	{
			return  true;
		} else {
			return false;
		}
	}
	

	// returns an array of questions added in to a test
	public function getTestQuestions ($coaching_id=0, $test_id=0, $parent_id=0) {
		$this->db->select('CQ.*');
		$this->db->from('coaching_test_questions CTQ');
		$this->db->join('coaching_questions CQ', 'CTQ.question_id=CQ.question_id');
		$this->db->where ('CTQ.coaching_id', $coaching_id);
		$this->db->where ('CTQ.test_id', $test_id);
		if ($parent_id > 0) {
			$this->db->where ('CQ.parent_id', $parent_id);
		}
		$query = $this->db->get ();
		return $query->result_array();
	}	
	
	
	// gives total marks of the added questions in a test
	public function getTestQuestionMarks ($coaching_id=0, $test_id=0) {
		$marks = 0;
		$data = [];
		$questions = $this->getTestQuestions ($coaching_id, $test_id);
		//print_r ($questions);
		if ( ! empty ($questions) ) {
			foreach ($questions as $row) {
				$data[] = $row['question_id'];
			}
			$this->db->select_sum ('marks');
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where_in( 'question_id', $data);
			$query = $this->db->get( 'coaching_questions');
			$result = $query->row_array ();
			$marks = $result['marks'];		
		}

		return $marks;
	}


	// gives total time of added questions 
	public function getTestQuestionTime( $coaching_id, $questions ) {
		$this->db->select_sum('time');
		$this->db->where_in( 'question_id', $questions);
		$this->db->where ('coaching_id', $coaching_id);
		$query = $this->db->get( 'coaching_questions');
		foreach ( $query->result() as $row ) {
			return $row->time;
		}
	}
	
	
	public function finaliseTest($coaching_id=0, $test_id=0, $marks=0) {
		$time = $this->input->post ('set_exam_time');
		if ($time == 0) {
			$update =  array ('finalized'=>1, 'max_marks'=>$marks);
		} else {
			$t = date("H:i", mktime(0,0,$time,0,0,0));
			list ($h, $m) = explode (':', $t);
			$update =  array ('finalized'=>1, 'max_marks'=>$marks, 'time_hour'=>$h, 'time_min'=>$m);
		}
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->where('test_id', $test_id);  
		return $this->db->update('coaching_tests', $update); 
	}
	
	public function unfinaliseTest($coaching_id=0, $test_id) {
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where('test_id', $test_id);  
		return $this->db->update('coaching_tests', array ('finalized'=>0)); 
	}
	
	
	public function resetTest($coaching_id=0, $test_id=0) {
		// delete all questions from test
		$this->db->where ('coaching_id', $coaching_id);		
		$this->db->where ('test_id', $test_id);		
		$this->db->delete('coaching_test_questions'); 

		// set status as unfinalized
		$this->db->where ('coaching_id', $coaching_id);		
		$this->db->where('test_id', $test_id);
		return $this->db->update ('coaching_tests', array ('finalized'=>0)); 	
	}
	
	// delete a question from test
	public function deleteTestQuestion($test_id=0, $id=0) {
		// delete all questions from test
		$this->db->delete('coaching_test_questions', array ('test_id'=>$test_id, 'question_id'=>$id)); 
	}
		
	
	// Model for enroling member	
	public function enrol_member ($coaching_id=0, $member_id=0, $test_id=0) {

		$attempts 	= $this->input->post ('num_takes');

		$start_date = $this->input->post ('start_date');
		list ($sy, $sm, $sd) = explode ("-", $start_date);
		$shh = $this->input->post ('start_time_hh');
		$smm = $this->input->post ('start_time_mm');
		$start_date = mktime ($shh, $smm, 0, $sm, $sd, $sy);

		$end_date = $this->input->post ('end_date');
		list ($ey, $em, $ed) = explode ("-", $end_date);
		$ehh = $this->input->post ('end_time_hh');
		$emm = $this->input->post ('end_time_mm');
		$end_date = mktime ($ehh, $emm, 0, $em, $ed, $ey);
		
		$result_release = $this->input->post ('result_release');
		$extra_time = intval ($this->input->post ('extra_time'));
		
		
		$data = array (	
						'start_date'=>	$start_date,
						'end_date'	=>	$end_date,
						'release_result' => $result_release,
						'attempts' => $attempts,
						'extra_time' => $extra_time,
					 );
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('coaching_test_enrolments');
		if ($sql->num_rows () == 0 ) {
			$data['member_id']	= $member_id;
			$data['test_id']	= $test_id;
			$data['coaching_id']	= $coaching_id;
			$this->db->insert('coaching_test_enrolments', $data);
		} else {
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('test_id', $test_id);
			$this->db->where ('member_id', $member_id);
			$this->db->update ('coaching_test_enrolments', $data);
		}
	}
	
	// Get tests in which a user is enroled
	public function enroled_in_tests ($member_id) {
		$q = $this->db->get_where ("coaching_test_enrolments", array ("member_id"=>$member_id));
		if ($q->num_rows () > 0 ) {
			return $q->result_array();
		} else {
			return false;
		}
	}
	

	// Model for listing enrolled member
	public function enrolled_member ($test_id, $member_id) {
		$query = $this->db->get_where ("coaching_test_enrolments", array ('test_id'=>$test_id, 'member_id'=>$member_id));
		if ($query->num_rows() > 0)	{
			return  true;
		} else {
			return false;
		}	
	}
	
	// Model for listing enrolled member
	public function enrolled_student ($test_id) {
		$this->db->select ("member_id, test_id");
		$query = $this->db->get_where ("coaching_test_enrolments", array ("test_id"=>$test_id));
		if ($query->num_rows() > 0)	{
			return  $query->result_array();
		} else {
			return false;
		}
	
	}

	
	//Model for unenroll the enrolled member
	public function unenrol_member($coaching_id, $member_id, $test_id){
		$this->db->where('member_id', $member_id);
		$this->db->where('test_id', $test_id);
		$this->db->where('coaching_id', $coaching_id);
		return $this->db->delete('coaching_test_enrolments'); 	
	}
	
	
	
	// Model for get details of enrolled member (Such as Date & Time)	
	public function getDate($member_id, $test_id) {	
		$query = $this->db->get_where ("coaching_test_enrolments", array ('test_id'=>$test_id, 'member_id'=>$member_id));
		if ($query->num_rows () > 0) {
			return  $query->row_array ();			
		} else {
			return false; 
		}
	}
	
	public function getAutoQuestions ($lesson_ids) {
		$lesson_ids = implode(',', $lesson_ids);
		$this->db->select ("question_id");
		$query = $this->db->where_in ("chapter_id", $lesson_ids);
		$query = $this->db->where ("parent_id >", 0);
		$this->db->where ('coaching_id', $coaching_id);
		
		$query = $this->db->get ('coaching_questions');
		if ($query->num_rows() > 0 ) {
			return $query->result_array();
		}
	}
	
	public function get_auto_questions ($lessons) { 
		$categories = $this->input->post ('categories');
		$difficulties = $this->input->post ('difficulties');
		$questions = array ();

		// Categories
		if ( ! empty ($categories)) {
			foreach ($categories as $cat_id=>$num) {
				$output = array ();
				$result = array ();
				$this->db->select ('question_id');
				$this->db->where ('category_id', $cat_id);
				$this->db->where ('parent_id >', 0);
				$this->db->where ('coaching_id', $coaching_id);
				
				$this->db->where_in ('chapter_id', $lessons);
				$sql = $this->db->get ('coaching_questions');
				if ($sql->num_rows () > 0) {
					foreach ($sql->result_array () as $row) {
						$result[] = $row['question_id'];
					}
				}
				shuffle ($result);
				if ($num == '-1') {
					$output = $result;
				} else if ($num == 0) {
					$output = array ();
				} else{
					$output = array_slice ($result, 0, $num);
				}
				$questions = array_merge ($questions, $output);
			}
		}
		
		// Difficulties
		if ( ! empty ($difficulties)) {
			foreach ($difficulties as $cat_id=>$num) {
				$output = array ();
				$result = array ();
				$this->db->select ('question_id');
				$this->db->where ('clsf_id', $cat_id);
				$this->db->where ('parent_id >', 0);
				$this->db->where ('coaching_id', $coaching_id);
				
				$this->db->where_in ('chapter_id', $lessons);
				$sql = $this->db->get ('coaching_questions');
				if ($sql->num_rows () > 0) {
					foreach ($sql->result_array () as $row) {
						$result[] = $row['question_id'];
					}
				}
				shuffle ($result);
				if ($num == '-1') {
					$output = $result;
				} else if ($num == 0) {
					$output = array ();
				} else{
					$output = array_slice ($result, 0, $num);
				}
				$questions = array_merge ($questions, $output);
			}
		}
		return $questions;
	}
	
	// get all enroled users in a test
	public function getMarks ($id=0) {
		$this->db->select('marks');
		$this->db->where('question_id', $id);
		$query = $this->db->get('coaching_questions');
		return $query->row('marks');
	}

	// get all enroled users in a test
	public function get_enrolment ($test_id) {		
		$this->db->where ('test_id', $test_id);
		$query = $this->db->get ('coaching_test_enrolments');
		if ($query->num_rows() > 0)	{
			$results = $query->result_array();			
			return $results;
		} else {
			// no members enroled
			return 0;
		}
	}
	
	
	// get all enroled users in a test
	public function get_users_in_test ($coaching_id=0, $test_id=0, $role_id=0, $class_id=0, $category_id=0, $batch_id=0, $status='-1') {

		$prefix = $this->db->dbprefix; 

  		$query = 'SELECT M.*, SR.description, TE.attempts, TE.start_date, TE.end_date FROM '.$prefix.'members M, '.$prefix.'coaching_test_enrolments TE, '.$prefix.'sys_roles SR ';
		if ($batch_id > 0) {
		    $query .= ', '.$prefix.'coaching_batch_users BU';
		}
  		$query .= ' WHERE TE.coaching_id='.$coaching_id.' AND TE.test_id='.$test_id.' AND SR.role_id=M.role_id';
		if ($batch_id > 0) {
		    $query .= ' AND M.member_id=BU.member_id AND BU.batch_id='.$batch_id;
		}
		if ($class_id > 0) {
		    $query .= ' AND M.class_id='.$class_id;
		}
		if ($status > '-1') {
		    $query .= ' AND M.status='.$status;
		}
		if ($role_id > 0) {
		    $query .= ' AND M.role_id='.$role_id;
		}
		
		$query .= ' AND TE.member_id=M.member_id AND TE.archived=0';
		
		$sql = $this->db->query ($query);
		return $sql->result_array ();
	}
	
	public function get_users_not_in_test ( $coaching_id=0, $test_id=0, $role_id=0, $class_id=0, $category_id=0, $batch_id=0, $status='-1') {

		$prefix = $this->db->dbprefix; 
		$query = 'SELECT M.*, SR.description FROM '.$prefix.'sys_roles SR, '.$prefix.'members M ';
		if ($batch_id > 0) {
		    $query .= ' INNER JOIN '.$prefix.'coaching_batch_users BU ON M.member_id=BU.member_id';
		}
		$query .= ' WHERE M.coaching_id='.$coaching_id;
		$query .= ' AND M.role_id=SR.role_id';
		if ($batch_id > 0) {
		    $query .= ' AND BU.batch_id='.$batch_id;
		}
		if ($class_id > 0) {
		    $query .= ' AND M.class_id='.$class_id;
		}
		if ($status > '-1') {
		    $query .= ' AND M.status='.$status;
		}
		if ($role_id > 0) {
		    $query .= ' AND M.role_id='.$role_id;
		}
		
		$query .= ' AND M.member_id NOT IN (SELECT TE.member_id FROM '.$prefix.'coaching_test_enrolments TE WHERE TE.test_id='.$test_id.') ';
        $sql = $this->db->query ($query);
		//echo $this->db->last_query ();
        return $sql->result_array (); 
	}
	
	// get all enroled users in a test
	public function get_archived_students ($coaching_id=0, $test_id=0, $role_id=0, $class_id=0, $category_id=0, $batch_id=0, $status='-1') {		
		
		$prefix = $this->db->dbprefix; 
		
  		$query = 'SELECT M.* FROM '.$prefix.'members M, '.$prefix.'coaching_test_enrolments TE';
		if ($batch_id > 0) {
		    $query .= ', '.$prefix.'coaching_batch_users BU';
		}
		$query .= ' WHERE M.coaching_id='.$coaching_id;
		if ($batch_id > 0) {
		    $query .= ' AND M.member_id=BU.member_id AND BU.batch_id='.$batch_id;
		}
		if ($class_id > 0) {
		    $query .= ' AND M.class_id='.$class_id;
		}
		if ($status > '-1') {
		    $query .= ' AND M.status='.$status;
		}
		if ($role_id > 0) {
		    $query .= ' AND M.role_id='.$role_id;
		}
		
		$query .= ' AND TE.member_id=M.member_id AND TE.archived=1';
		
		$sql = $this->db->query ($query);
        return $sql->result_array ();
	}

	// archive test users 
	public function archive_member ($test_id, $member_id) {
		$this->db->set ('archived', 1);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('test_id', $test_id);
		$this->db->update ('coaching_test_enrolments');
	}
	
	
	public function get_parent_question($qid) {
		$this->db->select("parent_id");
		$this->db->where("question_id", $qid);
		$this->db->where ('coaching_id', $coaching_id);
		
		$query = $this->db->get('coaching_questions');
		if($query->num_rows()>0){
			$results = $query->row_array();
			return $results['parent_id'];
		} else {
			return false;
		}
	}
	
	public function all_question_group ($test_id) {
		$this->db->where ("test_id", $test_id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$query = $this->db->get("coaching_test_questions");
		if ($query->num_rows() > 0) {
			$results = $query->result_array();
			return $results;
		} else {
			return false;
		}
	}
	
	
	//=========== Model for Delete lesson =====================
	public function delete_test_subject ($id) {
		
		//get all tests in this category
		$tests = $this->list_test (1, $id);
		if  ( is_array ($tests)) {
			foreach ($tests as $test) {
				$tid = $test['test_id'];
				$this->delete_tests ($tid);
			}
		}		
		// delete subject
		$this->db->where('id', $id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->delete('coaching_test_categories');	
		
	}
	
	
	// returns an array of questions added in to the test
	public function test_category_name ($subject_id) {
		$test_subject = $this->test_subject_details ($subject_id);
		$subject = $test_subject['title'];
		$test_category = $this->get_test_categories ($test_subject['parent_id']);
		$category = $test_category['title'];
		$result = array ('category'=>$category, 'subject'=>$subject);
		return $result;
	}

	public function get_monthly_test ($year, $month) {

		// Last day of given month
		$str_start = $year . '-' . $month . '-01';		
		$last_day = date ('t', strtotime ($str_start));
		
		$plan_start_date = mktime (0,0,0, $month, 1, $year);
		$plan_end_date   = mktime (0,0,0, $month, $last_day, $year);
		
		//$this->db->where ('start_date >=', $plan_start_date);
		//$this->db->where ('end_date <=', $plan_end_date);	
	
		$this->db->group_by ('start_date');
		$sql = $this->db->get ('coaching_test_enrolments');
		if ($sql->num_rows () > 0 ) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
	// tests taken within range
	public function test_taken_between ($start_date=0, $end_date=0) {
		
		$result = array ();		
		
		// current timestamp
		$today = time ();
		if ($start_date == 0) {
			// 7 days tests from today
			$start_date = mktime (0, 0, 0, date ('m'), date ('d')-7, date ('Y'));
		}
		if ($end_date == 0) {
			$end_date = mktime (0, 0, 0, date ('m'), date ('d'), date ('Y'));
		}
		
		$this->db->where ('loggedon >= ', $start_date );
		$this->db->where ('loggedon <= ',  $end_date);
		$this->db->group_by ('test_id');
		$this->db->where ('coaching_id', $coaching_id);
		
		$sql = $this->db->get ('coaching_test_attempts');
		$count = $sql->num_rows ();
		$result[$end_date] = $count;
		
	}
	
	/* Active Tests */
	public function count_active_tests () { 
		$now = time ();		
		$this->db->group_by ('test_id');
		$this->db->where ('start_date <', $now );
		$this->db->where ('end_date >', $now );
		$query = $this->db->get ('coaching_test_enrolments');
		if ($query->num_rows() > 0 ) {
			$result = $this->db->count_all_results ();
			return $result;
		} else {
			return 0;
		}
	}
	
	public function get_all_active_tests () { 
		$now = time ();		
		$this->db->group_by ('test_id');
		$this->db->where ('start_date <', $now );
		$this->db->where ('end_date >', $now );
		$query = $this->db->get ('coaching_test_enrolments');
		if ($query->num_rows() > 0 ) {
			$result = $query->result_array ();
			return $result;
		} else {
			return false;
		}
	}
	
	/* Published Tests */
	public function published_tests () {		
		$this->db->where ('finalized', 1);
		$sql = $this->db->get ('coaching_tests');
		if ($sql->num_rows () > 0) {
			$result = $sql->result_array ();
			return $result;
		} else {
			return false;
		}
	}

	/* UnPublished Tests */
	public function unpublished_tests () {
		$this->db->where ('finalized', 0);
		$sql = $this->db->get ('coaching_tests');
		if ($sql->num_rows () > 0) {
			$result = $sql->result_array ();
			return $result;
		} else {
			return false;
		}
	}
	
		//=========== Model for list tests =======================
	public function get_related_tests ($test_type=TEST_TYPE_PUBLIC) { 
		$results = array (); 
		$this->db->where ('test_type', $test_type);
		$this->db->where ('finalized', 1);
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->order_by ('RAND()', 'ASC');
		$query = $this->db->get ("coaching_tests");
		if ($query->num_rows() > 0)	{
			// prepare the array with key value to be displayed
			foreach ($query->result_array() as $row) {
				$results[] = $row['test_id'];
			}
			return $results;
		} else {
			return false;
		}
	}
	
	
	public function testTakenBy ($test_id) {
		$this->db->where("test_id", $test_id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$query = $this->db->get("coaching_test_attempts");
		return $this->db->count_all_results();
	}
	

	public function page_stats ($member_id=0) { 

		// My Subscriptions 
		$data['subscriptions'] = $subscriptions = $this->tests_model->category_subscriptions ($member_id);
		$uri = $this->uri->segment (3);
		$role_id = $this->session->userdata ('role_id');

		$this->load->model ('tests_reports');
		
		if ($role_id == USER_ROLE_STUDENT ) {			
			if ($subscriptions == false && $uri != 'category_subscriptions') {
				//$this->message->set ('You have not subscribed to any test categories. Subscribe categories you are interested in before continuing', 'warning', true);
				//redirect ('tests/page/category_subscriptions/'.$member_id);
			}
		}
		
		if ( ! empty ($subscriptions) ) {
			$num_subscriptions = count ($subscriptions);
		} else {
			$num_subscriptions = 0;
		}
		
		// My Tests 
		$data['my_tests'] = $my_tests = $this->tests_reports->test_taken_by_member ($member_id);
		$om = array (0);
		$max_om = 0;
		if ( ! empty ($my_tests) ) {
			foreach ($my_tests as $test) {
				//$om[] = $this->tests_reports_model->obtained_marks ($test['test_id'], $test['id'], $member_id);
			}
			$max_om = max ($om);
			$num_my_tests = count ($my_tests);
		} else {
			$num_my_tests = 0;
		}
		
		// Last Login 
		$login = $this->common_model->user_details ($member_id);
		if ( ! empty ($last_login) ) {
			$last_login = date ('d-m-Y', $login['last_activity']);
		} else {
			$last_login = date ('d-m-Y');
		}
		
		$output = array ();
		$output['page_stats']['pst1'] = 'My Subscriptions';
		$output['page_stats']['psf1'] = $num_subscriptions;
		$output['page_stats']['pst2'] = 'Tests Taken';
		$output['page_stats']['psf2'] = $num_my_tests;
		//$output['page_stats'][] = array ('pst2'=>'My Subscriptions', 'psf2'=>$num_subscriptions);
		//$output['page_stats'][] = array ('pst3'=>'My Subscriptions', 'psf3'=>$num_subscriptions);
		//$output['page_stats'] = array ('My Subscriptions'=>$num_subscriptions, 'Tests Attempted'=>$num_my_tests, 'Maximum Score'=>$max_om, 'Last Login'=>$last_login);
		return $output;

	}
	
	public function tests_in_category ($coaching_id, $category_id, $type=0, $status='') {
		$ids = array ();
		$categories = $this->common_model->all_children ($category_id, SYS_TREE_TYPE_TEST);
		if ($type > 0) {
			$this->db->where ("test_type", $type);			
		}
		if ($status != '') {
			$this->db->where ("finalized", $status);			
		}
		
		if ( ! empty ($categories)) {		
			$this->db->where_in ("category_id", $categories); 
		} else {
			$this->db->where ("category_id", $category_id);
		}
		$this->db->where ('coaching_id', $coaching_id);
		//
		$query = $this->db->get("coaching_tests");
		return $query->result_array ();
	}
	
	// Tests created by a a user
	public function tests_created ($member_id) {
		$this->db->where ('created_by', $member_id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$sql = $this->db->get ('coaching_tests');
		if ($sql->num_rows () > 0 ) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
	
	public function ofline_tests_not_marked () {
		$tests = array ();
		$marked = array ();
		$notmarked = array ();
		$this->db->select ('test_id');
		$this->db->where ('test_type', TEST_MODE_OFLINE);
		$this->db->where ('finalized', 1);
		$sql = $this->db->get ('coaching_tests');
		if ($sql->num_rows () > 0 ) {
			foreach ($sql->result_array() as $row) {
				$tests[] = $row['test_id'];
			}
			$this->db->select ('test_id');
			$this->db->group_by ('test_id');
			$query = $this->db->get ('test_answers_offline');
			if ($query->num_rows () > 0 ) {
				foreach ($query->result_array() as $row) {
					$marked[] = $row['test_id'];
				}
			}			
		}
		
		$notmarked = array_diff ($tests, $marked);
		
		return $notmarked;
	}
		
	
	//=========== Model for list tests =======================
	public function get_enroled_tests ($coaching_id=0, $member_id=0, $test_type=TEST_TYPE_REGULAR) {
		$this->db->select ('T.*, TE.start_date, TE.end_date, TE.attempts');
		$this->db->from ('coaching_tests T');
		$this->db->join ('coaching_test_enrolments TE', 'T.test_id=TE.test_id');
		$this->db->where ('T.finalized', 1);
		$this->db->where ('T.test_type', $test_type);
		$this->db->where ('T.coaching_id', $coaching_id);
		$this->db->where ('TE.member_id', $member_id);
		$sql = $this->db->get ();
		return $sql->result_array ();
	}

	public function _get_enrolled_test ($coaching_id=0, $member_id=0, $test_type=TEST_TYPE_REGULAR, $filter=TEST_TYPE_ONGOING) {		
		
		$results = array ();
		
		if ($test_type == TEST_TYPE_REGULAR) {
			// get enrolment
			$enroled = $this->user_enroled_in_tests ($member_id, $test_type, $filter);
			if ( $enroled == false) {
				return false;
				exit;
			} else {
				foreach ($enroled as $item) {
					$row = $this->tests_model->view_tests ($item['test_id']);
					$res = array_merge ($row, $item);
					$results[$row['test_id']] = $res;
				}
				return array_combine(range(0, (count($results)-1)), array_values($results));
				exit;
			}
		}
		
		if ($test_type > 0) {
			$this->db->where ('test_type', $test_type);
		}
		
		$this->db->where ('coaching_id', $coaching_id);		
		$this->db->where ("finalized", 1);
		$query = $this->db->get ("coaching_tests");
		if ($query->num_rows() > 0)	{
			// prepare the array with key value to be displayed
			foreach ($query->result_array() as $row) {
				$results[ $row['test_id'] ] = $row;
			}
			return $results;
		} else {
			return false;
		}
	}
	
	// Model for get details of enrolled member(Such as Date & Time)	
	public function user_enroled_in_tests ($member_id, $test_type=0, $filter=0) {
		$time = time ();
		if ($filter == TEST_TYPE_ONGOING) {
			$this->db->where ('start_date <=', $time); 
			$this->db->where ('end_date >', $time);
		} else if ($filter == TEST_TYPE_UPCOMING) {
			$this->db->where ('start_date >', $time);
			$this->db->where ('end_date >', $time);
		} else if ($filter == TEST_TYPE_PREVIOUS) {
			$this->db->where ('start_date <', $time);
			$this->db->where ('end_date <', $time);
		}
		$this->db->where (array ('member_id'=>$member_id));
		$query = $this->db->get_where ("coaching_test_enrolments");
		if ($query->num_rows () > 0 ) {
			return  $query->result_array();			
		} else {
			return false;
		}
	}

	//check number of attempts of a test
	public function check_attempt ($coaching_id, $tid, $member_id) {
		$this->db->where ("test_id", $tid);
		$this->db->where ("member_id", $member_id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$this->db->from ("coaching_test_attempts");		
		return $this->db->count_all_results ();
	}
	
	//check for valid test session
	public function check_session ($coaching_id, $tid, $member_id) {
		$this->db->select_max ("loggedon");
		$this->db->where ("test_id", $tid);
		$this->db->where ("member_id", $member_id);
		$this->db->where ('coaching_id', $coaching_id);
		
		$sql = $this->db->get ("coaching_test_attempts");		
		
		if ($sql->num_rows () > 0) {
			$result = $sql->row();
			return $result->loggedon;
		} else {
			return 0;
		}
	}
	
	//insert the time stamp when student starts for test
	public function	save_attempt ($coaching_id, $tid, $member_id, $time){
		$data = array(
					   'test_id' 	=> $tid ,
					   'member_id' 	=> $member_id,
					   'loggedon'	=>	$time,
					   'coaching_id'	=>	$coaching_id,
					   'plan_id'	=>	intval ($this->session->userdata ('plan_id')),
					   'obtained'	=>	0
					);
		$this->db->insert('coaching_test_attempts', $data);		
		return $this->db->insert_id();
	}
	
	
	//model for get how many attempts done by a student.
	public function get_attempts ($member_id, $tid) {
		$this->db->where ("test_id", $tid);
		$this->db->order_by ("loggedon", 'DESC');
		$this->db->where ("member_id", $member_id);		
		$sql = $this->db->get ("coaching_test_attempts");
		if ($sql->num_rows () > 0 ) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}

	//All tests taken by a user
	public function test_taken_by_member ($member_id=0, $attempt=0) {
		$this->db->select ('T.*, TA.loggedon, TA.id AS attempt_id');
		$this->db->from ('coaching_test_attempts TA');
		$this->db->join ('coaching_tests T', 'T.test_id=TA.test_id');
		$this->db->where ('TA.member_id', $member_id);
		$this->db->order_by ('TA.loggedon', 'DESC');
		$this->db->group_by ('TA.test_id');
		$sql = $this->db->get ();
		if ($sql->num_rows () > 0 ) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
	//Insert the answer of a student given by a specific student
	public function insert_answers ($member_id, $test_id, $qid, $answer) {
		$question = $this->qb_model->getQuestionDetails ($qid);
		$type = $question['type'];
		$data = array(
					   'attempt_id' => $this->input->post('attempt_id'),
					   'test_id' 	=> $test_id ,
					   'question_id'=> $qid,
					   'member_id' 	=> $member_id
					);
		//save answer for Question type Multiple choice single correct			
		if($type == QUESTION_MCSC) {
			for($i=1; $i<=6; $i++){
				if($i == $answer){
					$data['answer_'.$i] = $answer;	
				}	
			}
		}
		//save answer for Question type Long answers
		if($type == QUESTION_LONG) {
			$data['answer_1'] = $answer;	
		}
		//save answer for Question type Multiple choice multi correct
		if( $type == QUESTION_MCMC ) {
			for($i=1; $i<=6; $i++) {
				if( isset ($answer[$i]) ) {
					$data['answer_'.$i] = $answer[$i];
				}
			}
		}
		//save answer for Question type True False
		if($type == QUESTION_TF){
			$data['answer_1'] = $answer;	
		}
		//save answer for Question type MATCH THE FOLLOWING
		if($type == QUESTION_MATCH) {
			for ($i=1; $i<=6; $i++) {
				$data['answer_'.$i] = $answer[$i];	
			}			
		}
		
		$this->db->insert('coaching_test_answers', $data); 
	}	
	
	
	/* SUBSCRIPTION */
	// Category Subscription
	public function subscribe_category ($category_id, $plan_id, $member_id) {
		
		$data = array ('member_id'=>$member_id, 'plan_id'=>intval ($this->session->userdata ('plan_id')), 'category_id'=>$category_id);
		$plan_details = $this->get_plan (intval ($this->session->userdata ('plan_id')));
		$duration = $plan_details['duration']; 		// in months
		$data['price'] = $plan_details['price'];
		
		// Get current subscription details
		$this->db->where ('member_id', $member_id);
		$this->db->where ('category_id', $category_id);
		$query = $this->db->get ('tests_category_subscriptions');
		if ($query->num_rows() > 0) {
			$row = $query->row_array ();
			$et = $row['end_date'];
			if ($et < time ()) {
				// this subscription has expired, update new start and end-dates
				// Convert months to micro seconds (UNIX Timestamp)
				$st = time ();
				$end_ts = $duration * 30 * 24 * 3600;
				$et = $st + $end_ts;
			} else if ($et >= time()) {
				// this subscription is still active, add selected months to end date
				$st = $row['start_date'];
				$et = $row['end_date'];
				$end_ts = $duration * 30 * 24 * 3600;
				$et = $et + $end_ts;
			}
			$data['start_date'] = $st;
			$data['end_date'] = $et;

			$this->db->where ('category_id', $category_id);
			$this->db->where ('member_id', $member_id);
			$this->db->update ('tests_category_subscriptions', $data);
		} else {
			$st = time ();
			$end_ts = $duration * 30 * 24 * 3600;
			$et = $st + $end_ts;
			$data['start_date'] = $st;
			$data['end_date'] = $et;
			$this->db->insert ('tests_category_subscriptions', $data);
		}
		
	}
	 
	public function unsubscribe_category ($category_id=0, $member_id) {
		$data = array ('member_id'=>$member_id, 'category_id'=>$category_id);
		$sql = $this->db->get_where ('tests_category_subscriptions', $data);
		if ($sql->num_rows () > 0) {
			$this->db->where ($data);
			$this->db->delete ('tests_category_subscriptions');
			return '1';
		} else {
			return '0';
		}
	}
	
	public function check_category_subscription ($category_id, $member_id) {
		$now = time ();
		$this->db->where ('member_id', $member_id);
		$this->db->where ('category_id', $category_id);
		$this->db->where ('end_date >=', $now);
		$sql = $this->db->get ('tests_category_subscriptions');
		if ($sql->num_rows () > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_subscription_details ($category_id, $member_id) {
		$now = time ();
		$this->db->where ('member_id', $member_id);
		$this->db->where ('category_id', $category_id);
		$sql = $this->db->get ('tests_category_subscriptions');
		if ($sql->num_rows () > 0) {
			return $sql->row_array ();
		} else {
			return false;
		}
	}
	
	public function category_subscriptions ($member_id, $active=0) {
		$now = time ();
		$this->db->where ('member_id', $member_id);
		if ($active > 0) {
			$this->db->where ('end_date >=', $now);
		}
		$sql = $this->db->get_where ('tests_category_subscriptions');
		if ($sql->num_rows () > 0) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
	// get categories by level
	public function get_by_levels ($level=1, $parent_id=0, $status=0, $coaching_id=0, $plan_id=0) {
		$data = false;
		if ($parent_id > 0) {
			$this->db->where ('parent_id', $parent_id);			
		}
		if ($status > 0) {
			$this->db->where ('status', $status);
		}
		if ($coaching_id > 0) {
			$this->db->where ('coaching_id', $coaching_id);
		}
		if ($plan_id > 0) {
			$this->db->where ('plan_id', $plan_id);
		}
		$this->db->where ('level', $level);
		$this->db->order_by ('title', 'ASC');
		$sql = $this->db->get ('coaching_test_categories');
		return $sql->result_array ();
	}
	
	// get categories by master_id
	public function get_testcat_mid ($coaching_id=0, $plan_id=0, $master_id=0, $parent_id=0, $status=1) {
		$data = false;
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('plan_id', $plan_id);
		if ($master_id > 0) {
			$this->db->where ('master_id', $master_id);
		}
		$this->db->where ('parent_id', $parent_id);
		$this->db->where ('status', $status);
		$sql = $this->db->get ('coaching_test_categories');
		if ($sql->num_rows() > 0 ) {
			$data = $sql->row_array ();
		}
		return $data;
	}
	
	// Select test plans from a given category_id
	public function test_plans ($status='') {
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		$this->db->where ('plan_id <>', DEFAULT_COACHING_PLAN);
		$this->db->order_by ('creation_date', 'DESC');
		$sql = $this->db->get ('test_plans');
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
		$data['short_description'] 	= $this->input->post ('short_description');
		$data['description'] 		= $this->input->post ('description');
		$data['status'] 			= $this->input->post ('status');
		$data['duration'] 			= 0;
		$data['amount'] 			= $this->input->post ('price');
		$data['num_users'] 			= 0;

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
	

	public function plan_added_to_coaching ($plan_id=0, $coaching_id=0) {
		$this->db->where ('plan_id', $plan_id);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_plans');
		if ($sql->num_rows() > 0) {
			return true;
		} else {
			return false;
		}		
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

	public function tests_in_plan ($plan_id=0, $coaching_id=0) {
		$this->db->where ('plan_id', $plan_id);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_tests');
		return $sql->result_array ();
	}
	
	public function remove_plan ($plan_id) {
		$this->db->where ('plan_id', $plan_id);
		$this->db->delete ('test_plans');
	}
	
	
	// Select test plans from a given category_id
	public function get_test_cats ($its_category=array()) {
		
		// Create a new connection to MASTER DB
		$con['hostname'] = MASTER_DB_HOSTNAME;
		$con['username'] = MASTER_DB_USER;
		$con['password'] = MASTER_DB_PASSWORD;
		$con['database'] = MASTER_DB_NAME;
		$con['dbdriver'] = MASTER_DB_DRIVER;
		$con['dbprefix'] = MASTER_DB_PREFIX;

		if ($dbi = $this->load->database ($con, true)) {

			$dbi->where ('status', true);
			$dbi->where_in ('its_category', $its_category);
			$dbi->where ('level', TEST_LEVEL_EXAM);
			$dbi->order_by ('title', 'ASC');
			$sql = $dbi->get ('coaching_test_categories');
			if ($sql->num_rows () > 0 ) {
				$result = $sql->result_array ();
			} else {
				$result = false;
			}
			
		}
		
		return $result;
	}

	// Add ITS Categories to a plan
	public function add_its_cats ($plan_id=0) {

		// Get categories already added 
		$this->db->where ('plan_id', $plan_id);
		$sql = $this->db->get ('test_plans');
		$row = $sql->row_array ();
		$test_cats = $row['test_cat_id'];
		
		// User submitted categories
		$its_cats = $this->input->post ('its_cats');
		$cats = implode (',', $its_cats);
		
		// Join
		if ($test_cats != '') {
			$cats = $test_cats . ',' . $cats;
		}
		
		// Update
		$this->db->set ('test_cat_id', $cats);
		$this->db->where ('plan_id', $plan_id);
		$this->db->update ('test_plans');
	}
	
	// Remove ITS Categories to a plan
	public function remove_its_cat ($plan_id=0, $cat_id=0) {

		// Get categories already added 
		$test_cats = array ();
		$this->db->where ('plan_id', $plan_id);
		$sql = $this->db->get ('test_plans');
		$row = $sql->row_array ();
		if ($row['test_cat_id'] != '') {
			$test_cats = explode (',', $row['test_cat_id']);
		}
		
		// Remove
		$cats = array ();
		if ( ! empty($test_cats)) {
			foreach ($test_cats as $ct) {
				if ($ct <> $cat_id) {
					$cats[] = $ct;
				}
			}
		}
		$cats = implode (',', $cats);
		
		// Update
		$this->db->set ('test_cat_id', $cats);
		$this->db->where ('plan_id', $plan_id);
		$this->db->update ('test_plans');
	}
		

	public function topic_wise ($coaching_id, $test_id=0) {
		$tq = $this->getTestQuestions ($coaching_id, $test_id);
		//print_r ($tq);
	}

	/* Subscription Plans */
	public function subscription_plans ($status='') {
		if ($status != '') {
			$this->db->where ('status', $status);			
		}
		$this->db->order_by ('ordering', 'ASC');
		$sql = $this->db->get ('subscription_plans');
		return $sql->result_array ();
	}

	/* Subscription Plan */
	public function subscription_plan ($plan_id=0) {
		$this->db->where ('id', $plan_id);
		$sql = $this->db->get ('subscription_plans');
		return $sql->row_array ();
	}

	/* Create/Edit Subscription Plan */
	public function create_subscription_plan ($plan_id=0) {
		$data['title'] = $this->input->post ('title');
		$data['description'] = $this->input->post ('description');
		$data['duration'] = $this->input->post ('duration');
		$data['price'] = $this->input->post ('price');
		$data['max_users'] = $this->input->post ('max_users');
		$data['status'] = $this->input->post ('status');
		
		if ($plan_id > 0) {
			$this->db->where ('id', $plan_id);
			$this->db->update ('subscription_plans', $data);
		} else {
			$this->db->insert ('subscription_plans', $data);			
		}
	}

	/* Delete Subscription Plan */
	public function delete_subscription_plan ($plan_id=0) {
		$this->db->where ('id', $plan_id);
		$sql = $this->db->delete ('subscription_plans');
	}

	public function manage_test_toolbar ($coaching_id=0, $category_id=0, $test_id=0) {
		$toolbar['Prepare'] = [
			'<i class="fa fa-list"></i> Manage'=>'coaching/tests/manage/'.$coaching_id.'/'.$category_id.'/'.$test_id,
			'<i class="fa fa-search"></i> Preview'=>'coaching/tests/preview_test/'.$coaching_id.'/'.$category_id.'/'.$test_id,
			'<i class="fa fa-superscript"></i> Questions'=>'coaching/tests/preview_test/'.$coaching_id.'/'.$category_id.'/'.$test_id,
		];

		return $toolbar;
	}
	
}