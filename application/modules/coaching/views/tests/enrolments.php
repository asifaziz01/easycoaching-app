<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs" id="users" role="tablist">
		  <li class="nav-item">
			<a class="nav-link <?php if ($type==ENROLED_IN_TEST) echo 'active'; ?>" href="<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.ENROLED_IN_TEST.'/'.$role_id.'/'.$class_id.'/'.$batch_id ) ?>" >Enroled <span class="badge badge-primary"><?php echo $num_enroled; ?></span></a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link <?php if ($type==NOT_ENROLED_IN_TEST) echo 'active'; ?>" href="<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.NOT_ENROLED_IN_TEST.'/'.$role_id.'/'.$class_id.'/'.$batch_id ) ?>" >Not Enroled <span class="badge badge-primary"><?php echo $num_not_enroled; ?></span></a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link <?php if ($type==ARCHIVED_IN_TEST) echo 'active'; ?>" href="<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.ARCHIVED_IN_TEST.'/'.$role_id.'/'.$class_id.'/'.$batch_id ) ?>" >Archived <span class="badge badge-primary"><?php echo $num_archived; ?></span></a>
		  </li>
		</ul>
	</div>
</div>

<div class="card mb-2"> 
	<div class="card-body ">
		<strong>Search</strong>
		<?php echo form_open('tests/ajax_func/search_users/'.$class_id, array('class'=>"", 'id'=>'search-form')); ?>
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
						if (! empty ($batches)) {
							foreach ($batches as $batch) { 
							    ?>
								<option value="<?php echo $batch['batch_id']; ?>" <?php if ($batch['batch_id']==$batch_id) echo 'selected="selected"'; ?>><?php echo $batch['batch_name']; ?></option>
							    <?php
							}  
						}
						?>
					</select>
				</div>
				<div class="col-md-3">
					<div class="input-group ">
						<input name="search_text" class="form-control " type="search" placeholder="Search" aria-label="Search Test" aria-describedby="search-button">
						<div class="input-group-append">
							<button class="btn btn-sm btn-primary " type="submit" id="search-button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?php 
		if ($type == NOT_ENROLED_IN_TEST) {
			$this->load->view ('inc/users_not_enroled', $data);
		} else if ($type == ARCHIVED_IN_TEST) {
			$this->load->view ('inc/users_archived', $data);
		} else {
			$this->load->view ('inc/users_enroled', $data);
		}
		?>
	</div>	
</div>