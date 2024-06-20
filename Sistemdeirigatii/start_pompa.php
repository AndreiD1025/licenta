<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
$sql = "UPDATE pompa SET status = 1 WHERE id_pompa = 1"; 
if ($conn->query($sql) === TRUE) {
    echo "Pompa a fost pornită cu succes.";
} else {
    echo "Eroare la pornirea pompei: " . $conn->error;
}

$conn->close();
?>
