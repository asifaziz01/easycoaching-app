<div class="row">
	<div class="col-md-12 col-md-offset"> 
		<div class="card card-default">
			<?php if ( ! empty ($subscription_plans)) { ?>
				<table class="table  table-hover table-checkable ">
					<thead>
						<tr>
							<th width="30%"><?php echo 'Plan Name'; ?></th>
							<th width=""><?php echo 'Max Users'; ?></th>
							<th width=""><?php echo 'Duration (Days)'; ?></th>
							<th width=""><?php echo 'Price (Rs)'; ?></th>
							<th width=""><?php echo 'Status'; ?></th>
							<th width=""><?php echo 'Options'; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach ($subscription_plans as $row) { 
							?>
							<tr>
								<td><?php echo anchor ('admin/subscriptions/create_plan/'.$row['id'], $row['title'], array ('title'=>'Click to change')); ?></td>
								<td>
									<?php echo $row['max_users']; ?> 
								</td>
								<td>
									<?php echo $row['duration']; ?>
								</td>
								<td>
									<?php echo $row['price']; ?>
								</td>
								<td>
									<?php if ($row['status'] == 1) echo '<span class="badge badge-success">Active</span>'; else echo '<span class="badge badge-secondary">In-active</span>' ?>
								</td>
								<td>
								<div class="btn-group">
									<a href="<?php echo site_url('admin/subscriptions/create_plan/'.$row['id']); ?>" class="btn btn-xs"><i class="fa fa-edit"></i></a>
									
									<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this plan?' ; ?>','<?php echo site_url('admin/subscription_actions/delete_plan/'.$row['id']); ?>' )" class="" title="" ><i class="fa fa-trash"></i> </a>
								</div>
								</td> 
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-danger">
								<p><?php echo 'No Plans Found'; ?></p>
							</div>
						</div>
					</div>
				</div>
			<?php } // if result ?>
			
		</div>
	</div>
</div>
