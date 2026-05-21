<?php
require_once "models/ExamArrangementModel.php";

class ExamArrangementController {
    private $model;
    
    public function __construct() {
        $this->model = new ExamArrangementModel();
    }
    
    /**
     * Hiển thị giao diện chính
     */
    public function index() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $exams = $this->model->getExams();
        $selectedExam = $_GET['exam'] ?? '';
        
        include "views/department/exam_arrangement_index.php";
    }
    
    /**
     * Lấy thống kê
     */
    public function getStatistics() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        if (!$examId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
            exit;
        }
        
        $stats = $this->model->getStatistics($examId);
        echo json_encode(['success' => true, 'data' => $stats]);
    }
    
    /**
     * Xếp phòng tự động (cải tiến)
     */
    /**
 * Xếp phòng tự động (cải tiến)
 */
public function autoArrangeEnhanced() {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $criteria = $_POST['criteria'] ?? 'phong';
    $examId = $_POST['exam'] ?? '';
    
    if (!$examId) {
        echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
        exit;
    }
    
    // DEBUG: Log input
    error_log("Auto Arrange: examId=$examId, criteria=$criteria");
    
    // Lấy thí sinh chưa xếp phòng
    $students = $this->model->getUnarrangedStudents($examId);
    
    // DEBUG: Log số lượng thí sinh
    error_log("Unarranged students count: " . count($students));
    
    if (empty($students)) {
        echo json_encode(['success' => false, 'message' => 'Không có thí sinh nào cần xếp phòng']);
        exit;
    }
    
    // Lấy phòng thi trống
    $rooms = $this->model->getAvailableRooms(null, null, $examId);
    
    // DEBUG: Log số lượng phòng
    error_log("Available rooms count: " . count($rooms));
    
    if (empty($rooms)) {
        echo json_encode(['success' => false, 'message' => 'Không có phòng thi trống']);
        exit;
    }
    
    $results = [];
    $roomIndex = 0;
    $roomCapacity = [];
    $roomStudents = [];
    
    // Đếm số thí sinh đã xếp trong mỗi phòng
    foreach ($rooms as $room) {
        $roomCapacity[$room['maPhongTS']] = $room['soLuongToiDa'];
        $roomStudents[$room['maPhongTS']] = $room['soLuongHienTai'] ?? 0;
    }
    
    // DEBUG: Log thông tin phòng
    error_log("Room info: " . json_encode($rooms));
    
    // Sắp xếp sinh viên theo tiêu chí
    if ($criteria === 'vitri') {
        // Sắp xếp theo vị trí (ưu tiên vị trí gần)
        usort($students, function($a, $b) {
            return strcmp($a['diaChiHS'] ?? '', $b['diaChiHS'] ?? '');
        });
    } elseif ($criteria === 'nguyenvong') {
        // Sắp xếp theo nguyện vọng
        usort($students, function($a, $b) {
            $aPref = $a['nguyenVongDetails'][0]['thuTu'] ?? 999;
            $bPref = $b['nguyenVongDetails'][0]['thuTu'] ?? 999;
            return $aPref - $bPref;
        });
    }
    // Mặc định: sắp xếp theo số phòng
    
    foreach ($students as $student) {
        $assigned = false;
        
        // DEBUG: Log thông tin thí sinh
        error_log("Processing student: " . $student['maHS'] . " - " . $student['hoTenHS']);
        
        // Thử xếp vào các phòng theo thứ tự
        for ($i = 0; $i < count($rooms); $i++) {
            $currentRoomIndex = ($roomIndex + $i) % count($rooms);
            $room = $rooms[$currentRoomIndex];
            
            // Kiểm tra phòng còn chỗ không
            if ($roomStudents[$room['maPhongTS']] < $roomCapacity[$room['maPhongTS']]) {
                // Tạo số báo danh
                $sequence = $roomStudents[$room['maPhongTS']] + 1;
                $soBaoDanh = $this->model->generateExamNumber($examId, $room['maPhongTS'], $sequence);
                
                // Lấy nguyện vọng 1 nếu có
                $preferenceOrder = 0;
                $schoolName = '';
                if (!empty($student['nguyenVongDetails'])) {
                    $firstPref = $student['nguyenVongDetails'][0];
                    $preferenceOrder = $firstPref['thuTu'] ?? 0;
                    $schoolName = $firstPref['tenTruong'] ?? '';
                }
                
                $results[] = [
                    'studentId' => $student['maHS'],
                    'student' => [
                        'maHS' => $student['maHS'],
                        'hoTenHS' => $student['hoTenHS']
                    ],
                    'roomId' => $room['maPhongTS'],
                    'room' => [
                        'tenPhongTS' => $room['tenPhongTS'],
                        'maPhongTS' => $room['maPhongTS'],
                        'diaDiem' => $room['diaDiem'] ?? ''
                    ],
                    'soBaoDanh' => $soBaoDanh,
                    'preferenceOrder' => $preferenceOrder,
                    'schoolName' => $schoolName
                ];
                
                // Cập nhật đếm
                $roomStudents[$room['maPhongTS']]++;
                $roomIndex = $currentRoomIndex + 1;
                $assigned = true;
                break;
            }
        }
        
        if (!$assigned) {
            echo json_encode([
                'success' => false, 
                'message' => 'Tất cả phòng đều đã đầy',
                'partialResults' => $results
            ]);
            exit;
        }
    }
    
    // DEBUG: Log kết quả
    error_log("Arrangement results count: " . count($results));
    
    echo json_encode([
        'success' => true, 
        'data' => $results, 
        'count' => count($results),
        'message' => 'Đã xếp phòng cho ' . count($results) . ' thí sinh'
    ]);
}
    /**
     * Lưu kết quả xếp tự động
     */
    public function saveAutoArrangement() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $arrangements = json_decode($_POST['arrangements'] ?? '[]', true);
        
        if (empty($arrangements)) {
            echo json_encode(['success' => false, 'message' => 'Không có dữ liệu để lưu']);
            exit;
        }
        
        $result = $this->model->saveAutoArrangement($arrangements);
        echo json_encode($result);
    }
    
    /**
     * Lấy thí sinh chưa xếp phòng
     */
    public function getUnarrangedStudents() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        if (!$examId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
            exit;
        }
        
        $students = $this->model->getUnarrangedStudents($examId);
        
        // Lấy thông tin nguyện vọng cho từng thí sinh
        foreach ($students as &$student) {
            $student['nguyenVongDetails'] = $this->model->getStudentPreferences($student['maHS']);
        }
        
        echo json_encode(['success' => true, 'data' => $students]);
    }
    
    /**
     * Lấy thí sinh chưa xếp phòng theo trường
     */
    public function getUnarrangedBySchool() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        $schoolId = $_POST['schoolId'] ?? null;
        
        if (!$examId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
            exit;
        }
        
        $students = $this->model->getUnarrangedStudentsBySchool($examId, $schoolId);
        echo json_encode(['success' => true, 'data' => $students]);
    }
    
    /**
     * Lấy nguyện vọng thí sinh
     */
    public function getStudentPreferences() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $studentId = $_POST['studentId'] ?? '';
        if (!$studentId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã thí sinh']);
            exit;
        }
        
        $preferences = $this->model->getStudentPreferences($studentId);
        echo json_encode(['success' => true, 'data' => $preferences]);
    }
    
    /**
     * Kiểm tra chỉ tiêu trường
     */
    public function checkSchoolQuota() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        $schoolId = $_POST['schoolId'] ?? '';
        
        if (!$examId || !$schoolId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        
        $quota = $this->model->checkSchoolQuota($examId, $schoolId);
        echo json_encode(['success' => true, 'data' => $quota]);
    }
    
    /**
     * Lấy phòng thi trống
     */
    public function getAvailableRooms() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        $schoolId = $_POST['truong'] ?? null;
        
        if (!$examId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
            exit;
        }
        
        $rooms = $this->model->getAvailableRooms($schoolId, null, $examId);
        echo json_encode(['success' => true, 'data' => $rooms]);
    }
    
    /**
     * Lấy phòng thi theo trường
     */
    public function getRoomsBySchool() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        $schoolId = $_POST['schoolId'] ?? '';
        
        if (!$examId || !$schoolId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        
        // Kiểm tra chỉ tiêu trước
        $quota = $this->model->checkSchoolQuota($examId, $schoolId);
        if ($quota && $quota['conLai'] <= 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Trường đã đủ chỉ tiêu (' . $quota['daXep'] . '/' . $quota['chiTieu'] . ')'
            ]);
            exit;
        }
        
        $rooms = $this->model->getRoomsBySchool($schoolId, $examId);
        
        if (empty($rooms)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Không có phòng thi trống cho trường này'
            ]);
            exit;
        }
        
        echo json_encode(['success' => true, 'data' => $rooms]);
    }
    
    /**
     * Xếp phòng thủ công
     */
    public function manualArrange() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $studentId = $_POST['studentId'] ?? '';
        $roomId = $_POST['roomId'] ?? '';
        $schoolId = $_POST['schoolId'] ?? null;
        
        if (!$studentId || !$roomId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
            exit;
        }
        
        $result = $this->model->manualArrange($studentId, $roomId, $schoolId);
        echo json_encode($result);
    }
    
    /**
     * Tạo phòng thi mới
     */
    /**
 * Tạo phòng thi mới
 */
