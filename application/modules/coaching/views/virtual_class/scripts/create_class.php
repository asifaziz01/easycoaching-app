<script type="text/javascript">

	$(document).ready (function () {
		$('#start_date_check').on ('change', function () {
			var start_date = $(this).val ();
			if ($(this).prop ('checked') == true ) {
				$('#start_date_text').prop('disabled', false);
				$('#start_time_hh').prop('disabled', false);
				$('#start_time_mm').prop('disabled', false);
			} else {
				$('#start_date_text').prop('disabled', true);
				$('#start_time_hh').prop('disabled', true);
				$('#start_time_mm').prop('disabled', true);
			}
		});

		$('#end_date_check').on ('change', function () {
			var start_date = $(this).val ();
			if ($(this).prop ('checked') == true ) {
				$('#end_date_text').prop('disabled', false);
				$('#end_time_hh').prop('disabled', false);
				$('#end_time_mm').prop('disabled', false);
			} else {
				$('#end_date_text').prop('disabled', true);
				$('#end_time_hh').prop('disabled', true);
				$('#end_time_mm').prop('disabled', true);
			}
		});
	});

	function create_meeting () {
		const meetingURL = document.getElementById ('meeting_url').value;
		fetch (meetingURL, {
			method: 'GET',
			mode: 'no-cors',
		}).then (function (response) {
			// console.log (response);
		})
	}
</script>