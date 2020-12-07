<div class="row" >
	<div class="col-md-9">
		<div class="card">			
			<ul class="list-group">
			<?php
				if ($results) {
					foreach ($results as $log) { 
					?>
					<li class="list-group-item media v-middle">
						<div class="media-left">
							<div class="icon-block half img-circle bg-grey-300">
								<i class="fa fa-file-text text-white"></i>
							</div>
						</div>
						<div class="card-body">
							<h4 class="text-subhead margin-none">
								<a data-toggle="modal" href="#edit_log_<?php echo $log['id']; ?>" class="link-text-color"><?php echo entities_to_ascii (character_limiter($log["comment"], 30));?></a>
							</h4>
							<div class="text-light text-caption">
								<?php $logby = $this->users_model->get_user ($log["log_by"]); ?>
								Created by
								<a href="#">
									<?php echo $logby['first_name'] .' '. $logby['last_name']; ?></a> &nbsp; | <i class="fa fa-clock-o fa-fw"></i> <?php echo $log["date"];?>
								</a>
							</div>
						</div>
						<div class="media-right">							

							<!-- DELETE LOG  -->
							<a href="javascript:void(0);" onclick="show_confirm ('Are you sure delete this log?', '<?php echo site_url("users/ajax_func/delete_member_log/".$class_id."/".$role.'/'.$member_id."/".$log["id"]); ?>')" class="bs-tooltip btn btn-white text-light"><i class="fa fa-trash"></i></a>
						
						</div>
						
						<!-- EDIT LOG  -->
						<div class="modal fade" id="edit_log_<?php echo $log['id']; ?>" aria-hidden="true" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<?php echo form_open("users/ajax_func/member_log/".$class_id."/".$role.'/'.$member_id.'/'.$log['id'], array('class'=>'form-horizontal row-border','id'=>'')); ?>
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
											<h4 class="modal-title">Comment</h4>
										</div>
										<div class="modal-body">
											<div class="form-group">						
												<?php echo form_label('Enter Comment', '', array('class'=>'control-label')); ?>
												<div class="col-md-12">
													<?php echo form_textarea( array('name'=>'action_log', 'class'=>'form-control ', 'value'=>entities_to_ascii ($log['comment'])) );?>   
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary pull-right" />
										</div>
									</form>  
								</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>

					</li>
					<?php
					}
				} else { 
					echo '<li class="list-group-item  alert alert-danger text-center">Log empty</li>';
				}
				?>
			</ul>					
		</div>
		<div class="form-actions">
				<div class="btn-toolbar	float-right">
					<?php 					
					echo anchor ('users/page/index/', 'Cancel', array ('class'=>'btn btn-default'));
					?>
				</div>
		</div>	
	</div>
	<div class="col-md-3">
		<?php 
			$this->load->view('ajax/right_side_manage_menu',$data);
		?>
	</div>
</div>

<!-- Create log -->
<div class="modal fade" id="create_log">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open("users/ajax_func/member_log/".$class_id."/".$role.'/'.$member_id.'/0', array('class'=>'', 'id'=>'validate-1')); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Create Log</h4>
				</div>
				<div class="modal-body">
				
					<div class="row">
						<?php echo form_label('Enter Comment', '', array('class'=>'control-label')); ?>
						<div class="col-md-12">
							<?php echo form_textarea ( array('name'=>'action_log', 'class'=>'form-control', 'row'=>5) );?>   
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<div id="validation-message"></div>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" name="submit" value="<?php echo ('Save'); ?>" class="btn btn-primary pull-right" />
						</div>
					</div>
				
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->