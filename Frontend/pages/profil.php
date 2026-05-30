<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

include("../../Backend/config/dbaccess.php");
$connection = getDatabaseConnection();

$userId = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = $userId";
$result = $connection->query($sql);
$user = $result->fetch_assoc();

function feld($wert) {
    if ($wert == null) {
        return "";
    }
    return $wert;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Profil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <span class="navbar-logo">Fitnikum</span>
        <ul class="navbar-links">
            <li><a href="homepage.html">Home</a></li>
            <li class="dropdown">
                <a href="#">Produkte</a>
                <ul class="dropdown-menu">
                    <li><a href="proteine.html">Proteine</a></li>
                    <li><a href="vitamine.html">Vitamine</a></li>
                    <li><a href="zubehoer.html">Zubehör</a></li>
                </ul>
            </li>
            <li><a href="ueberuns.html">Über uns</a></li>
        </ul>
        <a href="../../Backend/logic/logout.php" class="navbar-cta">Ausloggen</a>
    </div>
</nav>

<section class="categories">
    <div class="auth-form-panel" style="max-width:500px; margin:0 auto;">
        <h1>Mein Profil</h1>
        <p class="auth-subtitle">Hier kannst du deine Daten bearbeiten.</p>

        <form class="auth-form" action="../../Backend/logic/update_profile.php" method="post">
            <div class="form-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" value="<?php echo feld($user["username"]); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-Mail</label>
                <input type="email" id="email" value="<?php echo feld($user["email"]); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="phone">Telefon</label>
                <input type="text" id="phone" name="phone" value="<?php echo feld($user["phone"]); ?>" placeholder="z. B. 0664 1234567">
            </div>

            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" id="address" name="address" value="<?php echo feld($user["address"]); ?>" placeholder="Straße und Hausnummer">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="postal_code">PLZ</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?php echo feld($user["postal_code"]); ?>">
                </div>
                <div class="form-group">
                    <label for="city">Ort</label>
                    <input type="text" id="city" name="city" value="<?php echo feld($user["city"]); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="payment_method">Zahlungsmethode</label>
                <select id="payment_method" name="payment_method">
                    <option value="">Bitte wählen</option>
                    <option value="Rechnung" <?php if ($user["payment_method"] == "Rechnung") echo "selected"; ?>>Rechnung</option>
                    <option value="PayPal" <?php if ($user["payment_method"] == "PayPal") echo "selected"; ?>>PayPal</option>
                    <option value="Kreditkarte" <?php if ($user["payment_method"] == "Kreditkarte") echo "selected"; ?>>Kreditkarte</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>
        </form>
    </div>
</section>

<footer>
    <span class="navbar-logo" style="font-size:1.1rem;">Fitnikum</span>
    <span>© 2026 Fitnikum. Alle Rechte vorbehalten.</span>
</footer>

</body>
</html>