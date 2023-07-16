<?php
require 'include/database.php';
require 'classes/Staff.php';
require 'classes/Patient.php';
require 'classes/Visit.php';
require 'classes/Prescription.php';
require 'classes/LabExam.php';
include 'header.php';

// Check if user is logged in as staff
if ($_SESSION['userType'] !== 'staff') {
    header('Location: index.php');
    exit();
}
// Get the user ID from the session variable
$staffID = $_SESSION['userID'];
$staff = new Staff($db);
$report = null;
$topMedicine = null;

// Generate monthly report or yearly report
if (isset($_POST['submit']) && $_POST['submit'] === 'Generate') {
    $type = $_POST['type'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    // Generate report based on type
    if ($type === 'monthly') {
        $report = $staff->generateReport($month, $year);
        $visitSummary = $staff->generatePatientVisitSummary($month, $year);
        $topMedicine = $staff->generateTopMedicines($month, $year);
    } elseif ($type === 'yearly') {
        $report = $staff->generateReport(null, $year);
        $visitSummary = $staff->generatePatientVisitSummary(null, $year);
        $topMedicine = $staff->generateTopMedicines(null, $year);
    }

    //take month variable and assign it the month name and call it $monthName
    $monthName = date('F', mktime(0, 0, 0, $month, 10));
}

?>

    <html>
    <head>
        <title>Generate Report</title>

    </head>
<body>
    <h1>Generate Report</h1>
    <!-- Form to select report type, year,  -->
    <form action="generateReports.php" method="post">
        <label for="type">Report Type:</label>
        <select name="type" id="type">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
        <label for="year">Year:</label>
        <!--    // Set default year to current year-->
        <input type="number" name="year" id="year" value="<?php echo date('Y'); ?>" min="1900" max="<?php echo date('Y'); ?>">
        <label for="month">Month:</label>
        <!--    // Set default month to current month-->
        <input type="number" name="month" id="month" value="<?php echo date('m'); ?>" min="1" max="12">
        <input type="submit" name="submit" value="Generate">
        <button type="button" onclick="window.location.href='staffDashboard.php'">Return to Dashboard</button>
    </form>

    <!--// Display report-->
    <!--// if report empty, echo no records, else display report-->
<?php if (isset($monthName) && isset($year)): ?>
    <?php if (empty($report)):
        echo 'No records for: '.$monthName.', '.$year; ?>
    <?php endif; ?>
<?php endif; ?>

<?php if ($report): ?>
    <hr>
    <h2>Reports</h2>
    <!--    // If report type = monthly, display month and year, else display year-->
<?php
if ($type === 'monthly'): ?>
    <h3>Month: <?php echo $monthName; ?> <br> Year: <?php echo $year; ?></h3>
    <?php else: ?>
    <h3>Year: <?php echo $year; ?></h3>
    <?php endif; ?>

    <hr>
    <h3>Total Patient Visit For Each Doctor</h3>
    <table>
        <thead>
        <tr>
            <th>Dr First Name</th>
            <th>Dr Last Name</th>
            <th>Total Visits</th>
        </tr>
        </thead>
        <tbody>
        <!--        // Loop through report and display each record-->
        <?php foreach ($report as $record): ?>
            <tr>
                <td><?php echo $record['firstName']; ?></td>
                <td><?php echo $record['lastName']; ?></td>
                <td><?php echo $record['Total Visits']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!--// Select all patients, and their doctors, then get total visits with that doctor.-->
    <?php if (isset($visitSummary)): ?>
    <?php
    if (isset($monthName) && isset($year)) {
        if (empty($visitSummary)) {
            echo 'No Visits for: ' . $monthName . ', ' . $year;
        }
    }
    ?>
    <?php
    if ($visitSummary) { ?>
    <?php
        echo '<hr>';
    echo '<h3>Total Visits For Each Patient</h3>';
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Patient First Name</th>';
    echo '<th>Patient Last Name</th>';
    echo '<th>Doctor First Name</th>';
    echo '<th>Doctor Last Name</th>';
    echo '<th>Total Visits</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($visitSummary as $record) {
        echo '<tr>';
        echo '<td>' . $record['patientFirstName'] . '</td>';
        echo '<td>' . $record['patientLastName'] . '</td>';
        echo '<td>' . $record['doctorFirstName'] . '</td>';
        echo '<td>' . $record['doctorLastName'] . '</td>';
        echo '<td>' . $record['visitCount'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    }
    ?> <?php endif; ?>


    <!--// Generate top 3 medications for month/year-->
    <?php
    echo '<hr>';
    if (isset($monthName) && isset($year)):
        if (empty($topMedicine)) {
            echo 'No medications for: ' . $monthName . ', ' . $year;
        }
    endif;

        // check if topMedicine is empty and month/year is set
    if (isset($monthName) && isset($year)) {
        if (empty($topMedicine)) {
            echo 'No medications for: ' . $monthName . ', ' . $year;
        }
    }

    if ($topMedicine) {
        echo '<h3>Top 3 Medications Prescribed</h3>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Medication Name</th>';
        echo '<th>Prescription Count</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($topMedicine as $record) {
            echo '<tr>';
            echo '<td>' . $record['Medicine'] . '</td>';
            echo '<td>' . $record['prescriptionCount'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    ?>
</body
</html>