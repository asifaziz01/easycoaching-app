<div class="card mb-2"> 
	<div class="card-header">
		<h4>Datewise Report</h4>
	</div>
	<div class="card-body ">
		<div class="form-group row mb-2">

			<div class="col-md-6 mb-2">
				<label>From</label>
				<input type="date" id="from-date" value="<?php echo $from_dt; ?>" class="form-control"  > 
			</div>
			<div class="col-md-6 mb-2">
				<label>To</label>
				<input type="date" id="to-date" value="<?php echo $to_dt; ?>" class="form-control"  > 
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-md-8">
				<canvas id="chart_pie" width=""></canvas>
			</div>
		</div>
	</div>	
</div>

<div class="card mb-2">
	<div class="card-header">
		<h4 class="">Month-wise Report</h4>
	</div>
	<div class="card-bodys">
		<?php echo $this->calendar->generate(); ?>
	</div>

	<div class="card-footer">
		<ul class="nav nav-pills nav-fill">
			<li class="nav-item">
				<a class="nav-link ">
					<span class="badge bg-success rounded-circle height-30 width-30 d-flex align-items-center justify-content-center mx-auto"> <?php echo $num_present; ?></span>
					<h4 class="display mt-4">Present</h4>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link ">
					<span class="badge bg-danger rounded-circle height-30 width-30 d-flex align-items-center justify-content-center mx-auto"> <?php echo $num_absent; ?></span>
					<h4 class="display mt-4">Absent</h4>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link ">
					<span class="badge bg-info rounded-circle height-30 width-30 d-flex align-items-center justify-content-center mx-auto"> <?php echo $num_leave; ?></span>
					<h4 class="display mt-4">Leave</h4>
				</a>
			</li>
		</ul>
	</div>
</div>
