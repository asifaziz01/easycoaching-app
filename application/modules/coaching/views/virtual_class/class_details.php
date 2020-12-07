<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card card-default">
			<?php //echo form_open ('coaching/virtual_class_actions/create_meeting') ?>
				<div class="card-body">

					<div class="form-group ">
						<?php echo form_label('Virtual Classroom Name', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $class['class_name']; ?></p>
					</div>
					
					<div class="form-group ">
						<?php echo form_label('ID', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $class['meeting_id']; ?></p>
					</div>
					
					<div class="form-group ">
						<?php echo form_label('Welcome Message', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $class['welcome_message']; ?></p>
					</div>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label('Moderator Password', '', array('class'=>'control-label')); ?>
							<p class="form-control-static font-weight-bold"><?php echo $class['moderator_pwd']; ?></p>
						</div>
					
						<div class="col-md-6">
							<?php echo form_label('Attendee Password', '', array('class'=>'control-label')); ?>
							<p class="form-control-static font-weight-bold"><?php echo $class['attendee_pwd']; ?></p>
						</div>
					</div>
					
					<div class="form-group ">
						<?php echo form_label('Student must wait for moderator to join', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold">
							<?php if ($class['wait_for_moderator'] == 1) echo 'Yes'; else echo 'No'; ?>
						</p>
					</div>

					<div class="form-group row d-none">
						<?php
							$start_date = date ('d-m-Y', $class['start_date']);
							$start_time = date ('h:i a', $class['start_date']);
						?>
						<?php echo form_label('Class start on', '', array('class'=>'col-md-2 control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $start_date; ?></p>

						<?php echo form_label('at ', '', array('class'=>'col-md-1 control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $start_time; ?></p>
					</div>

					<div class="form-group row d-none">
						<?php
							$end_date = date ('d-m-Y', $class['end_date']);
							$end_time = date ('h:i a', $class['end_date']);
						?>
						<?php echo form_label('Class ends on', '', array('class'=>'col-md-2 control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $end_date; ?></p>

						<?php echo form_label('at ', '', array('class'=>'col-md-1 control-label')); ?>
						<p class="form-control-static font-weight-bold"><?php echo $end_time; ?></p>
					</div>

					<div class="form-group ">
						<?php echo form_label('Max Participants', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold">
							<?php echo $class['max_participants']; ?>
						</p>
					</div>

					<div class="form-group ">
						<?php echo form_label('Class Duration', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold">
							<?php echo $class['duration']; ?>
						</p>
					</div>

					<div class="form-group ">
						<?php echo form_label('Record classroom', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold">
							<?php if ($class['record_class'] == 1) echo 'Yes'; else echo 'No'; ?>
						</p>
					</div>

					<div class="form-group ">
						<?php echo form_label('Recording Description', '', array('class'=>'control-label')); ?>
						<p class="form-control-static font-weight-bold">
							<?php echo $class['record_description']; ?>
						</p>
					</div>

					<div class="form-group d-none">
						<?php echo form_label('Classroom URL', '', array('class'=>'control-label')); ?>
						<input type="text" class="form-control" id="meeting_url" readonly value="<?php echo $meeting_url; ?>">
						<a onclick="" href="<?php echo $meeting_url; ?>" target="_blank" class="btn btn-secondary">Join Classroom</a>
					</div>

				</div>	

				<div class="card-footer">	
					<?php 
					echo anchor ('coaching/virtual_class/join_class/'.$coaching_id.'/'.$class_id, '<i class="fa fa-plus"></i> Create and Join', ['class'=>'btn btn-primary mr-1']); 
					echo anchor ('coaching/virtual_class/participants/'.$coaching_id.'/'.$class_id, 'Participants', ['class'=>'btn btn-info']);
					?>
				</div>
		</div>
	</div>
</div>