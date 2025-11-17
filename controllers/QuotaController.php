<?php
require_once "models/Quota.php";


class QuotaController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $quotaModel = new Quota($this->db);
        $schools = $quotaModel->getAllSchools();
        
        $currentQuotas = [];
        foreach ($schools as $school) {
            $currentQuota = $quotaModel->getCurrentYearQuota($school['maTruong']);
            if ($currentQuota) {
                $currentQuotas[$school['maTruong']] = $currentQuota;
            }
        }

        $user = $_SESSION['user'];
        $message = $_GET['msg'] ?? '';
        $error = $_GET['error'] ?? '';

        include "views/department/quota_input.php";
    }

    public function save() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $quotaModel = new Quota($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quotaData = [];
            $hasData = false;

            foreach ($_POST['maTruong'] as $index => $maTruong) {
                $soHocSinh = $_POST['soHocSinh'][$index] ?? '';
                $soLopHoc = $_POST['soLopHoc'][$index] ?? '';
                
                if (!empty($soHocSinh) && !empty($soLopHoc)) {
                    $quotaData[$maTruong] = [
                        'soHocSinh' => (int)$soHocSinh,
                        'soLopHoc' => (int)$soLopHoc
                    ];
                    $hasData = true;
                }
            }

            if (!$hasData) {
                header("Location: index.php?controller=quota&action=index&error=" . urlencode("Chưa có trường nào được nhập chỉ tiêu"));
                exit;
            }

            $validationErrors = $quotaModel->validateQuotaData($quotaData);
            
            if (!empty($validationErrors)) {
                $errorMessage = implode("; ", $validationErrors);
                header("Location: index.php?controller=quota&action=index&error=" . urlencode($errorMessage));
                exit;
            }

            $result = $quotaModel->saveQuota($quotaData);
            
            if ($result) {
                header("Location: index.php?controller=quota&action=index&msg=" . urlencode("✅ Nhập chỉ tiêu thành công! Trạng thái: Đã nhập chỉ tiêu"));
            } else {
                header("Location: index.php?controller=quota&action=index&error=" . urlencode("❌ Lỗi khi lưu chỉ tiêu"));
            }
            exit;
        }

        header("Location: index.php?controller=quota&action=index");
        exit;
    }
}
?>