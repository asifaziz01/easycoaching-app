<div class="row justify-content-center">
	<div class="col-md-12 ">
		<div class="card">
			<div class="card-header">
				<h4>Error</h4>
			</div>
			
			<div class="card-body">
				<?php if ($error == TEST_ERROR_MAX_ATTEMPT_REACHED) { ?>
					<div class="alert alert-danger ">
						Your maximum attempts for this test has reached the limit.
						<p>You cannot attempt this test any more.</p>
					</div>
				<?php } else if ($error == TEST_ERROR_RECENTLY_TAKEN) { ?>
					<div class="alert alert-danger">
						<strong>Test locked!</strong>.
						<p>This test has been locked, as you have recently taken this test. Next attempt will be avalailable only after given time <br>
							<a href="<?php echo site_url ('student/tests/request_reset/'.$coaching_id.'/'.$member_id.'/'.$test_id); ?>" onclick="" class="btn btn-warning btn-sm"> Request Reset  </a>
						</p>
					</div>

					<div class="mt-2">
						<script src="<?php echo base_url (THEME_PATH . 'assets/js/countdown.min.js'); ?>"></script>
						<script language="javascript">
							// Function for submit form when time is over.	
							function countdownComplete(){
								document.location = '<?php echo site_url ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$test_id); ?>';
							}
							// === *** SHOW TIMER *** === //
							var test2 = new Countdown( {  
													time: <?php echo $time_remaining; ?> , 
													rangeHi : 'hour',
													width:200, 
													height:60,
													hideLine	: true,
													numbers		: 	{
														color	: "#000000",
														bkgd	: "#ffffff",
														rounded	: 0.15,				// percentage of size
													},											
													onComplete	: countdownComplete
												} );
							var CountdownImageFolder = "images/"; 
							var CountdownImageBasename = "flipper";
							var CountdownImageExt = "png";
							var CountdownImagePhysicalWidth = 41;
							var CountdownImagePhysicalHeight = 90;
							
						</script>
					</div>

				<?php } else if ($error == TEST_ERROR_OFFLINE_TEST) { ?>
					<div class="alert alert-danger ">
						<strong>This Test is OFFLINE, held only in Campus.</strong>						
					</div>
				<?php } else if ($error == TEST_ERROR_UNPUBLISHED) { ?>
					<div class="alert alert-danger ">
						This test is not published at this moment
					</div>
				<?php } else if ($error == TEST_ERROR_NO_QUESTION) { ?>
					<div class="alert alert-danger ">
						There are no questions in this test.
					</div>
				<?php } ?>

			</div> <!-- // widget-content -->
		</div> <!-- // widget -->
	</div>
</div>