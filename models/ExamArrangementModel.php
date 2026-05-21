<?php
require_once 'BaseModel.php';

class ExamArrangementModel extends BaseModel {
    
    /**
     * Lấy thống kê
     */
    public function getStatistics($examId) {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM hosotuyensinh WHERE maKyTS = ?) as tong,
                (SELECT COUNT(*) FROM hosotuyensinh WHERE maKyTS = ? AND trangThaiXepPhong = 'DaXep') as daXep,
                (SELECT COUNT(*) FROM hosotuyensinh WHERE maKyTS = ? AND (trangThaiXepPhong IS NULL OR trangThaiXepPhong = 'ChuaXep')) as chuaXep";
        
        return $this->querySingle($sql, [$examId, $examId, $examId]);
    }
    
    /**
     * Lấy thí sinh chưa xếp phòng
     */
    /**
 * Lấy thí sinh chưa xếp phòng
 */
public function getUnarrangedStudents($examId) {
    $sql = "SELECT hs.*, nd.hoVaTen as hoTenHS, nd.ngaySinh as ngaySinhHS, 
                   nd.gioiTinh as gioiTinhHS, nd.diaChi as diaChiHS,
                   nd.soDienThoai as soDienThoaiHS, nd.email as emailHS,
                   ph.hoVaTen as hoTenPH, ph.soDienThoai as soDienThoaiPH,
                   qhc.quanHe
            FROM hosotuyensinh hs
            INNER JOIN nguoidung nd ON hs.maHS = nd.maNguoiDung
            LEFT JOIN quanhechild qhc ON hs.maHS = qhc.maHS
            LEFT JOIN phuhuynh phu ON qhc.maPH = phu.maPH
            LEFT JOIN nguoidung ph ON phu.maNguoiDung = ph.maNguoiDung
            WHERE hs.maKyTS = ?
              AND (hs.trangThaiXepPhong IS NULL OR hs.trangThaiXepPhong = 'ChuaXep')
              AND hs.dangThai = 'ChoXetTuyen'
            ORDER BY hs.ngayDangKy";
    
    $students = $this->query($sql, [$examId]);
    
    // Lấy thông tin nguyện vọng cho từng thí sinh
    foreach ($students as &$student) {
        $student['nguyenVongDetails'] = $this->getStudentPreferences($student['maHS']);
    }
    
    return $students;
}
    /**
     * Lấy thí sinh chưa xếp phòng theo trường
     */
    public function getUnarrangedStudentsBySchool($examId, $schoolId) {
        $sql = "SELECT hs.*, nd.hoVaTen as hoTenHS, nd.ngaySinh as ngaySinhHS, 
                       nd.gioiTinh as gioiTinhHS, nd.diaChi as diaChiHS
                FROM hosotuyensinh hs
                INNER JOIN nguoidung nd ON hs.maHS = nd.maNguoiDung
                INNER JOIN nguyenvong_tuyensinh nv ON hs.maHS = nv.maHS
                WHERE hs.maKyTS = ?
                  AND (hs.trangThaiXepPhong IS NULL OR hs.trangThaiXepPhong = 'ChuaXep')
                  AND hs.dangThai = 'ChoXetTuyen'
                  AND nv.maTruong = ?
                ORDER BY hs.ngayDangKy";
        
        $students = $this->query($sql, [$examId, $schoolId]);
        
        // Lấy thông tin nguyện vọng cho từng thí sinh
        foreach ($students as &$student) {
            $student['nguyenVongDetails'] = $this->getStudentPreferences($student['maHS']);
        }
        
        return $students;
    }
    
    /**
     * Lấy nguyện vọng thí sinh
     */
    public function getStudentPreferences($studentId) {
        $sql = "SELECT nv.*, t.tenTruong, t.diaChi as diaChiTruong
                FROM nguyenvong_tuyensinh nv
                LEFT JOIN truonghoc t ON nv.maTruong = t.maTruong
                WHERE nv.maHS = ?
                ORDER BY nv.thuTuNguyenVong";
        
        return $this->query($sql, [$studentId]);
    }
    
    /**
     * Lấy phòng thi trống
     */
    public function getAvailableRooms($schoolId = null, $criteria = null, $examId) {
        $sql = "SELECT pts.*, t.tenTruong, t.diaChi as diaChiTruong,
                       (pts.soLuongToiDa - pts.soLuongHienTai) as soChoTrong
                FROM phong_tuyensinh pts
                LEFT JOIN truonghoc t ON pts.maTruong = t.maTruong
                WHERE pts.maKyTS = ?
                  AND pts.trangThai = 'ConTrong'
                  AND pts.soLuongHienTai < pts.soLuongToiDa";
        
        $params = [$examId];
        
        if ($schoolId) {
            $sql .= " AND (pts.maTruong IS NULL OR pts.maTruong = ?)";
            $params[] = $schoolId;
        }
        
        // Sắp xếp theo tiêu chí
        switch ($criteria) {
            case 'vitri':
                $sql .= " ORDER BY pts.diaDiem, pts.tenPhongTS";
                break;
            case 'sochotrong':
                $sql .= " ORDER BY soChoTrong DESC, pts.tenPhongTS";
                break;
            default:
                $sql .= " ORDER BY pts.tenPhongTS";
        }
        
        return $this->query($sql, $params);
    }
    
    /**
     * Lấy phòng thi theo trường
     */
    public function getRoomsBySchool($schoolId, $examId) {
        $sql = "SELECT pts.*, t.tenTruong,
                       (pts.soLuongToiDa - pts.soLuongHienTai) as soChoTrong
                FROM phong_tuyensinh pts
                LEFT JOIN truonghoc t ON pts.maTruong = t.maTruong
                WHERE pts.maKyTS = ?
                  AND (pts.maTruong IS NULL OR pts.maTruong = ?)
                  AND pts.trangThai = 'ConTrong'
                  AND pts.soLuongHienTai < pts.soLuongToiDa
                ORDER BY pts.soChoTrong DESC";
        
        return $this->query($sql, [$examId, $schoolId]);
    }
    
    /**
     * Xếp phòng thủ công
     */
    /**
 * Xếp phòng thủ công
 */
