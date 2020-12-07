<div data-scrollable>
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="category">Time to complete</h4>
        </div>
        <div class="card-body">
            <div class="desktop-timer d-flex justify-content-center mb-2">
            	<script>
                    var offset_Width = document.getElementsByClassName("desktop-timer")[0].offsetWidth;
                    countdownWidth = (offset_Width<200)?offset_Width:200;
            	// Function for submit form when time is over.	
                /*function countdownComplete() {
                	var x = document.getElementById ('disable-timer-d').checked;
                    var y = document.getElementById ('disable-timer-m').checked;
                	if ((x == false)&&(y == false)) {
                		
                        $("#test_form").submit(function() {
                            alert("Time's up. Press OK to submit your test.");
                		});
                        
                        alert("Time's up. Press OK to submit your test.");
                        $("#test_form").submit ();  

                             
                	}
                
                }*/


            	// === *** SHOW TIMER *** === //
                new Countdown ({
                    time: <?php echo $test_duration; ?> ,
                    rangeHi : 'hour',
                    width:countdownWidth,
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
        <div class="card-footer bg-transparent">
            <div class="custom-control custom-switch">
                <input type="radio" name="timer" id="disable-timer-d" class="custom-control-input" value="0" >
        	    <label for="disable-timer-d" class="custom-control-label">Disable Auto-Submit on Time-Up</label>
            </div>
        </div>  
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Question Navigation</h4>
        </div>
        <div class="card-body">
            <div class="sidebar-btn">
                <button type="button" class="btn btn-secondary text-white previous" href="javascript:void(0);"> <i class="fa fa-arrow-left"></i> Previous </button> 
                <button type="button" class="btn btn-primary next float-right" href="javascript:void(0);">Next <i class="fa fa-arrow-right"></i> </button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title category">Questions</h4>
        </div>
    	<div class="card-body max-height-150 height-150-lg overflow-auto">
    		<?php
    		if ( ! empty ($all_questions)) {
    			$count_tabs = 1;
    			$y = 0;
    			foreach ( $all_questions as $subject_id=>$question_group ) {
    				//echo heading ($subject_wise[$subject_id], 5);
    				foreach ($question_group as $group_id=>$questions) {
    					foreach ($questions as $question_id) {
    						$y++;
    					if (strlen ($y) < 2) {
    							$y_text = '0'.$y;
    						} else {
    							$y_text = $y;
    						}
    						?>
    						<a class="btn btn-sm btn-secondary text-white" href="javascript:void(0)" onclick="display_question (<?php echo $y; ?>)" style="margin-top:5px" id="btn_<?php echo $y; ?>"><?php echo $y_text; ?></a> 
    						<?php 
    					}
    				}
    			}
    		}
    		?>
    	</div>
	</div>
</div>