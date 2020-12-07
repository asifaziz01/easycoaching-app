<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center pb-1">
	  	<?php if ( is_file ($logo)) { ?>
			<img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
		<?php } else { ?>
		    <h4 class="text-center"><?php echo $page_title; ?></h4>
		<?php } ?>
	    <h6 class="text-center">Get Access Code</h6>
	  </div>
	  <?php echo form_open ('login/login_actions/get_access_code', array ('class'=>'form-vertical', 'id'=>'validate-1') ); ?>
		  <div class="card-body px-lg-5 py-lg-5">
			<div class="alert alert-info">
				You will recieve Access Code on your registered mobile number and email (if given).
			</div>

			<div class="form-group">
				<div class="">
					<label for="mobile">Your registered mobile number<span class="required">*</span></label>
					<div class="input-group input-group-alternative">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-mobile"></i></span>
						</div>
						<input name="mobile" class="form-control required" placeholder="Mobile No" autofocus="autofocus" id="mobile">
					</div>
				</div>
			</div>

		  </div>

		  <div class="card-footer">
			<div class="text-center">
				<button type="submit" class="btn btn-success" >Send Access Code</button>
				<p class="mt-2">
					Don't have access code? <a href="<?php echo site_url('login/user/get_access_code'); ?>" class="" >Get Access Code</a>
				</p>
				<p class="mt-2">
					<a href="<?php echo site_url('login/user/index'); ?>" class="" >Log In</a>
				</p>
			</div>
		  </div>
		</form>
	</div>
  </div>	
</div>