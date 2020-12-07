<div class="card card-default">
	<div class="table-responsive">
	<table class="table  mb-0 table-hover ">
		<thead>
			<tr>
				<th width="5%">#</th>
				<th width="60%"><?php echo 'Coaching Name'; ?></th>
				<th><?php echo 'Subscription Plan'; ?></th>
				<th><?php echo 'Admin Email'; ?></th>
				<th><?php echo 'Creation Date'; ?></th>
				<th><?php echo 'Actions'; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
        if ( ! empty ($results)) { 
			foreach ($results as $row) {
				?>
				<tr>
				    <td><?php echo $i; ?></td>
					<td>
						<a href="<?php echo site_url ('coaching/home/dashboard/'.$row['id']); ?>" title='Dashboard', class=''><?php echo $row['coaching_name']; ?></a><br>
						<?php echo $row['reg_no']; ?>
					</td>
					
					<td><?php echo $row['title']; ?></td>
					<td><?php echo $row['email']; ?></td>
					<td><?php echo date ('d M Y', $row['creation_date']); ?></td>
                    <td>
                        <?php 
                            echo anchor ('admin/coaching/manage/'.$row['id'], 'Manage', array('class'=>'btn btn-primary'));
                        ?>
                    </td>
				</tr>
				<?php 
				$i++;
			} // foreach 
        } else {
            ?>
            <tr>
                <td colspan="4"><span class="text-danger">No coaching accounts created yet </td>
            </tr>
            <?php
        }
		?>
		</tbody>
	</table>
	</div>
</div>