<?php
$host = "localhost";
$dbname = "task2updatedathul";
$username = "root";
$password = "root";

$conn = mysqli_connect($host,$username,$password,$dbname);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

?>