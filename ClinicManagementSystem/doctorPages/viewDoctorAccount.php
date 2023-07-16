<?php
require 'include/database.php';
require 'classes/Staff.php';
include 'header.php';

require 'classes/Doctor.php';

// check if user is logged in and has a valid staff ID
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
    header('Location: logIn.php');
    exit();
}

if (isset($_GET['id'])) {
    $doctorID = $_GET['id'];
    $_SESSION['doctorID'] = $doctorID;
    echo 'using get';
} else if (isset($_SESSION['doctorID'])) {
    $doctorID = $_SESSION['doctorID'];
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
    $specialization = $_POST['Specialization'];
    $doctorID = $_POST['doctorID'];

    $doctor = new Doctor($db);
    if ($doctor->update($doctorID, $firstName, $lastName, $email, $phone, $specialization)) {
        echo 'Doctor updated successfully';
    } else {
        echo 'Error updating doctor';
    }
} else {
    echo 'Update button not pressed';
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Delete') {
    $doctorID = $_POST['doctorID'];

    $doctor = new Doctor($db);
    if ($doctor->delete($doctorID)) {
        echo 'Doctor deleted successfully';
    } else {
        echo 'Error deleting doctor';
    }
}

$staffID = $_SESSION['userID'];
//$doctorID = $_GET['id'];
$doctor = new Doctor($db);
$doctorData = $doctor->selectOne($doctorID);

$firstName = '';
$lastName = '';
$email = '';
$phone = '';
$specialization = '';

if ($doctorData !== false) {
$firstName = $doctorData['FirstName'];
$lastName = $doctorData['LastName'];
$email = $doctorData['Email'];
$phone = $doctorData['Phone'];
$specialization = $doctorData['Specialization'];
}

echo '<h1>Doctor Account</h1>';
echo '<button> <a href="userManagement.php">Back</a> </button>';
if ($doctorData !== false) {
    echo '<form action="viewDoctorAccount.php" method="post">';
    echo '<label for="FirstName">First Name:</label>';
    echo '<input type="text" id="FirstName" name="FirstName" required value="' . $firstName . '"><br><br>';
    echo '<label for="LastName">Last Name:</label>';
    echo '<input type="text" id="LastName" name="LastName" required value="' . $lastName . '"><br><br>';
    echo '<label for="Email">Email:</label>';
    echo '<input type="Email" id="Email" name="Email" required value="' . $email . '"><br><br>';
    echo '<label for="Phone">Phone:</label>';
    echo '<input type="text" id="Phone" name="Phone" required value="' . $phone . '"><br><br>';
    echo '<label for="Specialization">Specialization:</label>';
    echo '<input type="text" id="Specialization" name="Specialization" required value="' . $specialization . '"><br><br>';
    echo '<input type="hidden" name="doctorID" value="' . $doctorID . '">';
    echo '<input type="submit" name="submit" value="Update">';
    echo '<input type="submit" name="submit" value="Delete">';
    // return to staff dashboard
    echo '<button> <a href="userManagement.php">Cancel</a> </button>';
    echo '</form>';
} else {
    echo 'Doctor not found';
}
