<?php
require_once "models/Exam.php";
require_once "models/Notification.php";

class StudentController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function examRoom() {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $examModel = new Exam($this->db);

        // maHS lưu trong session khi login
        $maHS = $user['student_id'] ?? 'HS001';

        // Bộ lọc
        $filters = [
            'mon_thi' => $_GET['mon_thi'] ?? '',
            'ngay_thi' => $_GET['ngay_thi'] ?? '',
            'phong'   => $_GET['phong'] ?? ''
        ];

        // Lịch thi của học sinh
        $rows         = $examModel->getByStudent($maHS, $filters);
        $soNgayThi    = $examModel->countNgayThi($maHS);
        $soTietHomNay = $examModel->countTietHomNay($maHS);

        // Combobox: lấy từ MONHOC & PHONGHOC (không phụ thuộc maHS)
        $dsMon   = $examModel->getDistinctMon();   // tất cả môn
        $dsPhong = $examModel->getDistinctPhong(); // tất cả phòng

        include "views/student/exam_room.php";
    }

    // ✅ Trang xem danh sách thông báo
    public function notification() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $notificationModel = new Notification($this->db);

        // Lấy thông báo cho học sinh
        $doiTuong = "HocSinh"; 
        $notifications = $notificationModel->getNotificationsFor($doiTuong);

        include "views/student/notification.php";
    }
}