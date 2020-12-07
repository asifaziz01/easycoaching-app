<?php echo form_open('coaching/tests_actions/enrol_users/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type.'/'.$role_id.'/'.$class_id.'/'.$batch_id.'/'.$status, array('class'=>'form-horizontal row-border', 'id'=>'validate-1') ); ?>
	<div class="card card-default">
		<table class="table table-bordered table-hover table-checkable datatable mb-0">
			<thead>
				<tr>
					<th class="text-right" width="3%">#</th>
					<th class="text-center" width="3%">
						<?php echo form_checkbox(array('name'=>'chk', 'class'=>'check-all', 'id'=>'check-all', 'title'=>'Select All')); ?>
					</th>
					<th width="50%"><?php echo 'Name'; ?></th>
					<th width=""><?php echo 'S No'; ?></th>
					<th width=""><?php echo 'Role'; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			if ( ! empty ($results)) { 
				foreach ($results as $row) {
					?>
					<tr>
						<td class="text-right" ><?php echo $i; ?></td>
						<td class="text-center">
							<?php
							echo form_checkbox (array('name'=>'users[]', 'value'=>$row['member_id'], 'class'=>'check'));
							?>
						</td>
						<td>
							<?php echo ($row['first_name']) .' '. ($row['second_name']) .' '. ($row['last_name']); ?><br>
							<?php echo $row['adm_no']; ?>
						</td>
						<td><?php echo $row['sr_no']; ?></td>
						<td><?php echo $row['description']; ?></td>
					</tr>
					<?php 
					$i++;
				} // foreach
			} else {
				?>
				<tr>
					<td colspan="6" class="text-danger">No users found</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php if ($i > 1) { ?>
			<div class="card-footer">
				<div class="media">
					<div class="media-left my-auto">
						<strong>With Selected</strong>
					</div>
					<div class="media-left pr-0">
						<a id="set_enrolment" data-toggle="modal" data-target="#enrolment_settings" class="btn btn-info btn-sm"  href="javascript: void ()" disabled>Set Enrollment Period <i class="fa fa-arrow-right"></i> </a>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="modal fade" id="enrolment_settings">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Set Enrolment Period</h4>
				</div>
				<div class="modal-body" style="padding-left: 20px">					

					<div class="form-row mb-3">
						<?php echo form_label('Enroll From', '', array('class'=>'col-md-2')); ?>
						<div class="col-md-4">
							<input type="date" name="start_date" value="<?php echo set_value ('start_date', date ('Y-m-d')); ?>" min="<?php echo date('Y-m-d');?>" class="start_enroll form-control input-width-small " />
						</div>

						<div class="col-md-3">
							<select name="start_time_hh" id="start_time_hh" class="form-control" >
								<?php for ($i=0; $i<=23; $i++) { ?>
									<option value="<?php echo $i; ?>" ><?php echo str_pad($i, 1, '0', STR_PAD_LEFT); ?></option>
								<?php } ?>
							</select>
							<?php echo form_label('Hours', '', array('class'=>'control-label')); ?>
						</div>

						<div class="col-md-3">
							<select name="start_time_mm" id="start_time_mm" class="form-control" >
								<?php for ($i=0; $i<60; $i=$i+15) { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<?php echo form_label('Minutes', '', array('class'=>'control-label')); ?>
						</div>

					</div>

					<div class="form-row mx-0 mb-3">
						<?php echo form_label('Enroll Till', '', array('class'=>'col-md-2')); ?>
						<div class="col-md-4">
							<input type="date" name="end_date" value="<?php echo set_value ('end_date', date ('Y-m-d')); ?>" min="<?php echo date('Y-m-d');?>" class="end_enroll form-control input-width-small" />
						</div>

						<div class="col-md-3">
							<select name="end_time_hh" id="end_time_hh" class="form-control" >
								<?php for ($i=0; $i<=23; $i++) { ?>
									<option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<?php echo form_label('Hours', '', array('class'=>'control-label')); ?>
						</div>

						<div class="col-md-3">
							<select name="end_time_mm" id="end_time_mm" class="form-control" >
								<?php for ($i=0; $i<60; $i=$i+15) { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<?php echo form_label('Minutes', '', array('class'=>'control-label')); ?>
						</div>
					</div>

					<div class="form-row mx-0 mb-3">
						<?php echo form_label('Attempts allowed', '', array('class'=>'col-md-2', 'for' => 'attempts')); ?>
						<div class="col-md-10"> 
							<select name="num_takes" class="form-control custom-select" id="attempts">
								<?php for ($i=1; $i<=10; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo set_select ('num_takes', $i); ?> ><?php echo $i; ?></option>                    
								<?php } ?>
								<option value="0" <?php echo set_select ('num_takes', 0, true); ?> >Unlimited</option>
							</select>
						</div>
					</div>
					
					<div class="form-row mx-0 mb-3">
						<?php echo form_label('Release Results', '', array('class'=>'col-md-2')); ?>
						<div class="col-md-10">
							<label class="form-label">
								<input type="radio" name="result_release" value="<?php echo RELEASE_EXAM_IMMEDIATELY; ?>" <?php echo set_radio ('result_release', RELEASE_EXAM_IMMEDIATELY, true); ?> class="" />
								Release immediately when test is submitted
							</label><br>
							<label class="form-label">
								<input type="radio" name="result_release" value="<?php echo RELEASE_EXAM_NEVER; ?>" <?php echo set_radio ('result_release', RELEASE_EXAM_NEVER); ?> class="" id="notRelease" />
								Release Manually
							</label>
						</div>
					</div>					
					
					<div class="form-row mx-0 mb-3">
						<?php echo form_label('Extra Time', '', array('class'=>'col-md-2')); ?>
						<div class="col-md-10">
							<div class="input-group mb-3">
								<input type="number" name="extra_time" min="0" value="<?php echo set_value ('extra_time', '0'); ?>" class="form-control digits" />
								<div class="input-group-append">
									<span class="input-group-text">seconds</span>
								</div>
							</div>
						</div>
					</div>
				</div> 
				
				<div class="modal-footer justify-content-between">
					<a href="javascript:void();" class="btn btn-default" data-dismiss="modal" id="select_users" ><i class="fa fa-arrow-left"></i> Select Users</a>
					<input id="save" type="submit" class="btn btn-success" value="Save" >
				</div>
				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
<?php echo form_close();?>