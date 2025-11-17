<?php
class Student {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy toàn bộ hồ sơ + thông tin lớp/khoi/ban
    public function getAll() {
        $sql = "SELECT 
                    hs.maHS,
                    hs.dangThaiHocTap,
                    hs.soBaoDanh,
                    hs.maNguoiDung,
                    hs.maPhong,
                    hs.maLop,
                    nd.hoVaTen,
                    nd.gioiTinh,
                    nd.ngaySinh,
                    nd.diaChi,
                    nd.soDienThoai,
                    nd.email,
                    l.tenLop,
                    l.maKhoi,
                    k.tenKhoi,
                    l.maBan
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN LOP l       ON hs.maLop = l.maLop
                LEFT JOIN KHOI k      ON l.maKhoi = k.maKhoi
                ORDER BY l.maKhoi, l.tenLop, hs.maHS";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($maHS) {
        $sql = "SELECT 
                    hs.maHS,
                    hs.dangThaiHocTap,
                    hs.soBaoDanh,
                    hs.maNguoiDung,
                    hs.maPhong,
                    hs.maLop,
                    nd.hoVaTen,
                    nd.gioiTinh,
                    nd.ngaySinh,
                    nd.diaChi,
                    nd.soDienThoai,
                    nd.email,
                    l.tenLop,
                    l.maKhoi,
                    k.tenKhoi,
                    l.maBan
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN LOP l  ON hs.maLop = l.maLop
                LEFT JOIN KHOI k ON l.maKhoi = k.maKhoi
                WHERE hs.maHS = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maHS]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm hồ sơ mới (SBD tự sinh, lớp/phòng có thể NULL)
public function add($dataHocSinh, $dataNguoiDung) {
    try {
        $this->db->beginTransaction();

        // Thêm NGUOIDUNG
        $stmt1 = $this->db->prepare("
            INSERT INTO NGUOIDUNG(maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email)
            VALUES(:maNguoiDung, :hoVaTen, :gioiTinh, :ngaySinh, :diaChi, :soDienThoai, :email)
        ");
        $stmt1->execute($dataNguoiDung);

        // Thêm HOCSINH
        // HOCSINH: maHS, dangThaiHocTap, soBaoDanh, maNguoiDung, maPhong, maLop
        $stmt2 = $this->db->prepare("
            INSERT INTO HOCSINH(maHS, dangThaiHocTap, soBaoDanh, maNguoiDung, maPhong, maLop)
            VALUES(:maHS, :dangThaiHocTap, :soBaoDanh, :maNguoiDung, NULL, NULL)
        ");
        $stmt2->execute($dataHocSinh);

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        return false;
    }
}


    // Cập nhật hồ sơ (không đổi maHS, maNguoiDung)
  public function update($maHS, $dataHocSinh, $dataNguoiDung) {
    try {
        $this->db->beginTransaction();

        // Update bảng NGUOIDUNG
        $stmt1 = $this->db->prepare("
            UPDATE NGUOIDUNG 
            SET hoVaTen     = :hoVaTen,
                gioiTinh    = :gioiTinh,
                ngaySinh    = :ngaySinh,
                diaChi      = :diaChi,
                soDienThoai = :soDienThoai,
                email       = :email
            WHERE maNguoiDung = :maNguoiDung
        ");
        $stmt1->execute($dataNguoiDung);

        // Update bảng HOCSINH (chỉ trạng thái + SBD)
        $stmt2 = $this->db->prepare("
            UPDATE HOCSINH 
            SET dangThaiHocTap = :dangThaiHocTap,
                soBaoDanh      = :soBaoDanh
            WHERE maHS = :maHS
        ");
        $stmt2->execute([
            'dangThaiHocTap' => $dataHocSinh['dangThaiHocTap'],
            'soBaoDanh'      => $dataHocSinh['soBaoDanh'],
            'maHS'           => $maHS
        ]);

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        return false;
    }
}


    public function delete($maHS) {
        try {
            $this->db->beginTransaction();

            // Lấy mã người dùng
            $stmt = $this->db->prepare("SELECT maNguoiDung FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$maHS]);
            $maNguoiDung = $stmt->fetchColumn();

            // Xóa HOCSINH
            $stmt = $this->db->prepare("DELETE FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$maHS]);

            // Xóa NGUOIDUNG
            if ($maNguoiDung) {
                $stmt = $this->db->prepare("DELETE FROM NGUOIDUNG WHERE maNguoiDung = ?");
                $stmt->execute([$maNguoiDung]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Lọc theo lớp, giới tính, từ khóa
    public function filter($maLop, $gioiTinh, $keyword) {
        $sql = "SELECT 
                    hs.maHS,
                    hs.dangThaiHocTap,
                    hs.soBaoDanh,
                    hs.maNguoiDung,
                    hs.maPhong,
                    hs.maLop,
                    nd.hoVaTen,
                    nd.gioiTinh,
                    nd.ngaySinh,
                    nd.diaChi,
                    nd.soDienThoai,
                    nd.email,
                    l.tenLop,
                    l.maKhoi,
                    k.tenKhoi,
                    l.maBan
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN LOP l  ON hs.maLop = l.maLop
                LEFT JOIN KHOI k ON l.maKhoi = k.maKhoi
                WHERE 1=1";
        $params = [];

        if ($maLop !== '') {
            $sql .= " AND hs.maLop = ?";
            $params[] = $maLop;
        }
        if ($gioiTinh !== '') {
            $sql .= " AND nd.gioiTinh = ?";
            $params[] = $gioiTinh;
        }
        if ($keyword !== '') {
            $sql .= " AND (nd.hoVaTen LIKE ? OR hs.maHS LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $sql .= " ORDER BY l.maKhoi, l.tenLop, hs.maHS";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
