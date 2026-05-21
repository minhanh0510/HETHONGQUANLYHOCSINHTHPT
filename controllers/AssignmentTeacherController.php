<?php
// controllers/AssignmentTeacherController.php
require_once 'models/AssignmentTeacher.php';

class AssignmentTeacherController {
    private $db;
    private $model;
    private $maGV;
    
    public function __construct($db) {
        $this->db = $db;
        $this->model = new AssignmentTeacher($db);
        
        // Kiểm tra đăng nhập
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        // Kiểm tra vai trò = 'teacher' (theo AuthController)
        $role = $_SESSION['user']['role'] ?? null;
        
        if ($role !== 'teacher') {
            echo "<div style='padding: 20px; background: #f8d7da; color: #721c24; border: 2px solid #dc3545; margin: 20px; border-radius: 5px;'>";
            echo "<h2>❌ Lỗi phân quyền</h2>";
            echo "<p>Bạn không có quyền truy cập trang này.</p>";
            echo "<p><strong>Vai trò hiện tại:</strong> " . htmlspecialchars($role ?? 'Không xác định') . "</p>";
            echo "<p><strong>Vai trò yêu cầu:</strong> teacher (Giáo viên)</p>";
            echo "<p><a href='index.php?controller=auth&action=logout'>Đăng xuất</a></p>";
            echo "</div>";
            exit();
        }
        
        // Lấy maNguoiDung từ session
        $maNguoiDung = $_SESSION['user']['maNguoiDung'] ?? 
                       $_SESSION['user']['user_id'] ?? 
                       $_SESSION['user']['id'] ?? null;
        
        if (!$maNguoiDung) {
            die("Lỗi: Không tìm thấy mã người dùng trong session. Vui lòng đăng xuất và đăng nhập lại.");
        }
        
        // Lấy maGV từ maNguoiDung
        try {
            $stmt = $db->prepare("SELECT maGV FROM giaovien WHERE maNguoiDung = ?");
            $stmt->execute([$maNguoiDung]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                die("Lỗi: Không tìm thấy thông tin giáo viên. Vui lòng liên hệ quản trị viên.");
            }
            
            $this->maGV = $result['maGV'];
        } catch (Exception $e) {
            die("Lỗi hệ thống: " . $e->getMessage());
        }
    }
    
    // Hiển thị danh sách bài tập
    public function index() {
        try {
            $baiTapList = $this->model->getBaiTapByGiaoVien($this->maGV);
            $user = $_SESSION['user'];
            
            include 'views/teacher/assignment/index.php';
        } catch (Exception $e) {
            echo "<div style='padding: 20px; background: #f8d7da; color: #721c24; margin: 20px;'>";
            echo "<h2>Lỗi</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
    }
    
    // Hiển thị form tạo bài tập mới
    public function create() {
        try {
            $lopList = $this->model->getLopByGiaoVien($this->maGV);
            $monList = $this->model->getMonHocByGiaoVien($this->maGV);
            $user = $_SESSION['user'];
            
            include 'views/teacher/assignment/create.php';
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }
    
    // Xử lý tạo bài tập mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];
            
            if (empty($_POST['tenBaiTap'])) {
                $errors[] = "Tên bài tập không được để trống";
            }
            if (empty($_POST['maLop'])) {
                $errors[] = "Vui lòng chọn lớp";
            }
            if (empty($_POST['maMon'])) {
                $errors[] = "Vui lòng chọn môn học";
            }
            if (empty($_POST['thoiHanNop'])) {
                $errors[] = "Vui lòng chọn thời hạn nộp";
            }
            
            if (count($errors) > 0) {
                $_SESSION['error'] = implode("<br>", $errors);
                header("Location: index.php?controller=assignmentTeacher&action=create");
                exit();
            }
            
            $data = [
                'tenBaiTap' => $_POST['tenBaiTap'],
                'moTa' => $_POST['moTa'] ?? '',
                'noiDung' => $_POST['noiDung'] ?? '',
                'thoiHanNop' => $_POST['thoiHanNop'],
                'maLop' => $_POST['maLop'],
                'maMon' => $_POST['maMon'],
                'maGV' => $this->maGV
            ];
            
            if ($this->model->createBaiTap($data)) {
                $_SESSION['success'] = "Tạo bài tập thành công";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
            }
            
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
    }
    
    // Hiển thị form chỉnh sửa bài tập
    public function edit() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            $_SESSION['error'] = "Không tìm thấy bài tập hoặc bạn không có quyền chỉnh sửa";
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
        
        $user = $_SESSION['user'];
        include 'views/teacher/assignment/edit.php';
    }
    
    // Xử lý cập nhật bài tập
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maBaiTap = $_POST['maBaiTap'] ?? 0;
            
