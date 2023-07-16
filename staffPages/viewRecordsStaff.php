<?php
// Start the session
require 'include/database.php';
require 'classes/Doctor.php';
require 'classes/Patient.php';
require 'classes/Visit.php';
require 'classes/Prescription.php';
require 'classes/LabExam.php';
include 'header.php';

// Check if user is logged in as staff
if ($_SESSION['userType'] !== 'staff') {
  header('Location: index.php');
  exit();
}

$staffID = $_SESSION['userID'];

// Select all prescriptions
$prescription = new Prescription($db);
$prescriptions = $prescription->selectAll();

// Select all lab exams
$labExam = new LabExam($db);
$exams = $labExam->selectAll();

// Select all visits
$visit = new Visit($db);
$visits = $visit->selectAll();

// select type from drop down
if (isset($_POST['submit']) && $_POST['submit'] === 'Select') {
  $type = $_POST['type'];
}
echo '<h1>View Records</h1>';
//label for select type
echo '<label for="type">Select Records:</label>';
// create drop down menu to select type
echo '<form action="viewRecordsStaff.php" method="post">';
echo '<select name="type">';
echo '<option value="prescription">Prescription</option>';
echo '<option value="labExam">Lab Exam</option>';
echo '<option value="visit">Visit</option>';
echo '</select>';
echo '<input type="submit" name="submit" value="Select">';
//button to return to staff dashboard page
echo '<a href="staffDashboard.php"><button type="button">Return to Dashboard</button></a>';
echo '</form>';

// Display records based on type
if (isset($type)) {
  if ($type === 'prescription') {
      echo '<h2>Prescriptions</h2>';
      if (empty($prescriptions)) {
          echo 'No prescriptions.';
      } else {
          echo '<table>';
          echo '<thead>';
          echo '<tr>';
          echo '<th>Prescription ID</th>';
          echo '<th>Visit ID</th>';
          echo '<th>Medication</th>';
          echo '<th>Quantity</th>';
          echo '<th>Dosage</th>';
          echo '<th>Refillable</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';
          foreach ($prescriptions as $prescription) {
              echo '<tr>';
              echo '<td>' . $prescription['PrescriptionID'] . '</td>';
              echo '<td>' . $prescription['VisitID'] . '</td>';
              echo '<td>' . $prescription['Medicine'] . '</td>';
              echo '<td>' . $prescription['Quantity'] . '</td>';
              echo '<td>' . $prescription['Dose'] . '</td>';
              echo '<td>' . $prescription['Refillable'] . '</td>';
              echo '</tr>';
          }
          echo '</tbody>';
          echo '</table>';
      }
  } elseif ($type === 'labExam') {
    echo '<h2>Lab Exams</h2>';
    if (empty($exams)) {
        echo 'No lab exams.';
    } else {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Lab Exam ID</th>';
    echo '<th>Patient ID</th>';
    echo '<th>Doctor ID</th>';
    echo '<th>Visit ID</th>';
    echo '<th>Date</th>';
    echo '<th>Lab Exam Item</th>';
    echo '<th>Lab Exam Results</th>';
    echo '<th>Normal Range</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($exams as $exam) {
        echo '<tr>';
        echo '<td>' . $exam['LabExamID'] . '</td>';
        echo '<td>' . $exam['PatientID'] . '</td>';
        echo '<td>' . $exam['DoctorID'] . '</td>';
        echo '<td>' . $exam['VisitID'] . '</td>';
        echo '<td>' . $exam['Date'] . '</td>';
        echo '<td>' . $exam['ExamItem'] . '</td>';
        echo '<td>' . $exam['Result'] . '</td>';
        echo '<td>' . $exam['NormalRange'] . '</td>';
        echo '</tr>';
        }
    echo '</tbody>';
    echo '</table>';
    }
  } elseif ($type === 'visit') {
      echo '<h2>Visits</h2>';
      if (empty($visits)) {
          echo 'No visits.';
      } else {
          echo '<table>';
          echo '<thead>';
          echo '<tr>';
          echo '<th>Visit ID</th>';
          echo '<th>Patient ID</th>';
          echo '<th>Doctor ID</th>';
          echo '<th>Date</th>';
          echo '<th>Details</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';
          foreach ($visits as $visit) {
              echo '<tr>';
              echo '<td>' . $visit['VisitID'] . '</td>';
              echo '<td>' . $visit['PatientID'] . '</td>';
              echo '<td>' . $visit['DoctorID'] . '</td>';
              echo '<td>' . $visit['Date'] . '</td>';
              echo '<td>' . $visit['Details'] . '</td>';
              echo '</tr>';
          }
          echo '</tbody>';
          echo '</table>';
      }
  } }
?>