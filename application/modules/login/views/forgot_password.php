<div class="row justify-content-center">
	<div class="col-md-4">
		<div class="card card-default paper-shadow" data-z="0.5">
			<div class="card-header bg-white text-center pb-1">
				<?php if (is_file ($logo)) { ?>
					<img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
				<?php } else { ?>
				    <h4 class="text-center"><?php echo $page_title; ?></h4>
				<?php } ?>
			</div>
			<div class="card-body px-lg-5 py-lg-5">
				<div class="text-danger">We'll send you an email on your registered email-id with a link to create your new password.</div> <br>
				<!-- Login Formular -->  
				<?php echo form_open ('login/login_actions/reset_link', array ('class'=>'form-vertical', 'id'=>'validate-1') ); ?>
					<?php if($slug===''): ?>
					<div class="form-group">
						<div class="">
							<label for="userid">User-id/Login<span class="required">*</span></label>
							<div class="input-group input-group-alternative">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-user"></i></span>
								</div>
								<input name="userid" class="form-control required" placeholder="User-id/Login" autofocus="autofocus" id="userid">
							</div>
						</div>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<div class="">
							<label for="email">Email<span class="required">*</span></label>
							<div class="input-group input-group-alternative">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-at"></i></span>
								</div>
								<input name="email" class="form-control required" placeholder="youremail@example.com" autofocus="autofocus" id="email">
							</div>
						</div>
					</div>
					<div class="text-center">
						<?php if($slug!==''): ?>
						<input type="hidden" name="coaching_id" value="<?php echo $coaching_id; ?>">
						<?php endif; ?>
						<button type="submit" class="btn btn-primary" >Send Email</button>
						<a href="<?php echo site_url('login/login/index/?sub='.$slug); ?>" class="pull-right" ><strong>Log In!</strong></a>
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>