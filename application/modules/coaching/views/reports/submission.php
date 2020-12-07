<div class="card card-default">
    <?php echo form_open('coaching/report_actions/delete_submissions/'.$coaching_id.'/'.$category_id.'/'.$test_id, array('class'=>'', 'id'=>'validate-1'));?>
		<ul class="list-group">
			<li class="list-group-item list-group-header media">
				<div class="media-left">
					<input id="checkAll" type="checkbox" onchange="check_all()" >
					#
				</div>
				<div class="media-body">
					<?php echo 'Name '; ?><br>
					<?php echo 'Test Taken On'; ?><br>
				</div>
				<div class="media-right">
					<?php echo 'Max Score'; ?>
				</div>
			</li>
			<?php
			if  ( ! empty ($submissions) ) {
				$i = 1;
				foreach($submissions as $row) {
					?>
					<li class="list-group-item list-group-header media">
						<div class="media-left">
							<?php echo form_checkbox (array('name'=>'users[]', 'value'=>$row['member_id'], 'class'=>'checks')); ?>
							<?php echo $i; ?>							
						</div>
						
						<div class="media-body">
							<?php 
							$user_name = $row['first_name'] . ' ' .$row['last_name'];
							if ($row['submitted'] == 1 || ($row['submitted'] == 0 && $row['submit_time'] > 0)) {
								echo anchor('coaching/reports/all_reports/'.$coaching_id.'/'.$row['attempt_id'].'/'.$row['member_id'].'/'.$test_id, $user_name);
							} else {
								echo $user_name;
							}
							?>
							<div><?php echo $row['adm_no'];?></div>
							<div><?php if ($row['sr_no'] != '') echo $row['sr_no']; ?></div>
							<div><?php echo date('d F, Y H:i a', $row['loggedon']);?></div>
							<div>
							<?php
								$attempt_time = $row['loggedon'];
								$submit_time = $row['submit_time'];
								if ($row['submitted'] == 1 || ($row['submitted'] == 0 && $row['submit_time'] > $attempt_time)) {
									$time_taken = $submit_time - $attempt_time;
									$hours = floor($time_taken / 3600);
									$mins = floor($time_taken / 60 % 60);
									$secs = floor($time_taken % 60);
									$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
									echo 'Time Taken: <span class="badge badge-success">'.$timeFormat.' secs</span>';
								}
								?>
							</div>
						</div>
						<div class="media-right">
							<?php 
							if ($row['submitted'] == 1 || ($row['submitted'] == 0 && $row['submit_time'] > $attempt_time)) {
								$pass_marks = ($row['pass_marks'] * $test_marks) / 100;
								
								if ($row['obtained_marks'] < $pass_marks) {
									$result_class = 'text-danger';
									$result_text = 'Fail';
								} else {
									$result_class = 'text-success';
									$result_text = 'pass';
								}									
								echo '<div class="text-display-1 '.$result_class.'">'.$row['obtained_marks'].'</div>';
								echo '<span class="caption '.$result_class.'">'.$result_text.'</span>';
							} else {
								echo '<span class="badge badge-danger">Not Submitted</span>';
							}
							?>
						</div>
					</li>
					<?php 
					$i++;
				}
			} else {
				?>
				<li class="list-group-item text-danger">
					No submissions
				</li>
				<?php
			}
			?>
		</ul>
		<div class="card-footer">
			<input type="submit" name="submit" class="btn btn-danger" value="Delete Attempts">
		</div>
	<?php echo form_close();?>

</div>