<?php
require_once 'config.php';
require_once '../includes/session_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = $_SESSION['user_id'];
    $employeeId = $_SESSION['employee_id'];

    if ($clientId && $employeeId) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS unread_count 
            FROM messages 
            WHERE client_id = ? 
              AND employee_id = ? 
              AND sender_role = 'employee' 
              AND is_read = 0
        ");
        $stmt->execute([$clientId, $employeeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['unread_count' => $result['unread_count']]);
    } else {
        echo json_encode(['unread_count' => 0]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
