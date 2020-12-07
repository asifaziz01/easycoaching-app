<div class="row justify-content-center">
	
	<div class="col-md-12">
		<div class="card card-default">			
			<div class="card-body">
				<?php echo form_open('coaching/tests_actions/validate_question_group_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$question_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>
					
					<div class="form-group">
						<?php echo form_label('Question Group Title<span class="required">*</span>', '', array('class'=>' control-label'));	?>
						<div class="">
							<?php echo form_textarea(array('name'=>'question', 'class'=>'form-control required tinyeditor','rows'=>'5', 'value'=>set_value ('question', $result['question']), 'autofocus'=>true )); ?>
							<p class="help-block">Example: Answer the following questions </p>
						</div>
					</div>					

					<!-- override negative marks -->
					<?php echo form_hidden (array('name'=>'negmarks', 'value'=>'0')); ?>
				
					<div class="form-group">
						<?php echo form_label('Marks per question<span class="required">*</span>', '', array('class'=>'control-label')); ?>
						<div class="w-25">
							<?php echo form_input(array('type'=>'number', 'name'=>'marks', 'class'=>'form-control required input-width-mini digits', 'min'=>1, 'value'=>set_value('marks', $result['marks']) ));?>
						</div>
						<p class="text-muted">You can be change this for individual question</p>
					</div>
					
					<hr>
					
					<div class="btn-toolbar">
						<?php
							echo form_submit (array ('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-primary')); 
						?>
					</div>
				<?php echo form_close() ?>
			</div>
		</div>
</section>