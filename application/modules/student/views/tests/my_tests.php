<div class="card card-default paper-shadow" data-z="0.5">
 	<div class="card-header">
		<h4 class="">Tests Taken</h4>
  	</div>
  	<ul class="list-group">
	  <?php
		$i = 0;
		if ( ! empty ($test_taken)) {
			foreach ($test_taken as $row) {
				$i++;
				?>
				<li class="list-group-item media v-middle">
				  <div class="media-body">
						<h4 class="text-subhead margin-none">
						  <?php echo $row['title']; ?>
						</h4>
						<div class="caption">
						  <span class="text-grey-500">Taken On:</span>
						  <span class="text-grey-500"><?php echo date('d M, Y H:i A', $row['loggedon']); ?></span> 
						</div>
						<p class="_btn-group">
							<?php 
							if ($row['submitted'] == 1 || ($row['submitted'] == 0 && $row['submit_time'] > 0)) {
								if ($row['release_result'] == RELEASE_EXAM_IMMEDIATELY) {
									echo anchor ('student/reports/test_report/'.$coaching_id.'/'.$member_id.'/'.$row['attempt_id'].'/'.$row['test_id'], 'Report', array ('class'=>'btn btn-default btn-sm '));
								}
								if ($row['attempts'] == 0  || $num_attempts < $row['attempts'] ) {
									echo anchor ('student/tests/take_test/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], 'Re-Take Test ', array('class'=>'btn btn-sm btn-primary ml-2'));
								}
							}
							?>
						</p>
					</div>
					<div class="media-right">
						<?php 
						if ($row['submitted'] == 1 || ($row['submitted'] == 0 && $row['submit_time'] > 0)) {
							if ($row['release_result'] == RELEASE_EXAM_IMMEDIATELY) {
								$pass_marks = ($row['pass_marks'] * $row['test_marks']) / 100;
								
								if ($row['obtained_marks'] < $pass_marks) {
									$result_class = 'text-danger';
									$result_text = 'Fail';
								} else {
									$result_class = 'text-success';
									$result_text = 'pass';
								}
								?>
								<div class="text-display-1 <?php echo $result_class; ?>"><?php echo $row['obtained_marks']; ?></div>
								<span class="caption <?php echo $result_class; ?>"><?php echo $result_text; ?></span>
								<?php
							} else {
								echo '<span class="badge badge-danger">Result will be released later</span>';
							}
						} else {
							echo '<span class="badge badge-danger">Not Submitted</span>';
						}
						?>
				  </div>
				</li>
				<?php
			}
		}
		?>
	</ul>
	<?php 
	if ($i == 0) {
		?>
		<div class="card-body">
			<div class="alert alert-danger">
				<p>You have not taken any tests yet</p>
			</div>
		</div>
		<?php
	}
	?>
</div>