<?php
// views/classroom/index.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<style>
/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

a {
    text-decoration: none !important;
}

/* Main Content - Điều chỉnh để không bị che bởi sidebar */
.main-content {
    margin-left: 250px;
    padding: 30px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
    min-height: calc(100vh - 60px);
    margin-top: 60px;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    animation: float 8s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(20px, 20px); }
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-welcome {
    font-size: 15px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.hero-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.hero-title i {
    font-size: 36px;
}

.hero-subtitle {
    font-size: 15px;
    opacity: 0.9;
    margin-bottom: 20px;
}

.hero-stats {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.hero-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.2);
    padding: 12px 20px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.hero-stat i {
    font-size: 28px;
}

.hero-stat-value {
    font-size: 24px;
    font-weight: 700;
}

.hero-stat-label {
    font-size: 13px;
    opacity: 0.9;
}

/* Alerts */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

.alert-warning {
    background: #fffbeb;
    color: #92400e;
    border-left: 4px solid #f59e0b;
    justify-content: space-between;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pending-badge {
    background: #ef4444;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 700;
    margin: 0 5px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Section Headers */
.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 3px solid #e2e8f0;
}

.section-header h3 {
    color: #1a202c;
    font-size: 22px;
    font-weight: 700;
    margin: 0;
}

.section-header i {
    color: #667eea;
    font-size: 24px;
}

/* Dashboard Cards Grid */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

/* Class Card */
.class-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.class-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
}

.class-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px;
    color: white;
}

.class-name {
    font-size: 22px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.class-name i {
    font-size: 26px;
}

.class-info {
    padding: 20px 24px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #718096;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-label i {
    width: 20px;
    text-align: center;
    color: #667eea;
}

.info-value {
    color: #2d3748;
    font-weight: 600;
    font-size: 15px;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-top: 2px solid #f0f0f0;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 18px 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.3s ease;
    border-right: 1px solid #f0f0f0;
    position: relative;
}

.action-btn:last-child {
    border-right: none;
}

.action-btn::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 0;
    transition: height 0.3s ease;
}

.action-btn i {
    font-size: 28px;
    transition: transform 0.3s ease;
}

.action-btn span {
    font-size: 13px;
}

/* Manage Button */
.btn-manage {
    color: #3498db;
}

.btn-manage::before {
    background: #3498db20;
}

.btn-manage:hover {
    color: #2980b9;
}

.btn-manage:hover::before {
    height: 100%;
}

.btn-manage:hover i {
    transform: rotate(90deg);
}

/* Attendance Button */
.btn-attendance {
    color: #27ae60;
}

.btn-attendance::before {
    background: #27ae6020;
}

.btn-attendance:hover {
    color: #229954;
}

.btn-attendance:hover::before {
    height: 100%;
}

.btn-attendance:hover i {
    transform: scale(1.1);
}

/* Grades Button */
.btn-grades {
    color: #e67e22;
}

.btn-grades::before {
    background: #e67e2220;
}

.btn-grades:hover {
    color: #d35400;
}

.btn-grades:hover::before {
    height: 100%;
}

.btn-grades:hover i {
    transform: translateY(-3px);
}

/* Utility Cards */
.utility-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
}

.utility-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
}

.notification-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.utility-icon {
    font-size: 48px;
    margin-bottom: 15px;
    display: inline-block;
    transition: transform 0.3s ease;
}

.utility-card:hover .utility-icon {
    transform: scale(1.1);
}

.utility-card h4 {
    color: #1a202c;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
}

.utility-card p {
    color: #718096;
    margin-bottom: 20px;
    line-height: 1.6;
}

.pending-info {
    background: #fef3c7;
    color: #92400e;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 700;
    margin-bottom: 20px;
    display: inline-block;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4190 100%);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 16px;
    padding: 60px 30px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 80px;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #2d3748;
    font-size: 20px;
    margin-bottom: 10px;
}

