<?php include("../includes/session.php"); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Warenkorb</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">
    <div class="auth-form-panel" style="max-width:700px; margin:0 auto;">

        <h1>Warenkorb</h1>

        <!-- Liste der Produkte im Warenkorb -->
        <div id="cart-container">Lade Warenkorb...</div>

        <!-- Gesamtpreis + Bestellbereich -->
        <div id="checkout-area" style="display:none; margin-top:30px;">

            <h2>Gesamt: <span id="cart-total"></span></h2>

            <?php if (!isset($_SESSION["user_id"])) { ?>
                <p style="margin-top:20px;">
                    Zum Bestellen bitte <a href="login.php">einloggen</a>.
                </p>
            <?php } else { ?>

                <div class="form-group" style="margin-top:20px;">
                    <label>Zahlungsmethode</label>
                    <select id="payment-method">
                        <option value="">Bitte wählen</option>
                        <option value="Rechnung">Rechnung</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Kreditkarte">Kreditkarte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gutschein-Code (optional)</label>
                    <input type="text" id="voucher-code" placeholder="z. B. TEST1">
                </div>

                <p id="order-error" style="color:red;"></p>

                <button class="btn btn-primary" onclick="bestellen()">Bestellung aufgeben</button>

            <?php } ?>
        </div>

    </div>
</section>

<script src="../js/shop.js"></script>

</body>
</html>