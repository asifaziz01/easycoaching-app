<div class="card"> 
	<div class="card-body">
		<canvas id="briefChart" width="100%" ></canvas>
	</div>
	<div class="card-footer">
		<h4 class="text-center">Score</h4>
		<div class="d-flex justify-content-between text-center">
			<p class="nav-link mb-0">
				<span class="badge bg-grey-200 rounded-circle height-50 width-50 d-flex align-items-center justify-content-center mx-auto "> <?php echo $testMarks; ?></span>
				<span class="display mt-4">Test Marks</span>
			</p>
			<p class="nav-link mb-0">
				<span class="badge bg-primary rounded-circle height-50 width-50 d-flex align-items-center justify-content-center mx-auto"> <?php echo $ob_marks[$attempt_id]['obtained']; ?></span>
				<span class="display mt-4">Current Attempt</span>
			</p>
			<p class="nav-link mb-0">
				<span class="badge bg-grey-200 rounded-circle height-50 width-50 d-flex align-items-center justify-content-center mx-auto"> <?php echo $max_marks; ?></span>
				<span class="display mt-4">Max Score </span>
			</p>
		</div>
	</div>
</div>
