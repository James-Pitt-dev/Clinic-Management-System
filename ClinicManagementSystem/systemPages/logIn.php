<?php

require_once('include/database.php');
require_once('classes/Authentication.php');
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    $auth = new Authentication($db);
    $userID = $auth->validateLogin($email, $password, $usertype);

    if ($userID) {
      // Set session variables
      $_SESSION['loggedIn'] = true;
      $_SESSION['userID'] = $userID;
      $_SESSION['userType'] = $usertype;

      // Redirect to appropriate dashboard
        if ($usertype == 'patient') {
            header('Location: patientDashboard.php');
        } else if ($usertype == 'doctor') {
            header('Location: doctorDashboard.php');
        } else if ($usertype == 'staff') {
            header('Location: staffDashboard.php');
        }
        exit();

  } else {
      // Login failed
      echo "Invalid login";
  }

}
?>

<h1>Log In</h1>
<h3>(log in checks the log in table, it returns the user type and userID, this is stored in a global variable to manage access control and populate various fields/conditionals)</h3>
<form method="post" action="login.php">
  <label for="email">Email:</label>
  <input type="email" name="email" required>

  <label for="password">Password:</label>
  <input type="password" name="password" required>

  <label for="usertype">User Type:</label>
  <select name="usertype" required>
    <option value="patient">Patient</option>
    <option value="doctor">Doctor</option>
    <option value="staff">Staff</option>
  </select>

  <button type="submit">Log In</button>
</form>
