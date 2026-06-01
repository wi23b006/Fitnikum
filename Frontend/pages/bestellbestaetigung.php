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

    <?php include("../includes/navbar.php"); ?>

    <div class="auth-wrapper">
        <div class="auth-form-panel">

            <h1>Bestellung erfolgreich</h1>

            <p>Danke für deine Bestellung.</p>
            <p>Deine Bestellung wurde gespeichert.</p>

            <br>

            <a href="homepage.php" style="display: inline-block; padding: 10px 18px; background-color: #c6ff2e; color: black; text-decoration: none; border-radius: 8px; font-weight: bold;">
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