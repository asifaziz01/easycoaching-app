<div class="row">	

	<div class="col-md-9">				

		<div class="card">

			<div class="card-header">

				<h4><i class="fa fa-user"></i> Primary Details </h4>

			</div>

			<div class="card-body">

				<div class="">

					<?php echo form_open ('users/ajax_func/validate_account/'.$member_id, array ('class'=>'form-horizontal', 'id'=>'ajax_form1')); ?>			

						<div class="form-group row">

							<label class="col-md-3">Account Type</label>

							<div class="col-md-9">

								<p class="form-control">

								<?php 

								$role = $this->users_model->user_role_name ($result['role_id']);

								echo $role['description'];

								?>

								</p>

							</div>								

						</div>

						<div class="form-group row">

							<label class="col-md-3">Name</label>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['first_name'] ; ?>

									<?php echo $result['second_name'] ; ?>

									<?php echo $result['last_name'] ; ?>

								</p>

							</div>

						</div>	

						

						<div class="form-group row">

							<?php echo form_label('User ID <span class="required">*</span>', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['adm_no'] ; ?>

								</p>										

							</div>

						</div>

						

						<div class="form-group row">

							<?php echo form_label('SR NO', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['sr_no'] ; ?>

								</p>

							</div>

						</div>

						

						<div class="form-group row">

							<?php echo form_label('Primary Contact', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['primary_contact'] ; ?>

								</p>

							</div>

						</div>

						

						<div class="form-group row">

							<?php echo form_label('Email', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['email'] ; ?>

								</p>

							</div>

						</div>

						 

						<div class="form-group row">

							<?php echo form_label('Date Of Birth', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php echo $result['dob'] ; ?>

								</p>

							</div>

						</div>

						

						<div class="form-group row">

							<?php echo form_label('Gender', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php										

										if ($result['gender'] == 'm')

											echo 'Male';

										else if ($result['gender'] == 'f')

											echo 'Female';

										else

											echo 'Not Specified';

									?>

								</p>

							</div>

						</div>

						

						<div class="form-group row">

							<?php echo form_label('Special needs', '', array('class'=>'col-md-3')); ?>

							<div class="col-md-9">

								<p class="form-control">

									<?php

										if ($result['special_needs'] == 1)

											echo 'Yes';

										else 

											echo 'No';

									?>

								</p>										

							</div>

						</div>   

						

						<div class="form-actions">

							<?php

								echo anchor('users/page/index/'.$class_id.'/'.$role_id, 'Cancel', array('class'=>'btn btn-default pull-right'));

							?>

						</div>

					</form>							

				</div>

			</div>

		</div>

	</div>

	<div class="col-md-3">

		<?php 

			$this->load->view('ajax/right_side_manage_menu',$data);

		?>

	</div>

</div>

<script>	

	$( ".datepicker" ).datepicker({

		defaultDate: +0,

		showOtherMonths:true, 

		autoSize: true,

		changeMonth: true,

		changeYear: true,

		dateFormat: 'dd-mm-yy',

		yearRange: "-120:+0",

		maxDate: "+0D",

	});

	

	$(document).on('click', '.list-group-item', function() {

       $(".list-group-item").removeClass("list-group-item-info");

       $(this).addClass("list-group-item list-group-item-info");

   });

</script>

