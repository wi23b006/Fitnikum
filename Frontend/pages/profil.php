<?php
include("../includes/session.php");

// Schutz: ohne Login → zurück zum Login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include("../../Backend/config/dbaccess.php");
$conn = getDatabaseConnection();
$stmt = $conn->prepare("SELECT salutation, firstname, lastname, username, email, address, postal_code, city FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fitnikum – Mein Konto</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">
    <div class="auth-form-panel" style="max-width:600px; margin:0 auto;">

        <h1>Mein Konto</h1>

        <p id="msg" style="color:green;"></p>
        <p id="err" style="color:red;"></p>

        <!-- Stammdaten bearbeiten -->
        <form id="profile-form" class="auth-form">
            <div class="form-group">
                <label>Anrede</label>
                <input type="text" value="<?php echo htmlspecialchars($user["salutation"]); ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Vorname</label><input type="text" name="firstname" value="<?php echo htmlspecialchars($user["firstname"]); ?>" required></div>
                <div class="form-group"><label>Nachname</label><input type="text" name="lastname"  value="<?php echo htmlspecialchars($user["lastname"]);  ?>" required></div>
            </div>
            <div class="form-group">
                <label>Benutzername</label>
                <input type="text" value="<?php echo htmlspecialchars($user["username"]); ?>" readonly>
            </div>
            <div class="form-group">
                <label>E-Mail</label>
                <input type="text" value="<?php echo htmlspecialchars($user["email"]); ?>" readonly>
            </div>
            <div class="form-group"><label>Adresse</label><input type="text" name="address" value="<?php echo htmlspecialchars($user["address"]); ?>"></div>
            <div class="form-row">
                <div class="form-group"><label>PLZ</label><input type="text" name="postal_code" value="<?php echo htmlspecialchars($user["postal_code"]); ?>"></div>
                <div class="form-group"><label>Ort</label><input type="text" name="city" value="<?php echo htmlspecialchars($user["city"]); ?>"></div>
            </div>

            <div class="form-group">
                <label>Aktuelles Passwort (zur Bestätigung)</label>
                <input type="password" name="current_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>
        </form>

        <!-- Bestellhistorie -->
        <h2 style="margin-top:40px;">Meine Bestellungen</h2>
        <div id="orders">Lade Bestellungen...</div>

    </div>
</section>

<script>
// Stammdaten speichern
$("#profile-form").on("submit", function(event) {
    event.preventDefault();
    $("#msg").text("");
    $("#err").text("");

    $.post("../../Backend/logic/update_profile.php", $(this).serialize(), function(response) {
        if (response.success) {
            $("#msg").text("Daten gespeichert.");
            $("input[name=current_password]").val("");
        } else {
            $("#err").text(response.error);
        }
    }, "json");
});

// Bestellungen laden + anzeigen
$.get("../../Backend/logic/get_orders.php", function(response) {
    var html = "";

    if (response.success && response.orders.length > 0) {
        for (var i = 0; i < response.orders.length; i++) {
            var o = response.orders[i];
            html += "<div class='cart-item'>";
            html += "<p><strong>Bestellung #" + o.id + "</strong> vom " + o.created_at + "</p>";
            html += "<p>Gesamt: " + o.total_price + " €</p>";
            html += "<ul>";
            for (var j = 0; j < o.items.length; j++) {
                var it = o.items[j];
                html += "<li>" + it.product_name + " – " + it.quantity + " × " + it.price + " €</li>";
            }
            html += "</ul>";
            html += "<button onclick=\"window.open('../../Backend/logic/get_invoice.php?order_id=" + o.id + "','_blank')\" class='btn btn-outline'>Rechnung drucken</button>";
            html += "</div>";
        }
    } else {
        html = "<p>Du hast noch keine Bestellungen.</p>";
    }

    $("#orders").html(html);
}, "json");
</script>

</body>
</html>