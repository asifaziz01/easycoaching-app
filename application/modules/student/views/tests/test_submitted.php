<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card border-success">
			<div class="card-body text-dark">
				<h4>Test Submitted</h4>
				<div class=" ">
					<strong>Your Test Has Been Submitted Successfully!</strong> 
					<p>Click the below button to see your result.</p>
				</div>
				<p class="lead">
					Your score in this attempt: <span class=""><?php echo $score.'/'.$test_marks; ?></span>
				</p>
			</div>
			<div class="card-footer d-flex justify-content-between">
				<a href="<?php echo base_url('student/reports/test_report/'.$coaching_id.'/'.$member_id.'/'.$attempt_id.'/'.$test_id.'/'.OVERALL_REPORT);?>" class="btn btn-sm btn-danger"> See Full Report</a>
				<a href="<?php echo base_url('student/tests/take_test/'.$coaching_id.'/'.$member_id.'/'.$test_id);?>" class="btn btn-sm btn-primary"> Try Again</a>
			</div> <!-- // widget-content -->
		</div> <!-- // widget -->
	</div>
</div>