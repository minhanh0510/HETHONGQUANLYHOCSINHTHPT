<?php
require_once "models/Notification.php";

class NotificationController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Notification($this->db);
    }

    // Trang danh sách thông báo
    public function index() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $vaiTro = $user['role'] ?? ''; // Lấy vai trò từ session

        // Kiểm tra giá trị vai trò
        if (empty($vaiTro)) {
            echo "Vai trò không hợp lệ.";
            exit;
        }

        // Kiểm tra vai trò
        if ($vaiTro !== 'student' && $vaiTro !== 'parent') {
            echo "Bạn không có quyền truy cập chức năng này.";
            exit;
        }

        // Lấy thông báo cho vai trò
        $notifications = $this->model->getNotificationsFor($vaiTro);

        // Hiển thị giao diện phù hợp với vai trò
        if ($vaiTro === 'student') {
            include "views/student/notification.php"; // Giao diện dành cho học sinh
        } elseif ($vaiTro === 'parent') {
            include "views/parent/notification.php"; // Giao diện dành cho phụ huynh
        }
    }

    // Trang chi tiết thông báo
    public function detail() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $vaiTro = $user['role'] ?? ''; // Lấy vai trò từ session

        // Kiểm tra giá trị vai trò
        if (empty($vaiTro)) {
            echo "Vai trò không hợp lệ.";
            exit;
        }

        // Kiểm tra vai trò
        if ($vaiTro !== 'student' && $vaiTro !== 'parent') {
            echo "Bạn không có quyền truy cập chức năng này.";
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "Thiếu ID thông báo.";
            exit;
        }

        $notification = $this->model->getNotificationById($id);

        if (!$notification) {
            echo "Không tìm thấy thông báo.";
            exit;
        }

        // Hiển thị giao diện phù hợp với vai trò
        if ($vaiTro === 'student') {
            include "views/student/notification_detail.php"; // Giao diện dành cho học sinh
        } elseif ($vaiTro === 'parent') {
            include "views/parent/notification_detail_ph.php"; // Giao diện dành cho phụ huynh
        }
    }
}
?>
