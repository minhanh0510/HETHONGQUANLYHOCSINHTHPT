<?php
class Student {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // Lấy tất cả học sinh, JOIN với NGUOIDUNG để lấy thông tin cá nhân
    public function getAll() {
        $sql = "SELECT hs.maHS, hs.dangThaiHocTap, hs.soBaoDanh, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                ORDER BY pc.maLop, hs.maHS";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT hs.*, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                WHERE hs.maHS = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm học sinh: cần thêm vào cả NGUOIDUNG và HOCSINH
    public function add($dataHocSinh, $dataNguoiDung, $dataPhanCong = null) {
        // Thêm NGUOIDUNG
        $stmt1 = $this->db->prepare("INSERT INTO NGUOIDUNG(maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES(:maNguoiDung, :hoVaTen, :gioiTinh, :ngaySinh, :diaChi, :soDienThoai, :email)");
        $stmt1->execute($dataNguoiDung);
        // Thêm HOCSINH
        $stmt2 = $this->db->prepare("INSERT INTO HOCSINH(maHS, dangThaiHocTap, soBaoDanh, maNguoiDung) VALUES(:maHS, :dangThaiHocTap, :soBaoDanh, :maNguoiDung)");
        $stmt2->execute($dataHocSinh);
        // Thêm PHANCONG nếu có
        if ($dataPhanCong) {
            $stmt3 = $this->db->prepare("INSERT INTO PHANCONG(maHS, maLop, maBan, namHoc, trangThai) VALUES(:maHS, :maLop, :maBan, :namHoc, :trangThai)");
            return $stmt3->execute($dataPhanCong);
        }
        return true;
    }

    // Sửa học sinh: cập nhật cả NGUOIDUNG và HOCSINH
    public function update($id, $dataHocSinh, $dataNguoiDung, $dataPhanCong = null) {
        // Update NGUOIDUNG
        $stmt1 = $this->db->prepare("UPDATE NGUOIDUNG SET hoVaTen=:hoVaTen, gioiTinh=:gioiTinh, ngaySinh=:ngaySinh, diaChi=:diaChi, soDienThoai=:soDienThoai, email=:email WHERE maNguoiDung=:maNguoiDung");
        $stmt1->execute($dataNguoiDung);
        // Update HOCSINH
        $stmt2 = $this->db->prepare("UPDATE HOCSINH SET dangThaiHocTap=:dangThaiHocTap, soBaoDanh=:soBaoDanh WHERE maHS=:maHS");
        $stmt2->execute($dataHocSinh);
        // Update PHANCONG nếu có
        if ($dataPhanCong) {
            $stmt3 = $this->db->prepare("UPDATE PHANCONG SET maLop=:maLop, maBan=:maBan, namHoc=:namHoc, trangThai=:trangThai WHERE maHS=:maHS AND trangThai='DangHoc'");
            return $stmt3->execute($dataPhanCong);
        }
        return true;
    }

    public function delete($id) {
        // Xóa HOCSINH, sẽ tự động xóa NGUOIDUNG nếu dùng ON DELETE CASCADE
        $stmt = $this->db->prepare("DELETE FROM HOCSINH WHERE maHS = ?");
        return $stmt->execute([$id]);
    }

    // Lọc học sinh theo lớp, giới tính, từ khóa
    public function filter($maLop, $gioiTinh, $keyword) {
        $sql = "SELECT hs.maHS, hs.dangThaiHocTap, hs.soBaoDanh, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                WHERE 1=1";
        $params = [];
        if ($maLop) { $sql .= " AND pc.maLop = ?"; $params[] = $maLop; }
        if ($gioiTinh) { $sql .= " AND nd.gioiTinh = ?"; $params[] = $gioiTinh; }
        if ($keyword) { $sql .= " AND (nd.hoVaTen LIKE ? OR hs.maHS LIKE ?)"; $params[] = "%$keyword%"; $params[] = "%$keyword%"; }
        $sql .= " ORDER BY pc.maLop, hs.maHS";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}