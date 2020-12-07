<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coachings_model extends CI_Model {
	
	var $test_cats = array ();
	var $count_free_tests = 0;
	

	public function search_coaching () {		

		$this->db->select ('C.*, CS.starting_from, CS.ending_on, CS.created_by, SP.title');
		$this->db->from ('coachings C, coaching_subscriptions CS, subscription_plans SP');
		if ($this->input->post ('search_text')) {
			$search_text = $this->input->post ('search_text');
			$this->db->like ('C.coaching_name', $search_text);
			$this->db->or_like ('C.reg_no', $search_text);			
		}
		$this->db->where('CS.coaching_id=C.id');
		$this->db->where('SP.id=CS.plan_id');		
		$this->db->order_by ('creation_date', 'DESC');
		$sql = $this->db->get ();
		return $sql->result_array ();
	}

	public function get_all_coachings ($status='') {
		$this->db->select ('C.*, CS.starting_from, CS.ending_on, CS.created_by, SP.title');
		$this->db->from ('coachings C, coaching_subscriptions CS, subscription_plans SP');
		if ($status != '') {
			$this->db->where ('C.status', $status);
		}
		$this->db->order_by ('creation_date', 'DESC');
		$this->db->where('CS.coaching_id=C.id');
		$this->db->where('SP.id=CS.plan_id');
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
			return $result;
		} else { 
			return false;
		}
	}

	
	public function get_coaching ($coaching_id=0) {
		$this->db->from ('coachings');
		$this->db->where ('id', $coaching_id);
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function get_coaching_subscription ($coaching_id=0) {
		$this->db->select ('C.*, CS.starting_from, CS.ending_on, CS.status AS plan_status, CS.created_by, SP.*, SP.id AS sp_id');
		$this->db->from ('coachings C, coaching_subscriptions CS, subscription_plans SP');
		$this->db->where('CS.coaching_id=C.id');
		$this->db->where('SP.id=CS.plan_id');
		$this->db->where ('CS.id', $coaching_id);
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function get_coaching_by_slug ($coaching_slug='') {
		$this->db->where ('coaching_url', $coaching_slug);
		$this->db->or_where ('reg_no', $coaching_slug);
		$this->db->from ('coachings');
		$sql = $this->db->get ();
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else {
			return false;
		}
	}

	public function get_coaching_by_uid ($coaching_uid=0) {
		$this->db->where ('reg_no', $coaching_uid);
		$sql = $this->db->get ('coachings');
		if  ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ();
			return $result;
		} else { 
			return false;
		}
	}

	public function get_coaching_tests ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_tests');
		$result = $sql->result_array ();
		return $result;
	}

	public function get_coaching_users ($coaching_id=0) {
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('members');
		$result = $sql->result_array ();
		return $result;
	}

	public function coaching_plans ($coaching_id=0, $plan_id=0, $status='') {
		$this->db->select ('CP.*, TP.*');
		$this->db->from ('coaching_test_plans AS CP');
		$this->db->where ('CP.coaching_id', $coaching_id);
		if ($plan_id > 0) {
			$this->db->where ('CP.plan_id', $plan_id);
		}
		if ($status != '') {
			$this->db->where ('CP.status', $status);
		}
		$this->db->join ('test_plans AS TP', 'TP.plan_id = CP.plan_id');
		$sql = $this->db->get ();
		if ($plan_id > 0) {
			$result = $sql->row_array ();
		} else {
			$result = $sql->result_array ();
		}
		return $result;
		
	}
	
	
	
	public function check_unique ($str='', $type='reg_no', $coaching_id=0) {
		$this->db->where ($type, $str);
		if ($coaching_id > 0) {
			$this->db->where ('id <>', $coaching_id);			
		}
		$sql = $this->db->get ('coachings');
		if  ($sql->num_rows () > 0 ) {
			return true;
		} else {
			return false;
		}		
	}		
	
	
	// Save account
	public function coaching_signup ($coaching_id=0) {
		$coaching = [];
		
		$coaching['coaching_name'] = $this->input->post ('coaching_name');
		$coaching['coaching_url'] = str_replace ('-', '_', $this->input->post ('coaching_url'));
		$first_name = $this->input->post ('first_name');
		$last_name  = $this->input->post ('last_name');
		$coaching['owner_name'] 		= $first_name . ' ' . $last_name;
		$coaching['contact']	= $this->input->post ('primary_contact');
		$coaching['email'] 		= $this->input->post ('email');
		$coaching['doj']		= time ();
		$coaching['doe']		= time () + (30 * 24 * 3600);		// 30 days from now
		$coaching['subscription_type'] = FREE_SUBSCRIPTION_PLAN_ID;
		$coaching['subscription_status'] = SUBSCRIPTION_STATUS_ACTIVE;
		$coaching['status']   	= COACHING_ACCOUNT_ACTIVE;
		$coaching['creation_date'] = time ();
		$coaching['created_by'] = 0;
		
		$this->db->insert ('coachings', $coaching);
		$coaching_id = $this->db->insert_id ();
			
		// Set Reference-id
		$reg_no = $this->generate_coaching_id ($coaching_id);
		$this->db->set ('reg_no', $reg_no);
		$this->db->where ('id', $coaching_id);
		$this->db->update ('coachings'); 

		$user = [];
		
		$user['login'] 		= $this->input->post ('username');
		$password 			= $this->input->post ('password');
		$user['password'] 	= password_hash($password, PASSWORD_DEFAULT);
		$user['first_name'] = $first_name;
		$user['last_name']  = $last_name;
		$user['role_id'] 	= USER_ROLE_COACHING_ADMIN;
		$user['coaching_id']= $coaching_id;
		$user['email'] 		= $this->input->post ('email');
		$user['primary_contact'] 	= $this->input->post ('primary_contact');
		$user['status']  		= USER_STATUS_ENABLED;
		$user['created_by']  	= 0;
		$user['creation_date'] 	= time ();			
		$this->create_user ($user);

	}
	
	public function create_coaching_account ($coaching_id=0) {
		
		$website	= $this->input->post ('website');
		if ($this->input->post ('doj')) {
			$doj	= $this->input->post ('doj');
		} else {	
			$doj 	= date ('d/m/Y');		// Take today's date
		}
		list ($sd, $sm, $sy) = explode ('/', $doj);
		$start_date	= mktime (0, 0, 0, $sm, $sd, $sy);

		$data = array (
				'coaching_name'=>	$this->input->post ('coaching_name'), 
				'address'=>			$this->input->post ('address'), 
				'state'=>			$this->input->post ('state'), 
				'city'=>			$this->input->post ('city'), 
				'pin'=>				$this->input->post ('pin'), 
				'website'=> 		prep_url ($website),
				'doj'=> 			$start_date,
			);

		if ($coaching_id > 0) {
			// update account
			$this->db->where ('id', $coaching_id);
			$this->db->update ('coachings', $data);
		} else {
			// Create Coaching Account
			$data['sr_no']		=	0;
    		//$data['subscription_type'] = 0;
    		//$data['subscription_status'] = 0;
		    $data['status']   	= COACHING_ACCOUNT_ACTIVE;
		    $data['creation_date'] = time ();
			$data['created_by'] = intval ($this->session->userdata ('member_id'));
			$this->db->insert ('coachings', $data);
			$coaching_id = $this->db->insert_id ();
			
			// Add free subscription plan
			$plan['created_by'] = intval ($this->session->userdata ('member_id'));
			$plan['plan_id']	= FREE_SUBSCRIPTION_PLAN_ID;
			$plan['coaching_id']= $coaching_id;
			$plan['starting_from']= time ();
			$plan['ending_on']	= time () + (3 * 24 * 3600);
			$this->db->insert ('coaching_subscriptions', $plan);

			// Set Reference-id
			$reg_no = $this->generate_coaching_id ($coaching_id);
			$this->db->set ('reg_no', $reg_no);
			$this->db->where ('id', $coaching_id);
			$this->db->update ('coachings');
			
			// Create coaching admin
			$user = [];
    		$user['login'] 		= $this->input->post ('email');
    		$password 			= random_string('alnum', 8);;
    		$user['password'] 	= password_hash($password, PASSWORD_DEFAULT);
    		$user['first_name'] = $this->input->post ('first_name');
    		$user['last_name']  = $this->input->post ('last_name');
    		$user['role_id'] 	= USER_ROLE_COACHING_ADMIN;
    		$user['coaching_id']= $coaching_id;
    		$user['email'] 		= $this->input->post ('email');
    		$user['primary_contact'] 	= $this->input->post ('primary_contact');
    		$user['status']  	= USER_STATUS_ENABLED;
    		$user['created_by'] = intval ($this->session->userdata ('member_id'));
    		$user['creation_date'] 	= time ();			
    		$this->create_user ($user);
		}
		
		return $coaching_id;
	}
	
	

	// Save Settings
	public function save_settings ($coaching_id=0) {		
		
		$data = array (
			'address'=>			$this->input->post ('address'), 
			'city'=>			$this->input->post ('city'), 
			'state'=>			$this->input->post ('state'), 
			'pin'=>				$this->input->post ('pin'), 
			'coaching_name'=>	$this->input->post ('coaching_name'), 
			'website'=>			$this->input->post ('website'), 
		);

		// update account
		$this->db->where ('id', $coaching_id);
		$this->db->update ('coachings', $data);			
	}	

	// Create coaching user
	public function create_user ($data) {
	
		$sql = $this->db->insert('members', $data);
		$member_id = $this->db->insert_id ();
		
		// Generate user id
		$user_id = $this->users_model->generate_reference_id ($member_id);
		$this->db->set ('adm_no', $user_id);
		$this->db->where ('member_id', $member_id);
		$this->db->update ('members');
		
		$data['member_id'] = $member_id;
		$data['user_id']   = $user_id;
		
		$coaching_id = $data['coaching_id'];
		
		$this->send_confirmation_email ($data, $coaching_id);
		
		return $member_id;
	}
	
	public function send_confirmation_email ($data='', $coaching_id=0) {
		$coaching 		= $this->coachings_model->get_coaching ($coaching_id);
		$coaching_name  = $coaching['coaching_name'];
		$email 			= $data['email'];
		$member_id 		= $data['member_id'];
		$userid 		= $data['user_id']; 
		$crypt 			= md5 ($userid);
		$url			= 'login/page/create_password/'.$crypt;
		$link			= anchor($url, 'Create your password');
		$name 			= $data['first_name'].' '.$data['last_name'];
		$to 			= $email;
		$subject 		= 'Account Created';
		$message 		= '<strong> Greetings '.$name.'! </strong> 
						  <p>Your <strong>'.$coaching_name.'</strong> account is ready.</p>
						  <p>Your user-id is:<b>'.$userid.'</b><br>
						  Before login, you must '.$link.'. <br>
						  Alternatively, you can copy-paste the following url in your browser: '.site_url($url).'</p><br><br>
						  
						  <p style="text-align:center; ">
						   <a style="padding:8px 12px; background:#4caf50; border: 1px solid #4caf50; color: white; text-decoration: none" href="'.site_url($url).'">Create Your Password</a>
						  </p>';
		$this->common_model->send_email ($to, $subject, $message);
    }
	
	public function edit_plan ($coaching_id=0, $plan_id=0) {
		$start_date = $this->input->post ('start_date');
		$starting_from = strtotime($start_date);
		
		$end_date = $this->input->post ('end_date');
		$ending_on = strtotime($end_date);

		$status = $this->input->post ('status');

		$this->db->set ('starting_from', $starting_from);
		$this->db->set ('ending_on', $ending_on);		
		$this->db->set ('status', $status);
		$this->db->where ('coaching_id', $coaching_id);
		$this->db->where ('plan_id', $plan_id);
		$this->db->update ('coaching_subscriptions');		
	}

	/*---=== ===---*/
	public function delete_account ($coaching_id=0) {

		// Delete Coaching Users
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->delete ("members");

		// Delete Coaching Tests
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->delete ("tests");

		// Delete Coaching Plans
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->delete ("coaching_test_plans");

		// Delete Coaching Account
		$this->db->where ("id", $coaching_id);
		$this->db->delete ("coachings");
	}	
	
	
	// Add a test plan for coaching	
	public function add_plan ($coaching_id=0, $plan_id=0) {
		
		$data = array ();
		if ($plan_id > 0) {
			$plans = array ($plan_id);
		} else {
			$plans 	  = $this->input->post ('plan'); 			
		}
		$discount = $this->input->post ('discount');
		
		// Start Date
		if ($this->input->post ('start_date') != false) {
			$start_date   = $this->input->post ('start_date');
			list ($sm, $sd, $sy) = explode ('/', $start_date);
		} else {
			$sd = date('d');
			$sm = date('m');
			$sy = date('Y');
		}
		$start_date = mktime (0, 0, 0, $sm, $sd, $sy);
		$creation_date = time ();
		$created_by = intval ($this->session->userdata ('member_id'));
		
		foreach ($plans as $plan_id) {
			
			$details = $this->tests_model->get_plan ($plan_id);
			// End Date
			$months = $details['duration'];
			$em = $sm+$months;
			$end_date = mktime (0, 0, 0, $em, $sd, $sy);		
			// Discount
			$amount = $details['amount'];
			if (isset($discount) && $discount > 0) {
				$amount_paid = $amount * ($discount / 100);
			} else {
				$amount_paid = $amount;				
			}
			$plan_data['coaching_id']  	= $coaching_id;
			$plan_data['plan_id'] 	   	= $plan_id;
			$plan_data['start_date']   	= $start_date;
			$plan_data['end_date']     	= $end_date;
			$plan_data['amount_paid']  	= $amount_paid;
			$plan_data['status']       	= true;
			$plan_data['creation_date'] = time ();
			$plan_data['created_by']    = intval ($this->session->userdata ('member_id'));
			
			// Check if plan not already added
			$this->db->where ('coaching_id', $coaching_id);
			$this->db->where ('plan_id', $plan_id);
			$sql = $this->db->get ('coaching_plans');
			if ($sql->num_rows() == 0) {
				// 1. Insert Plan 
				// 2. Copy test categories in this plan from MASTER DB 
				// 3. Copy all tests in test category 
				// 4. Copy all questions in test
				// 5. Update question-id
				$this->db->insert ('coaching_plans', $plan_data);

				$plan_cats = $details['test_cat_id'];
				$cats = explode (',', $plan_cats);
				if ( ! empty ($cats)) {
					// Create a new connection to MASTER DB
					$con['hostname'] = MASTER_DB_HOSTNAME;
					$con['username'] = MASTER_DB_USER;
					$con['password'] = MASTER_DB_PASSWORD;
					$con['database'] = MASTER_DB_NAME;
					$con['dbdriver'] = MASTER_DB_DRIVER;
					$con['dbprefix'] = MASTER_DB_PREFIX; 

					$creation_date = time ();
					$created_by = intval ($this->session->userdata ('member_id'));
				
					if ($master_db = $this->load->database ($con, true)) {
						
						foreach ($cats as $cat_id) {
							/* 2. Copy test-categories from master db*/
							$master_db->where ('id', $cat_id);
							$sql = $master_db->get ('test_categories');
							$row = $sql->row_array ();							
							$data	=	array ('id'=>NULL, 'master_id'=>$cat_id, 'title'=>$row['title'], 'parent_id'=>0, 'level'=>$row['level'], 'coaching_id'=>$coaching_id, 'plan_id'=>$plan_id, 'created_by'=>$created_by, 'creation_date'=>$creation_date);
							// insert this row
							$sql = $this->db->insert ('test_categories', $data);
							$pid = $this->db->insert_id ();
							/* 3. Copy children categories, tests and questions from master db */
							$this->copy_test_cats ($coaching_id, $plan_id, $cat_id, $pid);
						}
					}
				}
			}			
		}
	}
	
	public function copy_test_cats ($coaching_id, $plan_id, $master_id, $parent_id) {
		/* Copy test categories from master table */
		
		// Create a new connection to MASTER DB
		$con['hostname'] = MASTER_DB_HOSTNAME;
		$con['username'] = MASTER_DB_USER;
		$con['password'] = MASTER_DB_PASSWORD;
		$con['database'] = MASTER_DB_NAME;
		$con['dbdriver'] = MASTER_DB_DRIVER;
		$con['dbprefix'] = MASTER_DB_PREFIX;

		$creation_date = time ();
		$created_by = intval ($this->session->userdata ('member_id'));
		
		if ($master_db = $this->load->database ($con, true)) {
			
			// Check if node has children categories
			$master_db->where ('parent_id', $master_id);
			$sql = $master_db->get ('test_categories');
			if ($sql->num_rows() > 0) {
				foreach ($sql->result_array () as $row) {
					$data	=	array ('id'=>NULL, 'master_id'=>$master_id, 'title'=>$row['title'], 'parent_id'=>$parent_id, 'coaching_id'=>$coaching_id, 'plan_id'=>$plan_id, 'level'=>$row['level'], 'created_by'=>$created_by, 'creation_date'=>$creation_date);
					// insert this row
					$sql = $this->db->insert ('test_categories', $data);
					$pid = $this->db->insert_id ();
					$this->copy_test_cats ($coaching_id, $plan_id, $row['id'], $pid);
				}
			} else {
				// This is the last level
				$this->copy_tests ($coaching_id, $plan_id, $master_id, $parent_id);
			}
		}
	}
	
	public function copy_tests ($coaching_id, $plan_id, $master_id, $id) {
		
		$creation_date = time ();
		$created_by = intval ($this->session->userdata ('member_id'));
		
		// Create a new connection to MASTER DB
		$con['hostname'] = MASTER_DB_HOSTNAME;
		$con['username'] = MASTER_DB_USER;
		$con['password'] = MASTER_DB_PASSWORD;
		$con['database'] = MASTER_DB_NAME;
		$con['dbdriver'] = MASTER_DB_DRIVER;
		$con['dbprefix'] = MASTER_DB_PREFIX;

		if ($master_db = $this->load->database ($con, true)) {
			$i = 0;
			//  Get tests in category
			$master_db->where ('category_id', $master_id);
			$sql = $master_db->get ('coaching_tests');
			if ($sql->num_rows () > 0) {
				foreach ($sql->result_array() as $row ) {
					/* Copy tests from master table */
					$test_id = $row['test_id'];
					$row['test_id'] = NULL;
					$row['category_id'] = $id;
					$row['coaching_id'] = $coaching_id;
					$row['plan_id'] 	= $plan_id;
					$row['creation_date'] = $creation_date;
					$row['created_by']  = $created_by;
					$row['master_id']   = $test_id;
					$sql_tests = $this->db->insert ('coaching_tests', $row);
					$new_test_id = $this->db->insert_id ();
					
					/* Copy questions of each test from master table */
					$master_db->where ('test_id', $test_id);
					$sql_tq = $master_db->get ('tests_questions');
					if ($sql_tq->num_rows() > 0) {
						foreach ($sql_tq->result_array () as $row_tq) {
							$row_tq['id'] 			= NULL;
							$row_tq['coaching_id']  = $coaching_id;
							$row_tq['plan_id']  	= $plan_id;
							$row_tq['test_id'] 		= $new_test_id;
							$qid 					= $row_tq['question_id'];
							
							/* Get questions from master db*/
							$master_db->where ('question_id', $qid);
							$sql_q = $master_db->get ('coaching_questions');
							if ($sql_q->num_rows() > 0 ) {
								$row_q = $sql_q->row_array();
								$qid = $row_q['question_id'];
								$pid = $row_q['parent_id'];
								
								// Insert parent question first
								$master_db->where ('question_id', $pid);
								$sql_pq = $master_db->get ('coaching_questions');
								$row_pq = $sql_pq->row_array ();
								$pqid = $row_pq['question_id'];
								$row_pq['question_id'] = NULL;
								$row_pq['coaching_id'] = $coaching_id;
								$row_pq['plan_id'] 		= $plan_id;
								$row_pq['creation_date'] = $creation_date;
								$row_pq['created_by'] = $created_by;
								$row_pq['master_id'] = $pqid;
								
								// Check if parent question not already present
								$this->db->where (array('master_id'=>$pqid, 'coaching_id'=>$coaching_id));
								$sql_check = $this->db->get ('coaching_questions');
								if ($sql_check->num_rows() == 0 ) {
									$this->db->insert ('coaching_questions', $row_pq);
									$new_pqid = $this->db->insert_id ();
								} else {
									$sql_check_row = $sql_check->row_array ();
									$new_pqid = $sql_check_row['question_id'];
								}
								
								// Insert Question
								$row_q['question_id']  	= NULL;
								$row_q['parent_id'] 	= $new_pqid;
								$row_q['coaching_id'] 	= $coaching_id;
								$row_q['plan_id'] 	  	= $plan_id;
								$row_q['creation_date'] = $creation_date;
								$row_q['created_by'] 	= $created_by;
								$row_q['master_id'] 	= $qid;
								
								$this->db->insert ('coaching_questions', $row_q);
								$new_qid = $this->db->insert_id ();
								
								/* Update test questions */
								$row_tq['question_id'] 		= $new_qid;
								$this->db->insert ('tests_questions', $row_tq);
							}
						}
					}
					
					$coaching = $this->get_coaching ($coaching_id);
					if ($coaching['subscription_type'] == FREE_SUBSCRIPTION_PLAN_ID && $this->count_free_tests >= MAX_FREE_TESTS) {
						break;
					}
					$this->count_free_tests++;
					$i++; 
				}
			}
		}
	}
	

	public function enable_plan ($coaching_id=0, $plan_id=0) {
		$this->db->set ("subscription_status", 1);
		$this->db->where ("id", $coaching_id);
		//$this->db->where ("plan_id", $plan_id);
		$this->db->update ("coachings");
	}

	public function disable_plan ($coaching_id=0, $plan_id=0) {		
		$this->db->set ("subscription_status", 0);
		$this->db->where ("id", $coaching_id);
		//$this->db->where ("plan_id", $plan_id);
		$this->db->update ("coachings");
	}

	public function remove_plan ($coaching_id=0, $plan_id=0) {
		
		// Remove questions
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->where ("plan_id", $plan_id);
		$this->db->delete ('coaching_questions');
		// Remove test-questions
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->where ("plan_id", $plan_id);
		$this->db->delete ("tests_questions");
		// Remove tests
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->where ("plan_id", $plan_id);
		$this->db->delete ("tests");
		// Remove test categories
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->where ("plan_id", $plan_id);
		$this->db->delete ("test_categories");
		// Remove Plan
		$this->db->where ("coaching_id", $coaching_id);
		$this->db->where ("plan_id", $plan_id);
		$this->db->delete ("coaching_plans");
		
	}

	/*=====================================================================*/
	
	public function generate_coaching_id ($coaching_id=0) {
		
		$id = 0;
		if ($coaching_id > 0) {
			// This means coaching record is already inserted and the primary key is passed as coaching_id
			$num = $coaching_id;
		} else {
			// Coaching record is not yet inserted, show a 
			$this->db->select_max ('id');
			$sql = $this->db->get ('coachings');
			if ($sql->num_rows () > 0) {
				$row = $sql->row_array ();
				$id = $row['id'];
			} else { 
				$id = 0;
			}
			$num = $id + COACHING_ID_INCREMENT;
		}
		$suffix = str_pad ($num, 5, "0", STR_PAD_LEFT);
		$ref_id = COACHING_ID_PREFIX1 . COACHING_ID_PREFIX2 . $suffix;
		return $ref_id;
	}

	
	public function coaching_users ($coaching_id=0, $role=0, $status='') {
		$this->db->where ('coaching_id', $coaching_id);
		if ($role > 0) {
			$this->db->where ('role_id', $role);
		}
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		$sql = $this->db->get ('members');
		return $sql->result_array ();
	}
	
	
	// Upload LOGO
	public function upload_logo () {
		
		$this->load->helper('directory');
		$this->load->helper('file');
		
		$coaching_id = $this->session->userdata('coaching_id');
		$upload_dir = $this->config->item ('sys_dir') . 'coachings/' .$coaching_id .'/';
		$file_name = $this->config->item ('coaching_logo');
		$file_path = $upload_dir . $file_name;
		
		if ( ! is_dir ($upload_dir) ) {
			mkdir ($upload_dir, 0777, true);
		}
		
		// upload parameters
		$options['upload_path'] = $upload_dir;
		$options['allowed_types'] = 'gif|jpg|png';
		$options['max_size']	= MAX_FILE_SIZE;
		$options['file_name']	= $file_name;
		$options['overwrite']	= true;
		
		// load upload library
		$this->load->library ('upload', $options); 		
		
		if ( ! $this->upload->do_upload ('userfile') ) {
			$response = $this->upload->display_errors ();
		} else {   
			$upload_data = $this->upload->data();			
			$config['image_library'] = 'gd2';
			$config['source_image']	= $file_path;
			$config['create_thumb'] = false; 
			$config['maintain_ratio'] = true;
			$config['width']	 	= 240;
			$config['height']		= 80;
			$config['master_dim']	= 'auto';
			
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			$response = $upload_data;
		}		
		return $response;;
	}	

	
	public function upgrade_plan ($coaching_id=0, $plan_id=0) {
		$created_by = intval ($this->session->userdata ('member_id'));
		$this->db->set ('plan_id', $plan_id);
		$this->db->set ('created_by', $created_by);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->update ('coaching_subscriptions');

	}
	
	public function select_plans ($plan_id=0, $status='') {
		if ($plan_id > 0) {
			$this->db->where ('plan_id', $plan_id);			
		}
		if ($status != '') {
			$this->db->where ('status', $status);
		}
		$this->db->order_by ('creation_date', 'ASC');
		$sql = $this->db->get ('test_plans');
		if ($sql->num_rows () > 0 ) {
			$result = $sql->result_array ();
		} else {
			$result = false;
		}
		
		return $result;
	}
		
	
	
}