<div class="row justify-content-center">
	<div class="col-md-12">
		<?php 
		if ( ! empty ($results) ) {
		  echo form_open('coaching/tests_actions/remove_questions/'.$category_id.'/'.$test_id, array('class'=>'form-horizontol', 'id'=>'validate-1') );
			$num_parent = 1;
			foreach ( $results as $parent_id=>$all_questions) {
				$parent 	= $all_questions['parent'];
				$questions 	= $all_questions['questions'];
				?>
				<div class="card mb-4 " oncopy="return false;" oncut="return false;" onpaste="return false;" onmousedown="return false;" onselectstart="return false;">
					<div class="card-header">
						<div class="">
							<?php if ( $test['finalized'] == 0) { ?>
								<input type="checkbox" class="checks checkAll" id="checkAll<?php echo $parent_id; ?>" value="<?php echo $parent_id; ?>" onclick="check_all ()">
							<?php } ?>
							<label for="checkAll<?php echo $parent_id; ?>" class="">Section <?php echo $num_parent; ?></label>
						</div>

						<div class="d-flex justify-content-between">						  
						  <div class="">
							<?php
								if ( $test['finalized'] == 0) {
									echo anchor ('coaching/tests/question_group_edit/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id, $parent['question']);
								} else {
									echo $parent['question'];
								}
							?>						  
						  </div>
						  
						  <div class="">
						  </div>
						</div>
					</div>
					<ul class="list-group">
						<?php 
						$num_question = 1;
						if ( ! empty($questions)) {
							foreach ($questions as $id=>$row) {
								?>
								<li class="list-group-item">
								  <div class="media">
									<div class="media-left">
										<?php if ( $test['finalized'] == 0) { ?>
											<input name="questions[]" id="select<?php echo $id; ?>" type="checkbox" value="<?php echo $id; ?>" class="mr-2 checks checks<?php echo $parent_id; ?>">
										<?php } ?>
										<label for="select<?php echo $id; ?>"><?php echo $num_question; ?>.</label>
									</div>

									<div class="media-body">
										<?php 
										if ( $test['finalized'] == 0) {
											echo anchor ('coaching/tests/question_edit/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$row['parent_id'].'/'.$id, $row['question']);
										} else {
											echo $row['question'];
										}
										?>
										<?php echo $this->qb_model->display_answer_choices($row['type'], $row); ?>
									</div>
								  </div>
								
								  <div class="row">
									<div class="col-xs-12 pl-4 pr-1">
									</div>
								  </div>

								</li>

								<?php
								$num_question++;
							}
						}
						$num_parent++;
						?>
					</ul>

					<div class="card-footer">
						<?php 
						if ( $test['finalized'] == 0) {
							?>
							<input type="submit" name="delete" value=" Delete " class="btn btn-sm btn-danger">
							<?php							
							echo anchor ('coaching/tests/question_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id, '<i class="fa fa-plus"></i> Add Question', array('class'=>'btn btn-info btn-sm ')); 
						}
						?>
					</div>
				</div>
				<?php
			}
			?>

			<div class="card-footer">
				<div class="row">
					<div class="col-2">
						<?php if ( $test['finalized'] == 0) { ?>
							<input type="checkbox" class="selectAll" id="selectAll">
							<label for="selectAll" class="control-label">Select All</label>
						<?php } ?>
					</div>
					<div class="col-4">
						<?php 
						if ( $test['finalized'] == 0) {
							echo form_submit (array ('name'=>'save', 'value'=>'Delete ', 'class'=>'btn btn-sm btn-primary'));
						} 
						?>
					</div>
					<div class="col-md-6">
						<?php //echo $this->pagination->create_links (); ?>
					</div>
				</div>
			</div>
		  </form>
		<?php } else { ?>
			<div class="alert alert-danger">
				<strong> <?php echo 'No questions found in test'; ?></strong>
				<p>You can <?php echo anchor('coaching/tests/question_group_create/'.$coaching_id.'/'.$category_id.'/'.$test_id, 'Create Questions', array ('class'=>'btn btn-sm btn-primary') ); ?>  <?php // echo anchor('coaching/tests/upload_test_questions/'.$coaching_id.'/'.$category_id.'/'.$test_id, 'Upload Questions', array ('class'=>'btn btn-sm btn-secondary') ); ?> in this test.
				</p>
			</div>
			<?php 
		} 
		?>
	</div>
</div>