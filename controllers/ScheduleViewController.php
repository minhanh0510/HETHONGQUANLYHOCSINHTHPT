<?php
// controllers/ScheduleViewController.php

require_once "models/ScheduleView.php";

class ScheduleViewController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function student() {
        // Start session nếu chưa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        $studentId = $user['maHS'] ?? $user['student_id'] ?? null;
        
        // Lấy studentId từ database nếu cần
        if (!$studentId && isset($user['maNguoiDung'])) {
            try {
                $sql = "SELECT maHS FROM hocsinh WHERE maNguoiDung = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$user['maNguoiDung']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $studentId = $result['maHS'] ?? null;
            } catch (Exception $e) {
                error_log("Error getting student ID: " . $e->getMessage());
            }
        }
        
        // Khởi tạo model
        $scheduleModel = new ScheduleView($this->db);
        
        // Lấy tham số - năm học 2025-2026
        $week = $_GET['week'] ?? date('Y-\WW');
        $namHoc = $_GET['namHoc'] ?? '2025-2026';
        
        // Tính ngày tuần trước để xác định học kỳ
        list($tbd, $tkt) = $scheduleModel->getWeekDates($week);
        
        // Tự động xác định học kỳ dựa vào ngày
        // HK1: 01/09 - 15/01 năm sau, HK2: 16/01 - 31/05
        $month = (int)date('m', strtotime($tbd));
        $day = (int)date('d', strtotime($tbd));
        
        // HK2: từ 16/01 đến 31/05
        if (($month == 1 && $day >= 16) || ($month >= 2 && $month <= 5)) {
            $hocKy = 2;
        } else {
            $hocKy = 1;
        }
        
        // Khởi tạo data mặc định
        $data = [
            'maLop' => '',
            'tenLop' => '',
            'tkb' => [],
            'hocKy' => $hocKy,
            'namHoc' => $namHoc,
            'week' => $week,
            'tbd' => '',
            'tkt' => '',
            'hasSchedule' => false,
            'error' => null,
            'user' => $user
        ];
        
        // Nếu không có studentId
        if (!$studentId) {
            $data['error'] = 'Không tìm thấy thông tin học sinh.';
            $this->renderView('student/schedule_view', $data);
            return;
        }
        
        // Lấy lớp của học sinh
        $maLop = $scheduleModel->getStudentClass($studentId);
        if (!$maLop) {
            $data['error'] = 'Học sinh chưa được phân công lớp học.';
            $this->renderView('student/schedule_view', $data);
            return;
        }
        
        // Lấy tên lớp
        try {
            $sqlLop = "SELECT tenLop FROM lop WHERE maLop = ?";
            $stmtLop = $this->db->prepare($sqlLop);
            $stmtLop->execute([$maLop]);
            $lopInfo = $stmtLop->fetch(PDO::FETCH_ASSOC);
            $tenLop = $lopInfo['tenLop'] ?? $maLop;
        } catch (Exception $e) {
            $tenLop = $maLop;
        }
        
        // Lấy thời khóa biểu (tbd, tkt đã được tính ở trên)
        $tkb = $scheduleModel->getClassSchedule($maLop, $tbd, $tkt, $hocKy, $namHoc);
        $hasSchedule = !empty($tkb);
        
        // Cập nhật data
        $data['maLop'] = $maLop;
        $data['tenLop'] = $tenLop;
        $data['tkb'] = $tkb;
        $data['tbd'] = $tbd;
        $data['tkt'] = $tkt;
        $data['hasSchedule'] = $hasSchedule;
        
