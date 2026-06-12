<?php
// Session starten und ggf. via "Login merken"-Cookie wieder einloggen.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Keine Session aktiv, aber Cookie noch da → User über Token einloggen (Spec 2e/f)
if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember"])) {

    include_once __DIR__ . "/../../Backend/config/dbaccess.php";
    $conn = getDatabaseConnection();

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE remember_token = ? AND active = 1");
    $stmt->bind_param("s", $_COOKIE["remember"]);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($u = $res->fetch_assoc()) {
        $_SESSION["user_id"]  = $u["id"];
        $_SESSION["username"] = $u["username"];
        $_SESSION["role"]     = $u["role"];
    }
}
?>