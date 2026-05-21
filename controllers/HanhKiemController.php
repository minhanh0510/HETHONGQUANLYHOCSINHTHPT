<?php
// controllers/HanhKiemController.php

require_once 'models/HanhKiemModel.php';

class HanhKiemController {
    private $model;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new HanhKiemModel($pdo);
        
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        // Kiểm tra quyền truy cập - phải là giáo viên
        if ($_SESSION['user']['role'] !== 'teacher') {
            die("Bạn không có quyền truy cập trang này. Chỉ giáo viên mới có thể truy cập.");
        }
    }
    
    /**
     * Hiển thị trang danh sách học sinh
     */
    public function index() {
        // Lấy thông tin user từ session
        $user = $_SESSION['user'];
        
        // Tìm maGV từ session hoặc database
        $maGV = null;
        if (isset($user['maGV'])) {
            $maGV = $user['maGV'];
        } elseif (isset($user['maNguoiDung'])) {
            // Tìm maGV từ maNguoiDung
            $stmt = $this->pdo->prepare("SELECT maGV FROM giaovien WHERE maNguoiDung = ?");
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $maGV = $result['maGV'];
                // Lưu vào session cho lần sau
                $_SESSION['user']['maGV'] = $maGV;
            }
        }
        
        // Kiểm tra có tìm thấy maGV không
        if (!$maGV) {
            die("Lỗi: Không tìm thấy mã giáo viên. Vui lòng liên hệ quản trị viên.<br>Debug: " . print_r($user, true));
        }
        
        $namHoc = $_GET['namHoc'] ?? '2024-2025';
        $hocKy = $_GET['hocKy'] ?? 1;
        
        // Lấy thông tin lớp chủ nhiệm
        $lopChuNhiem = $this->model->getLopChuNhiem($maGV, $namHoc);
        
        // Kiểm tra kết quả
        if (!$lopChuNhiem) {
            echo "<div style='padding: 50px; text-align: center; font-family: Arial;'>
                    <h2 style='color: #e74c3c;'>Thông báo</h2>
                    <p>Bạn chưa được phân công làm chủ nhiệm lớp nào cho năm học <strong>$namHoc</strong>.</p>
                    <p>Vui lòng liên hệ Ban Giám Hiệu để được phân công.</p>
                    <br>
                    <a href='index.php?controller=teacher&action=dashboard' 
                       style='padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px;'>
                        ← Quay lại Dashboard
                    </a>
                  </div>";
            exit();
        }
        
       // Danh sách học sinh (chuẩn theo phancong)
$danhSachHS = $this->model->getHocSinhByGVCN($maGV, $hocKy, $namHoc);

// Sĩ số lớp (CHUẨN – lấy từ phancong)
$tongHS = $this->model->getTongHocSinh($lopChuNhiem['maLop'], $namHoc);

