<?php
// views/classroom/view_students.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<style>
/* Reset & Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

a {
    text-decoration: none !important;
}

/* Main Content */
.main-content {
    padding: 30px;
    background: #f5f7fa;
    min-height: 100vh;
}

/* Header Section */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.page-title {
    color: #1a202c;
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #667eea;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4190 100%);
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.btn-success:hover {
    background: linear-gradient(135deg, #0e8678 0%, #2dd46a 100%);
}

.btn-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.btn-info:hover {
    background: linear-gradient(135deg, #3d94e5 0%, #00d9e5 100%);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

/* Dashboard Cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.dashboard-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.dashboard-card:hover::before {
    opacity: 1;
}

.card-icon {
    font-size: 36px;
    margin-bottom: 15px;
    opacity: 0.9;
}

.card-title {
    font-size: 13px;
    color: #718096;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.card-value {
    font-size: 32px;
    font-weight: 700;
    color: #1a202c;
}

/* Attendance Card Special Style */
.attendance-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white !important;
    cursor: pointer;
}

.attendance-card .card-title,
.attendance-card .card-value {
    color: white !important;
}

.attendance-card:hover {
    background: linear-gradient(135deg, #e082ea 0%, #e4465b 100%);
}

/* Alerts */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
}

.alert i {
    font-size: 20px;
}

.alert-danger {
    background: #fff5f5;
    color: #c53030;
    border-left: 4px solid #fc8181;
}

.alert-info {
    background: #ebf8ff;
    color: #2c5282;
    border-left: 4px solid #4299e1;
}

/* Student List Container */
.student-list-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    overflow: hidden;
}

.student-list-header {
    padding: 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-list-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
}

.student-list-body {
    padding: 25px;
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

.data-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.data-table thead th {
    padding: 16px 12px;
    color: black;
    font-weight: 600;
    text-align: left;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table thead th:first-child {
    border-radius: 8px 0 0 0;
}

.data-table thead th:last-child {
    border-radius: 0 8px 0 0;
}

.data-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #e2e8f0;
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table tbody tr:hover {
    background: #f7fafc;
    transform: scale(1.01);
}

.data-table td {
    padding: 16px 12px;
    color: #2d3748;
}

/* Student ID Badge */
.student-id {
    font-family: 'Courier New', monospace;
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    color: #667eea;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

/* Status Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-badge i {
    font-size: 10px;
}

.status-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
}

/* Action Buttons in Table */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #4a5568;
    font-size: 18px;
    margin-bottom: 10px;
}

.empty-state p {
    color: #718096;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-cards {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 15px;
    }

    .content-header {
        padding: 20px;
    }

    .page-title {
        font-size: 22px;
    }

    .dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
    }

    .data-table {
        font-size: 12px;
    }

    .data-table thead th,
    .data-table td {
        padding: 10px 8px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-sm {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .dashboard-cards {
        grid-template-columns: 1fr;
    }

    .card-value {
        font-size: 28px;
    }
}
</style>

<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-users-class"></i>
            <?= htmlspecialchars($classInfo['tenLop']) ?>
        </h1>
        <a href="index.php?controller=classroom&action=index" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-icon" style="color: #667eea;">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Sĩ số</div>
            <div class="card-value"><?= $classInfo['siSo'] ?></div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon" style="color: #48bb78;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="card-title">Khối</div>
            <div class="card-value" style="font-size: 24px;"><?= htmlspecialchars($classInfo['tenKhoi']) ?></div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon" style="color: #ed8936;">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="card-title">Ban</div>
            <div class="card-value" style="font-size: 24px;"><?= htmlspecialchars($classInfo['tenBan']) ?></div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon" style="color: #9f7aea;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="card-title">GVCN</div>
            <div class="card-value" style="font-size: 18px;"><?= htmlspecialchars($classInfo['tenGVCN']) ?></div>
        </div>

        <div class="dashboard-card attendance-card" 
             onclick="window.location.href='index.php?controller=classroom&action=classAttendance&maLop=<?= $classInfo['maLop'] ?>'">
            <div class="card-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="card-title">Điểm danh lớp</div>
            <div class="card-value" style="font-size: 18px;">
                <i class="fas fa-arrow-right"></i> Bắt đầu
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="student-list-container">
        <div class="student-list-header">
            <i class="fas fa-list-ul"></i>
            <h3>Danh sách học sinh</h3>
        </div>
        
        <div class="student-list-body">
            <?php if (empty($studentList)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-friends"></i>
                    <h4>Chưa có học sinh</h4>
                    <p>Lớp học này chưa có học sinh nào được thêm vào.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">STT</th>
                                <th style="width: 110px;">Mã HS</th>
                                <th>Họ và tên</th>
                                <th style="width: 100px;">Giới tính</th>
                                <th style="width: 120px;">Ngày sinh</th>
                                <th style="width: 120px; text-align: center;">SBD</th>
                                <th style="width: 130px; text-align: center;">Trạng thái</th>
                                <th style="width: 200px; text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentList as $index => $student): ?>
                                <tr>
                                    <td style="text-align: center; font-weight: 600; color: #718096;">
                                        <?= $index + 1 ?>
                                    </td>
                                    <td>
                                        <span class="student-id"><?= htmlspecialchars($student['maHS']) ?></span>
                                    </td>
                                    <td>
                                        <strong style="color: #2d3748;"><?= htmlspecialchars($student['hoVaTen']) ?></strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-<?= $student['gioiTinh'] === 'Nam' ? 'mars' : 'venus' ?>" 
                                           style="color: <?= $student['gioiTinh'] === 'Nam' ? '#4299e1' : '#ed64a6' ?>; margin-right: 5px;"></i>
                                        <?= htmlspecialchars($student['gioiTinh']) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt" style="color: #a0aec0; margin-right: 5px;"></i>
                                        <?= date('d/m/Y', strtotime($student['ngaySinh'])) ?>
                                    </td>
                                    <td style="text-align: center; font-weight: 600; color: #4a5568;">
                                        <?= htmlspecialchars($student['soBaoDanh']) ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($student['dangThaiHocTap'] === 'Đang học'): ?>
                                            <span class="status-badge status-success">
                                                <i class="fas fa-circle"></i> Đang học
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-warning">
                                                <i class="fas fa-circle"></i> <?= htmlspecialchars($student['dangThaiHocTap']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="index.php?controller=classroom&action=viewStudent&maHS=<?= $student['maHS'] ?>&maLop=<?= $classInfo['maLop'] ?>" 
                                               class="btn btn-primary btn-sm" 
                                               title="Xem hồ sơ học sinh">
                                                <i class="fas fa-user"></i> Hồ sơ
                                            </a>
                                            <a href="index.php?controller=classroom&action=viewScores&maHS=<?= $student['maHS'] ?>&maLop=<?= $classInfo['maLop'] ?>" 
                                               class="btn btn-success btn-sm" 
                                               title="Xem điểm số">
                                                <i class="fas fa-chart-line"></i> Điểm
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>