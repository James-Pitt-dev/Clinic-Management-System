<?php
include 'header.php';
require_once 'include/database.php';
require_once 'classes/Patient.php';


// check if user is logged in and has a valid patient ID
// if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'patient') {
//     header('Location: logIn.php');
//     exit();
// }

$patientID = $_SESSION['userID'];
$patient = new Patient($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $homeAddress = $_POST['address'];
    $healthCardNumber = $_POST['HealthCard'];

    if ($patient->update($patientID, $firstName, $lastName, $email, $phone, $homeAddress, $healthCardNumber)) {
        echo 'Patient updated successfully';
        $_SESSION['email'] = $email;
    } else {
        echo 'Error updating patient';
    }
}

// fetch patient data from the database
$patientData = $patient->selectOne($patientID);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Patient Info</title>
</head>
<body>
    <h1>Update Patient Info</h1>
    <form action="updatePatient.php" method="post">
        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" required value="<?= $patientData['FirstName'] ?>"><br><br>

        <label for="LastName">Last Name:</label>
        <input type="text" id="LastName" name="LastName" required value="<?= $patientData['LastName'] ?>"><br><br>

        <label for="Email">Email:</label>
        <input type="Email" id="Email" name="Email" required value="<?= $patientData['Email'] ?>"><br><br>

        <label for="Phone">Phone:</label>
        <input type="text" id="Phone" name="Phone" required value="<?= $patientData['Phone'] ?>"><br><br>

        <label for="address">Home Address:</label>
        <input type="text" id="address" name="address" required value="<?= $patientData['HomeAddress'] ?>"><br><br>

        <label for="HealthCard">Health Card Number:</label>
        <input type="text" id="HealthCard" name="HealthCard" required value="<?= $patientData['HealthCardNumber'] ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
