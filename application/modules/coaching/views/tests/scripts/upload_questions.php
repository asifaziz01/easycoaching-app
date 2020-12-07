<script>
	const loaderSelector = document.getElementById('loader');
	
	const textFormSelector = document.getElementById('upload-from-text');
	const textFormURL = textFormSelector.getAttribute ('action');

	const csvFormSelector = document.getElementById('upload-from-csv');
	const csvFormURL = csvFormSelector.getAttribute ('action');

	const wordFormSelector = document.getElementById('upload-from-word');
	const wordFormURL = wordFormSelector.getAttribute ('action');

	textFormSelector.addEventListener ('submit', e => {
		e.preventDefault (); 
		var formData = new FormData(textFormSelector);
		loaderSelector.style.display = 'block';
		fetch (textFormURL, {
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {
			    toastr.success (result.message);
			    document.location = result.redirect;
			} else {
			    toastr.error (result.error);
			}
		loaderSelector.style.display = 'none';
		});
	});
	
	csvFormSelector.addEventListener ('submit', e => {
		e.preventDefault (); 
		var formData = new FormData(csvFormSelector);
		loaderSelector.style.display = 'block';
		fetch (csvFormURL, {
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {
			    toastr.success (result.message);
			    document.location = result.redirect;
			} else {
			    toastr.error (result.error);
			}
		loaderSelector.style.display = 'none';
		});
	});
	
	wordFormSelector.addEventListener ('submit', e => {
		e.preventDefault (); 
		var formData = new FormData(wordFormSelector);
		loaderSelector.style.display = 'block';
		fetch (wordFormURL, {
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {
			    toastr.success (result.message);
			    document.location = result.redirect;
			} else {
			    toastr.error (result.error);
			}
		loaderSelector.style.display = 'none';
		});
	});
</script>