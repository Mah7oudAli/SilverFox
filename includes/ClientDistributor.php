<?php

class ClientDistributor
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // جلب جميع العملاء
    public function getAllClients()
    {
        // جلب البيانات من جدول clients مع الحقول المناسبة
        $stmt = $this->pdo->query("SELECT id, full_name, national_id, residence_or_work, mobile_phone, work_phone, personal_phone, username, password, qr_code_path, created_at, employee_id FROM clients");
        return $stmt->fetchAll();
    }

    // جلب جميع الموظفين
    public function getAllEmployees()
    {
        // جلب البيانات من جدول employees
        $stmt = $this->pdo->query("SELECT id, full_name, national_id, residence, role, shift_start, shift_end, start_date, username, password, created_at, path_qr_Employee FROM employees WHERE role = 'accountant'");
        return $stmt->fetchAll();
    }

    // جلب اسم الموظف الحالي المعين للعميل
    public function getEmployeeById($employeeId)
    {
        if (!$employeeId) {
            return 'غير مخصص';
        }

        // جلب اسم الموظف من جدول employees
        $stmt = $this->pdo->prepare("SELECT username FROM employees WHERE id = ?");
        $stmt->execute([$employeeId]);
        $employee = $stmt->fetch();

        return $employee ? $employee['username'] : 'غير مخصص';
    }

    // تعيين الموظف لعميل معين
    public function assignEmployeeToClient($clientId, $employeeId)
    {
        // التحقق من وجود الموظف في جدول employees
        $stmt = $this->pdo->prepare("SELECT id FROM employees WHERE id = ?");
        $stmt->execute([$employeeId]);

        if ($stmt->rowCount() > 0) {
            // إذا كان الموظف موجوداً، قم بتحديث العميل
            $stmt = $this->pdo->prepare("UPDATE clients SET employee_id = ? WHERE id = ?");
            $stmt->execute([$employeeId, $clientId]);
        } else {
            throw new Exception("Employee ID is not valid.");
        }
    }
}
?>
