<?php
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";

$volume = $_POST['volume'];

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO activitate_pompa (id_pompa, debit_apa, id_teren, data_utilizare) VALUES ('1','$volume','1', NOW())";

if ($conn->query($sql) === TRUE) {
  echo "New activity logged successfully";
} else {
  echo "Error logging activity: " . $conn->error;
}

$conn->close();
?>
