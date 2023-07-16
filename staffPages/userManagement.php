<?php
require_once 'include/database.php';
require_once 'classes/Staff.php';
include 'header.php';

// check if user is logged in and has staff privileges
// if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'staff') {
//     header('Location: logIn.php');
//     exit();
// }

// check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableName = $_POST['accountType'];
    $id = $_POST[$tableName.'ID'];


    $staff = new Staff($db);
    if ($staff->deleteAccount($tableName, $id)) {
        echo "Account with ID $id from table $tableName has been deleted successfully";
    } else {
        echo "Error deleting account";
    }
} else {
    // display form to select account type
    echo '<div class="container">';
    echo '<h1>Delete/Modify Accounts</h1>';
    echo '<form method="post">';
    echo '<div class="form-group">';
    echo '<label for="accountType">Select account type:</label>';
    echo '<select class="form-control" id="accountType" name="accountType">';
    echo '<option value="patients">Patient</option>';
    echo '<option value="doctors">Doctor</option>';
    echo '<option value="staff">Staff</option>';
    echo '</select>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Submit</button>';
    echo '</form>';

    // if account type has been selected, display list of accounts
    if (isset($_POST['accountType'])) {
       $tableName = $_POST['accountType'];
       $class = ucfirst($tableName);

       $tableData = (new $class($db))->selectAll();

       echo '<h2>' . ucfirst($tableName) . ' Accounts:</h2>';
       echo '<ul>';
       foreach ($tableData as $row) {
           echo '<li>';
           foreach ($row as $key => $value) {
               echo "<strong>$key:</strong> $value ";
           }
           echo '<form method="post" action="deleteAccount.php">';
           echo "<input type='hidden' name='tableName' value='$tableName'>";
           echo "<input type='hidden' name='id' value='{$row[$tableName.'ID']}'>";
           echo '<button type="submit" class="btn btn-danger">Delete</button>';
           echo '</form>';
           echo '<form method="get" action="' . $tableName . 'Update.php">';
           echo "<input type='hidden' name='id' value='{$row[$tableName.'ID']}'>";
           echo '<button type="submit" class="btn btn-primary">Update</button>';
           echo '</form>';
           echo '</li>';
       }
       echo '</ul>';
   }
   echo '</div>';
}
?>
