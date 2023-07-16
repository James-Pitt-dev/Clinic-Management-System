<?php
//This is the Registration Page, upon successful patient entry, redirect to the Password creation page and pass along the patient entry ID to be used in it. Register -> CreatePassword -> Landing Page

unset($_SESSION['patientID'], $_SESSION['role'], $_SESSION['email']);

include 'classes/Patient.php';
require 'include\database.php';
include 'header.php';



// check if user is logged in and has a valid patient ID
// if (isset($_SESSION['patientID'])) {
//     header('Location: index.php');
//
//     exit();
// }

// Destroy the current session
// session_destroy();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient = new Patient($db);
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $homeAddress = $_POST['address'];
    $healthCardNumber = $_POST['healthCard'];
        $role = 'patient';

    if ($patient->insert($firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber, $role)) {
        // Get the patientID from the insert_id property of the database object

        $_SESSION['patientID'] = $db->insert_id;
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;
        header('Location: createPassword.php');
        exit();
    } else {
        echo 'Error registering new patient';
    }
}
?>

<!DOCTYPE html>
<html>
   <head>
     <meta charset="UTF-8">
     <title>New Patient Registration</title>
   </head>
   <body>
     <h1>New Patient Registration</h1>
     <h3>(will redirect you to create a password on submit, user needs to manually log in after pass is set)</h3>
     <form action="patientRegister.php" method="post">
       <label for="firstName">First Name:</label>
       <input type="text" id="firstName" name="firstName" required><br><br>

       <label for="lastName">Last Name:</label>
       <input type="text" id="lastName" name="lastName" required><br><br>

       <label for="email">Email:</label>
       <input type="email" id="email" name="email" required><br><br>

       <label for="phone">Phone:</label>
       <input type="text" id="phone" name="phone" required><br><br>

       <label for="address">Home Address:</label>
       <input type="text" id="address" name="address" required><br><br>

       <label for="healthCard">Health Card Number:</label>
       <input type="text" id="healthCard" name="healthCard" required><br><br>

       <label for="role">Role:</label>
       <input type="text" id="role" name="role" value="patient" disabled required><br><br>

       <input type="submit" value="Submit">
     </form>
   </body>
</html>