// Thống kê hạnh kiểm (chuẩn)
$thongKe = $this->model->thongKeHanhKiem(
    $lopChuNhiem['maLop'],
    $hocKy,
    $namHoc
);
        // Hiển thị view
        include 'views/hanhkiem/index.php';
    }
    
    /**
     * Lấy thông tin hạnh kiểm để edit (trả về JSON)
     */
    public function form() {
        header('Content-Type: application/json');
        
        $maHS = $_GET['maHS'] ?? null;
        $hocKy = $_GET['hocKy'] ?? 1;
        $namHoc = $_GET['namHoc'] ?? '2024-2025';
        
        if (!$maHS) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy học sinh.']);
            exit();
        }
        
        // Lấy thông tin hạnh kiểm (nếu đã có)
        $hanhKiem = $this->model->getHanhKiemByHS($maHS, $hocKy, $namHoc);
        
        if ($hanhKiem) {
            echo json_encode([
                'success' => true,
                'xepLoai' => $hanhKiem['xepLoai'],
                'nhanXet' => $hanhKiem['nhanXet']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Chưa có dữ liệu hạnh kiểm.']);
        }
    }
    
    /**
     * Xử lý thêm hạnh kiểm
     */
    public function them() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
            exit();
        }
        
        $maHS = $_POST['maHS'] ?? null;
        $xepLoai = $_POST['xepLoai'] ?? null;
        $nhanXet = trim($_POST['nhanXet'] ?? '');
        $hocKy = $_POST['hocKy'] ?? 1;
        $namHoc = $_POST['namHoc'] ?? '2024-2025';
        
        // Lấy maGV từ session
        $user = $_SESSION['user'];
        $nguoiNhap = $user['maGV'] ?? null;
        
        if (!$nguoiNhap) {
            // Tìm maGV từ maNguoiDung
            $stmt = $this->pdo->prepare("SELECT maGV FROM giaovien WHERE maNguoiDung = ?");
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nguoiNhap = $result['maGV'] ?? null;
        }
        
        // Validate
        if (!$maHS || !$xepLoai) {
            echo json_encode([
                'success' => false, 
                'message' => 'Vui lòng điền đầy đủ thông tin.'
            ]);
            exit();
        }
        
        if (!$nguoiNhap) {
            echo json_encode([
                'success' => false, 
                'message' => 'Không tìm thấy thông tin giáo viên.'
            ]);
            exit();
        }
        
        $validXepLoai = ['Tot', 'Kha', 'TrungBinh', 'Yeu'];
        if (!in_array($xepLoai, $validXepLoai)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Xếp loại không hợp lệ.'
            ]);
            exit();
        }
        
        // Thực hiện thêm
        $result = $this->model->themHanhKiem($maHS, $xepLoai, $nhanXet, $nguoiNhap, $hocKy, $namHoc);
        
        echo json_encode($result);
    }
    
    /**
     * Xử lý cập nhật hạnh kiểm
     */
    public function capNhat() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
            exit();
        }
        
        $maHK = $_POST['maHK'] ?? null;
        $xepLoai = $_POST['xepLoai'] ?? null;
        $nhanXet = trim($_POST['nhanXet'] ?? '');
        
        // Lấy maGV từ session
        $user = $_SESSION['user'];
        $nguoiNhap = $user['maGV'] ?? null;
        
        if (!$nguoiNhap) {
            // Tìm maGV từ maNguoiDung
            $stmt = $this->pdo->prepare("SELECT maGV FROM giaovien WHERE maNguoiDung = ?");
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nguoiNhap = $result['maGV'] ?? null;
        }
        
        // Validate
        if (!$maHK || !$xepLoai) {
            echo json_encode([
                'success' => false, 
                'message' => 'Vui lòng điền đầy đủ thông tin.'
            ]);
            exit();
        }
        
        if (!$nguoiNhap) {
            echo json_encode([
                'success' => false, 
                'message' => 'Không tìm thấy thông tin giáo viên.'
            ]);
            exit();
        }
        
        $validXepLoai = ['Tot', 'Kha', 'TrungBinh', 'Yeu'];
        if (!in_array($xepLoai, $validXepLoai)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Xếp loại không hợp lệ.'
            ]);
            exit();
        }
        
        // Thực hiện cập nhật
        $result = $this->model->capNhatHanhKiem($maHK, $xepLoai, $nhanXet, $nguoiNhap);
        
        echo json_encode($result);
    }
    
    /**
     * Xử lý xóa hạnh kiểm
     */
    public function xoa() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
            exit();
        }
        
        $maHK = $_POST['maHK'] ?? null;
        
        if (!$maHK) {
            echo json_encode([
                'success' => false, 
                'message' => 'Không tìm thấy hạnh kiểm.'
            ]);
            exit();
        }
        
        // Thực hiện xóa
        $result = $this->model->xoaHanhKiem($maHK);
        
        echo json_encode($result);
    }
}
?>