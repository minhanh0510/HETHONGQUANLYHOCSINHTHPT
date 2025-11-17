<?php
class Admission {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function dangKyTuyenSinh($data, $user) {
        try {
            $this->db->beginTransaction();

            // Validate dữ liệu
            $validation = $this->validateData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            // Tạo mã học sinh mới
            $maHS = 'HS' . date('Y') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $maNguoiDung = 'ND' . date('Y') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

            // 1. Thêm người dùng
            $sqlNguoiDung = "INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sqlNguoiDung);
            $stmt->execute([
                $maNguoiDung,
                trim($data['hoTenHS']),
                $data['gioiTinhHS'],
                $data['ngaySinhHS'],
                trim($data['diaChiHS']),
                $data['soDienThoaiHS'],
                trim($data['emailHS'])
            ]);

            // 2. Thêm học sinh
            $sqlHocSinh = "INSERT INTO HOCSINH (maHS, dangThaiHocTap, soBaoDanh, maNguoiDung) 
                          VALUES (?, 'Đang xét tuyển', NULL, ?)";
            $stmt = $this->db->prepare($sqlHocSinh);
            $stmt->execute([$maHS, $maNguoiDung]);

            // 3. Thêm hồ sơ tuyển sinh - THÊM TÔN GIÁO, DÂN TỘC
            $sqlHoSo = "INSERT INTO HOSOTUYENSINH (
                maHS, maKyTS, hoTenHS, gioiTinhHS, ngaySinhHS, 
                diaChiHS, soDienThoaiHS, emailHS, hoTenPH, soDienThoaiPH, 
                emailPH, quanHe, maPH, dangThai, tonGiao, danToc
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ChoXetTuyen', ?, ?)";

            $stmt = $this->db->prepare($sqlHoSo);
            $stmt->execute([
                $maHS,
                $data['maKyTS'],
                trim($data['hoTenHS']),
                $data['gioiTinhHS'],
                $data['ngaySinhHS'],
                trim($data['diaChiHS']),
                $data['soDienThoaiHS'],
                trim($data['emailHS']),
                trim($data['hoTenPH']),
                $data['soDienThoaiPH'],
                trim($data['emailPH']),
                $data['quanHe'],
                $user['parent_id'],
                $data['tonGiao'] ?? null,  // THÊM TÔN GIÁO
                $data['danToc'] ?? null    // THÊM DÂN TỘC
            ]);

            // 4. Lưu nguyện vọng
            $this->luuNguyenVong($maHS, $data['nguyenVong']);

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Đăng ký tuyển sinh thành công! Hồ sơ đã được ghi nhận với trạng thái "Chờ xét tuyển".',
                'maHS' => $maHS
            ];

        } catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false, 
                'message' => '❌ Lỗi hệ thống: ' . $e->getMessage()
            ];
        }
    }

    private function validateData($data) {
        // Kiểm tra thông tin bắt buộc
        $required = [
            'hoTenHS', 'gioiTinhHS', 'ngaySinhHS', 'diaChiHS',
            'hoTenPH', 'soDienThoaiPH', 'quanHe', 'danToc'  // THÊM DAN TỘC BẮT BUỘC
        ];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field]))) {
                return ['valid' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc.'];
            }
        }

        // Validate số điện thoại
        if (!preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', $data['soDienThoaiPH'])) {
            return ['valid' => false, 'message' => 'Số điện thoại không hợp lệ, vui lòng nhập lại!'];
        }

        // Validate email
        if (!empty($data['emailPH']) && !filter_var($data['emailPH'], FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Địa chỉ email không hợp lệ, vui lòng nhập lại'];
        }
        if (!empty($data['emailHS']) && !filter_var($data['emailHS'], FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Địa chỉ email không hợp lệ, vui lòng nhập lại'];
        }

        // Validate ngày sinh
        if (strtotime($data['ngaySinhHS']) > time()) {
            return ['valid' => false, 'message' => 'Ngày sinh không hợp lệ!'];
        }

        // Validate họ tên
        if (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/u', $data['hoTenHS'])) {
            return ['valid' => false, 'message' => 'Họ tên chỉ được chứa chữ cái, vui lòng nhập lại!'];
        }
        if (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/u', $data['hoTenPH'])) {
            return ['valid' => false, 'message' => 'Họ tên chỉ được chứa chữ cái, vui lòng nhập lại!'];
        }

        // Validate nguyện vọng - SỬA: kiểm tra trường + môn chuyên (KHÔNG CÒN khối)
        if (empty($data['nguyenVong']) || !is_array($data['nguyenVong'])) {
            return ['valid' => false, 'message' => 'Vui lòng chọn ít nhất một nguyện vọng!'];
        }

        $validNguyenVong = false;
        foreach ($data['nguyenVong'] as $nguyenVong) {
            // SỬA: kiểm tra trường + môn chuyên
            if (!empty($nguyenVong['truong']) && !empty($nguyenVong['monChuyen'])) {
                $validNguyenVong = true;
                break;
            }
        }

        if (!$validNguyenVong) {
            return ['valid' => false, 'message' => 'Vui lòng chọn ít nhất một nguyện vọng hợp lệ (trường + môn chuyên)!'];
        }

        return ['valid' => true];
    }

    private function luuNguyenVong($maHS, $nguyenVongList) {
        // SỬA SQL - BỎ maKhoi, THÊM monTuChon
        $sql = "INSERT INTO NGUYENVONG_TUYENSINH (maHS, maTruong, monChuyen, monTuChon, thuTuNguyenVong) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($nguyenVongList as $index => $nguyenVong) {
            // SỬA: kiểm tra trường + môn chuyên
            if (!empty($nguyenVong['truong']) && !empty($nguyenVong['monChuyen'])) {
                $stmt->execute([
                    $maHS,
                    $nguyenVong['truong'],
                    $nguyenVong['monChuyen'],
                    $nguyenVong['monTuChon'] ?? null,  // THÊM MÔN TỰ CHỌN
                    $index + 1
                ]);
            }
        }
    }

    public function getAllTruong() {
        $sql = "SELECT maTruong, tenTruong FROM TRUONGHOC ORDER BY tenTruong";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllKhoi() {
        $sql = "SELECT maKhoi, tenKhoi FROM KHOI ORDER BY maKhoi";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMonHoc() {
        $sql = "SELECT maMon, tenMon FROM MONHOC ORDER BY tenMon";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHoSoByPhuHuynh($maPH) {
        $sql = "SELECT hs.*, kts.tenKyTS, kts.namHoc 
                FROM HOSOTUYENSINH hs 
                JOIN KY_TUYENSINH kts ON hs.maKyTS = kts.maKyTS 
                WHERE hs.maPH = ? 
                ORDER BY hs.ngayDangKy DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPH]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}