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

		<div class="row">
			<div class="col-md-3 mb-2">
				<select name="search_role" class="form-control" id="search-role">
					<option value="0">All Roles</option>
					<?php foreach ($roles as $role) { ?>
						<option value="<?php echo $role['role_id']; ?>" <?php if ($role_id ==$role['role_id']) echo 'selected="selected"'; ?> ><?php echo $role['description']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-3 mb-2">
				<select name="search_batch" class="form-control" id="search-batch">
					<option value="0">All Batches</option>
					<?php
					if (! empty ($batches)) {
						foreach ($batches as $batch) { 
						    ?>
							<option value="<?php echo $batch['batch_id']; ?>" <?php if ($batch['batch_id']==$batch_id) echo 'selected="selected"'; ?>><?php echo $batch['batch_name']; ?></option>
						    <?php
						}  
					}
					?>
				</select>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<?php echo form_open ('coaching/virtual_class_actions/add_participants/'.$coaching_id.'/'.$class_id, ['id'=>'validate-1']); ?>
				<table class="table">
					<thead>
						<tr>
							<th width="10">
								<input id="checkAll" type="checkbox" onchange="check_all()">
							</th>
							<th>User Name/ID</th>
							<th class="d-none d-sm-table-cell">Email</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$i = 0;
					if (! empty ($users)) {
						foreach ($users as $user) {
						  if ($this->virtual_class_model->participants_added ($coaching_id, $class_id, $user['member_id']) > 0) {
						  } else {
						  		$i++;
								$full_name = $user['first_name'].' '.$user['last_name'];
								$full_name = str_replace(' ', '+', $full_name);
								?>
								<tr>
									<td>
										<input type="checkbox" name="users[<?php echo $user['member_id']; ?>]" value="<?php echo $full_name; ?>" class="checks">
									</td>
									<td>
										<?php echo $user['first_name'].' '.$user['last_name']; ?><br>
										<?php echo $user['adm_no']; ?>
									</td>
									<td class="d-none d-sm-table-cell"><?php echo $user['email']; ?></td>
								</tr>
								<?php
							}
						}
					} 
					if ($i == 0) {
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
					Add as: 
					<select name="participant_role">
						<option value="<?php echo VM_PARTICIPANT_ATTENDEE; ?>">Attendee</option>
						<option value="<?php echo VM_PARTICIPANT_MODERATOR; ?>">Moderator</option>
					</select>
					<input type="submit" name="submit" value="Save" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>	

</div>