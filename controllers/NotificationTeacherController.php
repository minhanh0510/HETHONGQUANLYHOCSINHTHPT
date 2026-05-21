<?php
require_once 'models/Notification.php';

class NotificationTeacherController {
    private $notificationModel;

    public function __construct($db) {
        $this->notificationModel = new Notification($db);
    }

    // Hiển thị danh sách thông báo cho giáo viên
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        
        // ✅ FIX: Dùng 'role' thay vì 'vaiTro'
        $role = $user['role'] ?? '';
        
        // ✅ FIX: Kiểm tra vai trò 'teacher' thay vì 'GiaoVien'
        if ($role !== 'teacher') {
            echo "❌ Bạn không có quyền truy cập chức năng này. Vai trò hiện tại: " . htmlspecialchars($role);
            exit;
        }

        // Lấy thông báo cho giáo viên (TatCa hoặc GiaoVien)
        $notifications = $this->notificationModel->getNotificationsForTeacher();

        // ✅ FIX: Đường dẫn đúng là views/teacher/notification_teacher.php
        require_once 'views/teacher/notification_teacher.php';
    }

    // Hiển thị chi tiết thông báo
    public function detail() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        
        // ✅ FIX: Dùng 'role' thay vì 'vaiTro'
        $role = $user['role'] ?? '';
        
        // ✅ FIX: Kiểm tra vai trò 'teacher'
        if ($role !== 'teacher') {
            echo "❌ Bạn không có quyền truy cập chức năng này.";
            exit;
        }

        // Lấy ID thông báo
        $maThongBao = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($maThongBao <= 0) {
            header("Location: index.php?controller=notificationTeacher&action=index");
            exit;
        }

        // Lấy chi tiết thông báo với thông tin người gửi
        $notification = $this->notificationModel->getNotificationDetailById($maThongBao);

        if (!$notification) {
            echo "❌ Không tìm thấy thông báo.";
            exit;
        }

        // Kiểm tra xem giáo viên có quyền xem thông báo này không
        if ($notification['doiTuong'] !== 'TatCa' && $notification['doiTuong'] !== 'GiaoVien') {
            echo "❌ Bạn không có quyền xem thông báo này.";
            exit;
        }

        // ✅ FIX: Đường dẫn đúng là views/teacher/notification_teacher_detail.php
        require_once 'views/teacher/notification_teacher_detail.php';
    }
}
?>