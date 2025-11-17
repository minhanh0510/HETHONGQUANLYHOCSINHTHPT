<?php
class Assignment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Danh sách HS đã có lớp
    public function getAssigned($maKhoiFilter = '') {
        $sql = "SELECT 
                    hs.maHS,
                    nd.hoVaTen,
                    nd.gioiTinh,
                    nd.ngaySinh,
                    hs.maLop,
                    l.tenLop,
                    l.maKhoi,
                    k.tenKhoi,
                    l.maBan
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                LEFT JOIN LOP l  ON hs.maLop = l.maLop
                LEFT JOIN KHOI k ON l.maKhoi = k.maKhoi
                WHERE hs.maLop IS NOT NULL";
        $params = [];
        if ($maKhoiFilter !== '') {
            $sql .= " AND l.maKhoi = ?";
            $params[] = $maKhoiFilter;
        }
        $sql .= " ORDER BY l.maKhoi, l.tenLop, nd.hoVaTen";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // HS chưa được phân lớp
    public function getUnassigned() {
        $sql = "SELECT hs.maHS, nd.hoVaTen, nd.gioiTinh, nd.ngaySinh
                FROM HOCSINH hs
                JOIN NGUOIDUNG nd ON hs.maNguoiDung = nd.maNguoiDung
                WHERE hs.maLop IS NULL
                ORDER BY nd.hoVaTen";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy info lớp
    private function getLopInfo($maLop) {
        $stmt = $this->db->prepare("SELECT maLop, tenLop, siSo, maKhoi, maBan FROM LOP WHERE maLop = ?");
        $stmt->execute([$maLop]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đếm sĩ số hiện tại trong lớp (dựa trên HOCSINH)
    private function countCurrentInClass($maLop) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM HOCSINH WHERE maLop = ?");
        $stmt->execute([$maLop]);
        return (int)$stmt->fetchColumn();
    }

    // Phân công mới cho 1 HS (từ trạng thái maLop NULL)
    public function assignNew($maHS, $maLop) {
        try {
            $this->db->beginTransaction();

            // đảm bảo HS chưa có lớp
            $stmt = $this->db->prepare("SELECT maLop FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$maHS]);
            $current = $stmt->fetchColumn();
            if ($current !== null && $current !== '') {
                $this->db->rollBack();
                return "❌ Học sinh đã có lớp, hãy dùng chức năng Điều chỉnh.";
            }

            $lopInfo = $this->getLopInfo($maLop);
            if (!$lopInfo) {
                $this->db->rollBack();
                return "❌ Lớp không tồn tại.";
            }

            $maxSiSo = (int)$lopInfo['siSo'];
            $currentCount = $this->countCurrentInClass($maLop);
            if ($currentCount + 1 > $maxSiSo) {
                $this->db->rollBack();
                return "❌ Lớp đã đủ sĩ số, vui lòng chọn lớp khác.";
            }

            $update = $this->db->prepare("UPDATE HOCSINH SET maLop = ? WHERE maHS = ?");
            $update->execute([$maLop, $maHS]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi phân công: " . $e->getMessage();
        }
    }

    // Điều chỉnh lớp cho 1 HS (chỉ cần đổi HOCSINH.maLop)
    public function changeAssignment($maHS, $maLopMoi) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT maLop FROM HOCSINH WHERE maHS = ?");
            $stmt->execute([$maHS]);
            $maLopCu = $stmt->fetchColumn();

            if (!$maLopCu) {
                $this->db->rollBack();
                return "❌ Học sinh chưa có lớp, hãy dùng Phân công mới.";
            }

            if ($maLopCu === $maLopMoi) {
                $this->db->rollBack();
                return "⚠️ Lớp mới trùng với lớp hiện tại.";
            }

            $lopInfo = $this->getLopInfo($maLopMoi);
            if (!$lopInfo) {
                $this->db->rollBack();
                return "❌ Lớp mới không tồn tại.";
            }

            $maxSiSo = (int)$lopInfo['siSo'];
            $currentCount = $this->countCurrentInClass($maLopMoi);
            if ($currentCount + 1 > $maxSiSo) {
                $this->db->rollBack();
                return "❌ Lớp mới đã đủ sĩ số, vui lòng chọn lớp khác.";
            }

            $update = $this->db->prepare("UPDATE HOCSINH SET maLop = ? WHERE maHS = ?");
            $update->execute([$maLopMoi, $maHS]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "❌ Lỗi khi điều chỉnh phân công: " . $e->getMessage();
        }
    }
}
