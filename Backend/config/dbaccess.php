<?php
// Zentrale DB-Service-Klasse (Spec II.d, Matrix I).
// Wird von allen Backend-Dateien eingebunden:
//   include("../config/dbaccess.php");
//   $connection = getDatabaseConnection();

class DBAccess {

    // Liefert eine MySQL-Verbindung zurück.
    public static function getConnection() {

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
}


// Wrapper-Funktion, damit der bestehende Code weiter funktioniert.
function getDatabaseConnection() {
    return DBAccess::getConnection();
}
?>
