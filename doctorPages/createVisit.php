<?php
require 'include/database.php';
include 'classes/Doctor.php';
include 'classes/Patient.php';
include 'classes/Visit.php';
include 'classes/Prescription.php';
include 'classes/LabExam.php';

include 'header.php';

// check if user is logged in and has a valid doctor ID
// if (!isset($_SESSION['doctorID'])) {
//     header('Location: index.php');
//     exit();
// }

// get all patients registered and approved
$patient = new Patient($db);
$patients = $patient->selectAllApproved();



 // handle form submission for creating visit record, prescription, and lab exam
 if ($_SERVER['REQUEST_METHOD'] === 'POST'){
     $visit = new Visit($db);
     $doctorID = $_SESSION['userID'];
     $patientID = $_POST['patientID'];
     // $date = $_POST['date'];
     $details = $_POST['details'];

     if ($visit->createVisit($patientID, $doctorID, $details)) {
       echo 'Visit recorded';
   } else {
       echo 'Error creating visit';
   }


 }

    ?>

<!DOCTYPE html>
<html>
   <head>
     <meta charset="UTF-8">
     <title>New Visit Record</title>
   </head>
   <body>
     <h1>New Visit Record</h1>
<br>
<h3>Select patient for record</h3>
 <h4>(Select from all patients who are approved, approval function is not set yet, you need to edit the column in database to approve a patient for now)</h4>
<form action="createVisit.php" method="post">
<label for="patientID">Patient:</label>
<select id="patientID" name="patientID" required>
   <?php foreach ($patients as $p) : ?>
       <option value="<?= $p['PatientID'] ?>">
           <?= $p['FirstName'] . ' ' . $p['LastName'] . ' ('.'ID:' . $p['PatientID'] . ')' ?>
       </option>
   <?php endforeach; ?>
</select>
<br><br>

<!-- Hidden fields for storing patient and doctor IDs -->
<input type="hidden" id="doctorID" name="doctorID" value="<?= $_SESSION['userID'] ?>">
<input type="hidden" id="patientID" name="patientID" value="<?= $p['PatientID'] ?>">

<!-- Visit inputs -->
<label for="date">Current Date:</label>
<input type="" id="" name="" disabled value="<?= date('Y-m-d') ?>" required>
<br><br>

<label for="details">Details:</label>
<textarea id="details" name="details" rows="4" cols="50"></textarea>
<br><br>

<input type="submit" value="Create Visit Record">
</form>
