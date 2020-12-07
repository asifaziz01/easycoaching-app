<div class="card card-default">
	<div class="card-header">
		<h4>Users</h4>
	</div>
	<?php 
	echo form_open('users/ajax_func/confirm/'.$class_id.'/'.$role_id.'/'.$status, array('class'=>'form-horizontal row-border', 'id'=>'confirm') );
		if ( ! empty ($results)) {
			?>
			<!-- Data table -->		
			<table data-toggle="data-tables" class="table v-middle" id="data-tables">
				<thead>
					<tr>
						<th width="5%">
								<input id="checkAll" type="checkbox" >
						</th>
						<th width=""><?php echo 'User-id'; ?></th>
						<th width="25%"><?php echo 'Name'; ?></th>
						<th width=""><?php echo 'Contact'; ?></th>
						<th width=""><?php echo 'Email'; ?></th>
						<th width=""><?php echo 'Actions'; ?></th>
					</tr>
				</thead>
				
				<tbody id="responsive-table-body">
					<?php
					$i = 0 ;
					foreach ($results as $row_items) {
						$profile_image = $this->users_model->view_profile_image ($row_items['member_id']);
						?>
						<tr>
							<td>
								<input id="" type="checkbox" name="mycheck[]" value="<?php echo $row_items['member_id']; ?>" class="checks">
							</td>
							<td>
								<a class="label label-default" href="<?php echo site_url ('users/admin/create/0/'.$row_items['role_id'].'/'.$row_items['member_id']); ?>"> <?php echo $row_items['adm_no']; ?></a>
							</td>
							<td>
								<?php 
								if ($profile_image) {
									$rand_str = time ();
									?>
									<img src="<?php echo base_url ($profile_image); ?>?<?php echo $rand_str; ?>" alt="Profile Image" class="img-circle width-50" >
								<?php } else { ?>
									<div class="icon-block width-50 bg-grey-100">
										<i class="fa fa-photo text-light"></i>
									</div>
								<?php } ?>
								<?php echo ($row_items['first_name']) .' '. ($row_items['second_name']) .' '. ($row_items['last_name']); ?>
							</td>
							
							<td><?php echo $row_items['primary_contact']; ?></td>
							<td><?php echo $row_items['email']; ?></td>
							
							<td> 
								<div class="dropdown">
									<a class="btn btn-white paper-shadow dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-ellipsis-v"></i></a>
									<ul class="dropdown-menu">
										<li class="dropdown-item" ><?php echo anchor('users/admin/create/'.$class_id.'/'.$row_items['role_id'].'/'.$row_items['member_id'], '<i class="fa fa-edit"></i> Edit Account', array('title'=>'Edit', 'class'=>'')); ?>
										</li>
										<?php if ( $row_items['status'] == USER_STATUS_ENABLED ) { ?>
											<li class="dropdown-item" ><a href="javascript:void(0)" onclick="javascript:show_confirm ( '<?php echo 'Do you want to disable this user?'; ?>', '<?php echo site_url('users/ajax_func/disable_member/'.$class_id.'/'.$role_id.'/'.$row_items['member_id']); ?>' )" title="Disable" ><i class="fa fa-times-circle"></i> Disable Account</a></li>
										<?php } else if ( $row_items['status'] == USER_STATUS_DISABLED ) { ?>
											<li class="dropdown-item" ><a href="javascript:void(0)" onclick="javascript:show_confirm ( '<?php echo 'Do you want to enable this user?'; ?>', '<?php echo site_url('users/ajax_func/enable_member/'.$class_id.'/'.$role_id.'/'.$row_items['member_id']); ?>' )" ><i class="fa fa-check-circle"></i> Enable Account</a></li>
										<?php } ?>
										
										<li class="dropdown-item" hidden><?php echo anchor('users/admin/member_log/'.$class_id.'/'.$role_id.'/'.$row_items['member_id'], '<i class="fa fa-info-circle"></i> Member Log' ); ?></li>
										<li class="dropdown-item" hidden><?php echo anchor_popup('users/admin/view/'.$class_id.'/'.$role_id.'/'.$row_items['member_id'], '<i class="fa fa-reorder"></i> Details' ); ?></li>
										<?php if ($this->common_model->module_installed ('tests')) { ?>
										<?php } ?>
										<?php if ($row_items['status'] == USER_STATUS_UNCONFIRMED) { ?>
										<li class="dropdown-item">
											<a href="javascript:show_confirm_ajax ('Send email verication link?', '<?php echo site_url ('users/ajax_func/send_confirmation_email/'.$row_items['member_id']);?>')" class=""><i class="fa fa-link"></i> Resend Confirmation Email</a> 
										</li>
										<?php } else { ?>
											<li class="dropdown-item" ><?php echo anchor('users/admin/change_password/'.$class_id.'/'.$role_id.'/'.$row_items['member_id'], '<i class="fa fa-key"></i> Change Password'); ?></li>
										<?php } ?>
										<li class="dropdown-item" >
											<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this users?' ; ?>','<?php echo site_url('users/ajax_func/delete_account/'.$class_id.'/'.$role_id.'/'.$status.'/'.$row_items['member_id']); ?>' )" ><i class="fa fa-trash"></i> Delete Account</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php 
					} // foreach 
					?>
				</tbody>
			</table>
		
		<?php
		} else { // results 
			$pending = $this->users_model->countMemberByStatus (USER_STATUS_UNCONFIRMED, $role_id);
			?>
			<div class="card-body">
				<div class="alert alert-danger">
					<p>
					<?php echo 'No user found'; ?>
					</p>
				</div>
				<hr>
				<div class="">
					<?php
						echo anchor('users/admin/create/'.$class_id.'/'.$role_id, '<i class="fa fa-plus"></i> Create User', array('class'=>'btn btn-success '));
						?>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#import_users<?php echo $class_id; ?>"><i class="fa fa-upload" aria-hidden="true"></i> Import Users</button>
					
				</div>
			</div>	
			<div class="card-body">				
				<div class="">
					<?php if ($pending > 0) { ?>
						<p><?php echo 'There are <a href="'.site_url ('users/admin/index/'.$class_id.'/'.$role_id.'/'.USER_STATUS_UNCONFIRMED).'">'.$pending.' pending user </a> accounts'; ?></p>
					<?php } ?>
				</div>
			</div>
		<?php
		}
		?>
		<div class="card-footer">
			<div class="row">
				<div class="col-md-3">
					<?php 
					if ( ! empty ($results)) {
						$options = array( 0=> 'With Selected') ;
						$options['delete'] = 'Delete';
						$options['export'] = 'Export';
						if ( $status == USER_STATUS_UNCONFIRMED) {	
							$options['approve'] = 'Approve';
						} else if ($status == USER_STATUS_DISABLED ) {
							$options['enable'] = 'Enable';
						} else if ($status == USER_STATUS_ENABLED) {
							$options['disable'] = 'Disable';
						}
						echo form_dropdown ('action', $options, '', 'class="form-control " id="action"');
					}
					?>
				</div>
				<div class="col-md-9">
					<div class="btn-toolbar">
					<?php 
					if ( ! empty ($results)) {
						echo form_submit( array('name'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-primary'));
						?>
						<button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#import_users<?php echo $class_id; ?>"><i class="fa fa-upload" aria-hidden="true"></i> Import Users</button>
						<?php
						echo anchor('users/admin/create/'.$class_id.'/'.$role_id, '<i class="fa fa-plus"></i> Create User', array('class'=>'btn btn-success pull-right'));
						?>
						<?php
					}
					?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
				</div>
				<div class="col-md-6">
					<?php 
					if ( ! empty ($results)) {
					}
					?> 
				</div>
			</div>
		</div>
	</form>
</div> 	

<!-- IMPORT QUESTIONS FROM CSV -->
<div class="row">
	<!-- Modal dialog -->
	<div class="modal grow modal-backdrop-white fade" id="import_users<?php echo $class_id; ?>">
		<div class="modal-dialog modal-md">
		  <div class="v-cell">
			<div class="modal-content">
			<?php echo form_open_multipart ('users/ajax_func/import_from_csv/'.$class_id.'/'.$role_id, array ('class'=>'form-horizontal', 'id'=>'upload-user') ); ?>
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Import 
				<?php 
				if ( ! empty ($roles) ) {
					foreach ($roles as $role) {
						if ($role['role_id'] == $role_id) {
							echo $role['description'];
						}
					}
				}
				?>
				</h4> 
			  </div>
			  <div class="modal-body">
					<div class="row">
						<label class="col-md-3">Browse CSV File</label>
						<div class="col-md-9">
							<input type="file" name="userfile" size="20" class="form-control" />
							<p class="help-text">.csv files only</p>
						</div>
					</div>
					<p><small class="text-danger">Please upload students list in prescribed csv format. Note that Email, First Name and Last Name should not be left blank.</small></p>
					<a href="<?php echo site_url ('users/admin/download_file/users_sample')?>" class="btn btn-link">Download sample file</a>
			</div>
			  <div class="modal-footer">
				<div id="validation-message-csv" class="pull-left"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input type="submit" id="" class="btn btn-primary" value="Import ">
			</div>
			<?php echo form_close (); ?>
			</div>
		  </div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready ( function () {
	/* UPLOAD FROM CSV */
	$('#upload-user').validate({
		submitHandler: function (form) {	
			var pageURL = $(form).attr ('action');
			// Create an arbitrary FormData instance
			var formData = new FormData($('#upload-user')[0]); 
			$.ajax ({
				type: 'POST',
				url: pageURL,
				processData: false,
				contentType: false, 
				data: formData,
				beforeSend: function(){
					toastr.info("Please wait...", "", {
						"timeOut": 10000,
						"positionClass": "toast-top-right",
						"preventDuplicates": true,
					});	
				},
				complete: function(){
					toastr.clear()
				},
				success: function(response) { 
					if (response.status === true ) {
						toastr.success(response.message, "Done", {
							"timeOut": 10000,
							"positionClass": "toast-top-right",
							"preventDuplicates": true,
						});
						document.location.href = response.redirect;
					} else {
						toastr.error(response.error, "Error", {
							"timeOut": 10000,
							"preventDuplicates": true,
							"closeButton":true,
							"positionClass": "toast-top-right",
						});
					}

				}

			});			
		}
	});
	
	//DataTable
	$('#data-table').DataTable();

	/* SELECT ALL CHECKBOXES */
	$('#checkAll').on('click',function() {
		if ($(this).is(':checked')) {
			$('.checks').prop('checked','checked');
		} else {        
			$('.checks').prop('checked','');
		}
	});
});


	

</script>