<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center pb-1">
		<h4 class="text-center">Admin</h4>
	    <h6 class="text-center">Sign in with your credentials</h6>
	  </div>
	  <div class="card-body px-lg-5 py-lg-5">
		<?php echo form_open ('login/login_actions/validate_login/1', array('id'=>'login-form')); ?>
		  <div class="form-group mb-3">
			<div class="input-group input-group-alternative">
			  <div class="input-group-prepend">
				<span class="input-group-text"><i class="fa fa-user"></i></span>
			  </div>
			  <input class="form-control" placeholder="User-id OR Email-id" type="text" name="username">
			</div>
		  </div>
		  <div class="form-group">
			<div class="input-group input-group-alternative">
			  <div class="input-group-prepend">
				<span class="input-group-text"><i class="fa fa-lock"></i></span>
			  </div>
			  <input class="form-control" placeholder="Password" type="password" name="password">
			</div>
		  </div>
		  <div class="media d-none">
		  	<div class="media-body">
		  		<a href="<?php echo site_url ('login/login/forgot_password/?sub='); ?>" class="text">Forgot password?</a>
		  	</div>
		  	<div class="media-right">
		  		<a href="<?php echo site_url ('login/login/otp_request/?sub='); ?>" class="text">Sign in with OTP</a>
		  	</div>
		  </div>
		  <div class="text-center">
			<button type="submit" name="submit" class="btn btn-success my-4">Sign in</button>
		  </div>
		</form>
	  </div>

  </div>
</div>