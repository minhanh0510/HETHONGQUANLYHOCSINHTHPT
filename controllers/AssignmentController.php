<?php
require_once "models/Assignment.php";
require_once "models/Classroom.php";

class AssignmentController {
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

    public function index() {
        $this->requireAdmin();

        $mode       = $_GET['mode'] ?? 'new';   // new | edit
        $khoiFilter = $_GET['khoi'] ?? '';

        $assignModel = new Assignment($this->db);
        $classModel  = new Classroom($this->db);

        $lopList = $classModel->getAll();      // maLop, tenLop, maKhoi, maBan, siSo...
        $rows    = $assignModel->getAssigned($khoiFilter);
        $user    = $_SESSION['user'];
        $message = $_GET['msg'] ?? '';

        $studentOptions  = [];
        $selectedStudent = null;

        if ($mode === 'new') {
            $studentOptions = $assignModel->getUnassigned();
        }

        if ($mode === 'edit' && !empty($_GET['maHS'])) {
            foreach ($rows as $r) {
                if ($r['maHS'] === $_GET['maHS']) {
                    $selectedStudent = $r;
                    break;
                }
            }
        }

        include "views/admin/assignment_list.php";
    }

    public function save() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=assignment&action=index");
            exit;
        }

        $mode = $_POST['mode'] ?? 'new';
        $maLop = $_POST['lop'] ?? '';
        $maHS  = $_POST['maHS'] ?? '';

        if ($maLop === '' || $maHS === '') {
            $msg = "⚠️ Vui lòng chọn đầy đủ học sinh và lớp.";
            header("Location: index.php?controller=assignment&action=index&mode={$mode}&msg=" . urlencode($msg));
            exit;
        }

        $model  = new Assignment($this->db);
        $result = ($mode === 'edit')
            ? $model->changeAssignment($maHS, $maLop)
            : $model->assignNew($maHS, $maLop);

        if ($result === true) {
            $msg = "✅ Lưu phân công thành công!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=assignment&action=index&mode={$mode}&msg=" . urlencode($msg));
        exit;
    }
}
