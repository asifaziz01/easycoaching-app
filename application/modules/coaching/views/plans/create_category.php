<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card card-default">
			<div class="card-body">
				<?php echo form_open('plans/functions/create_category/'.$category_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>
					<div class="form-group row">
						<?php echo form_label('Title<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">
							<input type="text" class="form-control required" name="title" value="<?php echo set_value('title', $category['title']); ?>" />
						</div>
					</div>

					<div class="form-group row">
						<?php echo form_label('Description', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">
							<textarea name="description" class="tinyeditor form-control" rows="10"><?php echo set_value('description', $category['description']); ?></textarea>
						</div>
					</div>					
					
					<div class="form-group row">
						<?php echo form_label('Status', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="status" id="active" type="checkbox" value="1" <?php echo set_checkbox('status', $category['status'], true); ?> >
							<label for="active">Active</label>
						</div>
					</div>
				</div>				
				
				<div class="card-footer">
					<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary " accesskey="s" />
				</div>		
			</form>	
		</div>
	</div>
</div>