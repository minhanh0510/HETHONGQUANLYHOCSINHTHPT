<?php
require_once "models/User.php";

class AuthController {
    private $db;
    public function __construct($db){ $this->db = $db; }

    public function login() {
        session_start();
        // Nếu đã đăng nhập, chuyển hướng đến trang phù hợp
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header("Location: index.php?controller=studentAdmin&action=index");
            } elseif ($_SESSION['user']['role'] === 'student') {
                header("Location: index.php?controller=student&action=examRoom");
            } elseif ($_SESSION['user']['role'] === 'department') {
                header("Location: index.php?controller=quota&action=index");
            } elseif ($_SESSION['user']['role'] === 'parent') {
                header("Location: index.php?controller=parent&action=dashboard");
            } elseif ($_SESSION['user']['role'] === 'management') { 
                header("Location: index.php?controller=scoreEdit&action=index");
            } elseif ($_SESSION['user']['role'] === 'teacher') {
                header("Location: index.php?controller=classroom&action=index");
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new User($this->db);
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                if ($user['role'] === 'admin') {
                    header("Location: index.php?controller=studentAdmin&action=index");
                } elseif ($user['role'] === 'student') {
                    header("Location: index.php?controller=student&action=examRoom");
                } elseif ($user['role'] === 'department') {
                    header("Location: index.php?controller=quota&action=index"); 
                } elseif ($user['role'] === 'parent') {
                    header("Location: index.php?controller=parent&action=dashboard");
                } elseif ($user['role'] === 'management') { 
                    header("Location: index.php?controller=scoreEdit&action=index"); 
                } elseif ($user['role'] === 'teacher') {
                    header("Location: index.php?controller=classroom&action=index");
                } else {
                    $error = "Role chưa được hỗ trợ.";
                }
                exit;
            } else {
                $error = "Sai tài khoản hoặc mật khẩu!";
            }
        }

        include "views/auth/login.php";
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}