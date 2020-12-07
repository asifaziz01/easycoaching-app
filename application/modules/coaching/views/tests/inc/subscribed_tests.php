<div class="widget box"> 
	<div class="widget-header">
		<h4><?php echo 'Available Tests'; ?></h4>
		<?php
		// Page Specific ToolBar Buttons
		if ( isset ($toolbar_buttons) > 0) { 
			echo $this->common_model->generate_toolbar ($toolbar_buttons); 
		} 
		?> 
	</div><!-- /.widget-header -->
	
	<?php 
	if ( $sub_cat_id > 0 ) { 
		if ( ! empty ($results)) {
		?>
		<div class="widget-content no-padding">
			<table class="table table-striped table-hover table-checkable datatable">
				<thead>
					<tr>                              
						<th width=""><?php echo '#'; ?></th>
						<th width=""><?php echo 'Test Name'; ?></th>
						<th width=""><?php echo 'Questions'; ?></th>
						<th width=""><?php echo 'Marks'; ?></th>
						<th width=""><?php echo 'Duration'; ?></th>
						<th width="20%" align="right"><?php echo 'Options'; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($results as $row) { 
						?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $row['title']; ?></td>
							<td>
								<?php 
								$test_questions = $this->tests_model->getTestquestions ($row['test_id']);
								if ( is_array ($test_questions)) echo count ($test_questions);
								else echo '0';
								?>
							</td>
							<td>
								<?php 
								$test_marks = $this->tests_model->getTestquestionMarks ($row['test_id']);
								echo $test_marks['marks'];
								?>
							</td>
							<td>
								<?php 
								echo $row['time_min'] . ' mins';
								?>
							</td>
							
							<td>
								<div class="btn-group ">
									<?php 
									echo anchor ('tests/page/take_test/'.$sub_cat_id.'/'.$row['test_id'], 'Take Test ', array('class'=>'btn btn-sm btn-success'));
									// Report
									$attempts = $this->tests_model->get_attempts ($member_id, $row['test_id']);
									if ( ! empty($attempts)) {
										echo anchor ('tests/reports/all_reports/0/'.$member_id.'/'.$row['test_id'], 'Report', array ('class'=>'btn btn-info btn-sm ')); 
									}
									$bc = 'tests-page-subscribed_tests-'.$category_id;
									/*
									 | Get all chapters of the questions in this test
									*/
									// All test questions
									$all_questions = $this->tests_model->getTestQuestions ($row['test_id']);
									/* Perpare an array in form of subject->question_group->question */
									$collect = array ();
									if ( ! empty ($all_questions)) {					
										foreach ($all_questions as $qid) {
											// get details
											$details = $this->qb_model->getQuestionDetails ($qid);
											$parent_id = $details['parent_id'];
											
											// Chapter ID
											$chapter_id = $details['chapter_id'];
											
											$collect[$chapter_id] = $chapter_id;
										}
									}
									$contents = false;
									if (!empty($collect)) {
										foreach ($collect as $chapter_id) {
											if ($this->lessons_model->get_pages ($chapter_id)){
												$contents = true;
											}
										}
									}
									if ($contents == true) {
										echo anchor ('lessons/page/learn/0/0/'.$row['test_id'].'/'.$member_id.'/'.$bc, 'Learn ', array('class'=>'btn btn-sm btn-info')); 
									}
									?>									
								</div>
								
							</td> 
						</tr>
						<?php
					} 
					?>
					</tbody>
				</table>
			</div>
		<?php 
		} else { // if result
			?>
			<div class="widget-content">
				<div class="alert alert-danger">
					<h4>No Tests Found</h4>
					<p>There are no tests in this test category.</p>
				</div>
			</div>
			<?php
		}
	} 
	?>
</div> 
