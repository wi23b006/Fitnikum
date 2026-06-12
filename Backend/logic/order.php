<?php
include("helpers.php");
include("../config/dbaccess.php");

requireLogin();
$connection = getDatabaseConnection();

if (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) == 0) {
    sendJson(["success" => false, "error" => "Warenkorb ist leer."]);
}

$userId        = $_SESSION["user_id"];
$paymentMethod = trim($_POST["payment_method"] ?? "");
$voucherCode   = trim($_POST["voucher_code"]   ?? "");

if ($paymentMethod == "" && $voucherCode == "") {
    sendJson(["success" => false, "error" => "Bitte Zahlungsmethode oder Gutschein wählen."]);
}

// Gesamtbetrag berechnen
$total = 0;
foreach ($_SESSION["cart"] as $item) {
    $total += $item["price"] * $item["quantity"];
}

// Gutschein einlösen (Spec V.c: Restwert bleibt erhalten)
$voucherUsedAmount = 0;
if ($voucherCode != "") {

    $stmt = $connection->prepare("SELECT id, remaining_value, expires_at FROM vouchers WHERE code = ?");
    $stmt->bind_param("s", $voucherCode);
    $stmt->execute();
    $voucher = $stmt->get_result()->fetch_assoc();

    if (!$voucher) {
        sendJson(["success" => false, "error" => "Gutschein-Code nicht gefunden."]);
    }
    if ($voucher["expires_at"] < date("Y-m-d")) {
        sendJson(["success" => false, "error" => "Gutschein ist abgelaufen."]);
    }

    $remaining = (float)$voucher["remaining_value"];

    if ($remaining >= $total) {
        // Gutschein deckt alles → Restwert bleibt
        $voucherUsedAmount = $total;
        $newRemaining = $remaining - $total;
    } else {
        // Nur ein Teil wird abgedeckt → Gutschein leer
        $voucherUsedAmount = $remaining;
        $newRemaining = 0;
    }

    $stmt = $connection->prepare("UPDATE vouchers SET remaining_value = ? WHERE id = ?");
    $stmt->bind_param("di", $newRemaining, $voucher["id"]);
    $stmt->execute();
}

// Bestellung anlegen (prepared statement → kein SQL-Injection-Risiko mehr)
$stmt = $connection->prepare("INSERT INTO orders (user_id, total_price, payment_method, voucher_code, voucher_used_amount) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("idssd", $userId, $total, $paymentMethod, $voucherCode, $voucherUsedAmount);
$stmt->execute();
$orderId = $stmt->insert_id;

// Bestellpositionen anlegen
foreach ($_SESSION["cart"] as $item) {
    $stmt = $connection->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdi", $orderId, $item["id"], $item["name"], $item["price"], $item["quantity"]);
    $stmt->execute();
}

// Warenkorb leeren
$_SESSION["cart"] = array();

sendJson(["success" => true, "order_id" => $orderId]);
?>