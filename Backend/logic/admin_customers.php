<?php
include("helpers.php");
include("../config/dbaccess.php");

requireAdmin();
$connection = getDatabaseConnection();

$action = $_GET["action"] ?? "";


if ($action == "list") {
    // Alle Kunden auflisten (keine Admins)
    $result = $connection->query("SELECT id, firstname, lastname, username, email, active FROM users WHERE role = 'user' ORDER BY id");

    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    sendJson(["success" => true, "customers" => $customers]);
}


else if ($action == "toggle") {
    // Kunde aktiv/inaktiv setzen (Matrix VIII 3P)
    $id     = (int)($_POST["id"] ?? 0);
    $active = (int)($_POST["active"] ?? 0);

    $stmt = $connection->prepare("UPDATE users SET active = ? WHERE id = ? AND role = 'user'");
    $stmt->bind_param("ii", $active, $id);
    $stmt->execute();

    sendJson(["success" => true]);
}


else if ($action == "orders") {
    // Alle Bestellungen eines Kunden mit Positionen (Matrix VIII 5P)
    $userId = (int)($_GET["user_id"] ?? 0);

    $stmt = $connection->prepare("SELECT id, total_price, payment_method, invoice_number, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($order = $result->fetch_assoc()) {

        // Positionen dazu (auch die ausgeblendeten – damit der Admin sie wieder sieht)
        $stmt2 = $connection->prepare("SELECT id, product_name, price, quantity, visible FROM order_items WHERE order_id = ?");
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
}


else if ($action == "hide_item") {
    // Einzelne Bestellposition ausblenden (Matrix VIII 4P)
    $itemId = (int)($_POST["item_id"] ?? 0);

    $stmt = $connection->prepare("UPDATE order_items SET visible = 0 WHERE id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();

    sendJson(["success" => true]);
}


else {
    sendJson(["success" => false, "error" => "Unbekannte Aktion."]);
}
?>