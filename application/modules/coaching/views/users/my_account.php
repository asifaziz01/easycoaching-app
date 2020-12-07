<div class="row">
	<div class="col-md-9">
		<div class="card">
			<?php echo form_open ('coaching/user_actions/my_account/'.$coaching_id.'/'.$member_id, array ('class'=>'form-horizontal', 'id'=>'validate-1')); ?>
				
				<input type="hidden" name="user_role" value="<?php echo $result['role_id']; ?>" >
			
				<div class="card-body ">
				
					<div class="form-group row">
						<div class="col-md-6">
							<label class="form-label"><?php echo 'Role'; ?>	</label>
							<p class="form-control-static "><?php echo $roles['description']; ?></p>
						</div>
					</div>
					
					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('User Id <span class="text-danger">*</span>', '', array('class'=>'', 'for' =>"adm_no"));?>			
							<?php 
							$option = array('name'=>'adm_no','class'=>'form-control', 'readonly'=>'true','id'=>'adm_no','value'=>set_value('adm_no', $result['adm_no']));
							echo form_input($option);
							?>	
						</div>
						
						<div class="col-md-6">							
							<?php echo form_label('Serial No', '', array('class'=>'', 'for' =>"sr_no")); ?>
							<?php echo form_input(array('name'=>'sr_no', 'class'=>'form-control', 'id'=>'sr_no', 'value'=>set_value('sr_no', $result['sr_no']), 'readonly'=>true));?>
						</div>
					</div>
					
					<div class="form-group row">
					    
						<div class="col-md-4">
							<?php echo form_label('Name <span class="text-danger">*</span>', '', array('class'=>'', 'for' =>"name")); ?>
							<input name='first_name' class="form-control required " placeholder="First name" type="text" value="<?php echo set_value('first_name', $result['first_name']);?>">
						</div>
						<div class="col-md-4">
							<?php echo form_label('&nbsp;', '', array('class'=>'', 'for' =>"name")); ?>
							<input name='second_name' class="form-control" placeholder="Middle name" type="text" value="<?php echo set_value('second_name', $result['second_name']);?>">
						</div>
						<div class="col-md-4">
							<?php echo form_label('&nbsp;', '', array('class'=>'', 'for' =>"name")); ?>
					    <input name='last_name' class="form-control required " placeholder="Last name" type="text" value="<?php echo set_value('last_name', $result['last_name']);?>">
						</div>
					
					</div>					
					
					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Contact No<span class="text-danger">*</span>', '', array('class'=>'')); ?>															  
							<?php echo form_input(array('name'=>'primary_contact', 'class'=>'form-control digits ', 'value'=>set_value('primary_contact', $result['primary_contact']) ));?>
						</div>

						<div class="col-md-6">
							<?php echo form_label('E-mail', '', array('class'=>'', 'for' =>"email")); ?>
							<?php
							  echo form_input(array('name'=>'email', 'class'=>'form-control', 'value'=>set_value('email', $result['email']), 'id'=>'email')); 
							?>
						</div>
						
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Date Of Birth', '', array('class'=>'')); ?>
							<?php  
							if ($result['dob'] != '' ) {
								$dob = date('Y-m-d', strtotime($result["dob"]));
							} else {
								$dob = date('Y-m-d');
							}
							echo form_input(array('type'=>'date', 'name'=>'dob', 'class'=>'form-control input-width-small', 'value'=>set_value('dob', $dob)));
							?>
						</div>

						<div class="col-md-6">
							<?php echo form_label('Gender', '', array('class'=>'')); ?>
							<p>
							    
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
							<label class="form-check-label ml-3 mr-3"><?php echo form_radio(array('name'=>'gender', 'value'=>'m', 'checked'=>$status_male, 'class'=>'radio-primary form-check-input')); ?><?php echo ('Male'); ?></label>
							<label class="form-check-label mr-3"><?php echo form_radio(array('name'=>'gender', 'value'=>'f', 'checked'=>$status_female, 'class'=>'radio-primary form-check-input')); ?><?php echo ('Female'); ?></label>
							<label class="form-check-label mr-3"><?php echo form_radio(array('name'=>'gender', 'value'=>'n', 'checked'=>$status_none, 'class'=>'radio-primary form-check-input')); ?><?php echo ('Not Specified'); ?></label>
							</p>

						</div>
					</div>
					
							
					
				</div>
				
				<div class="card-footer">
					<div class="btn-toolbar">
						<?php
						echo form_submit ( array ('name'=>'submit', 'value'=>'Save ', 'class'=>'btn btn-success pull-right')); 
						?>
					</div>	
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	
	<div class="col-md-3">
		
		<div class="card card-default">
			<div class="card-body ">
		        <div class="profile text-center">
					<img src="<?php echo base_url($profile_image); ?>" alt="Profile Image" class="img-responsive img-thumbnail rounded-circle " width="200"> 
					<h4><?php echo $result['first_name'].' '.$result['last_name']; ?></h4>
					<small><a href="#" class="" data-toggle="modal" data-target="#add_image">Edit</a></small>
				</div>
			</div>
		</div>

	</div>
</div>



<!-- Add Image -->
<div class="modal" tabindex="-1" role="dialog" id="add_image">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	  <?php echo form_open_multipart ('coaching/user_actions/upload_profile_picture/'.$member_id.'/'.$coaching_id, array ('class'=>'form-horizontal row-border', 'id'=>'upload_image')); ?>
            <div class="modal-header">
                <h5 class="modal-title">Profile Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
				<div class="form-group row"> 
					<div class="col-md-12">
						<div id="profile_messages"></div>
						<div id="image_preview" class="text-center" >
							<img src="<?php echo base_url ($profile_image); ?>" alt="Profile Image" class="img-responsive img-thumbnail rounded-circle align-center" height="80" >
						</div>
						<br>
						<div class="align-center "><a class="" id="remove_image" href="#" onclick="show_confirm ('Remove this image?', '<?php echo site_url('coaching/user_actions/remove_profile_image/'.$member_id); ?>')">Remove Image</a></div>
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-md-12">
						<input type="file" name="userfile" class="required" accept="image/*" data-style="fileinput" data-inputsize="medium">
						<p class="help-block">Images only (image/*)</p>
					</div>
				</div>

			</div>
			
			<div class="modal-footer">
				<div class="btn-toolbar">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" name="submit" value="<?php echo ('Upload'); ?>" class="btn btn-primary pull-right" />
				</div>
			</div>
		<?php echo form_close (); ?>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->