<div class="card mb-3"> 
	<div class="card-body">
		<div class="form-group mb-0 row">
			<div class="col-md-6">
				<strong>Attempts</strong>
				<div class="input-group">
					<select title="Select Attempt" class="form-control custom-select selectpicker" id="attempts">
						<?php foreach ($attempts as $attempt) { ?>
							<option value="<?php echo $attempt['id']; ?>" <?php if ($attempt_id == $attempt['id']) echo 'selected="selected"'; ?> ><?php echo date('d F, Y h:i A', $attempt['loggedon']); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<strong>Report Type</strong>
				<select title="Report Type" class="form-control custom-select selectpicker" id="report-type">
					<?php foreach ($reports as $t=>$report) { ?>
						<option value="<?php echo $t; ?>" <?php if ($type == $t) echo 'selected="selected"'; ?> ><?php echo $report['title']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>

<?php
	if (isset($attempt_id) && $attempt_id > 0) {
		$this->load->view('reports/'.$reports[$type]['report_file']);
	} 
?>