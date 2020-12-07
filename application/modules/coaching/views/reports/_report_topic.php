<div class="row">			
	<div class="col-md-12">			
		<?php 

		$mm = 0;
		$tot =0;
			if ( isset ($attempt_id) && $attempt_id > 0) {
				if ( is_array ($results)) { ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th width="50%"><?php echo 'Question'; ?></th>
							<th><?php echo 'Subject'; ?></th>
							<th><?php echo 'Topic'; ?></th>
							<th><?php echo 'Obtained/Marks'; ?></th>
							<th><?php echo 'Response'; ?></th>
						</tr>
					</thead>
					<tbody>                                 
					<?php foreach ($results as $print) {
						$row_items = $this->qb_model->getQuestionDetails ($print['question_id']);?>
						<tr>				
							<td>
								<div class="comment">
									<?php 
									echo entities_to_ascii ($row_items['question']); 
									
									//check current question is attempted by student or not
									$check = $this->tests_reports_model->check_attempted_question($print['question_id'], $attempt_id, $member_id, $test_id);
									//if attempted then get details of that question that student attempted
									$your = $this->tests_reports_model->get_attempted_question($print['question_id'], $attempt_id, $member_id, $test_id);
									if ($row_items['type'] == QUESTION_MATCH) { 
										?>
										<div  class="col-md-6">
											<p><u><strong>COLOUMN A</strong></u></p>
											<p>
												<ol>										
													<?php for ($i=1; $i <= 6; $i++) { ?>
														<li>									
															<?php 
															if ($row_items['answer_'.$i] == $i) {
																echo '  <span class="text-success" ><strong>'.$row_items['choice_'.$i].'</strong></span>';
															} else {
																echo $row_items['choice_'.$i];
															} 
															?>													
													   </li>
													<?php } ?>
												</ol>
											</p>
										</div>
										
										<div  class="col-md-6">
											<ol style="list-style:lower-alpha">
											<?php if ($row_items['type'] == QUESTION_MATCH) echo '<u><strong>COLOUMN B</strong></u>'; ?>
											<?php for ($i=1; $i <= 6; $i++) { ?>
												<?php if ($row_items['option_'.$i] != "" && $row_items['option_'.$i] != "0" ) { ?>
													<li>
														<?php echo $row_items['option_'.$i]; ?>
												   </li>
												<?php } ?>
											<?php }	?>
											</ol>
										</div>
									<?php } else if ($row_items['type'] == QUESTION_LONG && $i == 1) { ?>
										<p>
											<?php 
												if ($row_items['choice_'.$i] > 0 ) 
													echo $row_items['choice_'.$i] . ' Words'; 
											?>
										</p>
									<?php } else if ($row_items['type'] == QUESTION_TF) { ?>
										<p style="margin-left:16px;">
											<?php echo 'True'; ?>
										</p>
									<?php } else if ($row_items['type'] == QUESTION_MCSC || $row_items['type'] == QUESTION_MCMC ) { 
										$your_answer = '';
										?>
										<ol>
										<?php for ($i=1; $i <= 6; $i++) { ?>
											<?php if ($row_items['choice_'.$i] != "") { ?>
												<li><?php echo $row_items['choice_'.$i]; ?></li>
												<?php 
												if ($your['answer_'.$i] == $i) { 
													$your_answer = $row_items['choice_'.$i]; 
												} 
												?>
											<?php } ?>
										<?php } ?>
										</ol>
									<?php } ?>						
								
									<!-- CORRECT ANSWER ----------------->
									<h6>Correct Answer</h6>
									<?php if($check == TRUE){ 
										
										for ($i=1; $i <= 6; $i++) { ?>
										<?php if ($row_items['choice_'.$i] != "" && $row_items['type'] != QUESTION_LONG) { ?>
												<?php $flg =0;
													//FOR TRUE/FALSE
													if($row_items['type'] == QUESTION_TF){
														if($your['answer_'.$i] == $i){
															echo "<span>True</span>";
															$i=7;
														}else{
															echo "<span>False</span>";
															$i=7;
															}
													}
													if($row_items['type'] == QUESTION_MCMC || $row_items['type'] == QUESTION_MCSC){
														if($row_items['answer_'.$i] == $i){
															echo '<strong>'.$row_items['choice_'.$i].'</strong>';
														 }
													 }
													 if($row_items['type'] == QUESTION_MATCH){
													 
													
														if($your['answer_'.$i] == 1){
															echo '<strong>'.$i.' -a</strong> ';	
														}
														if($your['answer_'.$i] == 2){
															echo '<strong>'.$i.' -b</strong> ';	
														}
														if($your['answer_'.$i] == 3){
															echo '<strong>'.$i.' -c</strong> ';	
														}
														if($your['answer_'.$i] == 4){
															echo '<strong>'.$i.' -d</strong> ';	
														}
														if($your['answer_'.$i] == 5){
															echo '<strong>'.$i.' -e</strong> ';	
														}
														if($your['answer_'.$i] == 6){
															echo '<strong>'.$i.' -f</strong> ';	
														}
														
													
													 }
													 
													 ?>													
											<?php }
											}
										}else{ 
											echo '  <span class="text-danger">Not Attempted</span>';
										}
										?>	
									
									<!-- YOUR ANSWER -->
									<h6>Your Answer</h6>
									<?php echo $your_answer; ?>
								</div>
							</td>
							
							<?php 
							$lesson = $this->tests_model->get_list_lesson($row_items['chapter_id']); 
							$lesson_title = $lesson['title'];
							$subject_title = $this->tests_model->get_subject_name($lesson['subject_id']); 
							//$subject_title = $subjet['title'];
							?>
							<td><?php echo $subject_title; ?> </td>
							<td><?php echo $lesson_title; ?> </td>						
							
							<!-- Marks------------>					
							<td align="center">
							<?php  $mm = $mm + $row_items['marks'];
							if($check == TRUE){ 
								 for ($i=1; $i <= 6; $i++) {?>
									<?php //if ($row_items['choice_'.$i] != "" && $row_items['type'] != QUESTION_LONG) { ?>
											<?php $flg=0;$neg =0;$ans= array();$user_ans= array();
													/*marks for Question type MCMC*/
													if($row_items['choice_'.$i] != ""  && $row_items['type'] == QUESTION_MCMC){
														//make an array of right answer
														$db_answer=0;
														
														for ($i=1; $i <= 6; $i++) {
															if ($row_items['answer_'.$i] > 0){
																//echo $row_items['answer_'.$i];
																$ans[$i] = $row_items['answer_'.$i];
																$db_answer++;//count no. of answer available in qb_db.
															}
														}
														$your_answer=0;
														//make an array of student answer
														for ($i=1; $i <= 6; $i++) {
															if ($your['answer_'.$i] != ""){//echo '<br>'.$your['answer_'.$i];
																$user_ans[$i] = $your['answer_'.$i];
																$your_answer++;//count no. of answer given by student.
															}
														}
														if($db_answer == $your_answer){
														//compare both array(right answer and student answer) is equal or not.
															if(array_values($ans) === array_values($user_ans)){
																
																echo $row_items['marks']."/".$row_items['marks'];
																$tot = $tot + $row_items['marks'];
															}else {
																if($row_items['negmarks']> 0){
																	$neg =	$neg + $row_items['negmarks'];
																}echo "0/".$row_items['marks'];
															  } 
														
														}else{
															echo "0/".$row_items['marks'];
															}unset($db_answer,$your_answer);
													
													}//END OF IF --MCMC
													
													//Marks for MCSC & TF
													if(( $row_items['type'] == QUESTION_MCSC) || ( $row_items['type'] == QUESTION_TF)){
														/*marks for Question type True False And MCSC*/   
															
															if($your['answer_'.$i] != ""){
																if($your['answer_'.$i] == $row_items['answer_'.$i]){
																	echo $row_items['marks']."/".$row_items['marks'];
																	$tot = $tot + $row_items['marks'];
																 
																 }else {
																	 if($row_items['negmarks']> 0){
																		$neg =	$neg + $row_items['negmarks'];
																		
																	  }echo "0/".$row_items['marks'];
																  }
															} 
													}//END OF IF--TF & MCSC
													
													//Marks for match the coloumn 
													if(( $row_items['type'] == QUESTION_MATCH)){
														/*marks for Question type True False And MCSC*/   
														$mcmc_marks = 0;
															for ($i=1; $i <= 6; $i++) {
																if ($i == $your['answer_'.$i]){
																	$mcmc_marks++;
																}
																
															}
															echo $mcmc_marks."/".$row_items['marks'];
															if(array_values($ans) === array_values($user_ans)){
																
																//echo $row_items['marks']."/".$row_items['marks'];
																$tot = $tot + $mcmc_marks;
															}else {
																if($row_items['negmarks']> 0){
																	$neg =	$neg + $row_items['negmarks'];
																}echo "0/".$row_items['marks'];
															  } 
													}
													$mcmc_marks = 0;
													//END OF IF--TF & MCSC
										} ?>													
													
										<?php //}
							} else {
									echo "0/".$row_items['marks'];
							} ?>
							</td>
			
							<!-- RESPONSE -->
							<td align="center"><?php 
							if($check == TRUE) { 
								for ($i=1; $i <= 6; $i++) { 
									$ans= array();$user_ans= array();
									/*Response for Question type MCMC*/
									if(($row_items['choice_'.$i] != ""  && $row_items['type'] == QUESTION_MCMC)){
										//make an array of right answer
										$db_answer=0;
										for ($i=1; $i <= 6; $i++) {
											if ($row_items['answer_'.$i] > 0){
												$ans[$i] = $row_items['answer_'.$i];
												$db_answer++;
											}
										}
										//make an array of student answer
										$your_answer=0;
										for ($i=1; $i <= 6; $i++) {
											if ($your['answer_'.$i] != ""){
												$user_ans[$i] = $your['answer_'.$i];
												$your_answer++;
											}
										}
										if($db_answer == $your_answer){
											//compare both array(right answer and student answer) is equal or not.
											if (array_values($ans) === array_values($user_ans)) {
												echo '  <span class="text-success" ><i class=" icon-checkmark icon-2x"></i></span>';
											} else {
												echo '  <span class="text-danger" ><i class="icon-cancel2 icon-2x"></i></span>';
											}
										} else {
											echo '  <span class="text-danger" ><i class="icon-cancel2 icon-2x"></i></span>';
										}

									/*Response for Question type True False And MCSC*/   
									}elseif(($row_items['choice_'.$i] != ""  && $row_items['type'] == QUESTION_MATCH)){
											for ($i=1; $i <= 6; $i++) {
												if ($i == $your['answer_'.$i]){
													echo '<p>'.$i.'.   <span class="text-success" ><i class=" icon-checkmark icon-2x"></i></span></p>';
												} else {
													echo '<p>'.$i.'.   <span class="text-danger" ><i class="icon-cancel2 icon-2x"></i></span></p>';
												}									
											}	
									} else {
										if($your['answer_'.$i] != ""){
											if($your['answer_'.$i] == $row_items['answer_'.$i]){
												echo '  <span class="text-success" ><i class=" icon-checkmark icon-2x"></i></span>';
											}else {
												echo '  <span class="text-danger" ><i class="icon-cancel2 icon-2x"></i></span>';
											}
										}
									}
								}
							} else if ($check == FALSE) {
								echo '  <span class="text-danger" ><i class="icon-ban icon-2x"></i>NA</span>';
							}
							?>	
							</td>  
						</tr>
					<?php }	?>
					</tbody>
				</table>		
			<?php 
			} 
		} else { ?>
			<div class="alert alert-info"><?php echo 'Select an attempt to get report.';?></div>
		<?php } ?>
	</div>
	
	<div class="col-md-3 hidden">
		<div class="widget box">
			<div class="widget-header">
				<h4>Test Details</h4>
			</div>
			<div class="widget-content">
				<dl>
					<dt>Title</dt>
					<dd><?php echo $tests['title']; ?></dd>
					<dt>Test Marks</dt>
					<dd><?php echo $tests['max_marks']; ?></dd>
					<dt>Pass Marks</dt>
					<dd><?php echo $tests['pass_marks']; ?></dd>
					<dt>Total Questions</dt>
					<dd>
					<?php 
						$num_questions = $this->tests_model->getTestQuestions ($test_id); 
						if ( ! empty ($num_questions)) {
							echo count ($num_questions);
						} else {
							echo '0';
						}
					?>
					</dd>
				</dl>	
			</div>
		</div>
		
		<div class="widget box">
			<div class="widget-header">
				<h4>Member Details</h4>
			</div>
			<div class="widget-content">
				<dl>
					<dt>Name</dt>
					<dd><?php echo $member['first_name'] . ' ' .  $member['last_name']; ?></dd>
					<dt>Registration Number</dt>
					<dd><?php echo $member['adm_no']; ?></dd>
					<dt>Serial Number</dt>
					<dd><?php echo $member['sr_no']; ?></dd>
				</dl>
			</div>
		</div>
		
	</div>	
	

</div>

<script>
	$(".comment").shorten(); 
</script>