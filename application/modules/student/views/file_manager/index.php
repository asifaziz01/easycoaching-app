<div class="card bg-transparent">
	<div class="card-body p-0">
		<div class="row no-gutters bg-white px-2">
			<div class="share-box col-6 col-sm-9 py-2 py-md-4">
				<ul class="nav nav-pills border nav-fill text-left" role="tablist">
					<li class="nav-item">
						<a class="nav-link rounded-0 active" id="my-files-tab" href="#my-files" data-toggle="tab" role="tab" aria-controls="my-files" aria-selected="true"><i class="fa fa-folder"></i> <span class="label d-none d-sm-inline-block">My Files</span></a>
					</li>
					<li class="nav-item border-top">
						<a class="nav-link rounded-0" id="shared-files-tab" href="#shared-files" data-toggle="tab" role="tab" aria-controls="shared-files" aria-selected="false"><i class="fa fa-share-alt"></i> <span class="label d-none d-sm-inline-block">Shared Files</span></a>
					</li>
				</ul>
			</div>
			<div class="view-box align-self-center col text-right py-2 py-md-4">
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
				  <label class="btn btn-primary active">
				    <input type="radio" name="view" class="view grid" autocomplete="off" checked><i class="fas fa-th"></i>
				  </label>
				  <label class="btn btn-primary">
				    <input type="radio" name="view" class="view list" autocomplete="off"><i class="fas fa-list"></i>
				  </label>
				</div>
				<a href="<?php echo site_url($upload_url);?>" class="btn btn-success rounded-circle"><i class="fa fa-plus"></i></a>
			</div>
		</div>
		<div class="row no-gutters">
			<div class="col-12 py-4 relative">
				<div class="tab-content bg-transparent p-0">
					<div class="tab-pane my-files fade show active" id="my-files" role="tabpanel" aria-labelledby="my-files-tab">
						<div class="row no-gutters justify-content-start height-360 max-height-360 height-auto-xs max-height-300-xs overflow-auto">
							<?php if(!empty($results)): ?>
							<?php
							foreach($results as $i => $file):
								extract($file);
							?>
							<label class="col-4 col-sm-3 px-1 mb-3 file" data-upload_on="<?php echo date("d-m-Y", $upload_on); ?>" data-filename="<?php echo $filename; ?>" data-file_id="<?php echo $id; ?>" data-path="<?php echo $path; ?>" data-view_file="<?php echo site_url('student/file_actions/view_file/'.$id)?>" data-size="<?php echo $size; ?>" data-icon="<?php echo $icon; ?>" data-share_count="<?php echo $share_count; ?>" for="<?php echo "file-$id"; ?>">
								<input type="checkbox" class="form-check-input invisible select-file" id="<?php echo "file-$id"; ?>" />
								<div class="card h-100 relative">
									<div class="dropdown">
										<button class="btn btn-sm bg-transparent text-reset mx-2 outline-none box-shadow-none absolute top right options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<div class="dropdown-menu dropdown-menu-right" style="min-width: auto">
											<a class="dropdown-item px-2" target="_blank" href="<?php echo site_url('student/file_actions/view_file/'.$id)?>"><i class="fa fa-eye"></i> View File</a>
											<a class="dropdown-item px-2" href="<?php echo site_url('student/file_actions/donwload_file/'.$id)?>"><i class="fa fa-download"></i> Download</a>
											<a class="dropdown-item px-2" href="<?php echo site_url('student/file_manager/share/'.$coaching_id.'/'.$member_id.'/'.$id)?>"><i class="fa fa-share-alt"></i> Share</a>
											<a class="dropdown-item px-2 rename-file" href="javascript:void(0);"><i class="far fa-keyboard"></i> Rename</a>
											<a class="dropdown-item px-2 delete-file" href="javascript:void(0);"><i class="fa fa-trash"></i> Delete</a>
										</div>
									</div>
									<div class="card-body text-center">
										<div class="file-data d-flex h-100 flex-column justify-content-center align-items-center">
											<i class="<?php echo $icon; ?> fa-3x d-block"></i>
											<h6 id="<?php echo "file_id_$id"; ?>" class="filename text-break"><?php echo $filename; ?></h6>
										</div>
									</div>
								</div>
							</label>
							<?php endforeach; ?>
							<?php else: ?>
								<div class="col-md px-2 px-md-1 ">
									<div class="alert alert-info mb-5" role="alert">
										<p class="mb-0"><strong>Files not found!</strong> Your folder is empty. <strong>Click <i class="fa fa-plus"></i></strong> to upload files.</p>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="tab-pane shared-files fade" id="shared-files" role="tabpanel" aria-labelledby="shared-files-tab">
						<div class="row no-gutters justify-content-start height-360 max-height-360 height-auto-xs max-height-300-xs overflow-auto">
							<?php if(!empty($shared_files)): ?>
							<?php
							foreach($shared_files as $i => $shared_file):
								extract($shared_file);
							?>
							<label class="col-4 col-sm-3 px-1 mb-3 file" data-upload_on="<?php echo date("d-m-Y", $upload_on); ?>" data-size="<?php echo $size; ?>" data-mime="<?php echo $mime_type; ?>" data-icon="<?php echo $icon; ?>" data-filename="<?php echo $filename; ?>" data-view_file="<?php echo site_url('student/file_actions/view_file/'.$id)?>" data-share_id="<?php echo $id; ?>" data-file_id="<?php echo $id; ?>" data-path="<?php echo $path; ?>" for="<?php echo "file-$id"; ?>" title="Shared On: <?php echo date("d-m-Y", $shared_on); ?>">
								<input type="checkbox" class="form-check-input invisible select-file" id="<?php echo "file-$id"; ?>" />
								<div class="card h-100 relative">
									<div class="dropdown">
										<button class="btn btn-sm bg-transparent text-reset mx-2 outline-none box-shadow-none absolute top right options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fas fa-ellipsis-v"></i>
										</button>
										<div class="dropdown-menu dropdown-menu-right" style="min-width: auto">
											<a class="dropdown-item px-2" target="_blank" href="<?php echo site_url('student/file_actions/view_file/'.$id)?>"><i class="fa fa-eye"></i> View File</a>
											<a class="dropdown-item px-2" download href="<?php echo site_url('student/file_actions/donwload_file/'.$id)?>"><i class="fa fa-download"></i> Download</a>
										</div>
									</div>
									<div class="card-body text-center">
										<div class="file-data d-flex h-100 flex-column justify-content-center align-items-center">
											<i class="<?php echo $icon; ?> fa-3x d-block"></i>
											<h6 id="<?php echo "file_id_$id"; ?>" class="filename text-break"><?php echo $filename; ?></h6>
										</div>
									</div>
								</div>
							</label>
							<?php endforeach; ?>
							<?php else: ?>
								<div class="col-md px-2 px-md-1">
									<div class="alert alert-info mb-5" role="alert">No Files Shared with you!</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="info-box d-none col-12 fade bg-white border-right px-2 py-2 py-md-4 overflow-auto">
				<div class="card h-100">
					<div class="card-body text-center">
						<div class="d-flex h-100 flex-column align-items-center justify-content-center">
							<div class="icon"></div>
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
				<label class="m-0">Total Files: <span class="total_files"><?php echo $total_files;?></span></label>
			</div>
		</div>
	</div>
</div>