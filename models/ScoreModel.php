<?php
class ScoreModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Lấy danh sách học kỳ từ CSDL
    public function getSemesters($maHS) {
        $query = "SELECT DISTINCT 
                        hocKy AS id,
                        CONCAT('Học kỳ ', hocKy) AS semester_name,
                        namHoc AS academic_year
                  FROM diem
                  WHERE maHS = :maHS
                  ORDER BY namHoc DESC, hocKy DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['maHS' => $maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy điểm tổng quan theo học kỳ và mã học sinh
    public function getScoresOverview($semester_id = null, $year = null, $maHS = null) {
        if (!$semester_id || !$year) {
            // Nếu không có học kỳ, lấy học kỳ mới nhất
            $latest_semester = $this->getLatestSemester($maHS);
            $semester_id = $latest_semester['id'];
            $year = $latest_semester['academic_year'];
        }

        // Nếu không có mã học sinh, lấy mặc định
        if (!$maHS) {
            $maHS = 'HS001';
        }

        $query = "SELECT 
                    m.maMon,
                    m.tenMon as subject_name,
                    -- Điểm kiểm tra thường xuyên (hệ số 1)
                    (SELECT AVG(diemSo) 
                     FROM diem 
                     WHERE maMon = m.maMon 
                     AND hocKy = :semester_id 
                     AND namHoc = :nam_hoc 
                     AND maHS = :ma_hs
                     AND loaiDiem = 'MiengTX') as regular_score,
                    
                    -- Điểm kiểm tra giữa kỳ 15 phút (hệ số 1)
                    (SELECT AVG(diemSo) 
                     FROM diem 
                     WHERE maMon = m.maMon 
                     AND hocKy = :semester_id2 
                     AND namHoc = :nam_hoc2 
                     AND maHS = :ma_hs2
                     AND loaiDiem = 'Giua15Phut') as midterm_score,
                    
                    -- Điểm kiểm tra 1 tiết (hệ số 2)
                    (SELECT AVG(diemSo) 
                     FROM diem 
                     WHERE maMon = m.maMon 
                     AND hocKy = :semester_id3 
                     AND namHoc = :nam_hoc3 
                     AND maHS = :ma_hs3
                     AND loaiDiem = 'MotTiet') as final_score,
                    
                    -- Điểm giữa kỳ (hệ số 2)
                    (SELECT AVG(diemSo) 
                     FROM diem 
                     WHERE maMon = m.maMon 
                     AND hocKy = :semester_id4 
                     AND namHoc = :nam_hoc4 
                     AND maHS = :ma_hs4
                     AND loaiDiem = 'GiuaKy') as giua_ky_score,
                    
                    -- Điểm cuối kỳ (hệ số 3)
                    (SELECT AVG(diemSo) 
                     FROM diem 
                     WHERE maMon = m.maMon 
                     AND hocKy = :semester_id5 
                     AND namHoc = :nam_hoc5 
                     AND maHS = :ma_hs5
                     AND loaiDiem = 'CuoiKy') as cuoi_ky_score
                    
                  FROM monhoc m
                  WHERE EXISTS (
                    SELECT 1 FROM diem d 
                    WHERE d.maMon = m.maMon 
                    AND d.hocKy = :semester_id6 
                    AND d.namHoc = :nam_hoc6
                    AND d.maHS = :ma_hs6
                  )
                  ORDER BY m.tenMon";

        $params = [
            'semester_id' => $semester_id,
            'semester_id2' => $semester_id,
            'semester_id3' => $semester_id,
            'semester_id4' => $semester_id,
            'semester_id5' => $semester_id,
            'semester_id6' => $semester_id,
            'nam_hoc' => $year,
            'nam_hoc2' => $year,
            'nam_hoc3' => $year,
            'nam_hoc4' => $year,
            'nam_hoc5' => $year,
            'nam_hoc6' => $year,
            'ma_hs' => $maHS,
            'ma_hs2' => $maHS,
            'ma_hs3' => $maHS,
            'ma_hs4' => $maHS,
            'ma_hs5' => $maHS,
            'ma_hs6' => $maHS,
        ];

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính điểm trung bình theo công thức Bộ GD
        foreach ($scores as &$score) {
            $score['average_score'] = $this->calculateAverageScore($score);
        }

        return $scores;
    }

    // Tính điểm trung bình theo công thức Bộ GD
    private function calculateAverageScore($score) {
        $diem_tx = $score['regular_score'] ? floatval($score['regular_score']) : 0;
        $diem_gk_15p = $score['midterm_score'] ? floatval($score['midterm_score']) : 0;
        $diem_1tiet = $score['final_score'] ? floatval($score['final_score']) : 0;
        $diem_giua_ky = $score['giua_ky_score'] ? floatval($score['giua_ky_score']) : 0;
        $diem_cuoi_ky = $score['cuoi_ky_score'] ? floatval($score['cuoi_ky_score']) : 0;

        // Công thức: (Điểm TX + Điểm GK 15p + Điểm 1 tiết*2 + Điểm giữa kỳ*2 + Điểm cuối kỳ*3) / (1 + 1 + 2 + 2 + 3)
        $tu_so = $diem_tx + $diem_gk_15p + ($diem_1tiet * 2) + ($diem_giua_ky * 2) + ($diem_cuoi_ky * 3);
        $mau_so = 1 + 1 + 2 + 2 + 3; // Tổng hệ số = 9

        return $mau_so > 0 ? round($tu_so / $mau_so, 1) : 0;
    }

    // Lấy học kỳ mới nhất
    private function getLatestSemester($maHS) {
        $query = "SELECT hocKy AS id, namHoc AS academic_year
                  FROM diem
                  WHERE maHS = :maHS
                  ORDER BY namHoc DESC, hocKy DESC
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['maHS' => $maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin học sinh THEO MÃ HỌC SINH
    public function getStudentInfo($maHS = null) {
        // Nếu không có mã học sinh, lấy mặc định
        if (!$maHS) {
            $maHS = 'HS001';
        }

        // Lấy thông tin từ học sinh theo mã HS
        $query = "SELECT nd.hoVaTen, hs.maHS, pc.maLop, pc.namHoc
                  FROM hocsinh hs
                  JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                  JOIN phancong pc ON hs.maHS = pc.maHS
                  WHERE pc.trangThai = 'DangHoc'
                  AND hs.maHS = :maHS
                  ORDER BY pc.namHoc DESC
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['maHS' => $maHS]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        }

        // Fallback demo data
        return [
            'hoVaTen' => 'NGUYỄN VĂN A',
            'maHS' => $maHS,
            'maLop' => '10A1',
            'namHoc' => '2024-2025'
        ];
    }
}
?>