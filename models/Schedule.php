<?php
class Schedule {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll() {
        $sql = "SELECT tkb.*, l.tenLop, mh.tenMon, gv.maGV, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU tkb
                JOIN LOP l ON tkb.maLop = l.maLop
                JOIN MONHOC mh ON tkb.maMon = mh.maMon
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung
                JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong
                ORDER BY tkb.ngay, tkb.tiet";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM THOIKHOABIEU WHERE maTKB = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $sql = "INSERT INTO THOIKHOABIEU (maLop, maMon, maGV, ngay, tiet, maPhong, namHoc, hocKy) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['maLop'], $data['maMon'], $data['maGV'], $data['ngay'], 
            $data['tiet'], $data['maPhong'], $data['namHoc'], $data['hocKy']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE THOIKHOABIEU SET maLop=?, maMon=?, maGV=?, ngay=?, tiet=?, maPhong=?, namHoc=?, hocKy=?
                WHERE maTKB=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['maLop'], $data['maMon'], $data['maGV'], $data['ngay'], 
            $data['tiet'], $data['maPhong'], $data['namHoc'], $data['hocKy'], $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM THOIKHOABIEU WHERE maTKB = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}