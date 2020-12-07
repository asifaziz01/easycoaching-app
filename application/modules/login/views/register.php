<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center pb-1">
	  	<?php if (is_file ($logo)) { ?>
			<img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
		<?php } else { ?>
		    <h4 class="text-center"><?php echo $page_title; ?></h4>
		<?php } ?>
	    <h5 class="text-center">Create a new <?php if ($role_id == USER_ROLE_TEACHER) echo 'teacher'; else echo 'student'; ?> account</h5>
	  </div>
		<?php echo form_open ('login/login_actions/register', array('class'=>'form-horizontal ', 'id'=>'validate-1')); ?> 
	  	  <div class="card-body ">
	  	  	<input type="hidden" name="user_role" value="<?php echo $role_id; ?>">
	  	  	<input type="hidden" name="sr_no" value="">
	  	  	<input type="hidden" name="second_name" value="">
	  	  	<input type="hidden" name="user_batch" value="0">
							
			<div class="form-group">
				<label class="control-label ">Your Name<span class="text-danger">*</span></label>
				<input type="text" name="first_name" class="form-control required"  value="<?php echo set_value ('first_name'); ?>">
			</div>			
			
			<div class="form-group">
				<label class="control-label ">Mobile <span class="text-danger">*</span></label>
				<input type="text" name="primary_contact" class="form-control digits required" value="<?php echo set_value ('primary_contact'); ?>">
			</div>

			<div class="form-group">
				<label class="control-label ">Email (Optional)</label>
				<input type="text" name="email" class="form-control email required" value="<?php echo set_value ('email'); ?>">	
			</div>			

			<div class="form-group">
				<label class="control-label" for="password">Password<span class="text-danger">*</span></label>
				<div class="input-group mb-3">
				  	<input type="password" name="password" id="reg-password" class="form-control required" placeholder="Password"  aria-label="Password" aria-describedby="show-password">
				  	<div class="input-group-append">
				  	  <span class="input-group-text" id="show-password">
				  	  	<a style="cursor:pointer" id="show-password-link">Show Password</a>
				  	  </span>
				  	</div>
				</div>
				<p class="text-muted">Choosing a strong password is recommended</p>
			</div>

		  	<?php if (! $access_code) { ?>
			  <div class="form-group">
				  <label class="control-label ">Access Code<span class="text-danger">*</span></label>
				  <input class="form-control" placeholder="Access Code" type="text" name="access_code">
				  <p class="text-muted">If you don't have access code, contact your coaching-center/institution</p>
			  </div>
		  	<?php } else { ?>
				  <input class="form-control" placeholder="Access Code" type="hidden" name="access_code" value="<?php echo $access_code; ?>">
		  	<?php } ?>

		  </div>
		  <div class="card-footer">
			
			<div class=" text-center ">
				<p><input type="submit" name="save" class="btn btn-success btn-block" value="Create Account"></p>
				Already have an account? <a href="<?php echo site_url ('login/user/index?sub='.$access_code); ?>" class="mt-4">Sign In</a>
			</div>
	  	  </div>
	  	</form>
	</div>
  </div>
</div>