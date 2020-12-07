<div class="row">
<?php if ($member_id > 0) { ?>
	<div class="col-md-9">
<?php } else { ?>
	<div class="col-md-12">
<?php } ?>
		<div class="card mb-4">
			<div class="card-body">
				<?php echo form_open ('coaching/user_actions/create_account/'.$coaching_id.'/'.$role_id.'/'.$member_id, array ('class'=>'form-horizontal', 'id'=>'validate-1')); ?>
					
				    <?php if ($member_id > 0) { ?>
						<div class="form-group row">
							<div class="col-md-4">
								<?php echo form_label('User Id <span class="text-danger">*</span>', '', array('class'=>'', 'for' =>"adm_no")); ?>
								<?php 
								$option = array('name'=>'adm_no','class'=>'form-control','readonly'=>'true','id'=>'adm_no','value'=>set_value('adm_no', $result['adm_no']));
								echo form_input($option);
								?>
							</div>
						</div>
					<?php } ?>

					<div class="form-group row">
						<div class="col-md-4">
							<label class="form-label"><?php echo 'User Role'; ?></label>
							<select data-toggle="select2" class="form-control" name="user_role">
								<?php 
								if ($member_id > 0) {
									$select_role_id = $result['role_id'];
								} else {
									if ($role_id > 0) {
										$select_role_id = $role_id;		
										
									} else if ($this->session->userdata ('role_id') == USER_ROLE_SUPER_ADMIN) {
										$select_role_id = USER_ROLE_ADMIN;
									} else {
										$select_role_id = USER_ROLE_STUDENT;
									}
								}
								if ( ! empty ($roles)) {
									foreach ($roles as $row) {
										?>
										<option value=<?php echo $row['role_id'];?> <?php if ($row['role_id'] == $select_role_id) echo 'selected="selected"'; ?> >
											<?php echo $row['description']; ?> 
										</option>
										<?php
									}
								}
								?>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label"><?php echo 'Status'; ?></label>
							<select name="status" class="form-control" id="search-status" >
								<option value="<?php echo USER_STATUS_ENABLED; ?>" <?php if ($row['status']==USER_STATUS_ENABLED) echo 'selected="selected"'; ?> >Enabled</option>
								<option value="<?php echo USER_STATUS_DISABLED; ?>" <?php if ($row['status']==USER_STATUS_DISABLED) echo 'selected="selected"'; ?> >Disabled</option>
								<option value="<?php echo USER_STATUS_UNCONFIRMED; ?>" <?php if ($row['status']==USER_STATUS_UNCONFIRMED) echo 'selected="selected"'; ?> >Pending</option>
							</select>
						</div>

						<div class="col-md-4">
							<?php echo form_label('Serial No', '', array('class'=>'', 'for' =>"sr_no")); ?>
							<div class="">
								<?php echo form_input(array('name'=>'sr_no', 'class'=>'form-control', 'id'=>'sr_no', 'value'=>set_value('sr_no', $result['sr_no'])));?>
							</div>
						</div>

					</div>

					<div class="form-group ">
						<label for="first_name" class="">Name <span class="text-danger">*</span></label>
						<div class="row">
							<div class="col-md-4 mb-1">
								<input name='first_name' class="form-control required " required="" id="first_name" placeholder="First name" type="text" value="<?php echo set_value('first_name', $result['first_name']);?>">
							</div>
							<div class="col-md-4 mb-1">
								<input name='second_name' class="form-control " id="second_name" placeholder="Middle name" type="text" value="<?php echo set_value('second_name', $result['second_name']);?>">
							</div>
							<div class="col-md-4 mb-1">
								<input name='last_name' class="form-control required " id="last_name" placeholder="Last name" type="text" value="<?php echo set_value('last_name', $result['last_name']);?>">
							</div>
						</div>
					</div>
						
					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Contact No<span class="text-danger">*</span>', '', array('class'=>'')); ?>
							<?php echo form_input(array('name'=>'primary_contact', 'class'=>'form-control digits ', 'value'=>set_value('primary_contact', $result['primary_contact']) ));?>
						</div>

						<div class="col-md-6">
							<?php echo form_label('E-mail <span class="text-danger"></span>', '', array('class'=>'', 'for' =>"email")); ?>
							<?php echo form_input(array('name'=>'email', 'class'=>'form-control email  ', 'value'=>set_value('email', $result['email']), 'id'=>'email')); ?>
						</div>
						
					</div>

					
					<div class="form-group row ">
						<div class="col-md-6">
							<?php echo form_label('Date Of Birth', '', array('class'=>'')); ?>
							<?php 
							if ($result["dob"]) {
								$dob = date('Y-m-d', strtotime($result["dob"]));
							} else {
								$dob = '';
							}
							echo form_input(array('type'=>'date', 'name'=>'dob', 'class'=>'form-control input-width-small', 'value'=>set_value('dob', $dob)));
							?>
						</div>
						
						<div class="col-md-6">
							<?php echo form_label('Gender', '', array('class'=>'form-label')); ?>
							<?php
								$status_none = false;
								$status_male = false;
								$status_female = false;
								if ($result['gender'] == 'm')
									$status_male = true;
								else if ($result['gender'] == 'f')
									$status_female = true;
								else
									$status_none = true;
							?>
							<div class="d-block">
								<div class="btn-group btn-group-toggle gender-toggle" data-toggle="buttons">
									<label class="btn position-relative btn-default"><?php echo form_radio(array('name'=>'gender', 'value'=>'m', 'checked'=>$status_male, 'class'=>'')); ?><?php echo ('Male'); ?></label>
									<label class="btn position-relative btn-default"><?php echo form_radio(array('name'=>'gender', 'value'=>'f', 'checked'=>$status_female, 'class'=>'radio-primary form-check-input')); ?><?php echo ('Female'); ?></label>
									<label class="btn position-relative btn-default"><?php echo form_radio(array('name'=>'gender', 'value'=>'n', 'checked'=>$status_none, 'class'=>'radio-primary form-check-input')); ?><?php echo ('Not Specified'); ?></label>
								</div>
							</div>
						</div>
						
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<label class="form-label">User Batch</label>
							<?php 
							if (! empty($batches)) {
							  foreach ($batches as $batch) { ?>
							  	<div class="ml-2">
							  		
								  	<label class="form-label">
								  		<input type="checkbox" value="<?php echo $batch['batch_id']; ?>" name="batches[]" <?php if ($batch['enroled'] == 1) echo 'checked'; ?> >
								  		<?php echo $batch['batch_name']; ?>
								  	</label>
							  	</div>
							  <?php 
							  }      
							}  
							?>
						</div>

						<div class="col-md-6">
						</div>

					</div>
				</div>
					
				<div class="card-footer">
					<?php
					echo form_submit ( array ('name'=>'submit', 'value'=>'Save ', 'class'=>'btn btn-primary ')); 
					?>
				</div>
			</form>
		</div>
	</div>
	<?php if ($member_id > 0) { ?>
		<div class="col-md-3">
			<?php $this->load->view('inc/user_menu', $data); ?>
		</div>
	<?php } ?>
</div>