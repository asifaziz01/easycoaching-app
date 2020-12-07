<div data-scrollable>
    <div class="card-body mb-4">
    	<h4 class="category">Time to complete</h4>
        <div class="mobile-timer">
        	<script>
        	// Function for submit form when time is over.	
            function countdownComplete() {
            	var x = document.getElementById ('disable-timer-d').checked;
                var y = document.getElementById ('disable-timer-m').checked;
            	if ((x == false)&&(y == false)) {
            		/*
                    $("#test_form").submit(function() {
                        alert("Time's up. Press OK to submit your test.");
            		});
                    */
                    alert("Time's up. Press OK to submit your test.");
                    $("#test_form").submit ();  

                         
            	}
            
            }


        	// === *** SHOW TIMER *** === //
            new Countdown ({
                time: <?php echo $test_duration; ?> ,
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
        <div class="custom-control custom-switch">
            <input type="radio" name="timer" id="disable-timer-m" class="custom-control-input" value="0" >
    	    <label for="disable-timer-m" class="custom-control-label">Disable Auto-Submit on Time-Up</label>
        </div>
    	        
    </div>
</div>