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
        // Xử lý chuỗi tuần định dạng Y-Www (ví dụ: 2025-W01)
        $ts = strtotime($weekString . "-1");
        if($ts === false) {
            $ts = strtotime("now");
        }
        $start = date("Y-m-d", $ts);
        $end   = date("Y-m-d", strtotime($start . " +6 days"));
        return [$start, $end];
    }

    public function getWeekNumber($date){
        return date("Y-\WW", strtotime($date));
    }

    /* ======================================================================
       LOAD DANH SÁCH DATA
    ====================================================================== */

    public function getClasses($khoi = null){
        if($khoi){
            $stmt = $this->db->prepare("SELECT * FROM LOP WHERE maKhoi=? ORDER BY tenLop");
            $stmt->execute([$khoi]);
        } else {
            $stmt = $this->db->query("SELECT * FROM LOP ORDER BY maKhoi, tenLop");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjects(){
        return $this->db->query("SELECT * FROM MONHOC ORDER BY tenMon")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeachers(){
        $sql = "SELECT gv.*, nd.hoVaTen 
                FROM GIAOVIEN gv
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                ORDER BY nd.hoVaTen";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeachersBySubject($maMon){
        // monGiangDay trong GIAOVIEN chứa maMon (TOAN, VAN, ...) không phải tenMon
        $sql = "SELECT gv.*, nd.hoVaTen 
                FROM GIAOVIEN gv
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                WHERE gv.monGiangDay = ?
                ORDER BY nd.hoVaTen";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maMon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy phân công giáo viên bộ môn từ bảng pcgvbm theo lớp và năm học
     * Trả về mảng [maMon => ['maGV' => ..., 'hoVaTen' => ...]]
     */
    public function getTeacherAssignmentsByClass($maLop, $namHoc){
        // Sử dụng trực tiếp namHoc (VD: "2025-2026")
        // Bảng pcgvbm lưu năm học dạng "2025-2026"
        
        $sql = "SELECT pc.maMon, pc.maGV, nd.hoVaTen
                FROM pcgvbm pc
                JOIN GIAOVIEN gv ON pc.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                WHERE pc.maLop = ? AND pc.namHoc = ?
                ORDER BY pc.maMon";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $namHoc]);
        
        $result = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $result[$row['maMon']] = [
                'maGV' => $row['maGV'],
                'hoVaTen' => $row['hoVaTen']
            ];
        }
        return $result;
    }

    /**
     * Lấy giáo viên được phân công dạy môn cho lớp từ bảng pcgvbm
     * Nếu không có phân công, trả về null
     */
    public function getAssignedTeacher($maLop, $maMon, $namHoc){
        // Sử dụng trực tiếp namHoc (VD: "2025-2026")
        
        $sql = "SELECT pc.maGV, nd.hoVaTen
                FROM pcgvbm pc
                JOIN GIAOVIEN gv ON pc.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                WHERE pc.maLop = ? AND pc.maMon = ? AND pc.namHoc = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $maMon, $namHoc]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getClassInfo($maLop){
        $stmt = $this->db->prepare("SELECT * FROM LOP WHERE maLop = ?");
        $stmt->execute([$maLop]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ======================================================================
       QUẢN LÝ TEMPLATE (Lịch mẫu cho cả học kỳ) - DISABLED vì bảng không tồn tại
    ====================================================================== */

    public function getTemplate($maLop, $hocKy, $namHoc){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại, trả về rỗng
        return [];
    }

    public function saveTemplate($data, $useTransaction = true){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại
        return false;
    }

    public function saveTemplateEntry($data){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại
        return false;
    }

    public function deleteTemplateEntry($maLop, $thu, $tiet, $hocKy, $namHoc){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại
        return false;
    }

    public function templateExists($maLop, $hocKy, $namHoc){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại
        return false;
    }

    public function clearTemplate($maLop, $hocKy, $namHoc){
        // Bảng THOIKHOABIEU_TEMPLATE không tồn tại
        return false;
    }

    /* ======================================================================
       QUẢN LÝ LỊCH THEO HỌC KỲ
    ====================================================================== */

    public function getByClassSemester($maLop, $hocKy, $namHoc){
        // Cast hocKy to int
        $hocKy = (int)$hocKy;
        
        $sql = "SELECT tkb.*, mh.tenMon, gv.maGV, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.maLop = ? AND tkb.hocKy = ? AND tkb.namHoc = ?
                ORDER BY thu, tiet";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $hocKy, $namHoc]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("getByClassSemester: maLop=$maLop, hocKy=$hocKy, namHoc=$namHoc, found=" . count($result) . " rows");
        return $result;
    }

    public function getSubjectHoursSummarySemester($maLop, $hocKy, $namHoc){
        $subjects = $this->getSubjects();
        $result = [];
        
        foreach($subjects as $s){
            $maMon = $s['maMon'];
            $soTietTuan = $s['soTiet'] ?? 4; // Số tiết/tuần quy định
            
            // Đếm số tiết đã xếp trong học kỳ này
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM THOIKHOABIEU 
                 WHERE maLop=? AND maMon=? AND hocKy=? AND namHoc=?"
            );
            $stmt->execute([$maLop, $maMon, $hocKy, $namHoc]);
            $daXep = $stmt->fetchColumn();
            
            $result[$maMon] = [
                'daXep' => $daXep,
                'toiDa' => $soTietTuan, // Số tiết/tuần tối đa
                'conLai' => max(0, $soTietTuan - $daXep),
                'vuot' => $daXep >= $soTietTuan
            ];
        }
        
        return $result;
    }

    public function clearScheduleSemester($maLop, $hocKy, $namHoc){
        try {
            // Cast hocKy to int để đảm bảo match đúng
            $hocKy = (int)$hocKy;
            
            $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND hocKy=? AND namHoc=?");
            $result = $stmt->execute([$maLop, $hocKy, $namHoc]);
            $rowCount = $stmt->rowCount();
            error_log("ClearScheduleSemester: maLop=$maLop, hocKy=$hocKy, namHoc=$namHoc, deleted=$rowCount rows");
            return $result;
        } catch(Exception $e){
            error_log("ClearScheduleSemester Error: " . $e->getMessage());
            return false;
        }
    }

    /* ======================================================================
       QUẢN LÝ LỊCH THEO TUẦN (Legacy - giữ tương thích)
    ====================================================================== */

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

        // Nếu có lịch cụ thể cho tuần, trả về
        if(!empty($result)){
            return $result;
        }

        // Nếu không có, trả về template (lịch mẫu)
        return $this->getTemplate($maLop, $hocKy, $namHoc);
    }

    public function hasCustomSchedule($maLop, $tbd, $tkt){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM THOIKHOABIEU WHERE maLop=? AND tuanBatDau=? AND tuanKetThuc=?");
        $stmt->execute([$maLop, $tbd, $tkt]);
        return $stmt->fetchColumn() > 0;
    }

    /* ======================================================================
       THÊM/SỬA/XÓA LỊCH TUẦN
    ====================================================================== */

    public function add($data){
        try {
            // Cast types để đảm bảo đúng định dạng
            $thu = (int)$data["thu"];
            $tiet = (int)$data["tiet"];
            $hocKy = (int)$data["hocKy"];
            
            // Log data being inserted
            error_log("Schedule::add - maLop={$data['maLop']}, thu=$thu, tiet=$tiet, maMon={$data['maMon']}, maGV={$data['maGV']}, hocKy=$hocKy, namHoc={$data['namHoc']}, tbd={$data['tbd']}, tkt={$data['tkt']}");
            
            $sql = "INSERT INTO THOIKHOABIEU (maLop, thu, tiet, maMon, maGV, hocKy, namHoc, tuanBatDau, tuanKetThuc, maPhong)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data["maLop"], $thu, $tiet,
                $data["maMon"], $data["maGV"],
                $hocKy, $data["namHoc"],
                $data["tbd"], $data["tkt"],
                $data["maPhong"]
            ]);
            
            if (!$result) {
                error_log("Schedule::add SQL Error: " . json_encode($stmt->errorInfo()));
            } else {
                error_log("Schedule::add Success - Inserted ID: " . $this->db->lastInsertId());
            }
            return $result;
        } catch(PDOException $e) {
            error_log("Schedule::add PDO Error: " . $e->getMessage());
            return false;
        } catch(Exception $e) {
            error_log("Schedule::add Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data){
        $sql = "UPDATE THOIKHOABIEU SET maMon=?, maGV=?, maPhong=? WHERE maTKB=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data["maMon"], $data["maGV"], $data["maPhong"], $id
        ]);
    }

    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maTKB=?");
        return $stmt->execute([$id]);
    }

    public function deleteWeekSchedule($maLop, $tbd, $tkt){
        $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND tuanBatDau=? AND tuanKetThuc=?");
        return $stmt->execute([$maLop, $tbd, $tkt]);
    }

    public function deleteSlot($maLop, $thu, $tiet, $tbd, $tkt, $hocKy = null, $namHoc = null){
        // Cast types
        $thu = (int)$thu;
        $tiet = (int)$tiet;
        
        // Nếu có hocKy và namHoc, xóa theo học kỳ (không cần match tuanBatDau/tuanKetThuc chính xác)
        if ($hocKy && $namHoc) {
            $hocKy = (int)$hocKy;
            $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND thu=? AND tiet=? AND hocKy=? AND namHoc=?");
            $result = $stmt->execute([$maLop, $thu, $tiet, $hocKy, $namHoc]);
            error_log("DeleteSlot (semester): maLop=$maLop, thu=$thu, tiet=$tiet, hocKy=$hocKy, namHoc=$namHoc, deleted=" . $stmt->rowCount());
            return $result;
        }
        
        // Fallback: xóa theo tuần cụ thể
        $stmt = $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND thu=? AND tiet=? AND tuanBatDau=? AND tuanKetThuc=?");
        $result = $stmt->execute([$maLop, $thu, $tiet, $tbd, $tkt]);
        error_log("DeleteSlot (week): maLop=$maLop, thu=$thu, tiet=$tiet, tbd=$tbd, tkt=$tkt, deleted=" . $stmt->rowCount());
        return $result;
    }

    /* ======================================================================
       ÁP DỤNG TEMPLATE CHO TUẦN / NĂM HỌC
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

    public function applyTemplateToYear($maLop, $hocKy, $namHoc, $startDate, $endDate){
        $template = $this->getTemplate($maLop, $hocKy, $namHoc);
        
        if(empty($template)){
            return false;
        }

        $currentDate = strtotime($startDate);
        $endDateTs = strtotime($endDate);
        $count = 0;

        while($currentDate <= $endDateTs){
            // Tìm thứ 2 của tuần
            $dayOfWeek = date('N', $currentDate);
            if($dayOfWeek != 1){
                $currentDate = strtotime('next monday', $currentDate);
            }
            
            if($currentDate > $endDateTs) break;

            $tbd = date('Y-m-d', $currentDate);
            $tkt = date('Y-m-d', strtotime($tbd . ' +6 days'));

            // Kiểm tra tuần đã khóa
            if(!$this->isWeekLocked($tbd) && !$this->hasCustomSchedule($maLop, $tbd, $tkt)){
                $this->applyTemplateToWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
                $count++;
            }

            // Chuyển sang tuần tiếp theo
            $currentDate = strtotime($tbd . ' +7 days');
        }

        return $count;
    }

    /* ======================================================================
       KIỂM TRA XUNG ĐỘT
    ====================================================================== */

    public function checkConflict($maLop, $maGV, $maPhong, $thu, $tiet, $tbd, $tkt, $ignoreId = null){
        $conflicts = [];
        
        // Kiểm tra xung đột trong bảng THOIKHOABIEU
        $sql = "SELECT tkb.*, mh.tenMon, nd.hoVaTen as tenGV, ph.tenPhong, l.tenLop
                FROM THOIKHOABIEU tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                JOIN LOP l ON tkb.maLop = l.maLop COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.thu = ? AND tkb.tiet = ? 
                AND tkb.tuanBatDau = ? AND tkb.tuanKetThuc = ?
                AND (tkb.maGV = ? OR tkb.maPhong = ? OR tkb.maLop = ?)";
        
        $params = [$thu, $tiet, $tbd, $tkt, $maGV, $maPhong, $maLop];
        
        if($ignoreId){
            $sql .= " AND tkb.maTKB != ?";
            $params[] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($results as $row){
            if($row['maGV'] == $maGV && $row['maLop'] != $maLop) {
                $conflicts[] = [
                    'type' => 'teacher',
                    'message' => "Giáo viên {$row['tenGV']} đang dạy môn {$row['tenMon']} tại lớp {$row['tenLop']}"
                ];
            }
            if($row['maPhong'] == $maPhong && $row['maLop'] != $maLop) {
                $conflicts[] = [
                    'type' => 'room',
                    'message' => "Phòng {$row['tenPhong']} đang được sử dụng bởi lớp {$row['tenLop']}"
                ];
            }
            if($row['maLop'] == $maLop) {
                $conflicts[] = [
                    'type' => 'class',
                    'message' => "Lớp đang học môn {$row['tenMon']} với GV {$row['tenGV']}"
                ];
            }
        }
        
        return $conflicts;
    }

    public function checkTemplateConflict($maLop, $maGV, $maPhong, $thu, $tiet, $hocKy, $namHoc, $ignoreId = null){
        $conflicts = [];
        
        // Kiểm tra xung đột trong THOIKHOABIEU (không dùng TEMPLATE vì bảng không tồn tại)
        $sql = "SELECT tkb.*, mh.tenMon, nd.hoVaTen as tenGV, ph.tenPhong, l.tenLop
                FROM THOIKHOABIEU tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                JOIN LOP l ON tkb.maLop = l.maLop COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.thu = ? AND tkb.tiet = ? 
                AND tkb.hocKy = ? AND tkb.namHoc = ?
                AND (tkb.maGV = ? OR tkb.maPhong = ? OR tkb.maLop = ?)";
        
        $params = [$thu, $tiet, $hocKy, $namHoc, $maGV, $maPhong, $maLop];
        
        if($ignoreId){
            $sql .= " AND tkb.maTKB != ?";
            $params[] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($results as $row){
            if($row['maGV'] == $maGV && $row['maLop'] != $maLop) {
                $conflicts[] = [
                    'type' => 'teacher',
                    'message' => "Giáo viên {$row['tenGV']} đang dạy môn {$row['tenMon']} tại lớp {$row['tenLop']}"
                ];
            }
            if($row['maPhong'] == $maPhong && $row['maLop'] != $maLop) {
                $conflicts[] = [
                    'type' => 'room',
                    'message' => "Phòng {$row['tenPhong']} đang được sử dụng bởi lớp {$row['tenLop']}"
                ];
            }
            if($row['maLop'] == $maLop) {
                $conflicts[] = [
                    'type' => 'class',
                    'message' => "Lớp đang học môn {$row['tenMon']} với GV {$row['tenGV']}"
                ];
            }
        }
        
        return $conflicts;
    }

    /* ======================================================================
       GỢI Ý Ô TRỐNG
    ====================================================================== */

    public function getSuggestedSlots($maLop, $maMon, $tbd, $tkt, $hocKy, $namHoc){
        $suggestions = [];
        
        // Lấy thông tin môn học
        $stmt = $this->db->prepare("SELECT soTiet, tenMon FROM MONHOC WHERE maMon = ?");
        $stmt->execute([$maMon]);
        $mon = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$mon) return $suggestions;
        
        // Lấy giáo viên dạy môn này
        $teachers = $this->getTeachersBySubject($maMon);
        if(empty($teachers)) return $suggestions;
        
        $maGV = $teachers[0]['maGV'];
        $defaultRoom = $this->getDefaultRoom($maLop);
        
        // Lấy lịch hiện tại của lớp
        $currentSchedule = $this->getByClassWeek($maLop, $tbd, $tkt, $hocKy, $namHoc);
        $busySlots = [];
        foreach ($currentSchedule as $item) {
            $busySlots[$item['thu']][$item['tiet']] = true;
        }
        
        // Lấy lịch bận của giáo viên trong tuần
        $gvBusy = $this->getTeacherBusySlots($maGV, $tbd, $tkt, $hocKy, $namHoc);
        
        // Lấy lịch bận của phòng trong tuần
        $roomBusy = $this->getRoomBusySlots($defaultRoom, $tbd, $tkt, $hocKy, $namHoc);
        
        // Kiểm tra nếu là môn buổi chiều
        $isAfternoonSubject = in_array($maMon, $this->afternoonSubjects);
        
        // Tìm các ô trống phù hợp (bao gồm cả buổi chiều tiết 6-8)
        for ($thu = 2; $thu <= 7; $thu++) {
            $startTiet = $isAfternoonSubject ? 6 : 1;
            $endTiet = $isAfternoonSubject ? 8 : 5;
            
            for ($tiet = $startTiet; $tiet <= $endTiet; $tiet++) {
                // Kiểm tra ô trống
                if (isset($busySlots[$thu][$tiet])) continue;
                if (isset($gvBusy[$thu][$tiet])) continue;
                if ($defaultRoom && isset($roomBusy[$thu][$tiet])) continue;
                
                $priority = $this->calculateSlotPriority($thu, $tiet, $maMon, $busySlots);
                
                $suggestions[] = [
                    'thu' => $thu,
                    'tiet' => $tiet,
                    'priority' => $priority,
                    'reason' => $this->getSlotReason($priority)
                ];
            }
        }
        
        // Sắp xếp theo độ ưu tiên
        usort($suggestions, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
        
        return array_slice($suggestions, 0, 8);
    }

    private function getTeacherBusySlots($maGV, $tbd, $tkt, $hocKy, $namHoc){
        $busy = [];
        
        // Kiểm tra trong lịch học kỳ
        $sql = "SELECT thu, tiet FROM THOIKHOABIEU WHERE maGV = ? AND hocKy = ? AND namHoc = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maGV, (int)$hocKy, $namHoc]);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $busy[$row['thu']][$row['tiet']] = true;
        }
        
        return $busy;
    }

    private function getRoomBusySlots($maPhong, $tbd, $tkt, $hocKy, $namHoc){
        if(!$maPhong) return [];
        
        $busy = [];
        
        // Kiểm tra trong lịch học kỳ
        $sql = "SELECT thu, tiet FROM THOIKHOABIEU WHERE maPhong = ? AND hocKy = ? AND namHoc = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maPhong, (int)$hocKy, $namHoc]);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $busy[$row['thu']][$row['tiet']] = true;
        }
        
        return $busy;
    }

    private function calculateSlotPriority($thu, $tiet, $maMon, $busySlots){
        $priority = 10;
        
        // Ưu tiên buổi sáng (tiết 1-3)
        if ($tiet <= 3) $priority += 3;
        
        // Ưu tiên giữa tuần (thứ 3-5)
        if ($thu >= 3 && $thu <= 5) $priority += 2;
        
        // Tránh tiết 5 cuối ngày
        if ($tiet == 5) $priority -= 2;
        
        // Tránh thứ 7
        if ($thu == 7) $priority -= 1;
        
        // Kiểm tra phân bố đều trong tuần (tránh xếp cùng ngày)
        if(isset($busySlots[$thu]) && count($busySlots[$thu]) >= 4){
            $priority -= 3;
        }
        
        return $priority;
    }

    private function getSlotReason($priority){
        if($priority >= 13) return "Rất phù hợp";
        if($priority >= 10) return "Phù hợp";
        if($priority >= 7) return "Chấp nhận được";
        return "Ít ưu tiên";
    }

    /* ======================================================================
       KIỂM TRA SỐ TIẾT/TUẦN
    ====================================================================== */

    public function checkWeeklyHours($maLop, $maMon, $tbd, $tkt, $hocKy = 1, $namHoc = '2025-2026'){
        // Đếm số tiết đã xếp trong học kỳ từ bảng THOIKHOABIEU
        $sql = "SELECT COUNT(*) as soTietDaXep 
                FROM THOIKHOABIEU 
                WHERE maLop = ? AND maMon = ? 
                AND hocKy = ? AND namHoc = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $maMon, (int)$hocKy, $namHoc]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt2 = $this->db->prepare("SELECT soTiet FROM MONHOC WHERE maMon = ?");
        $stmt2->execute([$maMon]);
        $mon = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        $toiDa = $mon ? $mon['soTiet'] : 0;
        $daXep = $result ? $result['soTietDaXep'] : 0;
        
        return [
            'daXep' => $daXep,
            'toiDa' => $toiDa,
            'conLai' => max(0, $toiDa - $daXep),
            'vuot' => $daXep >= $toiDa
        ];
    }

    public function getSubjectHoursSummary($maLop, $tbd, $tkt, $hocKy, $namHoc){
        $subjects = $this->getSubjects();
        $summary = [];
        
        foreach($subjects as $mon){
            $hours = $this->checkWeeklyHours($maLop, $mon['maMon'], $tbd, $tkt, $hocKy, $namHoc);
            $summary[$mon['maMon']] = [
                'tenMon' => $mon['tenMon'],
                'toiDa' => $hours['toiDa'],
                'daXep' => $hours['daXep'],
                'conLai' => $hours['conLai'],
                'vuot' => $hours['vuot']
            ];
        }
        
        return $summary;
    }

    /* ======================================================================
       XẾP LỊCH TỰ ĐỘNG
    ====================================================================== */

    // Các môn nên xếp buổi chiều 3 tiết liền (TD, QPAN - Giáo dục Quốc phòng và An ninh)
    private $afternoonSubjects = ['TD', 'QPAN'];

    /**
     * Tạo lịch tự động - CHỈ TRẢ VỀ DATA PREVIEW, KHÔNG LƯU DB
     * CHỈ xếp lịch cho các môn đã được phân công GVBM trong bảng pcgvbm
     */
    public function autoArrangePreview($maLop, $hocKy, $namHoc){
        try {
            $monList = $this->getSubjects();
            $defaultRoom = $this->getDefaultRoom($maLop);
            
            // LẤY PHÂN CÔNG GIÁO VIÊN BỘ MÔN TỪ BẢNG pcgvbm
            // Chỉ các môn có trong bảng này mới được xếp lịch
            $teacherAssignments = $this->getTeacherAssignmentsByClass($maLop, $namHoc);
            
            // Tách môn buổi chiều và buổi sáng
            $morningSubjects = [];
            $afternoonSubjectList = [];
            
            foreach($monList as $m){
                if(in_array($m['maMon'], $this->afternoonSubjects)){
                    $afternoonSubjectList[] = $m;
                } else {
                    $morningSubjects[] = $m;
                }
            }
            
            // Sắp xếp môn theo số tiết (giảm dần)
            usort($morningSubjects, function($a, $b){
                return $b['soTiet'] - $a['soTiet'];
            });
            
            $scheduleData = [];
            $usedSlots = [];
            $gvUsedSlots = [];
            
            // === XẾP MÔN BUỔI SÁNG ===
            foreach($morningSubjects as $m){
                $soTiet = (int)$m["soTiet"];
                if($soTiet <= 0) continue;
                
                // CHỈ lấy giáo viên từ bảng pcgvbm - KHÔNG fallback
                // Nếu môn chưa được phân công giáo viên thì bỏ qua
                if(!isset($teacherAssignments[$m['maMon']])){
                    continue; // Bỏ qua môn chưa phân công GVBM
                }
                
                $gvMon = $teacherAssignments[$m['maMon']]['maGV'];
                $tenGV = $teacherAssignments[$m['maMon']]['hoVaTen'];
                
                if (!$gvMon) continue;
                
                $added = 0;
                $prioritizedSlots = $this->getPrioritizedSlots(false);
                
                foreach($prioritizedSlots as $slot){
                    if($added >= $soTiet) break;
                    
                    $thu = $slot['thu'];
                    $tiet = $slot['tiet'];
                    $slotKey = $thu . '_' . $tiet;
                    
                    if(isset($usedSlots[$slotKey])) continue;
                    if(isset($gvUsedSlots[$gvMon][$slotKey])) continue;
                    
                    // Không check xung đột DB vì đây là preview
                    
                    // Không xếp quá 2 tiết cùng môn trong ngày
                    $sameSubjectToday = 0;
                    foreach($scheduleData as $item){
                        if($item['thu'] == $thu && $item['maMon'] == $m['maMon']){
                            $sameSubjectToday++;
                        }
                    }
                    if($sameSubjectToday >= 2) continue;
                    
                    // Lấy tên môn
                    $tenMon = $m['tenMon'] ?? '';
                    
                    $scheduleData[] = [
                        'thu' => $thu,
                        'tiet' => $tiet,
                        'maMon' => $m["maMon"],
                        'tenMon' => $tenMon,
                        'maGV' => $gvMon,
                        'tenGV' => $tenGV,
                        'maPhong' => $defaultRoom,
                        'tenPhong' => $defaultRoom
                    ];
                    
                    $usedSlots[$slotKey] = true;
                    $gvUsedSlots[$gvMon][$slotKey] = true;
                    $added++;
                }
            }
            
            // === XẾP MÔN BUỔI CHIỀU ===
            foreach($afternoonSubjectList as $m){
                $soTiet = (int)$m["soTiet"];
                if($soTiet <= 0) continue;
                
                // CHỈ lấy giáo viên từ bảng pcgvbm - KHÔNG fallback
                // Nếu môn chưa được phân công giáo viên thì bỏ qua
                if(!isset($teacherAssignments[$m['maMon']])){
                    continue; // Bỏ qua môn chưa phân công GVBM
                }
                
                $gvMon = $teacherAssignments[$m['maMon']]['maGV'];
                $tenGV = $teacherAssignments[$m['maMon']]['hoVaTen'];
                
                if (!$gvMon) continue;
                
                $room = ($m['maMon'] === 'TD') ? 'SAN' : $defaultRoom;
                $tenMon = $m['tenMon'] ?? '';
                
                $added = 0;
                
                for($thu = 2; $thu <= 7 && $added < $soTiet; $thu++){
                    $afternoonFree = true;
                    
                    for($tiet = 6; $tiet <= 8; $tiet++){
                        $slotKey = $thu . '_' . $tiet;
                        if(isset($usedSlots[$slotKey]) || isset($gvUsedSlots[$gvMon][$slotKey])){
                            $afternoonFree = false;
                            break;
                        }
                    }
                    
                    if($afternoonFree){
                        $tietCount = min(3, $soTiet - $added);
                        for($i = 0; $i < $tietCount; $i++){
                            $tiet = 6 + $i;
                            $slotKey = $thu . '_' . $tiet;
                            
                            $scheduleData[] = [
                                'thu' => $thu,
                                'tiet' => $tiet,
                                'maMon' => $m["maMon"],
                                'tenMon' => $tenMon,
                                'maGV' => $gvMon,
                                'tenGV' => $tenGV,
                                'maPhong' => $room,
                                'tenPhong' => $room
                            ];
                            
                            $usedSlots[$slotKey] = true;
                            $gvUsedSlots[$gvMon][$slotKey] = true;
                            $added++;
                        }
                    }
                }
            }
            
            return $scheduleData;
            
        } catch(Exception $e){
            error_log("AutoArrangePreview Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lưu lịch đã xếp vào DB cho CẢ HỌC KỲ
     * HK1: 01/09 → 15/01 năm sau
     * HK2: 16/01 → 31/05
     */
    public function saveScheduleForSemester($maLop, $scheduleData, $hocKy, $namHoc){
        try {
            // Tính tuanBatDau và tuanKetThuc theo học kỳ
            list($startYear, $endYear) = explode('-', $namHoc);
            
            if($hocKy == 1){
                $tuanBatDau = "$startYear-09-01";
                $tuanKetThuc = "$endYear-01-15";
            } else {
                $tuanBatDau = "$endYear-01-16";
                $tuanKetThuc = "$endYear-05-31";
            }
            
            $this->db->beginTransaction();
            
            // Xóa lịch cũ của học kỳ này
            $this->db->prepare("DELETE FROM THOIKHOABIEU WHERE maLop=? AND hocKy=? AND namHoc=?")
                    ->execute([$maLop, $hocKy, $namHoc]);
            
            // Insert lịch mới
            $sql = "INSERT INTO THOIKHOABIEU (maLop, thu, tiet, maMon, maGV, maPhong, hocKy, namHoc, tuanBatDau, tuanKetThuc)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            
            foreach($scheduleData as $item){
                $stmt->execute([
                    $maLop, $item['thu'], $item['tiet'],
                    $item['maMon'], $item['maGV'], $item['maPhong'] ?? null,
                    $hocKy, $namHoc, $tuanBatDau, $tuanKetThuc
                ]);
            }
            
            $this->db->commit();
            return count($scheduleData);
            
        } catch(Exception $e){
            if($this->db->inTransaction()){
                $this->db->rollBack();
            }
            error_log("SaveScheduleForSemester Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lưu lịch đã xếp vào DB cho TUẦN CỤ THỂ (legacy - giữ lại để tương thích)
     */
    public function saveScheduleForWeek($maLop, $scheduleData, $tbd, $tkt, $hocKy, $namHoc){
        try {
            $this->db->beginTransaction();
            
            // Xóa lịch tuần cũ trong bảng THOIKHOABIEU_TUAN
            $this->db->prepare("DELETE FROM THOIKHOABIEU_TUAN WHERE maLop=? AND tuanBatDau=? AND tuanKetThuc=?")
                    ->execute([$maLop, $tbd, $tkt]);
            
            // Insert lịch mới vào bảng THOIKHOABIEU_TUAN (bảng override theo tuần)
            $sql = "INSERT INTO THOIKHOABIEU_TUAN (maLop, thu, tiet, maMon, maGV, maPhong, hocKy, namHoc, tuanBatDau, tuanKetThuc)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            
            foreach($scheduleData as $item){
                $stmt->execute([
                    $maLop, $item['thu'], $item['tiet'],
                    $item['maMon'], $item['maGV'], $item['maPhong'] ?? null,
                    $hocKy, $namHoc, $tbd, $tkt
                ]);
            }
            
            $this->db->commit();
            return count($scheduleData);
            
        } catch(Exception $e){
            if($this->db->inTransaction()){
                $this->db->rollBack();
            }
            error_log("SaveScheduleForWeek Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy lịch cho tuần cụ thể - ưu tiên THOIKHOABIEU_TUAN, fallback THOIKHOABIEU
     */
    public function getScheduleForWeek($maLop, $tbd, $tkt, $hocKy, $namHoc){
        // Kiểm tra có lịch riêng cho tuần này trong THOIKHOABIEU_TUAN không
        $sql = "SELECT tkb.*, mh.tenMon, gv.maGV, nd.hoVaTen as tenGV, ph.tenPhong
                FROM THOIKHOABIEU_TUAN tkb
                JOIN MONHOC mh ON tkb.maMon = mh.maMon COLLATE utf8_unicode_ci
                JOIN GIAOVIEN gv ON tkb.maGV = gv.maGV COLLATE utf8_unicode_ci
                JOIN NGUOIDUNG nd ON gv.maNguoiDung = nd.maNguoiDung COLLATE utf8_unicode_ci
                LEFT JOIN PHONGHOC ph ON tkb.maPhong = ph.maPhong COLLATE utf8_unicode_ci
                WHERE tkb.maLop = ? AND tkb.tuanBatDau = ? AND tkb.tuanKetThuc = ?
                ORDER BY thu, tiet";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $tbd, $tkt]);
        $weekSchedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(!empty($weekSchedule)){
            return $weekSchedule;
        }
        
        // Nếu không có lịch riêng, lấy từ bảng THOIKHOABIEU (lịch mẫu học kỳ)
        return $this->getByClassSemester($maLop, $hocKy, $namHoc);
    }

    /**
     * Lưu lịch cho CẢ NĂM (từ startDate đến endDate)
     */
    public function saveScheduleForYear($maLop, $scheduleData, $hocKy, $namHoc, $startDate, $endDate){
        try {
            $currentDate = strtotime($startDate);
            $endDateTs = strtotime($endDate);
            $count = 0;

            while($currentDate <= $endDateTs){
                // Tìm thứ 2 của tuần
                $dayOfWeek = date('N', $currentDate);
                if($dayOfWeek != 1){
                    $currentDate = strtotime('next monday', $currentDate);
                }
                
                if($currentDate > $endDateTs) break;

                $tbd = date('Y-m-d', $currentDate);
                $tkt = date('Y-m-d', strtotime($tbd . ' +6 days'));

                // Kiểm tra tuần đã khóa
                if(!$this->isWeekLocked($tbd)){
                    $result = $this->saveScheduleForWeek($maLop, $scheduleData, $tbd, $tkt, $hocKy, $namHoc);
                    if($result !== false){
                        $count++;
                    }
                }

                $currentDate = strtotime($tbd . ' +7 days');
            }

            return $count;
            
        } catch(Exception $e){
            error_log("SaveScheduleForYear Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Hàm cũ - giữ lại để tương thích (nhưng giờ sẽ gọi hàm mới)
     */
    public function autoArrange($maLop, $hocKy, $namHoc){
        // Giờ chỉ trả về preview, không lưu DB
        return $this->autoArrangePreview($maLop, $hocKy, $namHoc);
    }

    private function getPrioritizedSlots($includeAfternoon = false){
        $slots = [];
        
        // Tiết buổi sáng: 1-5, buổi chiều: 6-8
        $maxTiet = $includeAfternoon ? 8 : 5;
        
        // Tạo danh sách tất cả các slot với độ ưu tiên
        for($thu = 2; $thu <= 7; $thu++){
            for($tiet = 1; $tiet <= $maxTiet; $tiet++){
                $priority = 10;
                
                // Ưu tiên buổi sáng
                if($tiet <= 3) $priority += 3;
                elseif($tiet <= 5) $priority += 1;
                else $priority -= 2; // Buổi chiều ít ưu tiên hơn
                
                // Ưu tiên giữa tuần
                if($thu >= 3 && $thu <= 5) $priority += 2;
                
                // Tránh tiết 5 (cuối buổi sáng)
                if($tiet == 5) $priority -= 2;
                
                // Tránh thứ 7
                if($thu == 7) $priority -= 1;
                
                $slots[] = [
                    'thu' => $thu,
                    'tiet' => $tiet,
                    'priority' => $priority
                ];
            }
        }
        
        // Sắp xếp theo độ ưu tiên (cao trước) và thêm yếu tố ngẫu nhiên nhẹ
        usort($slots, function($a, $b){
            $diff = $b['priority'] - $a['priority'];
            if($diff == 0){
                return rand(-1, 1);
            }
            return $diff;
        });
        
        return $slots;
    }

    /* ======================================================================
       KIỂM TRA TUẦN KHÓA
    ====================================================================== */

    public function isWeekLocked($weekStart){
        // Kiểm tra trong bảng khoatuan nếu tồn tại
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM khoatuan WHERE tuanBatDau = ? AND daKhoa = 1");
            $stmt->execute([$weekStart]);
            return $stmt->fetchColumn() > 0;
        } catch(Exception $e){
            // Bảng không tồn tại, trả về false
            return false;
        }
    }

    public function lockWeek($weekStart, $lock = true){
        try {
            // Kiểm tra đã có record chưa
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM khoatuan WHERE tuanBatDau = ?");
            $stmt->execute([$weekStart]);
            $exists = $stmt->fetchColumn() > 0;
            
            if($exists){
                $stmt = $this->db->prepare("UPDATE khoatuan SET daKhoa = ? WHERE tuanBatDau = ?");
                return $stmt->execute([$lock ? 1 : 0, $weekStart]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO khoatuan (tuanBatDau, daKhoa) VALUES (?, ?)");
                return $stmt->execute([$weekStart, $lock ? 1 : 0]);
            }
        } catch(Exception $e){
            return false;
        }
    }

    /* ======================================================================
       THỐNG KÊ
    ====================================================================== */

    public function getScheduleStats($maLop, $hocKy, $namHoc){
        $template = $this->getTemplate($maLop, $hocKy, $namHoc);
        
        $stats = [
            'tongTiet' => count($template),
            'soMon' => 0,
            'soNgay' => 0,
            'monHoc' => []
        ];
        
        $monSet = [];
        $ngaySet = [];
        
        foreach($template as $item){
            $monSet[$item['maMon']] = true;
            $ngaySet[$item['thu']] = true;
            
            if(!isset($stats['monHoc'][$item['maMon']])){
                $stats['monHoc'][$item['maMon']] = [
                    'tenMon' => $item['tenMon'],
                    'soTiet' => 0
                ];
            }
            $stats['monHoc'][$item['maMon']]['soTiet']++;
        }
        
        $stats['soMon'] = count($monSet);
        $stats['soNgay'] = count($ngaySet);
        
        return $stats;
    }
}
?>