public function createExamRoom() {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    $examId = $_POST['examId'] ?? '';
    $roomName = $_POST['roomName'] ?? '';
    $location = $_POST['location'] ?? '';
    $capacity = $_POST['capacity'] ?? 30;
    $maTruong = $_POST['maTruong'] ?? null;
    
    if (!$examId || !$roomName || !$capacity) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
        exit;
    }
    
    // Tạo mã phòng tự động
    $maPhongTS = 'PT' . date('Y') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    
    $sql = "INSERT INTO phong_tuyensinh 
            (maPhongTS, maKyTS, maTruong, tenPhongTS, diaDiem, soLuongToiDa, soLuongHienTai, trangThai) 
            VALUES (?, ?, ?, ?, ?, ?, 0, 'ConTrong')";
    
    try {
        $this->model->executeUpdate($sql, [$maPhongTS, $examId, $maTruong, $roomName, $location, $capacity]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Đã tạo phòng thi thành công',
            'maPhongTS' => $maPhongTS
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
}
    /**
     * Xóa phòng thi
     */
    public function deleteExamRoom() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $roomId = $_POST['roomId'] ?? '';
        if (!$roomId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã phòng']);
            exit;
        }
        
        // Kiểm tra xem phòng có thí sinh chưa
        $checkSql = "SELECT COUNT(*) as count FROM hosotuyensinh WHERE maPhongTS = ? AND trangThaiXepPhong = 'DaXep'";
        $result = $this->model->querySingle($checkSql, [$roomId]);
        
        if ($result && $result['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa phòng đã có thí sinh']);
            exit;
        }
        
        $sql = "DELETE FROM phong_tuyensinh WHERE maPhongTS = ?";
        
        try {
            $this->model->execute($sql, [$roomId]);
            echo json_encode(['success' => true, 'message' => 'Đã xóa phòng thi thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Lấy danh sách đã xếp phòng
     */
    public function getArrangedList() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $examId = $_POST['exam'] ?? '';
        $sort = $_POST['sort'] ?? 'phong';
        
        if (!$examId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kỳ thi']);
            exit;
        }
        
        $list = $this->model->getArrangedList($examId, $sort);
        echo json_encode(['success' => true, 'data' => $list]);
    }
    
    /**
     * Hủy xếp phòng
     */
    public function cancelArrangement() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        $studentId = $_POST['studentId'] ?? '';
        if (!$studentId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã thí sinh']);
            exit;
        }
        
        $result = $this->model->cancelArrangement($studentId);
        echo json_encode($result);
    }
    
    /**
     * Xuất danh sách Excel
     */
    public function exportList() {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'department') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        
        $examId = $_GET['exam'] ?? '';
        $sort = $_GET['sort'] ?? 'phong';
        
        if (!$examId) {
            die("Thiếu mã kỳ thi");
        }
        
        $list = $this->model->getArrangedList($examId, $sort);
        
        // Xuất Excel đơn giản
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="danh_sach_da_xep_phong_' . date('Ymd') . '.xls"');
        
        echo '<table border="1">';
        echo '<tr><th>STT</th><th>Số báo danh</th><th>Thí sinh</th><th>Mã HS</th><th>Phòng thi</th><th>Địa điểm</th><th>Nguyện vọng 1</th></tr>';
        
        foreach ($list as $index => $item) {
            echo '<tr>';
            echo '<td>' . ($index + 1) . '</td>';
            echo '<td>' . ($item['soBaoDanh'] ?? '') . '</td>';
            echo '<td>' . ($item['hoTenHS'] ?? '') . '</td>';
            echo '<td>' . ($item['maHS'] ?? '') . '</td>';
            echo '<td>' . ($item['tenPhongTS'] ?? '') . '</td>';
            echo '<td>' . ($item['diaDiem'] ?? '') . '</td>';
            echo '<td>' . ($item['tenTruong1'] ?? '') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }
}
?>