<?php

session_start();

include("../config/dbaccess.php");
$connection = getDatabaseConnection();

$email = $_POST["email"];
$password = $_POST["password"];

if ($email == "" || $password == "") {
    echo "Bitte E-Mail und Passwort eingeben.";
    exit;
}

$stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Benutzer wurde nicht gefunden.";
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user["password_hash"])) {

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    header("Location: ../../Frontend/pages/homepage.php");
    exit;

} else {
    echo "Passwort ist falsch.";
}

$stmt->close();
$connection->close();

?>