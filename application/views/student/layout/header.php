<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="author" content="Inovexia Software Services">    
    <meta name="description" content="<?php echo SITE_TITLE; ?> ">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Easy Coaching App">
    <meta name="apple-mobile-web-app-title" content="Easy Coaching App">
    <meta name="theme-color" content="#4285F4">
    <meta name="msapplication-navbutton-color" content="#4285F4">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="<?php echo site_url (); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" sizes="128x128" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon128.png'); ?>">
    <link rel="apple-touch-icon" sizes="128x128" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon128.png'); ?>">
    <link rel="icon" sizes="192x192" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon192.png'); ?>">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon192.png'); ?>">
    <link rel="icon" sizes="256x256" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon256.png'); ?>">
    <link rel="apple-touch-icon" sizes="256x256" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon256.png'); ?>">
    <link rel="icon" sizes="384x384" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon384.png'); ?>">
    <link rel="apple-touch-icon" sizes="384x384" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon384.png'); ?>">
    <link rel="icon" sizes="512x512" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon512.png'); ?>">
    <link rel="apple-touch-icon" sizes="512x512" href="<?php echo base_url(THEME_PATH . 'assets/img/touch/app-icon512.png'); ?>">

	<title><?php if (isset($page_title)) echo $page_title . ': '; echo $this->session->userdata ('site_title'); ?></title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&display=swap" rel="stylesheet">
    <!-- 
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
     -->
    <!-- Bootstrap core CSS -->
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font-awesome CSS -->
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/fontawesome.min.css'); ?>" rel="stylesheet">
    <!-- Chart-Js CSS -->
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/chart.min.css'); ?>" rel="stylesheet">
    <!-- Toastr CSS -->
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/toastr.min.css'); ?>" rel="stylesheet">
	<!--  Custom stylesheet -->
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/essentials.min.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/dropzone.min.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(THEME_PATH . 'assets/css/style.css'); ?>" rel="stylesheet">
    <!-- Custom JS (Dynamically included) -->
	<?php
	if (isset ($script_header) && !empty ($script_header)) {
		foreach ($script_header as $script) {
		    echo '<script src="'.base_url($script).'" type="text/javascript"></script>';
		}
	}
	?>
    <link rel="icon" href="<?php echo base_url(THEME_PATH . 'assets/img/fav-icon.png'); ?>" type="image/png" sizes="512x512">
	<link rel="manifest" href="<?php echo base_url ('manifest.json'); ?>">

