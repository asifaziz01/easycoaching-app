<script>
var obtained_marks = []; 
var xlabels = [];

<?php
// Data for "Summary Report"
$i = 1;
if ( ! empty ($ob_marks) ) {
	foreach ($ob_marks as $attempt_id=>$data) {
		?> 
		obtained_marks.push(<?php echo $data['obtained']; ?>);
		xlabels.push("<?php echo date('d M, Y h:i A', $data['loggedon']); ?>");
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
            fill: false,
			borderColor: "#43c115",
			backgroundColor: "#43c115",
			pointBackgroundColor: "#42a5f5",
			pointBorderColor: "#42a5f5",
			pointHoverBackgroundColor: "#42a5f5",
			pointHoverBorderColor: "#42a5f5",
   			label: 'Marks Obtained',
        }]
    },
    options: {
        responsive: true,
		title: {
			display: true,
			text: 'Summary Report'
		},
		tooltips: {
			//enabled: false,
			mode: 'index',
			intersect: false,
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		legend:{
			display: false,
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
				ticks: {
					min: 0,
					
					stepSize: 1,
              	}
			}]
		},
	    onResize: function(briefChart, size) {
	    	if(size.height < 200){
	    		briefChart.options.scales.xAxes[0].display = false;
	    	}else {
	    		briefChart.options.scales.xAxes[0].display = true;
	    	}
		}
    }
});
</script>