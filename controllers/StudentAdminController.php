<?php
require_once "models/Student.php";
require_once "models/Classroom.php"; // LOP

class StudentAdminController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    // Danh sách + form (thêm)
    public function index() {
        $this->requireAdmin();

        $studentModel   = new Student($this->db);
        $classroomModel = new Classroom($this->db);

        $maLop    = $_GET['maLop']    ?? '';
        $gioiTinh = $_GET['gioiTinh'] ?? '';
        $keyword  = $_GET['keyword']  ?? '';

        $rows    = $studentModel->filter($maLop, $gioiTinh, $keyword);
        $lopList = $classroomModel->getAll(); // trả về maLop, tenLop, maKhoi, maBan, siSo...
        $user    = $_SESSION['user'];
        $student = null; // form ở chế độ thêm mới

        include "views/admin/student_list.php";
    }

    // Bấm sửa -> load dữ liệu vào form trên cùng
    public function edit() {
        $this->requireAdmin();

        if (empty($_GET['maHS'])) {
            $_SESSION['error'] = "Không xác định được học sinh cần sửa.";
            header("Location: index.php?controller=studentAdmin&action=index");
            exit;
        }

        $maHS = $_GET['maHS'];

        $studentModel   = new Student($this->db);
        $classroomModel = new Classroom($this->db);

        $maLop    = $_GET['maLop']    ?? '';
        $gioiTinh = $_GET['gioiTinh'] ?? '';
        $keyword  = $_GET['keyword']  ?? '';

        $rows    = $studentModel->filter($maLop, $gioiTinh, $keyword);
        $lopList = $classroomModel->getAll();
        $user    = $_SESSION['user'];

        $student = $studentModel->getById($maHS);
        if (!$student) {
            $_SESSION['error'] = "Không tìm thấy hồ sơ học sinh.";
            header("Location: index.php?controller=studentAdmin&action=index");
            exit;
        }

        include "views/admin/student_list.php";
    }

    // Lưu (thêm + cập nhật)
    public function save() {
    // Bắt buộc admin
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?controller=auth&action=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?controller=studentAdmin&action=index");
        exit;
    }

    $model  = new Student($this->db);
    $isEdit = !empty($_POST['is_edit']);

    // ====== TẠO / LẤY MÃ ======
    if (!$isEdit) {
        // Thêm mới
        $maNguoiDung = 'ND' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $maHS        = 'HS' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    } else {
        // Cập nhật – lấy lại từ hidden input trong form
        $maNguoiDung = $_POST['maNguoiDung'] ?? '';
        $maHS        = $_POST['maHS'] ?? '';
    }

    // SBD tự động từ maHS
    $soBaoDanh = 'BD' . substr($maHS, 2); // HS001 -> BD001

    // ====== MAP DATA ======
    $dataNguoiDung = [
        'maNguoiDung' => $maNguoiDung,
        'hoVaTen'     => trim($_POST['hoVaTen'] ?? ''),
        'gioiTinh'    => trim($_POST['gioiTinh'] ?? ''),
        'ngaySinh'    => $_POST['ngaySinh'] ?? null,
        'diaChi'      => trim($_POST['diaChi'] ?? ''),
        'soDienThoai' => trim($_POST['soDienThoai'] ?? ''),
        'email'       => trim($_POST['email'] ?? '')
    ];

    // HOCSINH: chỉ quản lý trạng thái + SBD (maLop, maPhong để use case khác)
    $dataHocSinh = [
        'maHS'           => $maHS,
        'dangThaiHocTap' => trim($_POST['dangThaiHocTap'] ?? 'Đang học'),
        'soBaoDanh'      => $soBaoDanh,
        'maNguoiDung'    => $maNguoiDung
    ];

    // ====== VALIDATE ======
    if ($dataNguoiDung['hoVaTen'] === '' || $dataNguoiDung['gioiTinh'] === '' || empty($dataNguoiDung['ngaySinh'])) {
        $_SESSION['error'] = "Dữ liệu không được để trống (họ tên, giới tính, ngày sinh).";
        $redirect = $isEdit 
            ? "index.php?controller=studentAdmin&action=edit&maHS=" . urlencode($maHS)
            : "index.php?controller=studentAdmin&action=index";
        header("Location: $redirect");
        exit;
    }

    if ($dataNguoiDung['email'] !== '' && !filter_var($dataNguoiDung['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Nhập sai định dạng email.";
        $redirect = $isEdit 
            ? "index.php?controller=studentAdmin&action=edit&maHS=" . urlencode($maHS)
            : "index.php?controller=studentAdmin&action=index";
        header("Location: $redirect");
        exit;
    }

    if ($dataNguoiDung['soDienThoai'] !== '' && !preg_match('/^[0-9]{9,11}$/', $dataNguoiDung['soDienThoai'])) {
        $_SESSION['error'] = "Nhập sai định dạng số điện thoại (9-11 chữ số).";
        $redirect = $isEdit 
            ? "index.php?controller=studentAdmin&action=edit&maHS=" . urlencode($maHS)
            : "index.php?controller=studentAdmin&action=index";
        header("Location: $redirect");
        exit;
    }

    // ====== GỌI MODEL ======
    if ($isEdit) {
        $result = $model->update($maHS, $dataHocSinh, $dataNguoiDung);
    } else {
        $result = $model->add($dataHocSinh, $dataNguoiDung);
    }

    if ($result) {
        $_SESSION['message'] = "✅ Hồ sơ học sinh đã được " . ($isEdit ? "cập nhật" : "thêm mới") . " thành công.";
    } else {
        $_SESSION['error'] = "❌ Thao tác thất bại. Vui lòng thử lại.";
    }

    header("Location: index.php?controller=studentAdmin&action=index");
    exit;
}


    public function delete() {
        $this->requireAdmin();

        if (!empty($_GET['maHS'])) {
            $model  = new Student($this->db);
            $result = $model->delete($_GET['maHS']);

            if ($result) {
                $_SESSION['message'] = "✅ Xóa học sinh thành công!";
            } else {
                $_SESSION['error'] = "❌ Xóa học sinh thất bại.";
            }
        }

        header("Location: index.php?controller=studentAdmin&action=index");
        exit;
    }
}
