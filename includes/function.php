<?php
// includes/functions.php
require_once 'config.php';

function getClientReports($clientId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE client_id = ? AND status != 'completed' ORDER BY created_at DESC");
    $stmt->execute([$clientId]);
    return $stmt->fetchAll();
}

function getClientData($clientId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$clientId]);
    return $stmt->fetch();
}

function getMessages() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sendMessage($employeeId, $message) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO messages (employee_id, message) VALUES (?, ?)");
    $stmt->execute([$employeeId, $message]);
}
?>
