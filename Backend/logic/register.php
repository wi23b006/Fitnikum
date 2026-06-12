<?php
include("helpers.php");
include("../config/dbaccess.php");

$connection = getDatabaseConnection();

// Werte aus dem Formular holen (Spec 1b: alle Pflichtfelder)
$salutation = trim($_POST["salutation"] ?? "");
$firstname  = trim($_POST["firstname"]  ?? "");
$lastname   = trim($_POST["lastname"]   ?? "");
$address    = trim($_POST["address"]    ?? "");
$postalCode = trim($_POST["postal_code"]?? "");
$city       = trim($_POST["city"]       ?? "");
$email      = trim($_POST["email"]      ?? "");
$username   = trim($_POST["username"]   ?? "");
$password   = $_POST["password"]  ?? "";
$password2  = $_POST["password2"] ?? "";

// Validierung serverseitig: alle Felder müssen ausgefüllt sein
if ($salutation == "" || $firstname == "" || $lastname == ""
    || $address == "" || $postalCode == "" || $city == ""
    || $email == "" || $username == "" || $password == "") {
    sendJson(["success" => false, "error" => "Bitte alle Felder ausfüllen."]);
}

// Validierung: E-Mail-Format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJson(["success" => false, "error" => "Ungültige E-Mail-Adresse."]);
}

// Validierung: Passwort 2x identisch (Spec 1c)
if ($password !== $password2) {
    sendJson(["success" => false, "error" => "Die Passwörter stimmen nicht überein."]);
}

// Validierung: Passwortlänge
if (strlen($password) < 8) {
    sendJson(["success" => false, "error" => "Passwort muss mindestens 8 Zeichen lang sein."]);
}

// Prüfen ob E-Mail oder Username schon existieren
$stmt = $connection->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    sendJson(["success" => false, "error" => "E-Mail oder Username schon vergeben."]);
}

// Passwort verschlüsseln (Spec 1d, Matrix 2P)
$hash = password_hash($password, PASSWORD_DEFAULT);

// User anlegen
$sql = "INSERT INTO users (salutation, firstname, lastname, username, email, password_hash, address, postal_code, city)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sssssssss", $salutation, $firstname, $lastname, $username, $email, $hash, $address, $postalCode, $city);

if ($stmt->execute()) {
    sendJson(["success" => true]);
} else {
    sendJson(["success" => false, "error" => "Fehler beim Anlegen des Users."]);
}
?>