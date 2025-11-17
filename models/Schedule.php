<?php
class Schedule {
    private $db;
    public function __construct($db){
        $this->db = $db;
    }

    /* ======================================================================
       WEEK UTILS
    ====================================================================== */

    public function getWeekRange($weekString){
        $ts = strtotime($weekString . "-1");
        $start = date("Y-m-d", $ts);
        $end   = date("Y-m-d", strtotime($start . " +6 days"));
        return [$start, $end];
    }

    /* ======================================================================
       LOAD DANH SÁCH DATA
    ====================================================================== */

    public function getClasses($khoi){
        $stmt = $this->db->prepare("SELECT * FROM LOP WHERE maKhoi=?");
        $stmt->execute([$khoi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjects(){
        return $this->db->query("SELECT * FROM MONHOC ORDER BY tenMon")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeachers(){
        $sql = "SELECT gv.*, nd.hoVaTen 
                FROM GIAOVIEN gv
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRooms(){
        return $this->db->query("SELECT * FROM PHONGHOC ORDER BY tenPhong")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDefaultRoom($maLop){
        $stmt = $this->db->prepare("SELECT maPhong FROM LOP WHERE maLop = ?");
        $stmt->execute([$maLop]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['maPhong'] : null;
    }

    /* ======================================================================
       QUẢN LÝ TEMPLATE
    ====================================================================== */

    // Lấy template cho lớp
    public function getTemplate($maLop, $hocKy, $namHoc){
        $sql = "SELECT tt.*, mh.tenMon, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU_TEMPLATE tt
                JOIN MONHOC mh ON tt.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tt.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tt.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tt.maLop = ? AND tt.hocKy = ? AND tt.namHoc = ?
                ORDER BY thu, tiet";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $hocKy, $namHoc]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lưu template
    public function saveTemplate($data){
        // Xóa template cũ
        $this->db->prepare("DELETE FROM THOIKHOABIEU_TEMPLATE WHERE maLop=? AND hocKy=? AND namHoc=?")
                ->execute([$data['maLop'], $data['hocKy'], $data['namHoc']]);

        // Lưu template mới
        $sql = "INSERT INTO THOIKHOABIEU_TEMPLATE (maLop, thu, tiet, maMon, maGV, maPhong, hocKy, namHoc)
                VALUES (?,?,?,?,?,?,?,?)";
        
        $stmt = $this->db->prepare($sql);
        foreach($data['schedule'] as $item){
            $stmt->execute([
                $data['maLop'], $item['thu'], $item['tiet'],
                $item['maMon'], $item['maGV'], $item['maPhong'],
                $data['hocKy'], $data['namHoc']
            ]);
        }
        return true;
    }

    // Kiểm tra template đã tồn tại
    public function templateExists($maLop, $hocKy, $namHoc){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM THOIKHOABIEU_TEMPLATE WHERE maLop=? AND hocKy=? AND namHoc=?");
        $stmt->execute([$maLop, $hocKy, $namHoc]);
        return $stmt->fetchColumn() > 0;
    }

    /* ======================================================================
       QUẢN LÝ LỊCH THEO TUẦN
    ====================================================================== */

    // Lấy lịch theo tuần (ưu tiên lịch tuần cụ thể, nếu không có thì dùng template)
    public function getByClassWeek($maLop, $tbd, $tkt, $hocKy, $namHoc){
        // Đầu tiên tìm lịch cụ thể cho tuần này
        $sql = "SELECT tkb.*, mh.tenMon, gv.maGV, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.maLop = ? AND tkb.tuanBatDau = ? AND tkb.tuanKetThuc = ?
                ORDER BY thu, tiet";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $tbd, $tkt]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nếu có lịch cụ thể, trả về
        if(!empty($result)){
            return $result;
        }

        // Nếu không có, trả về template
        return $this->getTemplate($maLop, $hocKy, $namHoc);
    }

    // Kiểm tra tuần đã có lịch riêng chưa
    public function hasCustomSchedule($maLop, $tbd, $tkt){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM THOIKHOABIEU WHERE maLop=? AND tuanBatDau=? AND tuanKetThuc=?");
        $stmt->execute([$maLop, $tbd, $tkt]);
        return $stmt->fetchColumn() > 0;
    }

    /* ======================================================================
       THÊM/SỬA/XÓA
    ====================================================================== */

    public function add($data){
        $sql = "INSERT INTO THOIKHOABIEU (maLop, thu, tiet, maMon, maGV, hocKy, namHoc, tuanBatDau, tuanKetThuc, maPhong)
                VALUES (?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data["maLop"], $data["thu"], $data["tiet"],
            $data["maMon"], $data["maGV"],
            $data["hocKy"], $data["namHoc"],
            $data["tbd"], $data["tkt"],
            $data["maPhong"]
        ]);
    }

    public function update($id, $data){
        $sql = "UPDATE THOIKHOABIEU SET maMon=?, maGV=?, hocKy=?, namHoc=?, maPhong=? WHERE maTKB=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data["maMon"], $data["maGV"], $data["hocKy"], 
            $data["namHoc"], $data["maPhong"], $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maTKB=?");
        return $stmt->execute([$id]);
    }

    // Xóa toàn bộ lịch của một tuần
    public function deleteWeekSchedule($maLop, $tbd, $tkt){
        $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND tuanBatDau=? AND tuanKetThuc=?");
        return $stmt->execute([$maLop, $tbd, $tkt]);
    }

    /* ======================================================================
       ÁP DỤNG TEMPLATE CHO TUẦN
    ====================================================================== */

    public function applyTemplateToWeek($maLop, $tbd, $tkt, $hocKy, $namHoc){
        // Xóa lịch hiện tại của tuần
        $this->deleteWeekSchedule($maLop, $tbd, $tkt);

        // Lấy template
        $template = $this->getTemplate($maLop, $hocKy, $namHoc);
        
        if(empty($template)){
            return false;
        }

        // Áp dụng template cho tuần
        foreach($template as $item){
            $this->add([
                "maLop" => $maLop,
                "thu" => $item['thu'],
                "tiet" => $item['tiet'],
                "maMon" => $item['maMon'],
                "maGV" => $item['maGV'],
                "hocKy" => $hocKy,
                "namHoc" => $namHoc,
                "tbd" => $tbd,
                "tkt" => $tkt,
                "maPhong" => $item['maPhong']
            ]);
        }

        return true;
    }

    /* ======================================================================
       KIỂM TRA XUNG ĐỘT VÀ GỢI Ý
    ====================================================================== */

    public function checkAdvancedConflict($maLop, $maGV, $maPhong, $thu, $tiet, $tbd, $tkt, $ignoreId = null){
        $sql = "SELECT tkb.*, mh.tenMon, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.thu = ? AND tkb.tiet = ? AND tkb.tuanBatDau = ? AND tkb.tuanKetThuc = ?
                AND (tkb.maGV = ? OR tkb.maPhong = ? OR tkb.maLop = ?)";
        
        $params = [$thu, $tiet, $tbd, $tkt, $maGV, $maPhong, $maLop];
        
        if($ignoreId){
            $sql .= " AND tkb.maTKB != ?";
            $params[] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $conflicts = [];
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($results as $row){
            if($row['maGV'] == $maGV) {
                $conflicts[] = "Giáo viên {$row['tenGV']} đang dạy môn {$row['tenMon']} tại lớp {$row['maLop']}";
            }
            if($row['maPhong'] == $maPhong) {
                $conflicts[] = "Phòng {$row['tenPhong']} đang được sử dụng bởi lớp {$row['maLop']}";
            }
            if($row['maLop'] == $maLop) {
                $conflicts[] = "Lớp đang học môn {$row['tenMon']} với GV {$row['tenGV']}";
            }
        }
        
        return $conflicts;
    }

    public function getSuggestedSlots($maLop, $maMon, $tbd, $tkt){
    $suggestions = [];
    
    // Lấy thông tin môn học
    $stmt = $this->db->prepare("SELECT soTiet, tenMon FROM MONHOC WHERE maMon = ?");
    $stmt->execute([$maMon]);
    $mon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mon) return $suggestions;
    
    // Lấy lịch hiện tại của lớp
    $currentSchedule = $this->getByClassWeek($maLop, $tbd, $tkt, 1, "2024-2025");
    $busySlots = [];
    foreach ($currentSchedule as $item) {
        $busySlots[$item['thu']][$item['tiet']] = true;
    }
    
    // Lấy giáo viên môn học
    $gvMon = null;
    $gvList = $this->getTeachers();
    
    foreach ($gvList as $gv) {
        // So sánh tên môn học của giáo viên với tên môn từ mã môn
        if ($gv["monGiangDay"] == $mon['tenMon']) {
            $gvMon = $gv["maGV"];
            break;
        }
    }
    
    if (!$gvMon) return $suggestions;
    
    // Lấy lịch bận của giáo viên
    $gvBusy = [];
    $sql = "SELECT thu, tiet FROM THOIKHOABIEU 
            WHERE maGV = ? AND tuanBatDau = ? AND tuanKetThuc = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$gvMon, $tbd, $tkt]);
    $gvSchedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($gvSchedule as $item) {
        $gvBusy[$item['thu']][$item['tiet']] = true;
    }
    
    // Gợi ý các ô trống phù hợp
    for ($thu = 2; $thu <= 7; $thu++) {
        for ($tiet = 1; $tiet <= 5; $tiet++) {
            // Kiểm tra ô trống và không xung đột
            if (!isset($busySlots[$thu][$tiet]) && !isset($gvBusy[$thu][$tiet])) {
                $suggestions[] = [
                    'thu' => $thu,
                    'tiet' => $tiet,
                    'priority' => $this->calculatePriority($thu, $tiet, $maMon)
                ];
            }
        }
    }
    
    // Sắp xếp theo độ ưu tiên
    usort($suggestions, function($a, $b) {
        return $b['priority'] - $a['priority'];
    });
    
    return array_slice($suggestions, 0, 5); // Trả về 5 gợi ý tốt nhất
}

    private function calculatePriority($thu, $tiet, $maMon) {
        $priority = 0;
        
        // Ưu tiên buổi sáng (tiết 1-3)
        if ($tiet <= 3) $priority += 3;
        // Ưu tiên giữa tuần (thứ 3-5)
        if ($thu >= 3 && $thu <= 5) $priority += 2;
        // Tránh tiết 5 cuối ngày
        if ($tiet == 5) $priority -= 2;
        
        return $priority;
    }

    /* ======================================================================
       AUTO ARRANGE (TẠO TEMPLATE)
    ====================================================================== */

    public function autoArrange($maLop, $hocKy, $namHoc){
        // Xóa template cũ
        $this->db->prepare("DELETE FROM THOIKHOABIEU_TEMPLATE WHERE maLop=? AND hocKy=? AND namHoc=?")
                ->execute([$maLop, $hocKy, $namHoc]);

        $monList = $this->getSubjects();
        $gvList = $this->getTeachers();
        $defaultRoom = $this->getDefaultRoom($maLop);

        // Sắp xếp môn theo số tiết (giảm dần)
        usort($monList, function($a, $b){
            return $b['soTiet'] - $a['soTiet'];
        });

        $scheduleData = [];
        $usedSlots = [];
        
        foreach($monList as $m){
            $soTiet = (int)$m["soTiet"];
            if($soTiet <= 0) continue;

            // Tìm giáo viên
            $gvMon = null;
            foreach($gvList as $g){
                if ($g["monGiangDay"] == $m["tenMon"]){
                    $gvMon = $g["maGV"];
                    break;
                }
            }
            if (!$gvMon) continue;

            $added = 0;
            
            // Ưu tiên xếp các môn nhiều tiết trước
            for($attempt = 0; $attempt < 10 && $added < $soTiet; $attempt++){
                for($thu = 2; $thu <= 7 && $added < $soTiet; $thu++){
                    for($tiet = 1; $tiet <= 5 && $added < $soTiet; $tiet++){
                        
                        // Kiểm tra slot đã dùng
                        $slotKey = $thu . '_' . $tiet;
                        if(isset($usedSlots[$slotKey])) continue;
                        
                        // Kiểm tra giáo viên bận
                        $gvBusy = false;
                        foreach($scheduleData as $item){
                            if($item['maGV'] == $gvMon && $item['thu'] == $thu && $item['tiet'] == $tiet){
                                $gvBusy = true;
                                break;
                            }
                        }
                        if($gvBusy) continue;
                        
                        // Thêm vào lịch
                        $scheduleData[] = [
                            'thu' => $thu,
                            'tiet' => $tiet,
                            'maMon' => $m["maMon"],
                            'maGV' => $gvMon,
                            'maPhong' => $defaultRoom
                        ];
                        $usedSlots[$slotKey] = true;
                        $added++;
                    }
                }
            }
        }

        // Lưu template
        $this->saveTemplate([
            'maLop' => $maLop,
            'hocKy' => $hocKy,
            'namHoc' => $namHoc,
            'schedule' => $scheduleData
        ]);

        return true;
    }

    /* ======================================================================
       KIỂM TRA SỐ TIẾT/TUẦN
    ====================================================================== */

    public function checkWeeklyHours($maLop, $maMon, $tbd, $tkt){
        $sql = "SELECT COUNT(*) as soTietDaXep 
                FROM THOIKHOABIEU 
                WHERE maLop = ? AND maMon = ? 
                AND tuanBatDau = ? AND tuanKetThuc = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $maMon, $tbd, $tkt]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt2 = $this->db->prepare("SELECT soTiet FROM MONHOC WHERE maMon = ?");
        $stmt2->execute([$maMon]);
        $mon = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        return [
            'daXep' => $result['soTietDaXep'],
            'toiDa' => $mon['soTiet'],
            'vuot' => $result['soTietDaXep'] >= $mon['soTiet']
        ];
    }

    /* ======================================================================
       KIỂM TRA TUẦN KHÓA
    ====================================================================== */

    public function isWeekLocked($weekStart){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM khoatuan WHERE tuanBatDau = ? AND daKhoa = 1");
        $stmt->execute([$weekStart]);
        return $stmt->fetchColumn() > 0;
    }
}
?>