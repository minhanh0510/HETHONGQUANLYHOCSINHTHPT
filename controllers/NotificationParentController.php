<?php
require_once "models/Notification.php";

class NotificationParentController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Notification($this->db);
    }

    // Trang danh sách thông báo dành cho phụ huynh
    public function index() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $vaiTro = $user['vaiTro'] ?? ''; // Lấy vai trò từ session

        // Lấy thông báo cho vai trò
        $notifications = $this->model->getNotificationsFor_parent($vaiTro);

        // Hiển thị giao diện dành cho phụ huynh
        include "views/parent/notification_ph.php";
    }

    // Trang chi tiết thông báo dành cho phụ huynh
    public function detail() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $vaiTro = $user['vaiTro'] ?? ''; // Lấy vai trò từ session

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

        // Hiển thị giao diện dành cho phụ huynh
        include "views/parent/notification_detail_ph.php";
    }
}