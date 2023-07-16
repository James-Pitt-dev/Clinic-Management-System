<?php
require 'include/database.php';
include 'classes/LabExam.php';
include 'header.php';

$labExam = new LabExam($db);
$exams = $labExam->selectByDoctor($_SESSION['userID']);
?><h3>(fetch all from lab exam table where doctorID matches, separate into two categories based on result status, pending or not. No functionality to change result yet, need to manually edit result field in db for now)</h3> <?php
if ($exams) {
  echo '<h1>Lab Exam Results</h1>';
  echo '<h2>Pending Results</h2>';
  echo '<table>';
  echo '<thead><tr><th>Exam ID</th><th>Patient Name</th><th>Exam Item</th><th>Date</th><th>Result</th><th></th></tr></thead>';
  echo '<tbody>';
  foreach ($exams as $exam) {
    if ($exam['Result'] === 'Pending') {
      echo '<tr>';
      echo '<td>' . $exam['LabExamID'] . '</td>';
      echo '<td>' . $exam['firstName'] . ' ' . $exam['lastName'] . '</td>';
      echo '<td>' . $exam['ExamItem'] . '</td>';
      echo '<td>' . $exam['Date'] . '</td>';
      echo '<td>' . $exam['Result'] . '</td>';
      echo '<td><a href="updateLabExam.php?LabExamID=' . $exam['LabExamID'] . '">Update</a></td>';
      echo '</tr>';
    }
  }
  echo '</tbody></table>';

  echo '<h2>Completed Results</h2>';
  echo '<table>';
  echo '<thead><tr><th>Exam ID</th><th>Patient Name</th><th>Exam Item</th><th>Date</th><th>Result</th><th>Normal Range</th><th></th></tr></thead>';
  echo '<tbody>';
  foreach ($exams as $exam) {
    if ($exam['Result'] !== 'Pending') {
      echo '<tr>';
      echo '<td>' . $exam['LabExamID'] . '</td>';
      echo '<td>' . $exam['firstName'] . ' ' . $exam['lastName'] . '</td>';
      echo '<td>' . $exam['ExamItem'] . '</td>';
      echo '<td>' . $exam['Date'] . '</td>';
      echo '<td>' . $exam['Result'] . '</td>';
      echo '<td>' . $exam['NormalRange'] . '</td>';
      echo '<td><a href="updateLabExam.php?LabExamID=' . $exam['LabExamID'] . '">Update</a></td>';
      echo '</tr>';
    }
  }
  echo '</tbody></table>';
} else {
  echo 'No lab exam results found.';
}
?>
