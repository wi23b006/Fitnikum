<?php
include("helpers.php");
include("../config/dbaccess.php");

requireLogin();
$connection = getDatabaseConnection();
$userId = $_SESSION["user_id"];

// Bestellungen des Users, neueste zuerst (Spec 6c)
$stmt = $connection->prepare("SELECT id, total_price, payment_method, invoice_number, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($order = $result->fetch_assoc()) {

    // Positionen pro Bestellung (nur sichtbare - Matrix VIII)
    $stmt2 = $connection->prepare("SELECT product_name, price, quantity FROM order_items WHERE order_id = ? AND visible = 1");
    $stmt2->bind_param("i", $order["id"]);
    $stmt2->execute();
    $items = $stmt2->get_result();

    $order["items"] = [];
    while ($item = $items->fetch_assoc()) {
        $order["items"][] = $item;
    }
    $orders[] = $order;
}

sendJson(["success" => true, "orders" => $orders]);
?>