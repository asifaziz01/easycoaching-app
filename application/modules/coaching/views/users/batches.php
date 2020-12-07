<div class="card">
	<ul class="list-group ">
	<?php
	if ($all_batches) {
		foreach ($all_batches as $row) { 
			?>
			<li class="list-group-item media">
				<div class="media-left">
				</div>
				<div class="media-body">
					<a href="<?php echo site_url ('coaching/users/batch_users/'.$coaching_id.'/'.$row['batch_id']); ?>"><?php echo $row["batch_name"];?></a><br>
					<?php echo $row["batch_code"];?>
					<p class="text-muted"> 						
					<?php 
					if ($row['start_date'] > 0 ) {
						echo 'Starting On: '. date('d M, Y', $row["start_date"]);
					} 
					if ($row['end_date'] > 0 ) {
						echo 'Ending On: '.date('d M, Y', $row["end_date"]);
					} 
					?>
					</p>
					<?php 
					$num = 0;
					$users = $this->users_model->batch_users ($row['batch_id']);
					if ( ! empty ($users)) {
						$num = count ($users);
					}
						echo '<a href="'.site_url ('coaching/users/batch_users/'.$coaching_id.'/'.$row['batch_id']).'" class="badge badge-success">'.$num.' Users</a>';
					?>
				</div>
				<div class="media-right">
					<a href="<?php echo site_url('coaching/users/create_batch/'.$coaching_id.'/'.$row["batch_id"]); ?>" class="text-danger"><i class="fa fa-edit"></i> </a>	
					<a href="javascript:void(0);" onclick="show_confirm ('Are you sure delete this batch?', '<?php echo site_url('coaching/user_actions/delete_batch/'.$coaching_id.'/'.$row["batch_id"]); ?>')" class="text-danger"><i class="fa fa-trash"></i> </a>	
				</div>
			</li>
			<?php } 
			} else {
				?>
				<li class="list-group-item ">
					<div class="text-danger">No batches found. <?php echo anchor ('coaching/users/create_batch/'.$coaching_id, 'Create Batch'); ?></div>
				</li>
				<?php
			}
			?>
	</ul>
</div>

<!-- Create row -->
<div class="modal fade d-b" id="create_batch">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open("users/ajax_func/create_batch", array('class'=>'form-horizontal row-border', 'id'=>'validate-1')); ?>
				<div class="modal-header">
					<h4 class="modal-title">New Batch</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	
					
					<div class="form-group">
						<?php echo form_label('Batch Code', '', array('class'=>'col-md-12 control-label text-left')); ?>
						<div class="col-md-10">
							<?php echo form_input ( array('name'=>'batch_code', 'class'=>'form-control required') );?>   
						</div>
					</div>	
					<div class="form-group">
						<?php echo form_label('Batch Name', '', array('class'=>'col-md-12 control-label text-left')); ?>
						<div class="col-md-10">
							<?php echo form_input ( array('name'=>'batch_name', 'class'=>'form-control required') );?>   
						</div>
					</div>	
					
					<div class="form-group">
						<?php echo form_label('Start Date', '', array('class'=>'col-md-12 control-label text-left')); ?>
						<div class="col-md-6">
							<?php echo form_input ( array('name'=>'start_date', 'type'=>'date', 'class'=>'form-control', 'placeholder'=>'DD/MM/YYYY') );?>   
						</div>
					</div>	
					<div class="form-group">
						<?php echo form_label('End Date', '', array('class'=>'col-md-12 control-label text-left')); ?>
						<div class="col-md-6">
							<?php echo form_input ( array('name'=>'end_date', 'type'=>'date', 'class'=>'form-control ', 'placeholder'=>'DD/MM/YYYY') );?>   
						</div>
					</div>	
				</div>
				<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary " />
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->