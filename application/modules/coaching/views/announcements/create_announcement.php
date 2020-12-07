<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card">
			
			<div class="card-body">
				<?php echo form_open('coaching/announcement_action/create/'.$coaching_id.'/'.$announcement_id, array('class'=>'form-horizontal row-border', 'class'=>'validate-form')); ?>		
					<div class="form-group">
						<input type="hidden" class="form-control" name="coaching_id" value="" />
					</div>
					<div class="form-group">
						<?php echo form_label('Title<span class="required">*</span>', '', array('class'=>'control-label')); ?>
						<input type="text" class="form-control required" name="title" value="<?php echo set_value('title', $result['title']); ?>" />
					</div>

					<div class="form-group row">
						<div class="col-md-12">
							<?php echo form_label('Description', '', array('class'=>'control-label')); ?>
							<textarea class="form-control required tinyeditor" name="description" placeholder="" rows="5"><?php echo set_value('description', $result['description']); ?></textarea>
							
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Start Date', '', array('class'=>'control-label')); ?>
							<?php 
								if ($result['start_date'] != '') {
									$start_date = date ('Y-m-d', $result['start_date']);
								} else {
									$start_date = date("Y-m-d");
								}
							?>
							<input name="start_date" type="date" class="form-control required" value="<?php echo set_value('start_date', $start_date); ?>"/>
						</div>
						<div class="col-md-6">
							<?php echo form_label('End Date', '', array('class'=>'control-label')); ?>
							<?php 
								if ($result['end_date'] != '') {
									$end_date = date ('Y-m-d', $result['end_date']);
								} else {
									$end_date = date("Y-m-d", time ()+86400);
								}
							?>
							<input name="end_date" type="date" class="form-control required "  value="<?php echo set_value('end_date', $end_date); ?>"/>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-6 mt-3">
						  <?php echo form_label('Status', '', array('class'=>'control-label pr-5')); ?>
       					  <div class="custom-control custom-switch">
							  <input type="checkbox" name="status" class="custom-control-input" id="status" value="1" <?php if ($result['status'] == 1 ) echo 'checked';?> checked >
							  <label class="custom-control-label" for="status">Publish </label>
						  </div>

						</div>
						
					</div>					
				</div>

				<div class="card-footer">					
					<p class="btn-toolbar">
						<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary " accesskey="s" />
					</p>
				</form>
			</div>
		</div>
	</div>
</div>