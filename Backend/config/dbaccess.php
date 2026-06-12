<?php
// Stellt die Verbindung zur MySQL-Datenbank her.
// Wird von allen Backend-Dateien eingebunden:
//   include("../config/dbaccess.php");
//   $connection = getDatabaseConnection();

function getDatabaseConnection() {

    $host     = "localhost";
    $user     = "root";
    $password = "";
    $database = "fitnikum";

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die("Datenbank-Verbindung fehlgeschlagen: " . $connection->connect_error);
    }

    // Damit Umlaute (ä, ö, ü) richtig in der DB landen.
    $connection->set_charset("utf8mb4");

    return $connection;
}
?>