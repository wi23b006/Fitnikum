<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <div class="navbar-inner">
        <span class="navbar-logo">Fitnikum</span>
        <ul class="navbar-links">
            <li><a href="homepage.php">Home</a></li>

            <li class="dropdown">
                <a href="#">Produkte</a>
                <ul class="dropdown-menu">
                    <li><a href="proteine.php">Proteine</a></li>
                    <li><a href="vitamine.php">Vitamine</a></li>
                    <li><a href="zubehoer.php">Zubehör</a></li>
                </ul>
            </li>

            <li><a href="ueberuns.php">Über uns</a></li>
        </ul>

        <?php if (isset($_SESSION["user_id"])) { ?>
            <a href="profil.php" class="navbar-cta">Profil</a>
            <a href="warenkorb.php" class="navbar-cta">Warenkorb</a>
            <a href="../../Backend/logic/logout.php" class="navbar-cta">Ausloggen</a>
        <?php } else { ?>
            <a href="login.php" class="navbar-cta">Einloggen</a>
        <?php } ?>
    </div>
</nav>
