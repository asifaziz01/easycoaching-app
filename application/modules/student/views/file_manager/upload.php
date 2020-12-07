<div class="row justify-content-center"> 
	<div class="col">
		<?php echo form_open ('student/file_actions/do_file_upload/'.$coaching_id.'/'.$member_id, array('id'=>'my-dropzone', 'class'=>'dropzone dz-clickable')); ?>
			<div class="dz-message d-flex flex-column">
            	<i class="fa fa-upload"></i>
            	Drag &amp; Drop here or click
            </div>
		<?php echo form_close(); ?>
	</div>
</div>