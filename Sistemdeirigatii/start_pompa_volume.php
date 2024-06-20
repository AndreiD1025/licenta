<?php
if(isset($_POST['volum'])) {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "SistemIrigatii";
    $conn = new mysqli($hostname, $username, $password, $database);
    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }
    $volum = $_POST['volum'];
    $status = 3;
    $sql = "UPDATE pompa SET status='$status', volum='$volum' WHERE id_pompa=1";

    if ($conn->query($sql) === TRUE) {
        echo "Actualizarea stării și volumului pompei a fost realizată cu succes.";
    } else {
        echo "Eroare la actualizarea stării și volumului pompei: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Volumul nu a fost primit corect.";
}
?>
