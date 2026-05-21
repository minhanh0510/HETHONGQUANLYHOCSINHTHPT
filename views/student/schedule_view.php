<?php 
// views/student/schedule_view.php
include "views/layout/header.php"; 
include "views/layout/sidebar_student.php"; 

// Khởi tạo biến
$maLop = $maLop ?? '';
$tenLop = $tenLop ?? $maLop;
$tkb = $tkb ?? [];
$hocKy = $hocKy ?? 1;
$namHoc = $namHoc ?? '2025-2026';
$week = $week ?? date('Y-\WW');
$tbd = $tbd ?? '';
$tkt = $tkt ?? '';
$hasSchedule = !empty($tkb);
$error = $error ?? null;

// Xử lý ngày được chọn
$selectedDate = $_GET['ngay'] ?? date('Y-m-d');
// Tính thứ từ ngày được chọn (2-7, CN=8)
$selectedDayOfWeek = (int)date('N', strtotime($selectedDate)); // 1=Mon, 7=Sun
if($selectedDayOfWeek == 7) $selectedDayOfWeek = 8; // CN = 8 trong hệ thống VN
$selectedThu = $selectedDayOfWeek + 1; // Thứ 2 = 2, Thứ 3 = 3, ...
if($selectedThu > 7) $selectedThu = null; // CN không có trong TKB
?>

<style>
/* ===== STYLE THỜI KHÓA BIỂU HỌC SINH ===== */
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

