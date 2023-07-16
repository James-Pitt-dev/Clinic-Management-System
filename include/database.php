<?php
// Creating the Database connection ***********

  $dbHost = 'localhost';
  $dbName = 'ocms';
  $dbUser = 'root';
  $dbPass = '';

  $db = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

  if (mysqli_connect_error()) { //check connection success?
  echo mysqli_connect_error(); // give error text if false
  exit;
  }
  echo "\nConnected";
?>
