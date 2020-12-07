<script>

$(document).ready (function () {

	const interval = 1000 * 60;
	//const outputSelector = document.getElementById ('');
	const fetchURL = '<?php echo site_url ('student/virtual_class/is_meeting_running/'.$coaching_id.'/'.$class_id.'/'.$member_id); ?>';
	setInterval(get_running_meetings, interval);

	function get_running_meetings () {
		fetch (fetchURL, {
			method: 'GET',
			mode: 'no-cors',
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {
				document.location = result.redirect;
			}
		});
	}

});

</script>