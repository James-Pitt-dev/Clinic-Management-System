<?php
// Include the Doctor class and create a new instance
require_once('include/database.php');
require_once ('classes/Doctor.php');
include 'header.php';

// Check if user is logged in and redirect to login page if not
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || ($_SESSION['userType'] !== 'doctor')) {
    header('Location: login.php');
    exit;
}

$doctor = new Doctor($db);

// Get the user ID from the session variable
$doctorID = $_SESSION['userID'];

// If approved, continue with the dashboard page
?>
<html>
<head>
    <title>Doctor Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['userType']; ?>!</h1>
    <p>Doctor Dashboard:</p>
    <ul>
        <li><a href="createVisit.php">Add Visit Record</a></li>
        <li><a href="createPrescription.php">Add Prescription Record</a></li>
        <li><a href="createLabExam.php">Add Lab Exam Record</a></li>
        <li><a href="viewPatients.php">View Patients</a></li>
        <li><a href="viewLabExams.php">View Lab Results</a></li>
        <li><a href="viewPatientPrescriptions.php">View Patient Prescriptions</a></li>
        <li><a href="updateDoctor.php">Edit Profile</a></li>
        <li><a href="logOut.php">Logout</a></li>
    </ul>
</body>
</html>
