<script type="text/javascript">
	const loaderSelector = document.getElementById ('loader');
	const formSelector = document.getElementById ('search-form');
	const formURL = formSelector.getAttribute ('action');
	const outputSelector = document.getElementById ('coaching-list');
	
	formSelector.addEventListener ('submit', e => {
		e.preventDefault ();
		var formData = new FormData(formSelector);
		loaderSelector.style.display = 'block';
		
		fetch (formURL, {
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			if (result.status == true) {
				loaderSelector.style.display = 'none';
				var output =  result.data;
				outputSelector.innerHTML = output;
			}
		});
	});

</script>