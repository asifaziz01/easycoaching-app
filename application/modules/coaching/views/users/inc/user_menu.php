<div class="card card-default">
	<div class="card-body ">
        <div class="profile text-center">
			<img src="<?php echo base_url($profile_image); ?>" alt="Profile Image" class="img-responsive img-thumbnail rounded-circle " width="200"> 
			<h4><?php echo $result['first_name'].' '.$result['last_name']; ?></h4>
			<a href="#" class="" data-toggle="modal" data-target="#add_image">Edit</a>
		</div>
	</div>
	<ul class="list-group list-group-menu">
		<li class="list-group-item ">
			<a class="link-text-color" href="<?php echo site_url ('coaching/users/create/'.$coaching_id.'/'.$role_id.'/'.$member_id); ?>">Profile</a>
		</li>
		
		<li class="list-group-item">
			<?php 
				$url = 'coaching/users/change_password/'.$coaching_id.'/'.$member_id;
			?>
			<a class="link-text-color" href="<?php echo site_url ($url); ?>">Change Password</a>
		</li>
		
		<li class="list-group-item d-none">
			<?php 
				$url = 'coaching/users/reports/'.$coaching_id.'/'.$member_id;
			?>
			<a class="link-text-color" href="<?php echo site_url ($url); ?>">Reports</a>
		</li>
		<?php if ($member_id <> $this->session->userdata ('member_id')) { ?>
			<li class="list-group-item" >
				<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this users?' ; ?>','<?php echo site_url('coaching/user_actions/delete_account/'.$coaching_id.'/'.$role_id.'/'.$member_id); ?>' )" class="text-danger" >Delete Account</a>
			</li>
		<?php } ?>
    </ul>
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
							<img src="<?php echo base_url ($profile_image); ?>" alt="Profile Image" class="img-responsive img-thumbnail rounded-circle align-center" >
						</div>
						<br>
						<div class="align-center ">
							<a class="" id="remove_image" href="#" onclick="show_confirm ('Remove this image?', '<?php echo site_url('coaching/user_actions/remove_profile_image/'.$member_id); ?>')">Remove Image</a>
						</div>
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