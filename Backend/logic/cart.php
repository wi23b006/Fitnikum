<?php
include("helpers.php");

// Warenkorb in der Session anlegen, falls noch keiner da ist
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

$action = $_GET["action"] ?? "";

if ($action == "add") {

    // Produkt-ID kommt per POST vom Frontend
    include("../config/dbaccess.php");
    $connection = getDatabaseConnection();

    $productId = (int)($_POST["id"] ?? 0);

    // Produkt aus der DB holen (DB-Statements nur im BE - Matrix 4P)
    $stmt = $connection->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        sendJson(["success" => false, "error" => "Produkt nicht gefunden."]);
    }

    if (isset($_SESSION["cart"][$productId])) {
        // Schon im Warenkorb → Menge erhöhen
        $_SESSION["cart"][$productId]["quantity"]++;
    } else {
        // Neu in den Warenkorb
        $_SESSION["cart"][$productId] = [
            "id"       => $product["id"],
            "name"     => $product["name"],
            "price"    => $product["price"],
            "quantity" => 1
        ];
    }

    sendJson(["success" => true, "count" => cartCount()]);
}

else if ($action == "remove") {
    $productId = (int)($_POST["id"] ?? 0);
    unset($_SESSION["cart"][$productId]);
    sendJson(["success" => true, "count" => cartCount()]);
}

else if ($action == "plus") {
    $productId = (int)($_POST["id"] ?? 0);
    if (isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId]["quantity"]++;
    }
    sendJson(["success" => true, "count" => cartCount()]);
}

else if ($action == "minus") {
    $productId = (int)($_POST["id"] ?? 0);
    if (isset($_SESSION["cart"][$productId])) {
        $_SESSION["cart"][$productId]["quantity"]--;
        if ($_SESSION["cart"][$productId]["quantity"] <= 0) {
            unset($_SESSION["cart"][$productId]);
        }
    }
    sendJson(["success" => true, "count" => cartCount()]);
}

else if ($action == "count") {
    // Anzahl Produkte für das Badge in der Navbar
    sendJson(["success" => true, "count" => cartCount()]);
}

else if ($action == "list") {
    // Kompletter Warenkorb-Inhalt + Gesamtpreis
    $items = array_values($_SESSION["cart"]);
    $total = 0;
    foreach ($items as $item) {
        $total += $item["price"] * $item["quantity"];
    }
    sendJson(["success" => true, "items" => $items, "total" => $total]);
}

else {
    sendJson(["success" => false, "error" => "Unbekannte Aktion."]);
}


// Kleine Hilfsfunktion: zählt alle Stückzahlen im Warenkorb
function cartCount() {
    $total = 0;
    foreach ($_SESSION["cart"] as $item) {
        $total += $item["quantity"];
    }
    return $total;
}
?>