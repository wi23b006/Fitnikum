<?php

session_start();

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum - Warenkorb</title>
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>

    <?php include("../includes/navbar.php"); ?>

    <div class="auth-wrapper">
        <div class="auth-form-panel">

            <h1>Warenkorb</h1>

            <?php

            if (count($_SESSION["cart"]) == 0) {

                echo "<p>Dein Warenkorb ist leer.</p>";

            } else {

                $gesamt = 0;

                foreach ($_SESSION["cart"] as $product) {

                    $zwischensumme = $product["price"] * $product["quantity"];
                    $gesamt = $gesamt + $zwischensumme;

                    echo "<div class='cart-item'>";

                    echo "<h3>" . $product["name"] . "</h3>";
                    echo "<p>Einzelpreis: " . $product["price"] . " €</p>";
                    echo "<p>Menge: " . $product["quantity"] . "</p>";
                    echo "<p>Zwischensumme: " . $zwischensumme . " €</p>";

                    echo "<div style='margin-top: 15px; margin-bottom: 15px;'>";

                    echo "<a href='../../Backend/logic/cart.php?action=minus&id=" . $product["id"] . "' style='padding: 8px 14px; background-color: #dddddd; color: black; text-decoration: none; border-radius: 6px; margin-right: 8px;'>";
                    echo "-";
                    echo "</a>";

                    echo "<span style='font-weight: bold; margin-right: 8px;'>";
                    echo $product["quantity"];
                    echo "</span>";

                    echo "<a href='../../Backend/logic/cart.php?action=plus&id=" . $product["id"] . "' style='padding: 8px 14px; background-color: #dddddd; color: black; text-decoration: none; border-radius: 6px;'>";
                    echo "+";
                    echo "</a>";

                    echo "</div>";

                    echo "<a href='../../Backend/logic/cart.php?action=remove&id=" . $product["id"] . "' style='display: inline-block; padding: 8px 14px; background-color: #c0392b; color: white; text-decoration: none; border-radius: 6px;'>";
                    echo "Produkt entfernen";
                    echo "</a>";

                    echo "</div>";
                }

                echo "<hr>";
                echo "<h2>Gesamt: " . $gesamt . " €</h2>";

                echo "<br>";

                echo "<a href='../../Backend/logic/order.php' style='display: inline-block; padding: 12px 20px; background-color: #c6ff2e; color: black; text-decoration: none; border-radius: 8px; font-weight: bold;'>";
                echo "Bestellung aufgeben";
                echo "</a>";
            }

            ?>

        </div>
    </div>

    <footer>
        <span>© 2026 Fitnikum</span>
        <span>Datenschutz · Impressum</span>
    </footer>

</body>
</html>