
	<div class="col-md-4 col-md-offset-4">
		<div class="card card-default paper-shadow" data-z="0.5">
			<div class="card-header">
				<h4 class="card-title"><i class="fa fa-refresh"></i> Forgot User-id</h4>
			</div>
			<div class="card-body">
				<div class="text-danger">We'll send you an mail on your registered email-id with a link of your  user-id.</div> <br>
				<!-- Login Formular -->  
				<?php echo form_open ('login/functions/reset_link_user_id', array ('class'=>'form-vertical', 'id'=>'validate-1') ); ?>
					<div class="form-group">
						<div class="">
							<label for="email">Email<span class="required">*</span></label>
							<input name="email" class="form-control required" placeholder="youremail@example.com" autofocus="autofocus" id="email">
						</div>
					</div>
					<button type="submit" class="btn btn-primary" >Send Email</button>

					<a href="<?php echo site_url('login/page/login'); ?>" class="pull-right" ><strong>Log In!</strong></a><br>
				</form>
			</div>
		</div>
	</div>


	

	
