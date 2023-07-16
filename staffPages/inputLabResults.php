<?php

// Staff class use case to update lab exam results

require 'classes/Staff.php';
require 'classes/System.php';
require 'include/database.php';
require 'classes/LabExam.php';
require 'header.php';

// Check if user is logged in and redirect to login page if not
if ($_SESSION['userType'] !== 'staff') {
    header('Location: login.php');
    exit;
}

$staff = new Staff($db);
$system = new System($db);
$labExam = new LabExam($db);

// Get all pending lab exams
$pendingLabExams = $system->selectPendingLabExams();
// Get labexam results from system
$labExamResults = $system->displayLabExamResults();


// If the form is submitted, update the lab exam result
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $labExamID = $_POST['labExamID'];

    if ($_POST['result'] === 'Delete') {
        $result = 'Pending';
    } else {
        $result = $_POST['result'];
    }

    $system->updateLabExamResult($labExamID, $result);
    // if update success, then updateLabExamNormalRange($labExamID, $result);
    $system->updateLabExamNormalRange($labExamID, $result);
    header('Location: inputLabResults.php');
}




// for each pending lab exam, display the lab exam ID, patient ID, lab exam type, result, normalrange, and date.
// for each pending lab exam, display a form with a select field of values 'Normal', 'Abnormal' for the result and a submit button.
// when the submit button is clicked, the result is updated in the database.
?>

<html>
<head>
    <title>Input Lab Exam Results</title>
</head>
<body>

    <h2>Pending Lab Exams</h2>
    <?php
    if (empty($pendingLabExams)) {
        echo 'No pending lab exams.';
    } else {
    ?>
    <table>
        <thead>
        <tr>
            <th>Lab Exam ID</th>
            <th>Patient ID</th>
            <th>Lab Exam Type</th>
            <th>Result</th>
            <th>Normal Range</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($pendingLabExams as $pendingLabExam) {
            $labExamID = $pendingLabExam['LabExamID'];
            $patientID = $pendingLabExam['PatientID'];
            $labExamType = $pendingLabExam['ExamItem'];
            $result = $pendingLabExam['Result'];
            $normalRange = $pendingLabExam['NormalRange'];
            $date = $pendingLabExam['Date'];
            ?>
            <tr>
                <td><?php echo $labExamID; ?></td>
                <td><?php echo $patientID; ?></td>
                <td><?php echo $labExamType; ?></td>
                <td><?php echo $result; ?></td>
                <td><?php echo 'Awaiting Results'; ?></td>
                <td><?php echo $date; ?></td>
                <td>
                    <form action="inputLabResults.php" method="post">
                        <select name="result">
                            <option value="" disabled selected>Select</option>
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                        </select>
                        <input type="hidden" name="labExamID" value="<?php echo $labExamID; ?>">
                        <input type="submit" name="update" value="Update">
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
    }
    ?>
<!--    // Lab exam results should include date, patient name, patient health care number, exam-->
<!--    //item, result, and normal range.-->

    <h3>Lab Exam Results</h3>
    <?php
    if (empty($labExamResults)) {
        echo 'No lab exam results.';
    } else {
        ?>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Patient Name</th>
                <th>Health Care Number</th>
                <th>Lab Exam Type</th>
                <th>Result</th>
                <th>Normal Range</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($labExamResults as $labExamResult) {
                $labExamID = $labExamResult['LabExamID'];
                $date = $labExamResult['Date'];
                $patientName = $labExamResult['FirstName'] . ' ' . $labExamResult['LastName'];
                $healthCareNumber = $labExamResult['HealthCardNumber'];
                $labExamType = $labExamResult['ExamItem'];
                $result = $labExamResult['Result'];
                $normalRange = $labExamResult['NormalRange'] ? 'Pass' : 'Fail';
                ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $patientName; ?></td>
                    <td><?php echo $healthCareNumber; ?></td>
                    <td><?php echo $labExamType; ?></td>
                    <td><?php echo $result; ?></td>
                    <td><?php echo $normalRange; ?></td>
               <td>
                    <form action="inputLabResults.php" method="post">
                        <select name="result">
                            <option value="" disabled selected>Select</option>
                            <option value="Normal">Normal</option>
                            <option value="Abnormal">Abnormal</option>
                            <option value="Delete">Delete Result</option>
                        </select>
                        <input type="hidden" name="labExamID" readonly value="<?php echo $labExamID; ?>">
                        <input type="submit" name="update" value="Modify">
                    </form>
                </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    ?>
</body>
</html>


