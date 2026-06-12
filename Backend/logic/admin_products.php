<?php
include("helpers.php");
include("../config/dbaccess.php");

requireAdmin();
$connection = getDatabaseConnection();

$action = $_GET["action"] ?? "";


if ($action == "list") {
    // Alle Produkte mit Kategorie-Namen für die Übersichtstabelle
    $result = $connection->query("SELECT p.id, p.name, p.description, p.price, p.rating, p.image, p.category_id, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id");

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    sendJson(["success" => true, "products" => $products]);
}


else if ($action == "create") {
    // Neues Produkt anlegen (Spec Admin 1a)
    $name        = trim($_POST["name"]        ?? "");
    $description = trim($_POST["description"] ?? "");
    $categoryId  = (int)($_POST["category_id"] ?? 0);
    $price       = (float)($_POST["price"]    ?? 0);
    $rating      = (float)($_POST["rating"]   ?? 0);

    if ($name == "" || $categoryId == 0 || $price <= 0) {
        sendJson(["success" => false, "error" => "Bitte Name, Kategorie und Preis ausfüllen."]);
    }

    // Bild hochladen falls vorhanden (Matrix VII 2P)
    $imageFile = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imageFile = uniqid() . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../Frontend/res/img/" . $imageFile);
    }

    $stmt = $connection->prepare("INSERT INTO products (name, description, category_id, price, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidds", $name, $description, $categoryId, $price, $rating, $imageFile);
    $stmt->execute();

    sendJson(["success" => true]);
}


else if ($action == "update") {
    // Produkt bearbeiten (Matrix VII 2P)
    $id          = (int)($_POST["id"] ?? 0);
    $name        = trim($_POST["name"]        ?? "");
    $description = trim($_POST["description"] ?? "");
    $categoryId  = (int)($_POST["category_id"] ?? 0);
    $price       = (float)($_POST["price"]    ?? 0);
    $rating      = (float)($_POST["rating"]   ?? 0);

    // Falls ein neues Bild hochgeladen wurde → austauschen, sonst altes behalten
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imageFile = uniqid() . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../Frontend/res/img/" . $imageFile);

        $stmt = $connection->prepare("UPDATE products SET name = ?, description = ?, category_id = ?, price = ?, rating = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssiddsi", $name, $description, $categoryId, $price, $rating, $imageFile, $id);
    } else {
        $stmt = $connection->prepare("UPDATE products SET name = ?, description = ?, category_id = ?, price = ?, rating = ? WHERE id = ?");
        $stmt->bind_param("ssiddi", $name, $description, $categoryId, $price, $rating, $id);
    }

    $stmt->execute();
    sendJson(["success" => true]);
}


else if ($action == "delete") {
    // Produkt löschen (Matrix VII 2P)
    $id = (int)($_POST["id"] ?? 0);

    $stmt = $connection->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    sendJson(["success" => true]);
}


else {
    sendJson(["success" => false, "error" => "Unbekannte Aktion."]);
}
?>