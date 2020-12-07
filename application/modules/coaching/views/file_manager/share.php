
<?php echo form_open ('coaching/file_actions/share_file/'.$coaching_id.'/'.$member_id.'/'.$file_id, array('id'=>'share-form', 'class'=>'card bg-transparent')); ?>
<form class="">
	<div class="card-header text-center">
		<h3 class="card-title mb-0">File Share</h3>
	</div>
	<div class="card-body p-0 height-400 max-height-500 height-300-xs overflow-auto">
		<div class="row h-100 no-gutters justify-content-center">
			<div class="file-info col-6 bg-white border-right px-2 py-4">
				<?php extract($fileinfo); ?>
				<div class="card h-100">
					<div class="card-body text-center">
						<div class="d-flex h-100 flex-column align-items-center justify-content-center">
							<h2><i class="<?php echo $icon; ?> fa-7x d-block"></i></h2>
							<h3 id="<?php echo "file_id_$id"; ?>" class="filename"><?php echo $filename; ?></h3>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col text-center align-self-center">
								<label class="mb-0" title="Size"><i class="fa fa-database"></i><span class="d-block"><?php echo $filesize; ?></span></label>
							</div>
							<div class="col text-center align-self-center">
								<label class="mb-0" title="Uploaded On"><i class="fa fa-calendar-alt"></i><span class="d-block"><?php echo date("d-m-Y", $upload_on); ?></span></label>
							</div>
							<div class="col text-center align-self-center">
								<label class="mb-0" title="Share Count"><i class="fa fa-share-alt"></i><span class="d-block"><?php echo $share_count; ?></span></label>
							</div>
							<div class="col text-center align-self-center">
								<a class="btn btn-success" download href="<?php echo site_url('coaching/file_actions/donwload_file/'.$id)?>">Download <i class="fa fa-download"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="share-box col-6 max-height-400 overflow-auto border-right px-2 py-4">
				<div class="btn-group-vertical btn-group-toggle d-flex" data-toggle="buttons">
				<?php
				foreach($users as $i => $user):
					if($member_id != $user['member_id'] && !in_array($user['member_id'], $shared_users)):
					?>
					<label class="btn outline-none text-left btn-light border rounded-0" for="<?php echo "member-".$user['member_id']; ?>">
						<input type="checkbox" class="member-select" value="<?php echo $user['member_id']; ?>" id="<?php echo "member-".$user['member_id']; ?>" name="members[]">
						<div class="media m-0">
							<div class="media-left">
								<i class="fa fa-check invisible"></i>
							</div>
							<div class="media-body text-center">
								<span><?php echo $user['first_name']." ".$user['last_name']; ?></span>
							</div>
							<div class="media-right">
								<span class=""><?php echo $user['adm_no']; ?></span>
							</div>
						</div>
					</label>
				<?php
					endif;
				endforeach;
				?>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer px-2">
		<div class="media">
			<div class="media-left my-auto">
				<button class="btn btn-danger" type="button"><i class="fa fa-times"></i> Cancel</button>
				<button class="btn btn-warning btn-reset" type="button"><i class="fa fa-recycle"></i> Reset</button>
			</div>
			<div class="media-body my-auto">
			</div>
			<div class="media-right my-auto">
				<button class="btn btn-primary btn-share" type="button"><i class="fa fa-share-alt"></i> Share Selected</button>
				<label class="m-0">Total Selected: <span class="selected">0</span></label>
			</div>
		</div>
	</div>
<?php echo form_close(); ?>