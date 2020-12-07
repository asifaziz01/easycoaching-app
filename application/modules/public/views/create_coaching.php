<div class="row justify-content-center align-middle v-middle mt-4">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
    
    <div class="card card-default paper-shadow ">
      <div class="card-header bg-white text-center pb-1">
        <?php if ( is_file ($logo)) { ?>
            <img src="<?php echo $logo; ?>" height="50" title="<?php echo $page_title; ?>" class="text-center">
        <?php } else { ?>
            <h4 class="text-center"><?php echo $page_title; ?></h4>
        <?php } ?>
        <h6 class="text-center">Create a new coaching account</h6>
      </div>
      <div class="card-body ">
         
         <?php echo form_open ('public/page_actions/create_coaching', array('id'=>'validate-1')); ?>

            <div class="form-group ">
                <label class="control-label">Coaching Name<span class="required">*</span></label>
                <input type="text" name="coaching_name" class="form-control required" value = "<?php echo set_value('coaching_name', $coaching['coaching_name']) ; ?>">
                <p class="text-muted">Provide the name of your coaching or institute. Should be alpha-numeric.</p>
            </div>

			<div class="form-group d-none">
                <label class="control-label">Coaching Identifier<span class="required">*</span></label>
                <input type="text" name="coaching_url" class="form-control required" value = "<?php echo set_value('coaching_url', $coaching['coaching_url']) ; ?>" >
                <p class="text-muted">Should be unique, alpha-numeric. This will be used by your users to identify your coaching. <br> <span class="font-weight-bold">Example <strong>apexdelhi</strong>, <strong>sacademy</strong></span>
                </p>
			</div>

            <div class="form-group d-none">
				<label class=" control-label">City<span class="required">*</span></label>
				<input type="text" name="city" class="form-control required" value = "<?php echo set_value('city', $coaching['city']) ; ?>">
			</div>

			<div class="form-group d-none">
                <label class=" control-label">Website (if any)</label>
				<input type="text" name="website" class="form-control " value = "<?php echo set_value('website', $coaching['website']) ; ?>">
			</div>

            <div class="form-group ">
				<label>Admin Name<span class="required">*</span></label>
				<input type="text" name="first_name" class="form-control required" value = "<?php echo set_value('first_name', $coaching['fname']) ; ?>">
            </div>
    			
    		<div class="form-group ">
				<?php echo form_label('Contact No<span class="required">*</span>', '', array('class'=>'control-label')); ?>
				<?php echo form_input(array('name'=>'primary_contact', 'class'=>'form-control digits required', 'value'=>set_value('primary_contact', $coaching['contact']) ));?>
			</div>

            <div class="form-group ">
				<?php echo form_label('E Mail (Optional)', '', array('class'=>'control-label')); ?>
				<?php echo form_input(array('name'=>'email', 'class'=>'form-control email required', 'value'=>set_value('email', $coaching['email']), 'id'=>'email', 'onblur'=>'')); ?>
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

		</div>

		<div class="card-footer">
			<?php echo form_submit ( array ('name'=>'submit', 'value'=>'Save ', 'class'=>'btn btn-primary ')); ?>
            <p>By clicking <strong>Save</strong>, you agree to our <a href="<?php echo 'https://easycoachingapp.com/eula'; ?>" target="_blank">Terms and Conditions</a> and <a href="https://easycoachingapp.com/privacy-policy" target="_blank">Privacy Policy</a>
		</div>
	</form>
</div>