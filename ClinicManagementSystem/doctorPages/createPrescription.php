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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorID = $_SESSION['userID'];
    // $patientID = $_POST['PatientID'];
    // var_dump($patientID);
    $visitID = $_POST['visitID'];

    $medicine = $_POST['medicine'];
    $quantity = $_POST['quantity'];
    $dose = $_POST['dose'];
    $refillable = isset($_POST['refillable']) ? 1 : 0;
    $prescription = new Prescription($db);

    if ($prescription->insert($visitID, $medicine, $quantity, $dose, $refillable)) {
        echo 'Prescription recorded';
    } else {
        echo 'Error creating prescription';
    }
}

// get all visits for this doctor
$visit = new Visit($db);
$visits = $visit->getVisitsByDoctor($_SESSION['userID']);

?>

<!DOCTYPE html>
<html>
   <head>
     <meta charset="UTF-8">
     <title>New Prescription Record</title>
   </head>
   <body>
     <h4>(Select from all patients who have a visit record with logged in doctor)</h4>
     <h1>New Prescription Record</h1>
     <h3>Select visit record for patient:</h3>
     <form action="createPrescription.php" method="post">
       <label for="visitID">Visit Record:</label>
       <select id="visitID" name="visitID" required>
         <?php foreach ($visits as $v) : ?>
           <?php
           $patient = new Patient($db);
           $patientInfo = $patient->selectOne($v['PatientID']);
           ?>
           <option value="<?= $v['VisitID'] ?>">
             <?= $patientInfo['FirstName'] . ' ' . $patientInfo['LastName'] . ' (' . 'ID:' . $v['PatientID'] . ')' . ' - ' . $v['Date'] ?>
           </option>
         <?php endforeach; ?>
       </select>
       <br><br>

       <!-- Prescription inputs -->
       <label for="medicine">Medicine:</label>
       <input type="text" id="medicine" name="medicine" required>
       <br><br>

       <label for="quantity">Quantity:</label>
       <input type="number" id="quantity" name="quantity" required>
       <br><br>

       <label for="dose">Dose:</label>
       <input type="text" id="dose" name="dose" required>
       <br><br>

       <label for="refillable">Refillable:</label>
       <input type="checkbox" id="refillable" name="refillable">
       <br><br>

       <input type="submit" name="submit" value="Create Prescription Record">
     </form>
   </body>
</html>
