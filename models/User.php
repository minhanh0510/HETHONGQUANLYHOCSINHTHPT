<?php
class User {
    private $db;
    public function __construct($db){ $this->db = $db; }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT tk.*, nd.hoVaTen, hs.maHS 
                                   FROM TAIKHOAN tk 
                                   JOIN NGUOIDUNG nd ON tk.maNguoiDung = nd.maNguoiDung 
                                   LEFT JOIN HOCSINH hs ON nd.maNguoiDung = hs.maNguoiDung 
                                   WHERE tk.tenDangNhap = ? AND tk.matKhau = MD5(?) AND tk.trangThai = 'HoatDong'");
        // Join with NGUOIDUNG to get display name
        $sql = "SELECT tk.*, nd.hoVaTen
                FROM TAIKHOAN tk
                LEFT JOIN NGUOIDUNG nd ON tk.maNguoiDung = nd.maNguoiDung
                WHERE tk.tenDangNhap = ? AND tk.trangThai = 'HoatDong'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        // Kiểm tra mật khẩu: chấp nhận plain-text hoặc MD5
        $inputMd5 = md5($password);
        if ($row['matKhau'] !== $password && $row['matKhau'] !== $inputMd5) {
            return false;
        }

        // Map database role names to application roles used in controllers/views
        $roleMap = [
            'QuanTri' => 'admin',
            'HocSinh' => 'student',
            'GiaoVien' => 'teacher',
            'PhuHuynh' => 'parent',
            'BanGiamHieu' => 'management',
            'SoGiaoDuc' => 'department'
        ];

        $appRole = $roleMap[$row['vaiTro']] ?? strtolower($row['vaiTro']);

        // Normalize returned user array so the rest of app can expect 'role' and 'ho_ten'
        $user = [
            'username' => $row['tenDangNhap'],
            'role' => $appRole,
            'ho_ten' => $row['hoVaTen'] ?? $row['tenDangNhap'],
            'email' => $row['email'] ?? null,
            'maNguoiDung' => $row['maNguoiDung'] ?? null,
            // keep raw db fields in case other controllers expect them
            'raw' => $row
        ];

        return $user;
        
        if ($user) {
            // Map vaiTro từ CSDL sang role trong hệ thống
            $roleMapping = [
                'QuanTri' => 'admin',
                'HocSinh' => 'student',
                'GiaoVien' => 'teacher',
                'PhuHuynh' => 'parent',
                'BanGiamHieu' => 'manager',
                'SoGiaoDuc' => 'department'
            ];
            
            $user['role'] = $roleMapping[$user['vaiTro']] ?? $user['vaiTro'];
            $user['student_id'] = $user['maHS'] ?? null;
            
            return $user;
        }
        return false;
    }
}