/* Filter Section */
.filter-section {
    background: #5c6bc0;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
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

/* Status Bar */
.status-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

.status-info {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.status-badge.week {
    background: #e3f2fd;
    color: #1976d2;
}

.status-badge.class {
    background: #f3e5f5;
    color: #7b1fa2;
}

/* Schedule Grid */
.schedule-container {
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
    grid-template-columns: 100px repeat(6, 1fr);
    gap: 2px;
    background: #e1e5eb;
    border-radius: 8px;
    overflow: hidden;
}

.grid-header {
    background: #5c6bc0;
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
    font-size: 15px;
}

.grid-time .time {
    font-size: 10px;
    color: #7f8c8d;
    margin-top: 2px;
}

.grid-time.afternoon {
    background: #fff8e1;
}

.grid-cell {
    background: white;
    min-height: 90px;
    padding: 8px;
    display: flex;
    flex-direction: column;
}

.grid-cell.afternoon {
    background: #fffdf5;
}

/* Schedule Entry */
.schedule-entry {
    background: #7986cb;
    color: white;
    padding: 10px;
    border-radius: 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.schedule-entry.afternoon {
    background: #7986cb;
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

/* Empty cell */
.empty-cell {
    background: #f8f9fa;
    height: 100%;
    min-height: 70px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 20px;
}

/* Alert */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-info {
    background: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.alert-warning {
    background: #fff3e0;
    color: #f57c00;
    border: 1px solid #ffe0b2;
}

.alert-danger {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

/* No schedule */
.no-schedule {
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.no-schedule i {
    font-size: 64px;
    color: #bdc3c7;
    margin-bottom: 20px;
}

.no-schedule h3 {
    color: #2c3e50;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .schedule-grid {
        grid-template-columns: 70px repeat(6, 1fr);
        font-size: 12px;
    }
    
    .grid-header, .grid-time {
        padding: 10px 5px;
    }
    
    .schedule-entry {
        padding: 6px;
    }
    
    .schedule-entry .entry-subject {
        font-size: 11px;
    }
    
    .schedule-entry .entry-teacher,
    .schedule-entry .entry-room {
        font-size: 9px;
    }
}

/* Highlight ngày được chọn */
.grid-header.selected-day {
    background: #66bb6a;
}

.grid-header .today-badge {
    display: block;
    font-size: 10px;
    margin-top: 2px;
}

.selected-day-cell {
    background: #e8f8f0 !important;
}

.selected-day-entry {
    background: #66bb6a !important;
}

.selected-day-entry.afternoon {
    background: #66bb6a !important;
}
</style>

<div class="main-content">
    <div class="schedule-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                📅 Thời khóa biểu
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>📅 Chọn ngày</label>
                <input type="date" id="filterNgay" value="<?= htmlspecialchars($selectedDate) ?>" onchange="changeDate()">
            </div>
            
            <div class="filter-group">
                <label>Tuần</label>
                <input type="week" id="filterTuan" value="<?= htmlspecialchars($week) ?>" onchange="changeWeek()">
            </div>
            
            <div class="filter-group">
                <label>Năm học</label>
                <select id="filterNamHoc" onchange="changeWeek()">
                    <option value="2025-2026" <?= $namHoc == "2025-2026" ? "selected" : "" ?>>2025-2026</option>
                    <option value="2024-2025" <?= $namHoc == "2024-2025" ? "selected" : "" ?>>2024-2025</option>
                </select>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="status-bar">
            <div class="status-info">
                <span class="status-badge class">🏫 Lớp: <?= htmlspecialchars($tenLop) ?></span>
                <span class="status-badge week">📅 Tuần: <?= htmlspecialchars($tbd) ?> → <?= htmlspecialchars($tkt) ?></span>
            </div>
        </div>

        <!-- Error Message -->
        <?php if($error): ?>
            <div class="alert alert-danger">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Main Schedule -->
        <?php if(!$hasSchedule): ?>
            <div class="schedule-container">
                <div class="no-schedule">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Chưa có thời khóa biểu</h3>
                    <p>Thời khóa biểu cho tuần này chưa được xếp. Vui lòng liên hệ Ban giám hiệu.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="schedule-container">
                <div class="schedule-header-info">
                    <div>
                        <div class="schedule-title">
                            Thời khóa biểu lớp <?= htmlspecialchars($tenLop) ?>
                        </div>
                        <div class="schedule-subtitle">
                            HK<?= $hocKy ?> - Năm học: <?= htmlspecialchars($namHoc) ?>
                        </div>
                    </div>
                </div>

                <!-- Schedule Grid -->
                <div class="schedule-grid">
                    <!-- Header Row -->
                    <div class="grid-header">Tiết</div>
                    <?php 
                    $dayNames = [2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7'];
                    for($thu = 2; $thu <= 7; $thu++): 
                        $isSelectedDay = ($thu == $selectedThu);
                    ?>
                        <div class="grid-header <?= $isSelectedDay ? 'selected-day' : '' ?>">
                            <?= $dayNames[$thu] ?>
                            <?php if($isSelectedDay): ?>
                                <span class="today-badge">📍</span>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>

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
                        6 => '13:00 - 13:45',
                        7 => '13:50 - 14:35',
                        8 => '14:50 - 15:35'
                    ];

                    for($tiet = 1; $tiet <= 8; $tiet++):
                        $isAfternoon = $tiet >= 6;
                    ?>
                        <div class="grid-time <?= $isAfternoon ? 'afternoon' : '' ?>">
                            <span class="period">Tiết <?= $tiet ?></span>
                            <span class="time"><?= $periodTimes[$tiet] ?></span>
                        </div>

                        <?php for($thu = 2; $thu <= 7; $thu++):
                            $cell = $grid[$tiet][$thu] ?? null;
                            $isSelectedDay = ($thu == $selectedThu);
                        ?>
                            <div class="grid-cell <?= $isAfternoon ? 'afternoon' : '' ?> <?= $isSelectedDay ? 'selected-day-cell' : '' ?>">
                                <?php if($cell): ?>
                                    <div class="schedule-entry <?= $isAfternoon ? 'afternoon' : '' ?> <?= $isSelectedDay ? 'selected-day-entry' : '' ?>">
                                        <div class="entry-subject"><?= htmlspecialchars($cell['tenMon']) ?></div>
                                        <div class="entry-teacher">👨‍🏫 <?= htmlspecialchars($cell['tenGV']) ?></div>
                                        <div class="entry-room">🏠 <?= htmlspecialchars($cell['tenPhong'] ?? 'Chưa xếp') ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-cell">-</div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function changeWeek() {
    const week = document.getElementById('filterTuan').value;
    const namHoc = document.getElementById('filterNamHoc').value;
    const ngay = document.getElementById('filterNgay').value;
    
    window.location.href = `index.php?controller=scheduleView&action=student&week=${week}&namHoc=${namHoc}&ngay=${ngay}`;
}

function changeDate() {
    const ngay = document.getElementById('filterNgay').value;
    const namHoc = document.getElementById('filterNamHoc').value;
    
    // Tính tuần từ ngày được chọn
    const date = new Date(ngay);
    const year = date.getFullYear();
    const startOfYear = new Date(year, 0, 1);
    const days = Math.floor((date - startOfYear) / (24 * 60 * 60 * 1000));
    const weekNum = Math.ceil((days + startOfYear.getDay() + 1) / 7);
    const week = year + '-W' + String(weekNum).padStart(2, '0');
    
    // Cập nhật input tuần
    document.getElementById('filterTuan').value = week;
    
    window.location.href = `index.php?controller=scheduleView&action=student&week=${week}&namHoc=${namHoc}&ngay=${ngay}`;
}
</script>

<?php include "views/layout/footer.php"; ?>
