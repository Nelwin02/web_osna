<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>Doctor Dashboard</title>
		
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
		
		

		<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Prepare SQL query
$sql = "SELECT doctor_name, doctor_image FROM doctor_log WHERE username = $1";  // Use $1 for parameterized query

// Execute query with parameters
$result = pg_query_params($con, $sql, array($username));

if ($result) {
    $user = pg_fetch_assoc($result);  // Fetch associative array
    if ($user) {
        $name = $user['doctor_name'];
        $image = $user['doctor_image'];
    } else {
        $name = "Unknown";
        $image = "default.png"; // Default image if no user found
    }
} else {
    $name = "Unknown";
    $image = "default.png"; // Default image in case of query failure
}

// Close the PostgreSQL connection

?>

<?php
  

    // Query to count patients
    $sqlPatients = "SELECT COUNT(*) as count FROM patient_info";
    $resultPatients = pg_query($con, $sqlPatients);
    if ($resultPatients) {
        $countPatients = pg_fetch_assoc($resultPatients)['count'];
    } else {
        $countPatients = 0; // Default value if query fails
    }

    // Define a maximum threshold for progress calculation
    $maxPatients = 1000; // Change this to your desired maximum for the progress bar.
    $progressPercentage = ($countPatients / $maxPatients) * 100;
    $progressPercentage = $progressPercentage > 100 ? 100 : $progressPercentage; // Cap at 100%

    // Query to count prescriptions
    $sqlPrescriptions = "SELECT COUNT(*) as count FROM doctor_confirm WHERE prescription IS NOT NULL";
    $resultPrescriptions = pg_query($con, $sqlPrescriptions);
    if ($resultPrescriptions) {
        $countPrescriptions = pg_fetch_assoc($resultPrescriptions)['count'];
    } else {
        $countPrescriptions = 0; // Default value if query fails
    }

    // Define a maximum threshold for progress calculation
    $maxPrescriptions = 500; // Change this to your desired maximum for the progress bar.
    $progressPrescriptionPercentage = ($countPrescriptions / $maxPrescriptions) * 100;
    $progressPrescriptionPercentage = $progressPrescriptionPercentage > 100 ? 100 : $progressPrescriptionPercentage; // Cap at 100%

    // Query to count diagnoses
    $sqlDiagnoses = "SELECT COUNT(*) as count FROM doctor_confirm WHERE diagnosis IS NOT NULL";
    $resultDiagnoses = pg_query($con, $sqlDiagnoses);
    if ($resultDiagnoses) {
        $countDiagnoses = pg_fetch_assoc($resultDiagnoses)['count'];
    } else {
        $countDiagnoses = 0; // Default value if query fails
    }

    // Define a maximum threshold for progress calculation
    $maxDiagnoses = 500; // Change this to your desired maximum for the progress bar.
    $progressDiagnosisPercentage = ($countDiagnoses / $maxDiagnoses) * 100;
    $progressDiagnosisPercentage = $progressDiagnosisPercentage > 100 ? 100 : $progressDiagnosisPercentage; // Cap at 100%
?>
<?php
// Query to count new patients today
$sqlNewPatientsToday = "
SELECT COUNT(*) as count 
FROM patient_info 
WHERE DATE(date_created) = CURRENT_DATE";
$resultNewPatientsToday = pg_query($con, $sqlNewPatientsToday);
$newPatientsCountToday = ($resultNewPatientsToday) ? pg_fetch_assoc($resultNewPatientsToday)['count'] : 0;

// Query to get total patients with their cumulative counts
$sqlTotalPatientsGraph = "
SELECT DATE(date_created) as date, COUNT(*) as daily_count,
SUM(COUNT(*)) OVER (ORDER BY DATE(date_created)) as cumulative_count
FROM patient_info 
GROUP BY DATE(date_created)
ORDER BY DATE(date_created)";
$resultTotalPatientsGraph = pg_query($con, $sqlTotalPatientsGraph);

// Prepare data for the Total Patients graph
$dates = [];
$totalPatientCounts = [];
$dailyPatientCounts = [];
while ($row = pg_fetch_assoc($resultTotalPatientsGraph)) {
    $dates[] = $row['date']; // Dates for the graph
    $dailyPatientCounts[] = $row['daily_count']; // Daily patient counts
    $totalPatientCounts[] = $row['cumulative_count']; // Cumulative patient counts
}

?>

<?php
/*
$query = "
SELECT 
    p.patient_name,
    p.patient_id,
    v.weight,
    v.height,
    v.bloodpressure,
    v.heartrate
FROM 
    patients p
JOIN 
    (
        SELECT 
            patient_id,
            weight,
            height,
            bloodpressure,
            heartrate,
            date_added,
            ROW_NUMBER() OVER (PARTITION BY patient_id ORDER BY date_added DESC) AS row_num
        FROM 
            vitals
    ) v ON p.patient_id = v.patient_id
WHERE 
    v.row_num = 1;
";
*/
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
					<?php
    // Assuming your previous PHP logic is in place for counting patients, prescriptions, and diagnoses
    // And the necessary queries for patient count and progress percentage are already executed
