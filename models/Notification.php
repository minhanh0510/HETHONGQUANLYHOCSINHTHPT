<?php
class Notification {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy danh sách thông báo cho học sinh
     * @param string $doiTuong (HocSinh, PhuHuynh, TatCa,...)
     * @return array
     */
    public function getNotificationsFor($doiTuong) {
        try {
            $query = "SELECT * FROM thongbao 
                      WHERE doiTuong = :doiTuong OR doiTuong = 'TatCa' OR doiTuong = 'HocSinh'
                      ORDER BY ngayGui DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['doiTuong' => $doiTuong]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy thông báo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách thông báo cho phụ huynh
     * @param string $doiTuong (HocSinh, PhuHuynh, TatCa,...)
     * @return array
     */
    public function getNotificationsFor_parent($doiTuong) {
        try {
            $query = "SELECT * FROM thongbao 
                      WHERE doiTuong = :doiTuong OR doiTuong = 'TatCa' OR doiTuong = 'PhuHuynh'
                      ORDER BY ngayGui DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['doiTuong' => $doiTuong]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy thông báo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ MỚI: Lấy danh sách thông báo cho giáo viên
     * @return array
     */
    public function getNotificationsForTeacher() {
        try {
            $query = "SELECT 
                        t.maThongBao,
                        t.tieuDe,
                        t.noiDung,
                        t.moTa,
                        t.huongDanThucHien,
                        t.luuY,
                        t.ngayGui,
                        t.doiTuong,
                        COALESCE(n.hoVaTen, 'Hệ thống') as nguoiGui
                      FROM thongbao t
                      LEFT JOIN nguoidung n ON t.nguoiGui = n.maNguoiDung
                      WHERE t.doiTuong IN ('TatCa', 'GiaoVien')
                      ORDER BY t.ngayGui DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy thông báo giáo viên: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông báo theo ID
     * @param int $id
     * @return array|false
     */
    public function getNotificationById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM thongbao WHERE maThongBao = :id");
            $stmt->execute(['id' => $id]);
            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
            return $notification ?: false; // trả về false nếu không tìm thấy
        } catch (PDOException $e) {
            error_log("Lỗi getById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ MỚI: Lấy chi tiết thông báo với thông tin người gửi
     * @param int $id
     * @return array|false
     */
    public function getNotificationDetailById($id) {
        try {
            $query = "SELECT 
                        t.maThongBao,
                        t.tieuDe,
                        t.noiDung,
                        t.moTa,
                        t.huongDanThucHien,
                        t.luuY,
                        t.ngayGui,
                        t.doiTuong,
                        COALESCE(n.hoVaTen, 'Hệ thống') as nguoiGui
                      FROM thongbao t
                      LEFT JOIN nguoidung n ON t.nguoiGui = n.maNguoiDung
                      WHERE t.maThongBao = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
            return $notification ?: false;
        } catch (PDOException $e) {
            error_log("Lỗi getNotificationDetailById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ MỚI: Đếm số thông báo mới (3 ngày gần đây)
     * @param string $doiTuong
     * @return int
     */
    public function getNewNotificationsCount($doiTuong) {
        try {
            $query = "SELECT COUNT(*) as count
                      FROM thongbao
                      WHERE (doiTuong = :doiTuong OR doiTuong = 'TatCa')
                      AND ngayGui >= DATE_SUB(NOW(), INTERVAL 3 DAY)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['doiTuong' => $doiTuong]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (PDOException $e) {
            error_log("Lỗi đếm thông báo mới: " . $e->getMessage());
            return 0;
        }
    }
}
?>