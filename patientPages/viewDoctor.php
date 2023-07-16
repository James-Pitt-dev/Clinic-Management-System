<?php
include 'header.php';
require_once('include/database.php');
require_once('classes/Doctor.php');

if (isset($_GET['id'])) {
    $doctorID = $_GET['id'];

    $doctor = new Doctor($db);
    $doctorData = $doctor->selectOne($doctorID);

    if ($doctorData) {
        echo '<h1>' . $doctorData['FirstName'] . ' ' . $doctorData['LastName'] . '</h1>';
        echo '<p>Email: ' . $doctorData['Email'] . '</p>';
        echo '<p>Phone: ' . $doctorData['Phone'] . '</p>';
        echo '<p>Specialization: ' . $doctorData['Specialization'] . '</p>';
        echo '<p>Role: ' . $doctorData['Role'] . '</p>';
    } else {
        echo 'Doctor not found.';
    }
} else {
    echo 'Doctor ID not specified.';
}

?>
<h4>Maybe a blank profile pic here?</h4>
<br>
<h4>Random profile text too?</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<?php
?>
