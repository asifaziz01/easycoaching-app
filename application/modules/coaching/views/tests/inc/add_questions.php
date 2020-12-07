<?php  
	if ( ! empty ($results)) { 
		$h = 1;
		foreach ($results as $question_heading=>$questions) {
			$heading = $this->qb_model->getQuestionDetails ($question_heading);
			?>
			<div class="">
				<table class="table table-no-inner-border table-hover">
					<thead>
						<tr>
							<th class="checkbox-column">
								<input type="checkbox" id="" class="uniform checks checkAll">
							</th>
							<th colspan="2">
								<?php 
								$heading = strip_tags ($heading['question']); 
								$heading = character_limiter ($heading, 150); 
								echo $h . ') ' . $heading;
								?> 
							</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					foreach ($questions as $row ) { 					
						/* Question added in Test */
						$num_tests = 0;
						$t = '';
						$show_tests = '';
						$tests = $this->qb_model->questionInAnyTest( $row['question_id'] );
						if ( is_array ($tests) ) {
							$num_tests = count($tests);
							foreach( $tests as $test ){
								$tt = $this->tests_model->view_tests($test['test_id']);
								if (is_array($tt)) {
									$t[] = $tt['title'];
								}
							}
							if (is_array($t)) {
								$show_tests  .= '<a href="javascript:void()" class="label label-warning bs-popover btn-block" data-trigger="hover" data-placement="top" data-original-title="Deployed in tests" data-content="
								';
									foreach ($t as $test_name) {
										$show_tests .= $test_name .', ';
									}
								$show_tests .= '" >Deployed</a>';
							}
						}
						/*=============================*/					
						$isQuestion = $this->tests_model->questionInTest($test_id, $row['question_id']);
						if ($isQuestion) 
							$trClass = 'success';
						else
							$trClass = '';
						?>
						<tr class="<?php echo $trClass; ?>">
							<td class="checkbox-column">							
								<?php 
								if ($isQuestion) {													
									echo '<i class="fa fa-ok"></i>';
								} else {	
									echo '<input type="checkbox" name="mycheck['.$row['question_id'].']"  value="'.$row['question_id'].'" class="checks">';
								}
								?>
							</td>
							<td>
								<!--
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#panel_<?php echo $row['question_id']; ?>">
								</a>
								-->
								<?php echo $row['question']; ?>
								<div >
								<?php 
								switch ($row['type']) {
								
									case QUESTION_MCMC:
										?>
										<ol>
										<?php 
										for ($i=1; $i <= 6; $i++) {
											$choice = strip_tags ($row['choice_'.$i]);
											if ($choice != '') {
												echo '<li>';
													echo $choice;
												echo '</li>';
											}
										} 
										?>
										</ol>
										<?php
									break;												
									
									case QUESTION_TF:
										?>
										<ul>
										<?php
											echo '<li>True</li>';
											echo '<li>False</li>';
										?>
										</ul>
										<?php
									break;
									
									case QUESTION_LONG:
										?>
										<ul>
										<?php 
										for ($i=1; $i <= 2; $i++) {
											$choice = strip_tags ($row['choice_'.$i]);
											if ($choice != '') {
												echo '<li>';
													echo $choice;
												echo '</li>';
											}
										} 
										?>
										</ul>
										<?php
									break;
									
									case QUESTION_MATCH:												
										for ($i=1; $i <= 2; $i++) {
											$choice = strip_tags ($row['choice_'.$i]);
											$option = strip_tags ($row['option_'.$i]);
											if ($choice != '' || $option != '') {
												echo '<div class="row">';
													echo '<div class="col-md-6">';
														echo $choice;
													echo '</div>';
													echo '<div class="col-md-6">';
														echo $option;
													echo '</div>';
												echo '</div>';
											}
										} 													
									break;
									
									default :
										?>
										<ol>
										<?php 
										for ($i=1; $i <= 6; $i++) {
											$choice = strip_tags ($row['choice_'.$i]);
											if ($choice != '') {
												if ($row['answer_'.$i] == $i) {
													$class = "text-success";
												} else {
													$class= "";
												}
												echo '<li class="'.$class.'">';
													echo $choice;
												echo '</li>';
											}
										} 
										?>
										</ol>
										<?php
									break;										
								}
								?>
								</div>
								<div class="row"></div>
								<?php
								// ---====---
								if ( ! empty ($tests) ) {
									$show_tests = "";
									$show_tests  .= '<a href="javascript:void()" class="label label-warning bs-popover btn-block" data-trigger="hover" data-placement="top" data-original-title="Deployed in tests" data-content="';
									foreach( $tests as $test ){
										$t = $this->tests_model->view_tests($test['test_id']);
										$show_tests .= $t['title'];
									}
									
									$show_tests .= '" >Deployed</a>';
									echo $show_tests;
								}
								?>
							</td>
							
							<td>
								<p class="pull-right">
								<?php 
								// ---=====---
								if ($isQuestion) {
									$message = 'Remove this question from test?';
									$url = site_url ('tests/functions/remove_question/'.$category_id.'/'.$test_id.'/'.$row['question_id'].'/'.$lesson_id.'/'.$cat_ids.'/'.$diff_ids.'/'.$exclude);
									?>
									<a href="javascript: void ()" onclick="show_confirm_ajax ('<?php echo $url; ?>', '<?php echo $message; ?>')" class="bs-tooltip " data-toggle="tooltip" data-placement="top" title="Delete"><i class="icon icon-trash"></i> </a>
									<?php
								}
								?>
								</p> 
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<br>
			<?php
			$h++;  
		}
		?>
		<div class="fixed" style="position:fixed;bottom:0;width:100%; padding-top:10px; padding-left:10px; background:lightgray;">
			<div class="row">
				<div class="col-md-12">
					<div id="validation-message"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<label class="radio"><input type="checkbox" id="select_all"> Select All</label>
				</div>
				<div class="col-md-6">
					<div class="btn-toolbar-demo"> 
					<?php 
					echo form_submit ( array ('name'=>'submit', 'value'=>'Save', 'class'=>'btn btn-success'));
					echo anchor ( 'tests/page/manage/'.$category_id.'/'.$test_id, 'Cancel', array ('class'=>'btn btn-default'));
					?>
					</div>
				</div>
			</div>
		</div>

	<?php	
	} else { 
		if ($lesson_id == 0) { ?>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-info">
						<p>Select lesson(s) from Lesson Tree to add questions</p>
					</div>
				</div>
			</div>		
		<?php } else { ?>
			<br>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger">
						<p>No questions found</p>
					</div>
				</div>
			</div>
			<?php 
		} 
	}
?>
<script>
$(document).ready(function(e) {
	/* SELECT ALL GROUP CHECKBOXES */
	$('.checkAll').on('click',function() {
		if ($(this).is(':checked')) {
			$(this).closest('div').find('input[type="checkbox"]').prop('checked','checked');
		} else {        
			$(this).closest('div').find('input[type="checkbox"]').prop('checked','');
		}
	});
	/* SELECT ALL GROUP CHECKBOXES */


	/* SELECT ALL CHECKBOXES */
	$("#select_all").change(function(){  //"select all" change
		$(".checks").prop('checked', $(this).prop("checked")); //change all ".checks" checked status
	});

	//".checks" change
	$('.checks').change(function(){
		//uncheck "select all", if one of the listed checks item is unchecked
		if(false == $(this).prop("checked")){ //if this item is unchecked
			$("#select_all").prop('checked', false); //change "select all" checked status to false
		}
		//check "select all" if all checks items are checked
		if ($('.checks:checked').length == $('.checks').length ){
			$("#select_all").prop('checked', true);
		}
	});
	/* //-SELECT ALL CHECKBOXES */

}); 
</script>