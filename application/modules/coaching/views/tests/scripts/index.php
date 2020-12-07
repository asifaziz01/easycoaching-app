<script>
$(document).ready (function () {

	$('#search-status').on ('change', function () {
		var status = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/index/'.$coaching_id.'/'.$category_id); ?>/'+status;
		$(location).attr('href', url);
	});

	$('#search-type').on ('change', function () {
		var type = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/index/'.$coaching_id.'/'.$category_id.'/'.$status); ?>/'+type;
		$(location).attr('href', url);
	});

	$('#search-category').on ('change', function () {
		var cat_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/index/'.$coaching_id); ?>/'+cat_id+'/<?php echo $status; ?>';
		$(location).attr ('href', url);
	});
});
</script>

<script>
	const loaderSelector = document.getElementById ('loader');
	const formSelector = document.getElementById ('search-form');
	const formURL = formSelector.getAttribute ('action');
	const outputSelector = document.getElementById ('test-list');
	
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
				/*
				var i = 1;
				var output = '<table class="table">';
				for (var item in obj) {
					output += '<tr>';
						output += '<td>';
							output += i;
						output += '</td>';
						output += '<td>';
							output += '<a href="<?php echo site_url('coaching/tests/manage'); ?>/'+obj[item].test_id+'">'+obj[item].title+'</a><br>';
							output += obj[item].unique_test_id;
						output += '</td>';
						output += '<td>';
							output += obj[item].time_min + ' mins';
						output += '</td>';
						output += '<td>';
							if (obj[item].finalized == 1) {
								output += '<span class="badge badge-primary">Published</span>';
							} else {
								output += '<span class="badge badge-light">Upbublished</span>';
							}
						output += '</td>';
					output += '</tr>';
					i++;
				}
				output += '</table>';
				/*
				/*
				document.getElementById('count-tests').innerHTML = (i-1);
				document.getElementById('pagination').innerHTML = '';
				*/
				outputSelector.innerHTML = output;
			}
		});
	});

</script>