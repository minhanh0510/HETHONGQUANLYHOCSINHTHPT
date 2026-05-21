<?php
class Statistics {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // =================== DANH SÁCH HỌC SINH ===================
    public function getStudentList($filterType, $filterValue = null) {
    $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, 
                lp.tenLop, k.tenKhoi,
                hs.dangThaiHocTap,
                CASE 
                    WHEN EXISTS (SELECT 1 FROM phancong pc2 
                                WHERE pc2.maHS = hs.maHS 
                                AND pc2.trangThai = 'DangHoc' 
                                AND pc2.namHoc = (SELECT MAX(namHoc) FROM phancong))
                    THEN 'Đang học' 
                    ELSE 'Chưa phân công' 
                END AS trangThaiPhanCong
            FROM hocsinh hs
            JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
            JOIN lop lp ON hs.maLop = lp.maLop
            JOIN khoi k ON lp.maKhoi = k.maKhoi
            WHERE hs.dangThaiHocTap = 'Đang học'";
    
    $params = [];
    
    if ($filterType === 'lop' && $filterValue) {
        $sql .= " AND lp.maLop = ?";
        $params[] = $filterValue;
    } elseif ($filterType === 'khoi' && $filterValue) {
        $sql .= " AND k.maKhoi = ?";
        $params[] = $filterValue;
    }
    
    $sql .= " ORDER BY k.tenKhoi, lp.tenLop, nd.hoVaTen";
    
    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi SQL trong getStudentList: " . $e->getMessage());
        return [];
    }
    }
    
    // =================== DANH SÁCH GIÁO VIÊN ===================
    public function getTeacherList($toChuyenMon = null) {
    $sql = "SELECT gv.maGV, nd.hoVaTen, nd.gioiTinh,
                   gv.toChuyenMon, gv.monGiangDay,
                   COUNT(DISTINCT pcgvbm.maLop) AS soLopPhuTrach,
                   COUNT(DISTINCT pcgvbm.maMon) AS soMonPhuTrach
            FROM giaovien gv
            JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
            LEFT JOIN pcgvbm pcgvbm ON gv.maGV = pcgvbm.maGV
            WHERE 1=1";
    
    $params = [];
    
    if ($toChuyenMon) {
        $sql .= " AND gv.toChuyenMon = ?";
        $params[] = $toChuyenMon;
    }
    
    $sql .= " GROUP BY gv.maGV, nd.hoVaTen, nd.gioiTinh, gv.toChuyenMon, gv.monGiangDay
              ORDER BY gv.toChuyenMon, nd.hoVaTen";
    
    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // DEBUG: Log kết quả
        error_log("Teacher list count: " . count($result));
        if (!empty($result)) {
            error_log("First teacher: " . print_r($result[0], true));
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Lỗi SQL trong getTeacherList: " . $e->getMessage());
        return [];
    }
}

