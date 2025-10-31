<?php
require_once "config/database.php";

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Sử dụng $pdo từ database.php
switch ($controller) {
    case 'auth':
        require_once "controllers/AuthController.php";
        $ctrl = new AuthController($pdo);
        break;

    case 'student':
        require_once "controllers/StudentController.php";
        $ctrl = new StudentController($pdo);
        break;

    case 'studentAdmin':
        require_once "controllers/StudentAdminController.php";
        $ctrl = new StudentAdminController($pdo);
        break;
        
    case 'assignment':
        require_once "controllers/AssignmentController.php";
        $ctrl = new AssignmentController($pdo);
        break;

    case 'scheduleAdmin':
        require_once "controllers/ScheduleAdminController.php";
        $ctrl = new ScheduleAdminController($pdo);
        break;

    default:
        die("❌ Controller không tồn tại");
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    die("❌ Action không tồn tại");
}