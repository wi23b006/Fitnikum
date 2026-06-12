<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role     = $_SESSION["role"]     ?? null;
$username = $_SESSION["username"] ?? null;
?>
<nav class="navbar">
    <div class="navbar-inner">
        <span class="navbar-logo">Fitnikum</span>
        <ul class="navbar-links">

            <?php if ($role === "admin") { ?>
                <!-- Admin-Menü (Spec 2d.iii) -->
                <li><a href="homepage.php">Home</a></li>
                <li><a href="admin_produkte.php">Produkte bearbeiten</a></li>
                <li><a href="admin_kunden.php">Kunden bearbeiten</a></li>
                <li><a href="admin_gutscheine.php">Gutscheine verwalten</a></li>
            <?php } else { ?>
                <!-- Gast (Spec 2d.i) und User (Spec 2d.ii) -->
                <li><a href="homepage.php">Home</a></li>
                <li class="dropdown">
                    <a href="#">Produkte</a>
                    <ul class="dropdown-menu">
                        <li><a href="proteine.php">Proteine</a></li>
                        <li><a href="vitamine.php">Vitamine</a></li>
                        <li><a href="zubehoer.php">Zubehör</a></li>
                    </ul>
                </li>
                <?php if ($role === "user") { ?>
                    <li><a href="profil.php">Mein Konto</a></li>
                <?php } ?>
                <!-- Warenkorb-Link ist Drop-Ziel und zeigt Anzahl-Badge -->
                <li>
                    <a href="warenkorb.php" id="cart-link">
                        🛒 Warenkorb (<span id="cart-badge">0</span>)
                    </a>
                </li>
            <?php } ?>

        </ul>

        <?php if ($role) { ?>
            <span class="navbar-cta">Eingeloggt als <?php echo htmlspecialchars($username); ?></span>
            <a href="../../Backend/logic/logout.php" class="navbar-cta">Ausloggen</a>
        <?php } else { ?>
            <a href="login.php" class="navbar-cta">Einloggen</a>
        <?php } ?>
    </div>
</nav>