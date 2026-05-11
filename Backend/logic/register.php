<?php

include("../config/dbaccess.php");

$connection = getDatabaseConnection();

$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$email = $_POST["email"];
$password = $_POST["password"];
$password2 = $_POST["password2"];

$username = $firstname . " " . $lastname;

if ($password != $password2) {
    echo "Die Passwörter stimmen nicht überein.";
    exit;
}

if (strlen($password) < 8) {
    echo "Das Passwort muss mindestens 8 Zeichen lang sein.";
    exit;
}

$sqlCheck = "SELECT * FROM users WHERE email = '$email'";
$result = $connection->query($sqlCheck);

if ($result->num_rows > 0) {
    echo "Diese E-Mail ist bereits registriert.";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password_hash, role)
        VALUES ('$username', '$email', '$passwordHash', 'user')";

if ($connection->query($sql) === TRUE) {
    echo "Registrierung erfolgreich.";
} else {
    echo "Fehler bei der Registrierung: " . $connection->error;
}

$connection->close();

?>