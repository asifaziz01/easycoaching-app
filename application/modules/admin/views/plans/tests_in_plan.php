<div class="row justify-content-center" >
	<div class="col-md-12">
	  <div class="card">
		<?php echo form_open ('admin/plan_actions/remove_tests/'.$plan_id, array ('id'=>'validate-1')); ?>
		  <table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="4">
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
					<th width="20">
						<input type="checkbox" name="" id="check-all">
					</th>
					<th width="70%"><?php echo 'Test Name'; ?></th>
					<th width=""><?php echo 'Duration'; ?></th>
					<th width=""><?php echo 'Actions'; ?></th>
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
							<input type="checkbox" name="tests[]" value="<?php echo $row['test_id']; ?>" class="check" >
						</td>
						<td>
							<?php
							echo $row['title'];
							?>
						</td>
						<td><?php echo $row['time_min'] . ' mins'; ?></td>
						<td><?php echo anchor_popup ('admin/tests/manage/'.$row['category_id'].'/'.$row['test_id'], 'Manage', array('class'=>'btn btn-primary', 'disabled'=>true, 'title'=>'Editing feature is temporarily disabled ')); ?>
						</td>
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
		
		  <div class="card-footer">
			<button type="submit" class="btn btn-primary mr-2" disabled> Remove Tests</button>
			<a href="<?php echo site_url ('admin/plans/add_tests/'.$plan_id); ?>" class="btn btn-success" disabled><i class="fa fa-plus"></i> Add Tests</a>
		  </div>
		  
		</form>
		
	  </div>		  
	</div>
</div>