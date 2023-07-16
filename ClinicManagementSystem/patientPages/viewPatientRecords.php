<?php
require 'include/database.php';
include 'classes/Prescription.php';
include 'classes/LabExam.php';
include 'header.php';

// Check if user is logged in as patient
if ($_SESSION['userType'] !== 'patient') {
  header('Location: index.php');
  exit();
}

$patientID = $_SESSION['userID'];

// Select patient prescriptions
$prescription = new Prescription($db);
$prescriptions = $prescription->selectByPatient($patientID);

// button to return to patient dashboard
echo '<a href="patientDashboard.php">Return to Patient Dashboard</a>';
// Display prescriptions
if ($prescriptions) {
  echo '<h1>My Prescriptions</h1>';

  echo '<table>';
  echo '<thead><tr><th>Prescription ID</th><th>Medicine</th><th>Quantity</th><th>Dose</th><th>Refillable</th></tr></thead>';
  echo '<tbody>';
  foreach ($prescriptions as $prescription) {
      echo '<tr>';
      echo '<td>' . $prescription['PrescriptionID'] . '</td>';
      echo '<td>' . $prescription['Medicine'] . '</td>';
      echo '<td>' . $prescription['Quantity'] . '</td>';
      echo '<td>' . $prescription['Dose'] . '</td>';
      echo '<td>' . ($prescription['Refillable'] ? 'Yes' : 'No') . '</td>';

      echo '</tr>';
  }
  echo '</tbody></table>';
} else {
  echo 'No prescriptions found.';
}

// Select patient lab exam entries
$labExam = new LabExam($db);
$exams = $labExam->selectByPatient($patientID);

// Display lab exam entries
if ($exams) {
  echo '<h1>My Lab Exam Entries</h1>';
  echo '<h2>Pending Results</h2>';
  echo '<table>';
  echo '<thead><tr><th>Exam ID</th><th>Exam Item</th><th>Date</th><th>Result</th></tr></thead>';
  echo '<tbody>';
  foreach ($exams as $exam) {
    if ($exam['Result'] === 'Pending') {
      echo '<tr>';
      echo '<td>' . $exam['LabExamID'] . '</td>';
      echo '<td>' . $exam['ExamItem'] . '</td>';
      echo '<td>' . $exam['Date'] . '</td>';
      echo '<td>' . $exam['Result'] . '</td>';
      echo '</tr>';
    }
  }
  echo '</tbody></table>';

  echo '<h2>Completed Results</h2>';
  echo '<table>';
  echo '<thead><tr><th>Exam ID</th><th>Exam Item</th><th>Date</th><th>Result</th><th>Normal Range</th></tr></thead>';
  echo '<tbody>';
  // Display completed lab exam results and if normal normalrange = false, display abnormal, else display normal

  foreach ($exams as $exam) {
    if ($exam['Result'] !== 'Pending') {
      echo '<tr>';
      echo '<td>' . $exam['LabExamID'] . '</td>';
      echo '<td>' . $exam['ExamItem'] . '</td>';
      echo '<td>' . $exam['Date'] . '</td>';
      echo '<td>' . $exam['Result'] . '</td>';
      if ($exam['NormalRange'] == 0) {
        echo '<td>Fail</td>';
      } else {
        echo '<td>Pass</td>';
      }
      echo '</tr>';
    }
  }
  echo '</tbody></table>';
} else {
  echo 'No lab exam results found.';}
