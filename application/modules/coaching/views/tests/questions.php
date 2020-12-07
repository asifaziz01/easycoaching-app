<?php if ( ! empty ($results) ) { ?>
	<div class="card mb-4 " oncopy="return false;" oncut="return false;" onpaste="return false;" onmousedown="return false;" onselectstart="return false;">
	  <?php 
	  echo form_open('coaching/tests_actions/remove_questions/'.$category_id.'/'.$test_id, array('class'=>'form-horizontol', 'id'=>'validate-1') );
		$num_parent = 1;
		foreach ( $results as $parent_id=>$all_questions) {
			$parent 	= $all_questions['parent'];
			$questions 	= $all_questions['questions'];
			?>
			<ul class="list-group">
				<!------ // Question Heading // ------>
				<li class="list-group-item media">
					<div>
						<label for="" class="">Section <?php echo $num_parent; ?></label>
					</div>
					<div class="media-left">
						<?php if ( $test['finalized'] == 0) { ?>
							<input type="checkbox" class="checks checkAll" id="checkAll<?php echo $parent_id; ?>" value="<?php echo $parent_id; ?>" onclick="check_all ()">
						<?php } ?>
					</div>
					<div class="media-body">
						<?php
							if ( $test['finalized'] == 0) {
								echo anchor ('coaching/tests/question_group_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id, $parent['question']);
							} else {
								echo $parent['question'];
							}
						?>
					</div>
					<div class="media-right">
						<?php 
						if ( $test['finalized'] == 0) {
							echo anchor ('coaching/tests/question_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$parent_id, '<i class="fa fa-plus"></i> Add Question', array('class'=>'btn btn-info btn-sm '));
						}
						?>
					</div>
				</li>
				<!------ // Question Heading // ------>

				<!------ // Sub Questions // ------>
				<?php 
				$num_question = 1;
				if ( ! empty($questions)) {
					foreach ($questions as $id=>$row) {
						?>
						<li class="list-group-item media">
							<div class="media-left">
								<label for="">
									<?php if ( $test['finalized'] == 0) { ?>
										<input name="questions[]" id="select<?php echo $id; ?>" type="checkbox" value="<?php echo $id; ?>" class="mr-2 checks checks<?php echo $parent_id; ?>">
									<?php } ?>
									<?php echo $num_question; ?>.
								</label>
							</div>

							<div class="media-body">
								<?php 
								if ( $test['finalized'] == 0) {
									echo anchor ('coaching/tests/question_create/'.$coaching_id.'/'.$category_id.'/'.$test_id.'/'.$row['parent_id'].'/'.$id, $row['question']);
								} else {
									echo $row['question'];
								}
								$template = 'view_'.$row['type'];
								$data['result'] = $row;
								$this->load->view (ANSWER_TEMPLATE . $template, $data); ?>
								<?php //echo $this->qb_model->display_answer_choices($row['type'], $row); ?>
							</div>

							<div class="media-right">
								<a href="void()" onclick="show_confirm ('Remove this ?')">Delete</a>
								asasas
							</div>
						</li>
						<?php
						$num_question++;
					}
				}
				$num_parent++;
			}
			?>
			<!------ // Sub Questions // ------>					
			</ul>

			<div class="card-footer">
				<div class="media">
					<div class="media-left">
						<?php if ( $test['finalized'] == 0) { ?>
							<input type="checkbox" class="selectAll" id="selectAll">
							<label for="selectAll" class="control-label">Select All</label>
						<?php } ?>
					</div>
					<div class="media-body">
						<?php 
						if ( $test['finalized'] == 0) {
							echo form_submit (array ('name'=>'save', 'value'=>'Delete ', 'class'=>'btn btn-sm btn-danger'));
						} 
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
<?php } else { ?>

	<div class="alert alert-danger">
		<h4> <?php echo 'No questions added in test'; ?></h4>
		<p>You can <?php echo anchor ('coaching/tests/question_group_create/'.$coaching_id.'/'.$category_id.'/'.$test_id, 'Create Questions', array ('class'=>'btn btn-sm btn-primary') ); ?>  in this test.
		</p>
	</div>

<?php } ?>