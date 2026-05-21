<?php
class Exam {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy lịch thi của học sinh dựa trên LỚP của học sinh
     * Sử dụng bảng lichthilop (mỗi lớp có lịch thi riêng, buổi sáng/chiều)
     * Phòng thi lấy từ bảng phongthi
     */
    public function getByStudent($maHS, $filters = []) {
        $sql = "SELECT 
                    lt.maLichThi,
                    lt.maLop,
                    l.tenLop,
                    lt.maMon,
                    mh.tenMon AS mon_thi,
                    lt.ngayThi,
                    lt.buoiThi,
                    lt.gioBatDau,
                    lt.gioKetThuc,
                    lt.maPhong,
                    pt.tenPhong AS phong,
                    lt.hocKy,
                    lt.namHoc,
                    lt.ghiChu,
                    hs.maHS,
                    nd.hoVaTen,
                    CONCAT(TIME_FORMAT(lt.gioBatDau, '%H:%i'), '-', TIME_FORMAT(lt.gioKetThuc, '%H:%i')) AS caThi
                FROM lichthilop lt
                JOIN lop l ON lt.maLop = l.maLop
                JOIN monhoc mh ON lt.maMon = mh.maMon
                JOIN hocsinh hs ON hs.maLop = lt.maLop
                JOIN nguoidung nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN phongthi pt ON lt.maPhong = pt.maPhong
                WHERE hs.maHS = ?";

        $params = [$maHS];

        // Lọc theo tên môn (tenMon)
        if (!empty($filters['mon_thi'])) {
            $sql .= " AND mh.tenMon = ?";
            $params[] = $filters['mon_thi'];
        }

        // Lọc theo ngày thi
        if (!empty($filters['ngay_thi'])) {
            $sql .= " AND lt.ngayThi = ?";
            $params[] = $filters['ngay_thi'];
        }

        // Lọc theo phòng thi (dùng tenPhong từ bảng phongthi)
        if (!empty($filters['phong'])) {
            $sql .= " AND pt.tenPhong = ?";
            $params[] = $filters['phong'];
        }

        $sql .= " ORDER BY lt.ngayThi, lt.gioBatDau, mh.tenMon";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Danh sách môn thi (để fill combobox) - lấy từ bảng lichthilop
    public function getDistinctMon() {
        $sql = "SELECT DISTINCT mh.tenMon AS mon_thi 
                FROM lichthilop lt
                JOIN monhoc mh ON lt.maMon = mh.maMon
                ORDER BY mh.tenMon";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Danh sách phòng thi (để fill combobox) - lấy từ bảng phongthi
    public function getDistinctPhong() {
        $sql = "SELECT DISTINCT pt.tenPhong AS phong 
                FROM lichthilop lt
                JOIN phongthi pt ON lt.maPhong = pt.maPhong
                WHERE lt.maPhong IS NOT NULL
                ORDER BY pt.tenPhong";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm số ngày thi của học sinh
    public function countNgayThi($maHS) {
        $sql = "SELECT COUNT(DISTINCT lt.ngayThi) AS count
                FROM lichthilop lt
                JOIN hocsinh hs ON hs.maLop = lt.maLop
                WHERE hs.maHS = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }

    // Đếm số tiết thi hôm nay của học sinh
    public function countTietHomNay($maHS) {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) AS count
                FROM lichthilop lt
                JOIN hocsinh hs ON hs.maLop = lt.maLop
                WHERE hs.maHS = ?
                  AND lt.ngayThi = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS, $today]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }
}