.empty-state p {
    color: #718096;
    font-size: 15px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content {
        margin-left: 0;
        margin-top: 60px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 20px;
    }

    .hero-section {
        padding: 30px 20px;
    }

    .hero-title {
        font-size: 26px;
    }

    .dashboard-cards {
        grid-template-columns: 1fr;
    }

    .quick-actions {
        grid-template-columns: 1fr;
    }

    .action-btn {
        flex-direction: row;
        justify-content: flex-start;
        padding: 16px 20px;
        border-right: none;
        border-bottom: 1px solid #f0f0f0;
    }

    .action-btn:last-child {
        border-bottom: none;
    }

    .alert-warning {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<div class="main-content">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-welcome">Xin chào,</div>
            <h1 class="hero-title">
                <i class="fas fa-chalkboard-teacher"></i>
                Quản lý lớp học
            </h1>
            <p class="hero-subtitle">Hệ thống quản lý giáo viên chủ nhiệm - Năm học 2024-2025</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <i class="fas fa-door-open"></i>
                    <div>
                        <div class="hero-stat-value"><?= count($classList) ?></div>
                        <div class="hero-stat-label">Lớp chủ nhiệm</div>
                    </div>
                </div>
                <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                <div class="hero-stat" style="background: rgba(239, 68, 68, 0.2);">
                    <i class="fas fa-bell"></i>
                    <div>
                        <div class="hero-stat-value"><?= $pendingCount ?></div>
                        <div class="hero-stat-label">Đơn chờ duyệt</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-check-circle"></i>
            <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <!-- Thông báo đơn xin nghỉ -->
    <?php if (isset($pendingCount) && $pendingCount > 0): ?>
        <div class="alert alert-warning">
            <div class="alert-content">
                <i class="fas fa-bell"></i>
                <span>
                    Bạn có <span class="pending-badge"><?= $pendingCount ?></span> đơn xin nghỉ học đang chờ xét duyệt
                </span>
            </div>
            <a href="index.php?controller=leave_request&action=index" class="btn btn-warning">
                <i class="fas fa-eye"></i> Xem ngay
            </a>
        </div>
    <?php endif; ?>

    <!-- Danh sách lớp học -->
    <div class="section-header">
        <i class="fas fa-school"></i>
        <h3>Lớp học của bạn</h3>
    </div>

    <?php if (empty($classList)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h4>Chưa có lớp học</h4>
            <p>Bạn chưa được phân công làm giáo viên chủ nhiệm lớp nào</p>
        </div>
    <?php else: ?>
        <div class="dashboard-cards">
            <?php foreach ($classList as $class): ?>
                <div class="class-card">
                    <div class="class-header">
                        <h3 class="class-name">
                            <i class="fas fa-users"></i>
                            <?= htmlspecialchars($class['tenLop']) ?>
                        </h3>
                    </div>
                    
                    <div class="class-info">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-calendar-alt"></i> Năm học
                            </span>
                            <span class="info-value"><?= htmlspecialchars($class['namHoc']) ?></span>
                        </div>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="index.php?controller=classroom&action=manage&maLop=<?= $class['maLop'] ?>" 
                           class="action-btn btn-manage" 
                           title="Quản lý lớp học">
                            <i class="fas fa-cog"></i>
                            <span>Quản lý</span>
                        </a>
                        
                        <a href="index.php?controller=classroom&action=classAttendance&maLop=<?= $class['maLop'] ?>" 
                           class="action-btn btn-attendance" 
                           title="Điểm danh học sinh">
                            <i class="fas fa-clipboard-check"></i>
                            <span>Điểm danh</span>
                        </a>
                        
                        <a href="index.php?controller=grade&action=viewClassGrades&maLop=<?= $class['maLop'] ?>" 
                           class="action-btn btn-grades" 
                           title="Xem điểm số">
                            <i class="fas fa-chart-line"></i>
                            <span>Điểm số</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Tiện ích khác -->
    <div class="section-header" style="margin-top: 40px;">
        <i class="fas fa-tools"></i>
        <h3>Tiện ích khác</h3>
    </div>

    <div class="dashboard-cards">
        <div class="utility-card">
            <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                <span class="notification-badge"><?= $pendingCount ?></span>
            <?php endif; ?>
            
            <i class="fas fa-file-medical utility-icon" style="color: #667eea;"></i>
            <h4>Đơn xin nghỉ học</h4>
            <p>Xem và duyệt các đơn xin nghỉ của học sinh trong lớp</p>
            
            <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                <div class="pending-info">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $pendingCount ?> đơn chờ xét duyệt
                </div>
            <?php endif; ?>
            
            <a href="index.php?controller=leave_request&action=index" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-arrow-right"></i>
                Truy cập ngay
            </a>
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>