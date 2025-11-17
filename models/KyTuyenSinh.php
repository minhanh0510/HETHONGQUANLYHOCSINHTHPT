<?php
class KyTuyenSinh {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function getKyDangMo() {
        $sql = "SELECT * FROM KY_TUYENSINH 
                WHERE trangThai = 'DangMo' 
                AND ngayBatDau <= CURDATE() 
                AND ngayKetThuc >= CURDATE() 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy TẤT CẢ kỳ tuyển sinh
    public function getAllKyTuyenSinh() {
        $sql = "SELECT * FROM KY_TUYENSINH 
                ORDER BY 
                    CASE 
                        WHEN trangThai = 'DangMo' THEN 1 
                        ELSE 2 
                    END,
                    namHoc DESC, 
                    ngayBatDau DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($maKyTS) {
        $sql = "SELECT * FROM KY_TUYENSINH WHERE maKyTS = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maKyTS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>