<?php
include("helpers.php");
include("../config/dbaccess.php");

// Hier kein sendJson – wir geben HTML aus für den Druck.
if (!isset($_SESSION["user_id"])) {
    echo "Bitte einloggen.";
    exit;
}

$connection = getDatabaseConnection();
$orderId = (int)($_GET["order_id"] ?? 0);
$userId  = $_SESSION["user_id"];

// Bestellung holen + Besitz prüfen
$stmt = $connection->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Bestellung nicht gefunden.";
    exit;
}

// Rechnungsnummer generieren, falls noch keine vergeben (Spec 6c)
if ($order["invoice_number"] === null) {
    $invoiceNumber = "RG-" . date("Y") . "-" . str_pad($order["id"], 5, "0", STR_PAD_LEFT);
    $stmt = $connection->prepare("UPDATE orders SET invoice_number = ? WHERE id = ?");
    $stmt->bind_param("si", $invoiceNumber, $orderId);
    $stmt->execute();
    $order["invoice_number"] = $invoiceNumber;
}

// User-Anschrift
$stmt = $connection->prepare("SELECT firstname, lastname, address, postal_code, city FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Positionen
$stmt = $connection->prepare("SELECT product_name, price, quantity FROM order_items WHERE order_id = ? AND visible = 1");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung <?php echo $order["invoice_number"]; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        h1 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .total { font-weight: bold; }
        .print-btn { padding: 10px 20px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Rechnung</h1>
    <p>Rechnungsnummer: <strong><?php echo $order["invoice_number"]; ?></strong></p>
    <p>Datum: <?php echo $order["created_at"]; ?></p>

    <h3>Rechnungsanschrift</h3>
    <p>
        <?php echo $user["firstname"] . " " . $user["lastname"]; ?><br>
        <?php echo $user["address"]; ?><br>
        <?php echo $user["postal_code"] . " " . $user["city"]; ?>
    </p>

    <h3>Positionen</h3>
    <table>
        <tr><th>Produkt</th><th>Preis</th><th>Menge</th><th>Summe</th></tr>
        <?php while ($item = $items->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $item["product_name"]; ?></td>
                <td><?php echo number_format($item["price"], 2); ?> €</td>
                <td><?php echo $item["quantity"]; ?></td>
                <td><?php echo number_format($item["price"] * $item["quantity"], 2); ?> €</td>
            </tr>
        <?php } ?>
        <tr class="total"><td colspan="3">Gesamt</td><td><?php echo number_format($order["total_price"], 2); ?> €</td></tr>
    </table>

    <button class="print-btn" onclick="window.print()">Rechnung drucken</button>
</body>
</html>