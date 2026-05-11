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

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $connection->query($sql);

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

    echo "Login erfolgreich.";
    echo "<br>";
    echo "Willkommen " . $user["username"];

    header("Location: ../../Frontend/pages/homepage.html");
    exit;

} else {
    echo "Passwort ist falsch.";
}

$connection->close();

?>