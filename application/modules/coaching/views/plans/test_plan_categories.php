<div class="row justify-content-center" >
	<div class="col-md-12">
	  <div class="card">
		<table class="table table-hover table-bordere">
			<thead>
				<tr>
					<th width="50%"><?php echo 'Title'; ?></th>
					<th width=""><?php echo 'Plans'; ?></th>
					<th width=""><?php echo 'Status'; ?></th>
					<th width="10%"><?php echo 'Actions'; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ( ! empty ($categories)) {
					foreach ($categories as $row) {
						?>
						<tr>
							<td>
								<p class="text-bold mb-0">
								<?php echo anchor ('plans/admin/test_plans/'.$row['id'], $row['title'], array ('title'=>'Click to edit')); ?>
								</p>
								<span class="text-muted">
								<?php 
								    echo character_limiter ($row['description'], 150);
								?>
								</span>
							</td>

							<td>
								<?php 
									//echo $row['tests_in_plan'];
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
							<td>
								<a href="<?php echo site_url('plans/admin/create_category/'.$row['id']); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class="fa fa-edit"></i></a>		
								
								<a href="javascript:void(0)" onclick="show_confirm ('<?php echo 'Are you sure want to delete this category?' ; ?>','<?php echo site_url('plans/functions/delete_category/'.$row['id']); ?>' )" class="btn btn-sm btn-primary" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete"><i class="fa fa-trash"></i> </a>
							</td> 
						</tr>
						<?php 
					}
				} else { 
				?>
				<tr>
					<td colspan="4"><?php echo 'No Categories Found'; ?></td>
				</tr>
			<?php } // if result ?>		
			</tbody>
		</table>
	  </div>
	</div>
</div>