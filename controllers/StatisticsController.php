<?php
require_once "models/Statistics.php";

class StatisticsController {
    private $db;
    private $model;
    
    public function __construct($db) {
        $this->db = $db;
        $this->model = new Statistics($db);
    }
    
    private function requireManagement() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'management') {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }
    
    public function index() {
        $this->requireManagement();
        
        $action = $_GET['action'] ?? 'dashboard';
        
        switch ($action) {
            case 'studentList':
                $this->studentList();
                break;
            case 'teacherList':
                $this->teacherList();
                break;
            case 'assignmentList':
                $this->assignmentList();
                break;
            case 'studentStructure':
                $this->studentStructure();
                break;
            case 'academicStatistics':
                $this->academicStatistics();
                break;
            case 'conductStatistics':
                $this->conductStatistics();
                break;
            case 'dashboard':
            default:
                $this->dashboard();
                break;
        }
    }
    
    public function dashboard() {
        $this->requireManagement();
        
        $statsData = $this->model->getDashboardStatistics();
        $filterOptions = $this->model->getFilterOptions();
        
        include "views/management/statistics_dashboard.php";
    }
    
    public function studentList() {
        $this->requireManagement();
        
        $filterType = $_GET['filter_type'] ?? 'all';
        $filterValue = $_GET['filter_value'] ?? null;
        
        $students = $this->model->getStudentList($filterType, $filterValue);
        $filterOptions = $this->model->getFilterOptions();
        
        include "views/management/statistics_student_list.php";
    }
    
    public function teacherList() {
        $this->requireManagement();
        
        $toChuyenMon = $_GET['to_chuyen_mon'] ?? null;
        
        // DEBUG: Log thông tin filter
        error_log("Filter toChuyenMon: " . $toChuyenMon);
        
        $teachers = $this->model->getTeacherList($toChuyenMon);
        $filterOptions = $this->model->getFilterOptions();
        
        // DEBUG: Kiểm tra dữ liệu
        error_log("Teachers count: " . count($teachers));
        if (!empty($teachers)) {
            error_log("Sample teacher: " . print_r($teachers[0], true));
        } else {
            error_log("No teachers found!");
            // Kiểm tra query trực tiếp
            $sql = "SELECT COUNT(*) as total FROM giaovien";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $total = $stmt->fetchColumn();
            error_log("Total teachers in database: " . $total);
        }
        
        include "views/management/statistics_teacher_list.php";
    }
    
    public function assignmentList() {
    $this->requireManagement();
    
    // Đơn giản hóa bộ lọc - chỉ còn 3 tiêu chí
    $filters = [
        'namHoc' => $_GET['nam_hoc'] ?? '',
        'hocKy' => $_GET['hoc_ky'] ?? '',
        'toChuyenMon' => $_GET['to_chuyen_mon'] ?? ''
    ];
    
    $assignments = $this->model->getAssignmentList($filters);
    $filterOptions = $this->model->getFilterOptions();
    
    include "views/management/statistics_assignment_list.php";
}

    private function exportExcel($data, $title) {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $title . '_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo '<html>';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body>';
        echo '<h2>' . htmlspecialchars($title) . '</h2>';
        echo '<p>Xuất ngày: ' . date('d/m/Y H:i:s') . '</p>';
        
        if (!empty($data)) {
            echo '<table border="1">';
            // Header
            echo '<tr style="background-color: #f2f2f2;">';
            foreach (array_keys($data[0]) as $key) {
                echo '<th>' . htmlspecialchars($this->translateColumnName($key)) . '</th>';
            }
            echo '</tr>';
            
            // Data
            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Không có dữ liệu để xuất</p>';
        }
        
        echo '</body></html>';
        exit;
    }
    
    
    public function studentStructure() {
        $this->requireManagement();
        
        $scopeType = $_GET['scope_type'] ?? 'all';
        $scopeValue = $_GET['scope_value'] ?? null;
        
        $structure = $this->model->getStudentStructure($scopeType, $scopeValue);
        $filterOptions = $this->model->getFilterOptions();
        
        include "views/management/statistics_student_structure.php";
    }
    // Thêm phương thức thống kê học lực
    public function academicStatistics() {
    $this->requireManagement();

    // bước 2: danh sách tiêu chí
    $criteria = [
        'score' => 'Thống kê điểm số',
        'rank_rate' => 'Tỷ lệ học lực toàn trường',
        'compare_grade' => 'So sánh kết quả giữa các khối',
        'compare_class' => 'So sánh học lực giữa các lớp'
    ];

    require "views/statistics/academic_statistics.php";
}
    public function getClassesByYear() {
    $year = $_GET['namHoc'];
    $semester = $_GET['hocKy'];

    $classes = $this->model->getClassesByYear($year, $semester);
    echo json_encode($classes);
}
    public function scoreStatistics() {
    $year = $_POST['namHoc'];
    $semester = $_POST['hocKy'];
    $classId = $_POST['maLop'];
    $type = $_POST['type']; // subject | average

    $data = $this->model->getScoreStatistics(
        $year, $semester, $classId, $type
    );

    require "views/statistics/score_result.php";
}

    
    public function conductStatistics() {
        $this->requireManagement();
        
        $criteria = $_GET['criteria'] ?? 'ty_le_hanh_kiem';
        $filters = [
            'namHoc' => $_GET['nam_hoc'] ?? date('Y'),
            'scope' => $_GET['scope'] ?? 'khoi',
            'maLop' => $_GET['ma_lop'] ?? null
        ];
        
        $stats = $this->model->getConductAttendanceStats($criteria, $filters);
        $filterOptions = $this->model->getFilterOptions();
        
        include "views/management/statistics_conduct.php";
    }
    
    // API endpoints
    public function getQuickStats() {
        $this->requireManagement();
        
        $statsData = $this->model->getQuickStats();
        
        $response = [
            'totalStudents' => $statsData['total_students'] ?? 0,
            'totalTeachers' => $statsData['total_teachers'] ?? 0,
            'totalClasses' => $statsData['total_classes'] ?? 0,
            'avgScore' => $statsData['avg_score'] ?? 0
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Thêm phương thức mới để lấy lớp theo khối
    public function getClassesByGrade() {
        $this->requireManagement();
        
        $maKhoi = $_GET['ma_khoi'] ?? null;
        $data = [];
        
        if ($maKhoi) {
            try {
                $sql = "SELECT maLop, tenLop FROM lop WHERE maKhoi = ? ORDER BY tenLop";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$maKhoi]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("Error getting classes by grade: " . $e->getMessage());
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public function getFilterOptions() {
        $this->requireManagement();
        $type = $_GET['type'] ?? '';
        
        switch ($type) {
            case 'class':
                $data = $this->model->getClassOptions();
                break;
            case 'grade':
                $data = $this->model->getGradeOptions();
                break;
            case 'subject':
                $data = $this->model->getSubjectOptions();
                break;
            case 'school_year':
                $data = $this->model->getSchoolYearOptions();
                break;
            case 'specialized_group':
                $data = $this->model->getSpecializedGroupOptions();
                break;
            case 'class_by_grade':
                $maKhoi = $_GET['ma_khoi'] ?? null;
                $data = $maKhoi ? $this->model->getClassByGrade($maKhoi) : [];
                break;
            default:
                $data = $this->model->getFilterOptions();
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    // Xuất báo cáo
    public function export() {
        $this->requireManagement();
        
        $type = $_GET['type'] ?? 'excel';
        $reportType = $_GET['report_type'] ?? 'student_list';
        $data = [];
        $title = '';
        
        try {
            switch ($reportType) {
                case 'student_list':
                    $filterType = $_GET['filter_type'] ?? 'all';
                    $filterValue = $_GET['filter_value'] ?? null;
                    $data = $this->model->getStudentList($filterType, $filterValue);
                    $title = "Danh sách học sinh";
                    break;
                case 'teacher_list':
                    $toChuyenMon = $_GET['to_chuyen_mon'] ?? null;
                    $data = $this->model->getTeacherList($toChuyenMon);
                    $title = "Danh sách giáo viên";
                    break;
                case 'student_structure':
                    $scopeType = $_GET['scope_type'] ?? 'all';
                    $scopeValue = $_GET['scope_value'] ?? null;
                    $data = $this->model->getStudentStructure($scopeType, $scopeValue);
                    $title = "Thống kê cơ cấu học sinh";
                    break;
                case 'academic_stats':
                    $criteria = $_GET['criteria'] ?? 'ty_le_hoc_luc';
                    $filters = [
                        'namHoc' => $_GET['nam_hoc'] ?? date('Y'),
                        'hocKy' => $_GET['hoc_ky'] ?? 0
                    ];
                    $data = $this->model->getAcademicStatistics($criteria, $filters);
                    $title = "Thống kê học lực";
                    break;
                case 'conduct_stats':
                    $criteria = $_GET['criteria'] ?? 'ty_le_hanh_kiem';
                    $filters = [
                        'namHoc' => $_GET['nam_hoc'] ?? date('Y')
                    ];
                    $data = $this->model->getConductAttendanceStats($criteria, $filters);
                    $title = "Thống kê hạnh kiểm";
                    break;
                // Trong phương thức export()
                case 'assignment_list':
                    $filters = [
                        'namHoc' => $_GET['nam_hoc'] ?? '',
                        'hocKy' => $_GET['hoc_ky'] ?? '',
                        'toChuyenMon' => $_GET['to_chuyen_mon'] ?? ''
                    ];
                    $data = $this->model->getAssignmentList($filters);
                    $title = "Danh sách phân công giảng dạy";
                    break;
            }
            
            if ($type === 'excel') {
                $this->exportExcel($data, $title);
            } else {
                $this->exportPDF($data, $title);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?controller=statistics&action=$reportType");
            exit;
        }
    }
    // Thêm vào class StatisticsController

private function getSchoolYearOptions() {
    try {
        $sql = "SELECT DISTINCT namHoc as value, namHoc as label 
                FROM pcgvbm 
                WHERE namHoc IS NOT NULL AND namHoc != '' 
                ORDER BY namHoc DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting school year options: " . $e->getMessage());
        return [];
    }
}


    private function exportPDF($data, $title) {
        // Cần cài đặt thư viện TCPDF
        // Đây là code mẫu sử dụng TCPDF
        require_once('tcpdf/tcpdf.php');
        
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Hệ thống Quản lý Trường học');
        $pdf->SetAuthor('Ban Giám hiệu');
        $pdf->SetTitle($title);
        $pdf->SetSubject('Báo cáo thống kê');
        
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 10);
        
        $html = '<h1>' . $title . '</h1>';
        $html .= '<p><strong>Ngày xuất:</strong> ' . date('d/m/Y H:i:s') . '</p>';
        
        if (!empty($data)) {
            $html .= '<table border="1" cellpadding="4">';
            $html .= '<tr style="background-color:#f2f2f2;">';
            foreach (array_keys($data[0]) as $key) {
                $html .= '<th><strong>' . $this->translateColumnName($key) . '</strong></th>';
            }
            $html .= '</tr>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . $cell . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>Không có dữ liệu để xuất</p>';
        }
        
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($title . '_' . date('YmdHis') . '.pdf', 'D');
        exit;
    }
    
    private function translateColumnName($column) {
        $translations = [
            'maHS' => 'Mã HS',
            'hoVaTen' => 'Họ và tên',
            'gioiTinh' => 'Giới tính',
            'tenLop' => 'Lớp',
            'tenKhoi' => 'Khối',
            'dangThaiHocTap' => 'Trạng thái',
            'trangThaiPhanCong' => 'Phân công',
            'maGV' => 'Mã GV',
            'hoTenGV' => 'Tên giáo viên',
            'toChuyenMon' => 'Tổ chuyên môn',
            'monGiangDay' => 'Môn giảng dạy',
            'soLopPhuTrach' => 'Số lớp phụ trách',
            'soMonPhuTrach' => 'Số môn phụ trách',
            'tenMon' => 'Môn học',
            'soTietTuan' => 'Số tiết/tuần',
            'tongSo' => 'Tổng số',
            'soNam' => 'Số nam',
            'soNu' => 'Số nữ',
            'tyLeNam' => 'Tỷ lệ nam %',
            'tyLeNu' => 'Tỷ lệ nữ %',
            'diemTBHK' => 'Điểm TB HK',
            'diemTBNam' => 'Điểm TB năm',
            'gioi' => 'Giỏi',
            'kha' => 'Khá',
            'trungBinh' => 'Trung bình',
            'yeu' => 'Yếu',
            'tyLeGioi' => 'Tỷ lệ giỏi %',
            'tyLeKha' => 'Tỷ lệ khá %',
            'tyLeTrungBinh' => 'Tỷ lệ TB %',
            'tyLeYeu' => 'Tỷ lệ yếu %',
            'xepLoai' => 'Xếp loại',
            'soLuong' => 'Số lượng',
            'tyLePhanTram' => 'Tỷ lệ %',
            'tongBuoi' => 'Tổng buổi',
            'soBuoiVang' => 'Số buổi vắng',
            'tyLeVangPhanTram' => 'Tỷ lệ vắng %',
            'soLanViPham' => 'Số lần vi phạm',
            'cacViPham' => 'Các vi phạm',
            'maPCGVBM' => 'Mã PC',
            'maLop' => 'Mã lớp',
            'maMon' => 'Mã môn',
            'namHoc' => 'Năm học',
            'hocKy' => 'Học kỳ'
        ];
        
        return $translations[$column] ?? $column;
    }
}
?>