<div class="row justify-content-center" >
	<div class="col-md-12">
	  <div class="card">
		  <table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2">
						<?php echo $plan['title']; ?>
					</th>
					<th colspan="">
						<?php echo count ($results) . ' Tests'; ?>
					</th>
				</tr>
				<tr>
					<th width="20">
						<?php echo '#'; ?>
					</th>
					<th width="70%"><?php echo 'Test Name'; ?></th>
					<th width=""><?php echo 'Duration'; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			if ( ! empty($results)) {
				foreach ($results as $row) {
					?>
					<tr>
						<td>
							<?php echo $i; ?>
						</td>

						<td>
							<?php
							echo $row['title'];
							?>
						</td>
						<td><?php echo $row['time_min'] . ' mins'; ?></td>
					</tr>
					<?php
					$i++;
				}
			} else {
				?>
				<tr>
					<td colspan="3"><p class="text-danger">No tests added</p></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		  </table>
		  
	  </div>		  
	</div>
</div>