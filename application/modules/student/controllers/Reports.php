<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Reports extends MX_Controller {
		

	public function __construct () { 
		$config = ['config_student'];
	    $models = ['tests_reports', 'tests_model' ,'qb_model', 'users_model'];
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
	
	public function test_report ($coaching_id=0, $member_id=0, $attempt_id=0, $test_id=0, $type=SUMMARY_REPORT, $nav='' ) {
		
		// Get member_id
		if ($member_id == 0) {
			$member_id = $this->session->userdata ('member_id');
		}
		
		// Get Role
		$role_id = $this->session->userdata ('role_id');
		
		// Get latest attempt
		if ($attempt_id == 0) {
			$attempt_id = $this->tests_reports->last_attempt ($test_id, $member_id);
		}		
		
		$reports = array (
			SUMMARY_REPORT =>array ('title'=>'Summary Report', 'report_file'=>'report_summary', 'script_file'=>'report_summary'),
			OVERALL_REPORT =>array ('title'=>'Brief Report', 'report_file'=>'report_brief', 'script_file'=>'report_brief'),
			DETAIL_REPORT =>array ('title'=>'Detail Report', 'report_file'=>'report_detailed', 'script_file'=>'report_detail'),
			DIFFICULTY_REPORT =>array ('title'=>'Difficulty-wise Report', 'report_file'=>'report_difficulty', 'script_file'=>'report_difficulty'),
			CATEGORY_REPORT =>array ('title'=>'Classification-wise Report', 'report_file'=>'report_category', 'script_file'=>'report_category'),
			//TOPIC_REPORT =>array ('title'=>'Topic-wise Report', 'report_file'=>'report_topic', 'script_file'=>'report_topic'),
			);
		
		$page = str_replace (':', '/', $nav);
		$data['member'] 	= $this->users_model->get_user ($member_id);
		$test 				= $this->tests_model->view_tests ($test_id);
		$questions 			= $this->tests_model->getTestQuestions ($coaching_id, $test_id);
		$test_marks 		= $this->tests_model->getTestQuestionMarks ($coaching_id, $test_id);
		
		// Count total questions
		$num_questions 		= 0;
		if ( ! empty ($questions) ) {
			$num_questions = count($questions);
		}
		
		// Get all attempts and maximum marks from them
		$attempts = $this->tests_reports->get_attempts ($member_id, $test_id, 'ASC');
		$ob_marks = array ();
		$max_marks = 0;
		if ( ! empty ($attempts)) {
			foreach ($attempts as $atm) {
				$ob = $this->tests_reports->calculate_obtained_marks ($test_id, $atm['id'], $member_id);
				$ob_marks[$atm['id']] = array ('loggedon'=>$atm['loggedon'], 'obtained'=>$ob);
				$compare[] = $ob;
			}
			$max_marks = max ($compare);
		}
		
		// Brief report block
		$correct 			= 0;
		$wrong 				= 0;
		$not_answered 		= 0;
		$answered 			= 0;
		$total_questions	= 0;
		$om 				= 0;
		$response			= array ();
		$cat_response		= array ();
		$dif_response		= array ();
		if ( ! empty ($questions)) { 
			foreach ($questions as $question) {
				$question_id = $question['question_id'];
				$cat = $this->common_model->sys_parameter_name (SYS_QUESTION_CLASSIFICATION, $question['clsf_id']); 
				$diff = $this->common_model->sys_parameter_name (SYS_QUESTION_DIFFICULTIES, $question['diff_id']);
				
				$check = $this->tests_reports->check_test_question ($attempt_id, $test_id, $question_id, $member_id);				
				$question['om'] = $om;
				if ($check['answered'] == TQ_WRONG_ANSWERED) {
					// answered but wrong
					$answered = $answered + 1;
					$wrong = $wrong + 1;
					// Put in response array
					$response[TQ_WRONG_ANSWERED][$question_id] = $question;
					// Create category-wise response array
					// $cat_response[$cat['paramkey']][TQ_WRONG_ANSWERED][$question_id]['title'] = $cat['paramval'];
					$cat_response[$cat['paramval']][TQ_WRONG_ANSWERED][] = $question;
					// Create difficuty-wise response array
					$dif_response[$diff['paramval']][TQ_WRONG_ANSWERED][] = $question;
				} else if ($check['answered'] == TQ_CORRECT_ANSWERED) {
					// answered and correct
					$answered = $answered + 1;
					$correct = $correct + 1;
					$response[TQ_CORRECT_ANSWERED][$question_id] = $question; 
					// Create category-wise response array
					$cat_response[$cat['paramval']][TQ_CORRECT_ANSWERED][] = $question;
					// Create difficuty-wise response array
					$dif_response[$diff['paramval']][TQ_CORRECT_ANSWERED][] = $question;
				} else if ($check['answered'] == TQ_NOT_ANSWERED) {
					// not answered 
					$not_answered = $not_answered + 1;
					$response[TQ_NOT_ANSWERED][$question_id] = $question;
					// Create category-wise response array
					$cat_response[$cat['paramval']][TQ_NOT_ANSWERED][] = $question;
					// Create difficuty-wise response array
					$dif_response[$diff['paramval']][TQ_NOT_ANSWERED][] = $question;
				}
				// Obtained Marks
				$om = $om + $check['om'];
			}
		}
	
		// Total questions
		$total_questions = $answered + $not_answered;

		// Obtained Percentage

		if ($test_marks > 0) {
			$ob_perc = ($om / $test_marks) * 100;
		} else {
			$ob_perc = 0;
		}

		// Accuracy
		$accuracy = round($ob_perc, 2); 
		
		$data['brief']['answered'] 		= $answered;
		$data['brief']['not_answered'] 	= $not_answered;
		$data['brief']['correct'] 		= $correct;
		$data['brief']['wrong'] 		= $wrong;
		$data['brief']['ob_perc'] 		= $ob_perc;
		$data['brief']['accuracy'] 		= $accuracy;
		$data['coaching_id'] 			= $coaching_id;
		$category_id 				= $test['category_id'];
		$data['test'] 				= $test;
		$data['cat_response'] 		= $cat_response;
		$data['dif_response'] 		= $dif_response;
		$data['member_id'] 			= $member_id;
		$data['type'] 				= $type;
		$data['test_id'] 			= $test_id;
		$data['attempt_id'] 		= $attempt_id;
		$data['attempts'] 			= $attempts;
		$data['max_marks'] 			= $max_marks;
		$data['testMarks'] 			= $test_marks;
		$data['response'] 			= $response;
		$data['ob_marks'] 			= $ob_marks;
		$data['page'] 				= $page;
		$data['reports'] 			= $reports;
		$data['num_questions'] 		= $num_questions;
		$data['nav'] 				= $nav;
		$data['page_title'] 		= 'Reports';

		$data['bc'] = array ('Tests'=>'student/tests/tests_taken/'.$coaching_id.'/'.$member_id);
		
		$data['script'] = $this->load->view ('reports/scripts/'.$reports[$type]['script_file'], $data, true);
		$this->load->view(INCLUDE_PATH . 'header', $data);
		$this->load->view('reports/all_reports', $data);
		$this->load->view(INCLUDE_PATH . 'footer', $data);
	}
}