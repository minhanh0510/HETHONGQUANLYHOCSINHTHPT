<?php
class SupervisorAssignment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy thông tin năm học và học kỳ hiện tại
    public function getCurrentAcademicInfo() {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        if ($currentMonth >= 9) {
            $namHoc = $currentYear . '-' . ($currentYear + 1);
        } else {
            $namHoc = ($currentYear - 1) . '-' . $currentYear;
        }
        
        if ($currentMonth >= 9 || $currentMonth <= 1) {
            $hocKy = 1;
        } else {
            $hocKy = 2;
        }
        
        return [
            'namHoc' => $namHoc,
            'hocKy' => $hocKy
        ];
    }

    public function getCurrentExamPeriod($hocKy, $namHoc) {
        $currentDate = date('Y-m-d');
        
        $examType = '';
        if ($hocKy == 1) {
            $examType = "AND (tenKyThi LIKE '%giữa kỳ 1%' OR tenKyThi LIKE '%cuối kỳ 1%' OR tenKyThi LIKE '%Kỳ thi giữa kỳ 1%' OR tenKyThi LIKE '%Kỳ thi cuối kỳ 1%')";
        } else {
            $examType = "AND (tenKyThi LIKE '%giữa kỳ 2%' OR tenKyThi LIKE '%cuối kỳ 2%' OR tenKyThi LIKE '%Kỳ thi giữa kỳ 2%' OR tenKyThi LIKE '%Kỳ thi cuối kỳ 2%')";
        }
        
        $sql = "SELECT * FROM kythi 
                WHERE namHoc = ? 
                AND hocKy = ? 
                AND trangThai = 'DangMo'
                $examType
                ORDER BY 
                    CASE 
                        WHEN ngayThi >= ? THEN 0
                        ELSE 1
                    END,
                    ABS(DATEDIFF(ngayThi, ?)) ASC,
                    CASE caThi 
                        WHEN 'Sáng' THEN 1 
                        WHEN 'Chiều' THEN 2 
                        ELSE 3 
                    END
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$namHoc, $hocKy, $currentDate, $currentDate]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($exam) {
            return $exam['maKyThi'];
        }
        
        return null;
    }

    // Lấy tất cả ca thi từ tất cả kỳ thi trong học kỳ hiện tại
    public function getAllExamSessionsInCurrentSemester() {
        $currentDate = date('Y-m-d');
        $currentInfo = $this->getCurrentAcademicInfo();
        $hocKy = $currentInfo['hocKy'];
        $namHoc = $currentInfo['namHoc'];
        
        $sql = "SELECT DISTINCT
                    kt.maKyThi,
                    kt.tenKyThi,
                    kt.ngayThi,
                    kt.caThi,
                    (SELECT GROUP_CONCAT(DISTINCT mh.tenMon SEPARATOR ', ') 
                    FROM phongthi pt 
                    JOIN monhoc mh ON pt.maMon = mh.maMon
                    WHERE pt.maKyThi = kt.maKyThi) as mon_hoc_list,
                    (SELECT GROUP_CONCAT(DISTINCT mh.maMon SEPARATOR ',') 
                    FROM phongthi pt 
                    JOIN monhoc mh ON pt.maMon = mh.maMon
                    WHERE pt.maKyThi = kt.maKyThi) as maMon_list
                FROM kythi kt
                WHERE kt.namHoc = ? 
                AND kt.hocKy = ?
                AND kt.trangThai = 'DangMo'
                AND kt.ngayThi >= ?
                ORDER BY kt.ngayThi ASC, 
                        CASE kt.caThi 
                            WHEN 'Sáng' THEN 1 
                            WHEN 'Chiều' THEN 2 
                            ELSE 3 
                        END";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$namHoc, $hocKy, $currentDate]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as &$row) {
            $monList = [];
            
            if (!empty($row['maMon_list'])) {
                $maMonList = explode(',', $row['maMon_list']);
                
                foreach ($maMonList as $maMon) {
                    switch($maMon) {
                        case 'TOAN': $monList[] = 'Toán'; break;
                        case 'VAN': $monList[] = 'Ngữ Văn'; break;
                        case 'ANH': $monList[] = 'Tiếng Anh'; break;
                        case 'LY': $monList[] = 'Vật Lý'; break;
                        case 'HOA': $monList[] = 'Hóa Học'; break;
                        case 'SINH': $monList[] = 'Sinh Học'; break;
                        case 'SU': $monList[] = 'Lịch Sử'; break;
                        case 'DIA': $monList[] = 'Địa Lý'; break;
                        case 'GDCD': $monList[] = 'GDCD'; break;
                        default: $monList[] = $maMon;
                    }
                }
            }
            
            $row['mon_hoc_display'] = !empty($monList) ? implode(', ', $monList) : 'Các môn thi';
            $row['session_key'] = $row['ngayThi'] . '_' . $row['caThi'];
            $row['buoiThi'] = $row['caThi'];
            $row['thoiGianBatDau'] = $row['caThi'] == 'Sáng' ? '07:30:00' : '13:30:00';
            $row['thoiGianKetThuc'] = $row['caThi'] == 'Sáng' ? '09:30:00' : '15:30:00';
            
            $roomCountSql = "SELECT COUNT(*) as so_phong 
                            FROM phongthi pt
                            WHERE pt.maKyThi = ?";
            $roomStmt = $this->db->prepare($roomCountSql);
            $roomStmt->execute([$row['maKyThi']]);
            $roomCount = $roomStmt->fetch(PDO::FETCH_ASSOC);
            $row['so_phong'] = $roomCount['so_phong'] ?? 0;
        }
        
        return $results;
    }

    // Lấy tất cả phòng thi của tất cả kỳ thi trong học kỳ hiện tại
    public function getExamRoomsForAllExamsInCurrentSemester() {
        $currentDate = date('Y-m-d');
        
        $currentInfo = $this->getCurrentAcademicInfo();
        $hocKy = $currentInfo['hocKy'];
        $namHoc = $currentInfo['namHoc'];
        
        $sql = "SELECT pt.*, mh.tenMon, kt.ngayThi, kt.caThi, kt.hocKy, kt.namHoc, 
                    kt.maKyThi, kt.tenKyThi
                FROM phongthi pt 
                JOIN monhoc mh ON pt.maMon = mh.maMon
                JOIN kythi kt ON pt.maKyThi = kt.maKyThi
                WHERE kt.trangThai = 'DangMo'
                AND kt.hocKy = ? 
                AND kt.namHoc = ?
                AND kt.ngayThi >= ?
                ORDER BY kt.ngayThi ASC, 
                        CASE kt.caThi 
                            WHEN 'Sáng' THEN 1 
                            WHEN 'Chiều' THEN 2 
                            ELSE 3 
                        END,
                        CAST(SUBSTRING(pt.tenPhong, 7) AS UNSIGNED)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$hocKy, $namHoc, $currentDate]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rooms as &$room) {
            preg_match('/\d+/', $room['tenPhong'], $matches);
            if (!empty($matches)) {
                $room['tenPhong'] = 'Phòng ' . $matches[0];
            } else {
                $roomNumber = preg_replace('/[^0-9]/', '', $room['maPhong']);
                if (!empty($roomNumber)) {
                    $room['tenPhong'] = 'Phòng ' . $roomNumber;
                }
            }
            
            $room['thoiGianBatDau'] = $room['caThi'] == 'Sáng' ? '07:30:00' : '13:30:00';
            $room['thoiGianKetThuc'] = $room['caThi'] == 'Sáng' ? '09:30:00' : '15:30:00';
        }
        
        return $rooms;
    }

    // Lấy danh sách phòng thi theo ca thi
    public function getExamRoomsBySession($ngayThi, $caThi) {
        $currentDate = date('Y-m-d');
        
        if ($ngayThi < $currentDate) {
            return [];
        }
        
        $sql = "SELECT pt.*, mh.tenMon, kt.ngayThi, kt.caThi, kt.maKyThi
                FROM phongthi pt 
                JOIN monhoc mh ON pt.maMon = mh.maMon
                JOIN kythi kt ON pt.maKyThi = kt.maKyThi
                WHERE kt.ngayThi = ? 
                AND kt.caThi = ?
                AND kt.trangThai = 'DangMo'
                ORDER BY CAST(SUBSTRING(pt.tenPhong, 7) AS UNSIGNED)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ngayThi, $caThi]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rooms as &$room) {
            preg_match('/\d+/', $room['tenPhong'], $matches);
            if (!empty($matches)) {
                $room['tenPhong'] = 'Phòng ' . $matches[0];
            } else {
                $roomNumber = preg_replace('/[^0-9]/', '', $room['maPhong']);
                if (!empty($roomNumber)) {
                    $room['tenPhong'] = 'Phòng ' . $roomNumber;
                }
            }
            
            $room['thoiGianBatDau'] = $room['caThi'] == 'Sáng' ? '07:30:00' : '13:30:00';
            $room['thoiGianKetThuc'] = $room['caThi'] == 'Sáng' ? '09:30:00' : '15:30:00';
        }
        
        return $rooms;
    }

    // Lấy thông tin kỳ thi
    public function getExamInfo($maKyThi) {
        $sql = "SELECT * FROM kythi WHERE maKyThi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKyThi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết phòng thi
    public function getRoomDetails($maPhong, $maKyThi) {
        $currentDate = date('Y-m-d');
        
        $sql = "SELECT pt.*, mh.tenMon, kt.ngayThi, kt.caThi,
                       kt.hocKy, kt.namHoc, kt.maKyThi
                FROM phongthi pt
                JOIN monhoc mh ON pt.maMon = mh.maMon
                JOIN kythi kt ON pt.maKyThi = kt.maKyThi
                WHERE pt.maPhong = ? AND kt.maKyThi = ?
                AND kt.ngayThi >= ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhong, $maKyThi, $currentDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return null;
        }
        
        if ($result) {
            switch($result['maMon']) {
                case 'TOAN': $result['tenMon'] = 'Toán'; break;
                case 'VAN': $result['tenMon'] = 'Ngữ Văn'; break;
                case 'ANH': $result['tenMon'] = 'Tiếng Anh'; break;
                case 'LY': $result['tenMon'] = 'Vật Lý'; break;
                case 'HOA': $result['tenMon'] = 'Hóa Học'; break;
                case 'SINH': $result['tenMon'] = 'Sinh Học'; break;
                case 'SU': $result['tenMon'] = 'Lịch Sử'; break;
                case 'DIA': $result['tenMon'] = 'Địa Lý'; break;
                case 'GDCD': $result['tenMon'] = 'GDCD'; break;
                default: $result['tenMon'] = $result['maMon'];
            }
            
            preg_match('/\d+/', $result['tenPhong'], $matches);
            if (!empty($matches)) {
                $result['tenPhong'] = 'Phòng ' . $matches[0];
            }
            
            $result['thoiGianBatDau'] = $result['caThi'] == 'Sáng' ? '07:30:00' : '13:30:00';
            $result['thoiGianKetThuc'] = $result['caThi'] == 'Sáng' ? '09:30:00' : '15:30:00';
            $result['buoiThi'] = $result['caThi'];
        }
        
        return $result;
    }

    // Lấy danh sách giáo viên khả dụng
    public function getAvailableSupervisors($maPhong, $ngayThi, $caThi, $maKyThi) {
        $currentDate = date('Y-m-d');
        
        if ($ngayThi < $currentDate) {
            return [];
        }
        
        $roomInfo = $this->getRoomDetails($maPhong, $maKyThi);
        
        $sql = "SELECT DISTINCT gv.maGV, nd.hoVaTen, gv.monGiangDay, gv.toChuyenMon
                FROM giaovien gv
                JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                WHERE gv.maGV NOT IN (
                    SELECT pc.maGV 
                    FROM pcgiamthi pc
                    WHERE pc.ngayThi = ? 
                    AND pc.caThi = ?
                )
                ORDER BY 
                    CASE 
                        WHEN gv.monGiangDay = ? THEN 0
                        ELSE 1
                    END,
                    nd.hoVaTen";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $ngayThi, 
            $caThi,
            $roomInfo['maMon'] ?? ''
        ]);
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($teachers as &$teacher) {
            switch($teacher['monGiangDay']) {
                case 'TOAN': $teacher['monGiangDayText'] = 'Toán'; break;
                case 'VAN': $teacher['monGiangDayText'] = 'Ngữ Văn'; break;
                case 'ANH': $teacher['monGiangDayText'] = 'Tiếng Anh'; break;
                case 'LY': $teacher['monGiangDayText'] = 'Vật Lý'; break;
                case 'HOA': $teacher['monGiangDayText'] = 'Hóa Học'; break;
                case 'SINH': $teacher['monGiangDayText'] = 'Sinh Học'; break;
                case 'SU': $teacher['monGiangDayText'] = 'Lịch Sử'; break;
                case 'DIA': $teacher['monGiangDayText'] = 'Địa Lý'; break;
                case 'GDCD': $teacher['monGiangDayText'] = 'GDCD'; break;
                default: $teacher['monGiangDayText'] = $teacher['monGiangDay'];
            }
        }
        
        return $teachers;
    }

    // Kiểm tra giáo viên đã được phân công
    public function isTeacherAssigned($maGV, $ngayThi, $caThi) {
        $sql = "SELECT COUNT(*) 
                FROM pcgiamthi pc
                WHERE pc.maGV = ? 
                AND pc.ngayThi = ? 
                AND pc.caThi = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV, $ngayThi, $caThi]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy danh sách giám thị theo phòng
    public function getSupervisorsByRoomAndExam($maPhong, $maKyThi) {
        $currentDate = date('Y-m-d');
        
        $sql = "SELECT pc.*, nd.hoVaTen, gv.monGiangDay, kt.tenKyThi, kt.hocKy, kt.namHoc, 
                       kt.ngayThi, kt.caThi
                FROM pcgiamthi pc
                JOIN giaovien gv ON pc.maGV = gv.maGV
                JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                JOIN phongthi pt ON pc.maPhong = pt.maPhong
                JOIN kythi kt ON pt.maKyThi = kt.maKyThi
                WHERE pc.maPhong = ? AND kt.maKyThi = ?
                AND kt.ngayThi >= ?
                ORDER BY pc.ngayThi";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhong, $maKyThi, $currentDate]);
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($assignments as &$assignment) {
            switch($assignment['monGiangDay']) {
                case 'TOAN': $assignment['monGiangDayText'] = 'Toán'; break;
                case 'VAN': $assignment['monGiangDayText'] = 'Ngữ Văn'; break;
                case 'ANH': $assignment['monGiangDayText'] = 'Tiếng Anh'; break;
                case 'LY': $assignment['monGiangDayText'] = 'Vật Lý'; break;
                case 'HOA': $assignment['monGiangDayText'] = 'Hóa Học'; break;
                case 'SINH': $assignment['monGiangDayText'] = 'Sinh Học'; break;
                case 'SU': $assignment['monGiangDayText'] = 'Lịch Sử'; break;
                case 'DIA': $assignment['monGiangDayText'] = 'Địa Lý'; break;
                case 'GDCD': $assignment['monGiangDayText'] = 'GDCD'; break;
                default: $assignment['monGiangDayText'] = $assignment['monGiangDay'];
            }
        }
        
        return $assignments;
    }

    // Kiểm tra phòng đã đủ giám thị chưa
    public function isRoomFull($maPhong, $ngayThi, $caThi) {
        $sql = "SELECT COUNT(*) FROM pcgiamthi WHERE maPhong = ? AND ngayThi = ? AND caThi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhong, $ngayThi, $caThi]);
        return $stmt->fetchColumn() >= 2;
    }

    // Phân công nhiều giám thị cùng lúc
    public function assignMultipleSupervisors($maGVs, $maPhong, $maKyThi) {
        try {
            $this->db->beginTransaction();

            $roomInfo = $this->getRoomDetails($maPhong, $maKyThi);
            if (!$roomInfo) {
                throw new Exception("Không tìm thấy thông tin phòng thi hoặc ca thi đã qua hạn");
            }

            $ngayThi = $roomInfo['ngayThi'];
            $caThi = $roomInfo['caThi'];
            
            $currentDate = date('Y-m-d');
            if ($ngayThi < $currentDate) {
                throw new Exception("Không thể phân công cho ca thi đã qua hạn");
            }

            $currentCount = $this->countCurrentSupervisors($maPhong, $ngayThi, $caThi);
            $newCount = count($maGVs);
            
            if ($currentCount + $newCount > 2) {
                throw new Exception("Phòng thi chỉ được tối đa 2 giám thị. Hiện tại có {$currentCount} giám thị");
            }

            foreach ($maGVs as $maGV) {
                if ($this->isTeacherAssigned($maGV, $ngayThi, $caThi)) {
                    throw new Exception("Giáo viên đã được phân công trong {$caThi} ngày {$ngayThi}");
                }

                $sql = "INSERT INTO pcgiamthi (maGV, maPhong, ngayThi, caThi) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$maGV, $maPhong, $ngayThi, $caThi]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return $e->getMessage();
        }
    }

    // Đếm số giám thị hiện tại
    public function countCurrentSupervisors($maPhong, $ngayThi, $caThi) {
        $sql = "SELECT COUNT(*) FROM pcgiamthi WHERE maPhong = ? AND ngayThi = ? AND caThi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhong, $ngayThi, $caThi]);
        return $stmt->fetchColumn();
    }

    // Hủy phân công giám thị
    public function cancelSupervisorAssignment($maPCGT) {
        try {
            $sql = "DELETE FROM pcgiamthi WHERE maPCGT = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maPCGT]);
            return true;
        } catch (Exception $e) {
            return "❌ Lỗi khi hủy phân công: " . $e->getMessage();
        }
    }

    // Lấy số ca thi đã qua
    public function getExpiredSessionsCount($maKyThi) {
        $currentDate = date('Y-m-d');
        
        $sql = "SELECT COUNT(DISTINCT CONCAT(ngayThi, '_', caThi)) as count 
                FROM kythi 
                WHERE maKyThi = ? 
                AND ngayThi < ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKyThi, $currentDate]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] ?? 0;
    }
}
?>