// =================== DANH SÁCH PHÂN CÔNG GIẢNG DẠY ===================
public function getAssignmentList($filters = []) {
    $sql = "
        SELECT
            gv.maGV,
            nd.hoVaTen AS tenGiaoVien,
            gv.toChuyenMon,
            GROUP_CONCAT(DISTINCT mh.tenMon ORDER BY mh.tenMon SEPARATOR ', ') AS cacMonDay,
            COUNT(DISTINCT l.maLop) AS soLopPhuTrach,
            GROUP_CONCAT(DISTINCT CONCAT(l.tenLop, ' (', k.tenKhoi, ')') ORDER BY k.tenKhoi, l.tenLop SEPARATOR ', ') AS cacLopPhuTrach,
            pc.namHoc,
            pc.hocky AS hocKy,
            COALESCE(SUM(tkb.soTiet), 0) AS tongSoTiet  -- Tổng số tiết dạy
        FROM pcgvbm pc
        JOIN giaovien gv ON pc.maGV = gv.maGV
        JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
        JOIN monhoc mh ON pc.maMon = mh.maMon
        JOIN lop l ON pc.maLop = l.maLop
        JOIN khoi k ON l.maKhoi = k.maKhoi
        LEFT JOIN (
            SELECT maLop, maMon, maGV, namHoc, hocKy, COUNT(*) as soTiet
            FROM thoikhoabieu 
            GROUP BY maLop, maMon, maGV, namHoc, hocKy
        ) tkb ON (
            tkb.maLop = pc.maLop 
            AND tkb.maMon = pc.maMon 
            AND tkb.maGV = pc.maGV
            AND tkb.namHoc = pc.namHoc
            AND tkb.hocKy = pc.hocky
        )
        WHERE 1=1";
    
    $params = [];
    
    if (!empty($filters['namHoc'])) {
        $sql .= " AND pc.namHoc = ?";
        $params[] = $filters['namHoc'];
    }
    
    if (!empty($filters['hocKy'])) {
        $sql .= " AND pc.hocky = ?";
        $params[] = $filters['hocKy'];
    }
    
    if (!empty($filters['toChuyenMon'])) {
        $sql .= " AND gv.toChuyenMon = ?";
        $params[] = $filters['toChuyenMon'];
    }
    
    $sql .= "
        GROUP BY
            gv.maGV,
            pc.namHoc,
            pc.hocky
        ORDER BY 
            gv.toChuyenMon,
            nd.hoVaTen,
            pc.namHoc DESC,
            pc.hocky";
    
    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Nếu không có dữ liệu, kiểm tra xem có tồn tại dữ liệu không
        if (empty($result)) {
            error_log("No assignment data found with filters: " . print_r($filters, true));
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Lỗi SQL trong getAssignmentList: " . $e->getMessage());
        return [];
    }
}
    

    // =================== THỐNG KÊ CƠ CẤU HỌC SINH ===================
    public function getStudentStructure($scopeType, $scopeValue = null) {
    // KHỞI TẠO BIẾN $sql VÀ $params
    $sql = "";
    $params = [];
    
    if ($scopeType === 'all') {
        $sql = "SELECT 
                    'Toàn trường' as tenPhamVi,
                    COUNT(*) AS tongSo,
                    SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) AS soNam,
                    SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) AS soNu,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNam,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNu
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.dangThaiHocTap = 'Đang học'";
                
    } elseif ($scopeType === 'lop' && $scopeValue) {
        // SỬA: JOIN trực tiếp từ hs.maLop
        $sql = "SELECT 
                    CONCAT('Lớp ', lp.tenLop) as tenPhamVi,
                    COUNT(*) AS tongSo,
                    SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) AS soNam,
                    SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) AS soNu,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNam,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNu
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN lop lp ON hs.maLop = lp.maLop  -- SỬA: JOIN trực tiếp
                WHERE hs.dangThaiHocTap = 'Đang học' AND lp.maLop = ?";
        $params[] = $scopeValue;
        
    } elseif ($scopeType === 'khoi' && $scopeValue) {
        // SỬA: JOIN trực tiếp từ hs.maLop
        $sql = "SELECT 
                    CONCAT('Khối ', k.tenKhoi) as tenPhamVi,
                    COUNT(*) AS tongSo,
                    SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) AS soNam,
                    SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) AS soNu,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nam' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNam,
                    ROUND(SUM(CASE WHEN nd.gioiTinh = 'Nữ' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeNu
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN lop lp ON hs.maLop = lp.maLop  -- SỬA: JOIN trực tiếp
                JOIN khoi k ON lp.maKhoi = k.maKhoi
                WHERE hs.dangThaiHocTap = 'Đang học' AND k.maKhoi = ?";
        $params[] = $scopeValue;
    } else {
        return [];
    }
        
    // Kiểm tra xem $sql có được khởi tạo không
    if (empty($sql)) {
        return [];
    }
    
    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Lỗi SQL trong getStudentStructure: " . $e->getMessage());
        return [];
    }
}
    
    
    // =================== THỐNG KÊ HỌC LỰC - ĐIỂM SỐ ===================
    public function getAcademicStatistics($criteria, $filters = []) {
        switch ($criteria) {
            case 'diem_theo_mon':
                return $this->getScoresBySubject($filters);
            case 'trung_binh_hoc_ky':
                return $this->getSemesterAverage($filters);
            case 'trung_binh_nam':
                return $this->getYearAverage($filters);
            case 'ty_le_hoc_luc':
                return $this->getAcademicLevelRatio($filters);
            case 'so_sanh_khoi':
                return $this->compareAcademicByGrade($filters);
            case 'so_sanh_lop':
                return $this->compareAcademicByClass($filters);
            default:
                return [];
        }
    }
    
    private function getScoresBySubject($filters) {
        $sql = "SELECT 
                    hs.maHS, nd.hoVaTen, lp.tenLop, k.tenKhoi,
                    m.tenMon,
                    AVG(CASE 
                        WHEN d.hocKy = ? AND d.namHoc = ? 
                        THEN d.diemSo END) AS diemTBHK,
                    AVG(CASE 
                        WHEN d.namHoc = ? 
                        THEN d.diemSo END) AS diemTBNam
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop lp ON pc.maLop = lp.maLop
                JOIN khoi k ON lp.maKhoi = k.maKhoi
                JOIN diem d ON hs.maHS = d.maHS
                JOIN monhoc m ON d.maMon = m.maMon
                WHERE hs.dangThaiHocTap = 'Đang học' 
                AND d.hocKy = ? 
                AND d.namHoc = ?";
        
        $params = [
            $filters['hocKy'] ?? 1,
            $filters['namHoc'] ?? date('Y'),
            $filters['namHoc'] ?? date('Y'),
            $filters['hocKy'] ?? 1,
            $filters['namHoc'] ?? date('Y')
        ];
        
        if (!empty($filters['maLop'])) {
            $sql .= " AND lp.maLop = ?";
            $params[] = $filters['maLop'];
        }
        
        if (!empty($filters['maMon']) && $filters['maMon'] !== 'all') {
            $sql .= " AND m.maMon = ?";
            $params[] = $filters['maMon'];
        }
        
        $sql .= " GROUP BY hs.maHS, nd.hoVaTen, m.maMon, m.tenMon
                  HAVING diemTBHK IS NOT NULL
                  ORDER BY diemTBHK DESC, nd.hoVaTen";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getScoresBySubject: " . $e->getMessage());
            return [];
        }
    }
    
    private function getSemesterAverage($filters) {
        $sql = "SELECT 
                    hs.maHS, nd.hoVaTen, lp.tenLop,
                    AVG(d.diemSo) AS diemTBHK
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop lp ON pc.maLop = lp.maLop
                JOIN diem d ON hs.maHS = d.maHS
                WHERE hs.dangThaiHocTap = 'Đang học' 
                AND d.hocKy = ? 
                AND d.namHoc = ?";
        
        $params = [
            $filters['hocKy'] ?? 1,
            $filters['namHoc'] ?? date('Y')
        ];
        
        if (!empty($filters['maLop'])) {
            $sql .= " AND lp.maLop = ?";
            $params[] = $filters['maLop'];
        }
        
        $sql .= " GROUP BY hs.maHS, nd.hoVaTen
                  ORDER BY diemTBHK DESC, nd.hoVaTen";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getSemesterAverage: " . $e->getMessage());
            return [];
        }
    }
    
    private function getYearAverage($filters) {
        $sql = "SELECT 
                    hs.maHS, nd.hoVaTen, lp.tenLop,
                    AVG(d.diemSo) AS diemTBNam
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop lp ON pc.maLop = lp.maLop
                JOIN diem d ON hs.maHS = d.maHS
                WHERE hs.dangThaiHocTap = 'Đang học' 
                AND d.namHoc = ?";
        
        $params = [$filters['namHoc'] ?? date('Y')];
        
        if (!empty($filters['maLop'])) {
            $sql .= " AND lp.maLop = ?";
            $params[] = $filters['maLop'];
        }
        
        $sql .= " GROUP BY hs.maHS, nd.hoVaTen
                  ORDER BY diemTBNam DESC, nd.hoVaTen";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getYearAverage: " . $e->getMessage());
            return [];
        }
    }
    
    private function getAcademicLevelRatio($filters) {
        $hocKy = $filters['hocKy'] ?? 0; // 0 = cả năm
        
        if ($hocKy == 0) {
            // Cả năm
            $sql = "SELECT 
                        'Toàn trường' as tenPhamVi,
                        COUNT(*) AS tongSo,
                        SUM(CASE 
                            WHEN tb.diemTB >= 8.0 
                            THEN 1 ELSE 0 END) AS gioi,
                        SUM(CASE 
                            WHEN tb.diemTB >= 6.5 AND tb.diemTB < 8.0
                            THEN 1 ELSE 0 END) AS kha,
                        SUM(CASE 
                            WHEN tb.diemTB >= 5.0 AND tb.diemTB < 6.5
                            THEN 1 ELSE 0 END) AS trungBinh,
                        SUM(CASE 
                            WHEN tb.diemTB < 5.0
                            THEN 1 ELSE 0 END) AS yeu,
                        ROUND(SUM(CASE WHEN tb.diemTB >= 8.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeGioi,
                        ROUND(SUM(CASE WHEN tb.diemTB >= 6.5 AND tb.diemTB < 8.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeKha,
                        ROUND(SUM(CASE WHEN tb.diemTB >= 5.0 AND tb.diemTB < 6.5 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeTrungBinh,
                        ROUND(SUM(CASE WHEN tb.diemTB < 5.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeYeu
                    FROM (
                        SELECT 
                            hs.maHS,
                            AVG(d.diemSo) AS diemTB
                        FROM hocsinh hs
                        JOIN diem d ON hs.maHS = d.maHS
                        WHERE hs.dangThaiHocTap = 'Đang học' 
                        AND d.namHoc = ?
                        GROUP BY hs.maHS
                    ) tb";
            $params = [$filters['namHoc'] ?? date('Y')];
        } else {
            // Theo học kỳ
            $sql = "SELECT 
                        'Toàn trường' as tenPhamVi,
                        COUNT(*) AS tongSo,
                        SUM(CASE 
                            WHEN tb.diemTBHK >= 8.0 
                            THEN 1 ELSE 0 END) AS gioi,
                        SUM(CASE 
                            WHEN tb.diemTBHK >= 6.5 AND tb.diemTBHK < 8.0
                            THEN 1 ELSE 0 END) AS kha,
                        SUM(CASE 
                            WHEN tb.diemTBHK >= 5.0 AND tb.diemTBHK < 6.5
                            THEN 1 ELSE 0 END) AS trungBinh,
                        SUM(CASE 
                            WHEN tb.diemTBHK < 5.0
                            THEN 1 ELSE 0 END) AS yeu,
                        ROUND(SUM(CASE WHEN tb.diemTBHK >= 8.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeGioi,
                        ROUND(SUM(CASE WHEN tb.diemTBHK >= 6.5 AND tb.diemTBHK < 8.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeKha,
                        ROUND(SUM(CASE WHEN tb.diemTBHK >= 5.0 AND tb.diemTBHK < 6.5 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeTrungBinh,
                        ROUND(SUM(CASE WHEN tb.diemTBHK < 5.0 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS tyLeYeu
                    FROM (
                        SELECT 
                            hs.maHS,
                            AVG(d.diemSo) AS diemTBHK
                        FROM hocsinh hs
                        JOIN diem d ON hs.maHS = d.maHS
                        WHERE hs.dangThaiHocTap = 'Đang học' 
                        AND d.hocKy = ? 
                        AND d.namHoc = ?
                        GROUP BY hs.maHS
                    ) tb";
            $params = [
                $filters['hocKy'] ?? 1,
                $filters['namHoc'] ?? date('Y')
            ];
        }
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getAcademicLevelRatio: " . $e->getMessage());
            return [];
        }
    }
    
    private function compareAcademicByGrade($filters) {
        $hocKy = $filters['hocKy'] ?? 0;
        
        if ($hocKy == 0) {
            $sql = "SELECT 
                        k.tenKhoi,
                        COUNT(*) AS tongSo,
                        AVG(tb.diemTB) AS diemTBTungKhoi,
                        SUM(CASE WHEN tb.diemTB >= 8.0 THEN 1 ELSE 0 END) AS soGioi,
                        SUM(CASE WHEN tb.diemTB >= 6.5 AND tb.diemTB < 8.0 THEN 1 ELSE 0 END) AS soKha,
                        SUM(CASE WHEN tb.diemTB >= 5.0 AND tb.diemTB < 6.5 THEN 1 ELSE 0 END) AS soTrungBinh,
                        SUM(CASE WHEN tb.diemTB < 5.0 THEN 1 ELSE 0 END) AS soYeu
                    FROM (
                        SELECT 
                            hs.maHS,
                            AVG(d.diemSo) AS diemTB,
                            pc.maLop
                        FROM hocsinh hs
                        JOIN diem d ON hs.maHS = d.maHS
                        JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                        WHERE hs.dangThaiHocTap = 'Đang học' 
                        AND d.namHoc = ?
                        GROUP BY hs.maHS, pc.maLop
                    ) tb
                    JOIN lop lp ON tb.maLop = lp.maLop
                    JOIN khoi k ON lp.maKhoi = k.maKhoi
                    GROUP BY k.maKhoi, k.tenKhoi
                    ORDER BY k.tenKhoi";
            $params = [$filters['namHoc'] ?? date('Y')];
        } else {
            $sql = "SELECT 
                        k.tenKhoi,
                        COUNT(*) AS tongSo,
                        AVG(tb.diemTBHK) AS diemTBTungKhoi,
                        SUM(CASE WHEN tb.diemTBHK >= 8.0 THEN 1 ELSE 0 END) AS soGioi,
                        SUM(CASE WHEN tb.diemTBHK >= 6.5 AND tb.diemTBHK < 8.0 THEN 1 ELSE 0 END) AS soKha,
                        SUM(CASE WHEN tb.diemTBHK >= 5.0 AND tb.diemTBHK < 6.5 THEN 1 ELSE 0 END) AS soTrungBinh,
                        SUM(CASE WHEN tb.diemTBHK < 5.0 THEN 1 ELSE 0 END) AS soYeu
                    FROM (
                        SELECT 
                            hs.maHS,
                            AVG(d.diemSo) AS diemTBHK,
                            pc.maLop
                        FROM hocsinh hs
                        JOIN diem d ON hs.maHS = d.maHS
                        JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                        WHERE hs.dangThaiHocTap = 'Đang học' 
                        AND d.hocKy = ? 
                        AND d.namHoc = ?
                        GROUP BY hs.maHS, pc.maLop
                    ) tb
                    JOIN lop lp ON tb.maLop = lp.maLop
                    JOIN khoi k ON lp.maKhoi = k.maKhoi
                    GROUP BY k.maKhoi, k.tenKhoi
                    ORDER BY k.tenKhoi";
            $params = [
                $filters['hocKy'] ?? 1,
                $filters['namHoc'] ?? date('Y')
            ];
        }
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong compareAcademicByGrade: " . $e->getMessage());
            return [];
        }
    }
    
    private function compareAcademicByClass($filters) {
        // Kiểm tra nếu không có maKhoi thì trả về mảng rỗng
        if (empty($filters['maKhoi'])) {
            return [];
        }
        
        $sql = "SELECT 
                    lp.tenLop, k.tenKhoi,
                    COUNT(*) AS tongSo,
                    AVG(CASE 
                        WHEN d.hocKy = ? AND d.namHoc = ? 
                        THEN d.diemSo END) AS diemTBLop,
                    SUM(CASE 
                        WHEN tb.diemTBHK >= 8.0 
                        THEN 1 ELSE 0 END) AS soGioi,
                    SUM(CASE 
                        WHEN tb.diemTBHK >= 6.5 AND tb.diemTBHK < 8.0
                        THEN 1 ELSE 0 END) AS soKha,
                    SUM(CASE 
                        WHEN tb.diemTBHK >= 5.0 AND tb.diemTBHK < 6.5
                        THEN 1 ELSE 0 END) AS soTrungBinh,
                    SUM(CASE 
                        WHEN tb.diemTBHK < 5.0
                        THEN 1 ELSE 0 END) AS soYeu
                FROM (
                    SELECT 
                        hs.maHS,
                        AVG(d.diemSo) AS diemTBHK,
                        pc.maLop
                    FROM hocsinh hs
                    JOIN diem d ON hs.maHS = d.maHS
                    JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    WHERE hs.dangThaiHocTap = 'Đang học' 
                    AND d.hocKy = ? 
                    AND d.namHoc = ?
                    GROUP BY hs.maHS, pc.maLop
                ) tb
                JOIN lop lp ON tb.maLop = lp.maLop
                JOIN khoi k ON lp.maKhoi = k.maKhoi
                LEFT JOIN diem d ON tb.maHS = d.maHS
                WHERE k.maKhoi = ?
                GROUP BY lp.maLop, lp.tenLop, k.maKhoi, k.tenKhoi
                ORDER BY diemTBLop DESC";
        
        $params = [
            $filters['hocKy'] ?? 1,
            $filters['namHoc'] ?? date('Y'),
            $filters['hocKy'] ?? 1,
            $filters['namHoc'] ?? date('Y'),
            $filters['maKhoi'] ?? 1
        ];
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong compareAcademicByClass: " . $e->getMessage());
            return [];
        }
    }
    
    // =================== THỐNG KÊ HẠNH KIỂM - CHUYÊN CẦN ===================
    public function getConductAttendanceStats($criteria, $filters = []) {
        switch ($criteria) {
            case 'ty_le_hanh_kiem':
                return $this->getConductRatio($filters);
            case 'so_sanh_lop_khoi':
                return $this->compareConductByClassGrade($filters);
            case 'trung_binh_nghi':
                return $this->getAverageAbsences($filters);
            case 'hoc_sinh_vi_pham':
                return $this->getDisciplinaryStudents($filters);
            default:
                return [];
        }
    }
    
    private function getConductRatio($filters) {
        $namHoc = $filters['namHoc'] ?? date('Y');
        
        $sql = "SELECT 
                    hk.xepLoai,
                    COUNT(*) AS soLuong,
                    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM hanhkiem WHERE namHoc = ?), 2) AS tyLePhanTram
                FROM hanhkiem hk
                JOIN hocsinh hs ON hk.maHS = hs.maHS
                WHERE hk.namHoc = ? 
                AND hs.dangThaiHocTap = 'Đang học'
                GROUP BY hk.xepLoai
                ORDER BY 
                    CASE hk.xepLoai 
                        WHEN 'Tốt' THEN 1
                        WHEN 'Khá' THEN 2
                        WHEN 'Trung bình' THEN 3
                        WHEN 'Yếu' THEN 4
                    END";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$namHoc, $namHoc]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getConductRatio: " . $e->getMessage());
            return [];
        }
    }
    
    private function compareConductByClassGrade($filters) {
        $namHoc = $filters['namHoc'] ?? date('Y');
        $scope = $filters['scope'] ?? 'khoi'; // 'khoi' hoặc 'lop'
        
        if ($scope === 'khoi') {
            $sql = "SELECT 
                        k.tenKhoi,
                        COUNT(*) AS tongSo,
                        SUM(CASE WHEN hk.xepLoai = 'Tốt' THEN 1 ELSE 0 END) AS soTot,
                        SUM(CASE WHEN hk.xepLoai = 'Khá' THEN 1 ELSE 0 END) AS soKha,
                        SUM(CASE WHEN hk.xepLoai = 'Trung bình' THEN 1 ELSE 0 END) AS soTrungBinh,
                        SUM(CASE WHEN hk.xepLoai = 'Yếu' THEN 1 ELSE 0 END) AS soYeu
                    FROM hanhkiem hk
                    JOIN hocsinh hs ON hk.maHS = hs.maHS
                    JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    JOIN lop lp ON pc.maLop = lp.maLop
                    JOIN khoi k ON lp.maKhoi = k.maKhoi
                    WHERE hk.namHoc = ? 
                    AND hs.dangThaiHocTap = 'Đang học'
                    GROUP BY k.maKhoi, k.tenKhoi
                    ORDER BY k.tenKhoi";
        } else {
            $sql = "SELECT 
                        lp.tenLop, k.tenKhoi,
                        COUNT(*) AS tongSo,
                        SUM(CASE WHEN hk.xepLoai = 'Tốt' THEN 1 ELSE 0 END) AS soTot,
                        SUM(CASE WHEN hk.xepLoai = 'Khá' THEN 1 ELSE 0 END) AS soKha,
                        SUM(CASE WHEN hk.xepLoai = 'Trung bình' THEN 1 ELSE 0 END) AS soTrungBinh,
                        SUM(CASE WHEN hk.xepLoai = 'Yếu' THEN 1 ELSE 0 END) AS soYeu
                    FROM hanhkiem hk
                    JOIN hocsinh hs ON hk.maHS = hs.maHS
                    JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    JOIN lop lp ON pc.maLop = lp.maLop
                    JOIN khoi k ON lp.maKhoi = k.maKhoi
                    WHERE hk.namHoc = ? 
                    AND hs.dangThaiHocTap = 'Đang học'
                    GROUP BY lp.maLop, lp.tenLop, k.maKhoi, k.tenKhoi
                    ORDER BY k.tenKhoi, lp.tenLop";
        }
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$namHoc]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong compareConductByClassGrade: " . $e->getMessage());
            return [];
        }
    }
    
    private function getAverageAbsences($filters) {
        $namHoc = $filters['namHoc'] ?? date('Y');
        
        $sql = "SELECT 
                    hs.maHS, nd.hoVaTen, lp.tenLop, k.tenKhoi,
                    COUNT(dd.maDiemDanh) AS tongBuoi,
                    SUM(CASE WHEN dd.trangThai IN ('Vắng', 'Đi trễ') THEN 1 ELSE 0 END) AS soBuoiVang,
                    ROUND(
                        AVG(CASE WHEN dd.trangThai IN ('Vắng', 'Đi trễ') THEN 1 ELSE 0 END) * 100.0, 
                        2
                    ) AS tyLeVangPhanTram
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop lp ON pc.maLop = lp.maLop
                JOIN khoi k ON lp.maKhoi = k.maKhoi
                LEFT JOIN diemdanh dd ON hs.maHS = dd.maHS
                    AND YEAR(dd.ngayDiemDanh) = YEAR(?)
                WHERE hs.dangThaiHocTap = 'Đang học'
                GROUP BY hs.maHS, nd.hoVaTen, lp.tenLop, k.tenKhoi
                HAVING soBuoiVang > 0
                ORDER BY soBuoiVang DESC, tyLeVangPhanTram DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$namHoc . '-01-01']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getAverageAbsences: " . $e->getMessage());
            return [];
        }
    }
    
    private function getDisciplinaryStudents($filters) {
        $namHoc = $filters['namHoc'] ?? date('Y');
        
        $sql = "SELECT 
                    hs.maHS, nd.hoVaTen, lp.tenLop, k.tenKhoi,
                    COUNT(vp.maViPham) AS soLanViPham,
                    GROUP_CONCAT(DISTINCT vp.noiDungViPham SEPARATOR ', ') AS cacViPham
                FROM hocsinh hs
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN phancong pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                JOIN lop lp ON pc.maLop = lp.maLop
                JOIN khoi k ON lp.maKhoi = k.maKhoi
                LEFT JOIN vipham vp ON hs.maHS = vp.maHS
                    AND YEAR(vp.ngayViPham) = YEAR(?)
                WHERE hs.dangThaiHocTap = 'Đang học'
                GROUP BY hs.maHS, nd.hoVaTen, lp.tenLop, k.tenKhoi
                HAVING soLanViPham > 0
                ORDER BY soLanViPham DESC, nd.hoVaTen";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$namHoc . '-01-01']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getDisciplinaryStudents: " . $e->getMessage());
            return [];
        }
    }
    
    public function getQuickStats() {
        try {
            // Tổng học sinh đang học
            $sql1 = "SELECT COUNT(*) as total FROM hocsinh WHERE dangThaiHocTap = 'Đang học'";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute();
            $totalStudents = $stmt1->fetchColumn();
            
            // Tổng giáo viên
            $sql2 = "SELECT COUNT(*) as total FROM giaovien";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute();
            $totalTeachers = $stmt2->fetchColumn();
            
            // Tổng lớp học
            $sql3 = "SELECT COUNT(*) as total FROM lop";
            $stmt3 = $this->db->prepare($sql3);
            $stmt3->execute();
            $totalClasses = $stmt3->fetchColumn();
            
            // Điểm TB toàn trường
            $currentYear = date('Y');
            $sql4 = "SELECT ROUND(AVG(d.diemSo), 2) as avg_score 
                    FROM diem d 
                    JOIN hocsinh hs ON d.maHS = hs.maHS 
                    WHERE hs.dangThaiHocTap = 'Đang học' 
                    AND d.diemSo IS NOT NULL 
                    AND d.namHoc = ?";
            $stmt4 = $this->db->prepare($sql4);
            $stmt4->execute([$currentYear]);
            $avgScore = $stmt4->fetchColumn();
            
            return [
                'total_students' => (int)$totalStudents,
                'total_teachers' => (int)$totalTeachers,
                'total_classes' => (int)$totalClasses,
                'avg_score' => $avgScore ? (float)$avgScore : 0
            ];
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getQuickStats: " . $e->getMessage());
            return [
                'total_students' => 0,
                'total_teachers' => 0,
                'total_classes' => 0,
                'avg_score' => 0
            ];
        }
    }
    
    public function getClassOptions() {
        try {
            $sql = "SELECT maLop as value, tenLop as label FROM lop ORDER BY tenLop";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getClassOptions: " . $e->getMessage());
            return [];
        }
    }
    
    public function getGradeOptions() {
        try {
            $sql = "SELECT maKhoi as value, tenKhoi as label FROM khoi ORDER BY tenKhoi";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getGradeOptions: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSubjectOptions() {
        try {
            $sql = "SELECT maMon as value, tenMon as label FROM monhoc ORDER BY tenMon";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getSubjectOptions: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSchoolYearOptions() {
        try {
            $sql = "SELECT DISTINCT namHoc as value, namHoc as label 
                    FROM pcgvbm 
                    WHERE namHoc IS NOT NULL AND namHoc != '' 
                    ORDER BY namHoc DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getSchoolYearOptions: " . $e->getMessage());
            return [];
        }
    }
    
    public function getSpecializedGroupOptions() {
        try {
            $sql = "SELECT DISTINCT toChuyenMon as value, toChuyenMon as label 
                    FROM giaovien 
                    WHERE toChuyenMon IS NOT NULL AND toChuyenMon != '' 
                    ORDER BY toChuyenMon";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getSpecializedGroupOptions: " . $e->getMessage());
            return [];
        }
    }
    
    // =================== LẤY DỮ LIỆU LỌC ===================
    public function getFilterOptions() {
        try {
            return [
                'lop' => $this->getClassOptions(),
                'khoi' => $this->getGradeOptions(),
                'mon' => $this->getSubjectOptions(),
                'to_chuyen_mon' => $this->getSpecializedGroupOptions(),
                'nam_hoc' => $this->getSchoolYearOptions()
            ];
        } catch (Exception $e) {
            error_log("Lỗi trong getFilterOptions: " . $e->getMessage());
            return [
                'lop' => [],
                'khoi' => [],
                'mon' => [],
                'to_chuyen_mon' => [],
                'nam_hoc' => []
            ];
        }
    }
    
    public function getDashboardStatistics() {
        return $this->getQuickStats();
    }
    
    public function getClassByGrade($maKhoi) {
        try {
            $sql = "SELECT maLop as value, tenLop as label FROM lop WHERE maKhoi = ? ORDER BY tenLop";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maKhoi]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi SQL trong getClassByGrade: " . $e->getMessage());
            return [];
        }
    }
}
?>