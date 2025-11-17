<?php
// controllers/ClassroomController.php - FIXED VERSION
require_once "models/Classroom.php";

class ClassroomController {
    private $db;
    private $model;
    
    public function __construct($db) {
        $this->db = $db;
        $this->model = new Classroom($db);
    }

    // Basic Flow - Bước 1-2: Hiển thị danh sách học sinh trong lớp
    public function index() {
        session_start();
        $this->checkTeacherPermission();
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Lấy danh sách lớp mà giáo viên chủ nhiệm
        $classList = $this->model->getClassesByTeacher($maGV);
        
        // ĐẾM SỐ ĐƠN XIN NGHỈ CHỜ DUYỆT
        $pendingCount = $this->model->countPendingLeaveRequests($maGV);
        
        // Nếu chỉ có 1 lớp, tự động chuyển đến trang quản lý
        if (count($classList) === 1) {
            $maLop = $classList[0]['maLop'];
            header("Location: index.php?controller=classroom&action=manage&maLop=" . $maLop);
            exit;
        }
        
        include "views/classroom/index.php";
    }

    // Basic Flow - Bước 2: Hiển thị danh sách học sinh
    public function manage() {
        session_start();
        $this->checkTeacherPermission();
        
        $maLop = $_GET['maLop'] ?? '';
        
        if (empty($maLop)) {
            $_SESSION['error'] = "Vui lòng chọn lớp học";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra giáo viên có phải GVCN của lớp này không
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error'] = "Bạn không phải giáo viên chủ nhiệm của lớp này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin lớp
        $classInfo = $this->model->getClassInfo($maLop);
        
        // Lấy danh sách học sinh trong lớp
        $studentList = $this->model->getStudentsByClass($maLop);
        
        include "views/classroom/view_students.php";
    }

    // Basic Flow - Bước 3.1: Xem thông tin chi tiết học sinh
    public function viewStudent() {
        session_start();
        $this->checkTeacherPermission();
        
        $maHS = $_GET['maHS'] ?? '';
        $maLop = $_GET['maLop'] ?? '';
        
        if (empty($maHS) || empty($maLop)) {
            $_SESSION['error'] = "Thiếu thông tin học sinh hoặc lớp";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error'] = "Bạn không có quyền xem thông tin này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin chi tiết học sinh
        $studentInfo = $this->model->getStudentDetail($maHS);
        
        // Lấy thông tin phụ huynh
        $parentInfo = $this->model->getParentInfo($maHS);
        
        // Lấy kết quả học tập
        $academicResults = $this->model->getAcademicResults($maHS);
        
        // Lấy hạnh kiểm
        $conductInfo = $this->model->getConductInfo($maHS);
        
        include "views/classroom/view_student_detail.php";
    }

    // Basic Flow - Bước 3.2: Xem bảng điểm học sinh
    public function viewScores() {
        session_start();
        $this->checkTeacherPermission();
        
        $maHS = $_GET['maHS'] ?? '';
        $maLop = $_GET['maLop'] ?? '';
        $maMon = $_GET['maMon'] ?? '';
        
        if (empty($maHS) || empty($maLop)) {
            $_SESSION['error'] = "Thiếu thông tin học sinh hoặc lớp";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error'] = "Bạn không có quyền xem thông tin này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin học sinh
        $studentInfo = $this->model->getStudentInfo($maHS);
        
        // Lấy danh sách môn học
        $subjectList = $this->model->getAllSubjects();
        
        // Lấy bảng điểm
        if (!empty($maMon)) {
            $scores = $this->model->getScoresBySubject($maHS, $maMon);
            
            // Alternative Flow 3.1: Kiểm tra nếu chưa có điểm
            if (empty($scores)) {
                $_SESSION['info'] = "Chưa có điểm cho môn học này";
            }
        } else {
            $scores = [];
        }
        
        include "views/classroom/view_scores_detail.php";
    }

    // Basic Flow - Bước 3.3: Xem điểm danh CÁ NHÂN (CHỈ 1 HỌC SINH)
    public function viewAttendance() {
        session_start();
        $this->checkTeacherPermission();
        
        error_log("=== VIEW ATTENDANCE DEBUG ===");
        error_log("GET params: " . print_r($_GET, true));
        
        $maHS = $_GET['maHS'] ?? '';
        $maLop = $_GET['maLop'] ?? '';
        
        error_log("maHS: $maHS, maLop: $maLop");
        
        if (empty($maHS) || empty($maLop)) {
            error_log("ERROR: Missing maHS or maLop");
            $_SESSION['error_message'] = "Thiếu thông tin học sinh hoặc lớp";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        error_log("Teacher ID: $maGV");
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            error_log("ERROR: Not homeroom teacher");
            $_SESSION['error_message'] = "Bạn không có quyền xem thông tin này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin học sinh
        $studentInfo = $this->model->getStudentInfo($maHS);
        error_log("Student info: " . print_r($studentInfo, true));
        
        // Lấy lịch sử điểm danh (từ bảng donnghihoc)
        $attendanceHistory = $this->model->getAttendanceHistory($maHS);
        error_log("Attendance history count: " . count($attendanceHistory));
        
        // Tính toán thống kê
        $attendanceStats = $this->model->getAttendanceStats($maHS);
        error_log("Attendance stats: " . print_r($attendanceStats, true));
        
        error_log("Loading view: views/classroom/view_attendance.php");
        include "views/classroom/view_attendance.php";
    }

    // ========== DANH SÁCH HỌC SINH LỚP CHỦ NHIỆM ==========
    
    // Xem danh sách học sinh trong lớp chủ nhiệm (CÓ TÌM KIẾM)
    public function studentList() {
        session_start();
        $this->checkTeacherPermission();
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Lấy tham số
        $maLop = $_GET['maLop'] ?? '';
        $gioiTinh = $_GET['gioiTinh'] ?? '';
        $keyword = $_GET['keyword'] ?? '';
        
        // Nếu không có maLop, lấy lớp đầu tiên của giáo viên
        if (empty($maLop)) {
            $classList = $this->model->getClassesByTeacher($maGV);
            
            if (empty($classList)) {
                $_SESSION['error'] = "Bạn chưa được phân công làm chủ nhiệm lớp nào";
                header("Location: index.php?controller=classroom&action=index");
                exit;
            }
            $maLop = $classList[0]['maLop'];
        }
        
        // Kiểm tra quyền GVCN
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error'] = "Bạn không phải giáo viên chủ nhiệm của lớp này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin lớp
        $classroom = $this->model->getClassInfo($maLop);
        
        // Lấy danh sách học sinh (CÓ TÌM KIẾM)
        if (!empty($gioiTinh) || !empty($keyword)) {
            $studentList = $this->model->searchStudentsInClass($maLop, $gioiTinh, $keyword);
        } else {
            $studentList = $this->model->getStudentsByClass($maLop);
        }
        
        // Thống kê
        $stats = [
            'total' => count($studentList),
            'male' => count(array_filter($studentList, fn($s) => $s['gioiTinh'] === 'Nam')),
            'female' => count(array_filter($studentList, fn($s) => $s['gioiTinh'] === 'Nữ')),
        ];
        
        include "views/classroom/student_list.php";
    }

    // ========== LỊCH DẠY ==========
    
    // Xem lịch dạy của giáo viên
    public function schedule() {
        session_start();
        $this->checkTeacherPermission();
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Lấy tham số
        $view = $_GET['view'] ?? 'week'; // week hoặc day
        $date = $_GET['date'] ?? date('Y-m-d');
        
        // Nếu view = week, lấy thứ 2 đầu tuần
        if ($view === 'week') {
            $timestamp = strtotime($date);
            $dayOfWeek = date('N', $timestamp); // 1 (Mon) to 7 (Sun)
            $monday = date('Y-m-d', strtotime('-' . ($dayOfWeek - 1) . ' days', $timestamp));
            $scheduleData = $this->model->getWeeklySchedule($maGV, $monday);
        } else {
            $scheduleData = $this->model->getTeachingSchedule($maGV, $date);
        }
        
        // Lấy thống kê
        $stats = $this->model->getScheduleStats($maGV);
        
        // Lấy danh sách môn giảng dạy
        $subjects = $this->model->getTeachingSubjects($maGV);
        
        // Xử lý dữ liệu cho view tuần
        if ($view === 'week') {
            $weekSchedule = [];
            foreach ($scheduleData as $item) {
                $thu = $item['thuTrongTuan'];
                $tiet = $item['tiet'];
                $weekSchedule[$thu][$tiet] = $item;
            }
        } else {
            $weekSchedule = null;
        }
        
        include "views/classroom/schedule.php";
    }

    // ========== ĐIỂM DANH CẢ LỚP - FIXED COMPLETE ==========
    
    public function classAttendance() {
        session_start();
        $this->checkTeacherPermission();
        
        // Debug logging
        error_log("=== CLASS ATTENDANCE DEBUG ===");
        error_log("GET params: " . print_r($_GET, true));
        
        $maLop = $_GET['maLop'] ?? '';
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        // FIX: Accept both 'viewMode' and 'view' parameters
        $viewMode = $_GET['viewMode'] ?? $_GET['view'] ?? 'day';
        
        error_log("maLop: $maLop, date: $selectedDate, viewMode: $viewMode");
        
        if (empty($maLop)) {
            error_log("ERROR: Missing maLop");
            $_SESSION['error_message'] = "Thiếu thông tin lớp học";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        error_log("Teacher ID: $maGV");
        
        // Kiểm tra quyền GVCN
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            error_log("ERROR: Not homeroom teacher");
            $_SESSION['error_message'] = "Bạn không phải giáo viên chủ nhiệm của lớp này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lấy thông tin lớp
        $classInfo = $this->model->getClassInfo($maLop);
        error_log("Class info: " . print_r($classInfo, true));
        
        // ===== QUAN TRỌNG: LẤY DANH SÁCH HỌC SINH =====
        $students = $this->model->getStudentsByClass($maLop);
        error_log("Number of students: " . count($students));
        
        // ===== XỬ LÝ THEO VIEW MODE =====
        
        if ($viewMode === 'day') {
            // ĐIỂM DANH THEO NGÀY
            $savedAttendanceRaw = $this->model->getAttendanceByDate($maLop, $selectedDate);
            error_log("Saved attendance count: " . count($savedAttendanceRaw));
            
            // Không cần weekDates
            $weekDates = [];
            $weekAttendanceRaw = [];
            
            // Không cần leaveRequests
            $leaveRequests = [];
            $leaveStats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
            
        } elseif ($viewMode === 'week') {
            // XEM THEO TUẦN
            $monday = date('Y-m-d', strtotime('monday this week', strtotime($selectedDate)));
            error_log("Week starts from: $monday");
            
            // Tạo mảng các ngày trong tuần
            $weekDates = [];
            for ($i = 0; $i < 6; $i++) {
                $weekDates[] = date('Y-m-d', strtotime("$monday +$i days"));
            }
            
            // Lấy điểm danh cả tuần
            $weekAttendanceRaw = $this->model->getWeekAttendance($maLop, $monday);
            error_log("Week attendance data: " . print_r($weekAttendanceRaw, true));
            
            // Không cần savedAttendance cho view tuần
            $savedAttendanceRaw = [];
            
            // Không cần leaveRequests
            $leaveRequests = [];
            $leaveStats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
            
        } elseif ($viewMode === 'leave_requests') {
            // XÉT DUYỆT ĐƠN XIN NGHỈ
            error_log("Loading leave requests for class: $maLop");
            
            // Lấy đơn xin nghỉ của lớp (JOIN để lấy tên học sinh)
            $leaveRequests = $this->model->getLeaveRequestsByClass($maLop);
            error_log("Leave requests count: " . count($leaveRequests));
            
            // Tính thống kê
            $leaveStats = [
                'total' => count($leaveRequests),
                'pending' => count(array_filter($leaveRequests, fn($r) => $r['trangThai'] === 'ChoXuLy')),
                'approved' => count(array_filter($leaveRequests, fn($r) => $r['trangThai'] === 'DaDuyet')),
                'rejected' => count(array_filter($leaveRequests, fn($r) => $r['trangThai'] === 'TuChoi'))
            ];
            error_log("Leave stats: " . print_r($leaveStats, true));
            
            // Không cần attendance data
            $savedAttendanceRaw = [];
            $weekDates = [];
            $weekAttendanceRaw = [];
        } else {
            // Default: day mode
            $savedAttendanceRaw = $this->model->getAttendanceByDate($maLop, $selectedDate);
            $weekDates = [];
            $weekAttendanceRaw = [];
            $leaveRequests = [];
            $leaveStats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
        }
        
        error_log("Loading view: views/classroom/view_attendance.php");
        
        // Load view
        include "views/classroom/view_attendance.php";
    }
    
    // Lưu điểm danh - FIXED
    public function saveAttendance() {
        session_start();
        $this->checkTeacherPermission();
        
        error_log("=== SAVE ATTENDANCE DEBUG ===");
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("ERROR: Not a POST request");
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $maLop = $_POST['maLop'] ?? '';
        $date = $_POST['date'] ?? '';
        $attendance = $_POST['attendance'] ?? [];
        
        error_log("maLop: $maLop, date: $date");
        error_log("Attendance data: " . print_r($attendance, true));
        
        if (empty($maLop) || empty($date) || empty($attendance)) {
            error_log("ERROR: Missing required data");
            $_SESSION['error_message'] = "Dữ liệu không hợp lệ";
            header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&viewMode=day");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            error_log("ERROR: Not homeroom teacher");
            $_SESSION['error_message'] = "Bạn không có quyền thực hiện thao tác này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Lưu điểm danh vào database
        $result = $this->model->saveClassAttendance($maLop, $date, $attendance, $maGV);
        
        error_log("Save result: " . ($result ? "SUCCESS" : "FAILED"));
        
        if ($result) {
            $_SESSION['success_message'] = "Đã lưu điểm danh thành công!";
        } else {
            $_SESSION['error_message'] = "Có lỗi xảy ra khi lưu điểm danh";
        }
        
        header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&date=$date&viewMode=day");
        exit;
    }

    // ========== PHÊ DUYỆT/TỪ CHỐI ĐƠN NGHỈ - FIXED ==========
    
    // Xử lý phê duyệt đơn nghỉ học - FIXED
    public function approveLeave() {
        session_start();
        $this->checkTeacherPermission();
        
        error_log("=== APPROVE LEAVE DEBUG ===");
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $maDon = $_POST['maDon'] ?? '';
        $maLop = $_POST['maLop'] ?? '';
        
        if (empty($maDon) || empty($maLop)) {
            $_SESSION['error_message'] = "Thiếu thông tin cần thiết";
            header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&viewMode=leave_requests");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error_message'] = "Bạn không có quyền thực hiện thao tác này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Thực hiện phê duyệt
        $result = $this->model->approveLeaveRequest($maDon);
        
        if ($result) {
            $_SESSION['success_message'] = "Đã phê duyệt đơn nghỉ học thành công!";
        } else {
            $_SESSION['error_message'] = "Có lỗi xảy ra khi phê duyệt đơn. Vui lòng kiểm tra lại.";
        }
        
        // Redirect về tab đơn xin nghỉ
        header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&viewMode=leave_requests");
        exit;
    }

    // Xử lý từ chối đơn nghỉ học - FIXED
    public function rejectLeave() {
        session_start();
        $this->checkTeacherPermission();
        
        error_log("=== REJECT LEAVE DEBUG ===");
        error_log("POST data: " . print_r($_POST, true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        $maDon = $_POST['maDon'] ?? '';
        $maLop = $_POST['maLop'] ?? '';
        
        if (empty($maDon) || empty($maLop)) {
            $_SESSION['error_message'] = "Thiếu thông tin cần thiết";
            header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&viewMode=leave_requests");
            exit;
        }
        
        $user = $_SESSION['user'];
        $maGV = $user['teacher_id'] ?? '';
        
        // Kiểm tra quyền
        if (!$this->model->isHomeRoomTeacher($maGV, $maLop)) {
            $_SESSION['error_message'] = "Bạn không có quyền thực hiện thao tác này";
            header("Location: index.php?controller=classroom&action=index");
            exit;
        }
        
        // Thực hiện từ chối
        $result = $this->model->rejectLeaveRequest($maDon);
        
        if ($result) {
            $_SESSION['success_message'] = "Đã từ chối đơn nghỉ học!";
        } else {
            $_SESSION['error_message'] = "Có lỗi xảy ra khi từ chối đơn. Vui lòng kiểm tra lại.";
        }
        
        // Redirect về tab đơn xin nghỉ
        header("Location: index.php?controller=classroom&action=classAttendance&maLop=$maLop&viewMode=leave_requests");
        exit;
    }

    // Kiểm tra quyền giáo viên
    private function checkTeacherPermission() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            $_SESSION['error'] = "Chỉ giáo viên mới có quyền truy cập!";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }
}
?>