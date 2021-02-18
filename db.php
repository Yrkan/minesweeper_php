<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$db = "db1";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS games ( `game_id` VARCHAR(255) , `data` LONGTEXT NOT NULL , `status` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`game_id`))";

if (! mysqli_query($conn, $sql)) {
  die("Connection failed: can't create tables" . mysqli_connect_error());
}
?> 

