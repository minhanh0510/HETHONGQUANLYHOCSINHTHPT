<?php
class Assignment {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // Học sinh chưa phân công
    public function getUnassigned() {
        $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.maHS NOT IN (SELECT maHS FROM PHANCONG WHERE trangThai = 'DangHoc')
                ORDER BY nd.hoVaTen";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Học sinh đã phân công
    public function getAssigned() {
        $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, pc.maLop, l.tenLop
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN PHANCONG pc ON hs.maHS = pc.maHS
                JOIN LOP l ON pc.maLop = l.maLop
                WHERE pc.trangThai = 'DangHoc'
                ORDER BY pc.maLop, nd.hoVaTen";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Phân công mới
    public function assignStudents($studentIds, $lop, $ban) {
        try {
            $this->db->beginTransaction();

            // Kiểm tra sĩ số lớp
            $stmt = $this->db->prepare("SELECT siSo FROM LOP WHERE maLop = ?");
            $stmt->execute([$lop]);
            $current = (int)$stmt->fetchColumn();

            if ($current + count($studentIds) > 40) {
                return "❌ Lớp đã đủ sĩ số, vui lòng chọn lớp khác.";
            }

            // Phân công học sinh
            foreach ($studentIds as $id) {
                $stmt = $this->db->prepare("INSERT INTO PHANCONG (maHS, maLop, maBan, namHoc, trangThai) 
                                          VALUES (?, ?, ?, '2024-2025', 'DangHoc')");
                $stmt->execute([$id, $lop, $ban]);
            }

            // Cập nhật sĩ số lớp
            $stmt = $this->db->prepare("UPDATE LOP SET siSo = siSo + ? WHERE maLop = ?");
            $stmt->execute([count($studentIds), $lop]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi phân công: " . $e->getMessage();
        }
    }

    // Điều chỉnh phân công
    public function updateAssignment($studentIds, $lop, $ban) {
        try {
            $this->db->beginTransaction();

            foreach ($studentIds as $id) {
                // Cập nhật phân công hiện tại
                $stmt = $this->db->prepare("UPDATE PHANCONG SET maLop = ?, maBan = ? WHERE maHS = ? AND trangThai = 'DangHoc'");
                $stmt->execute([$lop, $ban, $id]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi cập nhật: " . $e->getMessage();
        }
    }
}