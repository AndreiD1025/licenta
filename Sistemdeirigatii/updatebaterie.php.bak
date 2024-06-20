<?php

   $hostname = "localhost";
$username = "root";
$password = "";
$database = "SistemIrigatii";


$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while (true) {
    // Interogare pentru a obține valoarea numerică actualizată din baza de date
    $sql = "SELECT umiditate FROM terenuri WHERE id_teren = 1";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Eroare la interogare: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $valoare_numerică = $row["umiditate"];
    } else {
        $valoare_numerică = 0;
    }

    // Trimite actualizarea către client în format SSE
    echo "data: " . json_encode(['valoare_numerică' => $valoare_numerică]) . "\n\n";

    // Fortează trimiterea bufferului către client pentru a asigura actualizările în timp real
    ob_flush();
    flush();

    // Așteaptă un interval de timp înainte de a trimite următoarea actualizare
    sleep(1); // Exemplu: actualizează la fiecare secundă
}
?>
