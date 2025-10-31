<?php
require_once "models/Assignment.php";
require_once "models/Classroom.php";

class AssignmentController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $mode = $_GET['mode'] ?? 'new'; // new | edit
        $assignModel = new Assignment($this->db);
        $classModel  = new Classroom($this->db);

        $lopList = $classModel->getAll();
        $rows    = ($mode === 'edit') ? $assignModel->getAssigned() : $assignModel->getUnassigned();
        $user    = $_SESSION['user'];
        $message = $_GET['msg'] ?? '';

        include "views/admin/assignment_list.php";
    }

    public function save() {
        session_start();
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }

        $mode  = $_POST['mode'] ?? 'new';
        $lop   = $_POST['lop'] ?? '';
        $ban   = $_POST['ban'] ?? '';
        $ids   = $_POST['hoc_sinh'] ?? [];

        $model = new Assignment($this->db);
        if (empty($ids)) {
            $msg = "⚠️ Vui lòng chọn ít nhất một học sinh.";
        } else {
            $result = ($mode === 'edit')
                ? $model->updateAssignment($ids, $lop, $ban)
                : $model->assignStudents($ids, $lop, $ban);
            $msg = ($result === true) ? "✅ Phân công thành công!" : $result;
        }

        header("Location: index.php?controller=assignment&action=index&mode=$mode&msg=" . urlencode($msg));
        exit;
    }
}