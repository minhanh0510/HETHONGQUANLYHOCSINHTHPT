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

    $maHS = $user['student_id'] ?? 'HS001';

    // Bộ lọc
    $filters = [
        'mon_thi' => $_GET['mon_thi'] ?? '',
        'ngay_thi' => $_GET['ngay_thi'] ?? '',
        'phong'   => $_GET['phong'] ?? ''
    ];

    // 1) Lấy lịch thi của HỌC SINH này
    $rows = $examModel->getByStudent($maHS, $filters);

    // 2) Combobox lọc (toàn bộ môn & phòng thi)
    $dsMon   = $examModel->getDistinctMon();    // từ MONHOC
    $dsPhong = $examModel->getDistinctPhong();  // từ PHONGTHI

    // 3) Thống kê DASHBOARD theo CHÍNH HỌC SINH NÀY
    $monSet   = [];
    $phongSet = [];
    $ngaySet  = [];

    foreach ($rows as $r) {
        if (!empty($r['mon_thi'])) {
            $monSet[$r['mon_thi']] = true;
        }
        if (!empty($r['phong'])) {
            $phongSet[$r['phong']] = true;
        }
        if (!empty($r['ngayThi'])) {
            $ngaySet[$r['ngayThi']] = true;
        }
    }

    $tongSoMonThi = count($monSet);   // 🔹 chỉ các môn HS này có lịch
    $soPhongThi   = count($phongSet); // 🔹 số phòng thi mà HS này tham gia
    $soNgayThi    = count($ngaySet);  // 🔹 số ngày thi của HS

    // 4) Số tiết thi hôm nay (vẫn dùng model, hoặc bạn tự đếm cũng được)
    $soTietHomNay = $examModel->countTietHomNay($maHS);

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