<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<link rel="shortcut icon" href="http://www.menlhk.go.id/assets/front/images/logo.jpg">
                <?php
                $title="";
				$direktorat="";
				$ditjen="Ditjen Penegakan Hukum Lingkungan Hidup dan Kehutanan";
                $dashboardLink="";
                
                $aplikasi = (isset($app))?$app:"senpi";
                $app =$aplikasi;
                    switch ($aplikasi){
						case "php": $title="Direktorat Penegakan Hukum Pidana";
									$direktorat = "Direktorat Penegakan Hukum Pidana";
                                     $dashboardLink = site_url("php/dashboardphp");
                            break;
                        case "senpi": $title="SENPI - PPH";
                                     $dashboardLink = site_url("dashboard");
							break;
						case "lk": $title="Sistem Informasi dan Monitoring Lembaga Konservasi Indonesia - SIMILKI";
							$dashboardLink = site_url("lemkon/dashboard");
							$ditjen="Ditjen Konservasi Sumber Daya Alam Ekosistem";
				   			break;
                        default : $title="Kementerian Lingkungan Hidup dan Kehutanan";
                            break;
                    }
                
                ?>
		<title><?php echo $title?></title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" />
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/chosen.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url()."html/"?>assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo base_url()."html/"?>assets/js/ace-extra.min.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo base_url()."html/"?>assets/js/html5shiv.min.js"></script>
		<script src="<?php echo base_url()."html/"?>assets/js/respond.min.js"></script>
		<![endif]-->
		
		<!-- GROCERY CRUD -->
		
		<!-- GROCERY CRUD -->
		
		<!--[if !IE]> -->
		<script src="<?php echo base_url()."html/"?>assets/js/jquery-2.1.4.min.js"></script>
		<script src="<?php echo base_url()."html/"?>assets/js/chosen.jquery.min.js"></script>
		<script src="<?php echo base_url()."html/"?>assets/js/loadingoverlay.min.js"></script>
		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo base_url()."html/"?>assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url()."html/"?>assets/js/bootstrap.min.js"></script>
		<style>
			.chosen-container{
				width: inherit !important;
			}
		</style>
	</head>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default          ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="<?echo base_url();?>" class="navbar-brand">
						<small>
							
							
							<div style="display: inline-block;"><img src="<?php echo base_url();?>/html/logo-lemkon.png" style="width: 50px;"></div>
							<div style="display: inline-block;"><?php echo $title?></div>
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
					<li>
					<a href="<?php echo site_url('login/logout')?>">
						<i class="ace-icon fa fa-power-off"></i>
						Logout
					</a>
					</li>
					</ul>
					<!--<ul class="nav ace-nav">
						<li class="grey dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-tasks"></i>
								<span class="badge badge-grey">4</span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-check"></i>
									4 Tasks to complete
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">Software Update</span>
													<span class="pull-right">65%</span>
												</div>

												<div class="progress progress-mini">
													<div style="width:65%" class="progress-bar"></div>
												</div>
											</a>
										</li>

										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">Hardware Upgrade</span>
													<span class="pull-right">35%</span>
												</div>

												<div class="progress progress-mini">
													<div style="width:35%" class="progress-bar progress-bar-danger"></div>
												</div>
											</a>
										</li>

										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">Unit Testing</span>
													<span class="pull-right">15%</span>
												</div>

												<div class="progress progress-mini">
													<div style="width:15%" class="progress-bar progress-bar-warning"></div>
												</div>
											</a>
										</li>

										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">Bug Fixes</span>
													<span class="pull-right">90%</span>
												</div>

												<div class="progress progress-mini progress-striped active">
													<div style="width:90%" class="progress-bar progress-bar-success"></div>
												</div>
											</a>
										</li>
									</ul>
								</li>

								<li class="dropdown-footer">
									<a href="#">
										See tasks with details
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important">8</span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-exclamation-triangle"></i>
									8 Notifications
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar navbar-pink">
										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
														New Comments
													</span>
													<span class="pull-right badge badge-info">+12</span>
												</div>
											</a>
										</li>

										<li>
											<a href="#">
												<i class="btn btn-xs btn-primary fa fa-user"></i>
												Bob just signed up as an editor ...
											</a>
										</li>

										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
														New Orders
													</span>
													<span class="pull-right badge badge-success">+8</span>
												</div>
											</a>
										</li>

										<li>
											<a href="#">
												<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
														Followers
													</span>
													<span class="pull-right badge badge-info">+11</span>
												</div>
											</a>
										</li>
									</ul>
								</li>

								<li class="dropdown-footer">
									<a href="#">
										See all notifications
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="green dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
								<span class="badge badge-success">5</span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-envelope-o"></i>
									5 Messages
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar">
										<li>
											<a href="#" class="clearfix">
												<img src="<?php echo base_url()."html/"?>assets/images/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Alex:</span>
														Ciao sociis natoque penatibus et auctor ...
													</span>

													<span class="msg-time">
														<i class="ace-icon fa fa-clock-o"></i>
														<span>a moment ago</span>
													</span>
												</span>
											</a>
										</li>

										<li>
											<a href="#" class="clearfix">
												<img src="<?php echo base_url()."html/"?>assets/images/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Susan:</span>
														Vestibulum id ligula porta felis euismod ...
													</span>

													<span class="msg-time">
														<i class="ace-icon fa fa-clock-o"></i>
														<span>20 minutes ago</span>
													</span>
												</span>
											</a>
										</li>

										<li>
											<a href="#" class="clearfix">
												<img src="<?php echo base_url()."html/"?>assets/images/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Bob:</span>
														Nullam quis risus eget urna mollis ornare ...
													</span>

													<span class="msg-time">
														<i class="ace-icon fa fa-clock-o"></i>
														<span>3:15 pm</span>
													</span>
												</span>
											</a>
										</li>

										<li>
											<a href="#" class="clearfix">
												<img src="<?php echo base_url()."html/"?>assets/images/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Kate:</span>
														Ciao sociis natoque eget urna mollis ornare ...
													</span>

													<span class="msg-time">
														<i class="ace-icon fa fa-clock-o"></i>
														<span>1:33 pm</span>
													</span>
												</span>
											</a>
										</li>

										<li>
											<a href="#" class="clearfix">
												<img src="<?php echo base_url()."html/"?>assets/images/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
												<span class="msg-body">
													<span class="msg-title">
														<span class="blue">Fred:</span>
														Vestibulum id penatibus et auctor  ...
													</span>

													<span class="msg-time">
														<i class="ace-icon fa fa-clock-o"></i>
														<span>10:09 am</span>
													</span>
												</span>
											</a>
										</li>
									</ul>
								</li>

								<li class="dropdown-footer">
									<a href="inbox.html">
										See all messages
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo base_url()."html/"?>assets/images/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									Jason
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="#">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>-->
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<!--<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="">
                                            
						<a href="<?php echo $dashboardLink?>">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>

					<!--<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								UI &amp; Elements
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Layouts
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="top-menu.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Top Menu
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Two Menus 1
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-2.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Two Menus 2
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="mobile-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Default Mobile Menu
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="mobile-menu-2.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Mobile Menu 2
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="mobile-menu-3.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Mobile Menu 3
										</a>

										<b class="arrow"></b>
									</li>
								</ul>
							</li>

							<li class="">
								<a href="typography.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Typography
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="elements.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Elements
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="buttons.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Buttons &amp; Icons
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="content-slider.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Content Sliders
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="treeview.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Treeview
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="jquery-ui.html">
									<i class="menu-icon fa fa-caret-right"></i>
									jQuery UI
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="nestable-list.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Nestable Lists
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Three Level Menu
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="#">
											<i class="menu-icon fa fa-leaf green"></i>
											Item #1
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="#" class="dropdown-toggle">
											<i class="menu-icon fa fa-pencil orange"></i>

											4th level
											<b class="arrow fa fa-angle-down"></b>
										</a>

										<b class="arrow"></b>

										<ul class="submenu">
											<li class="">
												<a href="#">
													<i class="menu-icon fa fa-plus purple"></i>
													Add Product
												</a>

												<b class="arrow"></b>
											</li>

											<li class="">
												<a href="#">
													<i class="menu-icon fa fa-eye pink"></i>
													View Products
												</a>

												<b class="arrow"></b>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Tables 1 </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
