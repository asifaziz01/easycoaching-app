<div class="row">
	<div class="col-md-12 "> 
		<?php echo form_open('admin/subscription_actions/create_plan/'.$plan_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>
			<div class="card card-default">
				
				<div class="card-body">  
					
					<div class="form-group row">
						<?php echo form_label('Title<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">	
							<input type="text" class="form-control required" name="title" value="<?php echo set_value('title', $plan['title']); ?>" />
						</div>
					</div>

					<div class="form-group row">
						<?php echo form_label('Description ', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">	
							<textarea class="form-control" name="description"><?php echo set_value('description', $plan['description']); ?></textarea>
						</div>
					</div>

					<div class="form-group row">
						<?php echo form_label('Duration (Months)<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="duration" type="text" class="form-control digits required" value="<?php echo set_value('duration', $plan['duration']); ?>"  placeholder="Months" /> 
						</div>

						<?php echo form_label('Price (Rs)<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="price" type="text" class="form-control digits required" value="<?php echo set_value('price', $plan['price']); ?>"  placeholder="Price" /> 
						</div>

					</div>

					<div class="form-group row">

						<?php echo form_label('Max Users<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="max_users" type="text" class="form-control digits required" value="<?php echo set_value('max_users', $plan['max_users']); ?>"  placeholder="" /> 
						</div>

						<?php echo form_label('Status', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">

							<div class="">
								<input name="status" id="checkbox1" type="checkbox" value="1" <?php if($plan['status'] == 1) echo 'checked="checked"'; ?>>
								<label for="checkbox1">Active</label>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-footer">
					<div class="btn-toolbar">
						<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-success pull-right" accesskey="s" />
					</div>
				</div>
				
			</form>	
		</div>
	</div>
</div>
