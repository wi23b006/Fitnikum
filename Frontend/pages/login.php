<?php include("../includes/session.php"); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Login</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">
    <div class="auth-form-panel" style="max-width:400px; margin:0 auto;">
        <h1>Login</h1>

        <p id="error" style="color:red;"></p>

        <form id="login-form" class="auth-form">
            <div class="form-group">
                <label>Benutzername oder E-Mail</label>
                <input type="text" name="login" required>
            </div>
            <div class="form-group">
                <label>Passwort</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Login merken
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Einloggen</button>
        </form>

        <p style="margin-top:20px;">
            Noch kein Konto? <a href="register.php">Jetzt registrieren</a>
        </p>
    </div>
</section>

<script>
$("#login-form").on("submit", function(event) {
    event.preventDefault();
    $.post("../../Backend/logic/login.php", $(this).serialize(), function(response) {
        if (response.success) {
            window.location.href = "homepage.php";
        } else {
            $("#error").text(response.error);
        }
    }, "json");
});
</script>

</body>
</html>