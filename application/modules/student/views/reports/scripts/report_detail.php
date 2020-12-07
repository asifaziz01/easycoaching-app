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

</script>