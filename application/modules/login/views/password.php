<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	
	<div class="card card-default paper-shadow ">
	  <div class="card-header bg-white text-center">
		<img src="<?php echo $logo; ?>" height="40" title="<?php echo $page_title; ?>" class="text-center">
	    <h5 class="text-center">Create New Password</h6>
	  </div>
	  <div class="card-body px-lg-5 py-lg-5">
		<?php echo form_open ('login/functions/update_password/'.$slug.'/'.$member_id, array('class'=>'form-horizontal','role'=>'form', 'id'=>'validate-1')); ?>
		
			<div class="form-group">
				<div class="input-group input-group-alternative">
					<div class="input-group-prepend">
						<label class="input-group-text"><i class="fa fa-lock"></i>&nbsp;<span>User-id</span></label>
					</div>
					<input class="form-control" readonly disabled value="<?php echo $result['adm_no']; ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="password">New Password</label>
				<div class="input-group input-group-alternative">
					<div class="input-group-prepend">
						<label class="input-group-text" for="password"><i class="fa fa-key"></i></label>
					</div>
					<input type="password" name="password" id='password' class="form-control required" placeholder="Password">
				</div>
				<div id="password-strength"></div>
			</div>

			<div class="form-group">
				<label for="conf_password">Confirm Password</label>
				<div class="input-group input-group-alternative">
					<div class="input-group-prepend">
						<label class="input-group-text" for="conf_password"><i class="fa fa-key"></i></label>
					</div>
					<input type="password" name="confirm_password" class="form-control required" id="conf_password"  placeholder="Re-enter password" >
				</div>
			</div>

			
			<div class="form-row">			
				<div id="pswd_info" class="col-md-12">
					<label class="">Password must meet the following requirements</label>
					<div><i id="letter"></i>       <span>At least one capital and small letter</span></div>
					<div><i id="number"></i>       <span>At least one number</span></div>
					<div><i id="spcl_char"></i>    <span>At least one special character</span></div>
					<div><i id="length"></i>       <span>Be at least 8 characters</span></div>
					<div><i id="re_pass"></i>      <span>"Confirm Password" should match "New Password".</span></div>
				</div>
			</div>
		</div>
				
		<div class="card-footer">
			<div class="btn-toolbar flex-column justify-content-center">
				<div class="text-center">
					<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-success d-inline-block" />
				</div>
				<div class="text-center">
					<a href="<?php echo site_url ('login/page/login/?sub='.$slug); ?>" class="btn btn-link">Already have an account? <strong>Sign In</strong></a>
				</div>
			</div>
		</div>
	  </form>
	</div>		
  </div>
</div>