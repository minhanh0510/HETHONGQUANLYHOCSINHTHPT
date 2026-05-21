<?php

class FeedbackEvaluation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Danh sách lớp
    public function getClasses($tenDangNhap) {
        $sql = "SELECT DISTINCT l.maLop, l.tenLop
                FROM PCGVBM p
                INNER JOIN LOP l ON p.maLop = l.maLop
                WHERE p.maGV = :tenDangNhap
                ORDER BY l.tenLop";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenDangNhap', $tenDangNhap);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Danh sách học sinh theo lớp
    public function getStudentsByClass($classId) {
        $sql = "SELECT hs.maHS, nd.hoVaTen
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.maLop = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lưu nhận xét
    public function saveFeedback($studentId, $nhanXet, $danhGia) {
        $sql = "INSERT INTO NHANXETDANHGIA (maHS, nhanXet, danhGia, ngayThucHien)
                VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$studentId, $nhanXet, $danhGia]);
    }
}
?>
