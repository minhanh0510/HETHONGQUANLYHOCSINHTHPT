<?php
class Account {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Tạo mã NDxxx tự động
    private function generateMaNguoiDung() {
        $sql = "SELECT maNguoiDung FROM nguoidung ORDER BY maNguoiDung DESC LIMIT 1";
        $stmt = $this->db->query($sql);

        if ($stmt->rowCount() > 0) {
            $last = $stmt->fetch()['maNguoiDung'];
            $number = intval(substr($last, 9)) + 1; // vì prefix là "NguoiDung"
        } else {
            $number = 1;
        }

        return "NguoiDung" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

    // Tạo người dùng trong bảng nguoidung
    private function createNguoiDung($hoVaTen, $gioiTinh, $ngaySinh, $diaChi, $soDienThoai, $email) {
        $maNguoiDung = $this->generateMaNguoiDung();

        $sql = "INSERT INTO nguoidung 
                (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email, ngayTao)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $maNguoiDung,
            $hoVaTen,
            $gioiTinh,
            $ngaySinh,
            $diaChi,
            $soDienThoai,
            $email
        ]);

        return $maNguoiDung;
    }

    // Tạo tài khoản trong bảng taikhoan
    public function createAccount($username, $password, $role, $hoVaTen, $gioiTinh, $ngaySinh, $diaChi, $soDienThoai, $email) {

        // Map role form sang ENUM hợp lệ MySQL
        $roleMap = [
            "admin"      => "QuanTri",
            "department" => "SoGiaoDuc",
            "teacher"    => "GiaoVien",
            "student"    => "HocSinh",
            "parent"     => "PhuHuynh",
            "management" => "BanGiamHieu"
        ];

        $enumRole = $roleMap[$role] ?? null;
        if (!$enumRole) {
            return false; // role không hợp lệ
        }

        error_log("Role mapped to ENUM: " . $enumRole);

        // Kiểm tra username tồn tại
        if ($this->isUsernameExists($username)) {
            return "duplicated_username";
        }

        // Tạo người dùng
        $maNguoiDung = $this->createNguoiDung($hoVaTen, $gioiTinh, $ngaySinh, $diaChi, $soDienThoai, $email);

        // Insert vào tài khoản (mật khẩu lưu nguyên)
        $sqlTK = "INSERT INTO taikhoan
                  (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung, ngayTao)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt2 = $this->db->prepare($sqlTK);
        $result = $stmt2->execute([
            $username,
            $password, // Lưu mật khẩu nguyên
            $enumRole,
            'HoatDong',
            $email,
            $maNguoiDung
        ]);

        error_log("Role inserted into database: " . $enumRole);

        return $result;
    }

    private function isUsernameExists($username) {
        $sql = "SELECT tenDangNhap FROM taikhoan WHERE tenDangNhap = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->rowCount() > 0;
    }
}
?>
