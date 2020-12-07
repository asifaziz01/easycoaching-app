<div class="row justify-content-center">
  <div class="col-md-8">
	<div class="card">
		<div class="card-header p-3">
			<h4 class="card-title">Edit Plan: <?php echo $coaching['title']; ?></h4>
		</div>
		<div class="card-body">
			<?php echo form_open ('admin/coaching_actions/edit_plan/'.$coaching_id.'/'.$coaching['sp_id']); ?>
			<div class="form-group">
				<label class="form-label">Starting From</label>
				<?php
				if ($coaching['starting_from']) {
					$starting_from = date ('Y-m-d', $coaching['starting_from']);
				} else {
					$starting_from = date ('Y-m-d');
				}
				?>
				<input type="date" name="start_date" value="<?php echo $starting_from; ?>">
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
				<input type="date" name="end_date" value="<?php echo $ending_on; ?>">
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
		</div>
	</div>
  </div>
</div>