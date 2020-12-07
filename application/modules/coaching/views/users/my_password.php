<div class="row justify-content-center">
	<div class="col-md-9"> 
		<div class="card card-default mb-4">						
			<div class="card-body">
				<?php echo form_open( 'coaching/user_actions/change_password/'.$coaching_id.'/'.$member_id, array('class'=>'form-horizontal ', 'id'=>'validate-1')); ?>
					<div class="form-group">
						<label class="col-md-3" for="password">Password<span class="text-danger">*</span></label>
						<div class="col-md-9">
							<input type="password" name="password" id='password' class="form-control" placeholder="Password">
							<div id='password-strength'></div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-3" for="conf_password">Confirm Password<span class="text-danger">*</span></label>
						<div class="col-md-9">
							<input type="password" name="repeat_password" class="form-control" id="conf_password"  placeholder="Re-enter password" >

						</div>
					</div>

					
					<div class="form-group">
					
						<div class="col-md-3"></div>
						<div id="pswd_info" class="col-md-9">
							<label class="">Password must meet the following requirements</label>
							<div><i id="letter"></i>       <span>At least one capital and small letter</span></div>
							<div><i id="number"></i>       <span>At least one number</span></div>
							<div><i id="spcl_char"></i>    <span>At least one special character</span></div>
							<div><i id="length"></i>       <span>Be at least 8 characters</span></div>
							<div><i id="re_pass"></i>      <span>"Confirm Password" should match "Password".</span></div>
						</div>
					</div>
				</div>
				
				<div class="card-footer">
					<div class="btn-toolbar">
					    <input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-success " />
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
