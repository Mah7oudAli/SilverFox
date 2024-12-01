<?php
include '../includes/session_header.php';
require_once '../includes/config.php';

if ($_SESSION['role'] != 'general_manager') {
    header("Location: /public/index.php");
    exit();
}

// جلب جميع الموظفين
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'employee'");
$employees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../partials/header.php'; ?>
<div class="container mt-4">
    <h1>Manage Employees</h1>
    <a href="add_employee.php" class="btn btn-primary mb-3">Add New Employee</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee['id']; ?></td>
                    <td><?php echo $employee['name']; ?></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td><?php echo $employee['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['id']; ?>" class="btn btn-warning">Edit</a>
                        <form action="toggle_employee_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                            <button type="submit" class="btn btn-secondary">
                            <?php echo $employee['status'] == 1 ? 'Deactivate' : 'Activate'; ?>
                            </button>
                        </form>
                        <form action="delete_employee.php" method="POST" style="display:inline;">
                            <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

