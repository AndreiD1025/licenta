<?php
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";


$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>
