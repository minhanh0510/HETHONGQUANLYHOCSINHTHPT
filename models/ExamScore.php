<?php
class ExamScore {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách trường học
    public function getAllSchools() {
        $sql = "SELECT * FROM TRUONGHOC ORDER BY tenTruong";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách phòng thi theo trường
    public function getRoomsBySchool($schoolId) {
        $sql = "SELECT * FROM PHONGTHI WHERE maTruong = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schoolId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách học sinh theo phòng thi
    public function getStudentsByRoom($roomId) {
        $sql = "SELECT hs.maHS, nd.hoVaTen, pt.tenPhong, mh.tenMon 
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN PHONGTHI pt ON hs.maPhong = pt.maPhong
                JOIN MONHOC mh ON pt.maMon = mh.maMon
                LEFT JOIN diem_tuyensinh dt ON hs.maHS = dt.maHS AND dt.tenMon = mh.tenMon
                WHERE hs.maPhong = ? AND dt.diem IS NULL"; // Chỉ lấy học sinh chưa có điểm
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lưu điểm tuyển sinh
    public function saveScores($roomId, $scores) {
    try {
        $this->db->beginTransaction();

        // Lấy danh sách học sinh trong phòng thi để biết số báo danh
        $sql = "SELECT hs.maHS, hs.soBaoDanh, mh.tenMon 
                FROM hocsinh hs
                JOIN phongthi pt ON hs.maPhong = pt.maPhong
                JOIN monhoc mh ON pt.maMon = mh.maMon
                WHERE hs.maPhong = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roomId]);
        $studentsInRoom = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $mapSoBaoDanh = [];
        $mapTenMon = [];
        foreach ($studentsInRoom as $s) {
            $mapSoBaoDanh[$s['maHS']] = $s['soBaoDanh'];
            $mapTenMon[$s['maHS']] = $s['tenMon'];
        }

        // Lưu điểm cho từng học sinh
        foreach ($scores as $studentId => $score) {
            $soBaoDanh = $mapSoBaoDanh[$studentId] ?? null;
            $tenMon = $mapTenMon[$studentId] ?? null;
            if (!$soBaoDanh || !$tenMon) continue;

            $stmt = $this->db->prepare("
                INSERT INTO diem_tuyensinh (maHS, tenMon, soBaoDanh, diem)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE diem = VALUES(diem)
            ");
            $stmt->execute([$studentId, $tenMon, $soBaoDanh, $score]);
        }

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        error_log("Lỗi lưu điểm: " . $e->getMessage());
        return false;
    }
}

}
?>