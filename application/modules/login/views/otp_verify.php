<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center pb-1">
	  	<?php if (is_file ($logo)) { ?>
			<img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
		<?php } else { ?>
		    <h4 class="text-center"><?php echo $page_title; ?></h4>
		<?php } ?>
	    <h6 class="text-center">Sign in with OTP</h6>
	  </div>
	  <div class="card-body px-lg-5 py-lg-5">
		<?php echo form_open ('login/login_actions/do_otp_verification/'.$slug, array('id'=>'validate-1', 'class' => 'verify_otp')); ?>
		  <input type="hidden" name="coaching_id" value="<?php echo $coaching_id; ?>" />
		  <input type="hidden" name="member_id" value="<?php echo $member_id; ?>" />
		  
		  <div class="form-group">
		  	<label for="otp">Enter One Time Password</label>
            <input type="text" id="otp-imput" name="otp" />
            <span id="otp-callback"></span>
		  </div>
		  <div class="resend-otp-wait"></div>
		  <a href="<?php echo site_url ('login/login_actions/resend_otp/'.$member_id.'/'.$slug); ?>" class="text resend-otp">Resend</a>
		<?php echo form_close(); ?>
	  </div>

	  <div class="card-footer">
	  	<h5 class="text-center">Don't have an account with <?php echo $page_title;?></h5>
		<a href="<?php echo site_url ('login/login/register/?sub='.$slug.'&role='.USER_ROLE_TEACHER); ?>" class="btn btn-block btn-primary">Create Teacher Account</a>
		<a href="<?php echo site_url ('login/login/register/?sub='.$slug.'&role='.USER_ROLE_STUDENT); ?>" class="btn btn-block btn-info">Create Student Account</a>

        <div class="mt-4 text-center" id="installBanner" >
            <button class="btn btn-success " id="installBtn"><i class="fab fa-android"></i> Install App</button> 
        </div>
	  </div>
		  <div class="card-footer d-none">
			<p class="">
				<h5 class="text-center"></h5>
				<?php echo anchor('student/login/install_app/?sub='.$slug, '<i class="fab fa-android"></i> Install Student App', ['class'=>'btn btn-success btn-block']); ?>
			</p>
		  </div>
	  </div>
  </div>
</div>