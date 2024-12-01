<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Settings</title>
		
		<!-- Favicon -->
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/opd.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

		<?php
  session_start();
  include 'db.php'; 
  ?>

<?php
		if (!isset($_SESSION['username'])) {
			
			header("Location: login.php");
			exit();
		}
		
		$username = $_SESSION['username'];
		
					
			$sql = "SELECT name FROM doctor_log WHERE username = '$username'";
			$result = mysqli_query($con, $sql);

			if ($result) {
				$user = mysqli_fetch_assoc($result);
				if ($user) {
					
					$name = $user['name'];
				} else {
				
					$name = "Unknown";
				}
			} else {
				
				$name = "Unknown";
			}


			mysqli_close($con);
			?>
    </head>
    <body>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
                <div class="header-left">
                    <a href="" class="logo">
						<img src="assets/img/opd.png" alt="Logo">
					</a>
					<a href="" class="logo logo-small">
						<img src="assets/img/opd.png" alt="Logo" width="30" height="30">
					</a>
                </div>
				<!-- /Logo -->
				
				<a href="javascript:void(0);" id="toggle_btn">
					<i class="fe fe-text-align-left"></i>
				</a>
				
				<div class="top-nav-search">
					
				</div>
				
				<!-- Mobile Menu Toggle -->
				<a class="mobile_btn" id="mobile_btn">
					<i class="fa fa-bars"></i>
				</a>
				<!-- /Mobile Menu Toggle -->
				
				<!-- Header Right Menu -->
				<ul class="nav user-menu">

				
					
					<!-- User Menu -->
					<li class="nav-item dropdown has-arrow">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<span class="user-img">
					
						<img src="images/<?php echo htmlspecialchars($image); ?>" alt="Doctor Image" class="img-circle" />
					</span>

					<style>
						.user-img img.img-circle {
							width: 50px;        
							height: 50px;       
							border-radius: 50%; 
							object-fit: cover;  
						}

					</style>


						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
								<img src="images/<?php echo htmlspecialchars($image); ?>" alt="Doctor Image" class="img-circle" />
								</div>
								<div class="user-text">
								<h6><?php echo $username; ?></h6>
									<p class="text-muted mb-0" style="font-size: 13px;"><?php echo $name; ?></p>
								</div>
							</div>
							<a class="dropdown-item" href="profile.php">My Profile</a>
							<a class="dropdown-item" href="settings.php">Settings</a>
							<a class="dropdown-item" href="login.php">Logout</a>
						</div>
					</li>
					<!-- /User Menu -->
					
				</ul>
				<!-- /Header Right Menu -->
				
            </div>
			<!-- /Header -->
			<style>
				.fa {
					margin-right: 8px;
				}

			 </style>
			
	<!-- Sidebar -->
	<div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title"> 
								<span>Main</span>
							</li>
							<li class="active"> 
								<a href="doctor_dash2.php"><i class="fe fe-home"></i> <span>Dashboard</span></a>
							</li>
							<li class="submenu">
								<a href="#"><i class="fa fa-wheelchair"></i> <span>Prescription</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
								<li><a href="prescription.php"><i class="fa fa-info-circle"></i> &nbsp;Add Prescription</a></li>
								
								</ul>

								<li><a href="patient_list.php"><i class="fa fa-info-circle"></i> &nbsp; Patient List</a></li>
								<li>
							<a href="login.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
							</li>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->


			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome, <?php echo $name; ?></h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>



			<!-- jQuery -->
			<script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
</html>