<script>
(($) => {
	$(document).ready(() => {
		$('.send-notification').click((event) => {
			var thisButton = event.target;
			var notifytitle = $(thisButton).parents('.announcement').data('notifytitle');
			var notifycontent = $(thisButton).parents('.announcement').data('notifycontent');
			var notifylink = $(thisButton).parents('.announcement').data('notifylink');
			fetch(`${appPath}notification/action/send_notification/`,{
			    method: 'POST',
			    headers:{
			        'Content-Type':'application/json'
			    },
			    body: JSON.stringify({
			        'title': notifytitle,
					'content': notifycontent,
					'link': notifylink
			    })
			}).then((response) => {
			    response.json().then((result) => {
			        console.log(result);
			        if(result.status){
			        	// ' notification sent successfully'. ' notification not able to sent.'
			        	if(result.success){
			        		toastr.success (`${result.success}/${result.total} notification sent successfully`);
			        	}
			        	if(result.failed){
			        		toastr.error (`${result.failed}/${result.total} notification were failed sending.`);
			        	}
			        }
			    });
			})
		});
	});
})(jQuery);
</script>