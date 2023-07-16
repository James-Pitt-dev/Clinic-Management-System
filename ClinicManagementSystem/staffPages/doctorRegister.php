<?php
// This is the doctor registration page
include 'classes/Doctor.php';
require 'include/database.php';
include 'header.php';
unset($_SESSION['doctorID'], $_SESSION['email']);



// // Check if user is logged in and has a valid doctor ID
// if (isset($_SESSION['doctorID'])) {
//     header('Location: index.php');
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = new Doctor($db);
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $role = 'doctor';

    if ($doctor->insert($firstName, $lastName, $email, $phone, $specialization, $role)) {
        // Get the doctor ID from the insert_id property of the database object
          // session_start();
        $_SESSION['doctorID'] = $db->insert_id;
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
        header('Location: createDoctorPassword.php');
        exit();
    } else {
        echo 'Error registering new doctor';
    }
}
?>

<!DOCTYPE html>
<html>
   <head>
     <meta charset="UTF-8">
     <title>New Doctor Registration</title>
   </head>
   <body>
     <h1>New Doctor Registration</h1>
     <h3>(will redirect you to create a password on submit, user needs to manually log in after pass is set)</h3>
     <form action="doctorRegister.php" method="post">
       <label for="firstName">First Name:</label>
       <input type="text" id="firstName" name="firstName" required><br><br>

       <label for="lastName">Last Name:</label>
       <input type="text" id="lastName" name="lastName" required><br><br>

       <label for="email">Email:</label>
       <input type="email" id="email" name="email" required><br><br>

       <label for="phone">Phone:</label>
       <input type="text" id="phone" name="phone" required><br><br>

       <label for="">Role:</label>
       <input type="text" id="role" name="role" value ="doctor" disabled required><br><br>

       <label for="specialization">Specialization:</label>
       <input type="text" id="specialization" name="specialization" required><br><br>

       <input type="submit" value="Submit">
     </form>
   </body>
</html>
