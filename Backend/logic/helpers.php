<?php
// Drei kleine Hilfsfunktionen, damit wir uns in den Endpoint-Dateien
// nicht ständig wiederholen müssen.

// Session starten (nur einmal, falls noch nicht läuft).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Schickt ein PHP-Array als JSON zurück und beendet das Script.
function sendJson($data) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data);
    exit;
}


// Falls jemand nicht eingeloggt ist: Fehler zurück, Script Ende.
function requireLogin() {
    if (!isset($_SESSION["user_id"])) {
        sendJson(["success" => false, "error" => "Bitte einloggen."]);
    }
}


// Falls jemand kein Admin ist: Fehler zurück, Script Ende.
function requireAdmin() {
    requireLogin();
    if ($_SESSION["role"] !== "admin") {
        sendJson(["success" => false, "error" => "Keine Admin-Rechte."]);
    }
}
?>