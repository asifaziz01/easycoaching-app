<script>
(function($){
	var waitTime = 60;
	var waiting = null;
	function updateWaitTime(){
		if(waitTime < 0){
			$('.resend-otp').removeClass('d-none');
			$('.resend-otp-wait').text('');
			clearInterval(waiting);
		}else{
			$('.resend-otp-wait').text(`Resed in ${waitTime} sec`);
			waitTime--;
		}
	}
	$('.resend-otp').click(function(event) {
		event.preventDefault ();
		const resendURL = event.target.href;
		console.log(resendURL);
		toastr.info ('Please wait...');
		$(this).addClass('d-none');
		waiting = setInterval(updateWaitTime, 1000);
		fetch (resendURL, { 
			method : 'GET',
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			toastr.clear ();
			if(result.max_attempt){
				clearInterval(waiting);
			}
			if (result.status == true) {
				toastr.success (result.message);
			} else {
				var message = result.error.replace('/[\n\r]/g', '');
				toastr.error (message);
			}
		});
	});
	$('.verify_otp').submit(function(event) {
		event.preventDefault ();
		const formURL = event.target.action;
		var formData = new FormData(event.target);
		toastr.info ('Please wait...');
		fetch (formURL, { 
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			toastr.clear ();
			if (result.status == true) {
				if (typeof(Storage) !== "undefined") {
				   localStorage.clear ();
				   localStorage.setItem('is_logged_in', result.is_logged_in );
				   localStorage.setItem('member_id', result.member_id );
				   localStorage.setItem('is_admin', result.is_admin );
				   localStorage.setItem('user_token', result.user_token );
				   localStorage.setItem('user_name', result.user_name );
				   localStorage.setItem('role_id', result.member_id );
				   localStorage.setItem('role_lvl', result.role_lvl );
				   localStorage.setItem('dashboard', result.dashboard );
				   localStorage.setItem('slug', result.slug );
				   localStorage.setItem('logo', result.logo );
				   localStorage.setItem('profile_image', result.profile_image );
				   localStorage.setItem('coaching_id', result.coaching_id );
				   localStorage.setItem('site_title', result.site_title );
				   localStorage.setItem('slug', result.slug );				    
				}
				else {
				  // Too bad, no localStorage for us
				}
				toastr.success (result.message);
				document.location = result.redirect;
			} else {
				var message = result.error.replace('/[\n\r]/g', '');
				toastr.error (message);
			}
		});
	});
	$('#otp-imput').pincodeInput({
		hidedigits:false,
		inputs:6,
		complete:function(value, e, errorElement){
			$('#otp-imput').val(value);
			$('.verify_otp').trigger('submit');
		}
	});
})(jQuery);
</script>