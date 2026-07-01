<?php
$severname = 'localhost'; 
$username = 'root'; 
$password = ''; 
$dbname = 'hospital';

$conn = new mysqli($severname, $username, $password, $dbname, 3307);

if ($conn->connect_error) 
{ 
  die("Connection failed: " . $conn->connect_error); 
}

$conn->set_charset("utf8mb4");
?>