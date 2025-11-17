<?php
class Notification {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Lấy danh sách thông báo theo đối tượng
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
            // Bạn có thể log lỗi thay vì echo
            error_log("Lỗi khi lấy thông báo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách thông báo theo đối tượng
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
            // Bạn có thể log lỗi thay vì echo
            error_log("Lỗi khi lấy thông báo: " . $e->getMessage());
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
}
