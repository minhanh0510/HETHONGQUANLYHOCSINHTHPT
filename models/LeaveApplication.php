<?php
// models/LeaveApplication.php

class LeaveApplication {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy học sinh mà phụ huynh quản lý
     */
    public function getStudentByParent($parentId) {
        try {
            $sql = "SELECT hs.maHS, nd.hoVaTen, hs.maLop 
                    FROM hocsinh hs
                    JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    JOIN quanhechild qh ON hs.maHS = qh.maHS
                    WHERE qh.maPH = ?
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$parentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result : null;
            
        } catch (Exception $e) {
            error_log("Error getting student by parent: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy giáo viên chủ nhiệm của lớp
     */
    public function getHomeroomTeacher($maLop) {
        try {
            $sql = "SELECT gv.maGV, nd.hoVaTen, nd.email
                    FROM pcgvcn pc
                    JOIN giaovien gv ON pc.maGV = gv.maGV
                    JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    WHERE pc.maLop = ?
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maLop]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting homeroom teacher: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lưu đơn xin nghỉ học
     */
    public function createLeaveApplication($data) {
        try {
            $sql = "INSERT INTO donnghihoc (maHS, lyDo, ngayBatDau, ngayKetThuc, trangThai, ngayGui) 
                    VALUES (?, ?, ?, ?, 'ChoXuLy', NOW())";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['maHS'],
                $data['lyDo'],
                $data['ngayBatDau'],
                $data['ngayKetThuc']
            ]);
            
        } catch (Exception $e) {
            error_log("Error creating leave application: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra ngày nghỉ hợp lệ
     */
    public function isValidDate($startDate, $endDate = null) {
        $today = date('Y-m-d');
        
        // Nếu chỉ có ngày bắt đầu (nghỉ 1 ngày)
        if (!$endDate) {
            return strtotime($startDate) >= strtotime($today);
        }
        
        // Kiểm tra cả ngày bắt đầu và kết thúc
        return strtotime($startDate) >= strtotime($today) && 
               strtotime($endDate) >= strtotime($startDate);
    }
    
    /**
     * Tính số ngày nghỉ
     */
    public function calculateDays($startDate, $endDate) {
        if (!$startDate || !$endDate) return 1;
        
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); // Bao gồm cả ngày kết thúc
        
        $interval = $start->diff($end);
        return $interval->days;
    }
    
    /**
     * Lấy danh sách đơn xin nghỉ của học sinh
     */
    public function getLeaveApplicationsByStudent($studentId) {
        try {
            $sql = "SELECT d.*, 
                    DATE_FORMAT(d.ngayBatDau, '%d/%m/%Y') as ngayBatDauFmt,
                    DATE_FORMAT(d.ngayKetThuc, '%d/%m/%Y') as ngayKetThucFmt,
                    DATE_FORMAT(d.ngayGui, '%d/%m/%Y %H:%i') as ngayGuiFmt,
                    DATEDIFF(d.ngayKetThuc, d.ngayBatDau) + 1 as soNgay
                    FROM donnghihoc d
                    WHERE d.maHS = ?
                    ORDER BY d.ngayBatDau DESC, d.maDon DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting leave applications: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Gửi thông báo đến giáo viên
     */
    public function sendNotificationToTeacher($teacherId, $studentName, $startDate, $endDate, $reason, $days) {
        try {
            if ($startDate == $endDate) {
                $title = "Đơn xin nghỉ học của học sinh: " . $studentName;
                $content = "Học sinh " . $studentName . " xin nghỉ học ngày " . $startDate . 
                           "\nLý do: " . $reason . 
                           "\nSố ngày nghỉ: 1 ngày";
            } else {
                $title = "Đơn xin nghỉ nhiều ngày của học sinh: " . $studentName;
                $content = "Học sinh " . $studentName . " xin nghỉ học từ ngày " . $startDate . 
                           " đến ngày " . $endDate . 
                           "\nLý do: " . $reason . 
                           "\nSố ngày nghỉ: " . $days . " ngày";
            }
            
            $sql = "INSERT INTO thongbao (tieuDe, noiDung, nguoiGui, ngayGui, doiTuong) 
                    VALUES (?, ?, ?, NOW(), 'GiaoVien')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $content, 'SYSTEM']);
            
        } catch (Exception $e) {
            error_log("Error sending notification: " . $e->getMessage());
            return false;
        }
    }
}
?>