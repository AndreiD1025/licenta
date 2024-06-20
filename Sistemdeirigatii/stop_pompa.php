<?php
// Conectare la baza de date
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";


$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

// Actualizare starea pompei pentru a indica pornirea pompei
$sql = "UPDATE pompa SET status = 0 WHERE id_pompa = 1"; // Presupunând că pompa cu ID-ul 1 este cea pe care o controlezi
if ($conn->query($sql) === TRUE) {
    echo "Pompa a fost oprirea cu succes.";
} else {
    echo "Eroare la oprirea pompei: " . $conn->error;
}

$conn->close();
?>
