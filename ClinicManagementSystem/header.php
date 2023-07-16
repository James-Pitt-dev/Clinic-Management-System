<?php
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Just a testing header list for all site functionality.</h1>
    <table>
        <tr>
            <td>
                <h3>Patient</h3>
                <ul>
                    <li><a href="updatePatient.php">Update Patient</a></li>
                    <li><a href="createPassword.php">Create Patient Password</a></li>
                    <li><a href="patientRegister.php">Patient Register</a></li>
                    <li><a href="patientDashboard.php">Patient Dashboard (home)</a></li>
                </ul>
            </td>
            <td>
                <h3>Doctor</h3>
                <ul>
                    <li><a href="updateDoctor.php">Update Doctor</a></li>
                    <li><a href="createDoctorPassword.php">Create Doctor Password</a></li>
                    <li><a href="doctorRegister.php">Doctor Register</a></li>
                    <li><a href="DoctorDashboard.php">Doctor Dashboard (home)</a></li>

                    <li><a href="createVisit.php">Create Visit</a></li>
                    <li><a href="createPrescription.php">Create Prescription</a></li>
                    <li><a href="createLabExam.php">Create Lab Exam</a></li>
                    <li><a href="viewLabExams.php">View Patient Lab Exams</a></li>
                    <li><a href="viewPatientPrescriptions.php">View Patient Prescriptions</a></li>
                </ul>
            </td>
            <td>
                <h3>Staff <br>(only register/update/login use cases so far)</h3>
                <ul>
                    <li><a href="updateStaff.php">Update Staff</a></li>
                    <li><a href="createStaffPassword.php">Create Staff Password</a></li>
                    <li><a href="staffRegister.php">Staff Register</a></li>
                    <li><a href="staffDashboard.php">Staff Dashboard (home)</a></li>
                    <!-- <li><a href="userManagement.php">Staff.delete & update</a></li> -->
                </ul>
            </td>
            <td>
                <h3>System</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="logIn.php">Log In</a></li>
                    <li><a href="logOut.php">Log Out</a></li>
                      <li><a href="viewDoctors.php">View Doctors</a></li>
                </ul>
            </td>
        </tr>
    </table>
      <h3>Logged in as <?php echo $_SESSION['userType']; ?></h3>
    <hr>

</body>
</html>
