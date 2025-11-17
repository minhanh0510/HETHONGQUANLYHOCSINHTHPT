<?php
// controllers/ScoreEditController.php
require_once "models/ScoreEdit.php";

class ScoreEditController {
    private $db;
    private $model;
    
    public function __construct($db) {
        $this->db = $db;
        $this->model = new ScoreEdit($db);
    }

    // Hiển thị danh sách minh chứng (Bước 2)
    public function index() {
        session_start();
        $this->checkPermission();
        
        $minhChungList = $this->model->getMinhChung();
        $lichSuSuaDiem = $this->model->getLichSuSuaDiem(null, 5); // Lấy 5 bản ghi gần nhất
        $user = $_SESSION['user'];
        
        include "views/management/score_edit_list.php";
    }

    // Hiển thị thông tin học sinh và điểm (Bước 4)
    public function showStudent() {
        session_start();
        $this->checkPermission();
        
        $maHS = $_GET['maHS'] ?? '';
        $maMinhChung = $_GET['maMinhChung'] ?? '';
        
        if (empty($maHS)) {
            $_SESSION['error'] = "Vui lòng chọn học sinh";
            header("Location: index.php?controller=scoreEdit&action=index");
            exit;
        }
        
        // Lấy dữ liệu
        $scores = $this->model->getStudentScores($maHS);
        $monHocList = $this->model->getMonHoc();
        $hocKyList = $this->model->getHocKy();
        $lichSuSuaDiem = $this->model->getLichSuSuaDiem(null, 5); // Lấy 5 bản ghi gần nhất
        $user = $_SESSION['user'];
        
        $studentInfo = $this->model->getStudentInfo($maHS);
        
        include "views/management/score_edit_form.php";
    }

    // Xử lý sửa điểm (Bước 6-8)
    // Xử lý sửa điểm (Bước 6-8)
    public function update() {
        session_start();
        $this->checkPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maDiem = $_POST['maDiem'] ?? '';
            $diemMoi = $_POST['diemMoi'] ?? '';
            $lyDo = $_POST['lyDo'] ?? '';
            $maHS = $_POST['maHS'] ?? '';
            $maMinhChung = $_POST['maMinhChung'] ?? ''; // THÊM DÒNG NÀY
            $maNguoiSua = $_SESSION['user']['maNguoiDung'];
            
            try {
                // Kiểm tra phạm vi điểm (Bước 7.1)
                if ($diemMoi < 0 || $diemMoi > 10) {
                    throw new Exception("Điểm nhập sai phạm vi (0-10)");
                }
                
                // THÊM $maMinhChung VÀO PHƯƠNG THỨC updateScore
                $result = $this->model->updateScore($maDiem, $diemMoi, $maNguoiSua, $lyDo, $maMinhChung);
                
                if ($result) {
                    $_SESSION['success'] = "✅ Sửa điểm thành công! Minh chứng đã được đánh dấu đã xử lý.";
                    header("Location: index.php?controller=scoreEdit&action=index");
                } else {
                    throw new Exception("❌ Lỗi khi sửa điểm");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: index.php?controller=scoreEdit&action=showStudent&maHS=" . $maHS . "&maMinhChung=" . $maMinhChung);
            }
            exit;
        }
        
        header("Location: index.php?controller=scoreEdit&action=index");
    }
   

    // Hiển thị trang lịch sử sửa điểm đầy đủ
    public function history() {
        session_start();
        $this->checkPermission();
        
        $page = $_GET['page'] ?? 1;
        $perPage = 20; // Số bản ghi mỗi trang
        
        // Lấy dữ liệu phân trang
        $lichSuSuaDiem = $this->model->getLichSuSuaDiemPaginated($page, $perPage);
        $totalRecords = $this->model->countAllLichSuSuaDiem();
        $totalPages = ceil($totalRecords / $perPage);
        
        $user = $_SESSION['user'];
        
        include "views/management/score_edit_history.php";
    }
    // Kết thúc sửa điểm (Exception 6.1)
    public function cancel() {
        session_start();
        $this->checkPermission();
        
        if (isset($_POST['confirm'])) {
            $_SESSION['info'] = "Đã hủy thao tác sửa điểm";
            header("Location: index.php?controller=management&action=index");
            exit;
        }
        
        include "views/management/score_edit_cancel.php";
    }

    private function checkPermission() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'management') {
            $_SESSION['error'] = "❌ Chỉ Ban giám hiệu mới có quyền truy cập!";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }
}
?>