<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card card-default">
			<div class="card-body">
				<?php echo form_open('coaching/virtual_class_actions/create_classroom/'.$coaching_id.'/'.$class_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>

					<div class="form-group ">
						<?php echo form_label('Classroom Name<span class="required">*</span>', '', array('class'=>'control-label')); ?>
						<input type="text" class="form-control required" name="class_name" value="<?php echo set_value('class_name', $class['class_name']); ?>" />
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
					
					<div class="form-group ">
						<?php echo form_label('Description (Optional)','', array('class'=>'control-label')); ?>
						<textarea name="description" class="form-control" rows="3" max_length="200"><?php echo set_value('description', $class['description']); ?></textarea>
						<div class="text-muted">Short desciption of the class. Maximum length can be 200 characters, including SPACES</div>
					</div>

					<div class="form-group ">
						<?php echo form_label('Welcome Message (Optional)', '', array('class'=>'control-label')); ?>
						<textarea name="welcome_message" class="form-control" rows="3" max_length="100"><?php echo set_value('welcome_message', $class['welcome_message']); ?></textarea>
						<div class="text-muted">This will be displayed to users in class. Maximum length can be 100 characters, including SPACES</div>
					</div>					
					
					<h5 class="card-title border-bottom mt-4 ">Restrictions</h5>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="wait_for_moderator" class="custom-control-input" id="wait_for_moderator" value="1" <?php if ($class['wait_for_moderator'] == 'true' ) echo 'checked';?>  checked disabled>
						  <label class="custom-control-label" for="wait_for_moderator">Student must wait for moderator to join <br>
						  <span class="text-muted">(Only a moderator can start a class. This setting cannot be changed)</span></label>
						  <!-- // This is an over-ride for above setting. Remove if needed -->
						  <input type="hidden" name="wait_for_moderator" value="1">
						</div>
					</div>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="mute_mic" class="custom-control-input" id="mute_mic" value="1" <?php if ($class['mute_all_mics'] == 'true' ) echo 'checked';?>  >
						  <label class="custom-control-label" for="mute_mic">Mute all mics on start <br>
						  <span class="text-muted">(User mic will be muted on joining. User(s) can un-mute themselves anytime during the class)</span></label>
						</div>
					</div>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="lock_mic" class="custom-control-input" id="lock_mic" value="1" <?php if ($class['join_listen_only'] == 'true' ) echo 'checked';?>  >
						  <label class="custom-control-label" for="lock_mic">Users can join in 'Listen Only' mode <br>
						  <span class="text-muted">(Moderators can control when to allow users to speak. User(s) cannot un-mute themselves)</span></label>
						</div>
					</div>


					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="webcam_for_moderator" class="custom-control-input" id="webcam_for_moderator" value="1" <?php if ($class['webcam_for_moderator'] == 'true' ) echo 'checked';?> checked >
						  <label class="custom-control-label" for="webcam_for_moderator">Disable attendee webcam(s) <br>
						  <span class="text-muted">(Only moderators can use/share their webcam during class)</span></label>
						</div>
					</div>
					
					<!--
					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="lock_public_chat" class="custom-control-input" id="lock_public_chat" value="1" <?php if ($class['lock_public_chat'] == 'true' ) echo 'checked';?> >
						  <label class="custom-control-label" for="lock_public_chat">Disable public chat <br>
						  <span class="text-muted">(Disable public chat feature during class session)</span></label>
						</div>
					</div>

					
					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="lock_private_chat" class="custom-control-input" id="lock_private_chat" value="1" <?php if ($class['lock_private_chat'] == 'true' ) echo 'checked';?> >
						  <label class="custom-control-label" for="lock_private_chat">Disable private chat <br>
						  <span class="text-muted">(Disable private chat feature during class session)</span></label>
						</div>
					</div>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="lock_notes" class="custom-control-input" id="lock_notes" value="1" <?php if ($class['lock_notes'] == 'true' ) echo 'checked';?> >
						  <label class="custom-control-label" for="lock_notes">Disable note sharing <br>
						  <span class="text-muted">(Disable note sharing feature during class session)</span></label>
						</div>
					</div>
					-->
					
					<h5 class="card-title border-bottom mt-4 ">Class Schedule</h5>

					<div class="accordion" id="acc-schedule">

					  <div class="cards ml-4">
					    <div class="card-header" id="headingOne">
					      <h2 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					          Starts and Ends on specific dates
					        </button>
					      </h2>
					    </div>

					    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#acc-schedule">
					      <div class="card-body">
							<div class="form-group row ">
								<?php
									if ($class['start_date']) {
										$start_date = date ('Y-m-d', $class['start_date']);
									} else {
										$start_date = date ('Y-m-d');
									}
								?>
								<div class="col-md-6">
									<label class="control-label">
										<input type="checkbox" name="start_date_check" id="start_date_check" value="1" <?php if ($class['start_date'] > 0 ) echo 'checked'; ?>> Class start on
									</label>
									<input type="date" class="form-control required" name="start_date" value="<?php echo set_value('start_date', $start_date); ?>" id="start_date_text" <?php if ($class['start_date'] > 0 ) echo ''; else echo 'disabled'; ?> />
								</div>

								<div class="col-md-3">
									<?php echo form_label('Time (HH)', '', array('class'=>'control-label')); ?>
									<select name="start_time_hh " id="start_time_hh" class="form-control" <?php if ($class['start_date'] > 0 ) echo ''; else echo 'disabled="true"'; ?>>
										<option value="-1">HH</option>
										<?php for ($i=0; $i<=23; $i++) { ?>
											<option value="<?php echo $i; ?>" <?php if ($i == date ('H', $class['start_date'])) echo 'selected="selected"'; ?> ><?php echo str_pad($i, 1, '0', STR_PAD_LEFT); ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-3">
									<?php echo form_label('Time (MM)', '', array('class'=>'control-label')); ?>
									<select name="start_time_mm " id="start_time_mm" class="form-control" <?php if ($class['start_date'] > 0 ) echo ''; else echo 'disabled="true"'; ?>>
										<option value="-1">MM</option>
										<?php for ($i=0; $i<=59; $i++) { ?>
											<option value="<?php echo $i; ?>" <?php if ($i == date('i', $class['start_date'])) echo 'selected="selected"'; ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group row ">
								<?php
									if ($class['end_date']) {
										$end_date = date ('Y-m-d', $class['end_date']);
									} else {
										$end_date = date ('Y-m-d');
									}
								?>
								<div class="col-md-6">
									<label class="control-label">
										<input type="checkbox" name="end_date_check" id="end_date_check" value="1"> Class ends on
									</label>
									<input type="date" class="form-control required" name="end_date" value="<?php echo set_value('end_date', $end_date); ?>" id="end_date_text" <?php if ($class['end_date'] > 0 ) echo ''; else echo 'disabled'; ?> />
								</div>

								<div class="col-md-3">
									<?php echo form_label('Time (HH)', '', array('class'=>'control-label')); ?>
									<select name="end_time_hh " id="end_time_hh" class="form-control" <?php if ($class['end_date'] > 0 ) echo ''; else echo 'disabled="true"'; ?>>
										<option value="-1">HH</option>
										<?php for ($i=0; $i<=23; $i++) { ?>
											<option value="<?php echo $i; ?>" <?php if ($i == date('H', $class['end_date'])) echo 'selected="selected"'; ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-3">
									<?php echo form_label('Time (MM)', '', array('class'=>'control-label')); ?>
									<select name="end_time_mm " id="end_time_mm" class="form-control" <?php if ($class['end_date'] > 0 ) echo ''; else echo 'disabled="true"'; ?>>
										<option value="-1">MM</option>
										<?php for ($i=0; $i<=59; $i++) { ?>
											<option value="<?php echo $i; ?>" <?php if ($i == date('i', $class['end_date'])) echo 'selected="selected"'; ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>					

					      </div>
					    </div>
					  </div>

					</div>

					
					<h5 class="card-title border-bottom mt-4">Recording</h5>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="record_class" class="custom-control-input" id="record-class" value="1" <?php if ($class['record_class'] == 'true' ) echo 'checked';?>  >
						  <label class="custom-control-label" for="record-class">Enable recording </label>
						</div>
					</div>


					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="auto_record" class="custom-control-input" id="auto-record-class" value="1" <?php if ($class['auto_start_recording'] == 'true' ) echo 'checked';?> >
						  <label class="custom-control-label" for="auto-record-class">Auto start recording on class start </label>
						</div>
					</div>

					<div class="form-group ">
						<div class="custom-control custom-switch">
						  <input type="checkbox" name="recording_for_students" class="custom-control-input" id="recording_for_students" value="1" <?php if ($class['recording_for_students'] == 'true' ) echo 'checked';?> >
						  <label class="custom-control-label" for="recording_for_students">Students can view recordings </label>
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