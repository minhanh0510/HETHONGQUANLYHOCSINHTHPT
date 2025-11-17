<?php
require_once "models/ExamScore.php";
class ExamScoreController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ExamScore($this->db);
    }

    // Hiển thị danh sách trường học
    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $schools = $this->model->getAllSchools();
        include "views/department/exam_score_index.php";
    }

    // Hiển thị danh sách phòng thi của trường đã chọn
    public function rooms() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $schoolId = $_GET['schoolId'] ?? null;
        if (!$schoolId) {
            header("Location: index.php?controller=examScore&action=index&error=" . urlencode("Thiếu mã trường."));
            exit;
        }

        $rooms = $this->model->getRoomsBySchool($schoolId);
        include "views/department/exam_score_room.php";
    }

    // Hiển thị form nhập điểm cho học sinh trong phòng thi
    public function input() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $roomId = $_GET['roomId'] ?? null;
        if (!$roomId) {
            header("Location: index.php?controller=examScore&action=index&error=" . urlencode("Thiếu mã phòng thi."));
            exit;
        }

        $students = $this->model->getStudentsByRoom($roomId);
        include "views/department/exam_score_input.php";
    }

    // Lưu điểm tuyển sinh
    public function save() {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
        header("Location: index.php?controller=auth&action=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $scores = $_POST['scores'] ?? [];
        $roomId = $_POST['roomId'] ?? null;

        if (!$roomId || empty($scores)) {
            header("Location: index.php?controller=examScore&action=input&roomId=$roomId&error=" . urlencode("Dữ liệu không hợp lệ."));
            exit;
        }

        foreach ($scores as $studentId => $score) {
            if ($score === '' || $score < 0 || $score > 10) {
                header("Location: index.php?controller=examScore&action=input&roomId=$roomId&error=" . urlencode("Điểm không hợp lệ. Vui lòng nhập lại."));
                exit;
            }
        }

        $result = $this->model->saveScores($roomId, $scores);

        if ($result) {
            $_SESSION['success'] = "✅ Lưu điểm thành công!";
        } else {
            $_SESSION['error'] = "❌ Lỗi khi lưu điểm.";
        }

        header("Location: index.php?controller=examScore&action=input&roomId=$roomId");
        exit;

    }

    header("Location: index.php?controller=examScore&action=index");
    exit;
}
}
?>
<?php