<script type="text/javascript">
$(function() {
	function bytesToSize(bytes) {
	   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	   if (bytes == 0) return '0 Byte';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
	var total_files = <?php echo $total_files;?>;
	$('.file').on( "contextmenu", function(event){
		event.preventDefault();
		$(this).find('.options').trigger('click');
	});
	$('.delete-file').click(function(event) {
		var file = $(this).parents('.file');
		var file_id = file.data('file_id');
		var file_path = file.data('path');
		var filename = file.data('filename');
		var file_path = `${file_path+filename}`;
		var confirm = window.confirm("This will permanentaly delete your file. Are you sure?");
		if(confirm){
			console.log(file_id, file_path);
			$.ajax ({ 
				type: 'POST',
				data: {
					'file_id': file_id,
					'file_path': file_path
				},
				url: `<?php echo site_url('student/file_actions/delete_file/'); ?>`,
				beforeSend: function(){
				},
				complete: function(){
				},
				success: function(response) {
					console.log(response);
					if(response.status){
						file.remove();
						$('.total_files').html(total_files--);
						toastr.success(response.message);
					}
				}
			});
		}
	});
	$('.rename-file').click(function(event) {
		var file_id = $(this).parents('.file').data('file_id');
		var file_path = $(this).parents('.file').data('path');
		var old_file = $(this).parents('.file').data('filename');
		var old_file_name = old_file.split(".")[0];
		var file_ext = old_file.split(".").pop();
		var new_file_name = window.prompt(`Please enter new file name for ${old_file}`, old_file_name);
		if (new_file_name !== null) {
			var old_file_path = `${file_path+old_file}`;
			var new_file_path = `${file_path+new_file_name}.${file_ext}`;
			var new_fileName = `${new_file_name}.${file_ext}`;
		    $.ajax ({ 
				type: 'POST',
				data: {
					'file_id': file_id,
					'old_path': old_file_path,
					'new_path': new_file_path,
					'new_file_name': new_fileName
				},
				url: `<?php echo site_url('student/file_actions/rename_file/'); ?>`,
				beforeSend: function(){
				},
				complete: function(){
				},
				success: function(response) {
					console.log(response);
					if(response.status){
						$(`#file_id_${file_id}`).html(new_fileName);
						toastr.success(response.message);
					}
				}
			});
		}
	});
	$('.select-file').on('change', function(event) {
		var thisChecked = $(this).is(":checked" );
		var fileData = $(this).parents('.file').data();
		$('.select-file').prop('checked', false);
		$('.card').removeClass('shadow bg-dark text-white');
		$('.info-box').removeClass('in').addClass('d-none');
		if(thisChecked){
			$(this).prop('checked', thisChecked);
			$(this).next('.card').addClass('shadow bg-dark text-white');
			$('.info-box').removeClass('d-none').addClass('in');
			if($(this).parents('.tab-pane').hasClass('my-files')){
				$('.info-box').find('.share_count').parents('.col').removeClass('d-none');
			}else if($(this).parents('.tab-pane').hasClass('shared-files')){
				$('.info-box').find('.share_count').parents('.col').addClass('d-none');
			}
			Object.keys(fileData).forEach(function(selector){
				var mySelector = $('.info-box').find(`.${selector}`);
				mySelector.html('');
				if(selector=='icon'){
					if(fileData[selector]=='far fa-file-image'){
						$('<img/>', {"class":'width-100 img-fluid', 'src':fileData['view_file']}).appendTo(mySelector);
					}else{
						$('<i/>', {"class": fileData[selector]+' fa-4x d-block'}).appendTo(mySelector);
					}
				}else if(selector=='size'){
					$('.info-box').find(`.${selector}`).html(bytesToSize(fileData[selector]));
				}else if(selector=='view_file'){
					var view_link = $('<a/>', {'class': 'btn btn-success', 'target':'_blank','href': fileData[selector], 'html': 'View '}).appendTo(mySelector);
					var link_icon = $('<i/>', {'class': 'fa fa-eye'}).appendTo(view_link);
				}else{
					$('.info-box').find(`.${selector}`).html(fileData[selector]);
				}
			});
		}
	});
	$('.view').on('change', function(event) {
		if($(this).hasClass('grid')){
			console.log('here');
			$('.file').addClass('col-4 col-sm-3 grid').removeClass('col-12 list');
			$('.file-data').addClass('flex-column justify-content-center').removeClass('justify-content-start');
			$('.filename').removeClass('ml-4 mb-0s');
		}else if($(this).hasClass('list')){
			console.log('there');
			$('.file').removeClass('col-4 col-sm-3 grid').addClass('col-12 list');
			$('.file-data').removeClass('flex-column justify-content-center').addClass('justify-content-start') ;
			$('.filename').addClass('ml-4 mb-0s');
		}
	});
});
</script>