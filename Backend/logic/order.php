<?php

session_start();

include("../config/dbaccess.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Frontend/pages/login.php");
    exit;
}

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

if (count($_SESSION["cart"]) == 0) {
    header("Location: ../../Frontend/pages/warenkorb.php");
    exit;
}

$connection = getDatabaseConnection();

$user_id = $_SESSION["user_id"];
$gesamt = 0;

foreach ($_SESSION["cart"] as $product) {
    $zwischensumme = $product["price"] * $product["quantity"];
    $gesamt = $gesamt + $zwischensumme;
}

$sql = "INSERT INTO orders (user_id, total_price, created_at)
        VALUES ('$user_id', '$gesamt', NOW())";

if ($connection->query($sql) === TRUE) {

    $order_id = $connection->insert_id;

    foreach ($_SESSION["cart"] as $product) {

        $product_id = $product["id"];
        $product_name = $product["name"];
        $price = $product["price"];
        $quantity = $product["quantity"];

        $sqlItem = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                    VALUES ('$order_id', '$product_id', '$product_name', '$price', '$quantity')";

        $connection->query($sqlItem);
    }

    $_SESSION["cart"] = array();

    header("Location: ../../Frontend/pages/bestellbestaetigung.php");
    exit;

} else {
    echo "Fehler bei der Bestellung: " . $connection->error;
}

$connection->close();

?>