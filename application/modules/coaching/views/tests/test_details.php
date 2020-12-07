<div class="row">
	<div class="col-md-12 "> 
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="fa fa-puzzle-piece"></i> <?php echo 'Test Details'; ?></h4>
			</div>
			<div class="widget-content">  
				<?php echo form_open('tests/page/create_test/'.$category_id.'/'.$test_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>					
					
					<div class="form-group hidden">
						<?php echo form_label('Test Id ', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-6">
							<?php 
								echo '<p class="form-control-static">'.$results['unique_test_id'].'</p>';			
							?>
						</div>
					</div>
					
					<div class="form-group">
						<?php echo form_label('Title', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-9">
							<p class="form-control-static"><?php echo $results['title']; ?></p>
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label('Marks', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-3">	
							<p class="form-control-static">
								<?php 
								$marks = $this->tests_model->getTestQuestionMarks ($test_id);
								echo $marks['marks']; 
								?>
							</p>
						</div>
						
						<?php echo form_label('Passing Percentage', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-3">
							<p class="form-control-static"><?php echo $results['pass_marks']; ?>%</p>
						</div>
					</div>			
					
					<div class="form-group">
						<?php echo form_label('Questions', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-3">	
							<p class="form-control-static">
								<?php 
								$questions = $this->tests_model->getTestQuestions ($test_id);
								if ($questions) {
									echo count ($questions);
								} else {
									echo 'None';
								}
								?>
							</p>
						</div>
						
					</div>			
					
					<div class="form-group">
						<?php echo form_label('Duration ', '', array('class'=>'col-md-3 control-label')); ?>
						<div class="col-md-3 hidden">
							<p class="form-control-static"><?php echo $results['time_hour']; ?></p> 
						</div>
						<div class="col-md-3">
							<p class="form-control-static"><?php echo $results['time_min']; ?> minutes</p> 
						</div>
					</div>	
					
					<div class="form-actions">
						<?php 
						?>
					</div>						
				</form>	
				
			</div>
		</div>
	</div>
</div>