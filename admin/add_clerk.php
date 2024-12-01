<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Add Clerk</title>
		
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
include 'db.php'; // Ensure this connects to your PostgreSQL database
?>

<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

try {
    // Fetch admin name
    $stmt = pg_prepare($con, "fetch_admin_name", "SELECT name FROM admin_log WHERE username = $1");
    $result = pg_execute($con, "fetch_admin_name", array($username));

    if ($result) {
        $user = pg_fetch_assoc($result);
        $name = $user['name'] ?? "Unknown";
    } else {
        $name = "Unknown";
    }
} catch (Exception $e) {
    $name = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_clerk'])) {
        // Handle clerk addition without image upload
        $username = pg_escape_string($con, $_POST['username']);
        $password = pg_escape_string($con, $_POST['password']);
        $clerk_name = pg_escape_string($con, $_POST['clerk_name']);

        // Check if username already exists
        $stmt = pg_prepare($con, "check_clerk_username", "SELECT username FROM clerk_log WHERE username = $1");
        $result = pg_execute($con, "check_clerk_username", array($username));

        if (pg_num_rows($result) > 0) {
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        } else {
            // Insert new clerk
            $stmt = pg_prepare($con, "insert_clerk", "INSERT INTO clerk_log (username, password, clerk_name) VALUES ($1, $2, $3)");
            if (pg_execute($con, "insert_clerk", array($username, $password, $clerk_name))) {
                echo "<script>alert('New Clerk added successfully');</script>";
            } else {
                echo "<script>alert('Error adding Clerk');</script>";
            }
        }
    }

    if (isset($_POST['submit'])) {
        // Handle clerk addition with image upload
        $username = pg_escape_string($con, $_POST['username']);
        $password = pg_escape_string($con, $_POST['password']);
        $clerk_name = pg_escape_string($con, $_POST['clerk_name']);
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = "../clerk/Images/" . basename($file_name);

        // Check if username already exists
        $stmt = pg_prepare($con, "check_clerk_username_with_image", "SELECT username FROM clerk_log WHERE username = $1");
        $result = pg_execute($con, "check_clerk_username_with_image", array($username));

        if (pg_num_rows($result) > 0) {
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        } else {
            // Insert clerk with image
            $stmt = pg_prepare($con, "insert_clerk_with_image", "INSERT INTO clerk_log (username, password, clerk_name, clerk_image) VALUES ($1, $2, $3, $4)");
            if (pg_execute($con, "insert_clerk_with_image", array($username, $password, $clerk_name, $file_name))) {
                if (move_uploaded_file($tempname, $folder)) {
                    echo "<script>alert('Clerk added and image uploaded successfully');</script>";
                } else {
                    echo "<script>alert('Clerk added but image not uploaded');</script>";
                }
            } else {
                echo "<script>alert('Error adding Clerk');</script>";
            }
        }
    }
}

// Close connection
pg_close($con);
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
							<span class="user-img"><img class="rounded-circle" src="./assets/img/profiles/profile.jpeg" width="31" alt="admin"></span>
						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
									<img src="./assets/img/profiles/profile.jpeg" alt="User Image" class="avatar-img rounded-circle">
								</div>
								<div class="user-text">
								<h6><?php echo $username; ?></h6>
									<p class="text-muted mb-0"><?php echo $name; ?></p>
								</div>
							</div>
							<a class="dropdown-item" href="profile.php">My Profile</a>
							<a class="dropdown-item" href="settings.php">Web Settings</a>
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
    margin-right: 8px; /* Add some space between the icon and text */
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
								<a href="admin_dash.php"><i class="fe fe-home"></i> <span>Dashboard</span></a>
							</li>
							<li class="submenu">
								<a href="#"><i class="fa fa-wheelchair"></i> <span>Manage Patient</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
								<li><a href="manage_patient.php"><i class="fa fa-info-circle"></i>Patient Information</a></li>
								<li><a href="patient.php"><i class="fa fa-stethoscope"></i>Health Records</a></li>
								</ul>
								<li class="submenu">
									<a href="#"><i class="fa fa-user-md"></i> <span>Manage Doctors</span> <span class="menu-arrow"></span></a>
									<ul style="display: none;">
									<li><a href="add_doctor.php"><i class="fa fa-user-plus"></i> Add Doctor</a></li>
									<li><a href="doctor.php"><i class="fa fa-user-md"></i> Doctor List</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="#"><i class="fa fa-user"></i> <span>Manage Clerk</span> <span class="menu-arrow"></span></a>
									<ul style="display: none;">
									<li><a href="add_clerk.php"><i class="fa fa-user-plus"></i> Add Clerk</a></li>
									<li><a href="clerk.php"><i class="fa fa-user-md"></i> Clerk List</a></li>
									</ul>
								</li>
							<li> 
							<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

							<li class="submenu">
                                <a href="#"><i class="fas fa-bullhorn"></i> <span>Announcement</span> <span class="menu-arrow"></span></a>
                                <ul style="display: none;">
                                <li><a href="add_announcement.php"><i class="fas fa-plus"></i>  Add Announcement</a></li>
                                
                                </ul>
                            </li>
	
							<li> 
								<a href="settings.php"><i class="fe fe-vector"></i> <span>Web Settings</span></a>
							</li>
							
							<li> 
								<a href="profile.php"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
							</li><br><br><br><br><br><br><br><br>
							<li> 
							<a href="login.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
							</li>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->

			<div class="row justify-content-end">
    <div class="container mt-5">
       
    </div>
</div>

<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Clerk</h4>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="clerk_name">Clerk's Name:</label>
                                <input type="text" class="form-control" id="clerk_name" name="clerk_name" required>
                            </div>

                            <div class="form-group">
                                <label for="image">Upload Image:</label>
                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="submit">Add Clerk</button>
                        </form>
                    </div>
                </div>
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

<script src="assets/plugins/raphael/raphael.min.js"></script>    
<script src="assets/plugins/morris/morris.min.js"></script>  
<script src="assets/js/chart.morris.js"></script>

<!-- Custom JS -->
<script  src="assets/js/script.js"></script>

</body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:34 GMT -->
</html>