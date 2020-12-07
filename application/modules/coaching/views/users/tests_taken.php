<section class="content">

	<div class="container">

		<div class="row">

			<div class="col-md-9">
				<div class="card card-default">
					<div class="card-body table-responsive">
						<table class="table v-middle table-bordered mb-0" >

							<thead>

								<tr>

									<th width="5%">#</th>

									<th width="50%"><?php echo 'Test Name'; ?></th>

									<th width=""><?php echo 'Taken On'; ?></th>

									<th width=""><?php echo 'Actions'; ?></th>

								</tr>

							</thead>					

							<tbody id="">

							<?php 

							if ( ! empty ($tests_taken)) { 

								$i = 1; 

								foreach ($tests_taken as $row) { 

									$test = $this->tests_model->view_tests ($row['test_id']);

									?>

									<tr>

										<td><?php echo $i; ?></td>

										<td><?php echo $test['title']; ?></td>

										<td><?php echo date ('l jS M, Y at H:i a', $row['loggedon']); ?></td>

										<td><?php echo anchor_popup ('tests/reports/all_reports/0/'.$member_id.'/'.$row['test_id'], 'Reports'); ?></td>

									</tr>

									<?php 

									$i++;

								} 

							} else {

								?>

								<tr>

									<td colspan="4">No tests taken</td>

								</tr>

								<?php 

							} 

							?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-3">

				<?php $this->load->view('ajax/user_menu', $data); ?>

			</div>

		</div>			

	</div>

</section>