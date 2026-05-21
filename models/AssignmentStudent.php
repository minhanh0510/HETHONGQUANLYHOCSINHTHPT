<?php
// models/AssignmentStudent.php

class AssignmentStudent {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy danh sách bài tập theo học sinh
     * @param string $maHS - Mã học sinh
     * @param array $filters - Bộ lọc (status, subject, search)
     * @return array
     */
    public function getBaiTapByHocSinh($maHS, $filters = []) {
        try {
            // Lấy lớp của học sinh
            $stmtLop = $this->db->prepare("SELECT maLop FROM hocsinh WHERE maHS = ?");
            $stmtLop->execute([$maHS]);
            $maLop = $stmtLop->fetchColumn();
            
            if (!$maLop) {
                return [];
            }
            
            $sql = "SELECT 
                        bt.maBaiTap,
                        bt.tenBaiTap,
                        bt.moTa,
                        bt.noiDung,
                        bt.thoiHanNop,
                        bt.maLop,
                        bt.maMon,
                        bt.maGV,
                        bt.ngayTao,
                        bt.trangThai,
                        m.tenMon,
                        nd.hoVaTen as tenGV,
                        l.tenLop,
                        -- Kiểm tra đã nộp chưa
                        bc.maBaoCao,
                        bc.diem,
                        bc.nhanXet,
                        bc.ngayNop,
                        bc.trangThai as trangThaiNop,
                        CASE 
                            WHEN bc.maBaoCao IS NOT NULL THEN 1
                            ELSE 0
                        END as daNop
                    FROM baitap bt
                    INNER JOIN monhoc m ON bt.maMon = m.maMon
                    INNER JOIN giaovien gv ON bt.maGV = gv.maGV
                    INNER JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    INNER JOIN lop l ON bt.maLop = l.maLop
                    LEFT JOIN baocaothongke bc ON bt.maBaiTap = bc.maBaiTap AND bc.maHS = ?
                    WHERE bt.maLop = ?";
            
            $params = [$maHS, $maLop];
            
            // Lọc theo trạng thái
            if (!empty($filters['status'])) {
                switch($filters['status']) {
                    case 'chuanop':
                        $sql .= " AND bc.maBaoCao IS NULL AND bt.trangThai = 'DangMo' AND bt.thoiHanNop >= NOW()";
                        break;
                    case 'danop':
                        $sql .= " AND bc.maBaoCao IS NOT NULL";
                        break;
                    case 'hethan':
                        $sql .= " AND bc.maBaoCao IS NULL AND bt.thoiHanNop < NOW()";
                        break;
                }
            }
            
            // Lọc theo môn học
            if (!empty($filters['subject'])) {
                $sql .= " AND bt.maMon = ?";
                $params[] = $filters['subject'];
            }
            
            // Tìm kiếm
            if (!empty($filters['search'])) {
                $sql .= " AND (bt.tenBaiTap LIKE ? OR bt.moTa LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $sql .= " ORDER BY 
                        CASE 
                            WHEN bt.trangThai = 'DangMo' AND bt.thoiHanNop >= NOW() THEN 1
                            WHEN bt.trangThai = 'DaDong' THEN 2
                            ELSE 3
                        END,
                        bt.thoiHanNop ASC,
                        bt.ngayTao DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error in getBaiTapByHocSinh: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy chi tiết bài tập
     */
    public function getBaiTapDetail($maBaiTap, $maHS) {
        try {
            $sql = "SELECT 
                        bt.*,
                        m.tenMon,
                        nd.hoVaTen as tenGV,
                        l.tenLop,
                        bc.maBaoCao,
                        bc.noiDung as noiDungBaiLam,
                        bc.tenFile,
                        bc.duongDanFile,
                        bc.loaiFile,
                        bc.kichThuocFile,
                        bc.diem,
                        bc.nhanXet,
                        bc.ngayNop,
                        bc.trangThai as trangThaiNop,
                        CASE 
                            WHEN bc.maBaoCao IS NOT NULL THEN 1
                            ELSE 0
                        END as daNop
                    FROM baitap bt
                    INNER JOIN monhoc m ON bt.maMon = m.maMon
                    INNER JOIN giaovien gv ON bt.maGV = gv.maGV
                    INNER JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    INNER JOIN lop l ON bt.maLop = l.maLop
                    LEFT JOIN baocaothongke bc ON bt.maBaiTap = bc.maBaiTap AND bc.maHS = ?
                    WHERE bt.maBaiTap = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maHS, $maBaiTap]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error in getBaiTapDetail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Nộp bài tập
     */
    public function nopBaiTap($data) {
        try {
            $this->db->beginTransaction();
            
            // Kiểm tra đã nộp chưa
            $stmtCheck = $this->db->prepare("SELECT maBaoCao FROM baocaothongke WHERE maHS = ? AND maBaiTap = ?");
            $stmtCheck->execute([$data['maHS'], $data['maBaiTap']]);
            
            if ($stmtCheck->fetch()) {
                throw new Exception("Bạn đã nộp bài tập này rồi!");
            }
            
            // Kiểm tra thời hạn
            $stmtDeadline = $this->db->prepare("SELECT thoiHanNop, trangThai FROM baitap WHERE maBaiTap = ?");
            $stmtDeadline->execute([$data['maBaiTap']]);
            $baiTap = $stmtDeadline->fetch(PDO::FETCH_ASSOC);
            
            if (!$baiTap) {
                throw new Exception("Không tìm thấy bài tập!");
            }
            
            if ($baiTap['trangThai'] === 'DaDong') {
                throw new Exception("Bài tập đã đóng, không thể nộp!");
            }
            
            if (strtotime($baiTap['thoiHanNop']) < time()) {
                throw new Exception("Bài tập đã quá hạn, không thể nộp!");
            }
            
            // Thêm bài nộp
            $sql = "INSERT INTO baocaothongke (
                        maHS, 
                        maBaiTap, 
                        noiDung, 
                        tenFile, 
                        duongDanFile, 
                        loaiFile, 
                        kichThuocFile, 
                        ngayNop, 
                        trangThai
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'DaNop')";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['maHS'],
                $data['maBaiTap'],
                $data['noiDung'],
                $data['tenFile'] ?? null,
                $data['duongDanFile'] ?? null,
                $data['loaiFile'] ?? null,
                $data['kichThuocFile'] ?? null
            ]);
            
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in nopBaiTap: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Upload file bài làm
     */
    public function uploadFile($file, $maHS, $maBaiTap) {
        try {
            // Validate file
            $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            $maxSize = 10 * 1024 * 1024; // 10MB
            
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExt, $allowedTypes)) {
                throw new Exception("Định dạng file không được hỗ trợ! Chỉ chấp nhận: " . implode(', ', $allowedTypes));
            }
            
            if ($file['size'] > $maxSize) {
                throw new Exception("File quá lớn! Kích thước tối đa: 10MB");
            }
            
            // Tạo thư mục upload
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/baitap/' . date('Y-m') . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Tạo tên file unique
            $fileName = $maHS . '_' . $maBaiTap . '_' . time() . '.' . $fileExt;
            $filePath = $uploadDir . $fileName;
            
            // Upload file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception("Không thể upload file!");
            }
            
            return [
                'tenFile' => $file['name'],
                'duongDanFile' => '/uploads/baitap/' . date('Y-m') . '/' . $fileName,
                'loaiFile' => $fileExt,
                'kichThuocFile' => $file['size']
            ];
            
        } catch (Exception $e) {
            error_log("Error in uploadFile: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Lấy danh sách môn học của học sinh
     */
    public function getMonHocByHocSinh($maHS) {
        try {
            $sql = "SELECT DISTINCT m.maMon, m.tenMon
                    FROM baitap bt
                    INNER JOIN monhoc m ON bt.maMon = m.maMon
                    INNER JOIN hocsinh hs ON bt.maLop = hs.maLop
                    WHERE hs.maHS = ?
                    ORDER BY m.tenMon";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maHS]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error in getMonHocByHocSinh: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Thống kê bài tập của học sinh
     */
    public function getStatistics($maHS) {
        try {
            $stmtLop = $this->db->prepare("SELECT maLop FROM hocsinh WHERE maHS = ?");
            $stmtLop->execute([$maHS]);
            $maLop = $stmtLop->fetchColumn();
            
            // Tổng số bài tập
            $stmtTotal = $this->db->prepare("SELECT COUNT(*) FROM baitap WHERE maLop = ?");
            $stmtTotal->execute([$maLop]);
            $total = $stmtTotal->fetchColumn();
            
            // Đã nộp
            $stmtSubmitted = $this->db->prepare(
                "SELECT COUNT(*) FROM baocaothongke bc
                 INNER JOIN baitap bt ON bc.maBaiTap = bt.maBaiTap
                 WHERE bc.maHS = ? AND bt.maLop = ?"
            );
            $stmtSubmitted->execute([$maHS, $maLop]);
            $submitted = $stmtSubmitted->fetchColumn();
            
            // Chưa nộp (còn hạn)
            $stmtPending = $this->db->prepare(
                "SELECT COUNT(*) FROM baitap bt
                 WHERE bt.maLop = ? 
                 AND bt.trangThai = 'DangMo'
                 AND bt.thoiHanNop >= NOW()
                 AND bt.maBaiTap NOT IN (
                     SELECT maBaiTap FROM baocaothongke WHERE maHS = ?
                 )"
            );
            $stmtPending->execute([$maLop, $maHS]);
            $pending = $stmtPending->fetchColumn();
            
            // Quá hạn
            $stmtExpired = $this->db->prepare(
                "SELECT COUNT(*) FROM baitap bt
                 WHERE bt.maLop = ? 
                 AND bt.thoiHanNop < NOW()
                 AND bt.maBaiTap NOT IN (
                     SELECT maBaiTap FROM baocaothongke WHERE maHS = ?
                 )"
            );
            $stmtExpired->execute([$maLop, $maHS]);
            $expired = $stmtExpired->fetchColumn();
            
            return [
                'total' => $total,
                'submitted' => $submitted,
                'pending' => $pending,
                'expired' => $expired
            ];
            
        } catch (Exception $e) {
            error_log("Error in getStatistics: " . $e->getMessage());
            return [
                'total' => 0,
                'submitted' => 0,
                'pending' => 0,
                'expired' => 0
            ];
        }
    }
}
?>