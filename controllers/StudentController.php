<?php
require_once "models/Exam.php";

class StudentController {
    private $db;
    public function __construct($db){ $this->db = $db; }

    public function examRoom() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $examModel = new Exam($this->db);

        $maHS = $user['student_id'] ?? 'HS001';
        $filters = [
            'mon_thi' => $_GET['mon_thi'] ?? '',
            'ngay_thi' => $_GET['ngay_thi'] ?? '',
            'phong' => $_GET['phong'] ?? ''
        ];

        $rows = $examModel->getByStudent($maHS, $filters);
        $dsMon = $examModel->getDistinctMon($maHS);
        $dsPhong = $examModel->getDistinctPhong($maHS);
        $soNgayThi = $examModel->countNgayThi($maHS);
        $soTietHomNay = $examModel->countTietHomNay($maHS);

        include "views/student/exam_room.php";
    }
}