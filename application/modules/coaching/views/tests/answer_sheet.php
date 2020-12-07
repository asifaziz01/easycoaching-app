<!--=== Normal ===-->

<div class="widget box">
	<div class="widget-header">
		<h4>Answer Sheet</h4>
	</div>
	<div class="widget-content">
		<?php 
		if (! empty ($results) ) {
			$a = 1;
			$time = 0;
			foreach ( $results as $id) { 
				$row_items = $this->qb_model->getQuestionDetails ($id);			
					// question numbering
					?> 
					<div class="row">
						<div  class="col-xs-1">
						<?php
							echo $a .'.';
							$a++;
						?>
						</div>
						<div  class="col-xs-11">						
							<?php 
							if ($row_items['type'] == QUESTION_MATCH) echo '<u><strong>COLOUMN A</strong></u>';  
							for ($i=1; $i <= 6; $i++) { 
								if ( $row_items['choice_'.$i] != "" && $row_items['type'] != QUESTION_LONG) { 
									if ($row_items['answer_'.$i] == $i) { 
										echo $row_items['choice_'.$i]; 
									}
								} else if ($row_items['choice_'.$i] != "" && $row_items['type'] == QUESTION_LONG) { ?>
								<?php }	?>
							<?php }	?>							
						</div>
					</div>
				<?php }	?>
		<?php }  else { ?>
			<div class="alert alert-danger col-md-12">
				<p> <strong>Error!</strong> <?php echo 'No questions found'; ?></p>
			</div>
		<?php } ?>
	</div>
</div>