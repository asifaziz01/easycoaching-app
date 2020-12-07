<div class="col-md-2">
	<div class="widget box">
		<div class="widget-header">
			<h4 class="">Attempts</h4>
		</div>
		<div class="widget-content no-padding">
			<div class="list-group">
				<?php 
					$i = 1;
					if ( ! empty ($res)) {
						foreach ($res as $row) { 
							$text = "";
							if ( $attempt_id == $row['id'] ) {
								$class = 'list-group-item active';
							} else {
								$class= "list-group-item";
							}						
							echo '<li class="'.$class.'">';
								$date = $row['loggedon'];
								echo anchor ('tests/reports/show_attempts/'.$row['id'].'/'.$member_id.'/'.$tid.'/'.$page, "Attempt ".$i); 
								echo $date;
							echo '</li>';
							$i++;
						}
					} else {
						echo '<li class="list-group-item">';
							echo "None found"; 
						echo '</li>';
					}
				?>
			</div>
		</div>
	</div>
</div>

	

<?php 
	$mm = 0; 
	$tot =0;
?> 
<div class="col-md-8">
	<?php 

	$mm = 0;
	$tot =0;
		if ( isset ($attempt_id) && $attempt_id > 0) {
			if ( is_array ($results)) { ?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th width='60%'><?php echo 'Questions'; ?></th>                          
						<th width='20%'><?php echo 'Your answer'; ?></th>  
						<th width='10%'><?php echo 'Obtained/Marks'; ?></th>  
						<th width='10%'><?php echo 'Response'; ?></th>												   
					</tr>
				</thead>
				<tbody> 						
				<?php foreach ($results as $print) {
					$row_items = $this->qb_model->getQuestionDetails ($print['question_id']);?>
					<tr>
				
						<td> 
						<?php echo $row_items['question'];  
						//check current question is attempted by student or not
						$check = $this->tests_reports_model->check_attempted_question($print['question_id'], $attempt_id, $member_id, $tid);
						//if attempted then get details of that question that student attempted
						$your = $this->tests_reports_model->get_attempted_question($print['question_id'], $attempt_id, $member_id, $tid);?>
						<div class="row">
						<div  class="col-md-6">
							<ol>
							<?php if ($row_items['type'] == QUESTION_MATCH) echo '<u><strong>COLOUMN A</strong></u>'; ?>
							<?php for ($i=1; $i <= 6; $i++) { ?>
								<?php if ($row_items['choice_'.$i] != "" && $row_items['type'] != QUESTION_LONG) { ?>
									<?php 
										if($row_items['answer_'.$i] == $i){
											$class = "text-success text-bold";
										} else {
											$class = "";
										}
									?>	
									<li class="<?php echo $class; ?>">
										<?php echo $row_items['choice_'.$i]; ?>
									</li>
								<?php } else if ($row_items['choice_'.$i] != "" && $row_items['type'] == QUESTION_LONG) { ?>
									<?php if ($i == 1) { ?>
									<p>
										<?php 
											if ($row_items['choice_'.$i] > 0 ) 
												echo $row_items['choice_'.$i] . ' Words'; 
											else 
												echo 'No Word Limit'; 
										?>
									</p>
									<?php } ?>
								<?php }	?>
							<?php }	?>
							</ol>
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
					</div>
				</td>
				<!-- YOUR ANSWER ----------------->
				<td>
			  
					<?php if($check == TRUE){ 
						
						for ($i=1; $i <= 6; $i++) { ?>
						<?php if ($row_items['choice_'.$i] != "" && $row_items['type'] != QUESTION_LONG) { ?>
								<?php $flg =0;
									//FOR TRUE/FALSE
									if($row_items['type'] == QUESTION_TF){
										if($your['answer_'.$i] == $i){
											echo "True";
											$i=7;
										}else{
											echo "False";
											$i=7;
											}
									}
									if($row_items['type'] == QUESTION_MCMC || $row_items['type'] == QUESTION_MCSC){
										if($your['answer_'.$i] == $i){
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
							echo '<span class="text-danger">Not Attempted</span>';
						}?>	
				</td>
				<!-- Marks------------>
		
				<td>
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
				<td><?php 
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
									echo '<span class="text-success"><i class="icon-ok"></i></span>';
								} else {
									echo '<span class="text-danger"><i class="icon-remove"></i></span>';
								}
							} else {
								echo '<span class="text-danger"><i class="icon-remove"></i></span>';
							}

						/*Response for Question type True False And MCSC*/   
						}elseif(($row_items['choice_'.$i] != ""  && $row_items['type'] == QUESTION_MATCH)){
								for ($i=1; $i <= 6; $i++) {
									if ($i == $your['answer_'.$i]){
										echo '<p>'.$i.'. <span class="text-success"><i class="icon-ok"></i></span></p>';
									} else {
										echo '<p>'.$i.'. <span class="text-danger"><i class="icon-remove"></i></span></p>';
									}									
								}	
						} else {
							if($your['answer_'.$i] != ""){
								if($your['answer_'.$i] == $row_items['answer_'.$i]){
									echo '<span class="text-success"><i class="icon-ok"></i></span>';
								}else {
									echo '<span class="text-danger"><i class="icon-remove"></i></span>';
								}
							}
						}
					}
				} else if ($check == FALSE) {
					echo '<span class="text-danger"><i class="icon-thumbs-down-alt"></i></span>';
				}
				?>	
					</td>
				</tr>
			<?php }	?>
		</tbody>
	</table>
</div>
	
	<div class="col-md-2 pull-right">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Test Details</h4>
			</div>
			<div class="widget-content">
				<?php $test = $this->tests_model->view_tests ($tid); ?>			
				  <dl>
					<?php  $obt_per = (($tot/$mm)*100); ?>
					<dt><?php echo 'Test Name';?></dt>
					<dd><?php echo $test['title'];?></dd>
					
					<dt><?php echo 'Max Marks';?></dt>
					<dd><?php echo $test['max_marks'];?></dd>
					
					<?php $pass_marks = $test['max_marks'] * ($test['pass_marks'] / 100); ?>
					<dt><?php echo 'Pass Marks';?></dt>
					<dd><?php echo round( $pass_marks, 2);?></dd>				
							
					<dt><?php echo 'Obtained Percentage';?>
					<dd><?php echo $obt = round($obt_per) . '%' ;?></dd>
					
					<dt><?php echo 'Marks Obtained';?></dt>
					<dd><?php echo "<span class='text-brown text-bold'>".$tot."</span> / ".$mm."</strong>";?></dd>					
					
					<dt><?php echo 'Result';?></dt>
					<dd>
					<?php 
					if ($obt_per >= $test['pass_marks']) {
						echo '<span class="text-darkgreen">Pass</span>';
					} else {
						echo '<span class="text-crimson">Fail</span>';
					}
					?>
					</dd>
				</dl>
			</div>
		</div>
		
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i>Student Details</h4>
			</div>
			<div class="widget-content">
				<?php $member = $this->users_model->get_user($member_id); ?>
			
				  <dl>
					<?php  $obt_per = (($tot/$mm)*100); ?>
					<dt><?php echo 'Login';?></dt>
					<dd><?php echo $member['login'];?></dd>
					
					<dt><?php echo 'Name';?></dt>
					<dd><?php echo $member['first_name']." ".$member['last_name'] ;?></dd>
				</dl>				
			</div>						
		</div>
		
		
	<?php } 
	} else { ?>
		<div class="alert alert-info"><?php echo 'Select an attempt to get report.';?></div>		
	<?php } ?>
</div>