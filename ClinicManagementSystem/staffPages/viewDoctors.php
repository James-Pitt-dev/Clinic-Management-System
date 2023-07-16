<?php
include 'header.php';
require_once('include/database.php');
require_once('classes/Doctor.php');

$doctor = new Doctor($db);
$doctors = $doctor->selectAll();

if ($doctors) {
    echo '<h1>Doctors List</h1>';
    echo '<table>';
    echo '<thead><tr><th>Doctor ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Specialization</th><th>Role</th><th>Actions</th></tr></thead>';
    echo '<tbody>';
    foreach ($doctors as $doc) {
        echo '<tr>';
        echo '<td>' . $doc['DoctorID'] . '</td>';
        echo '<td>' . $doc['FirstName'] . '</td>';
        echo '<td>' . $doc['LastName'] . '</td>';
        echo '<td>' . $doc['Email'] . '</td>';
        echo '<td>' . $doc['Phone'] . '</td>';
        echo '<td>' . $doc['Specialization'] . '</td>';
        echo '<td>' . $doc['Role'] . '</td>';
        echo '<td><a href="viewDoctor.php?id=' . $doc['DoctorID'] . '">View</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo 'No doctors found.';
}


?>
