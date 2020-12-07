<script type="text/javascript">
	$('#from-date').on ('change', function () {
		var from_date = $(this).val ();
		var to_date = $('#to-date').val ();
		var url = '<?php echo site_url ('student/attendance/my_attendance/'.$coaching_id.'/'.$member_id); ?>/'+from_date+'/'+to_date;
		$(location).attr ('href', url);
	});

	$('#to-date').on ('change', function () {
		var to_date = $(this).val ();
		var from_date = $('#from-date').val ();
		var url = '<?php echo site_url ('student/attendance/my_attendance/'.$coaching_id.'/'.$member_id); ?>/'+from_date+'/'+to_date;
		$(location).attr ('href', url);
	});
</script>

<script type="text/javascript">
	var ctx = document.getElementById('chart_pie').getContext('2d');	
	var config = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					<?php echo $num_present; ?>,
					<?php echo $num_absent; ?>,
					<?php echo $num_leave; ?>								
				],
				label: 'Date-wise Report',
				backgroundColor: [
						'#00C759',
						'#FF0033',
						'#288CFF',
						]
			}],
			labels: [
				'Present',
				'Absent',
				'Leave',
			],
		},
		options: {
			responsive: true,
			legend: {
				display: true,
				position: 'top',
			},
			animation: {
				animateScale: true,
				animateRotate: true
			}
		}
	};

	var pieChart = new Chart(ctx, config);		

</script>