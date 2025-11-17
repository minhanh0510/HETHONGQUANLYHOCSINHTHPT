<?php
class RoomAssignment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách phòng thi
    public function getAllRooms() {
        $sql = "SELECT * FROM PHONGTHI ORDER BY tenPhong";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách học sinh chưa được phân công
    public function getUnassignedStudents($roomId) {
        $sql = "SELECT hs.maHS, nd.hoVaTen 
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.maPhong IS NULL"; // Chỉ lấy học sinh chưa được phân công
        $stmt = $this->db->prepare($sql);
        $stmt->execute(); // Không cần truyền tham số vì không có dấu ?
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gán học sinh vào phòng thi
    public function assignStudentToRoom($studentId, $roomId) {
        $sql = "UPDATE HOCSINH SET maPhong = ? WHERE maHS = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$roomId, $studentId]);
    }

    // Cập nhật sức chứa phòng thi
    public function updateRoomCapacity($roomId) {
        $sql = "UPDATE PHONGTHI SET soLuongHienTai = soLuongHienTai + 1 WHERE maPhong = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$roomId]);
    }

    // Lấy thông tin phòng thi
    public function getRoomInfo($roomId) {
        $sql = "SELECT * FROM PHONGTHI WHERE maPhong = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>