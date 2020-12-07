<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Tests_reports extends CI_Model {	

	public function __construct() {
		parent::__construct();
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
	
	public function test_answer ($attempt_id, $test_id, $question_id, $member_id) {
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('question_id', $question_id);
		$this->db->where ('attempt_id', $attempt_id);
		$sql = $this->db->get('coaching_test_answers');
		if ($sql->num_rows() > 0) {
			return $sql->row_array ();			
		} else {
			return false;
		}
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
		if ( ! empty ($answer)) {
			// Attempted
			for ($i=1; $i <=5; $i++) {
				if ($question['answer_'.$i] > 0) {
					$correct[$i] = $question['answer_'.$i];				
				}
				if ($answer['answer_'.$i] > 0) {
					$user[$i] = $answer['answer_'.$i];				
				}
			}
			if (array_values($correct) === array_values($user)) {				
				$response['answered'] 		= 2;					// Correct Answer
				$response['om'] 			= $question['marks'];		// Marks 
			} else {
				$response['answered'] 		= 1;					// Wrong Answer
				$response['om'] 			= 0;					// Marks
			}
			$response['user_answer']   		= $user;				// User Answer
			$response['correct_answer'] 	= $correct;				// Correct Answer
		} else {
			// Not attempted
			$response['user_answer']   		= false;				// User Answer
			$response['om'] 				= 0;					// Marks
			$response['correct_answer'] 	= $correct;				// User Answer
			$response['answered'] 			= 0;					// Not Answered
		}
		
		return $response;
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

	public function test_submitted ($coaching_id=0, $test_id=0, $attempt_id=0, $member_id=0) {
		$this->db->where ('test_id', $test_id);
		$this->db->where ('member_id', $member_id);
		$this->db->where ('attempt_id', $attempt_id);
		$this->db->where ('coaching_id', $coaching_id);
		$sql = $this->db->get ('coaching_test_answers');
		return $sql->result_array ();
	}
	
}