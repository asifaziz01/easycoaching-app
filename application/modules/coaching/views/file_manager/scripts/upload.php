<script src="<?php echo base_url (THEME_PATH . 'assets/js/dropzone.min.js'); ?>"></script>
<script type="text/javascript">
Dropzone.autoDiscover = false;
$(function() {
	$("#my-dropzone").dropzone({
		url: "<?php echo site_url('coaching/file_actions/do_file_upload/'.$coaching_id.'/'.$member_id); ?>",
		success: function(file, response){
			console.log(response);
			if(response.status){
				toastr.success(response.message);
			}
		}
	});
});
</script>