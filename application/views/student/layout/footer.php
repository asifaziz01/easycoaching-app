			</div>
		</div>
	</main>
	
	<footer class="fixed-bottom mt-0">
		<nav class="bg-white border-top">
            <ul class="nav nav-tabs nav-justified ">
                <?php 
                $role_id = $this->session->userdata ('role_id');
                $footer_menu = $this->common_model->load_acl_menus ($role_id, 0, MENUTYPE_FOOTER);
                if (! empty ($footer_menu)) {
                    foreach ($footer_menu as $menu) {
                        $link = $menu['controller_path'].'/'.$menu['controller_nm'].'/'.$menu['action_nm'].'/'.$coaching_id.'/'.$member_id;
                        ?>
                        <li class="nav-item ">
                            <a class="nav-link px-0 border-top-0 rounded-0<?php echo ($this->router->fetch_class() == $menu['controller_nm'])?' active':'';?>" href="<?php echo site_url ($link); ?>">
                                <div class="<?php echo ($this->router->fetch_class() == $menu['controller_nm'])?'text-blue-400':'text-grey-600';?>"><?php echo $menu['icon_img']; ?></div>
                                <div class="<?php echo ($this->router->fetch_class() == $menu['controller_nm'])?'text-blue-400':'text-grey-600';?>"><?php echo $menu['menu_desc']; ?></div>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </nav>
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