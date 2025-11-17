<?php
class Exam {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách phòng thi của 1 học sinh
    public function getByStudent($maHS, $filters = []) {
        $sql = "SELECT 
                    dst.maDSThi,
                    dst.soBaoDanh,
                    pt.maPhong,
                    pt.tenPhong      AS phong,
                    pcgt.ngayThi,
                    pcgt.caThi,
                    hs.maHS,
                    nd.hoVaTen,
                    mh.maMon,
                    mh.tenMon        AS mon_thi
                FROM danhsachthi dst
                JOIN phongthi   pt   ON dst.maPhong = pt.maPhong
                JOIN pcgiamthi  pcgt ON dst.maPhong = pcgt.maPhong
                JOIN hocsinh    hs   ON dst.maHS = hs.maHS
                JOIN nguoidung  nd   ON hs.maNguoiDung = nd.maNguoiDung
                JOIN monhoc     mh   ON pt.maMon = mh.maMon
                WHERE dst.maHS = ? 
                  AND dst.trangThai = 'DaXepPhong'";

        $params = [$maHS];

        // Lọc theo tên môn (tenMon)
        if (!empty($filters['mon_thi'])) {
            $sql .= " AND mh.tenMon = ?";
            $params[] = $filters['mon_thi'];
        }

        // Lọc theo ngày thi
        if (!empty($filters['ngay_thi'])) {
            $sql .= " AND pcgt.ngayThi = ?";
            $params[] = $filters['ngay_thi'];
        }

        // Lọc theo tên phòng thi
        if (!empty($filters['phong'])) {
            $sql .= " AND pt.tenPhong = ?";
            $params[] = $filters['phong'];
        }

        $sql .= " ORDER BY pcgt.ngayThi, pcgt.caThi, pt.tenPhong";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Danh sách môn thi (để fill combobox)
public function getDistinctMon() {
    $sql = "SELECT tenMon AS mon_thi FROM MONHOC ORDER BY tenMon";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getDistinctPhong() {
    $sql = "SELECT tenPhong AS phong FROM PHONGTHI ORDER BY tenPhong";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function countNgayThi($maHS) {
        $sql = "SELECT COUNT(DISTINCT pcgt.ngayThi) AS count
                FROM danhsachthi dst
                JOIN pcgiamthi pcgt ON dst.maPhong = pcgt.maPhong
                WHERE dst.maHS = ? 
                  AND dst.trangThai = 'DaXepPhong'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    public function countTietHomNay($maHS) {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) AS count
                FROM danhsachthi dst
                JOIN pcgiamthi pcgt ON dst.maPhong = pcgt.maPhong
                WHERE dst.maHS = ?
                  AND pcgt.ngayThi = ?
                  AND dst.trangThai = 'DaXepPhong'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS, $today]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }
}
