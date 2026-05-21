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

html, body {
    height: 100%;
    overflow-x: hidden;
}

/* Main Content */
.main-content {
    padding: 20px;
    background: #f5f7fa;
    min-height: calc(100vh - 60px); /* Trừ chiều cao header/footer nếu có */
    width: 82%;
}

/* Header Section */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 100;
}

.page-title {
    color: #1a202c;
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
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
    white-space: nowrap;
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
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.dashboard-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-height: 120px;
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
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.dashboard-card:hover::before {
    opacity: 1;
}

.card-icon {
    font-size: 30px;
    margin-bottom: 12px;
    opacity: 0.9;
}

.card-title {
    font-size: 12px;
    color: #718096;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.card-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a202c;
    line-height: 1.2;
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
    padding: 12px 18px;
    border-radius: 10px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.alert i {
    font-size: 18px;
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
    height: calc(100vh - 320px); /* Tính toán chiều cao động */
    min-height: 400px;
    display: flex;
    flex-direction: column;
}

.student-list-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.student-list-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.student-list-body {
    padding: 0;
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Table Wrapper */
.table-wrapper {
    flex: 1;
    overflow-y: auto;
    padding: 0 20px 20px 20px;
    max-height: calc(100vh - 400px);
}

/* Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    min-width: 900px; /* Đảm bảo bảng đủ rộng */
}

.data-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: sticky;
    top: 0;
    z-index: 10;
}

.data-table thead th {
    padding: 14px 10px;
    color: white;
    font-weight: 600;
    text-align: left;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #4c51bf;
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
}

.data-table td {
    padding: 14px 10px;
    color: #2d3748;
    vertical-align: middle;
    white-space: nowrap;
}

/* Fixed column widths for better scrolling */
.data-table td:nth-child(1) { width: 60px; } /* STT */
.data-table td:nth-child(2) { width: 110px; } /* Mã HS */
.data-table td:nth-child(3) { min-width: 200px; } /* Họ tên */
.data-table td:nth-child(4) { width: 100px; } /* Giới tính */
.data-table td:nth-child(5) { width: 120px; } /* Ngày sinh */
.data-table td:nth-child(6) { width: 100px; } /* SBD */
.data-table td:nth-child(7) { width: 120px; } /* Trạng thái */
.data-table td:nth-child(8) { width: 200px; } /* Thao tác */

/* Student ID Badge */
.student-id {
    font-family: 'Courier New', monospace;
    background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
    color: #667eea;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    text-align: center;
}

/* Status Badges */
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    justify-content: center;
}

.status-badge i {
    font-size: 8px;
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

.action-buttons .btn-sm {
    min-width: 80px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.empty-state i {
    font-size: 48px;
    color: #cbd5e0;
    margin-bottom: 15px;
}

.empty-state h4 {
    color: #4a5568;
    font-size: 16px;
    margin-bottom: 8px;
}

.empty-state p {
    color: #718096;
    font-size: 13px;
    max-width: 300px;
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-cards {
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    }
    
    .card-value {
        font-size: 24px;
    }
}

@media (max-width: 992px) {
    .main-content {
        padding: 15px;
    }
    
    .student-list-container {
        height: calc(100vh - 300px);
    }
}

@media (max-width: 768px) {
    .content-header {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 20px;
    }
    
    .dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .dashboard-card {
        padding: 15px;
        min-height: 110px;
    }
    
    .card-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }
    
    .card-value {
        font-size: 22px;
    }
    
    .student-list-container {
        height: calc(100vh - 280px);
        min-height: 350px;
    }
    
    .student-list-header {
        padding: 15px;
    }
    
    .table-wrapper {
        padding: 0 15px 15px 15px;
    }
    
    .data-table {
        font-size: 13px;
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
    
    /* Adjust column widths for mobile */
    .data-table td:nth-child(1) { width: 50px; }
    .data-table td:nth-child(3) { min-width: 150px; }
    .data-table td:nth-child(8) { width: 150px; }
}

@media (max-width: 576px) {
    .main-content {
        padding: 10px;
    }
    
    .content-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
    
    .student-list-container {
        height: calc(100vh - 350px);
    }
    
    .table-wrapper {
        padding: 0 10px 10px 10px;
        max-height: calc(100vh - 420px);
    }
    
    .data-table {
        font-size: 12px;
    }
    
    .student-id {
        font-size: 11px;
        padding: 3px 8px;
    }
    
    .status-badge {
        font-size: 10px;
        padding: 4px 10px;
    }
}

@media (max-width: 480px) {
    .card-value {
        font-size: 20px;
    }
    
    .data-table td {
        padding: 8px 6px;
    }
    
    .data-table td:nth-child(2),
    .data-table td:nth-child(4),
    .data-table td:nth-child(5),
    .data-table td:nth-child(6) {
        display: none; /* Ẩn cột ít quan trọng trên mobile */
    }
    
    .action-buttons {
        flex-direction: row;
    }
    
    .action-buttons .btn-sm {
        padding: 5px 8px;
        font-size: 11px;
        min-width: 70px;
    }
}

/* Print Styles */
@media print {
    .main-content {
        padding: 0;
        background: white;
    }
    
    .content-header,
    .dashboard-cards,
    .alert,
    .student-list-header {
        display: none;
    }
    
    .student-list-container {
        box-shadow: none;
        height: auto;
    }
    
    .table-wrapper {
        overflow: visible;
        padding: 0;
    }
    
    .data-table {
        min-width: auto;
        font-size: 12pt;
    }
    
    .data-table thead th {
        color: black;
        background: #f0f0f0 !important;
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
            <div class="card-value">6</div>
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
            <h3>Danh sách học sinh (<?= count($studentList) ?> học sinh)</h3>
        </div>
        
        <div class="student-list-body">
            <?php if (empty($studentList)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-friends"></i>
                    <h4>Chưa có học sinh</h4>
                    <p>Lớp học này chưa có học sinh nào được thêm vào.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">STT</th>
                                <th>Mã HS</th>
                                <th>Họ và tên</th>
                                <th>Giới tính</th>
                                <th>Ngày sinh</th>
                                <th style="text-align: center;">SBD</th>
                                <th style="text-align: center;">Trạng thái</th>
                                <th style="text-align: center;">Thao tác</th>
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