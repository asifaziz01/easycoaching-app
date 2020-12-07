<div class="card card-default"> 
	<div class="card-footer border-top-0 border-bottom">
		<div class="d-flex justify-content-between text-center">
			<p class="nav-link mb-0">
				<span class="badge bg-success rounded-circle text-white height-50 width-50 d-flex align-items-center justify-content-center mx-auto"><?php echo $brief['correct']; ?></span>
				<span class="display mt-4">Correct</span>
			</p>		
			<p class="nav-link mb-0">
				<span class="badge bg-danger rounded-circle text-white height-50 width-50 d-flex align-items-center justify-content-center mx-auto"><?php echo $brief['wrong']; ?></span>
				<span class="display mt-4">Wrong</span>
			</p>		
			<p class="nav-link mb-0">
				<span class="badge bg-grey-500 rounded-circle text-white height-50 width-50 d-flex align-items-center justify-content-center mx-auto"><?php echo $brief['not_answered']; ?></span>
				<span class="display mt-4">Not Answered</span>
			</p>		
		</div>
	</div>

	<ul class="list-group">
		<li class="list-group-item media">
			<div class="media-left">#</div>  
			<div class="media-body"><?php echo 'Questions'; ?></div>
			<div class="media-right"><?php echo 'Score'; ?></div>
			<div class="media-right"><?php echo 'Response'; ?></div>
		</li>
		<?php
		$i = 1;
		if ( ! empty($response)) {
			foreach ($response as $type=>$row) {
				foreach ($row as $question) {
					?>
					<li class="list-group-item media">
						<div class="media-left">
							<?php echo $i; ?>
						</div>  
						<div class="media-body">
							<?php echo character_limiter ($question['question'], 150); ?>
						</div>
						<div class="media-right">
							<?php 
							if ($type == TQ_CORRECT_ANSWERED) {
								echo $question['marks'].'/'.$question['marks'];
							} else if ($type == TQ_WRONG_ANSWERED) {
								echo '0/'.$question['marks'];
							} else {
								echo '0/'.$question['marks'];
							}
							?>
						</div>

						<div class="media-right">
							<?php
								if ($type == TQ_CORRECT_ANSWERED) {
									echo '<span class="badge badge-success"><i class="fa fa-check fa-1x"></i></span>';
								} else if ($type == TQ_WRONG_ANSWERED) {
									echo '<span class="badge badge-danger"><i class="fa fa-times fa-1x"></i></span>';
								} else {
									echo '<span class="badge badge-secondary">Not Answered</span>';
								}
							?>
						</div>
					</li>
				<?php
				$i++;
			}
		}
	} 
	?>
	</ul>
</div>