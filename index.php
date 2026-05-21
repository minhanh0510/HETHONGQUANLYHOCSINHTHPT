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

    case 'scheduleView':
        require_once "controllers/ScheduleViewController.php";
        $ctrl = new ScheduleViewController($pdo);
        
        // Gọi action tương ứng
        if ($action == 'parent') {
            $ctrl->parent();
        } elseif ($action == 'teacher') {
            $ctrl->teacher();
        } else {
            // Mặc định là student
            $ctrl->student();
        }
        break;

    case 'leaveApplication':
        require_once "controllers/LeaveApplicationController.php";
        $ctrl = new LeaveApplicationController($pdo);
        
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
                $ctrl->index();
                break;
            case 'store':
                $ctrl->store();
                break;
            case 'cancel':
                $ctrl->cancel();
                break;
            default:
                $ctrl->index();
        }
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
        
    case 'teacher':
        require_once "controllers/TeacherController.php";
        $ctrl = new TeacherController($pdo);
        break;
        
    case 'assignmentTeacher':
        require_once "controllers/AssignmentTeacherController.php";
        $ctrl = new AssignmentTeacherController($pdo);
        break;
        
    case 'teacherAssignment':
        require_once "controllers/TeacherAssignmentController.php";
        $ctrl = new TeacherAssignmentController($pdo);
        if ($action === 'getTeachersBySubject') {
            $ctrl->getTeachersBySubject();
            exit;
        }
        break;
        
    case 'supervisorAssignment':
        require_once "controllers/SupervisorAssignmentController.php";
        $ctrl = new SupervisorAssignmentController($pdo);
        
        if ($action === 'getExamRooms') {
            $ctrl->getExamRooms();
            exit;
        }
        if ($action === 'getAvailableSupervisors') {
            $ctrl->getAvailableSupervisors();
            exit;
        }
        if ($action === 'getRemainingSlots') {
            $ctrl->getRemainingSlots();
            exit;
        }
        if ($action === 'getExamInfo') {
            $ctrl->getExamInfo();
            exit;
        }
        break;
    

    case 'examArrangement':
        require_once "controllers/ExamArrangementController.php";
        $ctrl = new ExamArrangementController();
        break;

    case 'notification':  
        require_once "controllers/NotificationController.php";
        $ctrl = new NotificationController($pdo);

        if (!isset($_GET['action'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $user = $_SESSION['user'] ?? null;
            
            if (!$user) {
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
            
            $vaiTro = strtolower($user['vaiTro'] ?? '');

            if ($vaiTro === 'giaovien') {
                $action = 'teacherIndex';
            } else {
                $action = 'index';
            }
        }
        break;
        
    case 'notificationTeacher':
        require_once 'controllers/NotificationTeacherController.php';
        $ctrl = new NotificationTeacherController($pdo); 
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
        
    case 'scoreView':
        require_once "controllers/ScoreViewController.php";
        $ctrl = new ScoreViewController($pdo);
        break;
        
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
    case 'hanhKiem':
        require_once "controllers/HanhKiemController.php";
        $ctrl = new HanhKiemController($pdo);
        break;
    case 'assignmentStudent':
        require_once "controllers/AssignmentStudentController.php";
        $ctrl = new AssignmentStudentController($pdo);
        break;
    
     case 'statistics':
        require_once "controllers/StatisticsController.php";
        $ctrl = new StatisticsController($pdo);
        
        // Kiểm tra quyền truy cập - chỉ cho Ban Giám Hiệu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'management') {
            // Chuyển hướng về trang đăng nhập với thông báo
            $_SESSION['error'] = "Chỉ Ban Giám Hiệu được truy cập chức năng Thống kê!";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
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