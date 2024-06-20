<?php
// Datele de conectare la baza de date
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";


$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

$sql = "SELECT umiditate FROM terenuri WHERE id_teren = 1";
$result = $conn->query($sql);

// Afisare erori, daca exista
if (!$result) {
    die("Eroare la interogare: " . $conn->error);
}

// Verificare dacă există date în tabel
if ($result->num_rows > 0) {
    // Extrageți valoarea din rezultatul interogării
    $row = $result->fetch_assoc();
    $valoare = $row["umiditate"];
    
    echo  $valoare;
}

// Închidere conexiune
$conn->close();
?>
