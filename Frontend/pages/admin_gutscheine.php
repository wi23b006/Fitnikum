<?php
include("../includes/session.php");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Fitnikum – Gutscheine verwalten</title>
    <link rel="stylesheet" href="../res/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        table th, table td { border: 1px solid #555; padding: 8px; text-align: left; }
        table th { background-color: #1a1a1a; }
    </style>
</head>
<body>

<?php include("../includes/navbar.php"); ?>

<section class="categories">

    <h1>Gutscheine verwalten</h1>

    <div class="auth-form-panel" style="max-width:500px;">
        <h2>Neuen Gutschein anlegen</h2>

        <form id="voucher-form" class="auth-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Wert (€) *</label>
                    <input type="number" name="value" step="0.01" min="0.01" required>
                </div>
                <div class="form-group">
                    <label>Ablaufdatum *</label>
                    <input type="date" name="expires_at" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Gutschein erstellen</button>
        </form>
    </div>

    <h2 style="margin-top:40px;">Alle Gutscheine</h2>
    <table id="vouchers-table">
        <thead>
            <tr>
                <th>Code</th><th>Wert</th><th>Restwert</th><th>Ablauf</th><th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</section>

<script src="../js/admin.js"></script>

</body>
</html>