-->
						
					
					<li class="<?php
						$segmen = $this->uri->segment(2);
						$temp = explode("_",$segmen);						
						if($temp[0]=="monitoring")echo "active open";?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-pencil-square-o"></i>
							<span class="menu-text"> Monitoring Menu </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
<!-- --------------------------------------------------------------------------------------------------------------- -->                                        
                                        <?php if($app=="senpi"){?>
						<ul class="submenu">
							<li class="<?php
						if($this->uri->segment(2)=="monitoring_Amo")echo "active"
					?>">
								<a href="<?php echo site_url("main/monitoring_Amo")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Amunisi
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="monitoring_BAP")echo "active"
					?>">
								<a href="<?php echo site_url("main/monitoring_BAP")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									B A P
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="monitoring_Senpi")echo "active"
					?>">
								<a href="<?php echo site_url("main/monitoring_Senpi")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									SENPI
								</a>

								<b class="arrow"></b>
							</li>
							
							<li class="<?php
						if($this->uri->segment(2)=="monitoring_mutasiSenpi")echo "active"
					?>">
								<a href="<?php echo site_url("main/monitoring_mutasiSenpi")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Mutasi SENPI
								</a>

								<b class="arrow"></b>
							</li>

							<!--<li class="">
								<a href="wysiwyg.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Wysiwyg &amp; Markdown
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="dropzone.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Dropzone File Upload
								</a>

								<b class="arrow"></b>
							</li>-->
						</ul>
					</li>
					<?php } ?>
