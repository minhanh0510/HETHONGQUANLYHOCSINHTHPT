<?php
require_once "models/Account.php";

class AccountController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Account($this->db);
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        include "views/admin/account_create.php";
    }

    public function save() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username    = $_POST['username'] ?? null;
            $password    = $_POST['password'] ?? null;
            $hoVaTen     = $_POST['hoVaTen'] ?? null;
            $gioiTinh    = $_POST['gioiTinh'] ?? null;
            $ngaySinh    = $_POST['ngaySinh'] ?? null;
            $soDienThoai = $_POST['soDienThoai'] ?? null;
            $email       = $_POST['email'] ?? null;
            $diaChi      = $_POST['diaChi'] ?? null;
            $role        = $_POST['role'] ?? null;

            error_log("Role received: " . $role);

            if (!$username || !$password || !$hoVaTen || !$email || !$role || !$gioiTinh || !$ngaySinh || !$soDienThoai || !$diaChi) {
                $_SESSION['error'] = "❌ Vui lòng nhập đầy đủ dữ liệu!";
                header("Location: index.php?controller=account&action=index");
                exit;
            }
            // 2️⃣ Kiểm tra ngày sinh không lớn hơn hôm nay
            $today = date('Y-m-d');
            if ($ngaySinh > $today) {
                $_SESSION['error'] = "❌ Ngày sinh không hợp lệ (không được lớn hơn ngày hiện tại)";
                header("Location: index.php?controller=account&action=index");
                exit;
            }

            $result = $this->model->createAccount(
                $username,
                $password,
                $role,
                $hoVaTen,
                $gioiTinh,
                $ngaySinh,
                $diaChi,
                $soDienThoai,
                $email
            );

            if ($result === "duplicated_username") {
                $_SESSION['error'] = "❌ Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!";
            } elseif ($result === true) {
                $_SESSION['success'] = "✅ Tạo tài khoản thành công!";
            } else {
                $_SESSION['error'] = "❌ Lỗi hệ thống! Không thể tạo tài khoản hoặc role không hợp lệ.";
            }

            header("Location: index.php?controller=account&action=index");
            exit;
        }

        header("Location: index.php?controller=account&action=index");
        exit;
    }
}
?>
