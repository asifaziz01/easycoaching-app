<div class="card bg-transparent">
	<div class="card-body p-0">
		<div class="row h-100 no-gutters justify-content-center">
			<div class="share-box col-sm-2 bg-white border-right px-2 py-2 py-md-4">
				<ul class="nav nav-pills border flex-column text-left" role="tablist">
					<li class="nav-item">
						<a class="nav-link rounded-0 active" id="my-files-tab" href="#my-files" data-toggle="tab" role="tab" aria-controls="my-files" aria-selected="true">My Files</a>
					</li>
					<li class="nav-item border-top">
						<a class="nav-link rounded-0" id="shared-files-tab" href="#shared-files" data-toggle="tab" role="tab" aria-controls="shared-files" aria-selected="false">Shared Files</a>
					</li>
				</ul>
			</div>
			<div class="col px-2 py-4 height-400 max-height-500 max-height-300-xs height-auto-xs overflow-auto">
				<div class="tab-content bg-transparent p-0">
					<div class="tab-pane my-files fade show active" id="my-files" role="tabpanel" aria-labelledby="my-files-tab">
						<div class="row no-gutters justify-content-center justify-content-sm-start">
							<?php if(!empty($results)): ?>
							<?php
							foreach($results as $i => $file):
								extract($file);
							?>
							<label class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2 px-md-1 mb-3 file" data-upload_on="<?php echo date("d-m-Y", $upload_on); ?>" data-filename="<?php echo $filename; ?>" data-file_id="<?php echo $id; ?>" data-path="<?php echo $path; ?>" data-view_file="<?php echo site_url('coaching/file_actions/view_file/'.$id)?>" data-size="<?php echo $size; ?>" data-icon="<?php echo $icon; ?>" data-share_count="<?php echo $share_count; ?>" for="<?php echo "file-$id"; ?>">
								<input type="checkbox" class="form-check-input invisible select-file" id="<?php echo "file-$id"; ?>" />
								<div class="card relative">
									<div class="dropdown">
										<button class="btn btn-sm bg-transparent text-reset mx-2 outline-none box-shadow-none absolute top right options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<div class="dropdown-menu dropdown-menu-right" style="min-width: auto">
											<a class="dropdown-item px-2" target="_blank" href="<?php echo site_url('coaching/file_actions/view_file/'.$id)?>"><i class="fa fa-eye"></i> View File</a>
											<a class="dropdown-item px-2" href="<?php echo site_url('coaching/file_actions/donwload_file/'.$id)?>"><i class="fa fa-download"></i> Download</a>
											<a class="dropdown-item px-2" href="<?php echo site_url('coaching/file_manager/share/'.$coaching_id.'/'.$member_id.'/'.$id)?>" target="_blank"><i class="fa fa-share-alt"></i> Share</a>
											<a class="dropdown-item px-2 rename-file" href="javascript:void(0);"><i class="far fa-keyboard"></i> Rename</a>
											<a class="dropdown-item px-2 delete-file" href="javascript:void(0);"><i class="fa fa-trash"></i> Delete</a>
										</div>
									</div>
									<div class="card-body text-center">
										<i class="<?php echo $icon; ?> fa-3x d-block"></i>
										<h6 id="<?php echo "file_id_$id"; ?>" class="filename"><?php echo $filename; ?></h6>
									</div>
								</div>
							</label>
							<?php endforeach; ?>
							<?php else: ?>
								<div class="col-md px-2 px-md-1 ">
									<div class="alert alert-danger" role="alert"><strong>Files not found!</strong> Your folder is empty.</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="tab-pane shared-files fade" id="shared-files" role="tabpanel" aria-labelledby="shared-files-tab">
						<div class="row no-gutters justify-content-center justify-content-md-start">
							<?php if(!empty($shared_files)): ?>
							<?php
							foreach($shared_files as $i => $shared_file):
								extract($shared_file);
							?>
							<label class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2 px-md-1 mb-3 file" data-upload_on="<?php echo date("d-m-Y", $upload_on); ?>" data-size="<?php echo $size; ?>" data-mime="<?php echo $mime_type; ?>" data-icon="<?php echo $icon; ?>" data-filename="<?php echo $filename; ?>" data-view_file="<?php echo site_url('coaching/file_actions/view_file/'.$id)?>" data-share_id="<?php echo $id; ?>" data-file_id="<?php echo $id; ?>" data-path="<?php echo $path; ?>" for="<?php echo "file-$id"; ?>" title="Shared On: <?php echo date("d-m-Y", $shared_on); ?>">
								<input type="checkbox" class="form-check-input invisible select-file" id="<?php echo "file-$id"; ?>" />
								<div class="card relative">
									<div class="dropdown">
										<button class="btn btn-sm bg-transparent text-reset mx-2 outline-none box-shadow-none absolute top right options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<div class="dropdown-menu dropdown-menu-right" style="min-width: auto">
											<a class="dropdown-item px-2" target="_blank" href="<?php echo site_url('coaching/file_actions/view_file/'.$id)?>"><i class="fa fa-eye"></i> View File</a>
											<a class="dropdown-item px-2" download href="<?php echo site_url('coaching/file_actions/donwload_file/'.$id)?>"><i class="fa fa-download"></i> Download</a>
										</div>
									</div>
									<div class="card-body text-center">
										<i class="<?php echo $icon; ?> fa-3x d-block"></i>
										<h6 id="<?php echo "file_id_$id"; ?>" class="filename"><?php echo $filename; ?></h6>
									</div>
								</div>
							</label>
							<?php endforeach; ?>
							<?php else: ?>
								<div class="col-md px-2 px-md-1 ">
									<div class="alert alert-danger" role="alert">No Files Shared with you!</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="info-box d-none d-sm-block col-sm-4 fade bg-white border-right px-2 py-2 py-md-4 overflow-auto">
				<div class="card h-100">
					<div class="card-body text-center">
						<div class="d-flex h-100 flex-column align-items-center justify-content-center">
							<h2 class="icon"></h2>
							<h3 class="filename"></h3>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col text-center align-self-center">
								<label class="mb-0" title="Size"><i class="fa fa-database"></i><span class="d-block size"></span></label>
							</div>
							<div class="col text-center align-self-center">
								<label class="mb-0" title="Uploaded On"><i class="fa fa-calendar-alt"></i><span class="d-block upload_on"></span></label>
							</div>
							<div class="col text-center d-none align-self-center">
								<label class="mb-0" title="Share Count"><i class="fa fa-share-alt"></i><span class="d-block share_count"></span></label>
							</div>
							<div class="col text-center align-self-center view_file"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer px-2">
		<div class="media">
			<div class="media-left my-auto">
				<label class="m-0"><?php echo $storage_label; ?></label>
			</div>
			<div class="media-body d-none d-sm-block my-auto">
				<div class="progress">
					<div class="progress-bar <?php echo $free_bar_color; ?>" style="width:<?php echo $free_percent; ?>%"><?php echo "$free_percent % free"; ?></div>
					<div class="progress-bar bg-info" style="width:<?php echo $storage_percent; ?>%"><?php echo ($storage_percent < 15 )?"":"$storage_percent % used"; ?></div>
				</div>
			</div>
			<div class="media-right ml-auto my-auto">
				<label class="m-0">Total Files: <?php echo $total_files;?></label>
			</div>
		</div>
	</div>
</div>