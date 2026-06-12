<?php
include("helpers.php");
include("../config/dbaccess.php");

$connection = getDatabaseConnection();
$categoryId = (int)($_GET["category"] ?? 0);

// Produkte einer Kategorie laden (Spec 3a)
$stmt = $connection->prepare("SELECT id, name, description, price, rating, image FROM products WHERE category_id = ?");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($product = $result->fetch_assoc()) {
    $products[] = $product;
}

sendJson(["success" => true, "products" => $products]);
?>