<!-- -------------------------------End Of monitoring menu senpi pph------------------------------------ -->                                        				

                                        <?php if($app=="php"){?>
						<ul class="submenu">
							<li class="<?php
						if($this->uri->segment(2)=="monitoring_profil_perusahaan")echo "active"
					?>">
								<a href="<?php echo site_url("php/monitoring_profil_perusahaan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Profil Tersangka
								</a>

								<b class="arrow"></b>
							</li>							

							<li class="<?php
						if($this->uri->segment(2)=="monitoring_kasus")echo "active"
					?>">
								<a href="<?php echo site_url("php/monitoring_kasus")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Monitoring Kasus
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="monitoring_barang_bukti")echo "active"
					?>">
								<a href="<?php echo site_url("php/monitoring_barang_bukti")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Barang Bukti
								</a>

								<b class="arrow"></b>
							</li>

							<!--li class="<?php 
						if($this->uri->segment(2)=="monitoring_pnbp")echo "active"
					?>">
								<a href="<?php echo site_url("php/monitoring_pnbp")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									P N B P
								</a>

								<b class="arrow"></b><?php ?>
							</li -->
							
							<!--li class="<?php
						if($this->uri->segment(2)=="monitoring_mutasiSenpi")echo "active"

						?>">
								<a href="<?php echo site_url("main/monitoring_mutasiSenpi")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Mutasi SENPI
								</a>

								<b class="arrow"></b>
							</li-->

							<!--<li class="">
								<a href="wysiwyg.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Wysiwyg &amp; Markdown
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="dropzone.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Dropzone File Upload
								</a>

								<b class="arrow"></b>
							</li>-->
						</ul>
					</li>
					<?php } ?>
