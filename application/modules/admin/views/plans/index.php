<div class="row justify-content-center" >
	<div class="col-md-9">
	  <div class="card">
		<table class="table table-hover table-bordere">
			<thead>
				<tr>
					<th width="40%"><?php echo 'Plan Name'; ?></th>
					<th width=""><?php echo 'Price (Rs)'; ?></th>
					<th width=""><?php echo 'Tests'; ?></th>
					<th width=""><?php echo 'Status'; ?></th>
					<th width=""><?php echo 'Active Subscribers'; ?></th>
					<th width=""><?php echo 'Options'; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ( ! empty ($plans)) {
					foreach ($plans as $row) {
						?>
						<tr>
							<td>
								<p class="text-bold mb-0">
								<?php echo anchor ('admin/plans/tests_in_plan/'.$row['plan_id'], $row['title'], array ('title'=>'Browse all tests in this plan ')); ?>
								</p>
								<span class="text-grey-500">
									Category: <?php echo $row['cat_title']; ?>
								</span>
							</td>
							<td>
								<?php 
								if ($row['amount'] == 0) {
									echo '<span class="label label-primary">Free</span>';
								} else {
									echo '<i class="fa fa-rupee-sign"></i> '.$row['amount'] . ' per month';
								}
								?>
							</td>
							<td>
								<?php 
									echo $row['tests_in_plan'];
								?>
							</td>
							<td>
								<?php 
								if ($row['status'] == 1) {
									echo '<span class="badge badge-success">Active</span>';
								} else {
									echo '<span class="badge badge-secondary">Draft</span>';
								}
								?>
							</td>
							<td class="text-center">
								<?php 
									echo $row['active_subscribers'];
								?>
							</td>
							<td>
								<a href="<?php echo site_url('admin/plans/create_plan/'.$row['plan_id']); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="fa fa-edit"></i></a>
								
								<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this plan?' ; ?>','<?php echo site_url('admin/plan_actions/delete_plan/'.$row['plan_id']); ?>' )" class="btn btn-sm btn-primary" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete"><i class="fa fa-trash"></i> </a>
							</td>
						</tr>
						<?php 
					}
				} else { 
				?>
				<tr>
					<td colspan="6"><?php echo 'No Plans Found'; ?></td>
				</tr>
			<?php } // if result ?>		
			</tbody>
		</table>
	  </div>
	</div>
	
	<div class="col-md-3">
	    <div class="card">
	        <div class="card-header">
	            <h5 class="">Categories</h5>
	        </div>
	        <div class="list-group">
	            <?php 
	            if (! empty ($categories)) {
	                foreach ($categories as $cat) {
	                   ?>
	                   <a class="list-group-item <?php if ($category_id == $cat['id']) echo 'active'; ?>" href="<?php echo site_url ('admin/plans/index/'.$cat['id']); ?>"><?php echo  $cat['title']; ?></a>
	                   <?php
	                }
	            } else {
					?>
					<li class="list-group-item">
						No Categories found
					</li>
					<?php
				}
	            ?>
	        </div>
	    </div>
	</div>
</div>