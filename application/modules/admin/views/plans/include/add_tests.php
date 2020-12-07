<table class="table">
	<thead>
		<tr>
			<td colspan="5">
				<button type="submit" class="btn btn-primary" ><i class="fa fa-plus"></i> Add Tests</button>
			</td>
		</tr>
		<tr>
			<th width="5%">#</th>
			<th width="5%">
				<input type="checkbox" name="" id="check-all">
			</th>
			<th width="70%">Test Name</th>
			<th>Duration</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	$i = $offset + 1;
	if ( ! empty ($tests)) { 
		foreach ($tests as $row) { 
			?>
			<tr>
				<td><?php echo $i; ?>
				<td>
					<input type="checkbox" name="tests[]" value="<?php echo $row['test_id']; ?>" class="check" >
				</td>
				<td>
					<?php echo $row['title']; ?><br>
					<?php echo $row['unique_test_id']; ?>
				</td>
				<td>
					<?php echo $duration = $row['time_min'] . ' mins'; ?>
				</td>
				<td>
					<?php 
					if ($row['finalized'] == 1) {
						echo '<span class="badge badge-primary">Published</span>';
					} else {
						echo '<span class="badge badge-light">Draft</span>';
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
			<td colspan="5"><span class="text-danger">No tests found</span></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>