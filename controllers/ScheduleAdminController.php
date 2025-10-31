<?php
require_once "models/Schedule.php";

class ScheduleAdminController {
    private $db;
    public function __construct($db){ $this->db = $db; }

    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $model = new Schedule($this->db);
        $rows = $model->getAll();
        include "views/admin/schedule_list.php";
    }

    public function form() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { 
            header("Location: index.php"); 
            exit; 
        }
        
        $model = new Schedule($this->db);
        $row = null;
        if (!empty($_GET['id'])) $row = $model->getById($_GET['id']);
        include "views/admin/schedule_form.php";
    }

    public function save() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }

        $model = new Schedule($this->db);
        $data = [
            'maLop' => $_POST['maLop'],
            'maMon' => $_POST['maMon'],
            'maGV' => $_POST['maGV'],
            'ngay' => $_POST['ngay'],
            'tiet' => $_POST['tiet'],
            'maPhong' => $_POST['maPhong'],
            'namHoc' => $_POST['namHoc'],
            'hocKy' => $_POST['hocKy']
        ];
        
        if (!empty($_POST['is_edit'])) {
            $result = $model->update($_POST['maTKB'], $data);
        } else {
            $result = $model->add($data);
        }

        if ($result) {
            $_SESSION['message'] = "✅ Lưu lịch học thành công!";
        } else {
            $_SESSION['error'] = "❌ Lỗi khi lưu lịch học!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index");
        exit;
    }

    public function delete() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }

        $model = new Schedule($this->db);
        if (!empty($_GET['id'])) {
            $result = $model->delete($_GET['id']);
            if ($result) {
                $_SESSION['message'] = "✅ Xóa lịch học thành công!";
            } else {
                $_SESSION['error'] = "❌ Lỗi khi xóa lịch học!";
            }
        }
        header("Location: index.php?controller=scheduleAdmin&action=index");
        exit;
    }
}