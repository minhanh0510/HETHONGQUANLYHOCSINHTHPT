<?php
require_once "models/Schedule.php";

class ScheduleAdminController {
    private $db;
    
    public function __construct($db){
        $this->db = $db;
    }

    private function requireAdmin(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin"){
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /* ===============================================================
       MAIN SCREEN - Hiển thị giao diện quản lý thời khóa biểu
    =============================================================== */
    public function index(){
        $this->requireAdmin();

        // Lấy params
        $khoi = $_GET["khoi"] ?? "K10";
        $maLop = $_GET["lop"] ?? "";
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";
        $week = $_GET["tuan"] ?? date("Y-\WW");
        $mode = "semester"; // Luôn xem theo học kỳ

        $model = new Schedule($this->db);
        
        // Lấy danh sách lớp theo khối
        $lopList = $model->getClasses($khoi);
        
        // Nếu chưa chọn lớp, chọn lớp đầu tiên
        if(empty($maLop) && !empty($lopList)){
            $maLop = $lopList[0]['maLop'];
        }

        // Tính tuanBatDau và tuanKetThuc từ tuần được chọn
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Lấy danh sách dữ liệu
        $monList = $model->getSubjects();
        $gvList  = $model->getTeachers();
        $roomList = $model->getRooms();
        $defaultRoom = $model->getDefaultRoom($maLop);
        
        // Lấy phân công giáo viên bộ môn từ bảng pcgvbm
        $teacherAssignments = $model->getTeacherAssignmentsByClass($maLop, $namHoc);

        // Kiểm tra có preview trong session không
        $hasPreview = isset($_SESSION["schedule_preview"]) && $_SESSION["schedule_preview"]["maLop"] === $maLop;
        $previewData = $hasPreview ? $_SESSION["schedule_preview"]["data"] : [];

        // Lấy thời khóa biểu
        if($hasPreview){
            // Nếu có preview → hiển thị preview
            $tkb = $previewData;
            $hasCustomSchedule = false;
        } else {
            // Không có preview → lấy từ DB: ưu tiên lịch tuần, rồi mới đến học kỳ
            $tkb = $model->getScheduleForWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
            $hasCustomSchedule = !empty($tkb);
        }
        
        $hasTemplate = false; // Không dùng template nữa
        $isWeekLocked = false; // Không khóa tuần nữa
        
        // Lấy thông tin số tiết từng môn
        $subjectHours = $model->getSubjectHoursSummarySemester($maLop, $hocKy, $namHoc);

        include "views/admin/schedule_management.php";
    }

    /* ===============================================================
       SAVE - Thêm/Cập nhật lịch học (AJAX)
    =============================================================== */
    public function save(){
        // Debug: Log that save was called
        error_log("=== ScheduleAdmin::save() CALLED ===");
        error_log("POST data: " . json_encode($_POST));
        
        $this->requireAdmin();
        
        // Clean any output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        $model = new Schedule($this->db);

        $data = [
            "maLop" => $_POST["maLop"] ?? '',
            "thu" => $_POST["thu"] ?? '',
            "tiet" => $_POST["tiet"] ?? '',
            "maMon" => $_POST["maMon"] ?? '',
            "maGV" => $_POST["maGV"] ?? '',
            "hocKy" => $_POST["hocKy"] ?? 1,
            "namHoc" => $_POST["namHoc"] ?? '2025-2026',
            "tbd" => $_POST["tbd"] ?? '',
            "tkt" => $_POST["tkt"] ?? '',
            "maPhong" => !empty($_POST["maPhong"]) ? $_POST["maPhong"] : null
        ];
        
        $mode = $_POST["mode"] ?? "week";
        $isEdit = $_POST["is_edit"] ?? 0;
        $maTKB = $_POST["maTKB"] ?? null;

        // Debug: show which field is empty
        $emptyFields = [];
        if(empty($data['maLop'])) $emptyFields[] = 'maLop';
        if(empty($data['maMon'])) $emptyFields[] = 'maMon';
        if(empty($data['maGV'])) $emptyFields[] = 'maGV';
        
        // Validate
        if(!empty($emptyFields)){
            echo json_encode([
                'success' => false, 
                'message' => 'Thiếu thông tin: ' . implode(', ', $emptyFields),
                'debug' => $data
            ]);
            exit;
        }

        // Kiểm tra tuần đã khóa
        if($model->isWeekLocked($data["tbd"])) {
            echo json_encode(['success' => false, 'message' => 'Tuần đã khóa, không thể thay đổi lịch học!']);
            exit;
        }

        // Kiểm tra số tiết/tuần
        $hourCheck = $model->checkWeeklyHours($data["maLop"], $data["maMon"], $data["tbd"], $data["tkt"], $data["hocKy"], $data["namHoc"]);
        if($hourCheck['vuot'] && $isEdit == 0){
            echo json_encode(['success' => false, 'message' => "Môn học đã đủ số tiết quy định ({$hourCheck['toiDa']} tiết/tuần)!"]);
            exit;
        }

        // Kiểm tra xung đột
        $conflicts = $model->checkConflict(
            $data["maLop"], $data["maGV"], $data["maPhong"], 
            $data["thu"], $data["tiet"], $data["tbd"], $data["tkt"], 
            $isEdit ? $maTKB : null
        );

        if(!empty($conflicts)){
            $messages = array_map(function($c){ return $c['message']; }, $conflicts);
            echo json_encode(['success' => false, 'message' => 'Xung đột lịch học: ' . implode('; ', $messages), 'conflicts' => $conflicts]);
            exit;
        }

        // Thực hiện lưu - Luôn lưu vào lịch tuần
        // Xóa slot cũ nếu có (theo hocKy và namHoc)
        $model->deleteSlot($data['maLop'], $data['thu'], $data['tiet'], $data['tbd'], $data['tkt'], $data['hocKy'], $data['namHoc']);
        
        try {
            $success = $model->add($data);
            
            if($success){
                // Xóa preview nếu có (vì đang edit trực tiếp)
                if(isset($_SESSION["schedule_preview"])){
                    unset($_SESSION["schedule_preview"]);
                }
                echo json_encode(['success' => true, 'message' => 'Lưu lịch học thành công!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lưu lịch học thất bại! Dữ liệu: ' . json_encode($data)]);
            }
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    /* ===============================================================
       DELETE - Xóa lịch học (AJAX)
    =============================================================== */
    public function delete(){
        $this->requireAdmin();
        
        // Clean any output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        $model = new Schedule($this->db);
        
        $maLop = $_POST["maLop"] ?? $_GET["maLop"] ?? '';
        $thu = $_POST["thu"] ?? $_GET["thu"] ?? '';
        $tiet = $_POST["tiet"] ?? $_GET["tiet"] ?? '';
        $hocKy = $_POST["hocKy"] ?? $_GET["hocKy"] ?? 1;
        $namHoc = $_POST["namHoc"] ?? $_GET["namHoc"] ?? '2025-2026';
        $tbd = $_POST["tbd"] ?? $_GET["tbd"] ?? '';
        $tkt = $_POST["tkt"] ?? $_GET["tkt"] ?? '';
        $maTKB = $_POST["maTKB"] ?? $_GET["id"] ?? null;

        // Kiểm tra tuần khóa
        if($model->isWeekLocked($tbd)){
            echo json_encode(['success' => false, 'message' => 'Tuần đã khóa, không thể xóa lịch học!']);
            exit;
        }

        // Xóa entry - ưu tiên xóa theo hocKy và namHoc
        $success = $model->deleteSlot($maLop, $thu, $tiet, $tbd, $tkt, $hocKy, $namHoc);

        if($success){
            // Xóa preview nếu có
            if(isset($_SESSION["schedule_preview"])){
                unset($_SESSION["schedule_preview"]);
            }
            echo json_encode(['success' => true, 'message' => 'Xóa lịch học thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa lịch học thất bại!']);
        }
        exit;
    }

    /* ===============================================================
       AUTO ARRANGE - Xếp lịch tự động (CHỈ PREVIEW, KHÔNG LƯU DB)
    =============================================================== */
    public function autoArrange(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? $_POST["lop"] ?? '';
        $hocKy = $_GET["hocKy"] ?? $_POST["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? $_POST["namHoc"] ?? "2025-2026";
        $khoi = $_GET["khoi"] ?? "K10";
        $week = $_GET["tuan"] ?? date("Y-\WW");

        if(empty($maLop)){
            $_SESSION["error"] = "Vui lòng chọn lớp!";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi");
            exit;
        }

        $model = new Schedule($this->db);
        
        // Chỉ tạo preview, lưu vào SESSION
        $previewData = $model->autoArrangePreview($maLop, $hocKy, $namHoc);
        
        if($previewData !== false && !empty($previewData)){
            // Lưu preview vào session
            $_SESSION["schedule_preview"] = [
                'maLop' => $maLop,
                'hocKy' => $hocKy,
                'namHoc' => $namHoc,
                'data' => $previewData,
                'count' => count($previewData)
            ];
            $_SESSION["message"] = "Đã tạo lịch gợi ý với " . count($previewData) . " tiết học. Vui lòng xem và bấm 'Lưu tuần này' hoặc 'Áp dụng cả năm' để lưu.";
        } else {
            $_SESSION["error"] = "Tạo lịch tự động thất bại! Vui lòng thử lại.";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc&tuan=$week&mode=week");
        exit;
    }

    /* ===============================================================
       CONFIRM WEEK - Lưu lịch preview cho TUẦN HIỆN TẠI
    =============================================================== */
    public function confirmWeek(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? $_POST["lop"] ?? '';
        $hocKy = $_GET["hocKy"] ?? $_POST["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? $_POST["namHoc"] ?? "2025-2026";
        $week = $_GET["tuan"] ?? $_POST["tuan"] ?? date("Y-\WW");
        $khoi = $_GET["khoi"] ?? "K10";

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Lấy preview từ session
        if(!isset($_SESSION["schedule_preview"]) || $_SESSION["schedule_preview"]["maLop"] !== $maLop){
            $_SESSION["error"] = "Không có lịch gợi ý! Vui lòng bấm 'Xếp lịch tự động' trước.";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc&tuan=$week");
            exit;
        }

        $previewData = $_SESSION["schedule_preview"]["data"];
        
        // Lưu cho tuần này
        $result = $model->saveScheduleForWeek($maLop, $previewData, $tbd, $tkt, $hocKy, $namHoc);
        
        if($result !== false){
            $_SESSION["message"] = "Đã lưu lịch học cho tuần {$tbd} → {$tkt} thành công! ({$result} tiết)";
            // Xóa preview sau khi lưu
            unset($_SESSION["schedule_preview"]);
        } else {
            $_SESSION["error"] = "Lưu lịch thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc&tuan=$week");
        exit;
    }

    /* ===============================================================
       CONFIRM SCHEDULE - Lưu lịch preview cho HỌC KỲ HIỆN TẠI
    =============================================================== */
    public function confirmSchedule(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? $_POST["lop"] ?? '';
        $hocKy = $_GET["hocKy"] ?? $_POST["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? $_POST["namHoc"] ?? "2025-2026";
        $khoi = $_GET["khoi"] ?? "K10";

        $model = new Schedule($this->db);

        // Lấy preview từ session
        if(!isset($_SESSION["schedule_preview"]) || $_SESSION["schedule_preview"]["maLop"] !== $maLop){
            $_SESSION["error"] = "Không có lịch gợi ý! Vui lòng bấm 'Xếp lịch tự động' trước.";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc");
            exit;
        }

        $previewData = $_SESSION["schedule_preview"]["data"];
        
        // Lưu cho học kỳ này (dùng tuanBatDau và tuanKetThuc theo học kỳ)
        $result = $model->saveScheduleForSemester($maLop, $previewData, $hocKy, $namHoc);
        
        if($result !== false){
            $_SESSION["message"] = "Đã lưu lịch học cho Học kỳ $hocKy - $namHoc thành công! ({$result} tiết)";
            // Xóa preview sau khi lưu
            unset($_SESSION["schedule_preview"]);
        } else {
            $_SESSION["error"] = "Lưu lịch thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc");
        exit;
    }

    /* ===============================================================
       APPLY TO YEAR - Áp dụng lịch preview cho CẢ NĂM (HK1 + HK2)
    =============================================================== */
    public function applyToYear(){
        $this->requireAdmin();

        $maLop = $_POST["lop"] ?? $_GET["lop"] ?? '';
        $namHoc = $_POST["namHoc"] ?? $_GET["namHoc"] ?? "2025-2026";
        $khoi = $_POST["khoi"] ?? $_GET["khoi"] ?? "K10";

        if(empty($maLop)){
            $_SESSION["error"] = "Vui lòng chọn lớp!";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi");
            exit;
        }

        // Lấy preview từ session
        if(!isset($_SESSION["schedule_preview"]) || $_SESSION["schedule_preview"]["maLop"] !== $maLop){
            $_SESSION["error"] = "Không có lịch gợi ý! Vui lòng bấm 'Xếp lịch tự động' trước.";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop");
            exit;
        }

        $model = new Schedule($this->db);
        $previewData = $_SESSION["schedule_preview"]["data"];
        
        // Lưu cho cả 2 học kỳ
        $count1 = $model->saveScheduleForSemester($maLop, $previewData, 1, $namHoc);
        $count2 = $model->saveScheduleForSemester($maLop, $previewData, 2, $namHoc);
        
        $totalCount = ($count1 ?? 0) + ($count2 ?? 0);
        
        if($totalCount > 0){
            $_SESSION["message"] = "Đã áp dụng lịch cho cả năm học {$namHoc}! (HK1: {$count1} tiết, HK2: {$count2} tiết)";
            // Xóa preview sau khi lưu
            unset($_SESSION["schedule_preview"]);
        } else {
            $_SESSION["error"] = "Lưu lịch thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=1&namHoc=$namHoc");
        exit;
    }

    /* ===============================================================
       CANCEL PREVIEW - Hủy lịch gợi ý
    =============================================================== */
    public function cancelPreview(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? '';
        $khoi = $_GET["khoi"] ?? "K10";
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        // Xóa preview khỏi session
        unset($_SESSION["schedule_preview"]);
        $_SESSION["message"] = "Đã hủy lịch gợi ý.";

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc");
        exit;
    }

    /* ===============================================================
       CLEAR SEMESTER - Xóa lịch học kỳ
    =============================================================== */
    public function clearSemester(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? '';
        $khoi = $_GET["khoi"] ?? "K10";
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        if(empty($maLop)){
            $_SESSION["error"] = "Vui lòng chọn lớp!";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi");
            exit;
        }

        $model = new Schedule($this->db);
        $result = $model->clearScheduleSemester($maLop, $hocKy, $namHoc);
        
        if($result){
            $_SESSION["message"] = "Đã xóa lịch Học kỳ $hocKy - $namHoc thành công!";
        } else {
            $_SESSION["error"] = "Xóa lịch thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc");
        exit;
    }

    /* ===============================================================
       APPLY TEMPLATE - Áp dụng template cho tuần (legacy)
    =============================================================== */
    public function applyTemplate(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? '';
        $week = $_GET["tuan"] ?? date("Y-\WW");
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";
        $khoi = $_GET["khoi"] ?? "K10";

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Kiểm tra tuần khóa
        if ($model->isWeekLocked($tbd)) {
            $_SESSION["error"] = "Tuần đã khóa, không thể áp dụng lịch mẫu!";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&tuan=$week&mode=week");
            exit;
        }

        $success = $model->applyTemplateToWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
        
        if($success){
            $_SESSION["message"] = "Đã áp dụng lịch mẫu cho tuần này!";
        } else {
            $_SESSION["error"] = "Chưa có lịch mẫu để áp dụng!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&tuan=$week&hocKy=$hocKy&namHoc=$namHoc&mode=week");
        exit;
    }

    /* ===============================================================
       RESET WEEK - Xóa lịch tuần (quay về dùng template)
    =============================================================== */
    public function resetWeek(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? '';
        $week = $_GET["tuan"] ?? date("Y-\WW");
        $khoi = $_GET["khoi"] ?? "K10";
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Kiểm tra tuần khóa
        if ($model->isWeekLocked($tbd)) {
            $_SESSION["error"] = "Tuần đã khóa, không thể xóa lịch!";
            header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&tuan=$week&mode=week");
            exit;
        }

        $model->deleteWeekSchedule($maLop, $tbd, $tkt);
        $_SESSION["message"] = "Đã xóa lịch tuần này! Hệ thống sẽ hiển thị lịch mẫu.";

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&tuan=$week&hocKy=$hocKy&namHoc=$namHoc&mode=week");
        exit;
    }

    /* ===============================================================
       CLEAR TEMPLATE - Xóa toàn bộ lịch mẫu
    =============================================================== */
    public function clearTemplate(){
        $this->requireAdmin();

        $maLop = $_GET["lop"] ?? '';
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";
        $khoi = $_GET["khoi"] ?? "K10";

        $model = new Schedule($this->db);
        
        $success = $model->clearTemplate($maLop, $hocKy, $namHoc);
        
        if($success){
            $_SESSION["message"] = "Đã xóa toàn bộ lịch mẫu!";
        } else {
            $_SESSION["error"] = "Xóa lịch mẫu thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&khoi=$khoi&lop=$maLop&hocKy=$hocKy&namHoc=$namHoc&mode=template");
        exit;
    }

    /* ===============================================================
       GET SUGGESTED SLOTS (AJAX)
    =============================================================== */
    public function getSuggestedSlots(){
        $this->requireAdmin();

        $maLop = $_GET['lop'] ?? '';
        $maMon = $_GET['mon'] ?? '';
        $week = $_GET['tuan'] ?? date("Y-\WW");
        $hocKy = $_GET['hocKy'] ?? 1;
        $namHoc = $_GET['namHoc'] ?? '2025-2026';

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        $suggestions = $model->getSuggestedSlots($maLop, $maMon, $tbd, $tkt, $hocKy, $namHoc);

        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit;
    }

    /* ===============================================================
       CHECK CONFLICT (AJAX)
    =============================================================== */
    public function checkConflict(){
        $this->requireAdmin();
        
        $maLop = $_GET['lop'] ?? '';
        $thu = $_GET['thu'] ?? '';
        $tiet = $_GET['tiet'] ?? '';
        $maGV = $_GET['gv'] ?? '';
        $maPhong = $_GET['phong'] ?? '';
        $tbd = $_GET['tbd'] ?? '';
        $tkt = $_GET['tkt'] ?? '';
        $hocKy = $_GET['hocKy'] ?? 1;
        $namHoc = $_GET['namHoc'] ?? '2025-2026';
        $mode = $_GET['mode'] ?? 'template';
        
        $model = new Schedule($this->db);
        
        if($mode === 'template'){
            $conflicts = $model->checkTemplateConflict($maLop, $maGV, $maPhong, $thu, $tiet, $hocKy, $namHoc);
        } else {
            $conflicts = $model->checkConflict($maLop, $maGV, $maPhong, $thu, $tiet, $tbd, $tkt);
        }
        
        header('Content-Type: application/json');
        echo json_encode($conflicts);
        exit;
    }

    /* ===============================================================
       GET TEACHERS BY SUBJECT (AJAX)
    =============================================================== */
    public function getTeachersBySubject(){
        $this->requireAdmin();
        
        $maMon = $_GET['mon'] ?? '';
        
        $model = new Schedule($this->db);
        $teachers = $model->getTeachersBySubject($maMon);
        
        header('Content-Type: application/json');
        echo json_encode($teachers);
        exit;
    }

    /* ===============================================================
       GET CLASSES BY GRADE (AJAX)
    =============================================================== */
    public function getClassesByGrade(){
        $this->requireAdmin();
        
        $khoi = $_GET['khoi'] ?? 'K10';
        
        $model = new Schedule($this->db);
        $classes = $model->getClasses($khoi);
        
        header('Content-Type: application/json');
        echo json_encode($classes);
        exit;
    }

    /* ===============================================================
       CHECK WEEK LOCK (AJAX)
    =============================================================== */
    public function checkWeekLock(){
        $this->requireAdmin();
        
        $week = $_GET['week'] ?? date("Y-\WW");
        
        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);
        $isLocked = $model->isWeekLocked($tbd);
        
        header('Content-Type: application/json');
        echo json_encode(['locked' => $isLocked]);
        exit;
    }

    /* ===============================================================
       LOCK/UNLOCK WEEK (AJAX)
    =============================================================== */
    public function toggleWeekLock(){
        $this->requireAdmin();
        
        $week = $_POST['week'] ?? $_GET['week'] ?? date("Y-\WW");
        $lock = $_POST['lock'] ?? $_GET['lock'] ?? 1;
        
        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);
        
        $success = $model->lockWeek($tbd, $lock == 1);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'locked' => $lock == 1]);
        exit;
    }

    /* ===============================================================
       GET SCHEDULE STATS (AJAX)
    =============================================================== */
    public function getStats(){
        $this->requireAdmin();
        
        $maLop = $_GET['lop'] ?? '';
        $hocKy = $_GET['hocKy'] ?? 1;
        $namHoc = $_GET['namHoc'] ?? '2025-2026';
        
        $model = new Schedule($this->db);
        $stats = $model->getScheduleStats($maLop, $hocKy, $namHoc);
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }
}
?>
