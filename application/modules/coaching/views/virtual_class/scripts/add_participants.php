<script>

$(document).ready (function () {

	$('#search-role').on ('change', function () {
		var role_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/virtual_class/add_participants/'.$coaching_id.'/'.$class_id); ?>/'+role_id+'/<?php echo $batch_id; ?>';
		$(location).attr('href', url);
	});
	$('#search-batch').on ('change', function () {
		var batch_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/virtual_class/add_participants/'.$coaching_id.'/'.$class_id.'/'.$role_id); ?>/'+batch_id;
		$(location).attr('href', url);
	});
});

</script>