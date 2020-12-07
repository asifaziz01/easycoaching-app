 <div class="row justify-content-center">

  <div class="col-md-6">

  	<!-- Plan Details -->
	<div class="card mb-2">
		<div class="card-header">
			<h4 class="card-title">Coaching Details</h4>
		</div>
		<div class="card-body">
			<dl class="dl">
				<dt class="dt">Name:</dt>
				<dd class="dd"><?php echo $coaching['coaching_name']; ?></dd>
				<dt class="dt">Plan:</dt>
				<dd class="dd"><?php echo $coaching['title']; ?></dd>
				<dt class="dt">Joining Date:</dt>
				<dd class="dd"><?php echo date ('d M Y', $coaching['starting_from']); ?></dd>
				<dt class="dt">Ending On:</dt>
				<dd class="dd"><?php echo date ('d M Y', $coaching['ending_on']); ?></dd>
			</dl>
		</div>
	</div>

  	<!-- Plan Users -->
	<div class="card mb-2">
		<div class="card-body">
			<h4 class="card-title">Users</h4>
			<h3><?php echo count($users); ?>/<span><small><?php echo $coaching['max_users']; ?></small></span></h3>
		</div>

		<div class="card-body">
			<dl class="dl">
			<?php
			if (! empty ($admins)) {
				foreach ($admins as $row) {
					?>
					<dt class="dt">Name:</dt>
					<dd class="dd"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></dd>
					<dt class="dt">Mobile:</dt>
					<dd class="dd"><?php echo $row['primary_contact']; ?></dd>
					<dt class="dt">Email:</dt>
					<dd class="dd"><?php echo $row['email']; ?></dd>
					<?php
				}
 			}
 			?>
			</dl>
		</div>
	</div>  	

  </div>

  <div class="col-md-6">

	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Edit Plan</h4>
		</div>
		<div class="card-body">
			<?php echo form_open ('admin/coaching_actions/edit_plan/'.$coaching_id.'/'.$coaching['sp_id'], ['id'=>'validate-1']); ?>
			<div class="form-group">
				<label class="form-label">Starting From</label>
				<?php
				if ($coaching['starting_from']) {
					$starting_from = date ('Y-m-d', $coaching['starting_from']);
				} else {
					$starting_from = date ('Y-m-d');
				}
				?>
				<input type="date" name="start_date" value="<?php echo $starting_from; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label class="form-label">Ending On</label>
				<?php
				if ($coaching['ending_on']) {
					$ending_on = date ('Y-m-d', $coaching['ending_on']);
				} else {
					$ending_on = date ('Y-m-d');
				}
				?>
				<input type="date" name="end_date" value="<?php echo $ending_on; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label class="form-label">Status</label>
				<div class="custom-control custom-switch">
				  <input type="checkbox" name="status" class="custom-control-input" id="status" value="1" <?php if ($coaching['plan_status'] == 1 || $coaching['ending_on'] > time () ) echo 'checked';?> >
				  <label class="custom-control-label" for="status">Active </label>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<input type="submit" name="submit" value="Save" class="btn btn-primary">
			<a href="<?php echo site_url ('admin/coaching/browse_plans/'.$coaching_id.'/'.$coaching['sp_id']); ?>" class="btn btn-link" >Change Plan</a>
		</div>
	</div>

	<!-- Plan Users -->
	<div class="card border-danger mt-4">
		<div class="card-body">
			<h4 class="card-title">Delete Account</h4>
			<a onclick="show_confirm ('Delete this coaching account? This will delete all coaching users and tests also.', '<?php echo site_url ('admin/coaching_actions/delete_account/'.$coaching_id); ?>')" class="btn btn-danger">Delete Account</a>
		</div>
	</div>

  </div>
</div>