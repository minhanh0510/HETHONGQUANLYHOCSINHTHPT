<?php
// models/ScoreEdit.php
class ScoreEdit {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function getHocKy() {
        // LUÔN trả về mảng mặc định - KHÔNG query database
        $currentYear = date('Y');
        return [
            [
                'maHocKy' => 1,
                'tenHocKy' => 'Học kỳ 1 - Năm học ' . $currentYear . '-' . ($currentYear + 1),
                'namHoc' => $currentYear . '-' . ($currentYear + 1)
            ],
            [
                'maHocKy' => 2, 
                'tenHocKy' => 'Học kỳ 2 - Năm học ' . $currentYear . '-' . ($currentYear + 1),
                'namHoc' => $currentYear . '-' . ($currentYear + 1)
            ]
        ];
    }

        public function getMinhChung() {
        // Đảm bảo tên bảng đúng
        $sql = "SELECT 
                    mc.*, 
                    hs.maHS,
                    nd.hoVaTen as tenHS,
                    mh.tenMon,
                    gv.maGV,
                    nd_gv.hoVaTen as tenGiaoVien
                FROM MINHCHUNG_SUADIEM mc
                JOIN HOCSINH hs ON mc.maHS = hs.maHS
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN MONHOC mh ON mc.maMon = mh.maMon
                JOIN GIAOVIEN gv ON mc.maGV = gv.maGV
                JOIN NGUOIDUNG nd_gv ON gv.maNguoiDung = nd_gv.maNguoiDung
                WHERE mc.trangThai = 'ChoXuLy'  -- CHỈ LẤY MINH CHỨNG CHƯA XỬ LÝ
                ORDER BY mc.ngayTao DESC";
        
        try {
            $result = $this->db->query($sql);
            if ($result) {
                return $result->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        } catch (Exception $e) {
            error_log("Lỗi getMinhChung: " . $e->getMessage());
            return [];
        }
    }

    public function debugStudentData($maHS) {
        try {
            // Kiểm tra học sinh có tồn tại không
            $sqlStudent = "SELECT hs.*, nd.hoVaTen 
                        FROM HOCSINH hs 
                        JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung 
                        WHERE hs.maHS = ?";
            $stmt = $this->db->prepare($sqlStudent);
            $stmt->execute([$maHS]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Kiểm tra điểm
            $sqlScores = "SELECT COUNT(*) as total FROM DIEM WHERE maHS = ?";
            $stmt = $this->db->prepare($sqlScores);
            $stmt->execute([$maHS]);
            $scoreCount = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'student' => $student,
                'score_count' => $scoreCount['total']
            ];
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getStudentScores($maHS, $maMon = null) {
        try {
            // Query sửa lại - không join với LOP vì không có maLop trong DIEM
            $sql = "SELECT 
                        d.maDiem, d.maHS, d.maMon, 
                        d.diemSo, d.loaiDiem, d.hocKy, d.namHoc, d.ngayNhap,
                        mh.tenMon,
                        nd.hoVaTen as tenHS,
                        l.tenLop
                    FROM DIEM d
                    JOIN HOCSINH hs ON d.maHS = hs.maHS
                    JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                    JOIN MONHOC mh ON d.maMon = mh.maMon
                    LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    LEFT JOIN LOP l ON pc.maLop = l.maLop
                    WHERE d.maHS = ?";
            
            $params = [$maHS];
            
            if ($maMon) {
                $sql .= " AND d.maMon = ?";
                $params[] = $maMon;
            }
            
            $sql .= " ORDER BY d.hocKy DESC, mh.tenMon ASC, d.ngayNhap DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $scores;
            
        } catch (Exception $e) {
            error_log("Lỗi getStudentScores: " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật điểm mới
    // Cập nhật điểm mới
    // Cập nhật điểm mới - PHIÊN BẢN ĐƠN GIẢN
public function updateScore($maDiem, $diemMoi, $maNguoiSua, $lyDo, $maMinhChung = null) {
    try {
        $this->db->beginTransaction();

        // 1. Lấy thông tin điểm cũ
        $sqlOld = "SELECT * FROM DIEM WHERE maDiem = ?";
        $stmt = $this->db->prepare($sqlOld);
        $stmt->execute([$maDiem]);
        $diemCu = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$diemCu) {
            throw new Exception("Không tìm thấy điểm cần sửa");
        }

        // 2. Kiểm tra phạm vi điểm
        if ($diemMoi < 0 || $diemMoi > 10) {
            throw new Exception("Điểm nhập sai phạm vi (0-10)");
        }

        // 3. Cập nhật điểm mới
        $sqlUpdate = "UPDATE DIEM SET diemSo = ?, ngayNhap = NOW() WHERE maDiem = ?";
        $stmt = $this->db->prepare($sqlUpdate);
        $stmt->execute([$diemMoi, $maDiem]);

        // 4. Lưu lịch sử sửa điểm
        $sqlHistory = "INSERT INTO LICHSU_SUADIEM 
                      (maDiem, diemCu, diemMoi, maNguoiSua, lyDo, ngaySua) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sqlHistory);
        $stmt->execute([
            $maDiem, 
            $diemCu['diemSo'], 
            $diemMoi, 
            $maNguoiSua, 
            $lyDo
        ]);

        // 5. CẬP NHẬT TRẠNG THÁI MINH CHỨNG - CHỈ CẬP NHẬT TRẠNG THÁI
        if ($maMinhChung) {
            $sqlUpdateMinhChung = "UPDATE MINHCHUNG_SUADIEM 
                                 SET trangThai = 'DaXuLy'
                                 WHERE maMinhChung = ?";
            $stmt = $this->db->prepare($sqlUpdateMinhChung);
            $stmt->execute([$maMinhChung]);
        }

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        error_log("Lỗi updateScore: " . $e->getMessage());
        throw $e;
    }
}

    // Lấy lịch sử sửa điểm
    public function getLichSuSuaDiem($maDiem = null, $limit = 10) {
        try {
            // Query cơ bản
            $sql = "SELECT 
                        ls.*,
                        hs.maHS,
                        nd_hs.hoVaTen as tenHS,
                        mh.tenMon,
                        nd_sua.hoVaTen as tenNguoiSua
                    FROM LICHSU_SUADIEM ls
                    JOIN DIEM d ON ls.maDiem = d.maDiem
                    JOIN HOCSINH hs ON d.maHS = hs.maHS
                    JOIN NGUOIDUNG nd_hs ON hs.maNguoiDung = nd_hs.maNguoiDung
                    JOIN MONHOC mh ON d.maMon = mh.maMon
                    JOIN NGUOIDUNG nd_sua ON ls.maNguoiSua = nd_sua.maNguoiDung";
            
            $params = [];
            $types = []; // Mảng để lưu kiểu dữ liệu của params
            
            // Nếu có maDiem, filter theo maDiem
            if ($maDiem) {
                $sql .= " WHERE ls.maDiem = ?";
                $params[] = $maDiem;
                $types[] = PDO::PARAM_INT;
            }
            
            $sql .= " ORDER BY ls.ngaySua DESC";
            
            // Nếu không có maDiem, áp dụng limit
            if (!$maDiem && $limit) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
                $types[] = PDO::PARAM_INT;
            }
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters với kiểu dữ liệu cụ thể
            foreach ($params as $index => $value) {
                $paramType = isset($types[$index]) ? $types[$index] : PDO::PARAM_STR;
                $stmt->bindValue($index + 1, $value, $paramType);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Lỗi getLichSuSuaDiem: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log("Lỗi getLichSuSuaDiem: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách môn học
    public function getMonHoc() {
        $sql = "SELECT maMon, tenMon FROM MONHOC ORDER BY tenMon";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin học sinh
    public function getStudentInfo($maHS) {
        $sql = "SELECT hs.*, 
                       (SELECT hoVaTen FROM NGUOIDUNG WHERE maNguoiDung = hs.maNguoiDung) as hoVaTen,
                       l.tenLop 
                FROM HOCSINH hs 
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                LEFT JOIN LOP l ON pc.maLop = l.maLop
                WHERE hs.maHS = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lấy tất cả lịch sử sửa điểm (không giới hạn)
    public function getAllLichSuSuaDiem() {
        try {
            $sql = "SELECT 
                        ls.*,
                        hs.maHS,
                        nd_hs.hoVaTen as tenHS,
                        mh.tenMon,
                        nd_sua.hoVaTen as tenNguoiSua,
                        l.tenLop
                    FROM LICHSU_SUADIEM ls
                    JOIN DIEM d ON ls.maDiem = d.maDiem
                    JOIN HOCSINH hs ON d.maHS = hs.maHS
                    JOIN NGUOIDUNG nd_hs ON hs.maNguoiDung = nd_hs.maNguoiDung
                    JOIN MONHOC mh ON d.maMon = mh.maMon
                    JOIN NGUOIDUNG nd_sua ON ls.maNguoiSua = nd_sua.maNguoiDung
                    LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    LEFT JOIN LOP l ON pc.maLop = l.maLop
                    ORDER BY ls.ngaySua DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Lỗi getAllLichSuSuaDiem: " . $e->getMessage());
            return [];
        }
    }

    // Lấy lịch sử sửa điểm với phân trang
    public function getLichSuSuaDiemPaginated($page = 1, $perPage = 20) {
        try {
            $offset = ($page - 1) * $perPage;
            
            $sql = "SELECT 
                        ls.*,
                        hs.maHS,
                        nd_hs.hoVaTen as tenHS,
                        mh.tenMon,
                        nd_sua.hoVaTen as tenNguoiSua,
                        l.tenLop
                    FROM LICHSU_SUADIEM ls
                    JOIN DIEM d ON ls.maDiem = d.maDiem
                    JOIN HOCSINH hs ON d.maHS = hs.maHS
                    JOIN NGUOIDUNG nd_hs ON hs.maNguoiDung = nd_hs.maNguoiDung
                    JOIN MONHOC mh ON d.maMon = mh.maMon
                    JOIN NGUOIDUNG nd_sua ON ls.maNguoiSua = nd_sua.maNguoiDung
                    LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                    LEFT JOIN LOP l ON pc.maLop = l.maLop
                    ORDER BY ls.ngaySua DESC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Lỗi getLichSuSuaDiemPaginated: " . $e->getMessage());
            return [];
        }
    }

    // Đếm tổng số bản ghi lịch sử
    public function countAllLichSuSuaDiem() {
        try {
            $sql = "SELECT COUNT(*) as total FROM LICHSU_SUADIEM";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Lỗi countAllLichSuSuaDiem: " . $e->getMessage());
            return 0;
        }
    }
}
?>