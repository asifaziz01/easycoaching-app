<div class="card">
	<div class="card-body">
        <?php if ( ! empty ($results)) { ?>
			<div class="table-responsive">
				<table data-toggle="data-table" class="table" cellspacing="0" width="100%">
					<thead>
						<tr>                              
							<th width="5%"><?php echo '#'; ?></th>
							<th width="75%"><?php echo 'Test Name'; ?></th>
							<th width="" align="right"><?php echo 'Action'; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						foreach ($results as $row) { 
							?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td>
									<?php echo anchor('coaching/tests/preview_test/'.$row['category_id'].'/'.$row['test_id'], $row['title']); ?>
									<p class="text-caption margin-none">
										<?php 
										// Test Marks
										$test_marks = $this->tests_model->getTestquestionMarks ($row['test_id']);
										$marks = $test_marks['marks'];

										// Test Questions
										$test_questions = $this->tests_model->getTestquestions ($row['test_id']);
										if ( is_array ($test_questions)) 
											$questions = count ($test_questions);
										else 
											$questions = 0;

										// Test Duration
										$duration = $row['time_min'] . ' mins';
										?>
										<i class="fa fa-clock-o fa-fw"></i> <?php echo $duration; ?> | 
										<i class="fa fa-superscript fa-fw"></i> Questions:<?php echo $questions; ?> | 
										<i class="fa fa-check fa-fw"></i> Marks: <?php echo $marks; ?>
									</p>
								</td>
								
								<td>
									<div class="btn-group">
										<a href="<?php echo site_url ('coaching/tests/manage/'.$row['category_id'].'/'.$row['test_id']); ?>" onclick="" class="btn btn-xs btn-info" title="Manage Test"><i class="fa fa-cog"></i> Manage </a>
									</div>											
								</td> 
							</tr>
							<?php
						} 
						?>
					</tbody>
				</table>
			</div>
        <?php } else { ?>
            <div class="alert alert-danger">
                <p>No tests found</p>
            </div>
        <?php } ?>
	</div>
	<div class="card-footer">
		<div class="btn-toolbar">
			<?php 
			echo anchor ('coaching/tests/index/'.$category_id, '<i class="fa fa-arrow-left"></i> Back', array('class'=>'btn btn-default'));
			?>
		</div>
	</div>
</div>
