<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Tests_reports extends CI_Model {	

	public function __construct() {
		parent::__construct();
	}

	// get all members who attempted this test
	public function test_attempts ( $coaching_id=0, $test_id=0 ) {
		$this->db->select ('T.test_id, T.pass_marks, TA.member_id, MAX(TA.loggedon) AS loggedon, MAX(TA.submit_time) AS submit_time, MAX(TA.id) AS attempt_id, TE.start_date, TE.end_date, TE.attempts, TE.release_result, M.first_name, M.last_name, M.adm_no, M.sr_no');
		$this->db->from ('coaching_test_attempts TA, coaching_test_enrolments TE, members M, coaching_tests T');
		$this->db->where ('TA.coaching_id', $coaching_id);
		$this->db->where ('TA.test_id', $test_id);
		$this->db->where ('TA.member_id=M.member_id AND TA.test_id=T.test_id');
		$this->db->order_by ('TA.loggedon', 'DESC');
		$this->db->group_by ('TA.member_id');
		$sql = $this->db->get ();
		
		$result = [];
		if ($sql->num_rows () > 0 ) {
			foreach ($sql->result_array () as $row) {
				$attempt_id = $row['attempt_id'];
				$member_id = $row['member_id'];
				// Submission
				$submitted = $this->test_submitted ($coaching_id, $test_id, $attempt_id, $member_id);
				if ($submitted) {
					$row['submitted'] = 1;
				} else {
					$row['submitted'] = 0;
				}

				// Obtained Marks
				$row['obtained_marks'] = $this->calculate_obtained_marks ($test_id, $attempt_id, $member_id);

				$result[] = $row;
			}
		}
		return $result;
	}
	
	public function show_students ($mid) {
		$query = $this->db->get_where ("members",  array ('member_id'=>$mid));
		if ($query->num_rows() > 0 ) {
			return $query->row_array();			
		} else {
			return false;
		}
	}

	public function get_attempts ($mid, $tid, $order='DESC') {
		$this->db->order_by ('loggedon', $order);
		$query = $this->db->get_where ("coaching_test_attempts", array ('member_id'=>$mid, 'test_id'=>$tid));
		if ($query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	public function get_attempted_question ($qid, $att_id, $mid, $tid, $course_id=0, $batch_id=0) {
		if ($course_id > 0) {
			$table = "tutorial_tests_answers";
			$this->db->where (array ("cid"=>$course_id, "bid"=>$batch_id)) ;
		} else {
			$table = "tests_answers"; 
		}
		$this->db->where (array ('question_id'=>$qid, 'member_id'=>$mid, 'test_id'=>$tid, 'attempt_id'=>$att_id));		
		$query = $this->db->get ($table);
		if ($query->num_rows() > 0 ) {
			return $query->row_array();
		} else {
			return false;
		}
	}
	
	public function check_attempted_question($qid, $att_id, $mid, $tid, $course_id=0, $batch_id=0) {
		if ($course_id > 0) {
			$table = "tutorial_tests_answers";
			$this->db->where (array ("cid"=>$course_id, "bid"=>$batch_id)) ;
		} else {
			$table = "tests_answers"; 
		}
		$this->db->where (array ('question_id'=>$qid, 'member_id'=>$mid, 'test_id'=>$tid, 'attempt_id'=>$att_id));		
		$query = $this->db->get ($table);	
		if ($query->num_rows() > 0) { 
			return TRUE;	
		} else {
			return FALSE;
		}
	}
	
	public function get_unattempted_question($att_id, $mid, $tid) {
		/*$this->db->select ('coaching_test_questions.question_id');
		$this->db->from ('coaching_test_questions');
		$this->db->where ('coaching_test_questions.test_id', $tid);
		$this->db->join ('question', 'coaching_test_questions.test_id = questions.question_id');
		$query = $this->db->get();*/
		$query = $this->db->query("SELECT Q.question_id FROM  lm_questions Q, lm_coaching_test_questions TQ WHERE Q.question_id = TQ.question_id AND TQ.test_id =$tid");
		if ($query->num_rows() > 0 ) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	
	// returns an array containing ALL questions attempted by user
	public function getAttemptedQuestions ( $test_id, $attempt_id, $member_id ) {
	
		$query = $this->db->get_where ('coaching_test_answers', array('test_id'=>$test_id, 'attempt_id'=>$attempt_id, 'member_id'=>$member_id) );
		if ($query->num_rows() > 0 ) {
			return $query->result_array();	
		} else {
			// didnt answered any question
			return false; 
		}
	}
	
	//Model for get test mark obtained by a student==========
	public function get_test_mark ($tid, $mid) {
		$query = $this->db->select ('obtained_marks' );
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid,  'member_id'=>$mid) );
		if ($query->num_rows() > 0 ) {
			$row = $query->row();
			return $row->obtained_marks;
		} else {
			return 0;
		}
		
	}
	//Model for get question mark obtained by a student in each question in Test==========
	public function get_ques_mark ($tid, $mid) {
		$query = $this->db->select ('obtained_marks' );
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid,  'member_id'=>$mid) );
		if ($query->num_rows() > 0 ) {
			$row = $query->row();
			return $row->obtained_marks;
		} else {
			return 0;
		}
		
	}
	
	//Count how many students are marked==========
	public function count_marked_student ($tid) {
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid) );
		if ($query->num_rows() > 0 ) {
			return $query->num_rows();
		} else {
			return 0;
		}
	}

	//Model for get marking type TESTWISE OR QUESTIONWISE========================================
	public function get_marking_type($tid) {
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid) );
		if ($query->num_rows() > 0 ) {
			$res = $query->row_array();
			return $res['marking_type']; 
		}else{
			return FALSE;
		}
	}
	
	//Model for save mark By Test wise====================================
	public function save_test_marks($tid, $mid, $mark, $type) {
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid,  'member_id'=>$mid) );
		
		$data = array (	'test_id'		=>	$tid,
						'member_id'		=>	$mid,
						'obtained_marks'=>	$mark,
						'marking_type'	=>	$type,
						  );
		//if entry is first time				  	
		if ($query->num_rows() == 0 ) {
							  
			if($this->db->insert("test_answers_offline", $data)){
				return true;
			}else{
				return false;
				}
		//if entry exist then update it.		
		}else{
			$this->db->where('test_id', $tid);
			$this->db->where('member_id', $mid);
			return $this->db->update('test_answers_offline', $data); 

			
			}
	}
	//Model for save mark By Question Wise =====================================
	public function save_ques_marks($tid, $mid, $obt_mark, $type) {
		
		$query = $this->db->get_where ('test_answers_offline', array('test_id'=>$tid,  'member_id'=>$mid) );
		
		$data = array (	'test_id'		=>	$tid,
						'member_id'		=>	$mid,
						'obtained_marks'=>	$obt_mark,
						'marking_type'	=>	$type,
					  );
		//if entry is first time				  	
		if ($query->num_rows() == 0 ) {
			if ($this->db->insert("test_answers_offline", $data)) {
				return true;
			} else {
				return false;
			}
		} else {
			// if entry exist then update it.		
			$this->db->where('test_id', $tid);
			$this->db->where('member_id', $mid);
			return $this->db->update('test_answers_offline', $data);
		}	
	}
	
	//Model for get details of Student======================================
	public function get_member_info($id){
		$query = $this->db->get_where ('members', array('member_id'=>$id) );
		if ($query->num_rows() > 0 ) {
			$result = $query->row_array();
			return $result['login'];
		} else {
			return FALSE;
		}
	}
	
	
	//Model for Reset method of Marksheet QUESTION WISE OR TESTWISE=================
	public function reset_marksheets($tid) {		
		return $this->db->delete('test_answers_offline', array('test_id' => $tid)); 		
	}
	
	// function to return a list of students who attempted a particular attempt of a test
	public function get_this_attempt ($test_id, $attempt) {
		
		$result = array ();
		$students = array ();
		
		$this->db->select ('member_id');
		$this->db->order_by ('loggedon');
		$this->db->group_by ('member_id');
		$this->db->where ('test_id', $test_id);
		$sql = $this->db->get ('coaching_test_attempts');
		if ($sql->num_rows () > 0 ) {
			foreach ( $sql->result_array() as $row) {
				$result[] = $row['member_id'];
			}			
			//	$result is a list of students who attempted this test			
			foreach ( $result as $member_id ) {
				$this->db->order_by ('loggedon');
				$this->db->where ('test_id', $test_id);
				$this->db->where ('member_id', $member_id);
				$query = $this->db->get ('coaching_test_attempts');
				if ( $query->num_rows () > 0 ) {				
					$num_attempts = count ($query->result_array ($attempt));
					// return only the n'th ($attempt) row 
					if ( $num_attempts >= $attempt) {
						$row = $query->row_array ($attempt);
						$students[] =  $row;
					}
				}
			}
			return $students;
		} else {
			return false;
		}
	}
	
	// function to return a list of students who attempted a particular attempt of a test
	public function obtained_marks ($test_id, $attempt, $member_id) {
		$this->db->select ('id');
		$this->db->order_by ('loggedon');
		$this->db->where ('member_id', $member_id);
		$this->db->where ('test_id', $test_id);
		$sql = $this->db->get ('coaching_test_attempts');
		if ($sql->num_rows () > 0 ) {
			$result = $sql->row_array ($attempt);
			$attempt_id = $result['id'];
			$obtained_marks = $this->calculate_obtained_marks ($test_id, $attempt_id, $member_id) ;
			return $obtained_marks;
		}		
	}
	
	
	public function calculate_obtained_marks ($test_id, $attempt_id, $member_id) {
			
			
			$obtainedMarks = 0;
			//$numAttempts[$id] = count ($attempts);
			// get attempted questions 
			$attemptedQuestions = $this->getAttemptedQuestions ( $test_id, $attempt_id, $member_id );
			// now check each quetion (correct/wrong)
			if ( $attemptedQuestions == false ) {
			// didnt answered any questions 
				$obtainedMarks = 0;
			} else {
				foreach ( $attemptedQuestions as $aqs ) {
				// check if answer is correct
					$question = $this->qb_model->getQuestionDetails ($aqs['question_id']);
					if ( $question != false ) {
					// we have to mark/check each question by its type
						// Multi Choice Single Correct 
						if ( $question['type'] == QUESTION_MCSC ) {
							$givenAns = "";
							$correctAns = "";
							for ( $i=1; $i <= 6; $i++ ) {
								// answer given by user
								if ( $aqs['answer_'.$i] == $i ) {
									$givenAns = $aqs['answer_'.$i];
								}
								// actual correct answer
								if ( $question['answer_'.$i] == $i ) {
									$correctAns = $question['answer_'.$i];
								}										
							} // end for
							if ( $givenAns == $correctAns ) {										
							// this is a correct answer
								$obtainedMarks = $obtainedMarks + $question['marks'];
							} else {
							// wrong answer
								if ( $question['negmarks'] > 0 ) {
								// deduct negative marks
									$obtainedMarks = $obtainedMarks - $question['negmarks'];
								}
							}
						} // end if MCSC
						
						// Multi Choice Multi Correct 
						if ( $question['type'] == QUESTION_MCMC ) {
							$givenAns = array();
							$correctAns = array();
							for ( $i=1; $i <= 6; $i++ ) {
								// answer given by user
								if ( $aqs['answer_'.$i] > 0 ) {
									$givenAns[] = $aqs['answer_'.$i];
								}
								// actual correct answer
								if ( $question['answer_'.$i] > 0 ) {
									$correctAns[] = $question['answer_'.$i];
								}
							} // end for
								
							// check this answer only if contains exact number of correct answers
							if ( count($givenAns) == count($correctAns) ) {
								$result = array_diff ($givenAns, $correctAns);
								if ( empty ($result) ) {
								// this is a correct answer
									$obtainedMarks = $obtainedMarks + $question['marks'];
								} else {
								// wrong answer
									if ( $question['negmarks'] > 0 ) {
									// deduct negative marks
										$obtainedMarks = $obtainedMarks - $question['negmarks'];
									}
								}
							} else {
							// wrong answer
								if ( $question['negmarks'] > 0 ) {
								// deduct negative marks
									$obtainedMarks = $obtainedMarks - $question['negmarks'];
								}
							}
						} // end if MCMC
						
						// TRUE-FALSE
						else if ( $question['type'] == QUESTION_TF ) {
							$givenAns = "";
							$correctAns = "";
							// answer given by user
							$givenAns = $aqs['answer_1'];										
							// actual correct answer
							$correctAns = $question['answer_1'];
								
							if ( $givenAns == $correctAns ) {
							// this is a correct answer
								$obtainedMarks = $obtainedMarks + $question['marks'];
							} else {
							// wrong answer
								if ( $question['negmarks'] > 0 ) {
								// deduct negative marks
									$obtainedMarks = $obtainedMarks - $question['negmarks'];
								}
							}

						} // end if TF 	
						
						// Multi Choice Multi Correct 
						else if ( $question['type'] == QUESTION_MATCH ) {
							$givenAns = array();
							$correctAns = array();
							$match_choices = array ();
							for ( $i=1; $i <= 6; $i++ ) {
								if ($question['choice_'.$i] != '') {
									$match_choices[] = 1;
								}
							}
							// number of choices given 
							$match_num_choices = count ($match_choices);
							// mark of each choice
							$each_mark = $question['marks'] / $match_num_choices;
							
							for ( $i=1; $i <= 6; $i++ ) {
								// answer given by user
								if ( $aqs['answer_'.$i] > 0 ) {										
									if ( $aqs['answer_'.$i] == $i ) {
										// this is a correct answer
										$givenAns[] = 1;
										$obtainedMarks = $obtainedMarks + $each_mark;
									}
								}										
							} // end for
							
							if ( empty ($givenAns)) {
								// wrong answer
								if ( isset ($tests['negative_marking']) && $tests['negative_marking'] == true ) {
									if ( $question['negmarks'] > 0 ) {
										// deduct negative marks
										$obtainedMarks = $obtainedMarks - $question['negmarks'];
									}
								}
							}

						} // end if MATCHING
						
					} // end foreach
				} // end foreach
			} // end esle
			return $obtainedMarks ;
	}	
	
	 
	public function cheked_test($mid, $tid) {
		$this->db->where('test_id', $tid);
		$this->db->where('member_id', $mid);
		$qry = $this->db->get('test_answers_offline');
		return $qry->num_rows();
	}
	
	public function max_attempt ($mid, $tid) {
		$q = $this->db->query("Select MAX(loggedon) as grt_atmp,  id  as attempt_id  from lm_coaching_test_attempts where  test_id = $tid AND member_id = $mid");
		if($q->num_rows() > 0 ){
			$r = $q->result_array();
			//print_r($r);die();
			return $r[0]['attempt_id'];
		}else{
			return FALSE;
		}
	}
	
	public function test_answer ($attempt_id, $test_id, $question_id, $member_id) {
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('question_id', $question_id);
		$this->db->where ('attempt_id', $attempt_id);
		$sql = $this->db->get('coaching_test_answers');
		return $sql->row_array ();
	}
	
	// check question
	public function check_test_question ($attempt_id=0, $test_id=0, $question_id=0, $member_id=0) {
		
		$correct = array ();
		$user = array ();
		$response = array ();
		
		// get question details
		$question = $this->qb_model->getQuestionDetails ($question_id);
		// get user answer details
		$answer = $this->test_answer ($attempt_id, $test_id, $question_id, $member_id);

		// Attempted
		if ( ! empty ($answer)) {			
			for ($i=1; $i <=QB_NUM_ANSWER_CHOICES; $i++) {
				if ($question['answer_'.$i] > 0) {
					$correct[$i] = $question['answer_'.$i];
				}
				if ($answer['answer_'.$i] > 0) {
					$user[$i] = $answer['answer_'.$i];		
				}
			}
			if (array_values($correct) === array_values($user)) {
				$response['answered'] 		= TQ_CORRECT_ANSWERED;		// Correct Answer
				$response['om'] 			= $question['marks'];		// Marks 
			} else {
				$response['answered'] 		= TQ_WRONG_ANSWERED;		// Wrong Answer
				$response['om'] 			= 0;					// Marks
			}
			$response['user_answer']   		= $user;				// User Answer
			$response['correct_answer'] 	= $correct;				// Correct Answer
		} else {
			// Not attempted
			$response['user_answer']   		= false;				// User Answer
			$response['om'] 				= 0;					// Marks
			$response['correct_answer'] 	= $correct;				// User Answer
			$response['answered'] 			= TQ_NOT_ANSWERED;		// Not Answered
		}
		
		return $response;
	} 
	
	public function report_category_wise ($attempt_id, $test_id, $member_id, $type=SYS_QUESTION_CATEGORIES) {

		$cats   = array ();
		$output = array ();
		$total_questions = 0;
		$results = $this->tests_model->getTestQuestions ($test_id);
		if ( is_array ($results)) {
			$total_questions = count ($results);
			foreach ( $results as $question_id) {
				$question = $this->qb_model->getQuestionDetails ($question_id);
				if ($type == SYS_QUESTION_DIFFICULTIES) {
					$cat_id = $question['clsf_id'];
				} else {
					$cat_id = $question['category_id'];
				}
				$cats[$cat_id][] =  $question_id;
			}
		}
		
		if ( ! empty ($cats)) {
			foreach ($cats as $cat_id=>$questions) {
				$cat = $this->common_model->sys_parameter_name ($type, $cat_id); 
				$answered 		= 0;
				$not_answered 	= 0;
				$correct 		= 0;
				$not_correct 	= 0;
				$num_questions 	= 0;
				if ( ! empty ($questions)) {
					$num_questions = count ($questions);
					foreach ($questions as $ids) {									
						$response = $this->check_test_question ($attempt_id, $test_id, $ids, $member_id);
						if ($response['answered'] == 2) {
							$correct  = $correct + 1;
							$answered = $answered + 1;
						} else if ($response['answered'] == 1) {
							$not_correct  = $not_correct + 1;
							$answered 	  = $answered + 1;
						} else if ($response['answered'] == 0) {
							$not_answered 	  = $not_answered + 1;
						}
					}								
				}
				
				$output[$cat['paramval']] = array ('Answered'=>$answered, 'Correct'=>$correct, 'Wrong'=>$not_correct, 'Not Answered'=>$not_answered, 'Total Questions'=>$num_questions);
			}
		}
		
		return $output;
	}
	
	
	public function report_topic_wise ($test_id=0, $member_id=0) {

		// All test questions
		$all_questions = $this->tests_model->getTestQuestions ($test_id);
		/* Perpare an array in form of subject->question_group->question */
		$collect = array ();
		if ( ! empty ($all_questions)) {					
			foreach ($all_questions as $qid) {
				// get details
				$details = $this->qb_model->getQuestionDetails ($qid);
				$chapter_id = $details['chapter_id'];
				$parent_id = $details['parent_id'];
				// get subject
				// $subject_id = $this->tests_model->get_subject_id ($chapter_id);
				$collect[$chapter_id][$parent_id][] = $qid;
			}
		}
		
		$prepare_questions = array ();
		foreach ( $collect as $chapter_id=>$question_group) {
			if ( is_array ($question_group)) {
				$question_group_ids = array_keys ($question_group);
				foreach ($question_group_ids as $qgids) {
					$prepare_questions[$chapter_id][$qgids] = $question_group[$qgids];
				}
			}
		}
		
		return $prepare_questions;
		
	}

	public function test_taken ($coaching_id=0, $test_id=0) {
		$select = 'CTA.loggedon, CTA.submit_time, CTA.id AS attempt_id, M.first_name, M.last_name, M.member_id, M.adm_no, M.sr_no';
		$this->db->select ($select);
		$this->db->from ('members M, coaching_test_attempts CTA');
		$this->db->where ('M.member_id=CTA.member_id');
		$this->db->where ('CTA.coaching_id', $coaching_id);
		$this->db->where ('CTA.test_id', $test_id);
		$this->db->group_by ('CTA.member_id');
		$this->db->order_by ('CTA.loggedon', 'DESC');
		$sql = $this->db->get ();
		return $sql->result_array ();
	}
	
	public function test_taken_by_member ($member_id=0, $attempt=0) {
		$this->db->where ('member_id', $member_id);
		$this->db->group_by ('test_id');
		$this->db->order_by ('loggedon', 'DESC');
		$sql = $this->db->get ('coaching_test_attempts');
		if ($sql->num_rows () > 0 ) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
	public function last_attempt ($test_id=0, $member_id=0) {
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$this->db->order_by ('loggedon', 'DESC');
		$this->db->limit (1);
		$sql = $this->db->get ('coaching_test_attempts'); 
		if ($sql->num_rows () > 0 ) {
			$row = $sql->row_array ();
			$attempt_id = $row['id'];
			return $attempt_id;
		} else {
			return 0;
		}		
	}

	public function delete_submissions($coaching_id=0, $test_id=0) {
		$users = $this->input->post ('users');
		if (! empty ($users)) {
			foreach ($users as $member_id) {
				// Delete attempts
				$this->db->where ('coaching_id', $coaching_id);
				$this->db->where ('test_id', $test_id);
				$this->db->where ('member_id', $member_id);
				$this->db->delete ('coaching_test_attempts');

				// Delete Submissions
				$this->db->where ('coaching_id', $coaching_id);
				$this->db->where ('test_id', $test_id);
				$this->db->where ('member_id', $member_id);
				$this->db->delete ('coaching_test_answers');
			}
		}
	}

	public function test_submitted ($coaching_id=0, $test_id=0, $attempt_id=0, $member_id=0) {
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('attempt_id', $attempt_id);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_test_answers');
		if ($sql->num_rows () > 0) {
			return $sql->result_array ();
		} else {
			return false;
		}
	}
	
}