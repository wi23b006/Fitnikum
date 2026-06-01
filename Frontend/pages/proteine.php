<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Proteine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>

<?php include("../includes/navbar.php"); ?>

    <section class="hero">
        <span class="hero-tag">Produkte</span>
        <h1>Proteine</h1>
        <p>
            Hier findest du verschiedene Proteinprodukte. Die Produkte sind aktuell nur Platzhalter
            und werden später im Projekt vollständig ergänzt.
        </p>
    </section>

    <section class="categories">
        <p class="section-label">Protein Produkte</p>

        <div class="search-bar">
            <input type="text" id="suchfeld" placeholder="Produkt suchen..." onkeyup="produkteSuchen()">
        </div>

        <div id="product-list" class="product-grid" data-category="proteine"></div>

    </section>

    <footer>
        <span class="navbar-logo" style="font-size:1.1rem;">Fitnikum</span>
        <span>© 2026 Fitnikum. Alle Rechte vorbehalten.</span>
    </footer>
    <script src="../js/script.js"></script>

</body>
</html>
