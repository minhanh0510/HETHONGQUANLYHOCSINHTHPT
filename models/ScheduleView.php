<?php
// models/ScheduleView.php

class ScheduleView {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy lớp của học sinh - từ bảng hocsinh trực tiếp
     */
    public function getStudentClass($studentId) {
        try {
            $sql = "SELECT maLop FROM hocsinh WHERE maHS = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$studentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['maLop'] : null;
            
        } catch (Exception $e) {
            error_log("Error getting student class: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy thời khóa biểu của lớp theo tuần
     * Ưu tiên bảng THOIKHOABIEU_TUAN (lịch riêng tuần) trước, nếu không có thì dùng THOIKHOABIEU (mẫu học kỳ)
     */
    public function getClassSchedule($maLop, $weekStart, $weekEnd, $hocKy = 1, $namHoc = '2025-2026') {
        try {
            // 1. Kiểm tra có lịch riêng cho tuần này không (THOIKHOABIEU_TUAN)
            $sqlTuan = "SELECT 
                            tkb.thu,
                            tkb.tiet,
                            mh.tenMon,
                            nd.hoVaTen as tenGV,
                            ph.tenPhong,
                            tkb.maMon,
                            tkb.maGV
                        FROM thoikhoabieu_tuan tkb
                        JOIN monhoc mh ON tkb.maMon = mh.maMon
                        JOIN giaovien gv ON tkb.maGV = gv.maGV
                        JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                        LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                        WHERE tkb.maLop = ?
                        AND tkb.hocKy = ?
                        AND tkb.namHoc = ?
                        AND tkb.tuanBatDau = ?
                        ORDER BY tkb.thu, tkb.tiet";
            
            $stmt = $this->db->prepare($sqlTuan);
            $stmt->execute([$maLop, $hocKy, $namHoc, $weekStart]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Nếu có lịch riêng tuần → trả về
            if (!empty($result)) {
                return $result;
            }
            
            // 2. Nếu không có lịch riêng → lấy từ mẫu học kỳ (THOIKHOABIEU)
            $sql = "SELECT 
                        tkb.thu,
                        tkb.tiet,
                        mh.tenMon,
                        nd.hoVaTen as tenGV,
                        ph.tenPhong,
                        tkb.maMon,
                        tkb.maGV
                    FROM thoikhoabieu tkb
                    JOIN monhoc mh ON tkb.maMon = mh.maMon
                    JOIN giaovien gv ON tkb.maGV = gv.maGV
                    JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                    WHERE tkb.maLop = ?
                    AND tkb.hocKy = ?
                    AND tkb.namHoc = ?
                    ORDER BY tkb.thu, tkb.tiet";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maLop, $hocKy, $namHoc]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting class schedule: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Chuyển đổi tuần (YYYY-WW) thành ngày bắt đầu/kết thúc
     */
    public function getWeekDates($weekString) {
        // Nếu không có tuần hoặc định dạng sai, lấy tuần hiện tại
        if (!$weekString || $weekString === 'YYYY-\WW' || empty($weekString)) {
            $weekString = date('Y-\WW');
        }
        
        try {
            // Chuẩn hóa chuỗi đầu vào - bỏ backslash
            $weekString = str_replace('\\', '', $weekString);
            
            // Tách năm và tuần từ format YYYY-Www hoặc YYYYWww
            if (preg_match('/^(\d{4})-?W(\d{1,2})$/i', $weekString, $matches)) {
                $year = (int)$matches[1];
                $week = (int)$matches[2];
            } else {
                // Fallback: dùng tuần hiện tại
                $year = (int)date('Y');
                $week = (int)date('W');
            }
            
            // Đảm bảo week có 2 chữ số
            $weekStr = str_pad($week, 2, '0', STR_PAD_LEFT);
            
            // Tính thứ 2 đầu tuần bằng DateTime
            $dto = new DateTime();
            $dto->setISODate($year, $week, 1); // 1 = Monday
            $monday = $dto->format('Y-m-d');
            
            $dto->setISODate($year, $week, 7); // 7 = Sunday
            $sunday = $dto->format('Y-m-d');
            
            return [$monday, $sunday];
            
        } catch (Exception $e) {
            // Fallback an toàn
            $monday = date('Y-m-d', strtotime('monday this week'));
            $sunday = date('Y-m-d', strtotime('sunday this week'));
            
            return [$monday, $sunday];
        }
    }
    
    /**
     * Lấy TKB theo ngày cụ thể
     */
    public function getDailySchedule($maLop, $date) {
        try {
            // Tìm thứ của ngày (1=Monday, 7=Sunday)
            $dayOfWeek = date('N', strtotime($date));
            $thu = $dayOfWeek + 1; // Chuyển sang hệ thống của VN: 2=Thứ 2, 7=Thứ 7
            
            // Tìm tuần chứa ngày này
            $monday = date('Y-m-d', strtotime('monday this week', strtotime($date)));
            $sunday = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
            
            // Lấy TKB của tuần
            $weeklySchedule = $this->getClassSchedule($maLop, $monday, $sunday);
            
            // Lọc theo thứ
            $dailySchedule = [];
            foreach ($weeklySchedule as $item) {
                if ($item['thu'] == $thu) {
                    $dailySchedule[] = $item;
                }
            }
            
            // Sắp xếp theo tiết
            usort($dailySchedule, function($a, $b) {
                return $a['tiet'] <=> $b['tiet'];
            });
            
            return $dailySchedule;
            
        } catch (Exception $e) {
            error_log("Error getting daily schedule: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Kiểm tra TKB có tồn tại cho tuần (kiểm tra cả lịch tuần và mẫu học kỳ)
     */
    public function checkScheduleExists($maLop, $weekStart, $weekEnd, $hocKy = 1, $namHoc = '2025-2026') {
        try {
            // 1. Kiểm tra lịch tuần riêng trong THOIKHOABIEU_TUAN
            $sqlTuan = "SELECT COUNT(*) as count 
                        FROM thoikhoabieu_tuan 
                        WHERE maLop = ? 
                        AND tuanBatDau = ?";
            
            $stmt = $this->db->prepare($sqlTuan);
            $stmt->execute([$maLop, $weekStart]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result['count'] > 0) {
                return true;
            }
            
            // 2. Không có lịch tuần → kiểm tra mẫu học kỳ THOIKHOABIEU
            $sqlHK = "SELECT COUNT(*) as count 
                      FROM thoikhoabieu 
                      WHERE maLop = ? 
                      AND hocKy = ? 
                      AND namHoc = ?";
            
            $stmtHK = $this->db->prepare($sqlHK);
            $stmtHK->execute([$maLop, $hocKy, $namHoc]);
            $resultHK = $stmtHK->fetch(PDO::FETCH_ASSOC);
            
            return $resultHK['count'] > 0;
            
        } catch (Exception $e) {
            error_log("Error checking schedule: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tuần hiện tại dạng YYYY-WW
     */
    public function getCurrentWeek() {
        return date('Y-\WW');
    }

    /**
     * Lấy học sinh mà phụ huynh quản lý
     */
    public function getStudentByParent($parentId) {
        try {
            // SỬA LẠI: JOIN đúng bảng quanhechild thay vì phuhuynh_hocsinh
            $sql = "SELECT hs.maHS, nd.hoVaTen, hs.maLop 
                    FROM hocsinh hs
                    JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    JOIN quanhechild qh ON hs.maHS = qh.maHS
                    WHERE qh.maPH = ?
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$parentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting student by parent: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy lịch dạy của giáo viên theo tuần
     * Ưu tiên THOIKHOABIEU_TUAN, fallback THOIKHOABIEU
     */
    public function getTeacherSchedule($teacherId, $weekStart, $weekEnd, $hocKy = 1, $namHoc = '2025-2026') {
        try {
            // 1. Kiểm tra lịch tuần riêng THOIKHOABIEU_TUAN
            $sqlTuan = "SELECT 
                            tkb.thu,
                            tkb.tiet,
                            mh.tenMon,
                            l.maLop,
                            l.tenLop,
                            ph.tenPhong,
                            tkb.maMon
                        FROM thoikhoabieu_tuan tkb
                        JOIN monhoc mh ON tkb.maMon = mh.maMon
                        JOIN lop l ON tkb.maLop = l.maLop
                        LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                        WHERE tkb.maGV = ?
                        AND tkb.tuanBatDau = ?
                        ORDER BY tkb.thu, tkb.tiet";
            
            $stmt = $this->db->prepare($sqlTuan);
            $stmt->execute([$teacherId, $weekStart]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(!empty($result)) {
                return $result;
            }
            
            // 2. Fallback: lấy từ mẫu học kỳ THOIKHOABIEU
            $sql = "SELECT 
                        tkb.thu,
                        tkb.tiet,
                        mh.tenMon,
                        l.maLop,
                        l.tenLop,
                        ph.tenPhong,
                        tkb.maMon
                    FROM thoikhoabieu tkb
                    JOIN monhoc mh ON tkb.maMon = mh.maMon
                    JOIN lop l ON tkb.maLop = l.maLop
                    LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                    WHERE tkb.maGV = ?
                    AND tkb.hocKy = ?
                    AND tkb.namHoc = ?
                    ORDER BY tkb.thu, tkb.tiet";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$teacherId, $hocKy, $namHoc]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting teacher schedule: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy lịch dạy của giáo viên theo ngày
     */
    public function getTeacherDailySchedule($teacherId, $date, $hocKy = 1, $namHoc = '2025-2026') {
        try {
            $dayOfWeek = date('N', strtotime($date));
            $thu = $dayOfWeek + 1;
            
            $monday = date('Y-m-d', strtotime('monday this week', strtotime($date)));
            
            // 1. Kiểm tra lịch tuần riêng THOIKHOABIEU_TUAN
            $sqlTuan = "SELECT 
                            tkb.thu,
                            tkb.tiet,
                            mh.tenMon,
                            l.maLop,
                            l.tenLop,
                            ph.tenPhong
                        FROM thoikhoabieu_tuan tkb
                        JOIN monhoc mh ON tkb.maMon = mh.maMon
                        JOIN lop l ON tkb.maLop = l.maLop
                        LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                        WHERE tkb.maGV = ?
                        AND tkb.tuanBatDau = ?
                        AND tkb.thu = ?
                        ORDER BY tkb.tiet";
            
            $stmt = $this->db->prepare($sqlTuan);
            $stmt->execute([$teacherId, $monday, $thu]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(!empty($result)) {
                return $result;
            }
            
            // 2. Fallback: lấy từ mẫu học kỳ THOIKHOABIEU
            $sql = "SELECT 
                        tkb.thu,
                        tkb.tiet,
                        mh.tenMon,
                        l.maLop,
                        l.tenLop,
                        ph.tenPhong
                    FROM thoikhoabieu tkb
                    JOIN monhoc mh ON tkb.maMon = mh.maMon
                    JOIN lop l ON tkb.maLop = l.maLop
                    LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                    WHERE tkb.maGV = ?
                    AND tkb.hocKy = ?
                    AND tkb.namHoc = ?
                    AND tkb.thu = ?
                    ORDER BY tkb.tiet";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$teacherId, $hocKy, $namHoc, $thu]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting teacher daily schedule: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Kiểm tra lịch dạy của giáo viên có tồn tại
     */
    public function checkTeacherScheduleExists($teacherId, $weekStart, $weekEnd, $hocKy = 1, $namHoc = '2025-2026') {
        try {
            // 1. Kiểm tra lịch tuần riêng THOIKHOABIEU_TUAN
            $sqlTuan = "SELECT COUNT(*) as count 
                        FROM thoikhoabieu_tuan 
                        WHERE maGV = ? 
                        AND tuanBatDau = ?";
            
            $stmt = $this->db->prepare($sqlTuan);
            $stmt->execute([$teacherId, $weekStart]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result['count'] > 0) {
                return true;
            }
            
            // 2. Fallback: kiểm tra mẫu học kỳ THOIKHOABIEU
            $sql = "SELECT COUNT(*) as count 
                    FROM thoikhoabieu 
                    WHERE maGV = ? 
                    AND hocKy = ?
                    AND namHoc = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$teacherId, $hocKy, $namHoc]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
            
            return $result['count'] > 0;
            
        } catch (Exception $e) {
            error_log("Error checking teacher schedule: " . $e->getMessage());
            return false;
        }
    }
}
?>