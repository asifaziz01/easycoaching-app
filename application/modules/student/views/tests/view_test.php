<section class="page-section">
	<div class="row">
		
		<div class="col-md-8 col-md-offset-2"> 				
			<div class="widget box">
				<div class="widget-header">	
					<h4>Test Details</h4>
				</div>
				<div class="widget-content no-padding">
					<div class="list-group">
						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Test Title	</label>
								<div class="col-md-9"><?php echo $row['title']; ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Questions	</label>
								<div class="col-md-9">
								<?php 
								// Num questions
								$questions = $this->tests_model->getTestQuestions ( $test_id ); 
								$num_questions = 0;
								if ( is_array ($questions) ) {
									$num_questions = count($questions);
								}
								echo $num_questions;
								?>
								</div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Marks</label>
								<div class="col-md-9"><?php echo $row['max_marks']; ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Duration</label>
								<div class="col-md-9"><?php echo $row['time_min'] . ' minutes'; ?></div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Pass Percentage</label>
								<div class="col-md-9"><?php echo $row['pass_marks'] . ' %'; ?></div>
							</div>
						</li>

						<li class="list-group-item">
							<div class="row">
								<label class="col-md-3">Allowed Attempts </label>
								<div class="col-md-9">
									<?php 
									if ($row['num_takes'] == 0) {
										echo 'Unlimited';
									} else {
										echo $row['num_takes'];	
									}
									?>
								</div>
							</div>							
						</li>
						
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-12">
									<?php 
									if ($page == "") {
										echo anchor ('tests/frontend/index/'.$category_id, '<i class="icon fa fa-arrow-left"></i> Back ', array ('class'=>'btn btn-default') );
									} else {
										echo anchor ('tests/frontend/'.$page, '<i class="icon fa fa-arrow-left"></i> Back ', array ('class'=>'btn btn-default') );						
									}
									
									if ($sub_cat_id > 0) {										
										echo anchor ('tests/frontend/take_test/'.$category_id.'/'.$test_id, 'Take Test', array ('class'=>'btn btn-info'));
									}
									?>
								</div>
							</div>							
						</li>
						
						<?php 
						// test taken by
						$taken_by = $this->tests_model->testTakenBy ( $test_id ); 
						?>						
					</div>					
					
				</div>
			</div>
			
			<p>
				<?php 
				
				?> 
			</p>
		</div><!-- End col-md-8  -->
		
		<aside class="col-md-2">
			
			<div class="panel panel-default">
			
				<!-- Created By -->
				<div class="panel-body">
					<h4>Test Controller</h4>
					<?php 
					$created_by = $row['created_by'];
					$controller = $this->users_model->get_user ($created_by);
					$name = $controller['first_name'] . ' '. $controller['last_name'];
					?>
					<div class="pull-right">
						<?php $profile_image = $this->users_model->view_profile_image ($created_by); ?>
						<img src="<?php echo base_url ($profile_image); ?>" alt="Teacher" class="img-circle styled" width="80" height="80">
					</div>
					<div class="media-body">
						<h5 class="media-heading"><?php echo $name; ?></h5>
					</div>
				</div>
				
			</div>
				
			<?php if ($row['test_type'] == TEST_TYPE_REGULAR || $row['test_type'] == TEST_TYPE_PRACTICE) { ?>
				<?php if ($sub_cat_id > 0) { ?>
					<a href="<?php echo site_url ('tests/frontend/take_test/'.$category_id.'/'.$row['test_id']); ?>" class="btn btn-info btn-lg btn-block " > Take Test </a>
				<?php } ?>
			<?php } else { ?> 
				<a href="<?php echo site_url ('public/tests/take_mocktest/'.$row['test_id']); ?>" class="btn btn-info btn-lg btn-block " > Take Test </a> 			
			<?php } ?>
			<br>
			<br>		
			
			
			
		</aside> <!-- End col-md-4 -->
		
	</div><!-- End row -->		
	
</section>