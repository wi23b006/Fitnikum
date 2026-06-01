<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Einloggen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>

<?php include("../includes/navbar.php"); ?>

    <div class="auth-wrapper">
        <div class="auth-brand">
            <div><span class="nav-logo">Fitnikum</span></div>
            <div>
                <h2>Dein Training.<br>Dein <span>Shop.</span></h2>
                <p>Meld dich an und greif auf deine Bestellungen, Wunschlisten und persönliche Empfehlungen zu.</p>
            </div>
            <div class="auth-brand-footer">
                Noch kein Konto? <a href="register.php" style="color: var(--accent);">Jetzt registrieren →</a>
            </div>
        </div>

        <div class="auth-form-panel">
            <h1>Einloggen</h1>
            <p class="auth-subtitle">Willkommen zurück.</p>

            <form class="auth-form" action="../../Backend/logic/login.php" method="post">
                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" placeholder="deine@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Einloggen</button>

                <div class="auth-divider">oder</div>
                <div class="auth-link">Noch kein Konto? <a href="register.php">Jetzt registrieren</a></div>
            </form>
        </div>
    </div>

    <footer>
        <span>© 2026 Fitnikum</span>
        <span>Datenschutz · Impressum</span>
    </footer>

</body>
</html>
