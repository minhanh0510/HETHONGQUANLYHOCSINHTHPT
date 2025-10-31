<?php
require_once "models/Student.php";
require_once "models/Classroom.php";

class StudentAdminController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $studentModel   = new Student($this->db);
        $classroomModel = new Classroom($this->db);

        $maLop = $_GET['maLop'] ?? '';
        $gioiTinh = $_GET['gioiTinh'] ?? '';
        $keyword = $_GET['keyword'] ?? '';

        $rows = $studentModel->filter($maLop, $gioiTinh, $keyword);
        $lopList = $classroomModel->getAll();
        $user    = $_SESSION['user'];

        include "views/admin/student_list.php";
    }

    public function save() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $model = new Student($this->db);
        
        // Tạo mã người dùng và học sinh tự động nếu thêm mới
        if (empty($_POST['is_edit'])) {
            $maNguoiDung = 'ND' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
            $maHS = 'HS' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
        } else {
            $maNguoiDung = $_POST['maNguoiDung'];
            $maHS = $_POST['maHS'];
        }

        $dataNguoiDung = [
            'maNguoiDung' => $maNguoiDung,
            'hoVaTen'     => trim($_POST['hoVaTen']),
            'gioiTinh'    => trim($_POST['gioiTinh']),
            'ngaySinh'    => $_POST['ngaySinh'] ?? null,
            'diaChi'      => trim($_POST['diaChi']),
            'soDienThoai' => trim($_POST['soDienThoai']),
            'email'       => trim($_POST['email'])
        ];

        $dataHocSinh = [
            'maHS'            => $maHS,
            'dangThaiHocTap'  => trim($_POST['dangThaiHocTap']),
            'soBaoDanh'       => trim($_POST['soBaoDanh']),
            'maNguoiDung'     => $dataNguoiDung['maNguoiDung']
        ];

        if (!empty($_POST['is_edit'])) {
            $result = $model->update($dataHocSinh['maHS'], $dataHocSinh, $dataNguoiDung);
        } else {
            $result = $model->add($dataHocSinh, $dataNguoiDung);
        }

        if ($result) {
            $_SESSION['message'] = "✅ Thao tác thành công!";
        } else {
            $_SESSION['error'] = "❌ Thao tác thất bại!";
        }

        header("Location: index.php?controller=studentAdmin&action=index");
        exit;
    }

    public function delete() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $model = new Student($this->db);
        if (!empty($_GET['maHS'])) {
            $result = $model->delete($_GET['maHS']);
            if ($result) {
                $_SESSION['message'] = "✅ Xóa học sinh thành công!";
            } else {
                $_SESSION['error'] = "❌ Xóa học sinh thất bại!";
            }
        }
        header("Location: index.php?controller=studentAdmin&action=index");
        exit;
    }
}