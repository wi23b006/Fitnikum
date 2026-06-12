<?php
include("helpers.php");
include("../config/dbaccess.php");

// Falls "Login merken" gesetzt war: Token in DB löschen und Cookie weg
if (isset($_SESSION["user_id"])) {
    $connection = getDatabaseConnection();
    $stmt = $connection->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
}

if (isset($_COOKIE["remember"])) {
    setcookie("remember", "", time() - 3600, "/");
}

session_destroy();

// Zurück zur Startseite
header("Location: ../../Frontend/pages/homepage.php");
exit;
?>