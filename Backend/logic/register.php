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

$stmtCheck = $connection->prepare("SELECT * FROM users WHERE email = ?");
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows > 0) {
    echo "Diese E-Mail ist bereits registriert.";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$role = "user";

$stmt = $connection->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $passwordHash, $role);

if ($stmt->execute()) {
    echo "Registrierung erfolgreich.";
} else {
    echo "Fehler bei der Registrierung: " . $connection->error;
}

$stmt->close();
$connection->close();

?>