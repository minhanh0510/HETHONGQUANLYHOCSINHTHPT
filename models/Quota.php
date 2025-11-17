<?php
class Quota {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAllSchools() {
        $sql = "SELECT t.*, 
                       COALESCE(ct.soHocSinh, 0) as chiTieuNamTruoc,
                       COALESCE(ct.soLopHoc, 0) as soLopHienTai
                FROM TRUONGHOC t
                LEFT JOIN CHITIEU ct ON t.maTruong = ct.maTruong 
                WHERE ct.namHoc = YEAR(CURDATE()) - 1 OR ct.maChiTieu IS NULL
                ORDER BY t.tenTruong";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentYearQuota($maTruong) {
        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $stmt = $this->db->prepare("SELECT * FROM CHITIEU WHERE maTruong = ? AND namHoc = ?");
        $stmt->execute([$maTruong, $currentYear]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveQuota($data) {
        try {
            $this->db->beginTransaction();
            
            $currentYear = date('Y') . '-' . (date('Y') + 1);
            
            foreach ($data as $maTruong => $quota) {
                $existing = $this->getCurrentYearQuota($maTruong);
                
                if ($existing) {
                    $stmt = $this->db->prepare("UPDATE CHITIEU SET soHocSinh = ?, soLopHoc = ? 
                                              WHERE maChiTieu = ?");
                    $stmt->execute([$quota['soHocSinh'], $quota['soLopHoc'], $existing['maChiTieu']]);
                } else {
                    $stmt = $this->db->prepare("INSERT INTO CHITIEU (maTruong, namHoc, soHocSinh, soLopHoc) 
                                              VALUES (?, ?, ?, ?)");
                    $stmt->execute([$maTruong, $currentYear, $quota['soHocSinh'], $quota['soLopHoc']]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function validateQuotaData($data) {
        $errors = [];
        
        foreach ($data as $maTruong => $quota) {
            if (empty($quota['soHocSinh']) || empty($quota['soLopHoc'])) {
                $errors[] = "Trường $maTruong: Không được để trống số học sinh hoặc số lớp";
                continue;
            }
            
            if (!is_numeric($quota['soHocSinh']) || !is_numeric($quota['soLopHoc']) ||
                $quota['soHocSinh'] <= 0 || $quota['soLopHoc'] <= 0 ||
                floor($quota['soHocSinh']) != $quota['soHocSinh'] || 
                floor($quota['soLopHoc']) != $quota['soLopHoc']) {
                $errors[] = "Trường $maTruong: Số học sinh và số lớp phải là số nguyên dương";
            }
        }
        
        return $errors;
    }
}
?>