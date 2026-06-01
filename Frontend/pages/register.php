<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitnikum – Registrieren</title>
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
                <h2>Kostenlos<br><span>starten.</span></h2>
                <p>Erstell jetzt dein kostenloses Konto und profitier von allen Vorteilen.</p>
                <ul class="perks">
                    <li>Bestellhistorie &amp; Tracking</li>
                    <li>Persönliche Wunschliste</li>
                    <li>Exklusive Mitgliederangebote</li>
                    <li>Schnellerer Checkout</li>
                </ul>
            </div>
            <div class="auth-brand-footer">
                Bereits registriert? <a href="login.php" style="color: var(--accent);">Jetzt einloggen →</a>
            </div>
        </div>

        <div class="auth-form-panel">
            <h1>Konto erstellen</h1>
            <p class="auth-subtitle">Dauert nur eine Minute.</p>

            <form class="auth-form" action="../../Backend/logic/register.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">Vorname</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Max" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Nachname</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Mustermann" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" placeholder="deine@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Passwort</label>
                    <input type="password" id="password" name="password" placeholder="Mindestens 8 Zeichen" required>
                </div>

                <div class="form-group">
                    <label for="password2">Passwort bestätigen</label>
                    <input type="password" id="password2" name="password2" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Konto erstellen</button>

                <div class="terms-note">
                    Mit der Registrierung stimmst du unseren <a href="#">AGB</a> und der <a href="#">Datenschutzerklärung</a> zu.
                </div>

                <div class="auth-divider">oder</div>

                <div class="auth-link">
                    Bereits registriert? <a href="login.php">Jetzt einloggen</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <span>© 2026 Fitnikum</span>
        <span>Datenschutz · Impressum</span>
    </footer>

</body>
</html>
