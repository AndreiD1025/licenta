<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";
$status = $_POST['status'];
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "UPDATE pompa SET status='$status' WHERE id_pompa=1";

if ($conn->query($sql) === TRUE) {
  echo "Pump status updated successfully";
} else {
  echo "Error updating pump status: " . $conn->error;
}
$conn->close();
?>
