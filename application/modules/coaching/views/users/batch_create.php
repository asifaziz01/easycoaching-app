<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card">
			<?php echo form_open("coaching/user_actions/create_batch/".$coaching_id."/".$batch_id, array('class'=>'form-horizontal batch-border ', 'id'=>'validate-1' )); ?>
				<div class="card-body">
					<div class="form-group row">
					  <div class="col-md-6">
						<?php echo form_label('Batch Name<span class="required">*</span>', '', array('class'=>'control-label')); ?>
							<?php echo form_input ( array('name'=>'batch_name', 'class'=>'form-control required', 'value'=>set_value('batch_name', $batch['batch_name'])) );?> 
					  </div>
					  <div class="col-md-6">
						<?php echo form_label('Batch Code<span class="required">*</span>', '', array('class'=>'control-label')); ?>
							<?php echo form_input ( array('name'=>'batch_code', 'class'=>'form-control required', 'value'=>set_value('batch_code', $batch['batch_code'])) );?>   
					  </div>
					</div>	


					<div class="form-group row">
					  <div class="col-md-6">
						<?php echo form_label('Batch Starting From', '', array('class'=>'control-label')); ?>
							<?php 
							if ($batch['start_date'] > 0){
								$start_date = date ('Y-m-d', $batch['start_date']); 
							} else {
								$start_date = "";
							}
							?>
							<?php echo form_input ( array('name'=>'start_date', 'class'=>'form-control datepicker', 'value'=>set_value('start_date', $start_date), 'type'=>'date') );?>   
					  </div>
					  <div class="col-md-6">
						<?php echo form_label('Batch Ending On', '', array('class'=>'control-label')); ?>
							<?php 
							if ($batch['end_date'] > 0){
								$end_date = date ('Y-m-d', $batch['end_date']); 
							} else {
								$end_date = "";
							}
							?>

							<?php echo form_input ( array('name'=>'end_date', 'class'=>'form-control datepicker', 'value'=>set_value('end_date', $end_date), 'type'=>'date') );?>
					  </div>
					</div>
				</div>

				<div class="card-footer">
					<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary " />
				</div>
			</form>
		</div>
	</div>
</div>
