<?php
require 'include/database.php';
require 'classes/Staff.php';
include 'header.php';

require 'classes/Patient.php';

// check if user is logged in and has a valid staff ID
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
    header('Location: logIn.php');
    exit();
}

if (isset($_GET['id'])) {
    $patientID = $_GET['id'];
    $_SESSION['patientID'] = $patientID;
    echo 'using get';
} else if (isset($_SESSION['patientID'])) {
    $patientID = $_SESSION['patientID'];
    echo 'using session';
} else {
    echo 'no key';
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Update') {
    echo 'Update button pressed';
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $homeAddress = $_POST['HomeAddress'];
    $patientID = $_POST['patientID'];
    $healthCardNumber = $_POST['HealthCardNumber'];

    $patient = new Patient($db);
    if ($patient->update($patientID, $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber)) {
        echo 'Patient updated successfully';
    } else {
        echo 'Error updating patient';
    }
} else {
    echo 'Update button not pressed';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Delete') {
    $patientID = $_POST['patientID'];

    $patient = new Patient($db);
    if ($patient->delete($patientID)) {
        echo 'Patient deleted successfully';
    } else {
        echo 'Error deleting patient';
    }
}
$staffID = $_SESSION['userID'];
$patient = new Patient($db);
$patientData = $patient->selectOne($patientID);

$firstName = '';
$lastName = '';
$email = '';
$phone = '';
$homeAddress = '';
$healthCardNumber = '';
$approved = '';

if ($patientData !== false) {
    $firstName = $patientData['FirstName'];
    $lastName = $patientData['LastName'];
    $email = $patientData['Email'];
    $phone = $patientData['Phone'];
    $homeAddress = $patientData['HomeAddress'];
    $healthCardNumber = $patientData['HealthCardNumber'];
    $approved = $patientData['Approval'];
    $approvedText = $approved ? 'Approved' : 'Not approved'; // ternary operator
}
echo '<h1>Patient Account</h1>';
echo '<button> <a href="userManagement.php">Back</a> </button>';
if ($patientData !== false) {
    echo '<form action="viewPatientAccount.php" method="post">';
    echo '<label for="FirstName">First Name:</label>';
    echo '<input type="text" id="FirstName" name="FirstName" required value="' . $firstName . '"><br><br>';
    echo '<label for="LastName">Last Name:</label>';
    echo '<input type="text" id="LastName" name="LastName" required value="' . $lastName . '"><br><br>';
    echo '<label for="Email">Email:</label>';
    echo '<input type="Email" id="Email" name="Email" required value="' . $email . '"><br><br>';
    echo '<label for="Phone">Phone:</label>';
    echo '<input type="text" id="Phone" name="Phone" required value="' . $phone . '"><br><br>';
    echo '<label for="HomeAddress">Home Address:</label>';
    echo '<input type="text" id="HomeAddress" name="HomeAddress" required value="' . $homeAddress . '"><br><br>';
    echo '<label for="HealthCardNumber">Health Card Number:</label>';
    echo '<input type="text" id="HealthCardNumber" name="HealthCardNumber" required value="' . $healthCardNumber . '"><br><br>';
    echo '<label for="Approved">Approved:</label>';
    echo '<input type="text" id="Approved" name="Approved" readonly required value="' . $approvedText . '"><br><br>';
    echo '<input type="hidden" name="patientID" value="' . $patientID . '">';
    echo '<input type="submit" name="submit" value="Update">';
    echo '<input type="submit" name="submit" value="Delete">';
    // button to return to user management page
    echo '<button> <a href="userManagement.php">Back</a> </button>';
    echo '</form>';
} else {
    echo 'Patient not found';
}

