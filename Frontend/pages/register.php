<?php include("../includes/session.php"); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Registrieren</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">
    <div class="auth-form-panel" style="max-width:500px; margin:0 auto;">
        <h1>Registrieren</h1>

        <p id="error" style="color:red;"></p>

        <form id="register-form" class="auth-form">
            <div class="form-group">
                <label>Anrede</label>
                <select name="salutation" required>
                    <option value="">Bitte wählen</option>
                    <option value="Herr">Herr</option>
                    <option value="Frau">Frau</option>
                    <option value="Divers">Divers</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Vorname</label><input type="text" name="firstname" required></div>
                <div class="form-group"><label>Nachname</label><input type="text" name="lastname" required></div>
            </div>
            <div class="form-group"><label>Adresse</label><input type="text" name="address" required></div>
            <div class="form-row">
                <div class="form-group"><label>PLZ</label><input type="text" name="postal_code" required></div>
                <div class="form-group"><label>Ort</label><input type="text" name="city" required></div>
            </div>
            <div class="form-group"><label>E-Mail</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Benutzername</label><input type="text" name="username" required></div>
            <div class="form-row">
                <div class="form-group"><label>Passwort</label><input type="password" name="password" minlength="8" required></div>
                <div class="form-group"><label>Passwort wiederholen</label><input type="password" name="password2" minlength="8" required></div>
            </div>
            <button type="submit" class="btn btn-primary">Konto anlegen</button>
        </form>
    </div>
</section>

<script>
// Registrierung per AJAX absenden
$("#register-form").on("submit", function(event) {
    event.preventDefault();

    // Clientseitige Validierung: Passwörter müssen übereinstimmen
    var pw  = $("input[name=password]").val();
    var pw2 = $("input[name=password2]").val();
    if (pw !== pw2) {
        $("#error").text("Passwörter stimmen nicht überein.");
        return;
    }

    $.post("../../Backend/logic/register.php", $(this).serialize(), function(response) {
        if (response.success) {
            alert("Registrierung erfolgreich. Du kannst dich jetzt einloggen.");
            window.location.href = "login.php";
        } else {
            $("#error").text(response.error);
        }
    }, "json");
});
</script>

</body>
</html>