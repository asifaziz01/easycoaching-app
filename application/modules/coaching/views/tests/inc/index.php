<div class="card card-default mb-4">
	<div class="table-responsive" id="test-lists">
		<table class="table mb-0">
			<thead>
				<tr>
					<th width="5%">#</th>
					<th width="50%">Test Name</th>
					<th>Test Type</th>
					<th>Duration</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i = 1;
			if ( ! empty ($tests)) { 
				foreach ($tests as $row) { 
					?>
					<tr>
						<td><?php echo $i; ?>
						<td>
							<?php echo anchor('coaching/tests/manage/'.$coaching_id.'/'.$row['category_id'].'/'.$row['test_id'], $row['title'], array('title'=>'Plans', 'class'=>'btn-link')); ?><br>
							<?php echo $row['unique_test_id']; ?>
						</td>
						<td>
							<?php 
							$param = $this->common_model->sys_parameter_name(SYS_TEST_TYPE, $row['test_type']);
							echo $param['paramval'];
							?>
						</td>
						<td>
							<?php echo $duration = $row['time_min'] . ' mins'; ?>
						</td>
						<td>
							<?php 
							if ($row['finalized'] == 1) {
								echo '<span class="badge badge-primary">Published</span>';
							} else {
								echo '<span class="badge badge-light">Un-published</span>';
							}
							?>
						</td>
					</tr>
					<?php 
					$i++; 
				} 
			} else {
				?>
				<tr>
					<td colspan="5" >
						<div>
							<span class="text-danger">No tests found</span>									
						</div>
						<?php echo anchor ('coaching/tests/create_test/'.$coaching_id.'/'.$category_id, 'Create Test'); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>
</div>
