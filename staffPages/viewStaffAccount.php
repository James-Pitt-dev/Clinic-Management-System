<?php

require 'include/database.php';
require 'classes/Staff.php';
include 'header.php';

// check if user is logged in and has a valid staff ID
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
    header('Location: logIn.php');
    exit();
}

if (isset($_GET['id'])) {
    $newStaffID = $_GET['id'];
    $_SESSION['staffID'] = $newStaffID;
    echo 'using get';
} else if (isset($_SESSION['staffID'])) {
    $newStaffID = $_SESSION['staffID'];
    echo 'using session';
} else {
    echo 'no key';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Update') {
    echo 'Update button pressed';
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $newStaffID = $_POST['staffID'];


    $staff = new Staff($db);
    if ($staff->update($newStaffID, $firstName, $lastName, $email, $phone)) {
        echo 'Staff updated successfully';
    } else {
        echo 'Error updating staff';
    }
} else {
    echo 'Update button not pressed';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Delete') {
    $newStaffID = $_POST['staffID'];

    $staff = new Staff($db);
    if ($staff->delete($newStaffID)) {
        echo 'Staff deleted successfully';
    } else {
        echo 'Error deleting staff';
    }
}
$staffID = $_SESSION['userID'];
$staff = new Staff($db);
$staffData = $staff->selectOne($newStaffID);

$firstName = '';
$lastName = '';
$email = '';
$phone = '';


if ($staffData !== false) {
    $firstName = $staffData['FirstName'];
    $lastName = $staffData['LastName'];
    $email = $staffData['Email'];
    $phone = $staffData['Phone'];
}
echo '<h1>Staff Account</h1>';
echo '<button> <a href="userManagement.php">Back</a> </button>';
if ($staffData !== false) {
    echo '<form action="viewStaffAccount.php" method="post">';
    echo '<label for="FirstName">First Name:</label>';
    echo '<input type="text" id="FirstName" name="FirstName" required value="' . $firstName . '"><br><br>';
    echo '<label for="LastName">Last Name:</label>';
    echo '<input type="text" id="LastName" name="LastName" required value="' . $lastName . '"><br><br>';
    echo '<label for="Email">Email:</label>';
    echo '<input type="Email" id="Email" name="Email" required value="' . $email . '"><br><br>';
    echo '<label for="Phone">Phone:</label>';
    echo '<input type="text" id="Phone" name="Phone" required value="' . $phone . '"><br><br>';
    echo '<label for="HomeAddress">Home Address:</label>';
    echo '<input type="hidden" name="staffID" value="' . $newStaffID . '">';
    echo '<input type="submit" name="submit" value="Update">';
    echo '<input type="submit" name="submit" value="Delete">';
    //button to return to staff dashboard
    echo '<button> <a href="userManagement.php">Back</a> </button>';
    echo '</form>';
} else {
    echo 'Staff not found';
}