<!-- --------------------------------------------- End of menu monitoring PSLH -------------------- -->
<!-- --------------------------------------------- menu monitoring LK ----------------------------- -->
<?php if($app=="lk"){?>
						<ul class="submenu">
							<li class="<?php
						if($this->uri->segment(2)=="monitoring_informasi_lk_umum")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/monitoring_informasi_lk_umum")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Informasi LK Umum
								</a>

								<b class="arrow"></b>
							</li>							

							<li class="<?php
						if($this->uri->segment(2)=="monitoring_investasi")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/monitoring_investasi")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Monitoring Investasi
								</a>

								<b class="arrow"></b>
							</li>							
							
							<li class="open">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>
									Monitoring Individu Satwa
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
								<li class="<?php
								if($this->uri->segment(2)=="monitoring_indeks_satwa")echo "active"
							?>">
										<a href="<?php echo site_url("lemkon/monitoring_indeks_satwa")?>">
											<i class="menu-icon"></i>
											Indeks Satwa Individu
										</a>

										<b class="arrow"></b>
									</li>
									<li class="<?php
										if($this->uri->segment(2)=="monitoring_individu_satwa")echo "active"
									?>">
										<a href="<?php echo site_url("lemkon/monitoring_individu_satwa")?>">
											<i class="menu-icon"></i>
											Data Individu Satwa
										</a>

										<b class="arrow"></b>
									</li>
									
									<li class="<?php
								if($this->uri->segment(2)=="monitoring_kelahiran_satwa")echo "active"

								?>">
										<a href="<?php echo site_url("lemkon/monitoring_kelahiran_satwa")?>">
											<i class="menu-icon"></i>
											Kelahiran Satwa
										</a>

										<b class="arrow"></b>
									</li>

									<li class="<?php
								if($this->uri->segment(2)=="monitoring_kematian_satwa")echo "active"

								?>">
										<a href="<?php echo site_url("lemkon/monitoring_kematian_satwa")?>">
											<i class="menu-icon"></i>
											Kematian Satwa
										</a>

										<b class="arrow"></b>
									</li>

									<li class="<?php 
								if($this->uri->segment(2)=="monitoring_perolehan_satwa")echo "active"
							?>">
										<a href="<?php echo site_url("lemkon/monitoring_perolehan_satwa")?>">
											<i class="menu-icon"></i>
											Perolehan Satwa
										</a>

										<b class="arrow"></b><?php ?>
									</li>

									<li class="<?php 
								if($this->uri->segment(2)=="monitoring_pelepasliaran")echo "active"
							?>">
										<a href="<?php echo site_url("lemkon/monitoring_pelepasliaran")?>">
											<i class="menu-icon"></i>
											Pelepasliaran
										</a>

										<b class="arrow"></b><?php ?>
									</li>
								</ul>
							</li>
						
							
								<li class="open">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Monitoring Satwa Berkelompok
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<ul class="submenu">
									
									<li class="<?php 
							if($this->uri->segment(2)=="monitoring_satwa_kelompok")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/monitoring_satwa_kelompok")?>">
										<i class="menu-icon"></i>
										Data Satwa Berkelompok
									</a>

									<b class="arrow"></b><?php ?>
									</li>
									<li class="<?php 
							if($this->uri->segment(2)=="monitoring_kelahiran_unindef")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/monitoring_kelahiran_unindef")?>">
										<i class="menu-icon"></i>
										Kelahiran Satwa Berkelompok
									</a>

									<b class="arrow"></b><?php ?>
									</li>
									<li class="<?php 
							if($this->uri->segment(2)=="monitoring_kematian_unindef")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/monitoring_kematian_unindef")?>">
										<i class="menu-icon"></i>
										Kematian Satwa Berkelompok
									</a>

									<b class="arrow"></b><?php ?>
									</li>
									<li class="<?php 
							if($this->uri->segment(2)=="monitoring_perolehan_unindef")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/monitoring_perolehan_unindef")?>">
										<i class="menu-icon"></i>
										Perolehan Satwa Berkelompok
									</a>

									<b class="arrow"></b><?php ?>
									</li>
									<li class="<?php 
							if($this->uri->segment(2)=="monitoring_pelepasliaran_unindef")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/monitoring_pelepasliaran_unindef")?>">
										<i class="menu-icon"></i>
										Pelepasliaran Satwa Berkelompok
									</a>

									<b class="arrow"></b><?php ?>
									</li>
								</ul>
							</li>
							
							

							<!--<li class="">
								<a href="wysiwyg.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Wysiwyg &amp; Markdown
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="dropzone.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Dropzone File Upload
								</a>

								<b class="arrow"></b>
							</li>-->
						</ul>
					</li>
					<?php } ?>
					
