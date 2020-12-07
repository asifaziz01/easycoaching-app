<div class="card paper-shadow" data-z="0.5">
	<div class="card-body ">
	    <?php echo form_open ('admin/coaching_actions/create_account/'.$coaching_id, array ('class'=>'', 'id'=>'validate-1')); ?>
			<h6 class="">Basic Details</h6>
			<p class="text-subhead ">Add your coaching name, address and other detail</p>

			<div class="form-row">
                <?php if ($coaching_id > 0) { ?>
				    <div class="col-6">
    					<label class="control-label">Registration No<span class="required">*</span></label>
					    <input type="text" name="reg_no" class="form-control required" value = "<?php echo set_value('reg_no', $coaching['reg_no']) ; ?>" readonly>
				    </div>
    			<?php } ?>
	    
    			<div class="col-6 ">
    				<label class="control-label">Joining Date<span class="required">*</span></label>
    				<input type="date" name="date" class="form-control required" value="<?php echo date('Y-m-d'); ?>">
    			</div>
    			
			</div>
			
			<div class="form-group">
				<label class="control-label">Coaching Name<span class="required">*</span></label>
				<input type="text" name="coaching_name" class="form-control required" value = "<?php echo set_value('coaching_name', $coaching['coaching_name']) ; ?>">
			</div>

			<div class="form-row ">	
			    <div class="col-6">
			        <label class="control-label">Address</label>
    				<div class="">
    					<input type="text" name="address" class="form-control required" value = "<?php echo set_value('address', $coaching['address']) ; ?>">
    				</div>       
			    </div>
				
				<div class="col-2 ">
    				<label class=" control-label">State</label>
    				<div class="">
    					<input type="text" name="state" class="form-control required" value = "<?php echo set_value('state', $coaching['state']) ; ?>">
    				</div>
    			</div>
    			
    			<div class="col-2 ">				
    				<label class=" control-label">City<span class="required">*</span></label>
    				<div class="">
    					<input type="text" name="city" class="form-control required" value = "<?php echo set_value('city', $coaching['city']) ; ?>">
    				</div>
    			</div>
    			<div class="col-2">
    				<label class=" control-label">PIN Code</label>
    				<div class="">
    					<input type="text" name="pin" class="form-control required" value = "<?php echo set_value('pin', $coaching['pin']) ; ?>">
    				</div>
    			</div>
			</div>

			<div class="form-group ">
				<label class=" control-label">Website</label>
				<div class="">
					<input type="text" name="website" class="form-control " value = "<?php echo set_value('website', $coaching['website']) ; ?>">
				</div>
			</div>
		    
		    <?php if ($coaching_id == 0) { ?>
		        <hr>
		    
    			<h6 class="margin-none">Coaching Admin Details</h6>
    			<p class="text-subhead ">Add admin name, contact and email</p>
    		
    			<div class="form-group row">
    
    				<?php $fname = $lname = ''; ?>
    				<div class="col-md-6">
    					<label>First Name</label>
    					<input type="text" name="first_name" class="form-control required" value = "<?php echo set_value('first_name', $fname) ; ?>">
    				</div>
    				<div class="col-md-6">
    					<label>Last Name</label>
    					<input type="text" name="last_name" class="form-control required" value = "<?php echo set_value('last_name', $lname) ; ?>">
    				</div>
    			</div>
    			
    			<div class="form-group row">
    				<div class="col-md-6">
    					<?php echo form_label('Contact No<span class="required">*</span>', '', array('class'=>'control-label')); ?>
    					<?php echo form_input(array('name'=>'primary_contact', 'class'=>'form-control digits required', 'value'=>set_value('primary_contact', $coaching['contact']) ));?>
    				</div>
    				<div class="col-md-6">
    					<?php echo form_label('E Mail<span class="required">*</span>', '', array('class'=>'control-label')); ?>
    					<?php echo form_input(array('name'=>'email', 'class'=>'form-control email required', 'value'=>set_value('email', $coaching['email']), 'id'=>'email', 'onblur'=>'')); ?>
    				</div>
    			</div>
    			

            <?php } ?>

			<hr>
			
			<div class="btn-toolbar">
				<?php 
				echo form_submit ( array ('name'=>'submit', 'value'=>'Save ', 'class'=>'btn btn-primary ')); 
				?>
			</div>
    	</form>
	</div>
</div>