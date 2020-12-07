<script type="text/javascript">
	/* 
		update_session, logout_user defined in app.js
	*/

	const loaderSelector = document.getElementById('loader');
	loaderSelector.style.display = 'block';
	const access_code = '<?php echo $ac; ?>';
	
	var user_token = '';

		// Check browser support
	if (typeof(Storage) !== "undefined") {
		// Retrieve user token from local storage
	  	user_token = localStorage.getItem("user_token");	  	
	} else {
		// Local storage not supported, retrieve from cookie	  	
	  	user_token = getCookie ('user_token');
	}

	if (user_token == null || user_token == '') {
		// user token not found, redirect to login page after clean-up
  		logout_user (access_code);
  	} else {
		// user token found, validate token and login
  		update_session (user_token);
  	}
</script>