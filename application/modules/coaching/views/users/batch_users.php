<div class="row" >
	<div class="col-md-12">
		<ul class="nav nav-tabs" id="users" role="tablist">
		  <li class="nav-item">
			<a class="nav-link <?php if ($add_users == 0) echo 'active'; ?>" href="<?php echo site_url ('coaching/users/batch_users/'.$coaching_id.'/'.$batch_id )?>" >Users In Batch <span class="badge badge-primary"><?php echo $num_users_in; ?></span></a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link <?php if ($add_users == TRUE) echo 'active'; ?>" href="<?php echo site_url ('coaching/users/batch_users/'.$coaching_id.'/'.$batch_id.'/1' )?>" >Users Not In Batch <span class="badge badge-primary"><?php echo $num_users_notin; ?></span></a>
		  </li>
		</ul>
	</div>
</div>

<div class="row" >
	<div class="col-md-12 ">
		<div class="card">
			<?php 
			if ($add_users > 0) {
				echo form_open ('coaching/user_actions/save_batch_users/'.$coaching_id.'/'.$batch_id, array ('id'=>'validate-1'));
			} else {
				echo form_open ('coaching/user_actions/remove_batch_users/'.$coaching_id.'/'.$batch_id, array ('id'=>'validate'));
			}
			?>				
				<table class="table table-hover ">
					<thead>
						<th width="5%">
							#
						</th>
						<th width="5%">
							<input type="checkbox" name="" value="" class="check" id="check-all">
						</th>
						<th width="60%">Name</th>
						<th width="">Role</th>
						<?php if ($add_users == 0) { ?>
							<th width="">Action</th>
						<?php } ?>
					</thead>
					<tbody> 
					<?php
					$i = 1;
					if (! empty($result)) {
						foreach ($result as $item) {
							?>
							<tr class="check">
								<td>
									<?php echo $i; ?>.
								</td>
								<td>
									<input type="checkbox" name="users[]" value="<?php echo $item['member_id']; ?>" class="check">
								</td>
								<td>
									<?php echo $item["first_name"].' '.$item["last_name"];?><br>
									<?php echo $item["adm_no"]; ?>
								</td>
								<td>
									<?php echo $item ["description"]; ?>
								</td>
								<?php if ($add_users == 0) { ?>
									<td>
										<!-- DELETE LOG  -->
										<a href="javascript:void(0);" onclick="show_confirm ('Remove this user from batch <?php echo $batch_title; ?>?', '<?php echo site_url("coaching/user_actions/remove_batch_user/".$coaching_id.'/'.$batch_id.'/'.$item['member_id'].'/'.$add_users); ?>')" class="btn btn-link" data-title="Remove User"><i class="fa fa-trash"></i></a>
									</td>
								<?php } ?>
							</tr>
							<?php 
							$i++;
						}
					} else { 
						echo '<tr><td colspan="4">No users in this batch</td></tr>';
					}
					?>
					</tbody>
				</table>
				<div class="card-footer">
					<?php if ($add_users > 0) { ?>
						<input type="submit" value="Add Users" class="btn btn-primary"> 
					<?php } else { ?>
						<input type="submit" value="Remove" class="btn btn-danger"> 
					<?php } ?>
				</div>

			</form>
		</div>
	</div>
</div>