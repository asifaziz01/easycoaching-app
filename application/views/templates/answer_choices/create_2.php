<?php
$true_ch  = false;
$false_ch = false;
if ($result['answer_1'] == 1) {
	$true_ch = true;
}
if ($result['answer_2'] == 2) {
	$false_ch = true;
}
?>
<div class="form-group" > 
	<div class='form-check '>
		<input type="radio" name="answer" value="1" <?php echo set_radio ( 'answer', 1, $true_ch); ?> class="form-check-input" id='true' /> 
		<label class="form-check-label" for='true'>
			True
		</label>
	</div>
</div>
<div class="form-group" >
	<div class='form-check'>
		<input type="radio" name="answer" value="2" <?php echo set_radio ( 'answer', 2, $false_ch); ?> class="form-check-input" id='false' />
		<label class="form-check-label" for='false'>
			False
		</label>
	</div>
</div>