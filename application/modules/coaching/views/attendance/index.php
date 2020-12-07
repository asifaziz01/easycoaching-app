<div class="card mb-2"> 
	<div class="card-body ">
		<strong>Search</strong>
		<?php echo form_open('coaching/attendance_actions/search_users/'.$coaching_id, array('class'=>"", 'id'=>'search-form')); ?>
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
						<?php /*foreach ($batches as $batch) { ?>
							<option value="<?php echo $batch['id']; ?>"><?php echo $batch['title']; ?></option>
						<?php }*/ ?>
					</select>
				</div>
				<div class="col-md-3 mb-2 d-none">
					<div class="input-group ">
						<input name="search_text" class="form-control " type="search" placeholder="Search" aria-label="Search Test" aria-describedby="search-button">
						<div class="input-group-append">
							<button class="btn btn-sm btn-primary " type="submit" id="search-button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				
				<div class="col-md-3 mb-2">
					<input type="date" id="search-date" value="<?php echo $date; ?>" max="<?php echo $date;?>" class="form-control"  > 
				</div>
			</div>
		</form>				
		
	</div>
</div>

<div class="card card-default">
	<ul class="list-group" id="data-tables">
		<?php
		$i = 0 ;
		if ( ! empty ($results)) {
			foreach ($results as $row) {
				?>
				<li class="list-group-item media">
					
					<div class="media-left">

					</div>

					<div class="media-body">


					<p>
						<?php echo ($row['first_name']) .' '. ($row['second_name']) .' '. ($row['last_name']); ?>
						<br> 
						<?php echo $row['adm_no']; ?>
					</p>

					</div>
					<div class="btn-group">
						<a onclick="mark_attendance (this.id, <?php echo $row['member_id']; ?>, <?php echo ATTENDANCE_PRESENT; ?>);" class="btn btn-att-<?php echo $row['member_id']; ?> <?php if ($attendance[$row['member_id']]['attendance'] == ATTENDANCE_PRESENT) echo 'btn-success'; else echo 'btn-default' ?>" id="present<?php echo $row['member_id']; ?>" >Present</a>
						
						<a onclick="mark_attendance (this.id, <?php echo $row['member_id']; ?>, <?php echo ATTENDANCE_LEAVE; ?>);" class="btn btn-att-<?php echo $row['member_id']; ?> <?php if ($attendance[$row['member_id']]['attendance'] == ATTENDANCE_LEAVE) echo 'btn-success'; else echo 'btn-default' ?>" id="leave<?php echo $row['member_id']; ?>" >Leave</a>
						
						<a onclick="mark_attendance (this.id, <?php echo $row['member_id']; ?>, <?php echo ATTENDANCE_ABSENT; ?>);" class="btn btn-att-<?php echo $row['member_id']; ?> <?php if ($attendance[$row['member_id']]['attendance'] == ATTENDANCE_ABSENT) echo 'btn-success'; else echo 'btn-default' ?>" id="absent<?php echo $row['member_id']; ?>" >Absent</a>
						
						<a href="<?php echo site_url ('attendance/reports/member_report/'.$row['member_id']); ?>" class="btn btn-dark text-white d-none">Report</a>
					</div>
				</li>
				<?php 
			} // foreach 
		} else {
			?>
			<li class="list-group-item">
				<span class="text-danger">No users found</span>
			</li>
			<?php
		}
		?>
	</ul>
</div>
