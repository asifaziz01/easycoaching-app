<?php 
/* Different answer options for each question type */
switch ($question_group['type']) {
	// Long Answer
	// ====================================
	case QUESTION_LONG:
		?>
		<div id="accordion_longa">
			<h3 class="block padding-bottom-10px">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_longa" href="#longa_more">
				More </a>
			</h3>
			<div id="longa_more" class="panel-collapse collapse ">
				<div class="form-group" >
					<label class=" control-label">
						Word Limit
					</label>
					<div class="">
						<?php 
						   echo form_input (array('name'=>'choice[1]','class'=>'form-control input-width-small', 'maxlength'=>'5', 'value'=>set_value ('choice[1]', $result['choice_1'])));
						?>
					</div>
				</div>
				<div class="form-group" >
					<label class=" control-label">
						Sample Answer
					</label>
					<div class="">
						<textarea name="choice[2]" class="form-control tinyeditor" row="20"  autofocus=""><?php echo set_value ('choice[2]', $result['choice_2']); ?></textarea>						
					</div> 
				</div>
			</div>
		</div>
		<?php
	break;
	// True False questions
	// ====================================
	case QUESTION_TF:
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
			<div class='form-check mb-4'>
				<input type="radio" name="answer" value="1" <?php echo set_radio ( 'answer', 1, $true_ch); ?> class="form-check-input" id='true' /> 
				<label class="form-check-label" for='true'>
					True							
				</label>
			</div>
			<div class="">
				<p class="form-control-static"></p>
			</div>
		</div>
		<div class="form-group" >
			<div class='form-check mb-4'>
				<input type="radio" name="answer" value="2" <?php echo set_radio ( 'answer', 2, $false_ch); ?> class="form-check-input" id='false' />
				<label class="form-check-label" for='false'>
					False
				</label>
			</div>
			<div class="">
				<p class="form-control-static"></p>
			</div>
		</div>
		<?php 
	break;
	// Matching questions
	// ====================================
	case QUESTION_MATCH:
		$j = 'A';
		for ($i=1; $i<=QB_NUM_ANSWER_CHOICES; $i++) : 
			if ($i <= 3) {
				$required_cl = 'required';
				$required_sp = '<span class="required text-danger">*</span>';
			} else {
				$required_cl = '';
				$required_sp = '';
			}
			?>
			<div class="form-group" >
				<div class="col-md-1">
					<?php echo $i; ?>
				</div>
				<div class="col-md-5"> 
					<textarea name="choice[<?php echo $i; ?>]" class="form-control <?php echo $required_cl; ?>" row="20"  autofocus=""><?php echo set_value ('choice['.$i.']', $result['choice_'.$i]); ?></textarea>										
				</div>
				<div class="col-md-1">
					<?php echo $j++; ?>
				</div>
				<div class="col-md-5">
					<textarea name="option[<?php echo $i; ?>]" class="form-control <?php echo $required_cl; ?>" row="20"  autofocus=""><?php echo set_value ('option['.$i.']', $result['option_'.$i]); ?></textarea>										
				</div>
			</div>
			<?php 
		endfor; // $i			
	break;
	// Multi choice multi correct questions
	// ====================================
	case QUESTION_MCMC:
		for ($i=1; $i<=QB_NUM_ANSWER_CHOICES; $i++) : 
			if ($i <= 3) {
				$required_cl = 'required';
				$required_sp = '<span class="required text-danger">*</span>';
			} else {
				$required_cl = '';
				$required_sp = '';
			}
			?>   
			<div class="form-group" >
				<div class='form-check mb-4'>
					<input type="checkbox" name="answer[<?php echo $i; ?>]" value="<?php echo $i; ?>" <?php if ($result['answer_'.$i] == $i) echo 'checked="checked"'; ?> class="form-check-input" id ="<?php echo $i; ?>"/>
					<label class="form-check-label" for="<?php echo $i; ?>" >
						<?php echo 'Choice '.$i . $required_sp; ?>
					</label>
				</div>
				<div class="">
					<textarea name="choice[<?php echo $i; ?>]" class="form-control <?php echo $required_cl; ?>" row="20"  autofocus=""><?php echo set_value ('choice['.$i.']', $result['choice_'.$i]); ?></textarea>										
				</div>
			</div>
			<?php 
		endfor; // $i
	break;
	// Default (Multi choice single correct)
	// =====================================
	default:
		for ($i=1; $i<=QB_NUM_ANSWER_CHOICES; $i++) : 
			if ($i <= 3) {
				$required_cl = 'required';
				$required_sp = '<span class="required text-danger">*</span>';
			} else {
				$required_cl = '';
				$required_sp = '';
			}
			?>
			<div class="form-group" >
				<label class="control-label" for="answer_<?php echo $i; ?>">
					<input type="radio" name="answer" value="<?php echo $i; ?>" <?php if ( $result['answer_'.$i] == $i) echo 'checked="checked"'; ?> class="control-input" id="answer_<?php echo $i; ?>" /> 
					<?php echo 'Choice '.$i . $required_sp; ?>
				</label>
				<textarea name="choice[<?php echo $i; ?>]" class="form-control tinyeditor <?php echo $required_cl; ?>" row="20"  autofocus=""><?php echo set_value ('choice['.$i.']', $result['choice_'.$i]); ?></textarea>				
			</div>
			<?php 
		endfor; // $i
	break;
}
?>   
 