<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<style>
/* ===== STYLE QUẢN LÝ THỜI KHÓA BIỂU ===== */
.schedule-page {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* Filter Section */
.filter-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: white;
    font-size: 13px;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 10px 12px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    color: #2c3e50;
}

.mode-toggle {
    display: flex;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    padding: 4px;
}

.mode-btn {
    padding: 8px 16px;
    border: none;
    background: transparent;
    color: white;
    cursor: pointer;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}

.mode-btn.active {
    background: white;
    color: #667eea;
}

/* Status Bar */
.status-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.status-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.status-badge.template {
    background: #e3f2fd;
    color: #1976d2;
}

.status-badge.week {
    background: #fff3e0;
    color: #f57c00;
}

.status-badge.locked {
    background: #ffebee;
    color: #c62828;
}

.status-badge.custom {
    background: #e8f5e9;
    color: #388e3c;
}

.status-actions {
    display: flex;
    gap: 10px;
}

/* Alert Messages */
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

/* Main Container */
.schedule-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

/* Subjects Panel */
.subjects-panel {
    width: 280px;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.subjects-panel h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.subject-item {
    background: #f8f9fa;
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    cursor: grab;
    border-left: 4px solid #3498db;
    transition: all 0.2s;
    position: relative;
}

.subject-item:hover {
    background: #e3f2fd;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
}

.subject-item.dragging {
    opacity: 0.5;
    cursor: grabbing;
}

.subject-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    border-left-color: #bdc3c7;
}

.subject-item .subject-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
}

.subject-item .subject-info {
    font-size: 12px;
    color: #7f8c8d;
}

.subject-item .subject-hours {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #3498db;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.subject-item.full .subject-hours {
    background: #27ae60;
}

.subject-item.disabled .subject-hours {
    background: #bdc3c7;
}

/* Schedule Panel */
.schedule-panel {
    flex: 1;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.schedule-header-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f1f3f4;
}

.schedule-title {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.schedule-subtitle {
    font-size: 13px;
    color: #7f8c8d;
    margin-top: 5px;
}

/* Schedule Grid */
.schedule-grid {
    display: grid;
    grid-template-columns: 80px repeat(6, 1fr);
    gap: 2px;
    background: #e1e5eb;
    border-radius: 8px;
    overflow: hidden;
}

.grid-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 10px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
}

