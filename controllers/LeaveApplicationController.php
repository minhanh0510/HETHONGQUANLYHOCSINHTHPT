<?php
// controllers/LeaveApplicationController.php

require_once "models/LeaveApplication.php";

class LeaveApplicationController {
    private $db;
    private $leaveModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->leaveModel = new LeaveApplication($db);
    }
    
    /**
     * Hiển thị trang chính với form và lịch sử
     */
    public function index() {
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập và role
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $user = $_SESSION['user'];
        $parentId = $user['maPH'] ?? null;
        
        // Nếu không có maPH trong session, lấy từ database
        if (!$parentId && isset($user['maNguoiDung'])) {
            try {
                $sql = "SELECT maPH FROM phuhuynh WHERE maNguoiDung = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$user['maNguoiDung']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $parentId = $result['maPH'] ?? null;
            } catch (Exception $e) {
                error_log("Error getting parent ID: " . $e->getMessage());
            }
        }
        
        if (!$parentId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin phụ huynh.';
            header("Location: index.php?controller=parent&action=dashboard");
            exit;
        }
        
        // Lấy thông tin học sinh
        $student = $this->leaveModel->getStudentByParent($parentId);
        
        if (!$student) {
            $_SESSION['error'] = 'Không tìm thấy học sinh được quản lý.';
            header("Location: index.php?controller=parent&action=dashboard");
            exit;
        }
        
        // Lấy danh sách đơn cũ
        $applications = $this->leaveModel->getLeaveApplicationsByStudent($student['maHS']);
        
        // Render view
        extract([
            'student' => $student,
            'applications' => $applications,
            'user' => $user,
            'title' => 'Xin phép nghỉ học',
            'leaveModel' => $this->leaveModel
        ]);
        
        require_once "views/parent/leave_application.php";
    }
    
    /**
     * Xử lý gửi đơn xin nghỉ
     */
    public function store() {
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        // Kiểm tra method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=leaveApplication&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $parentId = $user['maPH'] ?? null;
        
        if (!$parentId && isset($user['maNguoiDung'])) {
            try {
                $sql = "SELECT maPH FROM phuhuynh WHERE maNguoiDung = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$user['maNguoiDung']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $parentId = $result['maPH'] ?? null;
            } catch (Exception $e) {
                error_log("Error getting parent ID: " . $e->getMessage());
            }
        }
        
        if (!$parentId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin phụ huynh.';
            header("Location: index.php?controller=parent&action=dashboard");
            exit;
        }
        
        // Lấy thông tin học sinh
        $student = $this->leaveModel->getStudentByParent($parentId);
        
        if (!$student) {
            $_SESSION['error'] = 'Không tìm thấy học sinh được quản lý.';
            header("Location: index.php?controller=parent&action=dashboard");
            exit;
        }
        
        // Kiểm tra dữ liệu đầu vào
        $lyDo = trim($_POST['lyDo'] ?? '');
        $leaveType = $_POST['leaveType'] ?? 'single';
        $ngayNghi = $_POST['ngayNghi'] ?? '';
        $ngayBatDau = $_POST['ngayBatDau'] ?? '';
        $ngayKetThuc = $_POST['ngayKetThuc'] ?? '';
        
        $errors = [];
        
        // Validation
        if (empty($lyDo)) {
            $errors[] = 'Vui lòng nhập lý do nghỉ học.';
        }
        
        // Xác định ngày bắt đầu và kết thúc
        if ($leaveType === 'single') {
            // Nghỉ 1 ngày
            if (empty($ngayNghi)) {
                $errors[] = 'Vui lòng chọn ngày nghỉ.';
            } else {
                $ngayBatDau = $ngayNghi;
                $ngayKetThuc = $ngayNghi;
            }
        } else {
            // Nghỉ nhiều ngày
            if (empty($ngayBatDau)) {
                $errors[] = 'Vui lòng chọn ngày bắt đầu nghỉ.';
            }
            
            if (empty($ngayKetThuc)) {
                $errors[] = 'Vui lòng chọn ngày kết thúc nghỉ.';
            }
        }
        
        if (!empty($ngayBatDau) && !empty($ngayKetThuc)) {
            if (!$this->leaveModel->isValidDate($ngayBatDau, $ngayKetThuc)) {
                $errors[] = 'Ngày nghỉ không hợp lệ. Ngày bắt đầu phải từ hôm nay và ngày kết thúc phải sau ngày bắt đầu.';
            }
            
            // Kiểm tra số ngày nghỉ
            $soNgay = $this->leaveModel->calculateDays($ngayBatDau, $ngayKetThuc);
            if ($soNgay > 30) {
                $errors[] = 'Thời gian nghỉ tối đa là 30 ngày. Vui lòng liên hệ trực tiếp với giáo viên chủ nhiệm.';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old_input'] = [
                'lyDo' => $lyDo, 
                'ngayNghi' => $ngayNghi,
                'ngayBatDau' => $ngayBatDau, 
                'ngayKetThuc' => $ngayKetThuc,
                'leaveType' => $leaveType
            ];
            header("Location: index.php?controller=leaveApplication&action=index");
            exit;
        }
        
        // Chuẩn bị dữ liệu để lưu
        $leaveData = [
            'maHS' => $student['maHS'],
            'lyDo' => $lyDo,
            'ngayBatDau' => $ngayBatDau,
            'ngayKetThuc' => $ngayKetThuc
        ];
        
        // Lưu đơn xin nghỉ
        $result = $this->leaveModel->createLeaveApplication($leaveData);
        
        if ($result) {
            // Gửi thông báo cho giáo viên chủ nhiệm
            $teacher = $this->leaveModel->getHomeroomTeacher($student['maLop']);
            if ($teacher) {
                $soNgay = $this->leaveModel->calculateDays($ngayBatDau, $ngayKetThuc);
                $this->leaveModel->sendNotificationToTeacher(
                    $teacher['maGV'],
                    $student['hoVaTen'],
                    date('d/m/Y', strtotime($ngayBatDau)),
                    date('d/m/Y', strtotime($ngayKetThuc)),
                    $lyDo,
                    $soNgay
                );
            }
            
            $_SESSION['success'] = 'Đơn xin nghỉ học đã được gửi thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi đơn. Vui lòng thử lại.';
        }
        
        header("Location: index.php?controller=leaveApplication&action=index");
        exit;
    }
    
    /**
     * Xử lý hủy đơn
     */
    public function cancel() {
        header("Location: index.php?controller=leaveApplication&action=index");
        exit;
    }
}
?>