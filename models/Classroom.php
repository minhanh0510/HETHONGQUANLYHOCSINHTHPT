<?php
// models/Classroom.php - COMPLETE FIXED VERSION
class Classroom {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    // ========== CÁC METHOD CŨ (GIỮ NGUYÊN) ==========
    
    public function getAll() {
        $sql = "SELECT maLop, tenLop, siSo, namHoc, maKhoi, maBan FROM lop ORDER BY maKhoi, tenLop";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByKhoi($maKhoi) {
        $sql = "SELECT maLop, tenLop, siSo FROM lop WHERE maKhoi = ? ORDER BY tenLop";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKhoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách lớp của giáo viên chủ nhiệm
    public function getClassesByTeacher($maGV) {
        $sql = "SELECT l.*, k.tenKhoi, b.tenBan, pc.namHoc
                FROM pcgvcn pc
                JOIN lop l ON pc.maLop = l.maLop
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                LEFT JOIN bankp b ON l.maBan = b.maBan
                WHERE pc.maGV = :maGV
                ORDER BY pc.namHoc DESC, l.maKhoi, l.tenLop";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maGV' => $maGV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra giáo viên có phải GVCN của lớp không
    public function isHomeRoomTeacher($maGV, $maLop) {
        $sql = "SELECT COUNT(*) FROM pcgvcn 
                WHERE maGV = :maGV AND maLop = :maLop";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maGV' => $maGV, ':maLop' => $maLop]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy thông tin lớp
    public function getClassInfo($maLop) {
        $sql = "SELECT l.*, k.tenKhoi, b.tenBan,
                       nd.hoVaTen as tenGVCN
                FROM lop l
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                LEFT JOIN bankp b ON l.maBan = b.maBan
                LEFT JOIN pcgvcn pc ON l.maLop = pc.maLop
                LEFT JOIN giaovien gv ON pc.maGV = gv.maGV
                LEFT JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                WHERE l.maLop = :maLop
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maLop' => $maLop]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách học sinh trong lớp
    public function getStudentsByClass($maLop) {
        $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai,
                       hs.dangThaiHocTap, hs.soBaoDanh,
                       pc.namHoc, pc.trangThai, l.tenLop
                FROM phancong pc
                JOIN hocsinh hs ON pc.maHS = hs.maHS
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN lop l ON pc.maLop = l.maLop
                WHERE pc.maLop = :maLop AND pc.trangThai = 'DangHoc'
                ORDER BY nd.hoVaTen";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maLop' => $maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết học sinh
    public function getStudentDetail($maHS) {
        $sql = "SELECT hs.*, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh,
                       nd.diaChi, nd.soDienThoai, nd.email,
                       l.tenLop, k.tenKhoi, b.tenBan
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                LEFT JOIN lop l ON pc.maLop = l.maLop
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                LEFT JOIN bankp b ON l.maBan = b.maBan
                WHERE hs.maHS = :maHS
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin phụ huynh
    public function getParentInfo($maHS) {
        $sql = "SELECT nd.hoVaTen, nd.gioiTinh, nd.soDienThoai, nd.email,
                       ph.ngheNghiep, qh.quanHe
                FROM quanhechild qh
                JOIN phuhuynh ph ON qh.maPH = ph.maPH
                JOIN nguoidung nd ON ph.maNguoiDung = nd.maNguoiDung
                WHERE qh.maHS = :maHS";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy kết quả học tập (điểm trung bình các môn)
    public function getAcademicResults($maHS) {
        $sql = "SELECT mh.tenMon, 
                       AVG(CASE WHEN d.loaiDiem = 'MiengTX' THEN d.diemSo END) as diemMieng,
                       AVG(CASE WHEN d.loaiDiem = 'Giua15Phut' THEN d.diemSo END) as diem15Phut,
                       AVG(CASE WHEN d.loaiDiem = 'MotTiet' THEN d.diemSo END) as diem1Tiet,
                       AVG(CASE WHEN d.loaiDiem = 'GiuaKy' THEN d.diemSo END) as diemGiuaKy,
                       AVG(CASE WHEN d.loaiDiem = 'CuoiKy' THEN d.diemSo END) as diemCuoiKy,
                       d.hocKy, d.namHoc
                FROM diem d
                JOIN monhoc mh ON d.maMon = mh.maMon
                WHERE d.maHS = :maHS
                GROUP BY mh.tenMon, d.hocKy, d.namHoc
                ORDER BY d.namHoc DESC, d.hocKy DESC, mh.tenMon";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy hạnh kiểm
    public function getConductInfo($maHS) {
        $sql = "SELECT xepLoai, hocKy, namHoc
                FROM hanhkiem
                WHERE maHS = :maHS
                ORDER BY namHoc DESC, hocKy DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin học sinh (đơn giản)
    public function getStudentInfo($maHS) {
        $sql = "SELECT hs.maHS, nd.hoVaTen, hs.soBaoDanh
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.maHS = :maHS
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả môn học
    public function getAllSubjects() {
        $sql = "SELECT maMon, tenMon, soTiet FROM monhoc ORDER BY tenMon";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy điểm theo môn học
    public function getScoresBySubject($maHS, $maMon) {
        $sql = "SELECT d.*, mh.tenMon
                FROM diem d
                JOIN monhoc mh ON d.maMon = mh.maMon
                WHERE d.maHS = :maHS AND d.maMon = :maMon
                ORDER BY d.namHoc DESC, d.hocKy DESC, d.loaiDiem";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS, ':maMon' => $maMon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch sử điểm danh (nghỉ học)
    public function getAttendanceHistory($maHS) {
        $sql = "SELECT maDon, lyDo, ngayNghi, trangThai, ngayGui, ngayXuLy
                FROM donnghihoc
                WHERE maHS = :maHS
                ORDER BY ngayNghi DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thống kê điểm danh
    public function getAttendanceStats($maHS) {
        $sql = "SELECT 
                    COUNT(*) as tongSoNgayNghi,
                    SUM(CASE WHEN trangThai = 'DaDuyet' THEN 1 ELSE 0 END) as nghiCoPhep,
                    SUM(CASE WHEN trangThai = 'TuChoi' THEN 1 ELSE 0 END) as nghiKhongPhep,
                    SUM(CASE WHEN trangThai = 'ChoXuLy' THEN 1 ELSE 0 END) as choXuLy
                FROM donnghihoc
                WHERE maHS = :maHS";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maHS' => $maHS]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Đảm bảo không có giá trị null
        return [
            'tongSoNgayNghi' => $result['tongSoNgayNghi'] ?? 0,
            'nghiCoPhep' => $result['nghiCoPhep'] ?? 0,
            'nghiKhongPhep' => $result['nghiKhongPhep'] ?? 0,
            'choXuLy' => $result['choXuLy'] ?? 0
        ];
    }

    // ========== CẬP NHẬT: XỬ LÝ ĐƠN NGHỈ HỌC VỚI ERROR HANDLING ==========
    
    /**
     * Phê duyệt đơn nghỉ học
     * @param int $maDon - Mã đơn nghỉ học
     * @return bool - True nếu thành công
     */
    public function approveLeaveRequest($maDon) {
        try {
            // Bước 1: Kiểm tra đơn có tồn tại không
            $checkSql = "SELECT maDon, trangThai, maHS FROM donnghihoc WHERE maDon = :maDon";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':maDon' => $maDon]);
            $don = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Approve - Checking leave request maDon=$maDon: " . print_r($don, true));
            
            if (!$don) {
                error_log("ERROR: Leave request not found - maDon: $maDon");
                return false;
            }
            
            // Bước 2: Kiểm tra trạng thái hiện tại
            if ($don['trangThai'] !== 'ChoXuLy') {
                error_log("ERROR: Leave request already processed - Current status: " . $don['trangThai']);
                return false;
            }
            
            // Bước 3: Thực hiện phê duyệt
            $sql = "UPDATE donnghihoc 
                    SET trangThai = 'DaDuyet',
                        ngayXuLy = NOW()
                    WHERE maDon = :maDon AND trangThai = 'ChoXuLy'";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':maDon' => $maDon]);
            $rowCount = $stmt->rowCount();
            
            error_log("Approve result - Success: " . ($result ? 'YES' : 'NO') . ", Rows affected: $rowCount");
            
            return $result && $rowCount > 0;
            
        } catch (PDOException $e) {
            error_log("PDO Error in approveLeaveRequest: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            error_log("General Error in approveLeaveRequest: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Từ chối đơn nghỉ học
     * @param int $maDon - Mã đơn nghỉ học
     * @return bool - True nếu thành công
     */
    public function rejectLeaveRequest($maDon) {
        try {
            // Bước 1: Kiểm tra đơn có tồn tại không
            $checkSql = "SELECT maDon, trangThai, maHS FROM donnghihoc WHERE maDon = :maDon";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':maDon' => $maDon]);
            $don = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Reject - Checking leave request maDon=$maDon: " . print_r($don, true));
            
            if (!$don) {
                error_log("ERROR: Leave request not found - maDon: $maDon");
                return false;
            }
            
            // Bước 2: Kiểm tra trạng thái hiện tại
            if ($don['trangThai'] !== 'ChoXuLy') {
                error_log("ERROR: Leave request already processed - Current status: " . $don['trangThai']);
                return false;
            }
            
            // Bước 3: Thực hiện từ chối
            $sql = "UPDATE donnghihoc 
                    SET trangThai = 'TuChoi',
                        ngayXuLy = NOW()
                    WHERE maDon = :maDon AND trangThai = 'ChoXuLy'";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':maDon' => $maDon]);
            $rowCount = $stmt->rowCount();
            
            error_log("Reject result - Success: " . ($result ? 'YES' : 'NO') . ", Rows affected: $rowCount");
            
            return $result && $rowCount > 0;
            
        } catch (PDOException $e) {
            error_log("PDO Error in rejectLeaveRequest: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            error_log("General Error in rejectLeaveRequest: " . $e->getMessage());
            return false;
        }
    }

    // ========== METHODS CHO STUDENT LIST ==========

    // Lấy danh sách tất cả khối
    public function getAllKhoi() {
        $sql = "SELECT maKhoi, tenKhoi FROM khoi ORDER BY maKhoi";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm học sinh (cho giáo viên chủ nhiệm)
    public function searchStudents($maGV, $maLop = '', $maKhoi = '', $gioiTinh = '', $keyword = '') {
        $sql = "SELECT DISTINCT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai,
                       hs.soBaoDanh, hs.dangThaiHocTap,
                       l.tenLop, k.tenKhoi, b.tenBan
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop l ON pc.maLop = l.maLop
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                LEFT JOIN bankp b ON l.maBan = b.maBan
                WHERE pc.maLop IN (
                    SELECT maLop FROM pcgvcn WHERE maGV = :maGV
                )";
        
        $params = [':maGV' => $maGV];
        
        if (!empty($maLop)) {
            $sql .= " AND l.maLop = :maLop";
            $params[':maLop'] = $maLop;
        }
        
        if (!empty($maKhoi)) {
            $sql .= " AND l.maKhoi = :maKhoi";
            $params[':maKhoi'] = $maKhoi;
        }
        
        if (!empty($gioiTinh)) {
            $sql .= " AND nd.gioiTinh = :gioiTinh";
            $params[':gioiTinh'] = $gioiTinh;
        }
        
        if (!empty($keyword)) {
            $sql .= " AND (nd.hoVaTen LIKE :keyword OR hs.maHS LIKE :keyword OR hs.soBaoDanh LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        $sql .= " ORDER BY l.tenLop, nd.hoVaTen";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm học sinh trong 1 lớp cụ thể
    public function searchStudentsInClass($maLop, $gioiTinh = '', $keyword = '') {
        $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai,
                       hs.soBaoDanh, hs.dangThaiHocTap,
                       l.tenLop, k.tenKhoi
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop l ON pc.maLop = l.maLop
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                WHERE pc.maLop = :maLop";
        
        $params = [':maLop' => $maLop];
        
        if (!empty($gioiTinh)) {
            $sql .= " AND nd.gioiTinh = :gioiTinh";
            $params[':gioiTinh'] = $gioiTinh;
        }
        
        if (!empty($keyword)) {
            $sql .= " AND (nd.hoVaTen LIKE :keyword OR hs.maHS LIKE :keyword OR hs.soBaoDanh LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        $sql .= " ORDER BY nd.hoVaTen";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== METHODS CHO LỊCH DẠY ==========

    // Lấy lịch dạy của giáo viên
    public function getTeachingSchedule($maGV, $ngay = null) {
        $sql = "SELECT tkb.*, 
                       l.tenLop, 
                       mh.tenMon, 
                       ph.tenPhong,
                       k.tenKhoi
                FROM thoikhoabieu tkb
                JOIN lop l ON tkb.maLop = l.maLop
                JOIN monhoc mh ON tkb.maMon = mh.maMon
                LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                LEFT JOIN khoi k ON l.maKhoi = k.maKhoi
                WHERE tkb.maGV = :maGV";
        
        $params = [':maGV' => $maGV];
        
        if (!empty($ngay)) {
            $sql .= " AND tkb.ngay = :ngay";
            $params[':ngay'] = $ngay;
        } else {
            $sql .= " AND tkb.ngay >= CURDATE() AND tkb.ngay <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        }
        
        $sql .= " ORDER BY tkb.ngay, tkb.tiet";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thống kê lịch dạy
    public function getScheduleStats($maGV) {
        $sql = "SELECT 
                    COUNT(*) as tongTiet,
                    COUNT(DISTINCT maLop) as soLop,
                    COUNT(DISTINCT maMon) as soMon,
                    COUNT(CASE WHEN ngay >= CURDATE() THEN 1 END) as tietSapToi
                FROM thoikhoabieu
                WHERE maGV = :maGV 
                AND ngay >= CURDATE() 
                AND ngay <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maGV' => $maGV]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách môn giảng dạy
    public function getTeachingSubjects($maGV) {
        $sql = "SELECT DISTINCT mh.maMon, mh.tenMon, mh.soTiet
                FROM pcgvbm pc
                JOIN monhoc mh ON pc.maMon = mh.maMon
                WHERE pc.maGV = :maGV
                ORDER BY mh.tenMon";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maGV' => $maGV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch dạy theo tuần
    public function getWeeklySchedule($maGV, $startDate) {
        $sql = "SELECT tkb.*, 
                       l.tenLop, 
                       mh.tenMon, 
                       ph.tenPhong,
                       DAYOFWEEK(tkb.ngay) as thuTrongTuan
                FROM thoikhoabieu tkb
                JOIN lop l ON tkb.maLop = l.maLop
                JOIN monhoc mh ON tkb.maMon = mh.maMon
                LEFT JOIN phonghoc ph ON tkb.maPhong = ph.maPhong
                WHERE tkb.maGV = :maGV
                AND tkb.ngay >= :startDate
                AND tkb.ngay < DATE_ADD(:startDate, INTERVAL 7 DAY)
                ORDER BY tkb.ngay, tkb.tiet";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maGV' => $maGV, ':startDate' => $startDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== METHOD MỚI: ĐẾM ĐƠN XIN NGHỈ CHỜ DUYỆT ==========
    
    /**
     * Đếm số đơn xin nghỉ chờ duyệt của các lớp mà giáo viên chủ nhiệm
     * @param string $maGV - Mã giáo viên
     * @return int - Số lượng đơn chờ duyệt
     */
    public function countPendingLeaveRequests($maGV) {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM donnghihoc d
                    INNER JOIN hocsinh hs ON d.maHS = hs.maHS
                    INNER JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    INNER JOIN lop l ON pc.maLop = l.maLop
                    INNER JOIN pcgvcn gvcn ON l.maLop = gvcn.maLop
                    WHERE gvcn.maGV = :maGV 
                    AND d.trangThai = 'ChoXuLy'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maGV', $maGV);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Error counting pending leave requests: " . $e->getMessage());
            return 0;
        }
    }

    // ========== METHOD MỚI: LẤY ĐƠN XIN NGHỈ CỦA LỚP - THÊM VÀO ==========
    
    /**
     * Lấy danh sách đơn xin nghỉ của một lớp (JOIN để lấy tên học sinh)
     * @param string $maLop - Mã lớp
     * @return array - Danh sách đơn xin nghỉ
     */
    public function getLeaveRequestsByClass($maLop) {
        try {
            $sql = "SELECT d.maDon, d.maHS, d.lyDo, d.ngayNghi, d.trangThai, d.ngayGui, d.ngayXuLy,
                           nd.hoVaTen, hs.soBaoDanh
                    FROM donnghihoc d
                    INNER JOIN hocsinh hs ON d.maHS = hs.maHS
                    INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    INNER JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    WHERE pc.maLop = :maLop
                    ORDER BY 
                        CASE d.trangThai 
                            WHEN 'ChoXuLy' THEN 1 
                            WHEN 'DaDuyet' THEN 2 
                            WHEN 'TuChoi' THEN 3 
                        END,
                        d.ngayNghi DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':maLop' => $maLop]);
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getLeaveRequestsByClass - Found " . count($result) . " requests");
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Error getting leave requests by class: " . $e->getMessage());
            return [];
        }
    }

    // ========== METHOD MỚI: LẤY ĐIỂM DANH THEO TUẦN - THÊM VÀO ==========
    
    /**
     * Lấy điểm danh của cả tuần
     * @param string $maLop - Mã lớp
     * @param string $monday - Thứ 2 đầu tuần (Y-m-d)
     * @return array - Mảng 2 chiều [maHS][date] => status
     */
    public function getWeekAttendance($maLop, $monday) {
        try {
            error_log("=== GET WEEK ATTENDANCE DEBUG ===");
            error_log("maLop: $maLop, monday: $monday");
            
            $sql = "SELECT maHS, ngayDiemDanh, trangThai
                    FROM diemdanh
                    WHERE maLop = :maLop 
                    AND ngayDiemDanh >= :monday
                    AND ngayDiemDanh < DATE_ADD(:monday, INTERVAL 6 DAY)
                    ORDER BY ngayDiemDanh";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':maLop' => $maLop, ':monday' => $monday]);
            
            $result = [];
            $count = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $maHS = $row['maHS'];
                $ngay = $row['ngayDiemDanh'];
                $status = ($row['trangThai'] === 'CoMat') ? 'present' : 'absent';
                
                if (!isset($result[$maHS])) {
                    $result[$maHS] = [];
                }
                $result[$maHS][$ngay] = $status;
                $count++;
            }
            
            error_log("Found $count attendance records for the week");
            error_log("Week attendance data: " . print_r($result, true));
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Error getting week attendance: " . $e->getMessage());
            return [];
        }
    }

    // ========== ATTENDANCE METHODS - UPDATED ==========
    
    /**
     * Lưu điểm danh cho cả lớp - ĐÃ SỬA ĐỂ HỖ TRỢ THAM SỐ maGV
     * @param string $maLop - Mã lớp
     * @param string $date - Ngày điểm danh (Y-m-d)
     * @param array $attendance - Mảng [maHS => 'present'/'absent']
     * @param string $maGV - Mã giáo viên (optional)
     * @return bool - True nếu thành công
     */
    public function saveClassAttendance($maLop, $date, $attendance, $maGV = null) {
        try {
            error_log("=== SAVE ATTENDANCE MODEL DEBUG ===");
            error_log("maLop: $maLop, date: $date, maGV: $maGV");
            error_log("Attendance data: " . print_r($attendance, true));
            
            $this->db->beginTransaction();
            
            // Xóa điểm danh cũ của ngày này (nếu có)
            $deleteSql = "DELETE FROM diemdanh WHERE maLop = :maLop AND ngayDiemDanh = :date";
            $deleteStmt = $this->db->prepare($deleteSql);
            $deleteStmt->execute([':maLop' => $maLop, ':date' => $date]);
            error_log("Deleted old attendance records");
            
            // Thêm điểm danh mới
            $insertSql = "INSERT INTO diemdanh (maHS, maLop, ngayDiemDanh, trangThai, ghiChu, nguoiDiemDanh) 
                          VALUES (:maHS, :maLop, :date, :status, '', :nguoiDiemDanh)";
            $insertStmt = $this->db->prepare($insertSql);
            
            $count = 0;
            foreach ($attendance as $maHS => $status) {
                $trangThai = ($status === 'present') ? 'CoMat' : 'Vang';
                $insertStmt->execute([
                    ':maHS' => $maHS,
                    ':maLop' => $maLop,
                    ':date' => $date,
                    ':status' => $trangThai,
                    ':nguoiDiemDanh' => $maGV
                ]);
                $count++;
            }
            
            error_log("Inserted $count attendance records");
            
            $this->db->commit();
            error_log("Transaction committed successfully");
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("PDO Error in saveClassAttendance: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("General Error in saveClassAttendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy điểm danh của một lớp theo ngày
     * @param string $maLop - Mã lớp
     * @param string $date - Ngày điểm danh (Y-m-d)
     * @return array - Mảng điểm danh [maHS => ['trangThai', 'ghiChu']]
     */
    public function getAttendanceByDate($maLop, $date) {
        try {
            error_log("=== GET ATTENDANCE BY DATE DEBUG ===");
            error_log("maLop: $maLop, date: $date");
            
            $sql = "SELECT maHS, trangThai, ghiChu
                    FROM diemdanh
                    WHERE maLop = :maLop AND ngayDiemDanh = :date";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':maLop' => $maLop, ':date' => $date]);
            
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[$row['maHS']] = [
                    'trangThai' => $row['trangThai'],
                    'ghiChu' => $row['ghiChu']
                ];
            }
            
            error_log("Found " . count($result) . " attendance records");
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Error getting attendance: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch sử điểm danh của một học sinh
     * @param string $maHS - Mã học sinh
     * @param string $startDate - Ngày bắt đầu (optional)
     * @param string $endDate - Ngày kết thúc (optional)
     * @return array - Danh sách điểm danh
     */
    public function getStudentAttendanceHistory($maHS, $startDate = null, $endDate = null) {
        try {
            $sql = "SELECT dd.*, l.tenLop
                    FROM diemdanh dd
                    JOIN lop l ON dd.maLop = l.maLop
                    WHERE dd.maHS = :maHS";
            
            $params = [':maHS' => $maHS];
            
            if ($startDate) {
                $sql .= " AND dd.ngayDiemDanh >= :startDate";
                $params[':startDate'] = $startDate;
            }
            
            if ($endDate) {
                $sql .= " AND dd.ngayDiemDanh <= :endDate";
                $params[':endDate'] = $endDate;
            }
            
            $sql .= " ORDER BY dd.ngayDiemDanh DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting student attendance history: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thống kê điểm danh của lớp theo tháng
     * @param string $maLop - Mã lớp
     * @param string $month - Tháng (Y-m)
     * @return array - Thống kê
     */
    public function getMonthlyAttendanceStats($maLop, $month) {
        try {
            $sql = "SELECT 
                        COUNT(*) as tongNgay,
                        SUM(CASE WHEN trangThai = 'CoMat' THEN 1 ELSE 0 END) as soNgayCoMat,
                        SUM(CASE WHEN trangThai = 'Vang' THEN 1 ELSE 0 END) as soNgayVang,
                        SUM(CASE WHEN trangThai = 'DiTre' THEN 1 ELSE 0 END) as soNgayDiTre
                    FROM diemdanh
                    WHERE maLop = :maLop 
                    AND DATE_FORMAT(ngayDiemDanh, '%Y-%m') = :month";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':maLop' => $maLop, ':month' => $month]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting monthly attendance stats: " . $e->getMessage());
            return [
                'tongNgay' => 0,
                'soNgayCoMat' => 0,
                'soNgayVang' => 0,
                'soNgayDiTre' => 0
            ];
        }
    }

    /**
     * Kiểm tra xem đã điểm danh cho ngày này chưa
     * @param string $maLop - Mã lớp
     * @param string $date - Ngày cần kiểm tra
     * @return bool - True nếu đã điểm danh
     */
    public function hasAttendanceForDate($maLop, $date) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM diemdanh
                    WHERE maLop = :maLop AND ngayDiemDanh = :date";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':maLop' => $maLop, ':date' => $date]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
            
        } catch (PDOException $e) {
            error_log("Error checking attendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách học sinh vắng trong một khoảng thời gian
     * @param string $maLop - Mã lớp
     * @param string $startDate - Ngày bắt đầu
     * @param string $endDate - Ngày kết thúc
     * @return array - Danh sách học sinh vắng nhiều
     */
    public function getAbsentStudents($maLop, $startDate, $endDate) {
        try {
            $sql = "SELECT hs.maHS, nd.hoVaTen,
                        COUNT(*) as soNgayVang,
                        GROUP_CONCAT(DATE_FORMAT(dd.ngayDiemDanh, '%d/%m/%Y') ORDER BY dd.ngayDiemDanh SEPARATOR ', ') as danhSachNgay
                    FROM diemdanh dd
                    JOIN hocsinh hs ON dd.maHS = hs.maHS
                    JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    WHERE dd.maLop = :maLop 
                    AND dd.trangThai = 'Vang'
                    AND dd.ngayDiemDanh BETWEEN :startDate AND :endDate
                    GROUP BY hs.maHS, nd.hoVaTen
                    ORDER BY soNgayVang DESC, nd.hoVaTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':maLop' => $maLop,
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting absent students: " . $e->getMessage());
            return [];
        }
    }

    // ========== GRADE MANAGEMENT METHODS ==========
    
    /**
     * Lấy danh sách điểm của lớp theo môn
     * @param string $maLop - Mã lớp
     * @param string $maMon - Mã môn
     * @param string $hocKy - Học kỳ
     * @param string $namHoc - Năm học
     * @return array - Danh sách điểm
     */
    public function getClassGradesBySubject($maLop, $maMon, $hocKy, $namHoc) {
        try {
            $sql = "SELECT hs.maHS, nd.hoVaTen, hs.soBaoDanh,
                        GROUP_CONCAT(
                            CASE WHEN d.loaiDiem = 'MiengTX' THEN d.diemSo END 
                            ORDER BY d.lanThu SEPARATOR ', '
                        ) as diemMieng,
                        GROUP_CONCAT(
                            CASE WHEN d.loaiDiem = 'Giua15Phut' THEN d.diemSo END 
                            ORDER BY d.lanThi SEPARATOR ', '
                        ) as diem15Phut,
                        GROUP_CONCAT(
                            CASE WHEN d.loaiDiem = 'MotTiet' THEN d.diemSo END 
                            ORDER BY d.lanThi SEPARATOR ', '
                        ) as diem1Tiet,
                        MAX(CASE WHEN d.loaiDiem = 'GiuaKy' THEN d.diemSo END) as diemGiuaKy,
                        MAX(CASE WHEN d.loaiDiem = 'CuoiKy' THEN d.diemSo END) as diemCuoiKy
                    FROM hocsinh hs
                    JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    LEFT JOIN diem d ON hs.maHS = d.maHS 
                        AND d.maMon = :maMon 
                        AND d.hocKy = :hocKy 
                        AND d.namHoc = :namHoc
                    WHERE pc.maLop = :maLop
                    GROUP BY hs.maHS, nd.hoVaTen, hs.soBaoDanh
                    ORDER BY nd.hoVaTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':maLop' => $maLop,
                ':maMon' => $maMon,
                ':hocKy' => $hocKy,
                ':namHoc' => $namHoc
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting class grades: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Kiểm tra giáo viên có quyền nhập điểm môn này không
     * @param string $maGV - Mã giáo viên
     * @param string $maLop - Mã lớp
     * @param string $maMon - Mã môn
     * @return bool - True nếu có quyền
     */
    public function canTeacherEnterGrades($maGV, $maLop, $maMon) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM pcgvbm
                    WHERE maGV = :maGV 
                    AND maLop = :maLop 
                    AND maMon = :maMon";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':maGV' => $maGV,
                ':maLop' => $maLop,
                ':maMon' => $maMon
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
            
        } catch (PDOException $e) {
            error_log("Error checking teacher permission: " . $e->getMessage());
            return false;
        }
    }

}
?>