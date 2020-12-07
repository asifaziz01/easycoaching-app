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
});


var obtained_marks = []; 
var xlabels = [];

<?php
// Data for "Summary Report"
$i = 1;
if ( ! empty ($ob_marks) ) {
	foreach ($ob_marks as $attempt_id=>$data) {
		?> 
		obtained_marks.push(<?php echo $data['obtained']; ?>);
		xlabels.push("<?php echo date('d F, Y H:i a', $data['loggedon']); ?>");
		<?php 
		$i++;
		if ($i >= 10) break;
	}
} 

?>

var ctx = document.getElementById("briefChart").getContext('2d');
var briefChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: xlabels,
        datasets: [{
            data: obtained_marks,
        }]
    },
    options: {
        responsive: true,
		tooltips: {
			mode: 'index',
			intersect: false,
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Attempts'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Marks Obtained'
				},
				max: <?php echo $testMarks; ?>,
				min: 0
			}]
		}
    }
});
</script>