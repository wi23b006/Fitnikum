<?php
include("helpers.php");
include("../config/dbaccess.php");

$connection = getDatabaseConnection();

// Spec 2a: Login per Username ODER E-Mail
$login    = trim($_POST["login"] ?? "");
$password = $_POST["password"]   ?? "";
$remember = isset($_POST["remember"]); // Checkbox "Login merken"

if ($login == "" || $password == "") {
    sendJson(["success" => false, "error" => "Bitte Login und Passwort eingeben."]);
}

// User suchen: Username ODER E-Mail
$stmt = $connection->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    sendJson(["success" => false, "error" => "Login fehlgeschlagen."]);
}

$user = $result->fetch_assoc();

// Deaktivierte Kunden dürfen sich nicht einloggen (Matrix VIII)
if ((int)$user["active"] !== 1) {
    sendJson(["success" => false, "error" => "Account ist deaktiviert."]);
}

// Passwort prüfen
if (!password_verify($password, $user["password_hash"])) {
    sendJson(["success" => false, "error" => "Login fehlgeschlagen."]);
}

// Session setzen
$_SESSION["user_id"]  = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["role"]     = $user["role"];

// "Login merken": Token in DB speichern und Cookie setzen (Spec 2e)
if ($remember) {
    $token = bin2hex(random_bytes(32));
    $stmt = $connection->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
    $stmt->bind_param("si", $token, $user["id"]);
    $stmt->execute();
    setcookie("remember", $token, time() + 60*60*24*30, "/"); // 30 Tage
}

sendJson(["success" => true]);
?>