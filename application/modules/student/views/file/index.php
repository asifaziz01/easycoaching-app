<nav class="nav">
	<button type="button" onclick="create_folder ()">New Folder</button>
</nav>

<div class="card">
	<div class="card-header"></div>
	<div class="card-body">
		<div id="fm_content">
			<?php 
			if ( ! empty ($directory)) { 
				foreach ($directory as $dir) {
					print_r ($dir);
					?>

					<?php
				}
			}
			?>
		</div>
	</div>	
</div>
<form action="/file-upload"
      class="dropzone"
      id="my-awesome-dropzone"></form>