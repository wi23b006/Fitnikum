<?php include("../includes/session.php"); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Vitamine</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">
    <p class="section-label">Vitamine</p>
    <h1>Unsere Vitamin-Produkte</h1>

    <input type="text" id="suchfeld" placeholder="Suchen ..." class="form-group" style="padding:10px; width:300px; margin-bottom:20px;">

    <div id="product-list" class="category-grid" data-category="2"></div>
</section>

<script src="../js/shop.js"></script>

</body>
</html>