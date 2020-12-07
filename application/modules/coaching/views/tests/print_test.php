<div class="row justify-content-center">
	<div class="col-md-12">
		<?php 
		if ( ! empty ($results) ) {
			$num_parent = 1;
			foreach ( $results as $parent_id=>$all_questions) {
				$parent 	= $all_questions['parent'];
				$questions 	= $all_questions['questions'];
				?>
				<div class="card mb-4 " oncopy="return false;" oncut="return false;" onpaste="return false;" onmousedown="return false;" onselectstart="return false;">
					<div class="card-header">
						<div class="">
							<label for="checkAll<?php echo $parent_id; ?>" class="">Section <?php echo $num_parent; ?></label>
						</div>

						<div class="d-flex justify-content-between">
						  <div class="">
							<?php
								echo $parent['question'];
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
										<label for="select<?php echo $id; ?>"><?php echo $num_question; ?>.</label>
									</div>

									<div class="media-body">
										<?php echo $row['question']; ?>
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
				</div>
				<?php
			}
			?>

		<?php } else { ?>
			<div class="alert alert-danger">
				<strong> <?php echo 'No questions found in test'; ?></strong>
			</div>
			<?php 
		} 
		?>
	</div>
</div>

<?php
	echo $answer_content;
?>

<br>
<?php if ($print) { ?>
	<script>
		window.print();
	</script>
<?php } ?>