<?php
include("helpers.php");
include("../config/dbaccess.php");

requireAdmin();
$connection = getDatabaseConnection();

$action = $_GET["action"] ?? "";


if ($action == "list") {
    // Alle Gutscheine + Status (aktiv / eingelöst / abgelaufen)
    $result = $connection->query("SELECT id, code, value, remaining_value, expires_at FROM vouchers ORDER BY id DESC");

    $vouchers = [];
    $today = date("Y-m-d");

    while ($row = $result->fetch_assoc()) {
        if ($row["expires_at"] < $today) {
            $row["status"] = "abgelaufen";
        } else if ((float)$row["remaining_value"] == 0) {
            $row["status"] = "eingelöst";
        } else {
            $row["status"] = "aktiv";
        }
        $vouchers[] = $row;
    }
    sendJson(["success" => true, "vouchers" => $vouchers]);
}


else if ($action == "create") {
    // Neuen Gutschein anlegen (Spec Admin 3a)
    $value     = (float)($_POST["value"] ?? 0);
    $expiresAt = $_POST["expires_at"] ?? "";

    if ($value <= 0 || $expiresAt == "") {
        sendJson(["success" => false, "error" => "Bitte Wert und Ablaufdatum eingeben."]);
    }

    // 5-stelliger alphanumerischer Code – ohne verwirrende Zeichen wie 0/O, 1/I
    $code = strtoupper(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5));

    $stmt = $connection->prepare("INSERT INTO vouchers (code, value, remaining_value, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdds", $code, $value, $value, $expiresAt);
    $stmt->execute();

    sendJson(["success" => true, "code" => $code]);
}


else {
    sendJson(["success" => false, "error" => "Unbekannte Aktion."]);
}
?>