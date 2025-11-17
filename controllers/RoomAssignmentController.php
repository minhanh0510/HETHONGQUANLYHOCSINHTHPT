<?php

require_once "models/RoomAssignment.php";

class RoomAssignmentController {
    private $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new RoomAssignment($this->db);
    }

    // Hiển thị danh sách phòng thi
    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=roomAssignment&action=assign&roomId=$roomId&redirect=index");
            exit;
        }

        $rooms = $this->model->getAllRooms();
        include "views/admin/room_assignment_index.php";
    }

    // Hiển thị danh sách học sinh chưa được phân công
    public function assign() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $roomId = $_GET['roomId'] ?? null;
        if (!$roomId) {
            $_SESSION['error'] = "❌ Thiếu mã phòng thi.";
            header("Location: index.php?controller=roomAssignment&action=index");
            exit;
        }

        $students = $this->model->getUnassignedStudents($roomId);
        include "views/admin/room_assignment_assign.php";
    }

    // Gán học sinh vào phòng thi
    public function save() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId = $_POST['roomId'] ?? null;
            $studentId = $_POST['studentId'] ?? null;

            if (!$roomId || !$studentId) {
                $_SESSION['error'] = "❌ Dữ liệu không hợp lệ.";
                header("Location: index.php?controller=roomAssignment&action=assign&roomId=$roomId");
                exit;
            }

            // Kiểm tra sĩ số phòng
            $roomInfo = $this->model->getRoomInfo($roomId);
            $currentCapacity = $roomInfo['soLuongHienTai'];   // lấy từ CSDL
            $maxCapacity     = $roomInfo['soLuongToiDa'];

            error_log("Current Capacity: $currentCapacity, Max Capacity: $maxCapacity"); // Log giá trị

            if ($currentCapacity >= $maxCapacity) {
                $_SESSION['error'] = "❌ Phòng thi đã đủ sĩ số. Không thể thêm học sinh.";
                header("Location: index.php?controller=roomAssignment&action=assign&roomId=$roomId&redirect=index");
                exit;
            }


            // Thêm học sinh vào phòng thi
            $result = $this->model->assignStudentToRoom($studentId, $roomId);

            if ($result) {
                // Cập nhật sĩ số hiện tại
                $this->model->updateRoomCapacity($roomId);
                $_SESSION['success'] = "✅ Phân công thành công!";
            } else {
                $_SESSION['error'] = "❌ Lỗi khi phân công.";
            }

            header("Location: index.php?controller=roomAssignment&action=assign&roomId=$roomId");
            exit;
        }

        // Nếu không phải POST, quay lại trang danh sách phòng
        header("Location: index.php?controller=roomAssignment&action=index");
        exit;
    }
}

?>
