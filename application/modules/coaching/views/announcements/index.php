<div class="card card-default">
	<ul class="list-group">
	<?php
		$i = 1;
		if (!empty($results)) {
			foreach ($results as $row) {
				?>
				<li class="list-group-item media announcement" data-notifytitle="<?php echo $row['title']; ?>" data-notifycontent="<?php //echo addslashes($row['description']); ?>" data-notifylink="<?php echo "student/announcements/view/".$row['coaching_id']."/".$row['announcement_id']; ?>">
					<div class="media-left align-middle"><?php echo $i; ?></div>
					<div class="media-body">
						<?php echo anchor('coaching/announcements/create_announcement/' . $coaching_id . '/' . $row['announcement_id'], $row['title']); ?>
						<p class="text-muted">
							Availability: From <?php echo date('d M, Y', $row['start_date']); ?> To <?php echo date('d M, Y', $row['end_date']); ?>
						</p>
					</div>
					<div class="media-right align-middle">
						<a href="javascript:void(0)" class="btn btn-success p-0 height-30 width-30 rounded-circle d-flex align-items-center justify-content-center send-notification" data-toggle="tooltip" data-placement="left" title="Send Notification">
							<i class="far fa-bell"></i>
						</a>
					</div>
					<div class="media-right align-middle">
						<div class="d-block">
							<a href="javascript:void(0)" class="btn btn-danger p-0 height-30 width-30 rounded-circle d-flex align-items-center justify-content-center" onclick="show_confirm ('<?php echo 'Are you sure want to delete this announcement?'; ?>','<?php echo site_url('coaching/announcement_action/delete/' . $coaching_id . '/' . $row['announcement_id']); ?>' )">
								<i class="fa fa-trash"></i>
							</a>
						</div>
					</div>
				</li>
				<?php
$i++;
	}
} else {
	?>
			<li class="list-group-item text-danger">No announcements</li>
			<?php
}
?>
	</ul>
</div>