</head>
<body class="<?php if (isset($body_class)) echo $body_class; ?>">
	
	<header class="fixed-tops">
        <nav class="navbar navbar-dark bg-primary px-0">
            <div class="container">
                <!-- Sidebar Toggler -->
                <button class="navbar-toggle" type="button" id="toggle_sidebar_left">
                    <i class="fa fa-bars"></i>
                </button>
                <!-- /Sidebar Toggler -->

                <span class="nav-text font-oswald fs-3 absolute-center-h"><?php echo $this->session->userdata ('site_title'); ?></span>

                <div class="profile-button">
                  <?php 
                    $member_id = $this->session->userdata ('member_id');

                    if ($this->session->userdata ('profile_image')) { 
                    ?>
                    <div class="dropdown ">
                      <a href="#" class="dropdown-toggler" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo base_url ($this->session->userdata ('profile_image')); ?>" class="rounded-circle img-thumbnail" width="30"> 
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <p class="text-center">
                            <img src="<?php echo base_url ($this->session->userdata ('profile_image')); ?>" class="rounded-circle img-thumbnail" width="80"> 
                            <div class="text-center" href="#"><?php echo $this->session->userdata ('user_name'); ?></div>
                        </p>
                        <hr>
                        <a class="dropdown-item" href="<?php echo site_url ('student/users/my_account/'.$coaching_id.'/'.$member_id); ?>"><i class="fa fa-user"></i> My Account</a>
                        <a class="dropdown-item" href="<?php echo site_url ('student/users/my_password/'.$coaching_id.'/'.$member_id); ?>"><i class="fa fa-lock"></i> Change Password</a>
                        <a class="dropdown-item" href="#" onclick="logout_user ()"><i class="fa fa-sign-out-alt"></i> Logout</a>
                      </div>
                    </div>
                  <?php } ?>
                </div>

            </div>
        </nav>
        <div class="bg-white shadow-sm <?php echo (isset ($bc))? "position-relative":""; ?>">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div class="">
                        <?php 
                          if (isset ($bc)) {
                              $bc_link = current ($bc);
                              $bc_title  = key ($bc);
                              echo anchor ($bc_link, '<i class="fa fa-long-arrow-alt-left link-text-color"></i> ', array('class'=>'btn btn-link', 'title'=>'Back To '.$bc_title)); 
                          }
                        ?>
                    </div>

                    <div class="py-2 font-weight-bold <?php echo (isset ($bc))? "h-100 absolute-center-h":""; ?>">
                        <strong class="m-0"><?php if (isset($page_title)) echo $page_title; ?></strong>
                    </div>

                    <div class="right-toolbar">
                        <?php if (! empty ($toolbar_buttons)) { ?>
                            <div class="dropdown show">
                              <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-plus-circle"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <?php foreach ($toolbar_buttons as $title=>$url) { ?>
                                    <a class="dropdown-item" href="<?php echo site_url ($url); ?>"><?php echo $title; ?></a>
                                <?php } ?>
                              </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
	
	<!-- Sidebar left -->
	<div id="sidebar-left" class="sidebar sidebar-skin-white-blue left">
		<div class="sidebar-block  py-2">
            <div class="">
				<?php 
				$logo_path = $this->session->userdata ('logo');
				if (is_file($logo_path)) {
					echo '<img src="'.$logo_path.'" alt="'.$this->session->userdata ('site_title').'" class=" " width="120" height="" id="" />';
				} else {
                    ?>
					<h4 class="sidebar-heading"><?php echo $this->session->userdata ('site_title'); ?></h4>
                    <?php
				}
				?>
                <div class="text-grey-800">
                    <?php echo $this->session->userdata ('user_name'); ?>
                </div>
                <div class="text-grey-800">
                    <?php echo $this->session->userdata ('role_name'); ?>
                </div>
                <div class="text-grey-800">
                    <?php echo $this->session->userdata ('access_code'); ?>
                </div>
            </div>
    	</div>
        
		<ul class="sidebar-menu">
			<?php
			$main_menu = $this->session->userdata ('MAIN_MENU');
			$coaching_id = $this->session->userdata ('coaching_id');
            // Side-menu
            if (! empty ($main_menu)) {
                foreach ($main_menu as $menu) {
                    $link = $menu['controller_path'].'/'.$menu['controller_nm'].'/'.$menu['action_nm'].'/'.$coaching_id;
                    if ($this->session->userdata ('role_id') == USER_ROLE_STUDENT) {
                        $link .= '/'.$this->session->userdata ('member_id');
                    }
					?>
					<li class="">
						<a class="" href="<?php echo site_url($link); ?>">
							<?php echo $menu['icon_img']; ?>
							<?php echo $menu['menu_desc']; ?>
						</a>
					</li>
					<?php
				}
			}
			?>
			<li>
                <a class="enable-notification py-0 pr-0 text-left text-decoration-none btn btn-link" href="javascript:void(0);">
                    <span><i class="far fa-bell"></i> Enable Notification</span>
                </a>
            </li>
		</ul>

        <div class="sidebar-block">
            <div class="" id="installBanner" style="visibility: hidden;">
                <button class="btn btn-success " id="installBtn"><i class="fab fa-android"></i> Install App</button>
            </div>
        </div>

        <div class="sidebar-block">
            <a class="link-text-color " href="<?php echo BRANDING_URL; ?>"><?php echo BRANDING_TEXT; ?></a>
        </div>
	</div>
	<!--// Sidebar left -->

	<main id="content" role="main">
        <div class="py-4">
            <div class="container">
               <?php if (! empty ($annc)) { ?>
                  <?php foreach ($annc as $row) { ?>
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading d-inline"><?php echo anchor ('student/announcements/view/'.$coaching_id.'/'.$row['announcement_id'], $row['title'], array('class'=>'text-decoration-none') ); ?></h4>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <hr class="my-1">
                        <p class="mb-0"><?php //echo $row['description']; ?></p>
                    </div>
                  <?php } ?>
               <?php } ?>

                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4 col-sm-8">
                      <?php $this->message->display (); ?>
                    </div>
                </div>