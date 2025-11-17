<?php
require_once "models/Admission.php";

class ParentController {
    private $db;
    public function __construct($db) { 
        $this->db = $db; 
    }

    public function dashboard() {
        session_start();
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
            $_SESSION['error'] = "❌ Vui lòng đăng nhập với tài khoản phụ huynh!";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        // Truyền db instance cho view
        $current_page = 'dashboard';
        include __DIR__ . '/../views/parent/dashboard.php';
    }
}
?>