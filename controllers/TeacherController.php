<?php
// controllers/TeacherController.php

class TeacherController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=auth&action=login");
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($_SESSION['user']['role'] !== 'teacher') {
            die("Bạn không có quyền truy cập trang này");
        }
    }
    
    /**
     * Hiển thị trang Dashboard của giáo viên
     */
    public function dashboard() {
        // Lấy thông tin giáo viên từ session
        $user = $_SESSION['user'];
        
        // DEBUG: Kiểm tra thông tin user
        error_log("User info: " . print_r($user, true));
        
        // Tìm maGV từ nhiều nguồn có thể
        $maGV = null;
        if (isset($user['maGV'])) {
            $maGV = $user['maGV'];
        } elseif (isset($user['maNguoiDung'])) {
            // Tìm maGV từ maNguoiDung
            $stmt = $this->pdo->prepare("SELECT maGV FROM giaovien WHERE maNguoiDung = ?");
            $stmt->execute([$user['maNguoiDung']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $maGV = $result['maGV'] ?? null;
        }
        
        error_log("maGV: " . $maGV);
        
        if (!$maGV) {
            die("Không tìm thấy mã giáo viên. Debug info: " . print_r($user, true));
        }
        
        // Lấy dữ liệu thống kê
        $stats = $this->getTeacherStats($maGV);
        error_log("Stats: " . print_r($stats, true));
        
        // Lấy lịch giảng dạy hôm nay
        $scheduleToday = $this->getScheduleToday($maGV);
        error_log("Schedule count: " . count($scheduleToday));
        
        // Lấy lớp chủ nhiệm
        $homeroom = $this->getHomeroomClass($maGV);
        error_log("Homeroom: " . print_r($homeroom, true));
        
        // Lấy thông báo mới
        $notifications = $this->getRecentNotifications(5);
        error_log("Notifications count: " . count($notifications));
        
        // Lấy bài tập cần chấm
        $pendingAssignments = $this->getPendingAssignments($maGV);
        error_log("Pending assignments count: " . count($pendingAssignments));
        
        // Hiển thị view
        include "views/teacher/dashboard.php";
    }
    
    /**
     * Lấy thống kê cho giáo viên
     */
    private function getTeacherStats($maGV) {
        try {
            $stats = [
                'soLopGiangDay' => 0,
                'tongHocSinh' => 0,
                'soBaiKiemTra' => 0,
                'baiTapChuaCham' => 0
            ];
            
            // Đếm số lớp giảng dạy
            try {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(DISTINCT maLop) as soLop 
                    FROM pcgvbm 
                    WHERE maGV = ?
                ");
                $stmt->execute([$maGV]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['soLopGiangDay'] = $result['soLop'] ?? 0;
                error_log("soLopGiangDay query result: " . print_r($result, true));
            } catch (Exception $e) {
                error_log("Error counting classes: " . $e->getMessage());
            }
            
            // Đếm tổng số học sinh
            try {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(DISTINCT hs.maHS) as tongHS
                    FROM hocsinh hs
                    INNER JOIN lop l ON hs.maLop = l.maLop
                    INNER JOIN pcgvbm pc ON l.maLop = pc.maLop
                    WHERE pc.maGV = ?
                ");
                $stmt->execute([$maGV]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['tongHocSinh'] = $result['tongHS'] ?? 0;
                error_log("tongHocSinh query result: " . print_r($result, true));
            } catch (Exception $e) {
                error_log("Error counting students: " . $e->getMessage());
            }
            
            // Đếm số bài kiểm tra
            try {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) as soBai 
                    FROM baitap 
                    WHERE maGV = ?
                ");
                $stmt->execute([$maGV]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['soBaiKiemTra'] = $result['soBai'] ?? 0;
                error_log("soBaiKiemTra query result: " . print_r($result, true));
            } catch (Exception $e) {
                error_log("Error counting tests: " . $e->getMessage());
            }
            
            // Đếm bài tập chưa chấm
            try {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) as soBai 
                    FROM baocaothongke bctk
                    INNER JOIN baitap bt ON bctk.maBaiTap = bt.maBaiTap
                    WHERE bt.maGV = ? AND bctk.trangThai != 'DaCham'
                ");
                $stmt->execute([$maGV]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['baiTapChuaCham'] = $result['soBai'] ?? 0;
                error_log("baiTapChuaCham query result: " . print_r($result, true));
            } catch (Exception $e) {
                error_log("Error counting pending assignments: " . $e->getMessage());
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error in getTeacherStats: " . $e->getMessage());
            return [
                'soLopGiangDay' => 0,
                'tongHocSinh' => 0,
                'soBaiKiemTra' => 0,
                'baiTapChuaCham' => 0
            ];
        }
    }
    
    /**
     * Lấy lịch giảng dạy hôm nay
     */
    private function getScheduleToday($maGV) {
        try {
            $currentDate = date('Y-m-d');
            $thu = date('N') + 1; // 2=Monday...7=Saturday
            if ($thu == 8) $thu = 1; // Sunday
            
            error_log("Checking schedule for maGV=$maGV, thu=$thu, date=$currentDate");
            
            $stmt = $this->pdo->prepare("
                SELECT 
                    tkb.tiet,
                    mh.tenMon,
                    l.tenLop,
                    ph.tenPhong
                FROM thoikhoabieu tkb
                INNER JOIN monhoc mh ON tkb.maMon = mh.maMon
                INNER JOIN lop l ON tkb.maLop = l.maLop
                LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                WHERE tkb.maGV = ? 
                AND tkb.thu = ?
                AND ? BETWEEN tkb.tuanBatDau AND tkb.tuanKetThuc
                ORDER BY tkb.tiet
            ");
            $stmt->execute([$maGV, $thu, $currentDate]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Schedule result: " . print_r($result, true));
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in getScheduleToday: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin lớp chủ nhiệm
     */
    private function getHomeroomClass($maGV) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    l.maLop,
                    l.tenLop,
                    l.siSo,
                    l.namHoc,
                    k.tenKhoi
                FROM pcgvcn cn
                INNER JOIN lop l ON cn.maLop = l.maLop
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                WHERE cn.maGV = ?
                ORDER BY cn.namHoc DESC
                LIMIT 1
            ");
            $stmt->execute([$maGV]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getHomeroomClass: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy thông báo gần đây
     */
    private function getRecentNotifications($limit = 5) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    maThongBao,
                    tieuDe,
                    noiDung,
                    ngayGui
                FROM thongbao
                ORDER BY ngayGui DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getRecentNotifications: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy bài tập cần chấm
     */
    private function getPendingAssignments($maGV) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    bt.maBaiTap,
                    bt.tenBaiTap,
                    bt.thoiHanNop,
                    mh.tenMon,
                    l.tenLop,
                    COUNT(bctk.maBaoCao) as soLuongChuaCham
                FROM baitap bt
                INNER JOIN monhoc mh ON bt.maMon = mh.maMon
                INNER JOIN lop l ON bt.maLop = l.maLop
                LEFT JOIN baocaothongke bctk ON bt.maBaiTap = bctk.maBaiTap 
                    AND bctk.trangThai != 'DaCham'
                WHERE bt.maGV = ?
                GROUP BY bt.maBaiTap
                HAVING soLuongChuaCham > 0
                ORDER BY bt.thoiHanNop ASC
                LIMIT 10
            ");
            $stmt->execute([$maGV]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getPendingAssignments: " . $e->getMessage());
            return [];
        }
    }
    
    public function profile() {
        $user = $_SESSION['user'];
        include "views/teacher/profile.php";
    }
    
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['message'] = [
                'type' => 'success',
                'content' => 'Cập nhật thông tin thành công!'
            ];
            header("Location: /PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=teacher&action=profile");
            exit;
        }
    }
}
?>