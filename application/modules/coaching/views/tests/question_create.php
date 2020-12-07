<div class="row justify-content-center">
	
	<div class="col-md-3">
		<div class="card mb-2">
		  <div class="card-header">
		  	Questions
		  </div>
		  <ul class="list-group">
			<?php 
			$i = 1;
			if (! empty ($questions)) {
				foreach ($questions as $q) {
					?>
					<li class="list-group-item media">
						<div class="media-left">
							<?php echo $i; ?>
						</div>
						<div class="media-body">
							<?php 
							$qn = strip_tags ($q['question']); 
							$qn = character_limiter ($qn, 100);
							?>
							<a href="<?php echo site_url ('coaching/tests/question_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$q['parent_id'].'/'.$q['question_id'].'/'.$q['type']); ?>" class="<?php if ($q['question_id'] == $question_id) echo 'text-danger'; ?>"><?php echo $qn; ?></a>
						</div>
						
					</li>
					<?php
					$i++;
				}
				
			} else {
				?>
				<li class="list-group-item ">
					<span class="text-danger">No questions found</span>
				</li>
				<?php
			}
			?>
		  </ul>
		</div>
	</div>

	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<?php echo form_open ('coaching/tests_actions/validate_question_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id.'/'.$question_id, array('class'=>'form-horizontal row-border', 'id'=>'validate-1') ); ?>

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label ('Type <span class="required text-danger">*</span>', '', array('class'=>' control-label')); ?>
							<select name="question_type" class="form-control " id="question-type" <?php if ($question_id > 0) echo 'disabled'; ?> >
							<?php
							if (! empty ($question_types)) {
								foreach ($question_types as $item) {
									if ($item['paramkey'] == $question_type) {
										$selected = "selected='selected'";
									} else {
										$selected = "";
									}
									?>
									<option value="<?php echo $item['paramkey'];?>" <?php echo $selected; ?>><?php echo $item['paramval'];?></option>
									<?php 
								}
							}
							?>
							</select>
						</div>
					</div>

					<!--== Question ==-->
					<div class="form-group">
						<label class=" control-label">Question <span class="required text-danger">*</span></label>
						<div class="">
							<textarea name="question" class="form-control required tinyeditor " rows="5"  autofocus="true"><?php echo set_value ('question', $result['question']); ?></textarea>
						</div>
						<input type="hidden" name="category" value="<?php echo $question_group['category_id']; ?>" >
					</div>					

					<div class="form-group row">
						<div class="col-md-6">
							<?php echo form_label ('Classification <span class="required text-danger">*</span>', '', array('class'=>' control-label')); ?>
							<select name="classification" class="browser-default form-control required">
								<?php
								if (is_array($question_categories)) {
									foreach ($question_categories as $items) { 
										if ($question_id > 0) {
											if ($result['clsf_id'] == $items['paramkey']) {
												$selected = "selected='selected'";
											} else {
												$selected = "";
											}
										} else {
											if ($items['paramkey'] == 5) {
												$selected = "selected='selected'";
											} else {
												$selected = "";
											}
										}
										?>
										<option value="<?php echo $items['paramkey'];?>" <?php echo $selected; ?>><?php echo $items['paramval'];?></option>
										<?php 
									}
								}
								?>
							</select> 
						</div>
						
						<div class="col-md-6">
							<?php echo form_label ('Difficulty ', '', array('class'=>' control-label')); ?>
							<select name="difficulty" class="browser-default form-control required"> 
								<?php
								if (is_array($question_difficulties)) {
									foreach ($question_difficulties as $items) { ?>                
										<option  value="<?php echo $items['paramkey'];?>" <?php if ($result['diff_id'] == $items['paramkey']) echo "selected='selected'"; ?> ><?php echo $items['paramval'];?></option>
									<?php }
								}
								?>
							</select> 
						</div>
					</div>

					<div class="form-group">
						<?php echo form_label('Marks per question<span class="required">*</span>', '', array('class'=>'control-label')); ?>
						<div class="w-25">
							<?php
							if ($result['marks']) {
								$marks = $result['marks'];
							} else {
								$marks = $question_group['marks'];
							}							
							echo form_input(array('type'=>'number', 'name'=>'marks', 'class'=>'form-control required input-width-mini digits', 'min'=>1, 'value'=>set_value('marks', $marks) ));
							?>
						</div>
					</div>
					
				</div>

				<hr class="">

				<div class="card-body">					
					<h4 class="card-title">Answer Choices</h4>
					<div id="answer-choices">
						<?php $this->load->view (ANSWER_TEMPLATE . $template, $data); ?>
					</div>
				</div>

				<hr class="">

				<div class="card-body">
					<h4 class="card-title">Feedbacks</h4>
					<div class="form-group">
						<?php echo form_label('Question Feedback ', '', array('class'=>' control-label')); ?>
						<textarea name="question_feedback" class="form-control" rows="5"  autofocus=""><?php echo set_value ('question_feedback', $result['question_feedback']); ?></textarea>
					</div>
					<div class="form-group">
						<?php echo form_label('Answer feedback ', '', array('class'=>' control-label')); ?>
						<textarea name="answer_feedback" class="form-control" rows="5"  autofocus=""><?php echo set_value ('answer_feedback', $result['answer_feedback']); ?></textarea>
					</div>
					<!--== ./Feedback ==-->
				</div>

				<div class="card-footer"> 
					<?php
						echo form_button (array ('name'=>'save', 'value'=>'save', 'type'=>'submit', 'class'=>'btn btn-primary ', 'accesskey'=>'s', 'content'=>'Save', 'id'=>'save' )); 
						echo form_button (array ('name'=>'save_new', 'value'=>'save_new', 'type'=>'submit', 'class'=>'btn btn-secondary mr-1', 'accesskey'=>'n', 'content'=>'Save As New', 'id'=>'save_new' )); 
					?>
					<?php if ($question_id > 0) { ?>
						<a href="#" onclick="show_confirm ('Delete this question?', '<?php echo site_url ('coaching/tests_actions/remove_question/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id.'/'.$question_id); ?>')" class="btn btn-danger">Delete</a>
						<input type="hidden" name="question_type" value="<?php echo $result['type']; ?>">
					<?php } ?>
					<input type="hidden" name="negmarks" value="<?php echo $question_group['negmarks']; ?>">
					<input type="hidden" name="time" value="<?php echo $question_group['time']; ?>">
					<input type="hidden" name="language" value="0">
					<input type="hidden" name="category" value="0">
					<input type="hidden" name="save_type" id="save_type" value="save" > 
				</div>
			<?php echo form_close (); ?> 
		</div> <!-- /.widget .box -->
    </div>
</div>