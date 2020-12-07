<script type="text/javascript">
$(function() {
	var selectCount = 0;
	$('.member-select').on('change', function() {
		if($(this).is(":checked" )){
			$(this).parents('.btn').removeClass('btn-light').addClass('btn-success');
			$(this).next().find('.fa-check').removeClass('invisible');
			selectCount++;
			$('.selected').text(selectCount);
		}else{
			$(this).parents('.btn').addClass('btn-light').removeClass('btn-success');
			$(this).next().find('.fa-check').addClass('invisible');
			selectCount--;
			$('.selected').text(selectCount);
		}
	});
	$("#share-form").on('reset', function(event) {
		$(this).find('.btn.btn-success.active').removeClass('btn-success active').addClass('btn-light');
		$(this).find('.fa-check').addClass('invisible');
		selectCount = 0
		$('.selected').text(selectCount);
	});
	$(".btn-reset").click(function(){
        $(this).parents('form').trigger("reset");
    });
    $(".btn-share").click(function(){
    	var action = $("#share-form").prop('action');
    	var formData = new FormData($("#share-form").get(0));
    	console.log(action, formData);
    	fetch (action, {
			method : 'POST',
			body: formData,
		}).then (function (response) {
			return response.json ();
		}).then(function(result) {
			console.log(result);
			if(result.status){
				toastr.success(result.message);
			}
		});
	});
});
</script>