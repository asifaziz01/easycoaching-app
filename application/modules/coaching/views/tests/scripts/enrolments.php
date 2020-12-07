 <script>
$(document).ready (function () {
	$(".digits").bind("mousewheel", function (event,delta,nbr){});
	$('#search-status').on ('change', function () {
		var status = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type.'/'.$role_id.'/'.$class_id.'/'.$batch_id); ?>/'+status;
		$(location).attr('href', url);
	});

	$('#search-role').on ('change', function () {
		var role_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type); ?>/'+role_id+'/<?php echo $class_id.'/'.$batch_id.'/'.$status; ?>';
		$(location).attr('href', url);
	});
	$('#search-batch').on ('change', function () {
		var batch_id = $(this).val ();
		var url = '<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$type.'/'.$role_id.'/'.$class_id); ?>/'+batch_id+'/<?php echo $status; ?>';
		$(location).attr('href', url);
	});

	function toggleEnrolmentSetting(){
		if ($('.check:checked').length) {
			$('#set_enrolment').removeAttr('disabled');
		} else {
			$('#set_enrolment').attr('disabled', true);
		}
	}

	$("#check-all").click (function() {
		$('.check').not(this).prop('checked', this.checked);
		toggleEnrolmentSetting();
	});

	$(".check").click(function(){
		toggleEnrolmentSetting();
	});
});

</script>

<script>
	const loaderSelector = document.getElementById('loader');
	const formSelector = document.getElementById('search-form');
	const formURL = formSelector.getAttribute ('action');
	const outputSelector = document.getElementById ('users-list');
	formSelector.addEventListener ('submit', e => {
		e.preventDefault (); 
		/*		
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
				var obj =  result.data;
				var i = 1;
				var output = '<table class="table">';
					output += '<thead>';
						output += '<tr>';
							output += '<th width="5%">';
								output += '<input id="checkAll" type="checkbox" >';
							output += '</th>';
							output += '<th width="25%">Name</th>';
							output += '<th width="">Email</th>';
							output += '<th width="">Role</th>';
							output += '<th width="">Status</th>';
							output += '<th width="">Actions</th>';
						output += '</tr>';
					output += '</thead>';
					output += '<tbody>';
					for (var item in obj) {
						output += '<tr>';
							output += '<td>';
								output += '<input type="checkbox" value="'+obj[item].member_id+'" class="checks">';
							output += '</td>';
							output += '<td>';
								var name = obj[item].first_name+' '+obj[item].last_name;
								output += '<a href="<?php echo site_url('users/admin/create'); ?>/'+obj[item].class_id+'/'+obj[item].role_id+'/'+obj[item].member_id+'">'+name+'</a><br>';
								output += obj[item].adm_no;
							output += '</td>';
							output += '<td>';
								output += obj[item].email;
							output += '</td>';
							output += '<td>';
								output += obj[item].role_id;
							output += '</td>';
							output += '<td>';
								if (obj[item].status == 1) {
									output += '<span class="badge badge-primary">Active</span>';
								} else {
									output += '<span class="badge badge-light">Inactive</span>';
								}
							output += '</td>';
							output += '<td>';
							output += '</td>';
						output += '</tr>';
						i++;
					}
					output += '<tbody>';
				output += '</table>';
				
				outputSelector.innerHTML = output;
			}
		});*/
	});
</script>