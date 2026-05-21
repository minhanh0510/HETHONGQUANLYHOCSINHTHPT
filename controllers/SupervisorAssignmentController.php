<?php
require_once "models/SupervisorAssignment.php";

class SupervisorAssignmentController {
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

    // Hiển thị trang phân công giám thị
    public function index() {
        $this->requireManagement();

        $message = $_GET['msg'] ?? '';
        $model = new SupervisorAssignment($this->db);
        
        // Kiểm tra nếu có maKyThi từ URL
        $selectedExamFromURL = $_GET['maKyThi'] ?? null;
        
        // Lấy thông tin học kỳ, năm học hiện tại
        $currentInfo = $model->getCurrentAcademicInfo();
        $selectedYear = $currentInfo['namHoc'];
        $selectedSemester = $currentInfo['hocKy'];
        
        // Xác định kỳ thi hiển thị
        if ($selectedExamFromURL) {
            $selectedExam = $selectedExamFromURL;
        } else {
            $selectedExam = $model->getCurrentExamPeriod($selectedSemester, $selectedYear);
        }
        
        $examRooms = [];
        $roomAssignments = [];
        $examInfo = null;
        $examSubjects = [];
        $expiredSessionsCount = 0;

        if ($selectedExam) {
            $examInfo = $model->getExamInfo($selectedExam);
            if ($examInfo) {
                // Cập nhật năm học và học kỳ theo kỳ thi được chọn
                $selectedYear = $examInfo['namHoc'];
                $selectedSemester = $examInfo['hocKy'];
            }
            
            $expiredSessionsCount = $model->getExpiredSessionsCount($selectedExam);
            $examSubjects = $model->getAllExamSessionsInCurrentSemester();
            $examRooms = $model->getExamRoomsForAllExamsInCurrentSemester();
            
            // Lấy phân công giám thị cho từng phòng
            foreach ($examRooms as $room) {
                // Lấy maKyThi thực tế của phòng
                $roomAssignments[$room['maPhong']] = $model->getSupervisorsByRoomAndExam(
                    $room['maPhong'], 
                    $room['maKyThi']
                );
            }
        }

        include "views/management/supervisor_assignment.php";
    }

    // AJAX: Lấy danh sách giáo viên khả dụng
    public function getAvailableSupervisors() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $maPhong = $_POST['maPhong'] ?? '';
        $maKyThi = $_POST['maKyThi'] ?? '';
        $ngayThi = $_POST['ngayThi'] ?? '';
        $caThi = $_POST['caThi'] ?? '';
        
        if (empty($maPhong) || empty($maKyThi) || empty($ngayThi) || empty($caThi)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $model = new SupervisorAssignment($this->db);
        $teachers = $model->getAvailableSupervisors($maPhong, $ngayThi, $caThi, $maKyThi);
        
        header('Content-Type: application/json');
        echo json_encode($teachers);
    }

    // Phân công nhiều giám thị
    public function assignMultipleSupervisors() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=supervisorAssignment&action=index");
            exit;
        }

        $maGV1 = $_POST['maGV1'] ?? '';
        $maGV2 = $_POST['maGV2'] ?? '';
        $maPhong = $_POST['maPhong'] ?? '';
        $maKyThi = $_POST['maKyThi'] ?? '';

        if (empty($maPhong) || empty($maKyThi) || (empty($maGV1) && empty($maGV2))) {
            $msg = "⚠️ Vui lòng chọn ít nhất 1 giám thị";
            header("Location: index.php?controller=supervisorAssignment&action=index&msg=" . urlencode($msg));
            exit;
        }

        if (!empty($maGV1) && !empty($maGV2) && $maGV1 === $maGV2) {
            $msg = "⚠️ Không được chọn cùng một giáo viên cho 2 vị trí";
            header("Location: index.php?controller=supervisorAssignment&action=index&msg=" . urlencode($msg));
            exit;
        }

        $model = new SupervisorAssignment($this->db);
        
        $maGVs = [];
        if (!empty($maGV1)) $maGVs[] = $maGV1;
        if (!empty($maGV2)) $maGVs[] = $maGV2;

        $result = $model->assignMultipleSupervisors($maGVs, $maPhong, $maKyThi);

        if ($result === true) {
            $msg = "✅ Phân công " . count($maGVs) . " giám thị thành công!";
        } else {
            $msg = $result;
        }

        // Redirect với mã kỳ thi để đảm bảo hiển thị đúng
        header("Location: index.php?controller=supervisorAssignment&action=index&maKyThi=" . $maKyThi . "&msg=" . urlencode($msg));
        exit;
    }

    // Hủy phân công giám thị
    public function cancelSupervisorAssignment() {
        $this->requireManagement();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=supervisorAssignment&action=index");
            exit;
        }

        $maPCGT = $_POST['maPCGT'] ?? '';
        $maKyThi = $_POST['maKyThi'] ?? '';

        if (empty($maPCGT)) {
            $msg = "⚠️ Không tìm thấy phân công cần hủy";
            header("Location: index.php?controller=supervisorAssignment&action=index&msg=" . urlencode($msg));
            exit;
        }

        $model = new SupervisorAssignment($this->db);
        $result = $model->cancelSupervisorAssignment($maPCGT);

        if ($result === true) {
            $msg = "✅ Đã hủy phân công giám thị!";
        } else {
            $msg = $result;
        }

        header("Location: index.php?controller=supervisorAssignment&action=index&maKyThi=" . $maKyThi . "&msg=" . urlencode($msg));
        exit;
    }

    // AJAX: Lấy danh sách phòng thi theo ca thi
    public function getExamRoomsBySession() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $ngayThi = $_POST['ngayThi'] ?? '';
        $buoiThi = $_POST['buoiThi'] ?? '';
        
        if (empty($ngayThi) || empty($buoiThi)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $model = new SupervisorAssignment($this->db);
        $rooms = $model->getExamRoomsBySession($ngayThi, $buoiThi);
        
        header('Content-Type: application/json');
        echo json_encode($rooms);
    }

    // AJAX: Lấy thông tin chi tiết phòng thi
    public function getRoomDetails() {
        $maPhong = $_GET['maPhong'] ?? '';
        $maKyThi = $_GET['maKyThi'] ?? '';
        
        if (empty($maPhong) || empty($maKyThi)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $model = new SupervisorAssignment($this->db);
        $roomDetails = $model->getRoomDetails($maPhong, $maKyThi);
        
        header('Content-Type: application/json');
        echo json_encode($roomDetails);
    }
}
?>