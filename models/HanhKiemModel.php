<?php
// models/HanhKiemModel.php - FIXED: Chỉ lấy học sinh của lớp cụ thể

class HanhKiemModel {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Lấy danh sách học sinh theo GVCN - FIXED
     * Chỉ lấy học sinh đang học trong các lớp của GVCN
     */
    public function getHocSinhByGVCN($maGV, $hocKy = 1, $namHoc = '2024-2025') {
        try {
            $sql = "SELECT DISTINCT
                        hs.maHS,
                        hs.soBaoDanh,
                        hs.maLop,
                        nd.hoVaTen,
                        nd.ngaySinh,
                        nd.gioiTinh,
                        l.maLop,
                        l.tenLop,
                        hk.maHK,
                        hk.xepLoai,
                        hk.nhanXet,
                        hk.ngayNhap
                    FROM pcgvcn gvcn
                    JOIN lop l 
                        ON gvcn.maLop = l.maLop
                    JOIN hocsinh hs 
                        ON hs.maLop = l.maLop
                    JOIN phancong pc 
                        ON pc.maHS = hs.maHS
                       AND pc.maLop = l.maLop
                       AND pc.namHoc = :namHoc
                       AND pc.trangThai = 'DangHoc'
                    JOIN nguoidung nd 
                        ON hs.maNguoiDung = nd.maNguoiDung
                    LEFT JOIN hanhkiem hk 
                        ON hk.maHS = hs.maHS
                       AND hk.hocKy = :hocKy
                       AND hk.namHoc = :namHoc
                    WHERE gvcn.maGV = :maGV
                      AND gvcn.namHoc = :namHoc
                    ORDER BY l.tenLop, nd.hoVaTen";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':maGV'   => $maGV,
                ':hocKy'  => $hocKy,
                ':namHoc' => $namHoc
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getHocSinhByGVCN: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy hạnh kiểm của học sinh - FIXED
     */
    public function getHanhKiemByHS($maHS, $hocKy = 1, $namHoc = '2024-2025') {
        try {
            $sql = "SELECT 
                        hk.maHK,
                        hk.maHS,
                        hk.xepLoai,
                        hk.nhanXet,
                        hk.nguoiNhap,
                        hk.ngayNhap,
                        hk.hocKy,
                        hk.namHoc
                    FROM hanhkiem hk
                    JOIN hocsinh hs ON hk.maHS = hs.maHS
                    JOIN phancong pc 
                        ON hk.maHS = pc.maHS
                       AND pc.maLop = hs.maLop
                       AND pc.trangThai = 'DangHoc'
                       AND pc.namHoc = :namHoc
                    WHERE hk.maHS = :maHS
                      AND hk.hocKy = :hocKy
                      AND hk.namHoc = :namHoc
                    LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':maHS'   => $maHS,
                ':hocKy'  => $hocKy,
                ':namHoc' => $namHoc
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;

        } catch (PDOException $e) {
            error_log("Error getHanhKiemByHS: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Thêm mới hạnh kiểm
     */
    public function themHanhKiem($maHS, $xepLoai, $nhanXet, $nguoiNhap, $hocKy = 1, $namHoc = '2024-2025') {
        // Kiểm tra xem đã có hạnh kiểm chưa
        $existing = $this->getHanhKiemByHS($maHS, $hocKy, $namHoc);
        
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Học sinh đã có hạnh kiểm cho học kỳ này. Vui lòng sử dụng chức năng chỉnh sửa.'
            ];
        }
        
        $query = "INSERT INTO hanhkiem 
                    (maHS, xepLoai, nhanXet, nguoiNhap, hocKy, namHoc) 
                VALUES 
                    (:maHS, :xepLoai, :nhanXet, :nguoiNhap, :hocKy, :namHoc)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maHS', $maHS);
            $stmt->bindParam(':xepLoai', $xepLoai);
            $stmt->bindParam(':nhanXet', $nhanXet);
            $stmt->bindParam(':nguoiNhap', $nguoiNhap);
            $stmt->bindParam(':hocKy', $hocKy);
            $stmt->bindParam(':namHoc', $namHoc);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Thêm hạnh kiểm thành công!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi thêm hạnh kiểm.'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error in themHanhKiem: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi database: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Cập nhật hạnh kiểm
     */
    public function capNhatHanhKiem($maHK, $xepLoai, $nhanXet, $nguoiNhap) {
        $query = "UPDATE hanhkiem 
                SET xepLoai = :xepLoai, 
                    nhanXet = :nhanXet,
                    nguoiNhap = :nguoiNhap,
                    ngayNhap = CURRENT_TIMESTAMP
                WHERE maHK = :maHK";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maHK', $maHK);
            $stmt->bindParam(':xepLoai', $xepLoai);
            $stmt->bindParam(':nhanXet', $nhanXet);
            $stmt->bindParam(':nguoiNhap', $nguoiNhap);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Cập nhật hạnh kiểm thành công!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật hạnh kiểm.'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error in capNhatHanhKiem: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi database: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Xóa hạnh kiểm
     */
    public function xoaHanhKiem($maHK) {
        $query = "DELETE FROM hanhkiem WHERE maHK = :maHK";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':maHK', $maHK);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Xóa hạnh kiểm thành công!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa hạnh kiểm.'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error in xoaHanhKiem: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Lỗi database: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Lấy thông tin lớp của giáo viên chủ nhiệm
     */
    public function getLopChuNhiem($maGV, $namHoc = '2024-2025') {
        $query = "SELECT l.*, pcgv.namHoc
                FROM pcgvcn pcgv
                INNER JOIN lop l ON pcgv.maLop = l.maLop
                WHERE pcgv.maGV = :maGV 
                    AND pcgv.namHoc = :namHoc";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maGV', $maGV);
        $stmt->bindParam(':namHoc', $namHoc);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thống kê hạnh kiểm theo lớp - FIXED
     */
    public function thongKeHanhKiem($maLop, $hocKy = 1, $namHoc = '2024-2025') {
        $sql = "SELECT 
                    hk.xepLoai,
                    COUNT(DISTINCT hk.maHS) AS soLuong
                FROM hanhkiem hk
                JOIN hocsinh hs ON hk.maHS = hs.maHS AND hs.maLop = :maLop
                JOIN phancong pc 
                    ON hk.maHS = pc.maHS
                   AND pc.maLop = :maLop
                   AND pc.namHoc = :namHoc
                   AND pc.trangThai = 'DangHoc'
                WHERE hk.hocKy = :hocKy
                  AND hk.namHoc = :namHoc
                GROUP BY hk.xepLoai";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':maLop'  => $maLop,
            ':hocKy'  => $hocKy,
            ':namHoc' => $namHoc
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số học sinh trong lớp - FIXED
     */
    public function getTongHocSinh($maLop, $namHoc = '2024-2025') {
        $sql = "SELECT COUNT(*) 
                FROM hocsinh hs
                JOIN phancong pc
                    ON hs.maHS = pc.maHS
                WHERE hs.maLop = :maLop
                  AND pc.maLop = :maLop
                  AND pc.namHoc = :namHoc
                  AND pc.trangThai = 'DangHoc'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':maLop'  => $maLop,
            ':namHoc' => $namHoc
        ]);

        return (int)$stmt->fetchColumn();
    }
}
?>