<!-- --------------------------------------------- End of menu monitoring LK ---------------------- -->
					
					<?php if(strtolower($hak_akses)=="admin"){?>                     
					<li class="<?php
						$segmen = $this->uri->segment(2);
						$temp = explode("_",$segmen);						
						if(($temp[0]=="mst")||($temp[0]=="master"))echo "active open";
					?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Data Master </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
                                                <?php
                                                if($app=="senpi"){
                                                ?>
						<ul class="submenu">
												
							<li class="<?php
						if($this->uri->segment(2)=="mst_instansi")echo "active"
					?>">
								<a href="<?php echo site_url("main/mst_instansi")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Data Instansi 
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="mst_kondisiBrg")echo "active"
					?>">
								<a href="<?php echo site_url("main/mst_kondisiBrg")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Kondisi Barang
								</a>

								<b class="arrow"></b>
							</li>
							<li class="<?php
						if($this->uri->segment(2)=="mst_jenisSenjata")echo "active"
					?>">
								<a href="<?php echo site_url("main/mst_jenisSenjata")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Jenis Senjata
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
<!-- ----------------------------------------End of Data Master Senpi PPH--------------------------- -->                                                
                                                    <?php } ?>

                                                    <?php
                                                if($app=="php"){
                                                ?>
						<ul class="submenu">
							<li class="<?php
						if($this->uri->segment(2)=="mst_kategori_perusahaan")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_kategori_perusahaan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Kategori Perusahaan
								</a>

								<b class="arrow"></b>
							</li>
							<?php if($app=="pslh"){						?>
							<li class="<?php
						if($this->uri->segment(2)=="mst_peran")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_peran")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Peran php
								</a>

								<b class="arrow"></b>
							</li><?php }?>
							<li class="<?php
						if($this->uri->segment(2)=="mst_tahapan")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_tahapan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Tahapan PHP
								</a>

								<b class="arrow"></b>
							</li>
                            <li class="<?php
						if($this->uri->segment(2)=="mst_tipologi_kasus")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_tipologi_kasus")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Tipologi Kasus
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="mst_satuan")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_satuan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Satuan Barang
								</a>

								<b class="arrow"></b>
							</li>
                                                        <?php if($hak_akses=="ADMIN"){?>
                                                        <li class="<?php
						if($this->uri->segment(2)=="mst_member")echo "active"
					?>">
								<a href="<?php 
								if($app=="lk"){
									echo site_url("lemkon/master_member");
								}
								else{
									echo site_url("php/mst_member");
								}
								 ?>
								">
									<i class="menu-icon fa fa-caret-right"></i>
									Member 
								</a>

								<b class="arrow"></b>
                                                        </li><?php }?>
                                                        <li class="<?php
						if($this->uri->segment(2)=="mst_upass")echo "active"
					?>">
								<a href="<?php echo site_url("php/mst_upass")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Ubah Password
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
						<?php } ?>
