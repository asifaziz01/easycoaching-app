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
	
	<header class="bg-white border-bottom ">
        <div class="container">
            <nav class="navbar ">
                <!-- Sidebar Toggler -->
                <button class="navbar-toggle" type="button" id="toggle_sidebar_left">
                    <span class="icon-bar d-block bg-grey-500"></span>
                    <span class="icon-bar d-block bg-grey-500"></span>
                    <span class="icon-bar d-block bg-grey-500"></span>
                </button>
                <!-- /Sidebar Toggler -->

                <?php 
                if ($this->session->userdata ('site_title')) {
                    $title = $this->session->userdata ('site_title');
                } else if (isset($site_title)) {
                    $title = $site_title;
                } else {
                    $title = APP_NAME;
                }
                ?>
                <a class="navbar-brand link-text-color" href="<?php echo site_url($this->session->userdata ('dashboard')); ?>" title="<?php echo $title; ?>" >
                    <?php 
                        $logo_path = $this->session->userdata ('logo');
                        if (is_file($logo_path)) {
                            echo '<img src="'.$logo_path.'" alt="'.$title.'" class=" " width="120" height="" id="" />';
                        } else {
                            ?>
                            <h5 class=""><?php echo $title; ?></h5>
                            <?php
                        }
                    ?>                
                </a>

                <div class="profile-button">
                  <?php if ($this->session->userdata ('profile_image')) { ?>
                    <div class="dropdown ">
                      <a href="#" class=" dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo base_url ($this->session->userdata ('profile_image')); ?>" class="rounded-circle img-thumbnail" width="30"> 
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <p class="text-center">
                            <img src="<?php echo base_url ($this->session->userdata ('profile_image')); ?>" class="rounded-circle img-thumbnail" width="80"> 
                            <div class="text-center" href="#"><?php echo $this->session->userdata ('user_name'); ?></div>
                        </p>
                        <hr>
                        <a class="dropdown-item" onclick="logout_user ()"><i class="fa fa-sign-out-alt"></i> Logout</a>
                      </div>
                    </div>
                  <?php } ?>
                </div>

            </nav>
        </div>
    </header>

    <div class="bg-white shadow-sm  ">
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

                <div class="py-2 font-weight-bold">
                    <strong><?php if(isset($page_title)) echo $page_title; ?> </strong>
                </div>

                <div class="right-toolbar">
                    <?php if (! empty ($toolbar_buttons)) { ?>
                        <div class="dropdown show">
                          <a class="btn btn-success dropdown-toggler" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
	
	<!-- Sidebar left -->
	<div id="sidebar-left" class="sidebar sidebar-skin-white-blue left">
		<div class="sidebar-block">
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
                <div class="sidebar-text">
                    <?php echo $this->session->userdata ('user_name'); ?>
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
		</ul>

        <div class="sidebar-block">
            <?php if (isset ($access_code) && $access_code != '')  { ?>
                <div class="" id="installBanner" style="visibility: hidden;">
                    <button class="btn btn-success " id="installBtn"><i class="fab fa-android"></i> Install App</button>
                </div>
            <?php } ?>
        </div>

        <div class="sidebar-block">
            <a class="link-text-color " href="<?php echo BRANDING_URL; ?>"><?php echo BRANDING_TEXT; ?></a>
        </div>


	</div>
	<!--// Sidebar left -->

    <!-- Sidebar right -->
    <div id="sidebar-right" class="sidebar right sidebar-skin-blue">
        <div class="sidebar-block">            
            <div class="profile text-center">
                
            </div>
        </div>        
    </div>
    <!--// Sidebar right -->  

	<main id="content" role="main">
		<div class="container pt-4">          
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4 col-sm-8">
                  <?php $this->message->display (); ?>
                </div>
            </div>