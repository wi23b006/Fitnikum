<?php
include("helpers.php");
include("../config/dbaccess.php");

$connection = getDatabaseConnection();

$query      = trim($_GET["q"]        ?? "");
$categoryId = (int)($_GET["category"] ?? 0);

// LIKE-Suche: %query% findet "Vitamin" auch in "Multivitamin"
$like = "%" . $query . "%";

$stmt = $connection->prepare("SELECT id, name, description, price, rating, image FROM products WHERE category_id = ? AND name LIKE ?");
$stmt->bind_param("is", $categoryId, $like);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($product = $result->fetch_assoc()) {
    $products[] = $product;
}

sendJson(["success" => true, "products" => $products]);
?>