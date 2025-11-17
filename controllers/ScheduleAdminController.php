<?php
require_once "models/Schedule.php";

class ScheduleAdminController {
    private $db;
    public function __construct($db){
        $this->db = $db;
    }

    private function requireAdmin(){
        session_start();
        if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin"){
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /* ===============================================================
       MAIN SCREEN
    =============================================================== */
    public function index(){
        $this->requireAdmin();

        $khoi = $_GET["khoi"] ?? "K10";
        $maLop = $_GET["lop"] ?? "L10A1";
        $week = $_GET["tuan"] ?? date("Y-\WW");
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        $lopList = $model->getClasses($khoi);
        $monList = $model->getSubjects();
        $gvList  = $model->getTeachers();
        $roomList = $model->getRooms();
        $defaultRoom = $model->getDefaultRoom($maLop);

        $tkb = $model->getByClassWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
        $hasTemplate = $model->templateExists($maLop, $hocKy, $namHoc);
        $hasCustomSchedule = $model->hasCustomSchedule($maLop, $tbd, $tkt);

        include "views/admin/schedule_management.php";
    }

    /* ===============================================================
       SAVE (Thêm/Cập nhật)
    =============================================================== */
    public function save(){
        $this->requireAdmin();

        $model = new Schedule($this->db);

        $data = [
            "maLop" => $_POST["maLop"],
            "thu" => $_POST["thu"],
            "tiet" => $_POST["tiet"],
            "maMon" => $_POST["maMon"],
            "maGV" => $_POST["maGV"],
            "hocKy" => $_POST["hocKy"],
            "namHoc" => $_POST["namHoc"],
            "tbd" => $_POST["tbd"],
            "tkt" => $_POST["tkt"],
            "maPhong" => $_POST["maPhong"]
        ];

        // Kiểm tra tuần đã khóa
        if ($model->isWeekLocked($data["tbd"])) {
            $_SESSION["error"] = "Tuần đã khóa, không thể thay đổi lịch học!";
            header("Location: index.php?controller=scheduleAdmin&action=index&lop=".$data["maLop"]);
            exit;
        }

        // Kiểm tra số tiết/tuần
        $hourCheck = $model->checkWeeklyHours($data["maLop"], $data["maMon"], $data["tbd"], $data["tkt"]);
        if($hourCheck['vuot'] && $_POST["is_edit"] == 0){
            $_SESSION["error"] = "Môn học đã vượt quá số tiết quy định (" . $hourCheck['toiDa'] . " tiết/tuần)!";
            header("Location: index.php?controller=scheduleAdmin&action=index&lop=".$data["maLop"]);
            exit;
        }

        // Kiểm tra xung đột
        $ignoreId = ($_POST["is_edit"] == 1) ? $_POST["maTKB"] : null;
        $conflicts = $model->checkAdvancedConflict(
            $data["maLop"], $data["maGV"], $data["maPhong"], 
            $data["thu"], $data["tiet"], $data["tbd"], $data["tkt"], 
            $ignoreId
        );

        if(!empty($conflicts)){
            $conflictMessages = [];
            foreach($conflicts as $conflict){
                if(strpos($conflict, 'Giáo viên') !== false) {
                    $conflictMessages[] = "Giáo viên bận";
                } elseif(strpos($conflict, 'Phòng học') !== false) {
                    $conflictMessages[] = "Phòng học trùng";
                } elseif(strpos($conflict, 'Lớp học') !== false) {
                    $conflictMessages[] = "Lớp học trùng";
                }
            }
            $_SESSION["error"] = "Xung đột lịch học: " . implode(", ", array_unique($conflictMessages));
            header("Location: index.php?controller=scheduleAdmin&action=index&lop=".$data["maLop"]);
            exit;
        }

        // Thực hiện lưu
        if ($_POST["is_edit"] == 1){
            $success = $model->update($_POST["maTKB"], $data);
            if($success){
                $_SESSION["message"] = "Cập nhật lịch học thành công!";
            } else {
                $_SESSION["error"] = "Cập nhật lịch học thất bại!";
            }
        } else {
            $success = $model->add($data);
            if($success){
                $_SESSION["message"] = "Thêm lịch học thành công!";
            } else {
                $_SESSION["error"] = "Thêm lịch học thất bại!";
            }
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&lop=".$data["maLop"]);
    }

    /* ===============================================================
       DELETE
    =============================================================== */
    public function delete(){
        $this->requireAdmin();

        if (!empty($_GET["id"])){
            $model = new Schedule($this->db);
            
            // Lấy thông tin lịch học trước khi xóa để kiểm tra tuần khóa
            $stmt = $this->db->prepare("SELECT tuanBatDau FROM THOIKHOABIEU WHERE maTKB = ?");
            $stmt->execute([$_GET["id"]]);
            $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($schedule && $model->isWeekLocked($schedule['tuanBatDau'])){
                $_SESSION["error"] = "Tuần đã khóa, không thể xóa lịch học!";
            } else {
                $success = $model->delete($_GET["id"]);
                if($success){
                    $_SESSION["message"] = "Xóa lịch học thành công!";
                } else {
                    $_SESSION["error"] = "Xóa lịch học thất bại!";
                }
            }
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php?controller=scheduleAdmin&action=index'));
    }

    /* ===============================================================
       AUTO ARRANGE (TẠO TEMPLATE)
    =============================================================== */
    public function autoArrange(){
        $this->requireAdmin();

        $maLop = $_GET["lop"];
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        $model = new Schedule($this->db);
        
        $success = $model->autoArrange($maLop, $hocKy, $namHoc);
        if($success){
            $_SESSION["message"] = "Tạo lịch mẫu thành công! Lịch sẽ được áp dụng cho cả học kỳ.";
        } else {
            $_SESSION["error"] = "Tạo lịch mẫu thất bại!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&lop=$maLop");
    }

    /* ===============================================================
       ÁP DỤNG TEMPLATE CHO TUẦN
    =============================================================== */
    public function applyTemplate(){
        $this->requireAdmin();

        $maLop = $_GET["lop"];
        $week = $_GET["tuan"] ?? date("Y-\WW");
        $hocKy = $_GET["hocKy"] ?? 1;
        $namHoc = $_GET["namHoc"] ?? "2025-2026";

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Kiểm tra tuần khóa
        if ($model->isWeekLocked($tbd)) {
            $_SESSION["error"] = "Tuần đã khóa, không thể áp dụng lịch mẫu!";
            header("Location: index.php?controller=scheduleAdmin&action=index&lop=$maLop&tuan=$week");
            exit;
        }

        $success = $model->applyTemplateToWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
        if($success){
            $_SESSION["message"] = "Đã áp dụng lịch mẫu cho tuần này!";
        } else {
            $_SESSION["error"] = "Chưa có lịch mẫu để áp dụng!";
        }

        header("Location: index.php?controller=scheduleAdmin&action=index&lop=$maLop&tuan=$week");
    }

    /* ===============================================================
       XÓA LỊCH TUẦN (trở về dùng template)
    =============================================================== */
    public function resetWeek(){
        $this->requireAdmin();

        $maLop = $_GET["lop"];
        $week = $_GET["tuan"] ?? date("Y-\WW");

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        // Kiểm tra tuần khóa
        if ($model->isWeekLocked($tbd)) {
            $_SESSION["error"] = "Tuần đã khóa, không thể xóa lịch!";
            header("Location: index.php?controller=scheduleAdmin&action=index&lop=$maLop&tuan=$week");
            exit;
        }

        $model->deleteWeekSchedule($maLop, $tbd, $tkt);
        $_SESSION["message"] = "Đã xóa lịch tuần này! Hệ thống sẽ hiển thị lịch mẫu.";

        header("Location: index.php?controller=scheduleAdmin&action=index&lop=$maLop&tuan=$week");
    }

    /* ===============================================================
       GET SUGGESTED SLOTS (AJAX)
    =============================================================== */
    public function getSuggestedSlots(){
        $this->requireAdmin();

        $maLop = $_GET['lop'];
        $maMon = $_GET['mon'];
        $week = $_GET['tuan'] ?? date("Y-\WW");

        $model = new Schedule($this->db);
        list($tbd, $tkt) = $model->getWeekRange($week);

        $suggestions = $model->getSuggestedSlots($maLop, $maMon, $tbd, $tkt);

        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit;
    }

    /* ===============================================================
       CHECK CONFLICT (AJAX)
    =============================================================== */
    public function checkConflict(){
        $this->requireAdmin();
        
        $maLop = $_GET['lop'];
        $thu = $_GET['thu'];
        $tiet = $_GET['tiet'];
        $maGV = $_GET['gv'];
        $maPhong = $_GET['phong'];
        $tbd = $_GET['tbd'];
        $tkt = $_GET['tkt'];
        
        $model = new Schedule($this->db);
        $conflicts = $model->checkAdvancedConflict($maLop, $maGV, $maPhong, $thu, $tiet, $tbd, $tkt);
        
        header('Content-Type: application/json');
        echo json_encode($conflicts);
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
}
?>