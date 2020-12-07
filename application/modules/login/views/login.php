<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center pb-1">
	  	<?php if ( is_file ($logo)) { ?>
			<img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
		<?php } else { ?>
		    <h4 class="text-center"><?php echo $page_title; ?></h4>
		<?php } ?>
	    <h6 class="text-center">Sign in with your credentials</h6>
	  </div>
	  <div class="card-body py-5">
		<?php echo form_open ('login/login_actions/validate_login', array('id'=>'login-form')); ?>
		  <div class="form-group mb-3">
			<div class="input-group input-group-alternative">
			  <div class="input-group-prepend">
				<span class="input-group-text"><i class="fa fa-user"></i></span>
			  </div>
			  <input class="form-control" placeholder="Mobile No/Email-id/User-ID" type="text" name="username">
			</div>
		  </div>

		  <div class="form-group">
			<div class="input-group input-group-alternative">
			  <div class="input-group-prepend">
				<span class="input-group-text"><i class="fa fa-lock"></i></span>
			  </div>
			  <input class="form-control" placeholder="Password" type="password" name="password">
			</div>
	  		<a href="<?php echo site_url ('login/user/reset_password'); ?>" class="text">Reset password</a>
		  </div> 

		  <?php if ($access_code != '' && $found == true) { ?>
			  <div class="form-group">
				<div class="input-group input-group-alternative">
				  <div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-key"></i></span>
				  </div>
				  <input class="form-control" placeholder="Access Code" type="text" name="access_code" value="<?php echo $access_code; ?>" readonly>
				</div>
			  </div>
		  <?php } else { ?>
			  <div class="form-group">
				<div class="input-group input-group-alternative">
				  <div class="input-group-prepend">
					<span class="input-group-text"><i class="fa fa-key"></i></span>
				  </div>
				  <input class="form-control" placeholder="Access Code" type="text" name="access_code">
				</div>
		  		<a href="<?php echo site_url ('login/user/get_access_code'); ?>" class="text">Get Access Code</a>
			  </div>
		  <?php } ?>


		  <div class="media">
		  	<div class="media-body">
		  	</div>
		  	<div class="media-right d-none">
		  		<a href="<?php echo site_url ('login/user/otp_request'); ?>" class="text">Sign in with OTP</a>
		  	</div>
		  </div>
		  <div class="text-center">
			<button type="submit" name="submit" class="btn btn-success my-4">Sign in</button>
		  </div>
		</form>
	  </div>

	  <div class="card-footer">
	  	<h5 class="text-center">Don't have an account?</h5>
		<a href="<?php echo site_url ('login/user/register/?sub='.$access_code.'&role='.USER_ROLE_STUDENT); ?>" class="btn btn-block btn-info">Create Account</a>
	  </div>

  </div>
</div>