<?php
// controllers/AssignmentStudentController.php

class AssignmentStudentController {
    private $db;
    private $model;
    private $maHS;
    
    public function __construct($db) {
        $this->db = $db;
        
        // Khởi động session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        // Lấy thông tin user
        $user = $_SESSION['user'];
        
        // ✅ FIX: Thử nhiều cách lấy maHS từ session
        $this->maHS = $user['maHS'] ?? $user['ma_hs'] ?? null;
        
        // ✅ Nếu không có trong session, lấy từ database
        if (!$this->maHS && isset($user['maNguoiDung'])) {
            try {
                $stmt = $this->db->prepare("SELECT maHS FROM hocsinh WHERE maNguoiDung = ?");
                $stmt->execute([$user['maNguoiDung']]);
                $this->maHS = $stmt->fetchColumn();
                
                // Cập nhật lại session
                if ($this->maHS) {
                    $_SESSION['user']['maHS'] = $this->maHS;
                }
            } catch (Exception $e) {
                error_log("Error getting maHS: " . $e->getMessage());
            }
        }
        
        // ✅ Kiểm tra cuối cùng
        if (!$this->maHS) {
            echo "<div style='padding: 20px; background: #fee2e2; border: 2px solid #dc2626; margin: 20px;'>";
            echo "<h2>❌ Lỗi: Không tìm thấy thông tin học sinh!</h2>";
            echo "<p><strong>Debug Information:</strong></p>";
            echo "<pre style='background: white; padding: 10px; border-radius: 5px;'>";
            echo "Session user data:\n";
            print_r($user);
            echo "</pre>";
            echo "<p>Vui lòng đăng xuất và đăng nhập lại.</p>";
            echo "<a href='index.php?controller=auth&action=logout' style='display: inline-block; padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;'>Đăng xuất</a>";
            echo "</div>";
            exit;
        }
        
        // Khởi tạo model
        require_once "models/AssignmentStudent.php";
        $this->model = new AssignmentStudent($this->db);
    }
    
    /**
     * Trang danh sách bài tập
     */
    public function index() {
        // Lấy bộ lọc
        $filters = [
            'status' => $_GET['status'] ?? '',
            'subject' => $_GET['subject'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Lấy danh sách bài tập
        $baiTapList = $this->model->getBaiTapByHocSinh($this->maHS, $filters);
        
        // Lấy danh sách môn học
        $monHocList = $this->model->getMonHocByHocSinh($this->maHS);
        
        // Thống kê
        $statistics = $this->model->getStatistics($this->maHS);
        
        // Lấy thông tin user
        $user = $_SESSION['user'];
        
        // Load view
        require_once "views/student/assignment/index.php";
    }
    
    /**
     * Trang chi tiết bài tập
     */
    public function detail() {
        $maBaiTap = $_GET['id'] ?? null;
        
        if (!$maBaiTap) {
            $_SESSION['error'] = "Không tìm thấy mã bài tập!";
            header("Location: index.php?controller=assignmentStudent&action=index");
            exit;
        }
        
        // Lấy chi tiết bài tập
        $baiTap = $this->model->getBaiTapDetail($maBaiTap, $this->maHS);
        
        if (!$baiTap) {
            $_SESSION['error'] = "Không tìm thấy bài tập!";
            header("Location: index.php?controller=assignmentStudent&action=index");
            exit;
        }
        
        // Kiểm tra quyền truy cập (học sinh phải thuộc lớp của bài tập)
        $stmtCheck = $this->db->prepare("SELECT maLop FROM hocsinh WHERE maHS = ?");
        $stmtCheck->execute([$this->maHS]);
        $maLopHS = $stmtCheck->fetchColumn();
        
        if ($maLopHS !== $baiTap['maLop']) {
            $_SESSION['error'] = "Bạn không có quyền xem bài tập này!";
            header("Location: index.php?controller=assignmentStudent&action=index");
            exit;
        }
        
        // Load view
        require_once "views/student/assignment/detail.php";
    }
    
    /**
     * Xử lý nộp bài
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không hợp lệ!";
            header("Location: index.php?controller=assignmentStudent&action=index");
            exit;
        }
        
        try {
            $maBaiTap = $_POST['maBaiTap'] ?? null;
            $noiDung = trim($_POST['noiDung'] ?? '');
            
            // Validate
            if (!$maBaiTap) {
                throw new Exception("Không tìm thấy mã bài tập!");
            }
            
            if (empty($noiDung) && empty($_FILES['fileUpload']['name'])) {
                throw new Exception("Vui lòng nhập nội dung bài làm hoặc upload file!");
            }
            
            // Chuẩn bị dữ liệu
            $data = [
                'maHS' => $this->maHS,
                'maBaiTap' => $maBaiTap,
                'noiDung' => $noiDung
            ];
            
            // Xử lý upload file nếu có
            if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === UPLOAD_ERR_OK) {
                $fileInfo = $this->model->uploadFile($_FILES['fileUpload'], $this->maHS, $maBaiTap);
                $data = array_merge($data, $fileInfo);
            }
            
            // Nộp bài
            $result = $this->model->nopBaiTap($data);
            
            if ($result) {
                $_SESSION['success'] = "✅ Nộp bài thành công! Vui lòng chờ giáo viên chấm điểm.";
                header("Location: index.php?controller=assignmentStudent&action=detail&id=" . $maBaiTap);
            } else {
                throw new Exception("Có lỗi xảy ra khi nộp bài!");
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            
            if (isset($maBaiTap)) {
                header("Location: index.php?controller=assignmentStudent&action=detail&id=" . $maBaiTap);
            } else {
                header("Location: index.php?controller=assignmentStudent&action=index");
            }
        }
        
        exit;
    }
}
?>