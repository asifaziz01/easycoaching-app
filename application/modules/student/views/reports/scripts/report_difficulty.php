<script src="<?php echo base_url(THEME_PATH . 'assets/js/chart.bundle.min.js'); ?>"></script>
<script>
$(document).ready (function () {
	$('#attempts').on('change', function() {
		var attempt_id = $(this).val ();
		var url = '<?php echo site_url ('student/reports/test_report/'.$coaching_id.'/'.$member_id); ?>/'+attempt_id+'/<?php echo $test_id.'/'.$type; ?>';
		$(location).attr('href', url);
	});

	$('#report-type').on('change', function() {
		var type = $(this).val ();
		var url = '<?php echo site_url ('student/reports/test_report/'.$coaching_id.'/'.$member_id.'/'.$attempt_id.'/'.$test_id); ?>/'+type;
		$(location).attr('href', url);
	});
});

<?php
$i			   = 1;	
$total  	   = 0;
if ( ! empty ($dif_response)) {
	foreach ($dif_response as $cat_title=>$data) {
		$right_answers = 0;
		$wrong_answers = 0;
		$not_answered  = 0;
		$answered	   = 0;
		if (! empty ($data)) {
			foreach ($data as $type=>$questions) {
				if ($type == TQ_CORRECT_ANSWERED) { 
					$right_answers = $right_answers + 1;
				} 
				if ($type == TQ_WRONG_ANSWERED) { 
					$wrong_answers = $wrong_answers + 1;
				} 
				if ($type == TQ_NOT_ANSWERED) { 
					$not_answered = $not_answered + 1;
				} 
				?>
				var ctx = document.getElementById('chart_pie<?php echo $i; ?>').getContext('2d');	
				var config = {
					type: 'pie',
					data: {
						datasets: [{
							data: [
								<?php echo $right_answers; ?>,
								<?php echo $wrong_answers; ?>,
								<?php echo $not_answered; ?>								
							],
							label: 'Brief Report',
							backgroundColor: [
									'#66BB6A',
									'#BD362F',
									'#42A5F5',
									]
						}],
						labels: [
							'Correct',
							'Wrong',
							'Not Answered',
						],
					},
					options: {
						responsive: true,
						legend: {
							display: false,
							position: 'top',
						},
						title: {
							display: false,
							text: 'Doughnut Chart'
						},
						animation: {
							animateScale: true,
							animateRotate: true
						}
					}
				};

				var pieChart = new Chart(ctx, config);		
				<?php
				$i++;
			}
		}
	}
}
?>
</script>