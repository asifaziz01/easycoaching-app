<div class="card card-default ">
	<div class="-table-responsive" id="">
		<table class="table table-bordered v-middle mb-0" id="data-tables">
			<thead>
				<tr>
					<th width="5">#</th>
					<th width="">Title</th>
				</tr>
			</thead>

			<tbody>
			<?php 
				$i=1;
				if (! empty ($results)) {
					foreach ($results as $row) {
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo anchor ('student/announcements/view/'.$coaching_id.'/'.$row['announcement_id'], $row['title']); ?></td>
						</tr>
					 	<?php 
					 	$i++;  
					}
				} else {
					?>
					<tr>
						<td colspan="2">No announcements</td>
					</tr>
					<?php
				}
			?>	
			</tbody>
		</table>
	</div>
</div>