        // Render view
        $this->renderView('student/schedule_view', $data);
    }

    // DÀNH CHO PHỤ HUYNH - ĐƠN GIẢN
    public function parent() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        $scheduleModel = new ScheduleView($this->db);
        
        // Lấy ID phụ huynh
        $parentId = $user['maPH'] ?? null;
        if (!$parentId && isset($user['maNguoiDung'])) {
            $sql = "SELECT maPH FROM phuhuynh WHERE maNguoiDung = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $parentId = $result['maPH'] ?? null;
        }
        
        // Lấy params
        $week = $_GET['week'] ?? date('Y-\WW');
        $namHoc = $_GET['namHoc'] ?? '2025-2026';
        list($tbd, $tkt) = $scheduleModel->getWeekDates($week);
        
        // Xác định học kỳ
        $month = (int)date('m', strtotime($tbd));
        $day = (int)date('d', strtotime($tbd));
        $hocKy = (($month == 1 && $day >= 16) || ($month >= 2 && $month <= 5)) ? 2 : 1;
        
        // Data mặc định
        $data = [
            'maLop' => '',
            'tenLop' => '',
            'tkb' => [],
            'hocKy' => $hocKy,
            'namHoc' => $namHoc,
            'week' => $week,
            'tbd' => $tbd,
            'tkt' => $tkt,
            'hasSchedule' => false,
            'error' => null,
            'student_info' => null
        ];
        
        if (!$parentId) {
            $data['error'] = 'Không tìm thấy thông tin phụ huynh.';
            $this->renderView('parent/schedule_simple', $data);
            return;
        }
        
        // Lấy học sinh
        $studentInfo = $scheduleModel->getStudentByParent($parentId);
        if (!$studentInfo) {
            $data['error'] = 'Không tìm thấy học sinh được quản lý.';
            $this->renderView('parent/schedule_simple', $data);
            return;
        }
        
        $maLop = $scheduleModel->getStudentClass($studentInfo['maHS']);
        if (!$maLop) {
            $data['error'] = 'Học sinh chưa được phân lớp.';
            $data['student_info'] = $studentInfo;
            $this->renderView('parent/schedule_simple', $data);
            return;
        }
        
        // Lấy tên lớp
        $sqlLop = "SELECT tenLop FROM lop WHERE maLop = ?";
        $stmtLop = $this->db->prepare($sqlLop);
        $stmtLop->execute([$maLop]);
        $lopInfo = $stmtLop->fetch(PDO::FETCH_ASSOC);
        $tenLop = $lopInfo['tenLop'] ?? $maLop;
        
        // Lấy TKB
        $tkb = $scheduleModel->getClassSchedule($maLop, $tbd, $tkt, $hocKy, $namHoc);
        
        $data['maLop'] = $maLop;
        $data['tenLop'] = $tenLop;
        $data['tkb'] = $tkb;
        $data['hasSchedule'] = !empty($tkb);
        $data['student_info'] = $studentInfo;
        
        $this->renderView('parent/schedule_simple', $data);
    }
    
    // DÀNH CHO GIÁO VIÊN - ĐƠN GIẢN
    public function teacher() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        $scheduleModel = new ScheduleView($this->db);
        
        // Lấy ID giáo viên
        $teacherId = $user['maGV'] ?? null;
        if (!$teacherId && isset($user['maNguoiDung'])) {
            $sql = "SELECT maGV FROM giaovien WHERE maNguoiDung = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $teacherId = $result['maGV'] ?? null;
        }
        
        // Lấy params
        $week = $_GET['week'] ?? date('Y-\WW');
        $namHoc = $_GET['namHoc'] ?? '2025-2026';
        list($tbd, $tkt) = $scheduleModel->getWeekDates($week);
        
        // Xác định học kỳ
        $month = (int)date('m', strtotime($tbd));
        $day = (int)date('d', strtotime($tbd));
        $hocKy = (($month == 1 && $day >= 16) || ($month >= 2 && $month <= 5)) ? 2 : 1;
        
        // Data mặc định
        $data = [
            'tkb' => [],
            'hocKy' => $hocKy,
            'namHoc' => $namHoc,
            'week' => $week,
            'tbd' => $tbd,
            'tkt' => $tkt,
            'hasSchedule' => false,
            'error' => null,
            'teacherName' => $user['hoVaTen'] ?? 'Giáo viên'
        ];
        
        if (!$teacherId) {
            $data['error'] = 'Không tìm thấy thông tin giáo viên.';
            $this->renderView('teacher/schedule_simple', $data);
            return;
        }
        
        // Lấy lịch dạy
        $tkb = $scheduleModel->getTeacherSchedule($teacherId, $tbd, $tkt, $hocKy, $namHoc);
        
        $data['tkb'] = $tkb;
        $data['hasSchedule'] = !empty($tkb);
        
        $this->renderView('teacher/schedule_simple', $data);
    }
    
    /**
     * Render view - đơn giản chỉ include file
     */
    private function renderView($view, $data = []) {
        extract($data);
        require_once "views/{$view}.php";
    }
}
?>