<!-- ----------------------------------------End of Data Master PSLH--------------------------- -->  
<?php
                                                if($app=="lk"){
                                                ?>
						<ul class="submenu">
							<li class="<?php
						if($this->uri->segment(2)=="master_bentuk_lk")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_bentuk_lk")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Bentuk LK
								</a>

								<b class="arrow"></b>
							</li>
							<?php if($app=="lk"){						?>
							<li class="<?php
						if($this->uri->segment(2)=="master_institusi_lk")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_institusi_lk")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Institusi LK
								</a>

								<b class="arrow"></b>
							</li><?php }?>

							<li class="<?php
						if($this->uri->segment(2)=="master_perolehan")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_perolehan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Perolehan
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_taksa")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_taksa")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Taksa
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_status_konservasi_satwa")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_status_konservasi_satwa")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Status Konservasi Satwa
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_kawasan_hutan")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_kawasan_hutan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Kawasan Hutan
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_status_hukum_satwa")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_status_hukum_satwa")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Status Hukum Satwa
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_satwa")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_satwa")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Satwa
								</a>

								<b class="arrow"></b>
							</li>

							<li class="<?php
						if($this->uri->segment(2)=="master_jenis_kelamin")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_jenis_kelamin")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Jenis Kelamin
								</a>

								<b class="arrow"></b>
							</li>
                            <li class="<?php
						if($this->uri->segment(2)=="master_kejadian")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_kejadian")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Kejadian
								</a>

								<b class="arrow"></b>
							</li>
							<li class="<?php
						if($this->uri->segment(2)=="master_sebab_mati")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_sebab_mati")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Penyebab Kematian
								</a>

								<b class="arrow"></b>
							</li>

							<!--li class="<?php
						if($this->uri->segment(2)=="master_perolehan")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_perolehan")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Master Perolehan
								</a>

								<b class="arrow"></b>
							</li -->
                                                        <?php if($hak_akses=="ADMIN"){?>
                                                        <li class="<?php
						if($this->uri->segment(2)=="master_member")echo "active"
					?>">
								<a href="<?php echo site_url("lemkon/master_member")?>">
									<i class="menu-icon fa fa-caret-right"></i>
									Member
								</a>

								<b class="arrow"></b>
                                                        </li><?php }?>
                                                        
						</ul>
						<?php } ?>
<!-- ----------------------------------------end of Data Master LK----------------------------- -->                                              
                                                    
					</li>
					
					<?php } ?>
					<li class="<?php
						if($this->uri->segment(2)=="mst_upass")echo "active"
						?>">
									<a href="<?php echo site_url("lemkon/mst_upass")?>">
										<i class="menu-icon fa fa-caret-right"></i>
										Ubah Password
									</a>
	
									<b class="arrow"></b>
								</li>
					</li> 
				                  
	                    
					<!--<li class="">
						<a href="widgets.html">
							<i class="menu-icon fa fa-list-alt"></i>
							<span class="menu-text"> Widgets </span>
						</a>

						<b class="arrow"></b>
					</li>

					<li class="">
						<a href="calendar.html">
							<i class="menu-icon fa fa-calendar"></i>

							<span class="menu-text">
								Calendar

								<span class="badge badge-transparent tooltip-error" title="2 Important Events">
									<i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i>
								</span>
							</span>
						</a>

						<b class="arrow"></b>
					</li>

					<li class="">
						<a href="gallery.html">
							<i class="menu-icon fa fa-picture-o"></i>
							<span class="menu-text"> Gallery </span>
						</a>

						<b class="arrow"></b>
					</li>

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-tag"></i>
							<span class="menu-text"> More Pages </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="profile.html">
									<i class="menu-icon fa fa-caret-right"></i>
									User Profile
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="inbox.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Inbox
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="pricing.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Pricing Tables
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="invoice.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Invoice
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="timeline.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Timeline
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="search.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Search Results
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="email.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Email Templates
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="login.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Login &amp; Register
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file-o"></i>

							<span class="menu-text">
								Other Pages

								<span class="badge badge-primary">5</span>
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="faq.html">
									<i class="menu-icon fa fa-caret-right"></i>
									FAQ
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="error-404.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Error 404
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="error-500.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Error 500
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="grid.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Grid
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="blank.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Blank Page
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>-->
				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="<?php echo $dashboardLink?>">Dashboard</a>
							</li>
							<!--
							<li>
								<a href="#">Other Pages</a>
							</li>
							<li class="active">Blank Page</li>-->
						</ul><!-- /.breadcrumb -->

						<!--<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->
					</div>

					<div class="page-content">
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
