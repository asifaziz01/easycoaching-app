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
</script>