<script>

$(document).ready (function () {

	$('#category').on ('change', function () {
		var category = $(this).val ();
		var url = '<?php echo site_url ('coaching/virtual_class/index/'.$coaching_id); ?>/'+category;
		$(location).attr('href', url);
	});

	const interval = 1000 * 60;
	const outputSelector = document.getElementById ('');
	const fetchURL = '<?php echo site_url ('coaching/virtual_class_actions/get_running_meetings/'.$coaching_id); ?>';
	setInterval(get_running_meetings, interval);

	function get_running_meetings () {
		fetch (fetchURL, {
			method: 'GET',
			mode: 'no-cors',
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {

			}
		});

	}

});

</script>