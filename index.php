<?php
require_once "config/database.php";

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// ===== AUTO CONVERT: snake_case -> camelCase =====
// Chuyển đổi action từ view_attendance -> viewAttendance
if (strpos($action, '_') !== false) {
    $action = lcfirst(str_replace('_', '', ucwords($action, '_')));
    error_log("Action converted: {$_GET['action']} -> $action");
}

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
        
    case 'quota':
        require_once "controllers/QuotaController.php";
        $ctrl = new QuotaController($pdo);
        break;
        
    case 'department':
        require_once "controllers/DepartmentController.php";
        $ctrl = new DepartmentController($pdo);
        break;

    //tai
    case 'notification':  
        require_once "controllers/NotificationController.php";
        $ctrl = new NotificationController($pdo);

        // Nếu không có action, tự động điều hướng theo vai trò
        if (!isset($_GET['action'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $user = $_SESSION['user'] ?? null;
            
            if (!$user) {
                // Chưa đăng nhập -> chuyển về login
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
            
            $vaiTro = strtolower($user['vaiTro'] ?? '');

            // Điều hướng theo vai trò
            if ($vaiTro === 'giaovien') {
                $action = 'teacherIndex'; // 👩‍🏫 Giáo viên
            } else {
                $action = 'index'; // 👨‍🎓 Học sinh/Phụ huynh
            }
        }
        break;
    case 'notificationParent':
        require_once "controllers/NotificationParentController.php";
        $ctrl = new NotificationParentController($pdo);
        break;
    case 'examScore':
        require_once "controllers/ExamScoreController.php";
        $ctrl = new ExamScoreController($pdo);
        break;
    case 'account':
        require_once "controllers/AccountController.php";
        $ctrl = new AccountController($pdo);
        break;
    case 'feedbackEvaluation':
        require_once "controllers/FeedbackEvaluationController.php";
        $ctrl = new FeedbackEvaluationController($pdo);
        break;
    case 'roomAssignment':
        require_once "controllers/RoomAssignmentController.php";
        $ctrl = new RoomAssignmentController($pdo);
        break;
    //tai
    case 'admission':
        require_once "controllers/AdmissionController.php";
        $ctrl = new AdmissionController($pdo);
        break;
        
    case 'parent':
        require_once "controllers/ParentController.php";
        $ctrl = new ParentController($pdo);
        break;
        
    case 'scoreEdit':
        require_once "controllers/ScoreEditController.php";
        $ctrl = new ScoreEditController($pdo);
        break;
    
    case 'classroom':
        require_once "controllers/ClassroomController.php";
        $ctrl = new ClassroomController($pdo);
        break;
    
    default:
        die("❌ Controller không tồn tại: $controller");
}

// Kiểm tra action có tồn tại trong controller
if (isset($ctrl) && method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    // Hiển thị thông báo lỗi chi tiết
    echo "<div style='padding: 20px; background: #f8d7da; border: 2px solid #dc3545; color: #721c24;'>";
    echo "<h2>❌ Lỗi: Action không tồn tại</h2>";
    echo "<p><strong>Controller:</strong> $controller</p>";
    echo "<p><strong>Action gốc:</strong> " . ($_GET['action'] ?? 'N/A') . "</p>";
    echo "<p><strong>Action sau convert:</strong> $action</p>";
    
    if (isset($ctrl)) {
        echo "<p><strong>Available methods:</strong></p>";
        echo "<pre style='background: #fff; padding: 10px; border-radius: 5px;'>";
        print_r(get_class_methods($ctrl));
        echo "</pre>";
    }
    echo "</div>";
    die();
}
?>