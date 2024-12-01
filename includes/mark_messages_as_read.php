<?php
require_once '../includes/config.php';
require_once '../includes/session_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = $_SESSION['user_id'];
    $employeeId = $_SESSION['employee_id'];

    if ($clientId && $employeeId) {
        $stmt = $pdo->prepare("
            UPDATE messages 
            SET is_read = 1 
            WHERE client_id = ? 
              AND employee_id = ? 
              AND sender_role = 'employee'
        ");
        if ($stmt->execute([$clientId, $employeeId])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update messages.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
