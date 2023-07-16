<?php
require_once 'include/database.php';
require_once 'include/Staff.php';
include 'header.php';

// check if user is logged in and has a valid staff ID
// if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
//     header('Location: logIn.php');
//     exit();
// }

$staffID = $_SESSION['userID'];
$staff = new Staff($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $role = $_POST['Role'];

    if ($staff->update($staffID, $firstName, $lastName, $email, $phone, $role)) {
        echo 'Staff updated successfully';
        $_SESSION['email'] = $email;
    } else {
        echo 'Error updating staff';
    }
}

// fetch staff data from the database
$staffData = $staff->selectOne($staffID);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Staff Info</title>
</head>
<body>
    <h1>Update Staff Info</h1>
    <form action="updateStaff.php" method="post">
        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" required value="<?= $staffData['FirstName'] ?>"><br><br>

        <label for="LastName">Last Name:</label>
        <input type="text" id="LastName" name="LastName" required value="<?= $staffData['LastName'] ?>"><br><br>

        <label for="Email">Email:</label>
        <input type="Email" id="Email" name="Email" required value="<?= $staffData['Email'] ?>"><br><br>

        <label for="Phone">Phone:</label>
        <input type="text" id="Phone" name="Phone" required value="<?= $staffData['Phone'] ?>"><br><br>

        <label for="Role">Role:</label>
        <input type="text" id="Role" name="Role" required disabled value="<?= $staffData['Role'] ?>"><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
