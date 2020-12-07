<?php
for ($i=1; $i<=QB_NUM_ANSWER_CHOICES; $i++) : 
	if ($i <= 3) {
		$required_cl = 'required';
		$required_sp = '<span class="required text-danger">*</span>';
	} else {
		$required_cl = '';
		$required_sp = '';
	}
	?>
	<div class="form-group row" >
		<div class="col-md-2">
			<label class="form-label" >
				<input type="radio" name="answer" value="<?php echo $i; ?>" <?php if ( $result['answer_'.$i] == $i) echo 'checked="checked"'; ?> class="control-input" id="answer_<?php echo $i; ?>" /> <?php echo 'Choice '.$i . $required_sp; ?>
			</label>
		</div>

		<div class="col-md-10">
			<textarea name="choice[<?php echo $i; ?>]" class="form-control tinyeditor <?php echo $required_cl; ?>" row="20"  autofocus=""><?php echo set_value ('choice['.$i.']', $result['choice_'.$i]); ?></textarea>
		</div>
	</div>
	<?php 
endfor; // $i
?>