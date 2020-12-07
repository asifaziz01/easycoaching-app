<div class="row justify-content-center" >
	<div class="col-md-12">
	  <div class="card">
		<table class="table table-hover table-bordere">
			<thead>
				<tr>
					<th width="60%"><?php echo 'Plan Name'; ?></th>
					<th width="">
						<?php echo 'Category'; ?>
						<?php 
						if ($category_id > 0) { 
							echo '<div>';
							echo anchor ('admin/plans/import_from_its', 'Show All');
							echo '</div>';
						}
						?>
						
					</th>
					<th width=""><?php echo 'Price (Rs)'; ?></th>
					<th width=""><?php echo 'Options'; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ( ! empty ($test_plans)) {
					foreach ($test_plans as $row) {
						$plan_exists = $this->plans_model->its_test_plan_exists ($row['plan_id']);
						?>
						<tr>
							<td>
								<?php if ( ! $plan_exists ) { ?>
									<span class="badge badge-success">New</span>
								<?php } ?>
								<?php echo anchor ('admin/plans/import/'.$row['plan_id'], $row['title'], array ('title'=>'Click to add tests')); ?>
							</td>
							<td>
								<?php echo anchor ('admin/plans/import_from_its/'.$row['cat_id'], $row['cat_title'], array ('title'=>'')); ?>
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
								<a href="<?php echo site_url('admin/plans/create_test_plan/'.$row['plan_id']); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="fa fa-edit"></i></a>
							</td> 
						</tr>
						<?php 
					}
				} else { 
				?>
				<tr>
					<td colspan="3"><?php echo 'No Plans Found'; ?></td>
				</tr>
			<?php } // if result ?>		
			</tbody>
		</table>
	  </div>
	</div>
</div>