public function manualArrange($studentId, $roomId, $schoolId = null) {
    try {
        $this->beginTransaction();
        
        // DEBUG
        error_log("Manual Arrange: studentId=$studentId, roomId=$roomId, schoolId=$schoolId");
        
        // 1. Kiểm tra thí sinh
        $checkStudent = "SELECT maHS, maKyTS FROM hosotuyensinh WHERE maHS = ?";
        $student = $this->querySingle($checkStudent, [$studentId]);
        
        error_log("Student check result: " . json_encode($student));
        
        if (!$student) {
            throw new Exception('Thí sinh không tồn tại');
        }
        
        // 2. Kiểm tra phòng
        $checkRoom = "SELECT maPhongTS, soLuongToiDa, soLuongHienTai, maKyTS 
                     FROM phong_tuyensinh 
                     WHERE maPhongTS = ? 
                     AND soLuongHienTai < soLuongToiDa";
        
        $room = $this->querySingle($checkRoom, [$roomId]);
        
        error_log("Room check result: " . json_encode($room));
        
        if (!$room) {
            throw new Exception('Phòng đã đầy hoặc không tồn tại. Room ID: ' . $roomId);
        }
        
        // 3. Tạo số báo danh
        $soBaoDanh = $this->generateExamNumber($student['maKyTS'], $roomId);
        error_log("Generated exam number: $soBaoDanh");
        
        // 4. Cập nhật hồ sơ tuyển sinh
        $updateStudent = "UPDATE hosotuyensinh 
                         SET maPhongTS = ?, 
                             soBaoDanh = ?, 
                             trangThaiXepPhong = 'DaXep',
                             dangThai = 'DaXepPhong'
                         WHERE maHS = ?";
        
        $this->executeUpdate($updateStudent, [$roomId, $soBaoDanh, $studentId]);
        error_log("Updated student record");
        
        // 5. Cập nhật phòng
        $updateRoom = "UPDATE phong_tuyensinh 
                      SET soLuongHienTai = soLuongHienTai + 1,
                          trangThai = CASE 
                            WHEN soLuongHienTai + 1 >= soLuongToiDa THEN 'Day'
                            ELSE 'ConTrong'
                          END
                      WHERE maPhongTS = ?";
        
        $this->executeUpdate($updateRoom, [$roomId]);
        error_log("Updated room record");
        
        $this->commit();
        
        return [
            'success' => true, 
            'soBaoDanh' => $soBaoDanh,
            'message' => 'Đã xếp phòng thành công'
        ];
        
    } catch (Exception $e) {
        $this->rollBack();
        error_log("Manual Arrange Error: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
    
    /**
     * Lưu kết quả xếp tự động
     */
    public function saveAutoArrangement($arrangements) {
        try {
            $this->beginTransaction();
            
            foreach ($arrangements as $arrangement) {
                $studentId = $arrangement['studentId'];
                $roomId = $arrangement['roomId'];
                $soBaoDanh = $arrangement['soBaoDanh'];
                
                // Lấy mã kỳ thi của thí sinh
                $sqlExam = "SELECT maKyTS FROM hosotuyensinh WHERE maHS = ?";
                $exam = $this->querySingle($sqlExam, [$studentId]);
                
                if ($exam) {
                    // Cập nhật hồ sơ
                    $sqlUpdate = "UPDATE hosotuyensinh 
                                 SET maPhongTS = ?, 
                                     soBaoDanh = ?, 
                                     trangThaiXepPhong = 'DaXep',
                                     dangThai = 'DaXepPhong'
                                 WHERE maHS = ?";
                    
                    $this->executeUpdate($sqlUpdate, [$roomId, $soBaoDanh, $studentId]);
                    
                    // Cập nhật phòng
                    $sqlRoom = "UPDATE phong_tuyensinh 
                               SET soLuongHienTai = soLuongHienTai + 1,
                                   trangThai = CASE 
                                      WHEN soLuongHienTai + 1 >= soLuongToiDa THEN 'Day'
                                      ELSE 'ConTrong'
                                   END
                               WHERE maPhongTS = ?";
                    
                    $this->executeUpdate($sqlRoom, [$roomId]);
                }
            }
            
            $this->commit();
            return ['success' => true, 'message' => 'Đã lưu kết quả sắp xếp'];
            
        } catch (Exception $e) {
            $this->rollBack();
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }
    
    /**
     * Lấy danh sách đã xếp phòng
     */
    public function getArrangedList($examId, $sortBy = 'phong') {
        $orderBy = '';
        switch ($sortBy) {
            case 'vitri':
                $orderBy = 'pts.diaDiem, pts.tenPhongTS, hs.soBaoDanh';
                break;
            case 'truong':
                $orderBy = 't.tenTruong, pts.tenPhongTS, hs.soBaoDanh';
                break;
            default:
                $orderBy = 'pts.tenPhongTS, hs.soBaoDanh';
        }
        
        $sql = "SELECT hs.*, nd.hoVaTen as hoTenHS, 
                       nd.ngaySinh as ngaySinhHS, nd.gioiTinh as gioiTinhHS,
                       pts.tenPhongTS, pts.diaDiem,
                       t.tenTruong, t.maTruong,
                       nv.maTruong as nguyenvong1,
                       (SELECT tenTruong FROM truonghoc WHERE maTruong = nv.maTruong) as tenTruong1
                FROM hosotuyensinh hs
                INNER JOIN nguoidung nd ON hs.maHS = nd.maNguoiDung
                LEFT JOIN phong_tuyensinh pts ON hs.maPhongTS = pts.maPhongTS
                LEFT JOIN truonghoc t ON pts.maTruong = t.maTruong
                LEFT JOIN (SELECT maHS, maTruong FROM nguyenvong_tuyensinh WHERE thuTuNguyenVong = 1) nv 
                       ON hs.maHS = nv.maHS
                WHERE hs.maKyTS = ? AND hs.trangThaiXepPhong = 'DaXep'
                ORDER BY {$orderBy}";
        
        return $this->query($sql, [$examId]);
    }
    
    /**
     * Hủy xếp phòng
     */
    public function cancelArrangement($studentId) {
        try {
            $this->beginTransaction();
            
            // Lấy thông tin phòng của thí sinh
            $sqlGetRoom = "SELECT maPhongTS FROM hosotuyensinh WHERE maHS = ? AND trangThaiXepPhong = 'DaXep'";
            $result = $this->querySingle($sqlGetRoom, [$studentId]);
            
            if (!$result) {
                throw new Exception('Thí sinh chưa được xếp phòng');
            }
            
            $roomId = $result['maPhongTS'];
            
            // Cập nhật hồ sơ tuyển sinh
            $sqlUpdateStudent = "UPDATE hosotuyensinh 
                                SET maPhongTS = NULL, 
                                    soBaoDanh = NULL, 
                                    trangThaiXepPhong = 'ChuaXep',
                                    dangThai = 'ChoXetTuyen'
                                WHERE maHS = ?";
            $this->executeUpdate($sqlUpdateStudent, [$studentId]);
            
            // Cập nhật phòng (giảm số lượng)
            $sqlUpdateRoom = "UPDATE phong_tuyensinh 
                             SET soLuongHienTai = GREATEST(soLuongHienTai - 1, 0),
                                 trangThai = CASE 
                                    WHEN GREATEST(soLuongHienTai - 1, 0) < soLuongToiDa THEN 'ConTrong'
                                    ELSE 'Day'
                                 END
                             WHERE maPhongTS = ?";
            $this->executeUpdate($sqlUpdateRoom, [$roomId]);
            
            $this->commit();
            return ['success' => true, 'message' => 'Đã hủy xếp phòng thành công'];
            
        } catch (Exception $e) {
            $this->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Tạo số báo danh
     */
    public function generateExamNumber($examId, $roomId, $sequence = null) {
        // Lấy mã kỳ thi
        $sqlExam = "SELECT maKyTS FROM ky_tuyensinh WHERE maKyTS = ?";
        $exam = $this->querySingle($sqlExam, [$examId]);
        
        // Tạo mã kỳ thi (3 ký tự đầu)
        $examCode = $exam ? substr($exam['maKyTS'], 0, 3) : 'TS';
        
        // Tạo mã phòng (3 chữ số)
        $roomCode = str_pad($roomId, 3, '0', STR_PAD_LEFT);
        
        // Nếu không có sequence, đếm số thí sinh đã xếp trong phòng
        if (!$sequence) {
            $sqlCount = "SELECT COUNT(*) as count FROM hosotuyensinh WHERE maPhongTS = ? AND trangThaiXepPhong = 'DaXep'";
            $count = $this->querySingle($sqlCount, [$roomId]);
            $sequence = $count ? $count['count'] + 1 : 1;
        }
        
        // Tạo mã sequence (3 chữ số)
        $seqCode = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        // Format: Mã kỳ thi + Mã phòng + Sequence
        return strtoupper($examCode . $roomCode . $seqCode);
    }
    
    /**
     * Kiểm tra chỉ tiêu trường
     */
    public function checkSchoolQuota($examId, $schoolId) {
        try {
            // 1. Lấy chỉ tiêu của trường
            $sqlQuota = "SELECT ct.soHocSinh as chiTieu
                        FROM chitieu ct
                        INNER JOIN ky_tuyensinh kts ON ct.namHoc = kts.namHoc
                        WHERE kts.maKyTS = ? AND ct.maTruong = ?";
            
            $quota = $this->querySingle($sqlQuota, [$examId, $schoolId]);
            
            // 2. Đếm số thí sinh đã xếp phòng cho trường này
            $sqlCount = "SELECT COUNT(DISTINCT hs.maHS) as daXep
                        FROM hosotuyensinh hs
                        INNER JOIN nguyenvong_tuyensinh nv ON hs.maHS = nv.maHS
                        INNER JOIN phong_tuyensinh pts ON hs.maPhongTS = pts.maPhongTS
                        WHERE hs.maKyTS = ? 
                        AND hs.trangThaiXepPhong = 'DaXep'
                        AND (nv.maTruong = ? OR pts.maTruong = ?)";
            
            $count = $this->querySingle($sqlCount, [$examId, $schoolId, $schoolId]);
            
            if ($quota) {
                $result = [
                    'chiTieu' => $quota['chiTieu'],
                    'daXep' => $count ? $count['daXep'] : 0,
                    'conLai' => $quota['chiTieu'] - ($count ? $count['daXep'] : 0),
                    'percent' => $quota['chiTieu'] > 0 ? 
                        round((($count ? $count['daXep'] : 0) / $quota['chiTieu']) * 100, 2) : 0
                ];
            } else {
                // Nếu không có chỉ tiêu, coi như không giới hạn
                $result = [
                    'chiTieu' => 999,
                    'daXep' => $count ? $count['daXep'] : 0,
                    'conLai' => 999,
                    'percent' => 0
                ];
            }
            
            return $result;
            
        } catch (Exception $e) {
            // Trả về giá trị mặc định nếu có lỗi
            return [
                'chiTieu' => 999,
                'daXep' => 0,
                'conLai' => 999,
                'percent' => 0
            ];
        }
    }
    
    /**
     * Lấy danh sách kỳ thi tuyển sinh
     */
    public function getExams() {
        $sql = "SELECT maKyTS, tenKyTS, ngayBatDau, ngayKetThuc, trangThai 
                FROM ky_tuyensinh 
                WHERE trangThai = 'DangMo' OR trangThai = 'SapDienRa'
                ORDER BY ngayBatDau DESC";
        
        return $this->query($sql);
    }
    
    /**
     * Thực thi UPDATE/DELETE queries
     */
    protected function executeUpdate($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
?>