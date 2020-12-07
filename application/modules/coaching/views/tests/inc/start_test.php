<div data-scrollable>
	<h4 class="category">Time to complete</h4>
	<div class="sidebar-block">
		<div class="tk-countdown text-danger" id="countdown-text"></div>
		<div class="tk-countdown " id="countdown">
    		<script>
				function countdownComplete() {
					alert("Time's up. Press OK to submit your test.");
					/*
					$('#test_form').submit(function() {
						alert("Time's up. Press OK to submit your test.");
						return true;
					});
					*/
					$('.col-md-8 .card-footer input[type="submit"]').click();
					//document.forms["test_form"].submit();
					//document.getElementById("test_form").submit();
					//$('#test_form').trigger('submit');
				}
			var test2 = new Countdown( {
										time: <?php echo $test_duration; ?>, 
										rangeHi : 'hour',
										width:200, 
										height:40,
										hideLine	: true,
										numbers		: 	{
										color	: "#000000",
										bkgd	: "#ffffff",
										rounded	: 0,				// percentage of size
									},
									onComplete	: countdownComplete,
									});
			</script>
		</div>
	</div>
	<h4 class="category">Questions</h4>
	<ul>
		<li>
			<div class="sidebar-block" data-height="250" data-always-visible="0" data-rail-visible="0">
				<?php
				if ( ! empty ($all_questions)) {
					$count_tabs = 1;
					$y = 0;
					foreach ( $all_questions as $subject_id=>$question_group ) {
						echo heading ($subject_wise[$subject_id], 5);
						foreach ($question_group as $group_id=>$questions) {
							foreach ($questions as $question_id) {
								$y++;
  							if (strlen ($y) < 2) {
									$y_text = '0'.$y;
								} else {
									$y_text = $y;
								}
								?>
								<a class="btn btn-sm btn-default" href="javascript:void(0)" onclick="display_question (<?php echo $y; ?>)" style="margin-top:5px" id="btn_<?php echo $y; ?>"><?php echo $y_text; ?></a> 
								<?php 
							}
						}
					}
				}
				?>
			</div>
		</li>
	</ul>	
</div>