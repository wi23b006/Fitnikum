<?php
include("helpers.php");
include("../config/dbaccess.php");

requireLogin();
$connection = getDatabaseConnection();

$userId      = $_SESSION["user_id"];
$firstname   = trim($_POST["firstname"]   ?? "");
$lastname    = trim($_POST["lastname"]    ?? "");
$address     = trim($_POST["address"]     ?? "");
$postalCode  = trim($_POST["postal_code"] ?? "");
$city        = trim($_POST["city"]        ?? "");
$currentPw   = $_POST["current_password"] ?? "";

if ($firstname == "" || $lastname == "" || $currentPw == "") {
    sendJson(["success" => false, "error" => "Bitte Pflichtfelder und aktuelles Passwort ausfüllen."]);
}

// Passwort prüfen (Spec 6b: "beim Ändern von Daten, das Passwort verlangt")
$stmt = $connection->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!password_verify($currentPw, $user["password_hash"])) {
    sendJson(["success" => false, "error" => "Aktuelles Passwort ist falsch."]);
}

// Daten updaten
$stmt = $connection->prepare("UPDATE users SET firstname = ?, lastname = ?, address = ?, postal_code = ?, city = ? WHERE id = ?");
$stmt->bind_param("sssssi", $firstname, $lastname, $address, $postalCode, $city, $userId);
$stmt->execute();

sendJson(["success" => true]);
?>