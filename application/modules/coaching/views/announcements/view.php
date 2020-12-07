<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="card">			
			<div class="card-body">
				<h4 class="card-title"><?php echo $result['title']; ?></h4>
				<hr>
				<p class="form-control-static" id="editor">
					<?php echo ($result['description']); ?>
					Loading....
				</p>
			</div>
		</div>
	</div>
</div>
<style>
p > img {
	width: 100%;
	overflow: auto;
}
</style>