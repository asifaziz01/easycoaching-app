		</div>    	
	</main>
	
	<footer class="light-footer mt-4">
		<div class="container">
		  <?php if (! $this->session->userdata('is_logged_in')) { ?>
			<p class="text-center">
				Having trouble logging-in? <br>
			  <a class="link-text" href="<?php echo site_url ('public/page/reset'); ?>"> Click Here To Reset and Start Over</a>
			</p>
		  <?php } ?>
		  <div class="d-flex justify-content-center">
			<p class="footer-info">
			  <a class="link-text-color" href="<?php echo BRANDING_URL; ?>"><?php echo BRANDING_TEXT; ?></a>
			</p>
		</div>
	</footer>

	<div id="loader">
		<img src="<?php echo base_url (THEME_PATH . 'assets/img/loader.gif'); ?>" width="30" height="30">
	</div>

    <!-- Core -->
    <script src="<?php echo base_url(THEME_PATH . 'assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url(THEME_PATH . 'assets/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- Toastr JS for notifications -->
	<script type="text/javascript" src="<?php echo base_url (THEME_PATH . 'assets/js/toastr.min.js'); ?>"></script>
	<!-- ChartJS -->
	<script type="text/javascript" src="<?php echo base_url (THEME_PATH . 'assets/js/chart.bundle.min.js'); ?>"></script>
	<!-- Application JS -->
	<script type="text/javascript" src="<?php echo base_url (THEME_PATH . 'assets/js/app.js'); ?>"></script>
	<!-- Custom JS (Dynamically included) -->
	<?php
	if (isset ($script)) {
		echo $script;	
	}
	?>	
  </body>
</html>