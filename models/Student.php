<?php
class Student {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll() {
        $sql = "SELECT hs.maHS, hs.dangThaiHocTap, hs.soBaoDanh, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, l.tenLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                LEFT JOIN LOP l ON pc.maLop = l.maLop
                ORDER BY pc.maLop, hs.maHS";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT hs.*, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, l.tenLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                LEFT JOIN LOP l ON pc.maLop = l.maLop
                WHERE hs.maHS = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($dataHocSinh, $dataNguoiDung) {
        try {
            $this->db->beginTransaction();

            // Thêm NGUOIDUNG
            $stmt1 = $this->db->prepare("INSERT INTO NGUOIDUNG(maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) 
                                       VALUES(:maNguoiDung, :hoVaTen, :gioiTinh, :ngaySinh, :diaChi, :soDienThoai, :email)");
            $stmt1->execute($dataNguoiDung);

            // Thêm HOCSINH
            $stmt2 = $this->db->prepare("INSERT INTO HOCSINH(maHS, dangThaiHocTap, soBaoDanh, maNguoiDung) 
                                       VALUES(:maHS, :dangThaiHocTap, :soBaoDanh, :maNguoiDung)");
            $stmt2->execute($dataHocSinh);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update($id, $dataHocSinh, $dataNguoiDung) {
        try {
            $this->db->beginTransaction();

            // Update NGUOIDUNG
            $stmt1 = $this->db->prepare("UPDATE NGUOIDUNG SET hoVaTen=:hoVaTen, gioiTinh=:gioiTinh, ngaySinh=:ngaySinh, 
                                       diaChi=:diaChi, soDienThoai=:soDienThoai, email=:email 
                                       WHERE maNguoiDung=:maNguoiDung");
            $stmt1->execute($dataNguoiDung);

            // Update HOCSINH
            $stmt2 = $this->db->prepare("UPDATE HOCSINH SET dangThaiHocTap=:dangThaiHocTap, soBaoDanh=:soBaoDanh 
                                       WHERE maHS=:maHS");
            $stmt2->execute($dataHocSinh);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->db->beginTransaction();
            
            // Lấy mã người dùng trước khi xóa
            $stmt = $this->db->prepare("SELECT maNguoiDung FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$id]);
            $maNguoiDung = $stmt->fetchColumn();
            
            // Xóa HOCSINH
            $stmt = $this->db->prepare("DELETE FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$id]);
            
            // Xóa NGUOIDUNG
            $stmt = $this->db->prepare("DELETE FROM NGUOIDUNG WHERE maNguoiDung = ?");
            $stmt->execute([$maNguoiDung]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function filter($maLop, $gioiTinh, $keyword) {
        $sql = "SELECT hs.maHS, hs.dangThaiHocTap, hs.soBaoDanh, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh, nd.diaChi, nd.soDienThoai, nd.email,
                       pc.maLop, l.tenLop, pc.maBan, pc.namHoc, pc.trangThai as trangThaiPhanCong
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN PHANCONG pc ON hs.maHS = pc.maHS AND pc.trangThai = 'DangHoc'
                LEFT JOIN LOP l ON pc.maLop = l.maLop
                WHERE 1=1";
        $params = [];
        
        if ($maLop) { 
            $sql .= " AND pc.maLop = ?"; 
            $params[] = $maLop; 
        }
        if ($gioiTinh) { 
            $sql .= " AND nd.gioiTinh = ?"; 
            $params[] = $gioiTinh; 
        }
        if ($keyword) { 
            $sql .= " AND (nd.hoVaTen LIKE ? OR hs.maHS LIKE ?)"; 
            $params[] = "%$keyword%"; 
            $params[] = "%$keyword%"; 
        }
        
        $sql .= " ORDER BY pc.maLop, hs.maHS";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}