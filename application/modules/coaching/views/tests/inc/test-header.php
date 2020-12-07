<!DOCTYPE html>

<html class="st-layout ls-top-navbar-large show-sidebar <?php if (isset($hide_left_sidebar)) {} else { echo 'sidebar-l1'; } ?> <?php if (isset($sidebar)) echo 'sidebar-r3'; ?>" lang="en">



<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="description" content="">

  <meta name="author" content="">

  <title><?php echo SITE_TITLE; ?></title>



  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

  <link href="<?php echo base_url (TEMPLATE_PATH . 'assets/css/vendor/all.css'); ?>" rel="stylesheet">

  <link href="<?php echo base_url (TEMPLATE_PATH . 'assets/css/app/app.css'); ?>" rel="stylesheet">

  <link href="<?php echo base_url (TEMPLATE_PATH . 'assets/css/toastr.min.css'); ?>" rel="stylesheet">

  <link href="<?php echo base_url (TEMPLATE_PATH . 'assets/css/custom.css?ver='. time()); ?>" rel="stylesheet">

  <link rel="icon" href="<?php echo base_url (TEMPLATE_PATH . 'assets/images/fav-32x32.png'); ?>" sizes="32x32"/>

  <link rel="icon" href="<?php echo base_url (TEMPLATE_PATH . 'assets/images/fav-192x192.png'); ?>" sizes="192x192"/>

  <link rel="apple-touch-icon-precomposed" href="<?php echo base_url (TEMPLATE_PATH . 'assets/images/fav-180x180.png'); ?>"/>

  <meta name="msapplication-TileImage" content="<?php echo base_url (TEMPLATE_PATH . 'assets/images/fav-270x270.png'); ?>"/>

  <script src="<?php echo base_url (TEMPLATE_PATH . 'assets/js/vendor/all.js'); ?>"></script>
  <script src="<?php echo base_url (TEMPLATE_PATH . 'assets/js/countdown.js'); ?>"></script>


  <!--[if lt IE 9]>

	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



</head>



<body class="test-page">



	<!-- Wrapper required for sidebar transitions -->

	<div class="st-container">



    <!-- Fixed navbar -->

    <div class="navbar navbar-size-large navbar-default navbar-fixed-top" role="navigation">

      <div class="container-fluid">

        <div class="navbar-header">

          <div class="navbar-brand text-center navbar-brand-logo navbar-nav-padding-left">

			<?php if ($this->session->userdata ('admin_user') == true) { ?>

				<a class="svg" href="<?php echo site_url('home/admin/dashboard'); ?>">

			<?php } else { ?>			

				<a class="svg" href="<?php echo site_url('home/frontend/dashboard'); ?>">

			<?php } ?>

				<?php

					$sys_dir = $this->config->item ('sys_dir');

					$logo 	 = $this->config->item ('logo_file');

					$logo_path 	= $sys_dir . $logo;

					echo img ($logo_path, ['alt'=>SITE_TITLE, 'width'=>'100', 'height'=>'30', 'class'=>'its-logo img-responsive']);

				?>

				</a>

          </div>

        </div>



        <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse" id="main-nav">

		  

		  <ul class="nav navbar-nav">

			<li class="dropdown ">

				<a href="#">

				  

				</a>

			</li>

		  </ul>

		  

		  <div class="media">

			<div class="section-toolbar">

			<!-- Total Questions -->

			<div class="cell">

			  <div class="media width-120 v-middle margin-none">

				<div class="media-left">

				  <div class="icon-block bg-primary s30"><i class="fa fa-superscript"></i></div>

				</div>

				<div class="media-body">

				  <p class="text-body-2 text-light margin-none">Questions</p>

				  <p class="text-title text-primary margin-none"><?php echo $total_questions; ?></p>

				</div>

			  </div>

			</div>

			<!-- Answered -->

			<div class="cell">

			  <div class="media width-120 v-middle margin-none">

				<div class="media-left">

				  <div class="icon-block bg-default s30"><i class="fa fa-circle text-success"></i></div>

				</div>

				<div class="media-body">

				  <p class="text-body-2 text-light margin-none">Answered</p>

				  <p class="text-title text-success margin-none" id="review-answered">0</p>

				</div>

			  </div>

			</div>

			<!-- Not-Answered -->

			<div class="cell">

			  <div class="media width-120 v-middle margin-none">

				<div class="media-left">

				  <div class="icon-block bg-default s30"><i class="fa fa-circle text-warning"></i></div>

				</div>

				<div class="media-body">

				  <p class="text-body-2 text-light margin-none" style="font-size: 0.95em">Not Answered</p>

				  <p class="text-title text-warning margin-none" id="review-not-answered"><?php echo $total_questions; ?></p>

				</div>

			  </div>

			</div>

			<!-- Checked For Review -->

			<div class="cell">

			  <div class="media width-120 v-middle margin-none">

				<div class="media-left">

				  <div class="icon-block bg-default s30"><i class="fa fa-circle text-danger"></i></div>

				</div>

				<div class="media-body">

				  <p class="text-body-2 text-light margin-none">For Review</p>

				  <p class="text-title text-danger margin-none" id="review-for-review">0</p>

				</div>

			  </div>

			</div>

		  </div>

		  </div>



        </div>

        <!-- /.navbar-collapse -->



      </div>

    </div>	

    

	<?php if (isset($sidebar)) { ?>

		<div class="sidebar right sidebar-size-3 sidebar-offset-0 sidebar-visible-desktop sidebar-skin-white" id="sidebar-library">

			<?php echo $sidebar; ?>

		</div>

	<?php } ?>



	<!-- content push wrapper -->

    <div class="st-pusher" id="content">



      <!-- this is the wrapper for the content -->

      <div class="st-content">



        <!-- extra div for emulating position:fixed of the menu -->

        <div class="st-content-inner padding-top-none">