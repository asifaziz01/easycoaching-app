 <div class="card ">
	<div class="card-body">
		<div class="row">
			<div class="col-md-3 mb-2">
				<dt>Class Name</dt>
				<dd><?php echo $class['class_name']; ?></dd>
			</div>
			<div class="col-md-3 mb-2">
				<dt>Category</dt>
				<dd><?php if (isset ($class['title'])) echo $class['title']; else echo 'Uncategorized'; ?></dd>
			</div>
			<div class="col-md-3 mb-2">
				<dt>Participants</dt>
				<dd><?php echo $num_participants; ?></dd>
			</div>
		</div>
	</div>
</div>

<div class="row justify-content-center">

	<div class="col-md-12">
		<div class="card mb-2">
			<?php echo form_open ('coaching/virtual_class_actions/remove_participants/'.$coaching_id.'/'.$class_id, ['id'=>'validate-1']); ?>
				<table class="table">
					<thead>
						<tr>
							<th width="10"><input id="checkAll" type="checkbox" onchange="check_all()"></th>
							<th width="50%">User Name/ID</th>
							<th class="d-none d-sm-table-cell">Email</th>
							<th>Role</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (! empty ($participants)) {
						foreach ($participants as $user) {
							$full_name = $user['first_name'].' '.$user['last_name'];
							?>
							<tr>
								<td><input type="checkbox" name="users[]" value="<?php echo $user['member_id']; ?>" class="checks"></td>
								<td>
									<?php echo $user['first_name'].' '.$user['last_name']; ?><br>
									<?php echo $user['adm_no']; ?>									
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $user['email']; ?></td>
								<td>
									<?php 
									if ($user['role'] == VM_PARTICIPANT_MODERATOR) 
										echo 'Moderator'; 
									else 
										echo 'Attendee';
									?>

									<?php /*if ($user['member_id'] == $this->session->userdata ('member_id')) { ?>
										<a href="<?php echo site_url ('coaching/virtual_class/join_class/'.$coaching_id.'/'.$class_id.'/'.$user['member_id']); ?>" class='btn btn-primary mr-1' target="_blank"><i class="fa fa-plus"></i> Join</a>
									<?php } */ ?>
								</td>
							</tr>
							<?php
						}
					} else {
						?>
						<tr>
							<td colspan="4"><span class="text-danger">No users found</span></td>
						</tr>
						<?php
					}
					?> 
					</tbody>
				</table>

				<div class="card-footer">
					<input type="submit" name="" value="Remove Users" class="btn btn-danger">
					<?php echo anchor ('coaching/virtual_class/add_participants/'.$coaching_id.'/'.$class_id, 'Add Participants', ['class'=>'btn btn-link']); ?>
				</div>
			</form>
		</div>
	</div>	

</div>