<?php
class Exam {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách phòng thi của học sinh
    public function getByStudent($maHS, $filters = []) {
        $sql = "SELECT dst.maDSThi, dst.soBaoDanh, pt.tenPhong as phong, 
                       mh.tenMon as mon_thi, pcgt.ngayThi, pcgt.caThi,
                       hs.maHS, nd.hoVaTen
                FROM DANHSACHTHI dst
                JOIN PHONGTHI pt ON dst.maPhong = pt.maPhong
                JOIN HOCSINH hs ON dst.maHS = hs.maHS
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                JOIN PCGIAMTHI pcgt ON dst.maPhong = pcgt.maPhong
                JOIN MONHOC mh ON pcgt.maGV = mh.maMon  -- Giả định môn học từ giáo viên
                WHERE dst.maHS = ? AND dst.trangThai = 'DaXepPhong'";
        
        $params = [$maHS];

        if (!empty($filters['mon_thi'])) {
            $sql .= " AND mh.tenMon LIKE ?";
            $params[] = "%" . $filters['mon_thi'] . "%";
        }
        if (!empty($filters['ngay_thi'])) {
            $sql .= " AND pcgt.ngayThi = ?";
            $params[] = $filters['ngay_thi'];
        }
        if (!empty($filters['phong'])) {
            $sql .= " AND pt.tenPhong LIKE ?";
            $params[] = "%" . $filters['phong'] . "%";
        }

        $sql .= " ORDER BY pcgt.ngayThi, pcgt.caThi";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctMon($maHS) {
        $stmt = $this->db->prepare("SELECT DISTINCT mh.tenMon as mon_thi
                                   FROM DANHSACHTHI dst
                                   JOIN PCGIAMTHI pcgt ON dst.maPhong = pcgt.maPhong
                                   JOIN MONHOC mh ON pcgt.maGV = mh.maMon
                                   WHERE dst.maHS = ? AND dst.trangThai = 'DaXepPhong'
                                   ORDER BY mh.tenMon");
        $stmt->execute([$maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctPhong($maHS) {
        $stmt = $this->db->prepare("SELECT DISTINCT pt.tenPhong as phong
                                   FROM DANHSACHTHI dst
                                   JOIN PHONGTHI pt ON dst.maPhong = pt.maPhong
                                   WHERE dst.maHS = ? AND dst.trangThai = 'DaXepPhong'
                                   ORDER BY pt.tenPhong");
        $stmt->execute([$maHS]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countNgayThi($maHS) {
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT pcgt.ngayThi) AS count
                                   FROM DANHSACHTHI dst
                                   JOIN PCGIAMTHI pcgt ON dst.maPhong = pcgt.maPhong
                                   WHERE dst.maHS = ? AND dst.trangThai = 'DaXepPhong'");
        $stmt->execute([$maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }

    public function countTietHomNay($maHS) {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count
                                   FROM DANHSACHTHI dst
                                   JOIN PCGIAMTHI pcgt ON dst.maPhong = pcgt.maPhong
                                   WHERE dst.maHS = ? AND pcgt.ngayThi = ? AND dst.trangThai = 'DaXepPhong'");
        $stmt->execute([$maHS, $today]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }
}