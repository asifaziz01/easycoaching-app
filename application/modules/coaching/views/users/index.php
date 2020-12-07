<div class="card mb-2"> 
	<div class="card-body ">
		<strong>Search</strong>
		<?php echo form_open('coaching/user_actions/search_users/'.$coaching_id.'/'.$role_id.'/'.$status.'/'.$batch_id, array('class'=>"", 'id'=>'search-form')); ?>
			<div class="form-group row mb-2">
				<div class="col-md-3 mb-2">
					<select name="search_status" class="form-control" id="search-status" >
						<option value="-1">All Status</option>
						<option value="<?php echo USER_STATUS_DISABLED; ?>" <?php if ($status==USER_STATUS_DISABLED) echo 'selected="selected"'; ?> >Disabled</option>
						<option value="<?php echo USER_STATUS_ENABLED; ?>" <?php if ($status==USER_STATUS_ENABLED) echo 'selected="selected"'; ?> >Enabled</option>
						<option value="<?php echo USER_STATUS_UNCONFIRMED; ?>" <?php if ($status==USER_STATUS_UNCONFIRMED) echo 'selected="selected"'; ?> >Pending</option>
					</select>
				</div>

				<div class="col-md-3 mb-2">
					<select name="search_role" class="form-control" id="search-role">
						<option value="0">All Roles</option>
						<?php foreach ($roles as $role) { ?>
							<option value="<?php echo $role['role_id']; ?>" <?php if ($role_id ==$role['role_id']) echo 'selected="selected"'; ?> ><?php echo $role['description']; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3 mb-2">
					<select name="search_batch" class="form-control" id="search-batch">
						<option value="0">All Batches</option>
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

				<div class="col-md-3">
					<select name="filter_sort" class="form-control" id="filter-sort" >
						<option value="<?php echo SORT_ALPHA_ASC; ?>" <?php if ($sort==SORT_ALPHA_ASC) echo 'selected="selected"'; ?> >Name: A to Z</option>
						<option value="<?php echo SORT_ALPHA_DESC; ?>" <?php if ($sort==SORT_ALPHA_DESC) echo 'selected="selected"'; ?> >Name: Z to A</option>
						<option value="<?php echo SORT_CREATION_ASC; ?>" <?php if ($sort==SORT_CREATION_ASC) echo 'selected="selected"'; ?> >Old to New</option>
						<option value="<?php echo SORT_CREATION_DESC; ?>" <?php if ($sort==SORT_CREATION_DESC) echo 'selected="selected"'; ?> >New to Old</option>
					</select>
				</div>
			</div>

			<div class="form-group row mb-2">
				<div class="col-md-6 mb-2">
					<div class="input-group ">
						<input name="search_text" class="form-control " type="search" placeholder="Search by name, mobile number, user-id" aria-label="Search Test" aria-describedby="search-button">
						<div class="input-group-append">
							<button class="btn btn-sm btn-primary " type="submit" id="search-button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div>
</div>

<?php echo form_open('coaching/user_actions/confirm/'.$coaching_id.'/'.$role_id.'/'.$status, array('class'=>'form-horizontal row-border', 'id'=>'validate-1') ); ?>
	<div id="users-list">
		<?php $this->load->view ('users/inc/index', $data); ?>
	</div>
</form>