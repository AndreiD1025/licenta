<?php
   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";

global $actualizareBaterie;
$actualizareBaterie = 75; 

function updateBaterie() {
    global $servername, $username, $password, $dbname, $actualizareBaterie;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexiunea a eșuat: " . $conn->connect_error);
    }
    $sql = "UPDATE baterie SET procent_baterie = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $actualizareBaterie);
        if ($stmt->execute()) {
            echo "Procentul bateriei a fost actualizat cu succes.";
        } else {
            echo "Eroare la actualizarea procentului bateriei: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Eroare la pregătirea interogării: " . $conn->error;
    }
    $conn->close();
}
updateBaterie();
?>
