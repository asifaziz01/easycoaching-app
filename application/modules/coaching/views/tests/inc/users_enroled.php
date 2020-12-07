<?php echo form_open('coaching/tests_actions/unenrol_users/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type.'/'.$role_id.'/'.$class_id.'/'.$batch_id.'/'.$status, array('class'=>'form-horizontal row-border', 'id'=>'validate-1') ); ?>
	<div class="card ">
		<table class="table table-bordered table-hover table-checkable datatable mb-0">
			<thead>
				<tr>
					<th class="text-center" width="2%">
						<?php echo form_checkbox(array('name'=>'chk', 'class'=>'check-all', 'id'=>'check-all')); ?>
					</th>
					<th class="text-right" width="2%">#</th>
					<th width=""><?php echo 'Name'; ?></th>
					<th width=""><?php echo 'Role '; ?></th>
					<th class="d-none d-md-table-cell"><?php echo 'Num Attempts'; ?></th>
					<th class="d-none d-md-table-cell"><?php echo 'Start Date'; ?></th>
					<th class="d-none d-md-table-cell"><?php echo 'End Date'; ?></th>
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
						<td class="text-center" >
							<?php
							echo form_checkbox (array('name'=>'users[]', 'value'=>$row['member_id'], 'class'=>'check'));
							?>
						</td>
						<td class="text-right" ><?php echo $i++; ?></td>
						<td>
							<?php echo ($row['first_name']) .' '. ($row['second_name']) .' '. ($row['last_name']); ?><br>
							<?php echo $row['adm_no']; ?>
						</td>
						<td class="" ><?php echo $row['description']; ?></td>
						<td class="d-none d-md-table-cell" >
							<?php
							if ($row['attempts'] == 0) {
								echo '<span class="badge badge-primary">Unlimited</span>';
							} else {
								echo $row['attempts'];
							}
							?>							
						</td>
						<td class="d-none d-md-table-cell"><?php echo date ('d-m-Y H:i A', $row['start_date']); ?></td>
						<td class="d-none d-md-table-cell"><?php echo date ('d-m-Y H:i A', $row['end_date']); ?></td>
						<td> 
							<a href="javascript: void ()" onclick="show_confirm ('Un-enroll this user?', '<?php echo site_url ('coaching/tests_actions/unenrol_user/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type.'/'.$role_id.'/'.$class_id.'/'.$batch_id.'/'.$status.'/'.$row['member_id'].'/1'); ?>')"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php 
				} // foreach
			} else {
				?>
				<tr>
					<td colspan="7" class="text-danger ">No users found <?php echo anchor ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.NOT_ENROLED_IN_TEST, 'Enrol Now'); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		
		<?php if ($i > 1) { ?>
			<div class="card-footer">
				<div class="form-inline">
						<a id="set_enrolment" class="btn btn-info d-none"  href="javascript: void ()" disabled>Set Enrollment Period <i class="fa fa-arrow-right"></i> </a>
						<select name="actions" class="form-control w-50">
							<option value="">With selected</option>
							<option value="archive">Archive</option>
							<option value="unenrol">Un-Enrol</option>
						</select>
						<input type="submit" value="Submit" class="btn btn-primary btn-sm ml-2">
				</div>
			</div>
		<?php } ?>
	</div>
<?php echo form_close();?>