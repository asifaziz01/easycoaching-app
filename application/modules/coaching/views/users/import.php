<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<?php echo form_open_multipart ('coaching/user_actions/import_from_csv/'.$coaching_id.'/'.$role_id, array ('class'=>'form-horizontal', 'id'=>'upload-user') ); ?>
				<div class="card-header bg-white">
					<h4 class="card-title">Import Users</h4> 
				</div>
                <div class="card-body">
                	<div class="form-group row">
                		<div class="col-md-3 mb-2">
							<select name="role" class="form-control" id="search-role">
								<option value="0">Select Role</option>
								<?php foreach ($roles as $role) { ?>
									<option value="<?php echo $role['role_id']; ?>" <?php if ($role_id ==$role['role_id']) echo 'selected="selected"'; ?> ><?php echo $role['description']; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="col-md-3 mb-2">
							<select name="batch" class="form-control" id="search-batch">
								<option value="0">Select Batch</option>
								<?php 
								if (! empty($batches)) {
								  foreach ($batches as $batch) { ?>
									<option value="<?php echo $batch['batch_id']; ?>" <?php if ($batch_id == $batch['batch_id']) echo 'selected="selected"'; ?>><?php echo $batch['batch_name']; ?></option>
								  <?php 
								  }      
								}  
								?>
							</select>
						</div>
                	</div>

					<div class="form-group ">
						<label class="control-label">Browse CSV File</label>
						<input type="file" name="userfile" size="20" class="form-control" />
						<p class="help-text">.csv files only</p>
					</div> 
					<p class="text-danger">Upload students list in given format. Email, First Name and Last Name should not be left blank.</p>
					<a href="<?php echo site_url ('coaching/users/download_file/users_sample')?>" class="btn btn-link">Download sample file</a> 
				</div>
				<div class="card-footer">
					<input type="submit" id="" class="btn btn-primary" value="Import ">
				</div>
			<?php echo form_close (); ?>
		</div>
	</div>
</div>