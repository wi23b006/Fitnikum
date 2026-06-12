<?php
include("../includes/session.php");

// Schutz: nur Admin darf rein
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fitnikum – Produkte verwalten</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        table th, table td { border: 1px solid #555; padding: 8px; text-align: left; }
        table th { background-color: #1a1a1a; }
    </style>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">

    <h1>Produkte verwalten</h1>

    <div class="auth-form-panel" style="max-width:600px;">
        <h2 id="product-form-title">Neues Produkt anlegen</h2>

        <form id="product-form" class="auth-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="">

            <div class="form-group">
                <label>Name *</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Beschreibung</label>
                <textarea name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Kategorie *</label>
                <select name="category_id" required>
                    <option value="1">Proteine</option>
                    <option value="2">Vitamine</option>
                    <option value="3">Zubehör</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Preis (€) *</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Bewertung (0–5)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5" value="0">
                </div>
            </div>

            <div class="form-group">
                <label>Produktfoto</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>
            <button type="button" class="btn btn-outline" onclick="newProduct()">Neu</button>
        </form>
    </div>

    <h2 style="margin-top:40px;">Alle Produkte</h2>
    <table id="products-table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Kategorie</th><th>Preis</th><th>Bewertung</th><th>Bild</th><th>Aktion</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</section>

<script src="../js/admin.js"></script>

</body>
</html>