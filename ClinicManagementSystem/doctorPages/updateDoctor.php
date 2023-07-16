<?php
require_once 'include/database.php';
require_once 'classes/Doctor.php';
include 'header.php';

// check if user is logged in and has a valid doctor ID
// Access Control
// if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
//     header('Location: logIn.php');
//     exit();
// }


$doctorID = $_SESSION['userID'];
$doctor = new Doctor($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $specialization = $_POST['Specialization'];

    if ($doctor->update($doctorID, $firstName, $lastName, $email, $phone, $specialization)) {
        echo 'Doctor updated successfully';
        $_SESSION['email'] = $email;
    } else {
        echo 'Error updating doctor';
    }
}

// fetch doctor data from the database
$doctorData = $doctor->selectOne($doctorID);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Doctor Info</title>
</head>
<body>
    <h1>Update Doctor Info</h1>
    <form action="updateDoctor.php" method="post">
        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" required value="<?= $doctorData['FirstName'] ?>"><br><br>

        <label for="LastName">Last Name:</label>
        <input type="text" id="LastName" name="LastName" required value="<?= $doctorData['LastName'] ?>"><br><br>

        <label for="Email">Email:</label>
        <input type="Email" id="Email" name="Email" required value="<?= $doctorData['Email'] ?>"><br><br>

        <label for="Phone">Phone:</label>
        <input type="text" id="Phone" name="Phone" required value="<?= $doctorData['Phone'] ?>"><br><br>

        <label for="Specialization">Specialization:</label>
        <input type="text" id="Specialization" name="Specialization" required value="<?= $doctorData['Specialization'] ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
