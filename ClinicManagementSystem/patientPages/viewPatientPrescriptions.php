<?php
require 'include/database.php';
include 'classes/Prescription.php';
include 'header.php';

// if ($_SESSION['role'] !== 'doctor') {
//   echo ('You are not authorized to view this page.');

// }

$prescription = new Prescription($db);
$prescriptions = $prescription->selectByDoctor($_SESSION['userID']);

if ($prescriptions) {
  echo '<h1>Patient Prescriptions</h1>';

  echo '<table>';
  echo '<thead><tr><th>Prescription ID</th><th>Patient Name</th><th>Medicine</th><th>Quantity</th><th>Dose</th><th>Refillable</th><th>Date</th></tr></thead>';
  echo '<tbody>';
  foreach ($prescriptions as $prescription) {
      echo '<tr>';
      echo '<td>' . $prescription['PrescriptionID'] . '</td>';
      echo '<td>' . $prescription['firstName'] . ' ' . $prescription['lastName'] . '</td>';
      echo '<td>' . $prescription['Medicine'] . '</td>';
      echo '<td>' . $prescription['Quantity'] . '</td>';
      echo '<td>' . $prescription['Dose'] . '</td>';
      echo '<td>' . ($prescription['Refillable'] ? 'Yes' : 'No') . '</td>';

      echo '</tr>';
  }
  echo '</tbody></table>';
} else {
  echo 'No patient prescriptions found.';
}
?>
