<?php
class TeacherAssignment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách giáo viên CHƯA phân công chủ nhiệm
    public function getAvailableHomeRoomTeachers() {
        $sql = "SELECT gv.maGV, nd.hoVaTen, nd.email, nd.soDienThoai, gv.toChuyenMon, gv.monGiangDay
                FROM giaovien gv
                JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                WHERE gv.maGV NOT IN (
                    SELECT maGV FROM pcgvcn
                )
                ORDER BY nd.hoVaTen";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách giáo viên dạy môn cụ thể
    public function getTeachersBySubject($maMon) {
        $sql = "SELECT gv.maGV, nd.hoVaTen, nd.email, nd.soDienThoai, gv.toChuyenMon, gv.monGiangDay
                FROM giaovien gv
                JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                WHERE gv.monGiangDay = ?
                ORDER BY nd.hoVaTen";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maMon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra giáo viên đã được phân công môn này cho lớp khác chưa
    public function isTeacherAssignedToOtherClassForSubject($maGV, $maLop, $maMon) {
        $sql = "SELECT COUNT(*) FROM pcgvbm 
                WHERE maGV = ? AND maMon = ? AND maLop != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV, $maMon, $maLop]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy danh sách lớp học
    public function getAllClasses() {
        $sql = "SELECT l.maLop, l.tenLop, k.tenKhoi, l.siSo, l.maKhoi
                FROM lop l
                JOIN khoi k ON l.maKhoi = k.maKhoi
                ORDER BY l.maKhoi, l.tenLop";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách lớp CHƯA có giáo viên chủ nhiệm
    public function getAvailableClasses() {
        $sql = "SELECT l.maLop, l.tenLop, k.tenKhoi, l.siSo, l.maKhoi
                FROM lop l
                JOIN khoi k ON l.maKhoi = k.maKhoi
                WHERE l.maLop NOT IN (
                    SELECT maLop FROM pcgvcn
                )
                ORDER BY l.maKhoi, l.tenLop";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra lớp đã có giáo viên chủ nhiệm chưa
    public function hasHomeRoomTeacher($maLop) {
        $sql = "SELECT COUNT(*) FROM pcgvcn 
                WHERE maLop = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop]);
        return $stmt->fetchColumn() > 0;
    }

    // Phân công giáo viên chủ nhiệm
    public function assignHomeRoomTeacher($maGV, $maLop, $namHoc) {
        try {
            $this->db->beginTransaction();

            // Kiểm tra xem lớp đã có GVCN chưa
            if ($this->hasHomeRoomTeacher($maLop)) {
                $this->db->rollBack();
                return "❌ Lớp này đã có giáo viên chủ nhiệm";
            }

            // Thêm phân công mới
            $sql = "INSERT INTO pcgvcn (maGV, maLop, namHoc) 
                    VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maGV, $maLop, $namHoc]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi phân công: " . $e->getMessage();
        }
    }

    // Phân công giáo viên bộ môn
    public function assignSubjectTeacher($maGV, $maLop, $maMon, $namHoc) {
        try {
            $this->db->beginTransaction();

            // Kiểm tra xem đã phân công chưa
            $checkSql = "SELECT COUNT(*) FROM pcgvbm 
                         WHERE maGV = ? AND maLop = ? AND maMon = ? AND namHoc = ?";
            $stmt = $this->db->prepare($checkSql);
            $stmt->execute([$maGV, $maLop, $maMon, $namHoc]);
            
            if ($stmt->fetchColumn() > 0) {
                $this->db->rollBack();
                return "❌ Giáo viên đã được phân công môn này cho lớp";
            }

            // Thêm phân công mới
            $sql = "INSERT INTO pcgvbm (maGV, maLop, maMon, namHoc) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maGV, $maLop, $maMon, $namHoc]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi phân công: " . $e->getMessage();
        }
    }

    // Lấy danh sách môn học CHƯA phân công cho lớp
    public function getAvailableSubjectsForClass($maLop) {
        $sql = "SELECT mh.maMon, mh.tenMon 
                FROM monhoc mh
                WHERE mh.maMon NOT IN (
                    SELECT maMon FROM pcgvbm WHERE maLop = ?
                )
                ORDER BY mh.tenMon";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách môn học
    public function getAllSubjects() {
        $sql = "SELECT maMon, tenMon FROM monhoc ORDER BY tenMon";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách phân công hiện tại
    public function getCurrentAssignments($type = 'chunhiem') {
        if ($type === 'chunhiem') {
            $sql = "SELECT pc.*, nd.hoVaTen as tenGV, l.tenLop, k.tenKhoi
                    FROM pcgvcn pc
                    JOIN giaovien gv ON pc.maGV = gv.maGV
                    JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    JOIN lop l ON pc.maLop = l.maLop
                    JOIN khoi k ON l.maKhoi = k.maKhoi
                    ORDER BY k.maKhoi, l.tenLop";
        } else {
            $sql = "SELECT pc.*, nd.hoVaTen as tenGV, l.tenLop, k.tenKhoi, mh.tenMon
                    FROM pcgvbm pc
                    JOIN giaovien gv ON pc.maGV = gv.maGV
                    JOIN nguoidung nd ON gv.maNguoiDung = nd.maNguoiDung
                    JOIN lop l ON pc.maLop = l.maLop
                    JOIN khoi k ON l.maKhoi = k.maKhoi
                    JOIN monhoc mh ON pc.maMon = mh.maMon
                    ORDER BY k.maKhoi, l.tenLop, mh.tenMon";
        }
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hủy phân công giáo viên chủ nhiệm
    public function cancelHomeRoomTeacher($maPCGVCN) {
        try {
            $sql = "DELETE FROM pcgvcn WHERE maPCGVCN = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maPCGVCN]);
            return true;
        } catch (Exception $e) {
            return "❌ Lỗi khi hủy phân công: " . $e->getMessage();
        }
    }

    // Hủy phân công giáo viên bộ môn
    public function cancelSubjectTeacher($maPCGVBM) {
        try {
            $sql = "DELETE FROM pcgvbm WHERE maPCGVBM = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maPCGVBM]);
            return true;
        } catch (Exception $e) {
            return "❌ Lỗi khi hủy phân công: " . $e->getMessage();
        }
    }
}
?>