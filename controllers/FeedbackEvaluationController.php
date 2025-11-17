<?php
require_once __DIR__ . '/../models/FeedbackEvaluation.php';

class FeedbackEvaluationController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new FeedbackEvaluation($this->db);
    }

    // 1. DANH SÁCH LỚP
    public function showClasses() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $classes = $this->model->getClasses();

        include __DIR__ . '/../views/teacher/class_list.php';
    }

    // 2. DANH SÁCH HỌC SINH + LƯU NHẬN XÉT
    public function showStudents() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $classId = $_GET['classId'] ?? null;
        if (!$classId) {
            $_SESSION['error'] = "❌ Thiếu mã lớp.";
            header("Location: index.php?controller=feedbackEvaluation&action=showClasses");
            exit;
        }

        // Lấy danh sách học sinh theo lớp
        $students = $this->model->getStudentsByClass($classId);

        // Kiểm tra nếu có POST request để lưu nhận xét và đánh giá
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = true;
            foreach ($_POST['students'] as $studentId => $data) {
                $nhanXet = $data['nhanXet'] ?? null;
                $danhGia = $data['danhGia'] ?? null;

                if ($nhanXet && $danhGia) {
                    $result = $this->model->saveFeedback($studentId, $nhanXet, $danhGia);
                    if (!$result) {
                        $success = false;
                    }
                }
            }

            if ($success) {
                $_SESSION['success'] = "✔ Nhận xét và đánh giá đã được lưu thành công!";
            } else {
                $_SESSION['error'] = "❌ Có lỗi xảy ra khi lưu nhận xét và đánh giá.";
            }

            header("Location: index.php?controller=feedbackEvaluation&action=showStudents&classId=$classId");
            exit;
        }

        // Truyền biến $students vào view
        include __DIR__ . '/../views/teacher/feedback_form.php';
    }
}
?>
