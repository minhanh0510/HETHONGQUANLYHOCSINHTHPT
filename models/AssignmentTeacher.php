<?php
// models/AssignmentTeacher.php

class AssignmentTeacher {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Lấy danh sách bài tập theo giáo viên
    public function getBaiTapByGiaoVien($maGV) {
        $sql = "SELECT bt.*, l.tenLop, m.tenMon 
                FROM baitap bt
                LEFT JOIN lop l ON bt.maLop = l.maLop
                LEFT JOIN monhoc m ON bt.maMon = m.maMon
                WHERE bt.maGV = ?
                ORDER BY bt.ngayTao DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy chi tiết bài tập
    public function getBaiTapById($maBaiTap) {
        $sql = "SELECT bt.*, l.tenLop, m.tenMon 
                FROM baitap bt
                LEFT JOIN lop l ON bt.maLop = l.maLop
                LEFT JOIN monhoc m ON bt.maMon = m.maMon
                WHERE bt.maBaiTap = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maBaiTap]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Tạo bài tập mới
    public function createBaiTap($data) {
        try {
            $sql = "INSERT INTO baitap (tenBaiTap, moTa, noiDung, thoiHanNop, maLop, maMon, maGV, trangThai) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'DangMo')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['tenBaiTap'], 
                $data['moTa'], 
                $data['noiDung'], 
                $data['thoiHanNop'], 
                $data['maLop'], 
                $data['maMon'], 
                $data['maGV']
            ]);
        } catch (Exception $e) {
            error_log("Error creating assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // Cập nhật bài tập
    public function updateBaiTap($maBaiTap, $data) {
        try {
            $sql = "UPDATE baitap 
                    SET tenBaiTap = ?, moTa = ?, noiDung = ?, thoiHanNop = ?, trangThai = ?
                    WHERE maBaiTap = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['tenBaiTap'], 
                $data['moTa'], 
                $data['noiDung'], 
                $data['thoiHanNop'],
                $data['trangThai'],
                $maBaiTap
            ]);
        } catch (Exception $e) {
            error_log("Error updating assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // XÓA BÀI TẬP VĨNH VIỄN
    public function deleteBaiTap($maBaiTap) {
        try {
            $this->db->beginTransaction();
            
            $sqlFiles = "SELECT duongDanFile FROM baocaothongke WHERE maBaiTap = ? AND duongDanFile IS NOT NULL";
            $stmtFiles = $this->db->prepare($sqlFiles);
            $stmtFiles->execute([$maBaiTap]);
            $files = $stmtFiles->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($files as $filePath) {
                if (!empty($filePath)) {
                    $fileList = explode('|', $filePath);
                    foreach ($fileList as $file) {
                        $fullPath = $_SERVER['DOCUMENT_ROOT'] . trim($file);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
            }
            
            $sqlDeleteSubmissions = "DELETE FROM baocaothongke WHERE maBaiTap = ?";
            $stmtDeleteSubmissions = $this->db->prepare($sqlDeleteSubmissions);
            $stmtDeleteSubmissions->execute([$maBaiTap]);
            
            $sqlDeleteAssignment = "DELETE FROM baitap WHERE maBaiTap = ?";
            $stmtDeleteAssignment = $this->db->prepare($sqlDeleteAssignment);
            $stmtDeleteAssignment->execute([$maBaiTap]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy danh sách bài nộp
    public function getBaiNopByBaiTap($maBaiTap) {
        $sql = "SELECT 
                    bc.*,
                    hs.maHS, 
                    nd.hoVaTen, 
                    hs.soBaoDanh,
                    bc.tenFile,
                    bc.duongDanFile,
                    bc.loaiFile,
                    bc.kichThuocFile,
                    CASE 
                        WHEN bc.tenFile IS NOT NULL THEN 'CoFile'
                        ELSE 'KhongFile'
                    END as loaiNop
                FROM baocaothongke bc
                INNER JOIN hocsinh hs ON bc.maHS = hs.maHS
                INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE bc.maBaiTap = ?
                ORDER BY bc.ngayNop DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maBaiTap]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy danh sách học sinh trong lớp (ĐÃ SỬA - CHÍNH XÁC)
    public function getHocSinhByLop($maLop) {
        try {
            // KIỂM TRA LỚP
            $sqlCheckLop = "SELECT maLop, tenLop, siSo FROM lop WHERE maLop = ?";
            $stmtCheck = $this->db->prepare($sqlCheckLop);
            $stmtCheck->execute([$maLop]);
            $lopInfo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if (!$lopInfo) {
                error_log("Class " . $maLop . " does not exist");
                return [];
            }
            
            error_log("Getting students for class " . $maLop . " (siSo: " . $lopInfo['siSo'] . ")");
            
            // TRUY VẤN CHÍNH XÁC: Lấy từ bảng học sinh (đơn giản và đúng nhất)
            $sql = "SELECT 
                        hs.maHS,
                        hs.soBaoDanh,
                        nd.hoVaTen,
                        nd.gioiTinh,
                        nd.ngaySinh,
                        hs.dangThaiHocTap,
                        hs.maLop,
                        l.tenLop,
                        l.siSo
                    FROM hocsinh hs
                    INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    LEFT JOIN lop l ON hs.maLop = l.maLop
                    WHERE hs.maLop = ?
                    ORDER BY nd.hoVaTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maLop]);
            $hocSinh = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($hocSinh) . " students for class " . $maLop);
            
            return $hocSinh;
        } catch (Exception $e) {
            error_log("Error getting students by class: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy chi tiết bài nộp
    public function getBaiNop($maBaoCao) {
        $sql = "SELECT 
                    bc.*, 
                    hs.maHS, 
                    nd.hoVaTen,
                    bc.tenFile,
                    bc.duongDanFile,
                    bc.loaiFile,
                    bc.kichThuocFile
                FROM baocaothongke bc
                INNER JOIN hocsinh hs ON bc.maHS = hs.maHS
                INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE bc.maBaoCao = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maBaoCao]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lấy chi tiết bài nộp đầy đủ
    public function getSubmissionDetail($maBaoCao) {
        try {
            $sql = "SELECT 
                        bc.*,
                        bt.maBaiTap,
                        bt.tenBaiTap,
                        bt.moTa as moTaBaiTap,
                        bt.thoiHanNop,
                        bt.maGV,
                        m.tenMon,
                        hs.maHS,
                        nd.hoVaTen as tenHocSinh,
                        nd.email as emailHocSinh,
                        l.tenLop,
                        bc.tenFile,
                        bc.duongDanFile,
                        bc.loaiFile,
                        bc.kichThuocFile
                    FROM baocaothongke bc
                    INNER JOIN baitap bt ON bc.maBaiTap = bt.maBaiTap
                    INNER JOIN monhoc m ON bt.maMon = m.maMon
                    INNER JOIN hocsinh hs ON bc.maHS = hs.maHS
                    INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    LEFT JOIN phancong pc ON hs.maHS = pc.maHS
                    LEFT JOIN lop l ON pc.maLop = l.maLop
                    WHERE bc.maBaoCao = ?
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maBaoCao]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting submission detail: " . $e->getMessage());
            return false;
        }
    }
    
    // Chấm điểm
    public function chamDiem($maBaoCao, $diem, $nhanXet) {
        try {
            $sql = "UPDATE baocaothongke 
                    SET diem = ?, nhanXet = ?, trangThai = 'DaCham'
                    WHERE maBaoCao = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$diem, $nhanXet, $maBaoCao]);
        } catch (Exception $e) {
            error_log("Error grading assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy danh sách lớp của giáo viên
    public function getLopByGiaoVien($maGV) {
        $sql = "SELECT DISTINCT l.maLop, l.tenLop, m.tenMon, m.maMon
                FROM pcgvbm pc
                INNER JOIN lop l ON pc.maLop = l.maLop
                INNER JOIN monhoc m ON pc.maMon = m.maMon
                WHERE pc.maGV = ? AND pc.namHoc = '2024-2025'
                ORDER BY l.tenLop";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy môn học của giáo viên
    public function getMonHocByGiaoVien($maGV) {
        $sql = "SELECT DISTINCT m.maMon, m.tenMon
                FROM pcgvbm pc
                INNER JOIN monhoc m ON pc.maMon = m.maMon
                WHERE pc.maGV = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Thống kê bài tập
    public function getAssignmentStatistics($maBaiTap) {
        try {
            $baiTap = $this->getBaiTapById($maBaiTap);
            
            $sqlTotal = "SELECT COUNT(*) as total FROM phancong WHERE maLop = ? AND trangThai = 'DangHoc'";
            $stmtTotal = $this->db->prepare($sqlTotal);
            $stmtTotal->execute([$baiTap['maLop']]);
            $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sqlSubmitted = "SELECT COUNT(*) as submitted FROM baocaothongke WHERE maBaiTap = ?";
            $stmtSubmitted = $this->db->prepare($sqlSubmitted);
            $stmtSubmitted->execute([$maBaiTap]);
            $submitted = $stmtSubmitted->fetch(PDO::FETCH_ASSOC)['submitted'];
            
            $sqlWithFile = "SELECT COUNT(*) as withFile FROM baocaothongke WHERE maBaiTap = ? AND tenFile IS NOT NULL";
            $stmtWithFile = $this->db->prepare($sqlWithFile);
            $stmtWithFile->execute([$maBaiTap]);
            $withFile = $stmtWithFile->fetch(PDO::FETCH_ASSOC)['withFile'];
            
            $sqlGraded = "SELECT COUNT(*) as graded FROM baocaothongke WHERE maBaiTap = ? AND trangThai = 'DaCham'";
            $stmtGraded = $this->db->prepare($sqlGraded);
            $stmtGraded->execute([$maBaiTap]);
            $graded = $stmtGraded->fetch(PDO::FETCH_ASSOC)['graded'];
            
            $sqlAvg = "SELECT AVG(diem) as avgScore FROM baocaothongke WHERE maBaiTap = ? AND trangThai = 'DaCham'";
            $stmtAvg = $this->db->prepare($sqlAvg);
            $stmtAvg->execute([$maBaiTap]);
            $avgScore = $stmtAvg->fetch(PDO::FETCH_ASSOC)['avgScore'];
            
            $sqlTotalSize = "SELECT SUM(kichThuocFile) as totalSize FROM baocaothongke WHERE maBaiTap = ? AND tenFile IS NOT NULL";
            $stmtTotalSize = $this->db->prepare($sqlTotalSize);
            $stmtTotalSize->execute([$maBaiTap]);
            $totalSize = $stmtTotalSize->fetch(PDO::FETCH_ASSOC)['totalSize'];
            
            return [
                'baiTap' => $baiTap,
                'totalStudents' => $total,
                'submitted' => $submitted,
                'submittedWithFile' => $withFile,
                'submittedTextOnly' => $submitted - $withFile,
                'notSubmitted' => $total - $submitted,
                'graded' => $graded,
                'notGraded' => $submitted - $graded,
                'averageScore' => $avgScore ? round($avgScore, 2) : 0,
                'totalFileSize' => $totalSize ? $totalSize : 0,
                'totalFileSizeMB' => $totalSize ? round($totalSize / 1024 / 1024, 2) : 0
            ];
        } catch (Exception $e) {
            error_log("Error getting assignment statistics: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy danh sách học sinh chưa nộp
    public function getHocSinhChuaNop($maBaiTap) {
        try {
            $baiTap = $this->getBaiTapById($maBaiTap);
            
            $sql = "SELECT hs.maHS, nd.hoVaTen, hs.soBaoDanh, nd.email
                    FROM phancong pc
                    INNER JOIN hocsinh hs ON pc.maHS = hs.maHS
                    INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    WHERE pc.maLop = ? AND pc.trangThai = 'DangHoc'
                    AND hs.maHS NOT IN (
                        SELECT maHS FROM baocaothongke WHERE maBaiTap = ?
                    )
                    ORDER BY nd.hoVaTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$baiTap['maLop'], $maBaiTap]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting students not submitted: " . $e->getMessage());
            return [];
        }
    }
    
    // Đóng bài tập
    public function closeAssignment($maBaiTap) {
        try {
            $sql = "UPDATE baitap SET trangThai = 'DaDong' WHERE maBaiTap = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$maBaiTap]);
        } catch (Exception $e) {
            error_log("Error closing assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // Mở lại bài tập
    public function reopenAssignment($maBaiTap) {
        try {
            $sql = "UPDATE baitap SET trangThai = 'DangMo' WHERE maBaiTap = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$maBaiTap]);
        } catch (Exception $e) {
            error_log("Error reopening assignment: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy danh sách điểm theo bài tập
    public function getGradesByAssignment($maBaiTap) {
        try {
            $sql = "SELECT 
                        hs.maHS,
                        nd.hoVaTen,
                        hs.soBaoDanh,
                        bc.diem,
                        bc.nhanXet,
                        bc.ngayNop,
                        bc.trangThai,
                        bc.tenFile,
                        bc.loaiFile,
                        bc.kichThuocFile
                    FROM phancong pc
                    INNER JOIN hocsinh hs ON pc.maHS = hs.maHS
                    INNER JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                    LEFT JOIN baocaothongke bc ON hs.maHS = bc.maHS AND bc.maBaiTap = ?
                    WHERE pc.maLop = (SELECT maLop FROM baitap WHERE maBaiTap = ?)
                    AND pc.trangThai = 'DangHoc'
                    ORDER BY nd.hoVaTen";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maBaiTap, $maBaiTap]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting grades: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy thông tin file từ bài nộp
    public function getFileInfo($maBaoCao) {
        try {
            $sql = "SELECT tenFile, duongDanFile, loaiFile, kichThuocFile 
                    FROM baocaothongke 
                    WHERE maBaoCao = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maBaoCao]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting file info: " . $e->getMessage());
            return false;
        }
    }
    
    // Kiểm tra file có tồn tại
    public function checkFileExists($duongDanFile) {
        if (empty($duongDanFile)) {
            return false;
        }
        
        $files = explode('|', $duongDanFile);
        foreach ($files as $file) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $file;
            if (!file_exists($fullPath)) {
                return false;
            }
        }
        return true;
    }
    
    // Format kích thước file
    public function formatFileSize($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    // Lấy icon theo loại file
    public function getFileIcon($loaiFile) {
        $icons = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'xls' => '📊',
            'xlsx' => '📊',
            'ppt' => '📽️',
            'pptx' => '📽️',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'zip' => '📦',
            'rar' => '📦',
            'txt' => '📃'
        ];
        
        return isset($icons[$loaiFile]) ? $icons[$loaiFile] : '📎';
    }
}
?>