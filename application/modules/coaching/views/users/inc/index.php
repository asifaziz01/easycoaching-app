	<div class="card card-default">
		<div class="-table-responsive">
			<table class="table table-bordered v-middle mb-0" id="data-tables">
				<thead>
					<tr>
						<th width="3%">
							<input id="checkAll" type="checkbox" onchange="check_all()" >
						</th>
						<th width="3%">
							#
						</th>
						<th ><?php echo 'Name'; ?></th>
						<th class="d-none d-sm-table-cell"><?php echo 'Contact'; ?></th>
						<th class="d-none d-sm-table-cell"><?php echo 'Status'; ?></th>
						<th class="d-none d-sm-table-cell"><?php echo 'Role'; ?></th>
						<th width=""><?php echo 'Actions'; ?></th>
					</tr>
				</thead>

				<tbody>
				<?php
				$i = 1 ;
				if ( ! empty ($results)) {
					foreach ($results as $row) {
						?>
						<tr>
							<td>
								<input id="" type="checkbox" name="mycheck[]" value="<?php echo $row['member_id']; ?>" class="checks">
							</td>
							<td><?php echo $i; ?></td>
							<td>
								<a class="" href="<?php echo site_url ('coaching/users/create/'.$coaching_id.'/'.$row['role_id'].'/'.$row['member_id']); ?>"> 
									<?php echo ($row['first_name']) .' '. ($row['second_name']) .' '. ($row['last_name']); ?>

								</a> <br> 
								<?php echo $row['adm_no']; ?>
							</td>
							<td class="d-none d-sm-table-cell"><?php echo $row['primary_contact']; ?></td>
							<td class="d-none d-sm-table-cell">
								<?php if ($row['status'] == USER_STATUS_ENABLED) { ?>
									<span class="badge badge-success">Enabled</span>
								<?php } else if ($row['status'] == USER_STATUS_UNCONFIRMED) { ?>
									<span class="badge badge-danger">Pending</span>
								<?php } else if ($row['status'] == USER_STATUS_DISABLED) { ?>
									<span class="badge badge-secondary">Disabled</span>
								<?php } ?>
							</td>
							<td class="d-none d-sm-table-cell"><?php echo $row['description']; ?></td>
							<td> 
								<div class="dropdown">
									<a class="btn btn-outline dropdown-toggle" type="button" id="userMenu<?php echo $row['member_id'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Edit</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu<?php echo $row['member_id'];?>">
										<?php echo anchor('coaching/users/edit/'.$coaching_id.'/'.$row['role_id'].'/'.$row['member_id'], '<i class="fa fa-edit"></i> Profile', array('title'=>'Edit', 'class'=>'dropdown-item')); ?>
										
										<?php if ( $row['status'] == USER_STATUS_ENABLED ) { ?>
											<a href="javascript:void(0)" onclick="javascript:show_confirm ( '<?php echo 'Do you want to disable this user?'; ?>', '<?php echo site_url('coaching/user_actions/disable_member/'.$coaching_id.'/'.$role_id.'/'.$row['member_id']); ?>' )" title="Disable" class="dropdown-item" ><i class="fa fa-times-circle"></i> Disable Account</a>
										<?php } else if ( $row['status'] == USER_STATUS_DISABLED ) { ?>
											<a href="javascript:void(0)" onclick="javascript:show_confirm ( '<?php echo 'Do you want to enable this user?'; ?>', '<?php echo site_url('coaching/user_actions/enable_member/'.$coaching_id.'/'.$role_id.'/'.$row['member_id']); ?>' )" class="dropdown-item"><i class="fa fa-check-circle"></i> Enable Account</a>
										<?php } else if ($row['status'] == USER_STATUS_UNCONFIRMED) { ?>
											<a href="javascript:void(0)" onclick="javascript:show_confirm ( '<?php echo 'Do you want to approve this user?'; ?>', '<?php echo site_url('coaching/user_actions/enable_member/'.$coaching_id.'/'.$role_id.'/'.$row['member_id']); ?>' )" class="dropdown-item"><i class="fa fa-check-circle"></i> Approve</a>
										<?php } ?>
										<?php //echo anchor('coaching/users/member_log/'.$coaching_id.'/'.$role_id.'/'.$row['member_id'], '<i class="fa fa-info-circle"></i> Member Log', array ('class'=>'dropdown-item') ); ?>
										
										<?php echo anchor('coaching/users/change_password/'.$coaching_id.'/'.$row['member_id'], '<i class="fa fa-key"></i> Change Password', array ('class'=>'dropdown-item')); ?>
										<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this users?' ; ?>','<?php echo site_url('coaching/user_actions/delete_account/'.$coaching_id.'/'.$role_id.'/'.$row['member_id']); ?>' )" class="dropdown-item"><i class="fa fa-trash"></i> Delete Account</a>
									</div>
								</div>
							</td>
						</tr>
						<?php 
						$i++;
					} // foreach 
				} else {
					?>
					<tr>
						<td colspan="6"><span class="text-danger">No users found</span></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table> 
		</div>

		<div class="card-footer px-2">
			<div class="px-1">
				<div class="input-group">
					<select name="action" class="custom-select w-auto">
						<option value="0">---With Selected---</option>
						<option value="delete">Delete</option>
						<option value="enable">Enable Account</option>
						<option value="disable">Disable Account</option>
					</select>
					<div class="input-group-append">
						<input type="submit" name="Submit" value="Change" class="btn btn-primary btn-sm" />
					</div>
				</div>
			</div>
		</div>
	</div>
