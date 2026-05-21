<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=auth&action=login");
    exit;
}

$user = $_SESSION['user'];
$base_url = '/PTUD_HETHONGQUANLYHOCSINHC3';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xem Điểm - Hệ Thống Quản Lý Giáo Dục</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .score-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .table-badge {
            font-size: 14px;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .current-year-badge {
            background: #198754;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<?php include "views/layout/header.php"; ?>

<div class="sidebar">
    <div class="menu-title">
        <?= $user['role'] === 'student' ? 'HỌC SINH' : 'PHỤ HUYNH' ?>
    </div>

    <?php
    if ($user['role'] === 'student') {
        include "views/layout/sidebar_student.php";
    } else {
        include "views/layout/sidebar_parent.php";
    }
    ?>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-content">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">📊 Xem Điểm Học Tập</h1>
        
    </div>

    <!-- Student Information -->
    <?php if (!empty($student_info)): ?>
        <div class="score-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate"></i> Thông tin học sinh
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>Họ tên:</strong> <?= htmlspecialchars($student_info['hoVaTen']) ?></div>
                    <div class="col-md-3"><strong>Mã học sinh:</strong> <?= htmlspecialchars($student_info['maHS']) ?></div>
                    <div class="col-md-3"><strong>Lớp:</strong> <?= htmlspecialchars($student_info['maLop']) ?></div>
                    <div class="col-md-3"><strong>Năm học:</strong> <?= htmlspecialchars($selected_year) ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Semester Selector -->
    <div class="score-card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <strong class="fs-5">Chọn học kỳ:</strong>
                </div>

                <div class="col-md-6">
                    <form method="GET" action="<?= $base_url ?>/index.php" id="semesterForm">
                        <input type="hidden" name="controller" value="scoreView">
                        <input type="hidden" name="action" value="index">

                        <div class="input-group mb-3">
                            <select class="form-select form-select-lg" name="semester_id" id="semesterSelect">
                                <?php foreach ($semesters as $s): ?>
                                    <option
                                        value="<?= $s['id'] ?>"
                                        data-nam-hoc="<?= $s['academic_year'] ?>"
                                        <?= 
                                            // QUAN TRỌNG: Kiểm tra cả học kỳ VÀ năm học
                                            ($selected_semester == $s['id'] && $selected_year == $s['academic_year']) 
                                            ? 'selected' 
                                            : '' 
                                        ?>
                                    >
                                        <?= $s['semester_name'] ?> - <?= $s['academic_year'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            
                        </div>

                        <input type="hidden" name="nam_hoc" id="namHocInput" value="<?= $selected_year ?>">
                    </form>
                    
                    
                </div>

                <div class="col-md-3 text-end">
                    <?php if (!empty($selected_semester)): ?>
                        <form method="POST" action="<?= $base_url ?>/index.php?controller=scoreView&action=exportPDF">
                            <input type="hidden" name="semester_id" value="<?= $selected_semester ?>">
                            <input type="hidden" name="nam_hoc" value="<?= $selected_year ?>">
                            <button class="btn btn-danger btn-lg">
                                <i class="fas fa-file-pdf"></i> Xuất PDF
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= BẢNG ĐIỂM ================= -->
    <?php if (!empty($scores)): ?>
        <div class="score-card table-responsive">
            
            
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Môn học</th>
                        <th>TX</th>
                        <th>15p</th>
                        <th>1 Tiết</th>
                        <th>GK</th>
                        <th>CK</th>
                        <th>TB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $sc): ?>
                        <tr>
                            <td class="text-start fw-bold">
                                <i class="fas fa-book me-2"></i><?= htmlspecialchars($sc['subject_name']) ?>
                            </td>
                            <td><span class="badge bg-primary table-badge"><?= $sc['regular_score'] !== null ? number_format($sc['regular_score'], 1) : 'N/A' ?></span></td>
                            <td><span class="badge bg-info table-badge"><?= $sc['midterm_score'] !== null ? number_format($sc['midterm_score'], 1) : 'N/A' ?></span></td>
                            <td><span class="badge bg-warning table-badge"><?= $sc['final_score'] !== null ? number_format($sc['final_score'], 1) : 'N/A' ?></span></td>
                            <td><span class="badge bg-success table-badge"><?= $sc['giua_ky_score'] !== null ? number_format($sc['giua_ky_score'], 1) : 'N/A' ?></span></td>
                            <td><span class="badge bg-danger table-badge"><?= $sc['cuoi_ky_score'] !== null ? number_format($sc['cuoi_ky_score'], 1) : 'N/A' ?></span></td>
                            <td><span class="badge bg-dark table-badge fw-bold"><?= number_format($sc['average_score'], 1) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- FORMULA INFO -->
        <div class="alert alert-info mt-4">
            <h5><i class="fas fa-calculator me-2"></i>Công thức tính điểm trung bình:</h5>
            <p class="mb-0 fw-bold">
                ĐTBmhk = (Điểm TX + Điểm 15 phút + Điểm 1 tiết×2 + Điểm giữa kỳ×2 + Điểm cuối kỳ×3) / 9
            </p>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center py-5">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <h4>Không có dữ liệu điểm cho học kỳ <?= $selected_semester ?> - năm học <?= $selected_year ?></h4>
            <p class="mb-0">Vui lòng chọn học kỳ khác để xem điểm</p>
        </div>
    <?php endif; ?>

</div>

<footer class="footer text-center">
    © 2025 Hệ thống Quản lý Giáo dục | Nhóm 2 - TH3
</footer>

<!-- JS FIX NAM_HOC -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('semesterSelect');
        const namHocInput = document.getElementById('namHocInput');
        const form = document.getElementById('semesterForm');

        if (select && namHocInput && form) {
            select.addEventListener('change', function () {
                const opt = this.options[this.selectedIndex];
                const selectedNamHoc = opt.dataset.namHoc;
                
                console.log('Selected semester:', this.value);
                console.log('Selected year:', selectedNamHoc);
                
                // Cập nhật giá trị nam_hoc
                namHocInput.value = selectedNamHoc;
                
                // Tự động submit form
                form.submit();
            });
        }
    });
</script>

</body>
</html>