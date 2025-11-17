<?php
require_once "models/Admission.php";
require_once "models/KyTuyenSinh.php";

class AdmissionController {
    private $db;
    public function __construct($db) { 
        $this->db = $db; 
    }

    public function register() {
        session_start();
        
        // Kiểm tra đăng nhập - chỉ Phụ huynh
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
            $_SESSION['error'] = "❌ Chỉ phụ huynh mới có thể đăng ký tuyển sinh!";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $admissionModel = new Admission($this->db);
        $kyTSModel = new KyTuyenSinh($this->db);

        // Lấy TẤT CẢ kỳ tuyển sinh
        $kyTuyenSinhList = $kyTSModel->getAllKyTuyenSinh();
        
        // Xác định kỳ tuyển sinh được chọn
        $selectedKyTS = null;
        if (!empty($_POST['maKyTS'])) {
            $selectedKyTS = $kyTSModel->getById($_POST['maKyTS']);
        } elseif (!empty($_GET['maKyTS'])) {
            $selectedKyTS = $kyTSModel->getById($_GET['maKyTS']);
        } elseif (!empty($kyTuyenSinhList)) {
            // Tự động chọn kỳ đang mở đầu tiên
            foreach ($kyTuyenSinhList as $ky) {
                if ($ky['trangThai'] == 'DangMo') {
                    $selectedKyTS = $ky;
                    break;
                }
            }
            // Nếu không có kỳ đang mở, chọn kỳ đầu tiên
            if (!$selectedKyTS && !empty($kyTuyenSinhList)) {
                $selectedKyTS = $kyTuyenSinhList[0];
            }
        }

        // Xử lý form đăng ký
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['hoTenHS'])) {
            // Kiểm tra kỳ tuyển sinh có đang mở không
            if ($selectedKyTS['trangThai'] !== 'DangMo') {
                $error = "❌ Kỳ tuyển sinh này đã đóng, không thể đăng ký!";
            } else {
                $result = $admissionModel->dangKyTuyenSinh($_POST, $_SESSION['user']);
                
                if ($result['success']) {
                    $_SESSION['success'] = "✅ " . $result['message'];
                    $_SESSION['maHS'] = $result['maHS'];
                    header("Location: index.php?controller=admission&action=success");
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }

        // Lấy danh sách cho form
        $truongList = $admissionModel->getAllTruong();
        $khoiList = $admissionModel->getAllKhoi();
        $monHocList = $admissionModel->getAllMonHoc();

        $current_page = 'register';
        include __DIR__ . "/../views/parent/admission/register.php";
    }

    public function success() {
        session_start();
        if (!isset($_SESSION['success'])) {
            header("Location: index.php?controller=admission&action=register");
            exit;
        }

        $maHS = $_SESSION['maHS'] ?? '';
        $current_page = 'register';
        include __DIR__ . "/../views/parent/admission/success.php";
        
        // Xóa session sau khi hiển thị
        unset($_SESSION['success']);
        unset($_SESSION['maHS']);
    }
}
?>