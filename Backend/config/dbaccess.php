<?php

function getDatabaseConnection()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "fitnikum";

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die("Datenbankverbindung fehlgeschlagen: " . $connection->connect_error);
    }

    $connection->set_charset("utf8");

    return $connection;
}

?>