            $errors = [];
            
            if (empty($_POST['tenBaiTap'])) {
                $errors[] = "Tên bài tập không được để trống";
            }
            if (empty($_POST['thoiHanNop'])) {
                $errors[] = "Vui lòng chọn thời hạn nộp";
            }
            
            if (count($errors) > 0) {
                $_SESSION['error'] = implode("<br>", $errors);
                header("Location: index.php?controller=assignmentTeacher&action=edit&id=$maBaiTap");
                exit();
            }
            
            $data = [
                'tenBaiTap' => $_POST['tenBaiTap'],
                'moTa' => $_POST['moTa'] ?? '',
                'noiDung' => $_POST['noiDung'] ?? '',
                'thoiHanNop' => $_POST['thoiHanNop'],
                'trangThai' => $_POST['trangThai']
            ];
            
            if ($this->model->updateBaiTap($maBaiTap, $data)) {
                $_SESSION['success'] = "Cập nhật bài tập thành công";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
            }
            
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
    }
    
    // ===== CẬP NHẬT: XÓA BÀI TẬP VĨNH VIỄN =====
    public function delete() {
        header('Content-Type: application/json');
        
        try {
            $maBaiTap = $_GET['id'] ?? null;
            
            if (!$maBaiTap) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Không tìm thấy mã bài tập!'
                ]);
                exit;
            }
            
            // Kiểm tra quyền sở hữu
            $baiTap = $this->model->getBaiTapById($maBaiTap);
            
            if (!$baiTap) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Không tìm thấy bài tập!'
                ]);
                exit;
            }
            
            if ($baiTap['maGV'] != $this->maGV) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Bạn không có quyền xóa bài tập này!'
                ]);
                exit;
            }
            
            // Xóa bài tập vĩnh viễn
            $result = $this->model->deleteBaiTap($maBaiTap);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => '✅ Đã xóa bài tập vĩnh viễn khỏi hệ thống!'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => '❌ Có lỗi xảy ra khi xóa bài tập. Vui lòng thử lại!'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    // Hiển thị danh sách bài nộp
    public function danhsachbainop() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            $_SESSION['error'] = "Không tìm thấy bài tập";
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
        
        $baiNopList = $this->model->getBaiNopByBaiTap($maBaiTap);
        $hocSinhList = $this->model->getHocSinhByLop($baiTap['maLop']);
        $user = $_SESSION['user'];
        
        include 'views/teacher/assignment/danhsach_bainop.php';
    }
    
    // ===== PHƯƠNG THỨC MỚI: XEM CHI TIẾT BÀI NỘP =====
    public function submissionDetail() {
        $maBaoCao = $_GET['id'] ?? 0;
        
        try {
            // Lấy thông tin chi tiết bài nộp
            $submission = $this->model->getSubmissionDetail($maBaoCao);
            
            if (!$submission) {
                $_SESSION['error'] = 'Không tìm thấy bài nộp!';
                header('Location: index.php?controller=assignmentTeacher&action=index');
                exit();
            }
            
            // Kiểm tra quyền truy cập (giáo viên phải là người giao bài tập)
            if ($submission['maGV'] != $this->maGV) {
                $_SESSION['error'] = 'Bạn không có quyền xem bài nộp này!';
                header('Location: index.php?controller=assignmentTeacher&action=index');
                exit();
            }
            
            $user = $_SESSION['user'];
            include 'views/teacher/assignment/submission_detail.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: index.php?controller=assignmentTeacher&action=index');
            exit();
        }
    }
    
    // ===== PHƯƠNG THỨC MỚI: CHẤM ĐIỂM TỪ TRANG CHI TIẾT =====
    public function gradeSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maBaoCao = $_POST['maBaoCao'] ?? 0;
            $diem = $_POST['diem'] ?? null;
            $nhanXet = trim($_POST['nhanXet'] ?? '');
            
            $errors = [];
            
            // Validate
            if (empty($diem) && $diem !== '0') {
                $errors[] = "Vui lòng nhập điểm";
            }
            
            if (!is_numeric($diem) || $diem < 0 || $diem > 10) {
                $errors[] = "Điểm phải từ 0 đến 10";
            }
            
            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
                header("Location: index.php?controller=assignmentTeacher&action=submissionDetail&id=$maBaoCao");
                exit();
            }
            
            // Lưu điểm
            if ($this->model->chamDiem($maBaoCao, $diem, $nhanXet)) {
                $_SESSION['success'] = "Chấm điểm thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
            }
            
            header("Location: index.php?controller=assignmentTeacher&action=submissionDetail&id=$maBaoCao");
            exit();
        }
    }
    
    // ===== PHƯƠNG THỨC HIỆN TẠI: CHẤM ĐIỂM (JSON RESPONSE) =====
    public function saveChamDiem() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maBaoCao = $_POST['maBaoCao'] ?? 0;
            $diem = $_POST['diem'] ?? 0;
            $nhanXet = $_POST['nhanXet'] ?? '';
            
            if (!is_numeric($diem) || $diem < 0 || $diem > 10) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Điểm không hợp lệ, vui lòng nhập từ 0-10'
                ]);
                exit();
            }
            
            if ($this->model->chamDiem($maBaoCao, $diem, $nhanXet)) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Chấm điểm thành công'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Có lỗi xảy ra, vui lòng thử lại'
                ]);
            }
        }
        exit();
    }
    
    // ===== PHƯƠNG THỨC MỚI: THỐNG KÊ BÀI TẬP =====
    public function statistics() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            $_SESSION['error'] = "Không tìm thấy bài tập";
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
        
        try {
            // Lấy thống kê
            $stats = $this->model->getAssignmentStatistics($maBaiTap);
            $user = $_SESSION['user'];
            
            include 'views/teacher/assignment/statistics.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
    }
    
    // ===== PHƯƠNG THỨC MỚI: NHẮC NHỞ HỌC SINH CHƯA NỘP =====
    public function remindStudents() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài tập']);
            exit();
        }
        
        try {
            // Lấy danh sách học sinh chưa nộp
            $hocSinhChuaNop = $this->model->getHocSinhChuaNop($maBaiTap);
            
            if (count($hocSinhChuaNop) == 0) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Tất cả học sinh đã nộp bài'
                ]);
                exit();
            }
            
            // Gửi thông báo (giả lập - bạn có thể tích hợp với hệ thống thông báo thực)
            $count = count($hocSinhChuaNop);
            
            echo json_encode([
                'success' => true, 
                'message' => "Đã gửi nhắc nhở tới $count học sinh chưa nộp bài"
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
        exit();
    }
    
    // ===== PHƯƠNG THỨC MỚI: XUẤT ĐIỂM RA EXCEL =====
    public function exportGrades() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            $_SESSION['error'] = "Không tìm thấy bài tập";
            header("Location: index.php?controller=assignmentTeacher&action=index");
            exit();
        }
        
        try {
            $baiNopList = $this->model->getBaiNopByBaiTap($maBaiTap);
            $hocSinhList = $this->model->getHocSinhByLop($baiTap['maLop']);
            
            // Tạo file CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="diem_' . $baiTap['tenBaiTap'] . '_' . date('YmdHis') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // BOM cho UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($output, [
                'STT', 
                'Họ và tên', 
                'Số báo danh', 
                'Trạng thái', 
                'Ngày nộp', 
                'Điểm', 
                'Nhận xét'
            ]);
            
            // Data
            $stt = 1;
            $baiNopArray = [];
            foreach($baiNopList as $bn) {
                $baiNopArray[$bn['maHS']] = $bn;
            }
            
            foreach($hocSinhList as $hs) {
                $baiNop = $baiNopArray[$hs['maHS']] ?? null;
                
                fputcsv($output, [
                    $stt++,
                    $hs['hoVaTen'],
                    $hs['soBaoDanh'],
                    $baiNop ? ($baiNop['trangThai'] == 'DaCham' ? 'Đã chấm' : 'Chưa chấm') : 'Chưa nộp',
                    $baiNop ? date('d/m/Y H:i', strtotime($baiNop['ngayNop'])) : '',
                    $baiNop && $baiNop['trangThai'] == 'DaCham' ? $baiNop['diem'] : '',
                    $baiNop ? $baiNop['nhanXet'] : ''
                ]);
            }
            
            fclose($output);
            exit();
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header("Location: index.php?controller=assignmentTeacher&action=danhsachbainop&id=$maBaiTap");
            exit();
        }
    }
    
    // ===== PHƯƠNG THỨC MỚI: ĐÓNG BÀI TẬP =====
    public function closeAssignment() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            exit();
        }
        
        if ($this->model->closeAssignment($maBaiTap)) {
            echo json_encode(['success' => true, 'message' => 'Đã đóng bài tập']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit();
    }
    
    // ===== PHƯƠNG THỨC MỚI: MỞ LẠI BÀI TẬP =====
    public function reopenAssignment() {
        $maBaiTap = $_GET['id'] ?? 0;
        $baiTap = $this->model->getBaiTapById($maBaiTap);
        
        if (!$baiTap || $baiTap['maGV'] != $this->maGV) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            exit();
        }
        
        if ($this->model->reopenAssignment($maBaiTap)) {
            echo json_encode(['success' => true, 'message' => 'Đã mở lại bài tập']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra']);
        }
        exit();
    }
}
?>