?>

<!-- Begin Dashboard -->
<div class="container">
    <div class="row">
	<div class="col-xl-12">
            <div class="row">
                <!-- New Patients -->
				<div class="col-xl-3 col-sm-9 col-12">
                    <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary border-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <p><?php echo $newPatientsCountToday; ?></p>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">New Patients</h6>
                                <div class="progress progress-sm">
                                    <div 
                                        class="progress-bar bg-warning" 
                                        id="patientProgressBar" 
                                        style="width: <?php echo $progressPercentage; ?>%;" 
                                        data-count="<?php echo $newPatientsCountToday; ?>">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo round($progressPercentage); ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>

      
                <div class="col-xl-3 col-sm-9 col-12">
                <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary border-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <p><?php echo $countPatients; ?></p>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Patients</h6>
                                <div class="progress progress-sm">
                                    <div 
                                        class="progress-bar bg-warning" 
                                        id="patientProgressBar" 
                                        style="width: <?php echo $progressPercentage; ?>%;" 
                                        data-count="<?php echo $countPatients; ?>">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo round($progressPercentage); ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Prescriptions -->
                <div class="col-xl-3 col-sm-9 col-12">
                <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success border-success">
                                    <i class="fe fe-file"></i>
                                </span>
                                <div class="dash-count">
                                    <p><?php echo $countPrescriptions; ?></p>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Prescriptions</h6>
                                <div class="progress progress-sm">
                                    <div 
                                        class="progress-bar bg-info" 
                                        id="prescriptionProgressBar" 
                                        style="width: <?php echo $progressPrescriptionPercentage; ?>%;" 
                                        data-count="<?php echo $countPrescriptions; ?>">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo round($progressPrescriptionPercentage); ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Diagnoses -->
                <div class="col-xl-3 col-sm-9 col-12">
                <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger border-danger">
                                    <i class="fe fe-activity"></i>
                                </span>
                                <div class="dash-count">
                                    <p><?php echo $countDiagnoses; ?></p>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Diagnosis</h6>
                                <div class="progress progress-sm">
                                    <div 
                                        class="progress-bar bg-primary" 
                                        id="diagnosisProgressBar" 
                                        style="width: <?php echo $progressDiagnosisPercentage; ?>%;" 
                                        data-count="<?php echo $countDiagnoses; ?>">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo round($progressDiagnosisPercentage); ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
       

              
        <!-- Graphs Section -->
        <div class="col-xl-12">
            <div class="row">
                <!-- New Patients Graph -->
                <div class="col-xl-6 col-12">
                <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <h5 class="card-title">New Patients (Daily)</h5>
                            <canvas id="newPatientsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Total Patients Graph -->
                <div class="col-xl-6 col-12">
                <div class="card" style="background-color: white; border: 1px solid #ddd; ">
                        <div class="card-body">
                            <h5 class="card-title">Total Patients</h5>
                            <canvas id="totalPatientsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <!-- Vitals Section -->
<div class="col-xl-12">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h5 class="card-title mb-4">Current Vitals (Latest Records)</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-lg text-center">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Patient Name</th>
                            <th>P_ID</th>
                            <th>Weight (kg)</th>
                            <th>Height (cm)</th>
                            <th>Blood Pressure (mmHg)</th>
                            <th>Heart Rate (bpm)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($vitalsData)) : ?>
                        <?php foreach ($vitalsData as $vital) : ?>
                            <tr>
                                <td class="fw-bold"><?php echo htmlspecialchars($vital['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($vital['patient_username']); ?></td>
                                <td><?php echo !empty($vital['weight']) ? htmlspecialchars($vital['weight']) : 'N/A'; ?></td>
                                <td><?php echo !empty($vital['height']) ? htmlspecialchars($vital['height']) : 'N/A'; ?></td>
                                <td><?php echo !empty($vital['bloodpressure']) ? htmlspecialchars($vital['bloodpressure']) : 'N/A'; ?></td>
                                <td><?php echo !empty($vital['heartrate']) ? htmlspecialchars($vital['heartrate']) : 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-danger fw-bold">No records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data for New Patients (Current Date) Bar Chart
    var ctxNewPatientsToday = document.getElementById('newPatientsChart').getContext('2d');
    var newPatientsChart = new Chart(ctxNewPatientsToday, {
        type: 'bar',
        data: {
            labels: ['Today'], // Label for the current date
            datasets: [{
                label: 'New Patients Today',
                data: [<?php echo $newPatientsCountToday; ?>], // New patients count for today
                backgroundColor: 'rgba(75, 192, 192, 0.8)'
            }]
        },
        options: {
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Data for Total Patients Graph
    var ctxTotalPatients = document.getElementById('totalPatientsChart').getContext('2d');
    var totalPatientsChart = new Chart(ctxTotalPatients, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>, // Array of dates
            datasets: [{
                label: 'Cumulative Patients',
                data: <?php echo json_encode($totalPatientCounts); ?>, // Cumulative patient counts
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>


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