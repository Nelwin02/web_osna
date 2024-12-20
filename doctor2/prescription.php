<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Prescription </title>
		
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

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Query to fetch doctor details from PostgreSQL
$sql = "SELECT doctor_name, doctor_image FROM doctor_log WHERE username = $1";
$result = pg_query_params($con, $sql, array($username));

if ($result) {
    $user = pg_fetch_assoc($result);
    if ($user) {
        $name = $user['doctor_name'];
        $image = $user['doctor_image'];
    } else {
        $name = "Unknown";
    }
} else {
    $name = "Unknown";
}
?>

            <?php


$sql = "SELECT username, name, address, contactnum, age, sex, date_created, status FROM patient_info ORDER BY id DESC";
$result = pg_query($con, $sql);
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
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Prescription</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="clerk_dash.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">View New Patient</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row align-items-center mb-4 justify-content-end">



<!-- Status Filter Buttons aligned to the right -->
<div class="col-md-6 col-lg-3 d-flex align-items-center justify-content-end">
   
</div>
            </div>
<!-- Consultations Table -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Patient Prescription Records</h4>
    </div>
    <div class="card-body">
        <!-- Filters and Search -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="input-group w-50">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light text-secondary">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control" 
                    placeholder="Search by ID, Name, or Address"
                >
            </div>

            <!-- Filter Buttons -->
            <div class="btn-group" role="group" aria-label="Status Filter">
                <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="no-record">
                    No Record
                </button>
                <button type="button" class="btn btn-outline-success filter-btn" data-filter="authorized">
                    Authorized
                </button>
                <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="all">
                    All
                </button>
            </div>
        </div>

        <?php
        // Get the current date
        $currentDate = date('Y-m-d');

        // SQL query to join tables and filter by the current date
        $sql = "
            SELECT 
                patient_info.username,
                patient_info.name,
                patient_info.address,
                patient_info.contactnum,
                patient_info.age,
                patient_info.sex,
                prediction.created_at
            FROM 
                patient_info
            INNER JOIN 
                prediction 
            ON 
                patient_info.username = prediction.username
            WHERE 
                DATE(prediction.created_at) = '$currentDate'
            ORDER BY 
                prediction.created_at DESC
        ";

        // Execute the query
        $predictionresult = pg_query($con, $sql);
        ?>

        <!-- First Table -->
        <table class="table table-bordered table-hover" id="patientTable">
            <thead class="thead-dark">
                <tr>
                    <th>P_ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                if ($result && pg_num_rows($result) > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        $status = isset($row['status']) && $row['status'] === 'pending' ? 'No Record' : 'Authorized';
                        $color = $status === 'No Record' ? 'grey' : 'green';

                        echo "<tr data-status='" . strtolower(str_replace(' ', '-', $status)) . "' data-pid='" . htmlspecialchars($row['username']) . "' data-name='" . htmlspecialchars($row['name']) . "' data-address='" . htmlspecialchars($row['address']) . "'>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['age']) . "</td>
                            <td>" . htmlspecialchars($row['sex']) . "</td>
                            <td class='text-" . ($color === 'grey' ? 'secondary' : 'success') . "'>" . $status . "</td>
                            <td>
                                <a href='view_prediction.php?username=" . urlencode($row['username']) . "' class='btn btn-info btn-sm' title='View Details'>
                                    <i class='fa fa-eye'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>No patients found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Predictions Table -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Today's Predictions</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="predictionTable">
            <thead class="thead-dark">
                <tr>
                    <th>P_ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Prediction Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($predictionresult && pg_num_rows($predictionresult) > 0) {
                    while ($row = pg_fetch_assoc($predictionresult)) {
                        $dateTime = new DateTime($row['created_at']);
                        $formattedDate = $dateTime->format('h:i A');

                        echo "<tr>
                            <td>" . htmlspecialchars($row['username']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['age']) . "</td>
                            <td>" . htmlspecialchars($row['sex']) . "</td>
                            <td>" . $formattedDate . "</td>
                            <td>
                                <a href='view_oldpatient.php?username=" . urlencode($row['username']) . "' class='btn btn-info btn-sm' title='View Details'>
                                    <i class='fa fa-eye'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#tableBody tr');
    const searchInput = document.getElementById('searchInput');

    function filterRowsByStatus(filter) {
        tableRows.forEach(row => {
            const status = row.getAttribute('data-status');
            row.style.display = (filter === 'all' || status === filter) ? '' : 'none';
        });
    }

    function searchRows(query) {
        const lowerQuery = query.toLowerCase();
        tableRows.forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const username = row.getAttribute('data-pid').toLowerCase();
            const address = row.getAttribute('data-address').toLowerCase();
            row.style.display = (name.includes(lowerQuery) || username.includes(lowerQuery) || address.includes(lowerQuery)) ? '' : 'none';
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterRowsByStatus(filter);
        });
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        if (query) {
            searchRows(query);
        } else {
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            filterRowsByStatus(activeFilter);
        }
    });

    document.querySelector('.filter-btn.active').click();
});
</script>








<style>

.placeholder-center::placeholder {
    text-align: center;
}

    .thead-dark th {
        background-color: #343a40;
        color: #ffffff;
    }
    #toggleRowsBtn {
        min-width: 100px;
    }
</style>




    <!-- /Page Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
