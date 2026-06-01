<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Frontend/pages/login.php");
    exit;
}

include("../config/dbaccess.php");
$connection = getDatabaseConnection();

$userId = $_SESSION["user_id"];
$username = $_POST["username"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$postal_code = $_POST["postal_code"];
$city = $_POST["city"];
$payment_method = $_POST["payment_method"];

if ($username == "") {
    echo "Bitte einen Namen eingeben.";
    exit;
}

$sql = "UPDATE users SET
            username = '$username',
            phone = '$phone',
            address = '$address',
            postal_code = '$postal_code',
            city = '$city',
            payment_method = '$payment_method'
        WHERE id = $userId";

if ($connection->query($sql) === TRUE) {
    $_SESSION["username"] = $username;
    header("Location: ../../Frontend/pages/profil.php");
    exit;
} else {
    echo "Fehler beim Speichern: " . $connection->error;
}

$connection->close();

?>