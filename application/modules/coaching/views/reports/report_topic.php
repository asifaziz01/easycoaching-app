<div class="row">			
	<div class="col-md-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="20%"><?php echo 'Topic'; ?></th>
					<th width=""><?php echo 'Questions'; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( is_array ($results) ) {
					$num = 1;
					$total = 0;
					$count_chapter = 1;
					$remQuestions = count($results);
					foreach ( $results as $chapter_id=>$question_group ) {
						/* TAB CONTENT */
						?>
						<tr>
							<td>
								<?php 
								$chapter = $this->common_model->node_details ($chapter_id, SYS_TREE_TYPE_QB);
								echo $chapter['title'];
								?>
							</td>
							<td>
								<ul class="list-unstyled">
								<?php
								foreach ($question_group as $qg_id=>$questions) {
									echo '<li>';
										$heading = $this->qb_model->getQuestionDetails ($qg_id );
										echo ( $heading['question']);
										if ( ! empty ($questions)) {					
											echo '<ol>';
											foreach ($questions as $question_id) {
												$row = $this->qb_model->getQuestionDetails ($question_id );
												echo '<li>'.$row['question'];
													echo $this->qb_model->display_answer_choices($row['type'], $row);
													$response = $this->tests_reports->check_test_question ($attempt_id, $test_id, $question_id, $member_id);
													?>
													<div class="row">
														<div class="col-md-4">
															<?php
															if ($response['answered'] == 1) {
																echo '<span class="label label-danger"><i class="fa fa-times fa-2x"></i></span>';
															} else if ($response['answered'] == 2) {
																echo '<span class="label label-success"><i class="fa fa-check fa-2x"></i></span>';
															} else if ($response['answered'] == 0) {
																echo '<span class="label label-danger">Not Attempted</span>';
															}
															?>
														</div>
														<div class="col-md-4">
															<?php 
															if ( ! empty ($response['user_answer'])) {
																echo 'Your Answer: ';
																foreach ($response['user_answer'] as $user_ans) {
																	echo '<label>'.$row['choice_'.$user_ans].'</label>';
																}
															}
															?>
														</div>
														<div class="col-md-4">
															<?php
																$om = $response['om'];
																$total = $om + $total;
																echo '<strong>Marks: ' . $om."/".$row['marks'].'</strong>';
															?>
														</div>
													</div>
													<?php
												echo '</li>';
											}
											echo '</ol>';
										}
									echo '</li>';
								}
								?>
								</ul>
							
							</td> 
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<td colspan="4">No Topics To Show</td>
					</tr>
					
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>
