<script>
	const loaderSelector = document.getElementById('loader');
	const formSelector = document.getElementById('login-form');
	const errorDiv = document.getElementById('error');
	
	formSelector.addEventListener ('submit', e => {
		e.preventDefault ();
		var idbSupported = false;
		var db;
		const dbName = 'itsc';
		const dbVersion = 1;
		const formURL = formSelector.getAttribute ('action');
		var formData = new FormData(formSelector);
		loaderSelector.style.display = 'block';		
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
				toastr.error (result.error);
			}
			loaderSelector.style.display = 'none';
		});
	});	

</script>