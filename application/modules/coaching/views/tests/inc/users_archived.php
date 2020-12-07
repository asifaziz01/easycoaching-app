<?php echo form_open('coaching/tests_actions/unenrol_users/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id.'/'.$type.'/'.$batch_id.'/'.$status, array('class'=>'form-horizontal row-border', 'id'=>'validate-1') ); ?>
	<div class="card card-default">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-hover table-checkable datatable mb-0">
				<thead>
					<tr>
						<th class="text-center" width="3%">
							<?php echo form_checkbox(array('name'=>'chk', 'class'=>'check-all', 'id'=>'check-all')); ?>
						</th>
						<th class="text-right" width="3%">#</th>
						<th width="85%"><?php echo 'Name'; ?></th>
						<th><?php echo 'Actions'; ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				if ( ! empty ($results)) { 
					foreach ($results as $row) {
						?>
						<tr>
							<td class="text-center">
								<?php 
								echo form_checkbox (array('name'=>'users[]', 'value'=>$row['member_id'], 'class'=>'check'));
								?>
							</td>
							<td class="text-right" ><?php echo $i; ?></td>
							<td>
								<?php echo ($row['first_name']) .' '. ($row['second_name']) .' '. ($row['last_name']); ?><br>
								<?php echo $row['adm_no']; ?>
							</td>
							<td> 
								<a href="javascript: void ()" onclick="show_confirm ('Un-enroll this user?', '<?php echo site_url ('tests/ajax_func/unenrol_user/'.$category_id.'/'.$test_id.'/'.$role_id.'/'.$class_id.'/'.$type.'/'.$batch_id.'/'.$status.'/'.$row['member_id'].'/1'); ?>')"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
						<?php 
						$i++;
					} // foreach
				} else {
					?>
					<tr>
						<td colspan="4" class="text-center bg-danger text-white">No Users Archived</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
		<?php if ($i > 0) { ?>
		<div class="card-footer">
			<div class="media">
				<div class="media-left my-auto">
					<strong>With Selected</strong>
				</div>
				<div class="media-left pr-0">
					<?php //echo anchor ('tests/admin/manage/'.$category_id.'/'.$test_id, '<i class="fa fa-arrow-left"></i> Cancel', array ('class'=>'btn') ); ?>
					<a id="set_enrolment" class="btn btn-info d-none"  href="javascript: void ()" disabled>Set Enrollment Period <i class="fa fa-arrow-right"></i> </a>
					<select name="actions" class="form-control">
						<option value="">Select an action</option>
						<!--<option value="archive">Archive</option>-->
						<option value="unenrol">Un-Enrol</option>
					</select>
				</div>
				<div class="media-right my-auto">
					<input type="submit" value="Submit" class="btn btn-primary btn-sm">
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
<?php echo form_close();?>