		</div>    	
	</main>
	
	<footer class="light-footer mt-4">
		<div class="container text-center">
			<?php if (isset ($access_code) && $access_code != '')  { ?>
		        <div class="" id="installBanner" style="visibility: hidden;">
		            <button class="btn btn-success " id="installBtn"><i class="fab fa-android"></i> Install App</button>
		        </div>
		    <?php } ?>
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
	<script src="<?php echo base_url(THEME_PATH . 'assets/js/bootstrap-pincode-input.js'); ?>"></script>
	
	<!-- Default JS (Must be loaded befaore app.js) -->
	<script type="text/javascript" src="<?php echo base_url (THEME_PATH . 'assets/js/default.js'); ?>"></script>
	<!-- Application JS -->
	<script type="text/javascript" src="<?php echo base_url (THEME_PATH . 'assets/js/app.js?ver=1.4'); ?>"></script>
	<!-- Custom JS (Dynamically included) -->
	<?php
	if (isset ($script)) {
		echo $script;	
	}
	?>	
  </body>
</html>