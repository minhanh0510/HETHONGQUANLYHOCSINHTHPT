<?php
class User {
    private $db;
    public function __construct($db){ $this->db = $db; }

    public function login($username, $password) {
        // Query với JOIN để lấy thêm teacher_id, student_id, parent_id
        $sql = "SELECT tk.*, 
                       nd.hoVaTen, 
                       nd.email,
                       gv.maGV as teacher_id,
                       hs.maHS as student_id,
                       ph.maPH as parent_id
                FROM taikhoan tk
                JOIN nguoidung nd ON tk.maNguoiDung = nd.maNguoiDung
                LEFT JOIN giaovien gv ON nd.maNguoiDung = gv.maNguoiDung
                LEFT JOIN hocsinh hs ON nd.maNguoiDung = hs.maNguoiDung
                LEFT JOIN phuhuynh ph ON nd.maNguoiDung = ph.maNguoiDung
                WHERE tk.tenDangNhap = ? AND tk.trangThai = 'HoatDong'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            error_log("User not found: " . $username);
            return false;
        }

        // Debug: ghi log thông tin user tìm thấy
        error_log("User found: " . print_r($row, true));

        // Kiểm tra mật khẩu - THÊM DEBUG
        $inputMd5 = md5($password);
        error_log("Input password: " . $password);
        error_log("Input MD5: " . $inputMd5);
        error_log("Stored password: " . $row['matKhau']);

        if ($row['matKhau'] !== $password && $row['matKhau'] !== $inputMd5) {
            error_log("Password mismatch for user: " . $username);
            return false;
        }

        // Sử dụng trực tiếp vaiTro từ TAIKHOAN - THÊM DEBUG
        error_log("User role from DB: " . $row['vaiTro']);

        $roleMap = [
            'QuanTri' => 'admin',
            'HocSinh' => 'student',
            'GiaoVien' => 'teacher',
            'PhuHuynh' => 'parent',
            'BanGiamHieu' => 'management',
            'SoGiaoDuc' => 'department'
        ];

        $appRole = $roleMap[$row['vaiTro']] ?? 'unknown';
        error_log("Mapped role: " . $appRole);
        $user = [
            'username' => $row['tenDangNhap'],
            'role' => $appRole,
            'hoVaTen' => $row['hoVaTen'] ?? $row['tenDangNhap'],
            'email' => $row['email'] ?? null,
            'maNguoiDung' => $row['maNguoiDung'] ?? null,
            'teacher_id' => $row['teacher_id'] ?? null, 
            'student_id' => $row['student_id'] ?? null, 
            'parent_id' => $row['parent_id'] ?? null,   
            'raw' => $row
        ];

        error_log("Final user array: " . print_r($user, true));
        return $user;
    }
}
?>