.grid-time {
    background: #f8f9fa;
    padding: 15px 10px;
    text-align: center;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.grid-time .period {
    font-size: 16px;
}

.grid-time .time {
    font-size: 10px;
    color: #7f8c8d;
    margin-top: 2px;
}

.grid-cell {
    background: white;
    min-height: 100px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    display: flex;
    flex-direction: column;
}

.grid-cell:hover {
    background: #f0f8ff;
}

.grid-cell.highlight {
    background: #e3f2fd !important;
    border: 2px dashed #2196f3 !important;
}

.grid-cell.suggested {
    background: #e8f5e9 !important;
    border: 2px dashed #4caf50 !important;
}

.grid-cell.suggested::after {
    content: "✓";
    position: absolute;
    top: 5px;
    right: 5px;
    color: #4caf50;
    font-weight: bold;
    font-size: 14px;
}

.grid-cell.conflict {
    background: #ffebee !important;
    border: 2px dashed #f44336 !important;
}

.grid-cell.locked {
    background: #f5f5f5 !important;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Buổi chiều - style nhẹ nhàng */
.grid-time.afternoon {
    background: #f5f5f5;
    border-top: 2px solid #e0e0e0;
}

.grid-time.afternoon .period {
    color: #ff9800;
}

.grid-cell.afternoon {
    background: #fafafa;
}

.grid-cell.afternoon:hover {
    background: #f5f5f5;
}

/* Schedule Entry */
.schedule-entry {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px;
    border-radius: 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.schedule-entry .entry-subject {
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 5px;
}

.schedule-entry .entry-teacher {
    font-size: 11px;
    opacity: 0.9;
}

.schedule-entry .entry-room {
    font-size: 10px;
    opacity: 0.8;
    margin-top: auto;
}

.schedule-entry .entry-delete {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255,255,255,0.3);
    border: none;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    display: none;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.schedule-entry:hover .entry-delete {
    display: flex;
}

.schedule-entry .entry-delete:hover {
    background: #e74c3c;
}

/* Buttons */
.btn {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-outline {
    background: transparent;
    border: 2px solid currentColor;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-overlay.active {
    display: flex;
}

.modal-box {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
}

.modal-close {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.modal-close:hover {
    background: rgba(255,255,255,0.3);
}

.modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-info {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.form-info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.form-info-item:last-child {
    margin-bottom: 0;
}

.form-info-label {
    color: #7f8c8d;
}

.form-info-value {
    font-weight: 600;
    color: #2c3e50;
}

.conflict-warning {
    background: #fff3e0;
    border: 1px solid #ffcc80;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 20px;
    color: #e65100;
}

.conflict-warning ul {
    margin: 8px 0 0 20px;
    padding: 0;
}

.modal-footer {
    padding: 20px 25px;
    background: #f8f9fa;
    border-radius: 0 0 16px 16px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Apply Year Modal */
.apply-year-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* Responsive */
@media (max-width: 1200px) {
    .schedule-container {
        flex-direction: column;
    }
    
    .subjects-panel {
        width: 100%;
        position: static;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .subjects-panel h3 {
        width: 100%;
    }
    
    .subject-item {
        flex: 1;
        min-width: 200px;
    }
}

@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .schedule-grid {
        font-size: 12px;
    }
    
    .grid-cell {
        min-height: 80px;
        padding: 5px;
    }
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.loading-overlay.active {
    display: flex;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Toast Notification */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 3000;
}

.toast {
    background: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: toastSlideIn 0.3s ease;
    min-width: 300px;
}

.toast.success {
    border-left: 4px solid #27ae60;
}

.toast.error {
    border-left: 4px solid #e74c3c;
}

.toast.warning {
    border-left: 4px solid #f39c12;
}

@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<div class="main-content">
    <div class="schedule-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                📅 Quản lý Thời khóa biểu
            </div>
            <div class="header-actions">
                <button class="btn btn-success" onclick="autoArrange()">
                    ⚙️ Xếp lịch tự động
                </button>
                <?php if(isset($hasPreview) && $hasPreview): ?>
                    <button class="btn btn-info" onclick="confirmWeek()">
                        📅 Lưu tuần này
                    </button>
                    <button class="btn btn-primary" onclick="confirmSchedule()">
                        💾 Lưu học kỳ này
                    </button>
                    <button class="btn btn-warning" onclick="applyToYear()">
                        📆 Áp dụng cả năm
                    </button>
                    <button class="btn btn-secondary" onclick="cancelPreview()">
                        ❌ Hủy
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Preview Alert -->
        <?php if(isset($hasPreview) && $hasPreview): ?>
            <div class="alert alert-info" style="background: #e3f2fd; border: 2px solid #2196f3; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px;">
                <strong>📋 Đang xem lịch GỢI Ý</strong> - Lịch này chưa được lưu vào hệ thống. 
                Bấm <strong>"Lưu học kỳ này"</strong> để lưu cho HK<?= $hocKy ?>, hoặc <strong>"Áp dụng cả năm"</strong> để lưu cho cả HK1 + HK2.
            </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Khối</label>
                <select id="filterKhoi" onchange="onFilterChange('khoi')">
                    <option value="K10" <?= $khoi == "K10" ? "selected" : "" ?>>Khối 10</option>
                    <option value="K11" <?= $khoi == "K11" ? "selected" : "" ?>>Khối 11</option>
                    <option value="K12" <?= $khoi == "K12" ? "selected" : "" ?>>Khối 12</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Lớp</label>
                <select id="filterLop" onchange="onFilterChange('lop')">
                    <?php foreach($lopList as $l): ?>
                        <option value="<?= $l['maLop'] ?>" <?= $maLop == $l['maLop'] ? "selected" : "" ?>>
                            <?= $l['tenLop'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Học kỳ</label>
                <select id="filterHocKy" onchange="onFilterChange('hocKy')">
                    <option value="1" <?= $hocKy == 1 ? "selected" : "" ?>>Học kỳ 1</option>
                    <option value="2" <?= $hocKy == 2 ? "selected" : "" ?>>Học kỳ 2</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Năm học</label>
                <select id="filterNamHoc" onchange="onFilterChange('namHoc')">
                    <option value="2024-2025" <?= $namHoc == "2024-2025" ? "selected" : "" ?>>2024-2025</option>
                    <option value="2025-2026" <?= $namHoc == "2025-2026" ? "selected" : "" ?>>2025-2026</option>
                    <option value="2026-2027" <?= $namHoc == "2026-2027" ? "selected" : "" ?>>2026-2027</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Tuần</label>
                <input type="week" id="filterTuan" value="<?= $week ?>" onchange="onFilterChange('tuan')">
            </div>
        </div>

        <!-- Status Bar -->
        <div class="status-bar">
            <div class="status-info">
                <span class="status-badge week">📅 Học kỳ <?= $hocKy ?> - <?= $namHoc ?></span>
                <span class="status-badge" style="background: #e8f5e9; color: #388e3c;">📆 Tuần: <?= $tbd ?> → <?= $tkt ?></span>
                <?php if(isset($hasPreview) && $hasPreview): ?>
                    <span class="status-badge" style="background: #e3f2fd; color: #1976d2;">📋 Đang xem lịch GỢI Ý</span>
                <?php elseif($hasCustomSchedule): ?>
                    <span class="status-badge custom">✅ Đã có lịch (<?= count($tkb) ?> tiết)</span>
                <?php else: ?>
                    <span class="status-badge" style="background: #fff3e0; color: #f57c00;">⚠️ Chưa có lịch</span>
                <?php endif; ?>
            </div>
            
            <div class="status-actions">
                <?php if($hasCustomSchedule && !(isset($hasPreview) && $hasPreview)): ?>
                    <button class="btn btn-sm btn-danger" onclick="clearSemester()">
                        🗑️ Xóa lịch học kỳ này
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Messages -->
        <?php if(!empty($_SESSION['message'])): ?>
            <div class="alert alert-success">
                ✅ <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                ❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if($isWeekLocked && $mode === 'week'): ?>
            <div class="alert alert-warning">
                🔒 <strong>Tuần đã khóa:</strong> Không thể thay đổi lịch học trong tuần này.
            </div>
        <?php endif; ?>

        <!-- Main Container -->
        <div class="schedule-container">
            <!-- Subjects Panel -->
            <div class="subjects-panel">
                <h3>📚 Môn học</h3>
                <?php foreach($monList as $m): 
                    $hours = $subjectHours[$m['maMon']] ?? ['daXep' => 0, 'toiDa' => 0, 'conLai' => 0, 'vuot' => false];
                    $isFull = $hours['vuot'];
                    $isDisabled = $isFull || ($isWeekLocked && $mode === 'week');
                ?>
                    <div class="subject-item <?= $isDisabled ? 'disabled' : '' ?> <?= $isFull ? 'full' : '' ?>" 
                         draggable="<?= $isDisabled ? 'false' : 'true' ?>"
                         data-mamon="<?= $m['maMon'] ?>"
                         data-tenmon="<?= $m['tenMon'] ?>"
                         data-sotiet="<?= $m['soTiet'] ?>"
                         onclick="showSuggestions('<?= $m['maMon'] ?>')">
                        <div class="subject-name"><?= $m['tenMon'] ?></div>
                        <div class="subject-info">
                            <?= $hours['daXep'] ?>/<?= $hours['toiDa'] ?> tiết
                            <?php if($isFull): ?>
                                <span style="color: #27ae60;">✓ Đủ</span>
                            <?php endif; ?>
                        </div>
                        <span class="subject-hours"><?= $hours['conLai'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Schedule Panel -->
            <div class="schedule-panel">
                <div class="schedule-header-info">
                    <div>
                        <div class="schedule-title">
                            Lịch học lớp <?= $maLop ?>
                        </div>
                        <div class="schedule-subtitle">
                            HK<?= $hocKy ?> - Năm học: <?= $namHoc ?>
                        </div>
                    </div>
                </div>

                <!-- Schedule Grid -->
                <div class="schedule-grid" id="scheduleGrid">
                    <!-- Header Row -->
                    <div class="grid-header">Tiết</div>
                    <div class="grid-header">Thứ 2</div>
                    <div class="grid-header">Thứ 3</div>
                    <div class="grid-header">Thứ 4</div>
                    <div class="grid-header">Thứ 5</div>
                    <div class="grid-header">Thứ 6</div>
                    <div class="grid-header">Thứ 7</div>

                    <!-- Grid Content -->
                    <?php
                    // Tạo grid dữ liệu
                    $grid = [];
                    foreach($tkb as $row){
                        $grid[$row["tiet"]][$row["thu"]] = $row;
                    }

                    $periodTimes = [
                        1 => '7:00 - 7:45',
                        2 => '7:50 - 8:35',
                        3 => '8:50 - 9:35',
                        4 => '9:40 - 10:25',
                        5 => '10:30 - 11:15',
                        // Buổi chiều
                        6 => '13:00 - 13:45',
                        7 => '13:50 - 14:35',
                        8 => '14:50 - 15:35'
                    ];

                    for($tiet = 1; $tiet <= 8; $tiet++):
                    ?>
                        <div class="grid-time <?= $tiet >= 6 ? 'afternoon' : '' ?>">
                            <span class="period">Tiết <?= $tiet ?></span>
                            <span class="time"><?= $periodTimes[$tiet] ?></span>
                        </div>

                        <?php for($thu = 2; $thu <= 7; $thu++):
                            $cell = $grid[$tiet][$thu] ?? null;
                            $cellClass = ($isWeekLocked && $mode === 'week') ? 'locked' : '';
                            if($tiet >= 6) $cellClass .= ' afternoon';
                            $cellData = $cell ? htmlspecialchars(json_encode($cell), ENT_QUOTES, 'UTF-8') : '';
                        ?>
                            <div class="grid-cell <?= $cellClass ?>"
                                 data-thu="<?= $thu ?>"
                                 data-tiet="<?= $tiet ?>"
                                 data-cell="<?= $cellData ?>"
                                 ondragover="onDragOver(event)"
                                 ondragleave="onDragLeave(event)"
                                 ondrop="onDrop(event)"
                                 onclick="onCellClick(this)">
                                
                                <?php if($cell): ?>
                                    <div class="schedule-entry">
                                        <div class="entry-subject"><?= $cell['tenMon'] ?></div>
                                        <div class="entry-teacher">👨‍🏫 <?= $cell['tenGV'] ?></div>
                                        <div class="entry-room">🏠 <?= $cell['tenPhong'] ?? 'Chưa xếp' ?></div>
                                        
                                        <?php if(!$isWeekLocked || $mode === 'template'): ?>
                                            <button class="entry-delete" onclick="deleteEntry(event, <?= $thu ?>, <?= $tiet ?>)" title="Xóa">
                                                ×
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Lịch -->
<div class="modal-overlay" id="scheduleModal">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title" id="modalTitle">Thêm lịch học</span>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <form id="scheduleForm">
                <input type="hidden" name="maLop" id="fmMaLop" value="<?= $maLop ?>">
                <input type="hidden" name="thu" id="fmThu">
                <input type="hidden" name="tiet" id="fmTiet">
                <input type="hidden" name="tbd" id="fmTbd" value="<?= $tbd ?>">
                <input type="hidden" name="tkt" id="fmTkt" value="<?= $tkt ?>">
                <input type="hidden" name="hocKy" value="<?= $hocKy ?>">
                <input type="hidden" name="namHoc" value="<?= $namHoc ?>">
                <input type="hidden" name="mode" value="<?= $mode ?>">
                <input type="hidden" name="is_edit" id="fmIsEdit" value="0">
                <input type="hidden" name="maTKB" id="fmMaTKB">

                <div class="form-info">
                    <div class="form-info-item">
                        <span class="form-info-label">Lớp:</span>
                        <span class="form-info-value"><?= $maLop ?></span>
                    </div>
                    <div class="form-info-item">
                        <span class="form-info-label">Thời gian:</span>
                        <span class="form-info-value" id="infoTime">--</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Môn học *</label>
                    <select name="maMon" id="fmMon" class="form-control" required onchange="onSubjectChange()">
                        <option value="">-- Chọn môn học --</option>
                        <?php foreach($monList as $m): ?>
                            <option value="<?= $m['maMon'] ?>" data-tenmon="<?= $m['tenMon'] ?>">
                                <?= $m['tenMon'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Giáo viên *</label>
                    <select name="maGV" id="fmGV" class="form-control" required onchange="checkConflict()">
                        <option value="">-- Chọn giáo viên --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Phòng học</label>
                    <select name="maPhong" id="fmPhong" class="form-control" onchange="checkConflict()">
                        <option value="">-- Chọn phòng --</option>
                        <?php foreach($roomList as $r): ?>
                            <option value="<?= $r['maPhong'] ?>" <?= ($r['maPhong'] == $defaultRoom) ? 'selected' : '' ?>>
                                <?= $r['tenPhong'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="conflict-warning" id="conflictWarning" style="display: none;">
                    <strong>⚠️ Cảnh báo xung đột:</strong>
                    <ul id="conflictList"></ul>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
            <button type="button" class="btn btn-primary" onclick="saveSchedule()">💾 Lưu lại</button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ===== CONFIGURATION =====
const CONFIG = {
    maLop: '<?= $maLop ?>',
    khoi: '<?= $khoi ?>',
    hocKy: <?= $hocKy ?>,
    namHoc: '<?= $namHoc ?>',
    tbd: '<?= $tbd ?>',
    tkt: '<?= $tkt ?>',
    week: '<?= $week ?>',
    mode: '<?= $mode ?>',
    defaultRoom: '<?= $defaultRoom ?>',
    isWeekLocked: <?= $isWeekLocked ? 'true' : 'false' ?>
};

// Phân công giáo viên bộ môn từ bảng pcgvbm (ưu tiên dùng)
const teacherAssignments = {
    <?php foreach($teacherAssignments as $maMon => $info): ?>
        '<?= $maMon ?>': {maGV: '<?= $info['maGV'] ?>', hoVaTen: '<?= $info['hoVaTen'] ?>'},
    <?php endforeach; ?>
};

// Giáo viên theo môn từ bảng GIAOVIEN (fallback nếu không có phân công)
const gvByMon = {
    <?php foreach($monList as $m): ?>
        '<?= $m['maMon'] ?>': [
            <?php 
            $gvMon = array_filter($gvList, function($g) use ($m) {
                return $g['monGiangDay'] == $m['maMon'];
            });
            foreach($gvMon as $g): ?>
                {maGV: '<?= $g['maGV'] ?>', hoVaTen: '<?= $g['hoVaTen'] ?>'},
            <?php endforeach; ?>
        ],
    <?php endforeach; ?>
};

let draggedSubject = null;
let currentSuggestions = [];

// ===== FILTER FUNCTIONS =====
function onFilterChange(changedField = null) {
    const params = new URLSearchParams({
        controller: 'scheduleAdmin',
        action: 'index',
        khoi: document.getElementById('filterKhoi').value,
        hocKy: document.getElementById('filterHocKy').value,
        namHoc: document.getElementById('filterNamHoc').value,
        tuan: document.getElementById('filterTuan').value,
        mode: CONFIG.mode
    });
    
    // Nếu đổi khối thì không gửi lớp, để controller tự chọn lớp đầu tiên
    if(changedField !== 'khoi') {
        params.append('lop', document.getElementById('filterLop').value);
    }
    
    window.location.href = 'index.php?' + params.toString();
}

function switchMode(newMode) {
    const params = new URLSearchParams({
        controller: 'scheduleAdmin',
        action: 'index',
        khoi: CONFIG.khoi,
        lop: CONFIG.maLop,
        hocKy: CONFIG.hocKy,
        namHoc: CONFIG.namHoc,
        mode: newMode
    });
    
    window.location.href = 'index.php?' + params.toString();
}

// ===== DRAG & DROP =====
document.querySelectorAll('.subject-item:not(.disabled)').forEach(item => {
    item.addEventListener('dragstart', (e) => {
        draggedSubject = {
            maMon: item.dataset.mamon,
            tenMon: item.dataset.tenmon
        };
        item.classList.add('dragging');
    });
    
    item.addEventListener('dragend', () => {
        item.classList.remove('dragging');
        draggedSubject = null;
        clearSuggestions();
    });
});

function onDragOver(e) {
    e.preventDefault();
    const cell = e.currentTarget;
    if(!cell.classList.contains('locked') && !cell.querySelector('.schedule-entry')) {
        cell.classList.add('highlight');
    }
}

function onDragLeave(e) {
    e.currentTarget.classList.remove('highlight');
}

function onDrop(e) {
    e.preventDefault();
    const cell = e.currentTarget;
    cell.classList.remove('highlight');
    
    if(cell.classList.contains('locked')) {
        showToast('Tuần này đã khóa!', 'error');
        return;
    }
    
    if(cell.querySelector('.schedule-entry')) {
        showToast('Ô này đã có môn học!', 'warning');
        return;
    }
    
    if(draggedSubject) {
        openModal(cell, draggedSubject.maMon);
    }
}

// ===== CELL CLICK =====
function onCellClick(cell) {
    if(CONFIG.isWeekLocked && CONFIG.mode === 'week') {
        showToast('Tuần này đã khóa!', 'error');
        return;
    }
    
    const existingEntry = cell.querySelector('.schedule-entry');
    const cellData = cell.dataset.cell ? JSON.parse(cell.dataset.cell) : null;
    
    if(existingEntry && cellData) {
        // Edit mode
        openModal(cell, cellData.maMon, cellData);
    } else {
        // Add mode
        openModal(cell, null);
    }
}

// ===== MODAL FUNCTIONS =====
function openModal(cell, maMon, editData = null) {
    const thu = cell.dataset.thu;
    const tiet = cell.dataset.tiet;
    
    document.getElementById('fmThu').value = thu;
    document.getElementById('fmTiet').value = tiet;
    document.getElementById('infoTime').textContent = `Thứ ${thu} - Tiết ${tiet}`;
    
    if(editData) {
        document.getElementById('modalTitle').textContent = 'Cập nhật lịch học';
        document.getElementById('fmIsEdit').value = '1';
        document.getElementById('fmMaTKB').value = editData.maTKB || editData.maTemplate || '';
        document.getElementById('fmMon').value = editData.maMon;
        document.getElementById('fmPhong').value = editData.maPhong || CONFIG.defaultRoom;
        
        // Load giáo viên và chọn
        onSubjectChange();
        setTimeout(() => {
            document.getElementById('fmGV').value = editData.maGV;
            checkConflict();
        }, 100);
    } else {
        document.getElementById('modalTitle').textContent = 'Thêm lịch học';
        document.getElementById('fmIsEdit').value = '0';
        document.getElementById('fmMaTKB').value = '';
        document.getElementById('fmMon').value = maMon || '';
        document.getElementById('fmGV').innerHTML = '<option value="">-- Chọn giáo viên --</option>';
        document.getElementById('fmPhong').value = CONFIG.defaultRoom;
        
        if(maMon) {
            onSubjectChange();
        }
    }
    
    document.getElementById('conflictWarning').style.display = 'none';
    document.getElementById('scheduleModal').classList.add('active');
}

function closeModal() {
    document.getElementById('scheduleModal').classList.remove('active');
    clearSuggestions();
}

function onSubjectChange() {
    const maMon = document.getElementById('fmMon').value;
    const gvSelect = document.getElementById('fmGV');
    
    gvSelect.innerHTML = '<option value="">-- Chọn giáo viên --</option>';
    
    // Ưu tiên lấy giáo viên từ bảng phân công (pcgvbm)
    if(maMon && teacherAssignments[maMon]) {
        // Có phân công trong pcgvbm - thêm giáo viên được phân công lên đầu
        const assigned = teacherAssignments[maMon];
        const assignedOption = document.createElement('option');
        assignedOption.value = assigned.maGV;
        assignedOption.textContent = assigned.hoVaTen + ' (Được phân công)';
        gvSelect.appendChild(assignedOption);
        
        // Tự động chọn giáo viên được phân công
        gvSelect.value = assigned.maGV;
        checkConflict();
        
        // Vẫn hiển thị các GV khác cùng môn như là lựa chọn phụ
        if(gvByMon[maMon]) {
            gvByMon[maMon].forEach(gv => {
                if(gv.maGV !== assigned.maGV) {
                    const option = document.createElement('option');
                    option.value = gv.maGV;
                    option.textContent = gv.hoVaTen;
                    gvSelect.appendChild(option);
                }
            });
        }
    } else if(maMon && gvByMon[maMon]) {
        // Không có phân công trong pcgvbm - fallback sang gvByMon
        gvByMon[maMon].forEach(gv => {
            const option = document.createElement('option');
            option.value = gv.maGV;
            option.textContent = gv.hoVaTen;
            gvSelect.appendChild(option);
        });
        
        // Nếu chỉ có 1 GV, chọn luôn
        if(gvByMon[maMon].length === 1) {
            gvSelect.value = gvByMon[maMon][0].maGV;
            checkConflict();
        }
    }
}

function checkConflict() {
    const maGV = document.getElementById('fmGV').value;
    const maPhong = document.getElementById('fmPhong').value;
    const thu = document.getElementById('fmThu').value;
    const tiet = document.getElementById('fmTiet').value;
    
    if(!maGV) return;
    
    const params = new URLSearchParams({
        controller: 'scheduleAdmin',
        action: 'checkConflict',
        lop: CONFIG.maLop,
        thu: thu,
        tiet: tiet,
        gv: maGV,
        phong: maPhong,
        tbd: CONFIG.tbd,
        tkt: CONFIG.tkt,
        hocKy: CONFIG.hocKy,
        namHoc: CONFIG.namHoc,
        mode: CONFIG.mode
    });
    
    fetch('index.php?' + params.toString())
        .then(res => res.json())
        .then(conflicts => {
            const warningDiv = document.getElementById('conflictWarning');
            const listDiv = document.getElementById('conflictList');
            
            if(conflicts.length > 0) {
                listDiv.innerHTML = conflicts.map(c => `<li>${c.message}</li>`).join('');
                warningDiv.style.display = 'block';
            } else {
                warningDiv.style.display = 'none';
            }
        })
        .catch(err => console.error('Error checking conflict:', err));
}

// ===== SAVE SCHEDULE =====
function saveSchedule() {
    try {
        // Lấy giá trị trực tiếp từ elements
        const maLop = document.getElementById('fmMaLop').value;
        const maMon = document.getElementById('fmMon').value;
        const maGV = document.getElementById('fmGV').value;
        const thu = document.getElementById('fmThu').value;
        const tiet = document.getElementById('fmTiet').value;
        const tbd = document.getElementById('fmTbd').value;
        const tkt = document.getElementById('fmTkt').value;
        const maPhong = document.getElementById('fmPhong').value;
        const isEdit = document.getElementById('fmIsEdit').value;
        const maTKB = document.getElementById('fmMaTKB').value;
        
        console.log('Values:', {maLop, maMon, maGV, thu, tiet, tbd, tkt, maPhong});
        
        // Validate
        if(!maLop) {
            alert('Lỗi: maLop rỗng! Value=' + maLop);
            return;
        }
        if(!maMon) {
            alert('Lỗi: Vui lòng chọn môn học!');
            return;
        }
        if(!maGV) {
            alert('Lỗi: Vui lòng chọn giáo viên!');
            return;
        }
        
        // Tạo FormData thủ công
        const formData = new FormData();
        formData.append('maLop', maLop);
        formData.append('thu', thu);
        formData.append('tiet', tiet);
        formData.append('maMon', maMon);
        formData.append('maGV', maGV);
        formData.append('hocKy', CONFIG.hocKy);
        formData.append('namHoc', CONFIG.namHoc);
        formData.append('tbd', tbd);
        formData.append('tkt', tkt);
        formData.append('maPhong', maPhong);
        formData.append('mode', CONFIG.mode);
        formData.append('is_edit', isEdit);
        formData.append('maTKB', maTKB);
        
        showLoading();
        
        fetch('index.php?controller=scheduleAdmin&action=save', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(text => {
            console.log('Response:', text);
            hideLoading();
            try {
                const data = JSON.parse(text);
                if(data.success) {
                    showToast(data.message, 'success');
                    closeModal();
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert('Lỗi: ' + data.message + (data.debug ? '\n\nDebug: ' + JSON.stringify(data.debug) : ''));
                    showToast(data.message, 'error');
                }
            } catch(e) {
                alert('Lỗi parse JSON: ' + text);
            }
        })
        .catch(err => {
            hideLoading();
            alert('Fetch error: ' + err.message);
        });
    } catch(e) {
        alert('JavaScript Error: ' + e.message + '\n' + e.stack);
    }
}

// ===== DELETE ENTRY =====
function deleteEntry(e, thu, tiet) {
    e.stopPropagation();
    
    if(!confirm('Bạn có chắc muốn xóa tiết học này?')) return;
    
    const formData = new FormData();
    formData.append('maLop', CONFIG.maLop);
    formData.append('thu', thu);
    formData.append('tiet', tiet);
    formData.append('tbd', CONFIG.tbd);
    formData.append('tkt', CONFIG.tkt);
    formData.append('hocKy', CONFIG.hocKy);
    formData.append('namHoc', CONFIG.namHoc);
    formData.append('mode', CONFIG.mode);
    
    showLoading();
    
    fetch('index.php?controller=scheduleAdmin&action=delete', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if(data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => {
        hideLoading();
        showToast('Có lỗi xảy ra!', 'error');
        console.error(err);
    });
}

// ===== SUGGESTIONS =====
function showSuggestions(maMon) {
    if(CONFIG.isWeekLocked && CONFIG.mode === 'week') {
        showToast('Tuần này đã khóa!', 'error');
        return;
    }
    
    const params = new URLSearchParams({
        controller: 'scheduleAdmin',
        action: 'getSuggestedSlots',
        lop: CONFIG.maLop,
        mon: maMon,
        tuan: CONFIG.week,
        hocKy: CONFIG.hocKy,
        namHoc: CONFIG.namHoc
    });
    
    fetch('index.php?' + params.toString())
        .then(res => res.json())
        .then(suggestions => {
            clearSuggestions();
            
            if(suggestions.length === 0) {
                showToast('Không có ô trống phù hợp!', 'warning');
                return;
            }
            
            suggestions.forEach(slot => {
                const cell = document.querySelector(`[data-thu="${slot.thu}"][data-tiet="${slot.tiet}"]`);
                if(cell && !cell.querySelector('.schedule-entry')) {
                    cell.classList.add('suggested');
                    cell.title = slot.reason;
                    currentSuggestions.push(cell);
                }
            });
        })
        .catch(err => console.error('Error fetching suggestions:', err));
}

function clearSuggestions() {
    currentSuggestions.forEach(cell => {
        cell.classList.remove('suggested', 'highlight');
        cell.title = '';
    });
    currentSuggestions = [];
}

// ===== AUTO ARRANGE =====
function autoArrange() {
    if(!confirm('Tạo lịch gợi ý tự động? Bạn sẽ cần bấm "Lưu tuần này", "Lưu học kỳ này" hoặc "Áp dụng cả năm" để lưu.')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=autoArrange&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&hocKy=${CONFIG.hocKy}&namHoc=${CONFIG.namHoc}`;
    window.location.href = url;
}

// ===== CONFIRM WEEK (Lưu tuần này) =====
function confirmWeek() {
    if(!confirm('Lưu lịch này cho tuần ' + CONFIG.tbd + ' → ' + CONFIG.tkt + '?')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=confirmWeek&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&hocKy=${CONFIG.hocKy}&namHoc=${CONFIG.namHoc}&tuan=${CONFIG.week}`;
    window.location.href = url;
}

// ===== CONFIRM SCHEDULE (Lưu học kỳ này) =====
function confirmSchedule() {
    const hkName = CONFIG.hocKy == 1 ? 'Học kỳ 1' : 'Học kỳ 2';
    if(!confirm('Lưu lịch này cho ' + hkName + ' - ' + CONFIG.namHoc + '?')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=confirmSchedule&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&hocKy=${CONFIG.hocKy}&namHoc=${CONFIG.namHoc}`;
    window.location.href = url;
}

// ===== APPLY TO YEAR (Cả 2 học kỳ) =====
function applyToYear() {
    if(!confirm('Áp dụng lịch này cho CẢ NĂM (HK1 + HK2) - ' + CONFIG.namHoc + '?')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=applyToYear&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&namHoc=${CONFIG.namHoc}`;
    window.location.href = url;
}

// ===== CANCEL PREVIEW =====
function cancelPreview() {
    if(!confirm('Hủy lịch gợi ý này?')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=cancelPreview&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&hocKy=${CONFIG.hocKy}&namHoc=${CONFIG.namHoc}`;
    window.location.href = url;
}

// ===== CLEAR SEMESTER =====
function clearSemester() {
    const hkName = CONFIG.hocKy == 1 ? 'Học kỳ 1' : 'Học kỳ 2';
    if(!confirm('Xóa toàn bộ lịch của ' + hkName + ' - ' + CONFIG.namHoc + '?')) return;
    
    const url = `index.php?controller=scheduleAdmin&action=clearSemester&lop=${CONFIG.maLop}&khoi=${CONFIG.khoi}&hocKy=${CONFIG.hocKy}&namHoc=${CONFIG.namHoc}`;
    window.location.href = url;
}

// ===== UTILITY FUNCTIONS =====
function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span>${type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️'}</span>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Close modal on outside click
document.getElementById('scheduleModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeModal();
    }
});

document.getElementById('applyYearModal').addEventListener('click', function(e) {
    if(e.target === this) {
        closeApplyYearModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') {
        closeModal();
        closeApplyYearModal();
    }
});
</script>

<?php include "views/layout/footer.php"; ?>
