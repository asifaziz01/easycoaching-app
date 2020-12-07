<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tests extends MX_Controller {

    public function __construct () {
		$config = ['config_student'];
	    $models = ['tests_model' ,'qb_model', 'tests_reports', 'users_model'];
		$this->common_model->autoload_resources ($config, $models);

        $cid = $this->uri->segment (4);        
        
        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('student/home/dashboard');
            }
        }

	}
    

    public function index ($coaching_id=0, $member_id=0, $test_type=TEST_TYPE_REGULAR) {
		$this->browse_tests ($coaching_id, $member_id, $test_type);
	} 
	
    public function browse_tests ($coaching_id=0, $member_id=0, $test_type=TEST_TYPE_REGULAR) {
		$data['page_title'] 	= "Browse Tests";
		
        $data['coaching_id'] 	= $coaching_id;
		$data['member_id'] 		= $member_id;
		$data['test_type'] 		= $test_type;

		if ($test_type == TEST_TYPE_REGULAR) {

			$enrolments 			= $this->tests_model->get_enroled_tests ($coaching_id, $member_id);

			$enroled = [];
			$now = time ();
			if (! empty ($enrolments)) {
				foreach ($enrolments as $row) {
					
	                $questions = $this->tests_model->getTestQuestions ($coaching_id, $row['test_id']);
	                $testMarks = $this->tests_model->getTestQuestionMarks ($coaching_id, $row['test_id']);

	                if (! empty ($questions)) {
	                    $num_test_questions = count ($questions);
	                } else {
	                    $num_test_questions = 0;
	                }


					$attempts = $this->tests_model->get_attempts ($coaching_id, $member_id, $row['test_id']);
					if (! empty ($attempts)) {
						$num_attempts = count($attempts);
					} else {
						$num_attempts = 0;
					}

	                $row['test_marks'] = $testMarks;
	                $row['num_test_questions'] = $num_test_questions;
					$row['num_attempts'] = $num_attempts;

					// On going tests
					if ( $now >= $row['start_date'] && $now <= $row['end_date']) {
						$enroled['ongoing'][] = $row;
					} else if ($now < $row['start_date'] && $now < $row['end_date']) {
					// Up coming tests
						$enroled['upcoming'][] = $row;
					} else {
					// Archived tests
						$enroled['archived'][] = $row;
					}
				}
			}
			$data['tests'] = $enroled;
		} else {
			$data['tests'] = $this->tests_model->get_all_tests ($coaching_id, $category_id=0, TEST_TYPE_PRACTICE);
		}
		
		$data['bc'] = array ('Dashboard'=>'student/home/dashboard/'.$coaching_id.'/'.$member_id);

		$this->load->view ( INCLUDE_PATH . 'header', $data); 
		$this->load->view ( 'tests/browse_tests', $data);
		$this->load->view ( INCLUDE_PATH . 'footer', $data);
		
    }
	
	public function tests_taken ($coaching_id=0, $member_id=0, $category_id=0, $type=0, $offset=0) {
		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id==0) {
            $member_id = $this->session->userdata ('member_id');
        }

        $data['coaching_id'] = $coaching_id;
		$data['member_id'] = $member_id;
		$data['category_id'] = $category_id;
		$data['type'] 		 = $type;		
		$data['page_title'] 	= 'Tests Taken';
		$data['bc'] 			= array ('Dashboard'=>'student/home/dashboard/'.$coaching_id.'/'.$member_id);		

		// We have to call the same method again to get ALL records. Notice fourth parameter is set true
		$test_taken = $this->tests_model->test_taken_by_member ($coaching_id, $member_id);

		$data['test_taken'] = $test_taken;

		$this->load->view ( INCLUDE_PATH  . 'header', $data);
		$this->load->view('tests/my_tests',$data);
		$this->load->view ( INCLUDE_PATH  . 'footer', $data);
	}

	
	// it gives all the instructions for the test
	public function take_test ($coaching_id=0, $member_id=0, $test_id=0, $page="") {
		redirect ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.$page);
	}
	
	public function test_instructions ($coaching_id=0, $member_id=0, $test_id=0, $nav="") {
		
		$test = $this->tests_model->view_tests ($test_id);		
		
        $questions = $this->tests_model->getTestQuestions ($coaching_id, $test_id);
        $testMarks = $this->tests_model->getTestQuestionMarks ($coaching_id, $test_id);

        if (! empty ($questions)) {
            $num_test_questions = count ($questions);
        } else {
            $num_test_questions = 0;
        }

        $data['test_marks'] = $testMarks;
        $data['num_test_questions'] = $num_test_questions;


		if ($coaching_id == 0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id == 0) {
            $member_id = $this->session->userdata ('member_id');
        }
		
		$start_test = true;
		
		// Check enrolment
		if ($test['test_type'] == TEST_TYPE_REGULAR) {
			$now = time ();
			$enrolment = $this->tests_model->get_enrolment_details( $coaching_id, $test_id, $member_id);
			if ( $now >= $enrolment['start_date'] && $now <= $enrolment['end_date']) {
				$start_test = true;
			} else {				
				$this->message->set ('This test is not active', 'danger', true);
				redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id);
			}
	
			// Check attempts
			$attempt = $this->tests_model->check_attempt ($coaching_id, $test_id, $member_id);
			if ( $enrolment['attempts'] > 0) {
				if ( $attempt > $enrolment['attempts']) {
					$this->message->set ('You have taken all attempts of this test.', 'danger', true);
					redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.TEST_ERROR_MAX_ATTEMPT_REACHED);
				} 
			}

		}


		$page = str_replace (':', '/', $nav);

		//$data['page_stats'] = $this->tests_model->page_stats ($member_id);
		
		$data['page_title'] 	= "Instructions";
		$data['page'] 			= $page;
		$data['test'] 			= $test;
		$data['coaching_id'] 	= $coaching_id;
		$data['member_id'] 		= $member_id;
		$data['test_id'] 		= $test_id;
		$data['start_test'] 	= $start_test;
		
		$data['bc']			= array ('Dashboard'=>'student/tests/index/'.$coaching_id.'/'.$member_id);

		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('tests/test_instructions',$data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);		
	}
	

	public function test_verification ($coaching_id=0, $member_id=0, $test_id=0) {
		
		/* Check for valid test session */
		# Test window cannot be refreshed/reloaded. This will disable/lock the current test.
		# Next test attempt cannot be taken before expiry of current test.

		$test = $this->tests_model->view_tests ($test_id);
		$member_id = $this->session->userdata ('member_id');

		if ( $test['finalized'] == 0) {
			redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.TEST_ERROR_UNPUBLISHED);
		}		
		
		// Check enrolment
		if ($test['test_type'] == TEST_TYPE_REGULAR) {
			$now = time ();
			$enrolment = $this->tests_model->get_enrolment_details( $coaching_id, $test_id, $member_id);
			if ( $now >= $enrolment['start_date'] && $now <= $enrolment['end_date']) {
				$start_test = true;
			} else {				
				$this->message->set ('This test is not active', 'danger', true);
				redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id);
			}
	
			// Check attempts
			$attempt = $this->tests_model->check_attempt ($coaching_id, $test_id, $member_id);
			if ( $enrolment['attempts'] > 0) {
				if ( $attempt > $enrolment['attempts']) {
					redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.TEST_ERROR_MAX_ATTEMPT_REACHED);
				} 
			}

		}

		// Recently Taken?
		// Check if this test has been recently taken (within test duration time)
		/*
		$last_attempt = $this->tests_model->check_session ($coaching_id, $test_id, $member_id);
		$now = time ();
		$test_duration = ($test['time_hour'] * 60 * 60) + ($test['time_min'] * 60);
		$next_attempt = $last_attempt + $test_duration;
		if ( $next_attempt >  $now) {
			$time_remaining = $next_attempt - $now;
			redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.TEST_ERROR_RECENTLY_TAKEN.'/'.$time_remaining);
		}
		*/
		
		return false;
	}
	
	public function test_error ($coaching_id=0, $member_id=0, $test_id=0, $error=0, $time_remaining=0) {

		$data['page_title'] = "Error";
		
		$data['error']				= $error;
		$data['coaching_id']		= $coaching_id;
		$data['member_id']			= $member_id;
		$data['test_id']			= $test_id;
		$data['time_remaining']		= $time_remaining;
		$data['bc']					= ['Tests'=>'student/tests/index/'.$coaching_id.'/'.$member_id];

		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('tests/test_error', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}
	
	// here starts the test
	public function start_test ($coaching_id=0, $member_id=0, $test_id=0) {
	
		$this->load->helper('text');
		$this->load->helper('html');
		$this->test_verification ($coaching_id, $member_id, $test_id);

		$test = $this->tests_model->view_tests ($test_id);
		$test_duration = ($test['time_hour'] * 3600) + ($test['time_min'] * 60);
		
		if ($coaching_id==0) {
            $coaching_id = $this->session->userdata ('coaching_id');
        }
        if ($member_id==0) {
            $member_id = $this->session->userdata ('member_id');
        }
		$data['user'] = $this->users_model->get_user ($member_id);
		
		/* PREPARE TEST */
		$all_questions = array ();
		$attempted_questions = array ();
		$remaining_questions = array ();
		
		// Settings for the first time test starts 

		// All test questions
		$testMarks = $this->tests_model->getTestQuestionMarks ($coaching_id, $test_id);
		$questions = $this->tests_model->getTestQuestions ($coaching_id, $test_id);
		/* If randomize ALL questions
		if ($test['randomize_all_questions'] == 1) {
			shuffle ($all_questions);
		}
		
		*/

		if (! empty($questions)) {
			$num_test_questions = count ($questions);
		} else {
			$num_test_questions = 0;
		}
		$data['test_marks'] = $testMarks;
		$data['total_questions'] = $num_test_questions;
		$data['confirm_div'] = $num_test_questions + 1;

		/* Perpare an array in form of subject->question_group->question */
		$result = array ();
		if ( ! empty($questions)) {
			foreach ($questions as $row) {
				$parent_id = $row['parent_id'];
				$parent_row = $this->qb_model->getQuestionDetails ($parent_id);
				$result[$parent_id]['parent'] = $parent_row;
				$result[$parent_id]['questions'][] = $row;
			}
		} else {
			redirect ('student/tests/test_error/'.$coaching_id.'/'.$member_id.'/'.$test_id.'/'.TEST_ERROR_NO_QUESTION);
		}
		
		// save current attempt log
		$now = time ();
		//$attempt_id = 0;
		$attempt_id = $this->tests_model->save_attempt ($coaching_id, $test_id, $member_id, $now);		
		
		$data['test'] 					= $test;
		$data['test_id'] 				= $test_id;
		$data['coaching_id'] 			= $coaching_id;
		$data['attempt_id'] 			= $attempt_id;
		$data['member_id'] 				= $member_id;
		$data['results'] 				= $result;
		$data['test_duration'] 			= $test_duration;
		$data['hide_left_sidebar'] 		= true;
		$data['data']	 				= $data;
		$data['page_title'] 			= $test['title'];

		$data['script'] = $this->load->view ('tests/scripts/start_test', $data, true);
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('tests/start_test', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}	
	
	
	public function test_submitted ($coaching_id=0, $test_id=0, $member_id=0, $attempt_id=0) {
		
		$data['page_title'] 		= "Test Submitted";

		$data['coaching_id']		= $coaching_id;
		$data['member_id']			= $member_id;
		$data['test_id']			= $test_id;
		$data['attempt_id']		    = $attempt_id;
		$data['bc']					= ['Test Taken'=>'student/tests/tests_taken/'.$coaching_id.'/'.$member_id];

		$enrolment = $this->tests_model->get_enrolment_details ($coaching_id, $test_id, $member_id);
		if ($enrolment['release_result'] == RELEASE_EXAM_NEVER) {
			$this->message->set ('Test submitted successfully. Result will be declared on a later date', 'success', true);
			redirect ('student/tests/tests_taken/'.$coaching_id.'/'.$member_id);
		}

		$data['test_marks'] 		= $this->tests_model->getTestquestionMarks ($coaching_id, $test_id);
		$ob = $this->tests_reports->calculate_obtained_marks ($test_id, $attempt_id, $member_id);
		$data['score'] = $ob;

		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('tests/test_submitted', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}

}