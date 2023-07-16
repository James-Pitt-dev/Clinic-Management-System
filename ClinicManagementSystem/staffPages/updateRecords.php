<?php

require 'include/database.php';
include 'classes/Doctor.php';
include 'classes/Patient.php';
include 'classes/Visit.php';
include 'classes/Prescription.php';
include 'classes/LabExam.php';
include 'header.php';

$visitID = null;
// check if user is logged in and has a valid doctor ID
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
    header('Location: logIn.php');
    exit();
}
// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Select') {

    $visitID = $_POST['visitID'];
}

// check if submit button has been clicked for updating visit details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Update Visit Details'){
  $visit = new Visit($db);
  $visitID = $_POST['visitID'];
  $details = $_POST['details'];
  if ($visit->updateVisit($visitID, $details)) {
      echo 'Visit details updated';
  } else {
      echo 'Error updating visit details';
  }

}

//check if submit button has been clicked for updating lab exam
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Update Lab Exam'){

  $visit = new Visit($db);
  $labExam = new LabExam($db);

    $visitID = isset($_POST['visitID']) ? $_POST['visitID'] : null;

  $examDate = $_POST['examDate'];
  $examItem = $_POST['examItem'];
  $labExamID = $_POST['LabExamID'];

  if($labExam->updateDateAndExamItem($labExamID, $examDate, $examItem)){
      echo 'Lab Exam recorded';
  } else {
      echo 'Error creating Lab Exam';
  }

}
//check if submit button has been clicked for updating prescription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $_POST['submit'] === 'Update Prescription') {
    $prescription = new Prescription($db);

    $prescriptionID = $_POST['prescriptionID'];
    $visitID = $_POST['visitID'];
    $medicine = $_POST['medicine'];
    $quantity = $_POST['quantity'];
    $dose = $_POST['dose'];
    $refillable = $_POST['refillable'];

    if ($prescription->update($prescriptionID, $visitID, $medicine, $quantity, $dose, $refillable)) {
        echo 'Prescription updated';
    } else {
        echo 'Error updating prescription';
    }
}
?>

<h3>Lab Exam Update</h3>
<h3>Select visit record for patient:</h3>
 <h4>(Select from all patients who have a visit record with logged in doctor)</h4>
<form action="updateRecords.php" method="post">
  <label for="visitID">Visit Record:</label>
  <select id="visitID" name="visitID" required>
    <?php
      // get all visits for this doctor
      $visit = new Visit($db);
      $visits = $visit->getVisitsByDoctor($_SESSION['userID']);
      foreach ($visits as $v) :
        $patient = new Patient($db);
        $patientInfo = $patient->selectOne($v['PatientID']);
    ?>
    <hr>
    <option value="<?= $v['VisitID'] ?>">
      <?= $patientInfo['FirstName'] . ' ' . $patientInfo['LastName'] . ' (' . 'ID:' . $v['PatientID'] . ')' . ' - ' . $v['Date'] ?>
    </option>
    <?php endforeach; ?>
  </select>
  <br><br>
  <input type="submit" name="submit" value="Select">
    <button type="button" onclick="window.location.href='doctorDashboard.php'">Return to Dashboard</button>

</form>

<form action="updateRecords.php" method="post">
    <br><br>
    <?php if (isset($visitID)) :
        // Get visit details based on selected visit ID
        $visit = new Visit($db);
        $visitInfo = $visit->selectOne($visitID);
        // Get labexam details based on visidID
        $labExam = new LabExam($db);
        $labExamEntries = $labExam->selectByVisit($visitID);

        $visitID = $_POST['visitID'];
    // Get prescription details based on visitID
    $prescription = new Prescription($db);
    $prescriptionInfo = $prescription->selectByVisit($visitID);


    ?>
<!--    // visit update-->
        <input type="hidden" name="visitID" value="<?= $visitID ?>">
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $visitInfo['Date'] ?></td>
                    <td>
                      <textarea name="details" rows="8" cols="40" value="" required ><?= $visitInfo['Details'] ?></textarea>
                    </td>

                </tr>
            </tbody>
        </table>
        <br>
        <input type="submit" name="submit" value="Update Visit Details">
      </form>
        <br><br>
<hr>
<!-- Lab exam update -->
    <h3>Lab Exam Entries</h3>
<?php if ($labExamEntries !== false) : ?>

    <?php foreach ($labExamEntries as $entry) : ?>
        <form action="updateRecords.php" method="post">
            <input type="hidden" name="LabExamID" value="<?= $entry['LabExamID'] ?>">

            <label for="date">Date:</label>
            <input type="text" name="examDate" value="<?= $entry['Date'] ?>" required><br><br>
            <label for="examItem">Exam Item:</label>
            <input type="text" name="examItem" value="<?= $entry['ExamItem'] ?>" required><br><br>
            <label for="result">Result:</label>
            <input type="text" name="result" readonly value="<?= $entry['Result'] ?>"><br><br>
            <label for="normalRange">Normal Range:</label>
            <?php if ($entry['NormalRange'] === 0 && ($entry['Result'] === 'Pending')) : ?>
                <input type="text" name="normalRange" readonly value="Awaiting Results"><br><br>
            <?php elseif ($entry['NormalRange'] === 0) : ?>
                <input type="text" name="normalRange" readonly value="Fail"><br><br>
            <?php else : ?>
                <input type="text" name="normalRange" readonly value="Pass"><br><br>
            <?php endif; ?>
            <input type="submit" name="submit" value="Update Lab Exam">
        </form>
        <hr>
    <?php endforeach; ?>
<?php else : ?>
    <p>No lab exams exist for this visit/patient.</p>
<?php endif; ?>


    <br><br>
<!--//    prescription update-->
    <h3>Prescription Entries</h3>
    <?php if ($prescriptionInfo !== false ) : ?>

          <?php foreach ($prescriptionInfo as $entry) : ?>

                <form action="updateRecords.php" method="post">
                    <input type="hidden" name="prescriptionID" value="<?= $entry['PrescriptionID']; ?>">
                    <input type="hidden" name="visitID" value="<?= $visitID ?>">
                    <label for="medicine">Medicine:</label>
  <input type="text" name="medicine" value="<?= isset($entry['Medicine']) ? $entry['Medicine'] : ''; ?>" required><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" name="quantity" value="<?= isset($entry['Quantity']) ? $entry['Quantity'] : ''; ?>" required><br><br>
  <label for="dose">Dose:</label>
  <input type="text" name="dose" value="<?= isset($entry['Dose']) ? $entry['Dose'] : ''; ?>" required><br><br>
  <label for="refillable">Refillable:</label>
  <input type="checkbox" name="refillable" value="1" <?= isset($entry['Refillable']) && $entry['Refillable'] == 1 ? 'checked' : '' ?>>

<br><br>
                    <input type="submit" name="submit" value="Update Prescription">
                </form>
                <hr>
    <?php endforeach; ?>
      <?php else : ?>
            <p>No prescriptions exist for this visit/patient.</p>
        <?php endif; ?>
<?php endif;  ?>
