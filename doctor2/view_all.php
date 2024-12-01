<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>View All Informations</title>
		
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
    include 'db.php'; // Database connection

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];

    // Query to fetch doctor details from PostgreSQL
    $sql = "SELECT doctor_name, doctor_image FROM doctor_log WHERE username = $1";
    $stmt = pg_prepare($con, "fetch_doctor_details", $sql);
    $result = pg_execute($con, "fetch_doctor_details", array($username));

    if ($result) {
        $user = pg_fetch_assoc($result);
        if ($user) {
            $name = $user['doctor_name'];
            $image = $user['doctor_image'];
        } else {
            $name = "Unknown";
            $image = ''; // You can set a default image if needed
        }
    } else {
        $name = "Unknown";
        $image = ''; // You can set a default image if needed
    }

    // Close the PostgreSQL connection
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
								<li><a href="prescription.php"><i class="fa fa-plus"></i> &nbsp;Add Prescription</a></li>
								
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
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">Prescription</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="clerk_dash.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Add Prescription</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                            <h2 class="text-center">
                                <hr style="border: 2px solid black; width: 100%;">
                            </h2>
                        </div>

                        <?php
// consult_patient.php

// Connect to PostgreSQL database
include('db.php');

// Retrieve username from URL
$username = isset($_GET['username']) ? $_GET['username'] : null;

if ($username) {
    // Fetch patient data from PostgreSQL database
    $sql = "SELECT * FROM patient_info WHERE username = $1";
    $stmt = pg_prepare($con, "fetch_patient_data", $sql);
    $result = pg_execute($con, "fetch_patient_data", array($username));

    $patient = pg_fetch_assoc($result);
    
    if ($patient) {
        ?>
        <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px;">

            <!-- Vital Signs History Table -->
            
              
                    <h3 class="mb-0" style="font-weight: 600;">Vital Signs List</h3>
                </div>
                <div class="card-body" style="background-color: #f9f9fb; padding: 20px;">
                                    <?php
                    // Fetch vital signs history from vitalsigns table
                    $sql = "SELECT * FROM vitalsigns WHERE username = $1 ORDER BY date_added DESC";
                    $stmt = pg_prepare($con, "fetch_vitals", $sql);
                    $result = pg_execute($con, "fetch_vitals", array($username));

                    // Function to format units for weight and height
                    function formatVitalSignUnits($value, $type) {
                        if ($type == 'weight') {
                            return $value . ' kg';
                        } elseif ($type == 'height') {
                            return $value . ' cm';
                        } elseif ($type == 'bloodpressure') {
                            return $value . ' mmHg';
                        } elseif ($type == 'heartrate') {
                            return $value . ' bpm';
                        }
                        return $value;
                    }

                    if (pg_num_rows($result) > 0) {
                        // Display vital signs history in a scrollable table
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-hover table-bordered" style="font-size: 14px;">';
                        echo '<thead class="thead-dark"><tr><th>Weight</th><th>Height</th><th>Blood Pressure</th><th>Heart Rate</th><th>Date</th></tr></thead>';
                        echo '<tbody>';
                        while ($vitals = pg_fetch_assoc($result)) {
                            echo '<tr>';
                            // Format weight and height with appropriate units
                            echo '<td>' . htmlspecialchars(formatVitalSignUnits($vitals['weight'], 'weight')) . '</td>';
                            echo '<td>' . htmlspecialchars(formatVitalSignUnits($vitals['height'], 'height')) . '</td>';
                            echo '<td>' . htmlspecialchars(formatVitalSignUnits($vitals['bloodpressure'], 'bloodpressure')) . '</td>';
                            echo '<td>' . htmlspecialchars(formatVitalSignUnits($vitals['heartrate'], 'heartrate')) . '</td>';
                            echo '<td>' . htmlspecialchars($vitals['date_added']) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                    } else {
                        echo "<p class='text-center text-muted'>No vital signs history available.</p>";
                    }
                    ?>


                    <br>
                    <br>

                


          
                    <h3 class="mb-0" style="font-weight: 600;">Prediction List</h3>
                </div>
                <div class="card-body" style="background-color: #f9f9fb; padding: 20px; font-size: 14px;">
                <?php
                    // Fetch prediction history from prediction table
                    $sql = "SELECT * FROM prediction WHERE username = $1 ORDER BY created_at DESC";
                    $stmt = pg_prepare($con, "fetch_predictions", $sql);
                    $result = pg_execute($con, "fetch_predictions", array($username));

                    // Function to sanitize text (removes unwanted keywords)
                    function sanitizeText($text) {
                        $keywords = ['Diagnosis:', 'Prescription:', 'Treatment:'];
                        return str_replace($keywords, "", $text);
                    }

                    if (pg_num_rows($result) > 0) {
                        // Display prediction history in a scrollable table
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-hover table-bordered" style="font-size: 14px;">';
                        echo '<thead class="thead-dark"><tr><th>Symptoms</th><th>Diagnosis</th><th>Prescription</th><th>Treatment</th></tr></thead>';
                        echo '<tbody>';
                        while ($prediction = pg_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars(sanitizeText($prediction['symptoms'])) . '</td>';
                            echo '<td>' . htmlspecialchars(sanitizeText($prediction['predicted_disease'])) . '</td>';
                            echo '<td>' . htmlspecialchars(sanitizeText($prediction['predicted_prescription'])) . '</td>';
                            echo '<td>' . htmlspecialchars(sanitizeText($prediction['predicted_treatment'])) . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody></table>';
                        echo '</div>';
                    } else {
                        echo "<p class='text-center text-muted'>No prediction history available.</p>";
                    }
                    ?>

                </div>
            </div>
        </div>

        <?php
    } else {
        echo "<div style='text-align: center; padding-top: 50px;'><p class='text-danger'>Patient not found.</p></div>";
    }
} else {
    echo "<div style='text-align: center; padding-top: 50px;'><p class='text-warning'>No patient selected.</p></div>";
}

// Close database connection
$con->close();
?>






<style>
    .container {
    background-color: #f8f9fa; /* Light background for better contrast */
    padding: 20px;
    border-radius: 10px; /* Rounded corners for a softer look */
}

.list-group-item {
    border: none; /* Remove border from list items */
    background-color: transparent; /* Transparent background */
}

.list-group-item a {
    text-decoration: none; /* Remove underline from links */
    color: #333; /* Dark text color for better readability */
}

.list-group-item a:hover {
    background-color: #e9ecef; /* Light gray on hover */
    border-radius: 5px; /* Rounded hover effect */
}

.btn {
    transition: background-color 0.3s, transform 0.3s; /* Add transition for smooth effect */
}

.btn:hover {
    background-color: #218838; /* Darker green on hover */
    transform: scale(1.05); /* Slightly enlarge the button */
}

</style>

 <!-- for displaying patient data  -->
   

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>