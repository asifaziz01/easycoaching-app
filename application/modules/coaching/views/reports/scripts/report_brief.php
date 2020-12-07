<script src="<?php echo base_url(THEME_PATH . 'assets/js/chart.bundle.min.js'); ?>"></script>
<script>
$(document).ready (function () {
	$('#attempts').on('change', function() {
		var attempt_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/reports/all_reports/'.$coaching_id); ?>/'+attempt_id+'/<?php echo $member_id.'/'.$test_id.'/'.$type; ?>';
		$(location).attr('href', url);
	});

	$('#report-type').on('change', function() {
		var type = $(this).val ();
		var url = '<?php echo site_url ('coaching/reports/all_reports/'.$coaching_id.'/'.$attempt_id.'/'.$member_id.'/'.$test_id); ?>/'+type;
		$(location).attr('href', url);
	});

	var ctx = document.getElementById("pieChart");
	var config = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					<?php echo $brief['correct']; ?>,
					<?php echo $brief['wrong']; ?>,
					<?php echo $brief['not_answered']; ?>
				],
				label: 'Brief Report',
				backgroundColor: [
						'#43c115',
						'#BD362F',
						'#e8f2ff',
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
				display: true,
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

});
</script>