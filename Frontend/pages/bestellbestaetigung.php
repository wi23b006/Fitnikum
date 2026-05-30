<?php

session_start();

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum - Bestellbestätigung</title>
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="navbar-inner">
            <span class="navbar-logo">Fitnikum</span>

            <ul class="navbar-links">
                <li><a href="homepage.html">Home</a></li>
                <li><a href="proteine.html">Produkte</a></li>
                <li><a href="vitamine.html">Ernährung</a></li>
                <li><a href="zubehoer.html">Zubehör</a></li>
            </ul>

            <a href="warenkorb.php" class="navbar-cta">Warenkorb</a>
        </div>
    </nav>

    <div class="auth-wrapper">
        <div class="auth-form-panel">

            <h1>Bestellung erfolgreich</h1>

            <p>Danke für deine Bestellung.</p>
            <p>Deine Bestellung wurde gespeichert.</p>

            <br>

            <a href="homepage.html" style="display: inline-block; padding: 10px 18px; background-color: #c6ff2e; color: black; text-decoration: none; border-radius: 8px; font-weight: bold;">
                Zurück zur Startseite
            </a>

        </div>
    </div>

    <footer>
        <span>© 2026 Fitnikum</span>
        <span>Datenschutz · Impressum</span>
    </footer>

</body>
</html>