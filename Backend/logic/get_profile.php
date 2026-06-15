<?php
// Liefert die Stammdaten des eingeloggten Users als JSON.
// Wird vom Frontend (profil.php) per AJAX aufgerufen,

include("helpers.php");
include("../config/dbaccess.php");

requireLogin();
$connection = getDatabaseConnection();
                                                                                //Fragezeichen ist Platzhalter, nächste zeile fügt es ein (i) 

$stmt = $connection->prepare("SELECT salutation, firstname, lastname, username, email, address, postal_code, city FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

sendJson(["success" => true, "user" => $user]);
?>
