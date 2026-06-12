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
    <title>Fitnikum – Kunden verwalten</title>
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

    <h1>Kunden verwalten</h1>

    <table id="customers-table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Username</th><th>E-Mail</th><th>Status</th><th>Aktion</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</section>

<script src="../js/admin.js"></script>

</body>
</html>