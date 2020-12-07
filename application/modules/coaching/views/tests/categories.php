<div class="row justify-content-center">
	<div class="col-lg-8">
		<div class="card">
			<div class="table-responsives">
				<table class="table">
					<thead>
						<tr>
							<th width="5">#</th>
							<th width="70%">Title</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$i = 1;
					if (! empty($categories)) {
						foreach ($categories as $row) {
							?>
							<tr>
								<th scope="row"><?php echo $i; ?></th>
								<td>
								<?php 
									echo anchor ('coaching/tests/index/'.$coaching_id.'/'.$row['id'], $row['title']);
								?>
								</td>
								<td>
									<!-- Rename -->
									<a href="#rename<?php echo $row['id']; ?>" data-toggle="modal" id="<?php echo $row['id']; ?>" class="btn btn-sm " data-val="<?php echo $row['title']; ?>" data-toggle="tooltip" data-placement="top" title="Rename"><i class=" fa fa-edit"></i> </a>

									<!-- Rename Category Modal -->
									<div class="modal fade" id="rename<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="renameNode" aria-hidden="true">
									  <div class="modal-dialog" role="document">
										<div class="modal-content">
										   <?php echo form_open ('coaching/tests_actions/add_category/'.$coaching_id.'/'.$row['id'], array ('class'=>'validate-form')); ?>
											  <div class="modal-header">
												<h5 class="modal-title" id="renameNodeTitle">Rename</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												  <span aria-hidden="true">&times;</span>
												</button>
											  </div>
											  <div class="modal-body">
												<div class="form-group">
													<label class="control-label">Change Title</label>
													<input type="text" class="form-control" name="title" value="<?php echo $row['title']; ?>">
												</div>
											  </div>
											  <div class="modal-footer">
												<button type="submit" class="btn btn-success">Save changes</button>
											  </div>
										   </form>
										</div>
									  </div>
									</div>
									<!-- Delete -->
									<?php
									$message = 'Delete this category?';
									$url = site_url('coaching/tests_actions/remove_category/'.$coaching_id.'/'.$row['id']);
									?>
									<a href="#" onclick="show_confirm ('<?php echo $message; ?>', '<?php echo $url; ?>')" class="btn btn-sm " ><i class="fa fa-trash"></i></a>
									
								</td>
							</tr> 
							<?php
							$i++;
						}
					} else { ?>
						<tr>
							<td colspan="3" class="text-danger">None found</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>

			<div class="card-footer">
				<!-- Button trigger modal -->
				<a href="#add_category" data-toggle="modal" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add Category"><i class=" fa fa-plus"></i> Add Category</a>
			</div>
		</div>
	</div> <!--// col-md-6 -->	
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="renameNode" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	   <?php echo form_open ('coaching/tests_actions/add_category/'.$coaching_id, array ('class'=>'validate-form')); ?>
		  <div class="modal-header">
			<h5 class="modal-title" id="renameNodeTitle">Add New Category</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="form-group">
				<label class="control-label">Category Title</label>
				<input type="text" value="" class="form-control" name="title" autofocus="true">
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-success">Save </button>
		  </div>
	   </form>
	</div>
  </div>
</div>