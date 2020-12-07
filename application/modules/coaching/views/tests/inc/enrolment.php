<?php 
if ($type == NOT_ENROLED_IN_TEST) { 
	echo form_open('coaching/tests_actions/enrol_users/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id, array('class'=>'form-horizontal row-border', 'id'=>'enrol_users') ); 
} else if ($type == ARCHIVED_IN_TEST) {
	echo form_open('coaching/tests_actions/archive_users/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id, array('class'=>'form-horizontal row-border', 'id'=>'enrol_users') ); 	
} else {
	echo form_open('coaching/tests_actions/unenrol_users/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id, array('class'=>'form-horizontal row-border', 'id'=>'enrol_users') ); 		
}
?>
<input type="hidden" id="goto" value="<?php echo site_url ('tests/page/enrolments/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id.'/'.$type); ?>" >
<input type="hidden" id="confirm_goto" value="<?php echo site_url ('tests/page/enrolments/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id.'/'.$type); ?>" >
	<div class="row" id="enrolment_users">
		<div class="col-md-12">
			<div class="widget ">			
				<div class="widget-content">				
					<?php if ( ! empty ($results)) { 
						$i = 0;
						?> 
						<table class="table table-bordered table-checkable ">
							<thead>
								<tr>
									<th class="checkbox-column">
										<?php echo form_checkbox(array('name'=>'chk', 'class'=>'uniform')); ?>
									</th>
									<th width="50%"><?php echo 'Name'; ?></th>
									<th><?php echo 'Admission No'; ?></th>
									<th><?php echo 'Serial No'; ?></th>
									<?php if ($type==ENROLED_IN_TEST) { ?>
										<th><?php echo 'Enrolled From'; ?></th>
										<th><?php echo 'Enrolled Till'; ?></th>
									<?php } ?>
									<th><?php echo 'Options'; ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
								foreach ($results as $member_id) {
									$row_items = $this->users_model->get_user ($member_id);
									$items = $this->tests_model->getDate ($member_id, $test_id);
									if ($row_items['role_id'] <> $role_id) {
										///break;
									}
									$i++;
									// procedure to 
									if (isset($mycheck[$row_items['member_id']]) && $mycheck[$row_items['member_id']] == $row_items['member_id']) {
										 $check = true;
									} else {
										 $check = false;
									}
									// load rules ie Function Buttons which are provided to the user 		
									?>
									<tr>
										<td class="checkbox-column"><?php echo form_checkbox (array('name'=>'mycheck['.$row_items['member_id'].']', 'value'=>$row_items['member_id'], 'checked'=>$check, 'class'=>'uniform')); ?></td>                                

										<td>
											<?php $profile_image = $this->users_model->view_profile_image ($row_items['member_id']); ?>
											<img src="<?php echo base_url ($profile_image); ?>" height="40" width="40" class="img-thumbnail img-circle">

											<?php echo ($row_items['first_name']) .' '. ($row_items['second_name']) .' '. ($row_items['last_name']); ?>
										</td>
										<td><?php echo $row_items['adm_no']; ?></td>
										<td><?php echo $row_items['sr_no']; ?></td>
										<?php if ($type==ENROLED_IN_TEST) { ?>
											<td><?php echo $items['start_date']; ?></td>
											<td><?php echo $items['end_date']; ?></td>
										<?php } ?>
										<td> 
										<?php if ($type==ENROLED_IN_TEST) { ?>
											<a href="javascript: void ()" onclick="show_confirm_ajax ('Un-enroll this user?', '<?php echo site_url ('coaching/tests_actions/unenrol_users/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id.'/'.$row_items['member_id']); ?>')"><i class="fa fa-trash"></i></a>
										<?php } ?>
										</td>
									</tr>
									<?php 
									} // foreach
									if ($i == 0) {
										echo '<tr>';
											echo '<td colspan="5">';
												echo 'No users found';
											echo '</td>';
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
						<br>
						<?php if ($i > 0) { ?>
							<div class="row">
								<div class="col-md-2">
									<strong>With Selected</strong>
								</div>
								<div class="col-md-8">
									<a id="set_enrolment" class="btn btn-info" data-toggle="modal" href="#enrolment_settings" disabled>Set Enrollment Period <i class="fa fa-arrow-right"></i> </a>
									<?php if ($type == ENROLED_IN_TEST) { ?>
										<input type="submit" value="Un-Enroll Users" class="btn btn-danger">
									<?php } ?>
								</div>
							</div> <!-- /row -->
						<?php
						}						
					} else { 
						?>		
						<div class="alert alert-danger"><?php echo 'No users found'; ?></div>
						<?php 
					} 
					?>
				
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<!-- Modal dialog -->
		<div class="modal fade" id="enrolment_settings">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Set Enrolment Period</h4>
					</div>
					<div class="modal-body" style="padding-left: 20px">
						
						<div class="form-group">
							<div class="enrol_message"></div>
						</div>
						
						<div class="form-group">
							<?php echo form_label('Attempts allowed', '', array('class'=>'control-label col-md-3')); ?>
							<div class="col-md-9"> 
								<select name="num_takes" class="form-control input-width-medium" id="attempts">
									<?php for ($i=1; $i<=10; $i++) { ?>
										<option value="<?php echo $i; ?>" <?php echo set_select ('num_takes', $i); ?> ><?php echo $i; ?></option>                    
									<?php } ?>
									<option value="0" <?php echo set_select ('num_takes', 0, true); ?> >Unlimited</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">						
							<?php echo form_label('Release Results', '', array('class'=>'control-label col-md-3')); ?>
							<div class="col-md-9"> 
								<label class="radio">
									<input type="radio" name="result_release" value="<?php echo RELEASE_EXAM_NEVER; ?>" <?php echo set_radio ('result_release', RELEASE_EXAM_NEVER, true); ?> />Do not release results
								</label>
								<label class="radio">
									<input type="radio" name="result_release" value="<?php echo RELEASE_EXAM_IMMEDIATELY; ?>" <?php echo set_radio ('result_release', RELEASE_EXAM_IMMEDIATELY); ?> />Release immediately when test is submitted or marked (offline test)
								</label>
							</div>								
						</div>								
						
						<div class="form-group">
							<?php echo form_label('Enroll From', '', array('class'=>'control-label col-md-3')); ?>
							<div class="col-md-9"> 
								<input type="text" name="start_date" value="<?php echo set_value ('start_date', date ('d/m/Y')); ?>" class="start_enroll form-control input-width-small "  >
							</div>
						</div>

						<div class="form-group">
							<?php echo form_label('Enroll Till', '', array('class'=>'control-label col-md-3')); ?>
							<div class="col-md-9"> 
								<input type="text" name="end_date" value="<?php echo set_value ('end_date', date ('d/m/Y')); ?>" class="end_enroll form-control input-width-small" >
							</div>
						</div>
						
						<div class="form-group">
							<?php echo form_label('Extra Time', '', array('class'=>'control-label col-md-3')); ?>
							<div class="col-md-9"> 
								<input type="text" name="extra_time" value="<?php echo set_value ('extra_time', '0'); ?>" class="form-control digits input-width-small" > seconds
							</div>
						</div>
					</div> 
					
					<div class="modal-footer">
						<a href="javascript: void ()" class="btn btn-default" i data-dismiss="modal" ><i class="fa fa-arrow-left"></i> Select Users</a>
						<input type="submit" class="btn btn-success" value="Save" >
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</div> <!-- /.row -->
	
</form>

<script> 
/*
	<?php if  ($tab == TAB_NOT_ENROLED && $errors == true) { ?>
		$(window).load(function() {
			$("#en_set_date").modal('show');
		});	
	<?php } ?>
	
	 $('input[type="checkbox"]').change(function() {	
		if (this.checked) {
			$('#enrolment_button').show ();
			//alert ()
		} else {
			$('#enrolment_button').hide ();			
		}
    });
	
*/	

$('input[type="checkbox"]').change(function() {	
	
	/*
	if (this.not(":checked").length === 0)) {
		$("#set_enrolment").attr("disabled", true);
		//$('#set_enrolment').show ();
		//alert ()
	} else {
		$("#set_enrolment").removeAttr('disabled');
		//$('#set_enrolment').hide ();
	}*/
});

//$("#set_enrolment").attr("disabled", true); 

$( ".start_enroll" ).datepicker({ 
	defaultDate: +0,
	showOtherMonths:true, 
	autoSize: true,
	dateFormat: 'dd/mm/yy',
	changeMonth: true,
	autoSize: true,
	appendText: '<span class="help-block">(dd/mm/yyyy)</span>',	
	changeYear: true,
	onClose: function( selectedDate ) {
		$( ".end_enroll" ).datepicker( "option", "minDate", selectedDate );
	}
});	

$( ".end_enroll" ).datepicker({
	defaultDate: +0,
	showOtherMonths:true, 
	autoSize: true,
	dateFormat: 'dd/mm/yy',
	changeMonth: true,
	autoSize: true,
	appendText: '<span class="help-block">(dd/mm/yyyy)</span>',	
	changeYear: true,
	onClose: function( selectedDate ) {
		$( ".start_enroll" ).datepicker( "option", "maxDate", selectedDate );
	}
});	

/* Upload Profile Image */
$('#enrol_users').submit (function (e) {	
	e.preventDefault ();
		var pageURL = $(this).attr ('action');
		var gotoURL = $('#goto').val ();

		$.ajax ({
			beforeSend: function(){
				NProgress.start();
			},
			complete: function(){
				NProgress.done(); 
			},
			type: 'POST',
			url: pageURL,
			data: $(this).serialize (),
			success: function (result) {
				if (is_int (result)) {
					$('.enrol_message').html ('<div class="alert alert-success">Users enrolled successfully</div>');
					document.location.href= gotoURL;
				} else {
					$('.enrol_message').html (result);
				}
			}
		});		
		
	
});

</script>