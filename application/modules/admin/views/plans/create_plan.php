<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card card-default">
			<div class="card-body">
				<?php echo form_open('admin/plan_actions/create_plan/'.$plan_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>
					<div class="form-group row">
						<?php echo form_label('Title<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">
							<input type="text" class="form-control required" name="title" value="<?php echo set_value('title', $plan['title']); ?>" />
						</div>
					</div>

					<div class="form-group row">
						<?php echo form_label('Category', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">
                            <select name="category" class="form-control">
                                <option value="0"><?php echo 'Select'; ?></option>

                                <?php 
                                if (! empty($categories)) {
                                    foreach ($categories as $cat) {
                                        ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id']==$plan['category_id']) echo 'selected="selected"'; ?>><?php echo $cat['title']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
						</div>
					</div>
					
					<div class="form-group row">
						<?php echo form_label('Description', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-9">
							<textarea name="description" class="tinyeditor form-control" rows="3"><?php echo set_value('description', $plan['description']); ?></textarea>
						</div>
					</div>
					
					<div class="form-group row">
					
						<?php echo form_label('Price (Rs)<span class="required">*</span>', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="price" type="number" class="form-control digits required" value="<?php echo set_value('price', $plan['amount']); ?>"  placeholder="Price" /> 
							Per Month
						</div>

						<?php echo form_label('Status', '', array('class'=>'col-md-2 control-label')); ?>
						<div class="col-md-3 ">
							<input name="status" id="checkbox1" type="checkbox" value="1" <?php echo set_checkbox('status', $plan['status']); ?>>
							<label for="checkbox1">Active</label>
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