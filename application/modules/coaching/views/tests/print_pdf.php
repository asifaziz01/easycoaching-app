
<!--
<div>Marks: <?php echo $test_marks['marks']; ?></div>

<?php 
	if ( is_array($results) ) {
		$num_parent = 1;
		foreach ( $results as $parent_id=>$questions) {
			$parent = $this->qb_model->getQuestionDetails ($parent_id);
			?>
			<table width="100%" border="0">
				<tr>
					<td colspan="2">
						<h4> Section <?php echo $num_parent; ?></h4>
						<?php echo $parent['question']; ?>
					</td>
				</tr>
				<?php 
				$num_question = 1;
				if ( ! empty($questions)) {
					foreach ($questions as $id) {
						$row = $this->qb_model->getQuestionDetails ($id);
						?>
						<tr>
							<td width="5%">
								<?php echo $num_question; ?>.
							</td>
							<td>
								<?php echo $row['question']; ?>
								<?php echo $this->qb_model->pdf_answer_choices($row['type'], $row); ?>
							</td>
						</tr>
						<?php
						$num_question++;
					}
				}
				?>
			</table>
			<?php
			$num_parent++;
		}
	} else { 
		?>	
		<div class="alert alert-danger">
			<h4> <?php echo 'No questions were found in this test'; ?></h4>
			<p>You need to <?php echo anchor('tests/admin/select_method/'.$category_id.'/'.$test_id, '<strong>Add Questions</strong>', array ('class'=>'btn btn-success') ); ?> in this test.
			</p>
		</div>
		<?php 
	} 
?>

-->