<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card">
			
			<div class="card-body">
				<?php echo form_open('coaching/tests_actions/create_test/'.$coaching_id.'/'.$category_id.'/'.$test_id, array('class'=>'form-horizontal row-border', 'class'=>'validate-form')); ?>		
					
					<div class="form-group">
						<?php echo form_label('Title<span class="required">*</span>', '', array('class'=>'control-label')); ?>
						<input type="text" class="form-control required" name="title" value="<?php echo set_value('title', $results['title']); ?>" />
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Category', '', array('class'=>'control-label')); ?>
							<select class="form-control required " name="category">
								<option value="0" <?php if ($category_id==0) echo 'selected="selected"'; ?>>Uncategorized</option>
								<?php 
								if (! empty($categories)) {
									foreach ($categories as $cat) {
										?>
										<option value="<?php echo $cat['id']; ?>" <?php if ($category_id == $cat['id']) echo 'selected="selected"'; ?> ><?php echo $cat['title']; ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Passing Percentage<span class="required">*</span>', '', array('class'=>'control-label')); ?>
							<input name="pass_marks" type="text" class="form-control digits required " maxlength='3' value="<?php echo set_value('pass_marks', $results['pass_marks']); ?>" placeholder="%"  />
						</div>
						<div class="col-md-6">
							<?php echo form_label('Test Duration (minutes)<span class="required">*</span>', '', array('class'=>'control-label')); ?>
							<div class="form-row">
								<div class="col-10">
									<input name="time_min" type="text" class="form-control digits required " maxlength='4' value="<?php echo set_value('time_min', $results['time_min']); ?>" placeholder="Minutes" /> 
								</div>
								<div class="col">
									<span class="align-middle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter test duration in minutes. Enter 0 (zero) for no limit"><i class="fas fa-question-circle"></i></span>
								</div>
							</div>
						</div>
					</div>
					
                    <div class="form-group ">
                        <?php echo form_label('Test Type<span class="required">*</span>', '', array('class'=>'control-label')); ?>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="test_regular" name="test_type" value="<?php echo TEST_TYPE_REGULAR; ?>" <?php echo set_radio('test_type', TEST_TYPE_REGULAR, true); ?>> 
                            <label class="form-check-label" for="test_regular">
                                Available to enroled students only
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="test_practice" name="test_type" value="<?php echo TEST_TYPE_PRACTICE; ?>" <?php echo set_radio('test_type', TEST_TYPE_PRACTICE); ?>> 
                            <label class="form-check-label" for="test_practice">
					        	Available to all students
                            </label>
                        </div>
                        
                        <div class="form-check d-none">
                            <input type="radio" class="form-check-input" id="test_public" name="test_type" value="<?php echo TEST_TYPE_PUBLIC; ?>" <?php echo set_radio('test_type', TEST_TYPE_PUBLIC); ?>> 
                            <label class="form-check-label" for="test_public">
                                Public<br>
					        	<small>Available to your students as well as other users of this system</small>
                            </label>
                        </div>
                        
                    </div>
					

					<div class="form-group">
					</div>
					
					<hr>
					
					<p class="btn-toolbar">
						<input type="submit" name="sub" value="<?php echo ('Save'); ?>" class="btn btn-primary " accesskey="s" />
					</p>
				</form>
			</div>
		</div>
	</div>
</div>