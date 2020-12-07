<script>
$('#show-password-link').on ('click', function () {
	const password = document.getElementById("reg-password");
	const password_text = document.getElementById("show-password-link");
	if (password.type === "password") {
	    password.type = "text";
	    password_text.text = 'Hide Password';
	} else {
	    password.type = "password";
	    password_text.text = 'Show Password';
	}
});
</script>