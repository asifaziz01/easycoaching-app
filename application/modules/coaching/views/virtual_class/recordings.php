<div class="card">
	<ul class="list-group">
	<?php
	$i = 0;
	if ($response == 'SUCCESS') {
		foreach ($recordings->recording as $recording) {
			$i++;
			$start_time = floatval ($recording->startTime);
			$end_time = floatval ($recording->endTime);
			$duration = ($end_time - $start_time)/1000;
			$duration_mm = round ($duration / 60,  2);
			$url = $recording->playback->format->url;
			$time = '';
			$time .= date ('d F, Y', ($start_time/1000));
			$time .= ' at ';
			$time .= date ('h:i A', ($start_time/1000));
			?>
			<li class="list-group-item media">
				<div class="media-left">
					<?php echo $i; ?>.
				</div>
				<div class="media-body">
					<a href="<?php echo $url; ?>" target="_blank"><?php echo $class['class_name']; ?></a>
					<p>
						<span class="badge badge-default">Time</span> <?php echo $time; ?><br>
						<span class="badge badge-default">Duration</span> <?php echo $duration_mm . ' minutes'; ?>
					</p>
				</div>

			</li>
			<?php
		}
	} 
	if ($i == 0) {
		?>
		<li class="list-group-item text-danger">No rcordings found</li>
		<?php
	}
	?>
	</ul>
</div>