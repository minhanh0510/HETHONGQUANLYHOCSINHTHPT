<?php
class Exam {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy danh sách phòng thi theo học sinh + bộ lọc
     */
    public function getByStudent($maHS, $filters = []) {
        $sql = "
            SELECT 
                dst.maDSThi,
                dst.soBaoDanh,
                hs.maHS,
                nd.hoVaTen,
                pt.tenPhong          AS phong,
                pt.maPhong,
                pcgt.ngayThi         AS ngayThi,
                pcgt.caThi           AS caThi,
                mh.tenMon            AS mon_thi,
                mh.maMon
            FROM danhsachthi dst
            JOIN hocsinh hs       ON dst.maHS    = hs.maHS
            JOIN nguoidung nd     ON hs.maNguoiDung = nd.maNguoiDung
            JOIN phongthi pt      ON dst.maPhong = pt.maPhong
            LEFT JOIN pcgiamthi pcgt ON pt.maPhong = pcgt.maPhong
            JOIN monhoc mh        ON pt.maMon    = mh.maMon
            WHERE dst.maHS = ?
              AND dst.trangThai = 'DaXepPhong'
        ";

        $params = [$maHS];

        // Lọc theo môn thi (tên môn)
        if (!empty($filters['mon_thi'])) {
            $sql .= " AND mh.tenMon = ?";
            $params[] = $filters['mon_thi'];
        }

        // Lọc theo ngày thi
        if (!empty($filters['ngay_thi'])) {
            $sql .= " AND pcgt.ngayThi = ?";
            $params[] = $filters['ngay_thi'];
        }

        // Lọc theo phòng thi (tên phòng)
        if (!empty($filters['phong'])) {
            $sql .= " AND pt.tenPhong = ?";
            $params[] = $filters['phong'];
        }

        $sql .= " ORDER BY pcgt.ngayThi, pcgt.caThi, mh.tenMon";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Combobox môn thi: lấy TẤT CẢ môn học
     */
    public function getDistinctMon() {
        $sql = "
            SELECT mh.tenMon AS mon_thi
            FROM monhoc mh
            ORDER BY mh.tenMon
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Combobox phòng thi: lấy TẤT CẢ phòng học
     */
    public function getDistinctPhong() {
        $sql = "
            SELECT ph.tenPhong AS phong
            FROM phonghoc ph
            ORDER BY ph.tenPhong
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countNgayThi($maHS) {
        $sql = "
            SELECT COUNT(DISTINCT pcgt.ngayThi) AS count
            FROM danhsachthi dst
            JOIN pcgiamthi pcgt ON dst.maPhong = pcgt.maPhong
            WHERE dst.maHS = ?
              AND dst.trangThai = 'DaXepPhong'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    public function countTietHomNay($maHS) {
        $today = date('Y-m-d');
        $sql = "
            SELECT COUNT(*) AS count
            FROM danhsachthi dst
            JOIN pcgiamthi pcgt ON dst.maPhong = pcgt.maPhong
            WHERE dst.maHS = ?
              AND dst.trangThai = 'DaXepPhong'
              AND pcgt.ngayThi = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS, $today]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }
}
