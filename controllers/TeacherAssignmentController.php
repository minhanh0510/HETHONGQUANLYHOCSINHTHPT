<?php
require_once "models/TeacherAssignment.php";
require_once "models/Classroom.php";

class TeacherAssignmentController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function requireManagement() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'management') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    // Helper function để tính năm học hiện tại
    private function getCurrentSchoolYear() {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        if ($currentMonth >= 8) {
            // Tháng 8-12: năm học bắt đầu từ năm hiện tại
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            // Tháng 1-7: năm học bắt đầu từ năm trước
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    // Basic Flow - Bước 1-2: Hiển thị danh sách lớp và giáo viên
    public function index() {
        $this->requireManagement();

        $type = $_GET['type'] ?? 'chunhiem';
        $message = $_GET['msg'] ?? '';

        $model = new TeacherAssignment($this->db);
        
        // Lấy danh sách theo loại phân công
        if ($type === 'chunhiem') {
            $teachers = $model->getAvailableHomeRoomTeachers();
            $classes = $model->getAvailableClasses();
        } else {
            $teachers = [];
            $classes = $model->getAllClasses();
        }
        
        $subjects = $model->getAllSubjects();
        $currentAssignments = $model->getCurrentAssignments($type);
        
        // Tính năm học hiện tại để truyền vào view
        $currentSchoolYear = $this->getCurrentSchoolYear();

        include "views/management/teacher_assignment.php";
    }

    // Basic Flow - Phân công giáo viên chủ nhiệm
    public function assignHomeRoomTeacher() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=teacherAssignment&action=index");
            exit;
        }

        $maGV = $_POST['maGV'] ?? '';
        $maLop = $_POST['maLop'] ?? '';
        // Sử dụng năm học tự động thay vì lấy từ POST
        $namHoc = $this->getCurrentSchoolYear();

        if (empty($maGV) || empty($maLop)) {
            $msg = "⚠️ Vui lòng chọn đầy đủ giáo viên và lớp";
            header("Location: index.php?controller=teacherAssignment&action=index&type=chunhiem&msg=" . urlencode($msg));
            exit;
        }

        $model = new TeacherAssignment($this->db);
        $result = $model->assignHomeRoomTeacher($maGV, $maLop, $namHoc);

        if ($result === true) {
            $msg = "✅ Phân công giáo viên chủ nhiệm thành công!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=teacherAssignment&action=index&type=chunhiem&msg=" . urlencode($msg));
        exit;
    }

    // Basic Flow - Phân công giáo viên bộ môn
    public function assignSubjectTeacher() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=teacherAssignment&action=index");
            exit;
        }

        $maGV = $_POST['maGV'] ?? '';
        $maLop = $_POST['maLop'] ?? '';
        $maMon = $_POST['maMon'] ?? '';
        // Sử dụng năm học tự động thay vì lấy từ POST
        $namHoc = $this->getCurrentSchoolYear();

        if (empty($maGV) || empty($maLop) || empty($maMon)) {
            $msg = "⚠️ Vui lòng chọn đầy đủ giáo viên, lớp và môn học";
            header("Location: index.php?controller=teacherAssignment&action=index&type=bomon&msg=" . urlencode($msg));
            exit;
        }

        $model = new TeacherAssignment($this->db);
        $result = $model->assignSubjectTeacher($maGV, $maLop, $maMon, $namHoc);

        if ($result === true) {
            $msg = "✅ Phân công giáo viên bộ môn thành công!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=teacherAssignment&action=index&type=bomon&msg=" . urlencode($msg));
        exit;
    }

    // AJAX: Lấy danh sách môn học theo lớp
    public function getSubjectsByClass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $maLop = $_POST['maLop'] ?? '';
        
        if (empty($maLop)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing class code']);
            return;
        }

        $model = new TeacherAssignment($this->db);
        $subjects = $model->getAvailableSubjectsForClass($maLop);
        
        header('Content-Type: application/json');
        echo json_encode($subjects);
    }

    // AJAX: Lấy danh sách giáo viên theo môn học
    public function getTeachersBySubject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $maMon = $_POST['maMon'] ?? '';
        
        if (empty($maMon)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing subject code']);
            return;
        }

        $model = new TeacherAssignment($this->db);
        $teachers = $model->getTeachersBySubject($maMon);
        
        header('Content-Type: application/json');
        echo json_encode($teachers);
    }

    // Hủy phân công giáo viên chủ nhiệm
    public function cancelHomeRoomTeacher() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=teacherAssignment&action=index");
            exit;
        }

        $maPCGVCN = $_POST['maPCGVCN'] ?? '';

        if (empty($maPCGVCN)) {
            $msg = "⚠️ Không tìm thấy phân công cần hủy";
            header("Location: index.php?controller=teacherAssignment&action=index&type=chunhiem&msg=" . urlencode($msg));
            exit;
        }

        $model = new TeacherAssignment($this->db);
        $result = $model->cancelHomeRoomTeacher($maPCGVCN);

        if ($result === true) {
            $msg = "✅ Đã hủy phân công giáo viên chủ nhiệm!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=teacherAssignment&action=index&type=chunhiem&msg=" . urlencode($msg));
        exit;
    }

    // Hủy phân công giáo viên bộ môn
    public function cancelSubjectTeacher() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=teacherAssignment&action=index");
            exit;
        }

        $maPCGVBM = $_POST['maPCGVBM'] ?? '';

        if (empty($maPCGVBM)) {
            $msg = "⚠️ Không tìm thấy phân công cần hủy";
            header("Location: index.php?controller=teacherAssignment&action=index&type=bomon&msg=" . urlencode($msg));
            exit;
        }

        $model = new TeacherAssignment($this->db);
        $result = $model->cancelSubjectTeacher($maPCGVBM);

        if ($result === true) {
            $msg = "✅ Đã hủy phân công giáo viên bộ môn!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=teacherAssignment&action=index&type=bomon&msg=" . urlencode($msg));
        exit;
    }
}
?>