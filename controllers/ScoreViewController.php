<?php
require_once 'models/ScoreModel.php';

class ScoreViewController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new ScoreModel($pdo);
    }

    public function index() {
        // Kiểm tra đăng nhập và vai trò
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $allowed_roles = ['student', 'parent'];
        
        if (!in_array($user['role'], $allowed_roles)) {
            die("Bạn không có quyền truy cập trang này");
        }

        // Lấy mã học sinh từ session
        $maHS = $this->getStudentId($user);

        // Lấy danh sách học kỳ
        $semesters = $this->model->getSemesters($maHS);
        
        // Xử lý lựa chọn học kỳ và năm học
        $selected_semester = $_GET['semester_id'] ?? null;
        $selected_year = $_GET['nam_hoc'] ?? null;
        
        // DEBUG: Log giá trị nhận được
        error_log("GET semester_id: " . ($_GET['semester_id'] ?? 'null'));
        error_log("GET nam_hoc: " . ($_GET['nam_hoc'] ?? 'null'));
        
        // QUAN TRỌNG: Đồng bộ semester_id và nam_hoc
        if ($selected_semester && !$selected_year && !empty($semesters)) {
            // Tìm năm học tương ứng với học kỳ được chọn
            foreach ($semesters as $sem) {
                if ($sem['id'] == $selected_semester) {
                    $selected_year = $sem['academic_year'];
                    error_log("Found year for semester $selected_semester: $selected_year");
                    break;
                }
            }
        }
        
        // Nếu không có cả hai, lấy học kỳ mới nhất
        if (!$selected_semester && !empty($semesters)) {
            $selected_semester = $semesters[0]['id'];
            $selected_year = $semesters[0]['academic_year'];
            error_log("Using latest semester: $selected_semester, year: $selected_year");
        }
        
        // Nếu vẫn không có năm học, lấy từ database
        if (!$selected_year) {
            $latest = $this->model->getLatestSemester($maHS);
            $selected_year = $latest['academic_year'] ?? '2024-2025';
        }
        
        // Lấy dữ liệu điểm với đầy đủ tham số
        error_log("Fetching scores for: semester=$selected_semester, year=$selected_year, maHS=$maHS");
        $scores = $this->model->getScoresOverview($selected_semester, $selected_year, $maHS);
        $student_info = $this->model->getStudentInfo($maHS);
        
        // DEBUG: Log kết quả
        error_log("Scores count: " . count($scores));
        error_log("Selected year for display: $selected_year");

        // Hiển thị view
        require 'views/student/score_view.php';
    }

    public function exportPDF() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $user = $_SESSION['user'];
        $maHS = $this->getStudentId($user);

        $semester_id = $_POST['semester_id'] ?? null;
        $nam_hoc = $_POST['nam_hoc'] ?? null;
        
        if ($semester_id && $nam_hoc) {
            $scores = $this->model->getScoresOverview($semester_id, $nam_hoc, $maHS);
            $student_info = $this->model->getStudentInfo($maHS);
            
            $this->generatePDF($scores, $student_info, $semester_id, $nam_hoc);
        } else {
            header('Location: index.php?controller=scoreView&action=index');
            exit();
        }
    }

    // Hàm lấy mã học sinh từ session
    private function getStudentId($user) {
        if ($user['role'] === 'student') {
            return $user['student_id'] ?? $this->getStudentIdFromUsername($user['username']);
        } elseif ($user['role'] === 'parent') {
            return $this->getStudentIdForParent($user['parent_id'] ?? '');
        }
        
        return 'HS001'; // Fallback
    }

    // Lấy mã học sinh từ username
    private function getStudentIdFromUsername($username) {
        if (strpos($username, 'hs') === 0) {
            return strtoupper($username);
        }
        return 'HS001';
    }

    // Lấy mã học sinh cho phụ huynh
    private function getStudentIdForParent($parentId) {
        if (!$parentId) return 'HS001';
        
        $query = "SELECT maHS FROM quanhechild WHERE maPH = :maPH LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['maPH' => $parentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['maHS'] ?? 'HS001';
    }

    private function generatePDF($scores, $student_info, $semester_id, $nam_hoc) {
        $html = $this->generatePDFContent($scores, $student_info, $semester_id, $nam_hoc);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit();
    }

    private function generatePDFContent($scores, $student_info, $semester_id, $nam_hoc) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Bảng Điểm</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
                th { background-color: #f2f2f2; }
                h1, h3 { text-align: center; color: #333; }
                .student-info { margin-bottom: 20px; }
                .formula { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <h1>BẢNG ĐIỂM HỌC TẬP</h1>
            <h3>Học kỳ ' . $semester_id . ' - Năm học ' . $nam_hoc . '</h3>
            
            <div class="student-info">
                <p><strong>Họ tên:</strong> ' . htmlspecialchars($student_info['hoVaTen']) . '</p>
                <p><strong>Mã học sinh:</strong> ' . htmlspecialchars($student_info['maHS']) . '</p>
                <p><strong>Lớp:</strong> ' . htmlspecialchars($student_info['maLop']) . '</p>
                <p><strong>Năm học:</strong> ' . htmlspecialchars($student_info['namHoc']) . '</p>
            </div>

            <div class="formula">
                <strong>Công thức tính điểm trung bình:</strong><br>
                ĐTBmhk = (Điểm TX + Điểm 15p + Điểm 1 tiết×2 + Điểm giữa kỳ×2 + Điểm cuối kỳ×3) / 9
            </div>
            <hr>';
        
        if (!empty($scores)) {
            $html .= '
            <table>
                <thead>
                    <tr>
                        <th>Môn học</th>
                        <th>Điểm TX</th>
                        <th>Điểm 15p</th>
                        <th>Điểm 1 tiết</th>
                        <th>Điểm GK</th>
                        <th>Điểm CK</th>
                        <th>Điểm TB</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($scores as $score) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($score['subject_name']) . '</td>
                    <td>' . ($score['regular_score'] ? number_format($score['regular_score'], 1) : '-') . '</td>
                    <td>' . ($score['midterm_score'] ? number_format($score['midterm_score'], 1) : '-') . '</td>
                    <td>' . ($score['final_score'] ? number_format($score['final_score'], 1) : '-') . '</td>
                    <td>' . ($score['giua_ky_score'] ? number_format($score['giua_ky_score'], 1) : '-') . '</td>
                    <td>' . ($score['cuoi_ky_score'] ? number_format($score['cuoi_ky_score'], 1) : '-') . '</td>
                    <td><strong>' . number_format($score['average_score'], 1) . '</strong></td>
                </tr>';
            }
            
            $html .= '
                </tbody>
            </table>
            <p style="text-align: right; margin-top: 30px;">
                Ngày xuất: ' . date('d/m/Y H:i') . '
            </p>';
        } else {
            $html .= '<p style="text-align: center;">Không có dữ liệu điểm</p>';
        }
        
        $html .= '
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body>
        </html>';
        
        return $html;
    }
}
?>