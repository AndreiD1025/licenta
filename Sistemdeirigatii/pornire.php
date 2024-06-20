<?php
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
$sql = "SELECT status FROM pompa WHERE id_pompa = 1";
$result = $conn->query($sql);

if (!$result) {
    die("Eroare la interogare: " . $conn->error);
}
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $valoare = $row["status"];
    echo  $valoare;
}
$conn->close();
?>
