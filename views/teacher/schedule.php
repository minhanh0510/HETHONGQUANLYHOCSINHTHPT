<?php
// views/teacher/schedule.php
$title = $title ?? 'Lịch dạy';
$viewType = $viewType ?? 'week';
$week = $week ?? date('Y-\WW');
$day = $day ?? date('Y-m-d');
$schedule = $schedule ?? [];
$groupedSchedule = $groupedSchedule ?? [];
$hasSchedule = $hasSchedule ?? false;
$error = $error ?? null;
$teacher_info = $teacher_info ?? null;
$user = $user ?? [];
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_teacher.php"; ?>

<style>
    .schedule-container {
        padding: 20px;
    }
    
    .schedule-controls {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    
    .control-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .view-buttons {
        display: flex;
        gap: 10px;
    }
    
    .view-buttons .btn {
        padding: 8px 20px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none !important;
    }
    
    .date-select-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .date-select-group .input-group {
        width: auto;
    }
    
    .schedule-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 0;
    }
    
    .schedule-table th {
        background-color: #4a6572;
        color: white;
        text-align: center;
        padding: 12px 8px;
        font-weight: 500;
        border: none;
        font-size: 14px;
    }
    
    .schedule-table td {
        padding: 10px 8px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        text-align: center;
    }
    
    .time-cell {
        background-color: #f8f9fa;
        font-weight: 500;
        color: #495057;
        width: 80px;
    }
    
    .subject-cell {
        background: #e3f2fd; /* Màu xanh nhạt */
        border-radius: 4px;
        padding: 8px 4px;
        font-weight: 500;
        color: #1565c0; /* Màu xanh đậm cho chữ */
        min-height: 45px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        transition: all 0.2s ease;
        border: 1px solid #bbdefb;
    }
    
    .subject-cell:hover {
        background: #bbdefb;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .subject-name {
        font-weight: 600;
        font-size: 14px;
    }
    
    .class-info {
        font-size: 12px;
        color: #2196f3; /* Xanh sáng hơn */
    }
    
    .room-info {
        font-size: 11px;
        color: #64b5f6; /* Xanh nhạt */
        font-style: italic;
    }
    
    .empty-cell {
        color: #9e9e9e;
        font-style: normal;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        border: 1px dashed #e0e0e0;
    }
    
    .daily-table th {
        background-color: #2c80b9;
    }
    
    .daily-item {
        background: #e3f2fd;
        color: #1565c0;
        padding: 12px;
        border-radius: 4px;
        font-weight: 500;
        border-left: 4px solid #2c80b9;
        margin-bottom: 8px;
    }
    
    .daily-time {
        background: #f8f9fa;
        font-weight: 500;
        color: #495057;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }
    
    .no-schedule {
        text-align: center;
        padding: 40px 20px;
        color: #757575;
    }
    
    .no-schedule i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #bdbdbd;
    }
    
    .stats-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-top: 20px;
        border-left: 4px solid #2c80b9;
    }
    
    .stats-value {
        font-size: 24px;
        font-weight: 600;
        color: #2c80b9;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 13px;
        color: #666;
    }
    
    /* Xóa card thông tin giáo viên - Không cần hiển thị vì header đã có */
    
    /* Responsive */
    @media (max-width: 768px) {
        .control-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .view-buttons {
            justify-content: center;
        }
        
        .date-select-group {
            justify-content: center;
        }
    }
</style>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title mb-3">
            <i class="fas fa-calendar-alt me-2 text-primary"></i>
            <?php echo htmlspecialchars($title); ?>
        </h1>
        
        <!-- ĐÃ XÓA CARD THÔNG TIN GIÁO VIÊN -->
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Bộ điều khiển -->
    <div class="schedule-controls">
        <div class="control-group">
            <div class="view-buttons">
                <a href="index.php?controller=scheduleView&action=teacher&view=week&week=<?php echo htmlspecialchars($week); ?>" 
                   class="btn <?php echo ($viewType == 'week') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <i class="fas fa-calendar-week"></i> Theo tuần
                </a>
                <a href="index.php?controller=scheduleView&action=teacher&view=day&day=<?php echo htmlspecialchars($day); ?>" 
                   class="btn <?php echo ($viewType == 'day') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <i class="fas fa-calendar-day"></i> Theo ngày
                </a>
            </div>
            
            <div class="date-select-group">
                <?php if ($viewType == 'week'): ?>
                    <input type="week" id="weekPicker" class="form-control" 
                           value="<?php echo htmlspecialchars($week); ?>"
                           min="2024-W01" max="2026-W52" style="width: 180px;">
                    <button class="btn btn-primary" onclick="changeWeek()">
                        <i class="fas fa-search"></i> Xem
                    </button>
                <?php else: ?>
                    <input type="date" id="dayPicker" class="form-control" 
                           value="<?php echo htmlspecialchars($day); ?>"
                           min="2024-01-01" max="2026-12-31" style="width: 160px;">
                    <button class="btn btn-primary" onclick="changeDay()">
                        <i class="fas fa-search"></i> Xem
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <?php if ($viewType == 'week'): ?>
        <!-- Xem theo tuần -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Lịch dạy tuần <?php echo htmlspecialchars($week); ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!$hasSchedule): ?>
                    <div class="no-schedule py-5">
                        <i class="fas fa-calendar-times text-muted"></i>
                        <h5 class="text-muted">Không có lịch dạy cho tuần này</h5>
                        <p class="mb-0">Vui lòng chọn tuần khác.</p>
                    </div>
                <?php elseif (empty($groupedSchedule)): ?>
                    <div class="no-schedule py-5">
                        <i class="fas fa-calendar-alt text-muted"></i>
                        <h5 class="text-muted">Tuần này không có tiết dạy nào</h5>
                        <p class="mb-0">Tất cả các tiết dạy đều trống.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered schedule-table">
                            <thead>
                                <tr>
                                    <th class="time-cell">Tiết</th>
                                    <th>Thứ 2</th>
                                    <th>Thứ 3</th>
                                    <th>Thứ 4</th>
                                    <th>Thứ 5</th>
                                    <th>Thứ 6</th>
                                    <th>Thứ 7</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($tiet = 1; $tiet <= 5; $tiet++): ?>
                                <tr>
                                    <td class="time-cell align-middle">
                                        <strong>Tiết <?php echo $tiet; ?></strong>
                                    </td>
                                    <?php for ($thu = 2; $thu <= 7; $thu++): ?>
                                    <td class="align-middle">
                                        <?php 
                                        $found = false;
                                        if (isset($groupedSchedule[$thu])) {
                                            foreach ($groupedSchedule[$thu] as $item) {
                                                if (($item['tiet'] ?? 0) == $tiet) {
                                                    ?>
                                                    <div class="subject-cell">
                                                        <div class="subject-name">
                                                            <?php echo htmlspecialchars($item['tenMon'] ?? ''); ?>
                                                        </div>
                                                        <div class="class-info">
                                                            <?php echo htmlspecialchars($item['tenLop'] ?? $item['maLop'] ?? ''); ?>
                                                        </div>
                                                        <?php if (!empty($item['tenPhong'])): ?>
                                                        <div class="room-info">
                                                            <i class="fas fa-door-closed me-1"></i>
                                                            <?php echo htmlspecialchars($item['tenPhong']); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                    $found = true;
                                                    break;
                                                }
                                            }
                                        }
                                        if (!$found) {
                                            echo '<div class="empty-cell">-</div>';
                                        }
                                        ?>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Xem theo ngày -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Lịch dạy ngày <?php echo date('d/m/Y', strtotime($day)); ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($schedule)): ?>
                    <div class="no-schedule py-5">
                        <i class="fas fa-chalkboard-teacher text-muted"></i>
                        <h5 class="text-muted">Không có lịch dạy cho ngày này</h5>
                        <p class="mb-0">Chúc bạn có một ngày làm việc hiệu quả!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%" class="text-center">Tiết</th>
                                    <th width="35%">Môn học</th>
                                    <th width="35%">Lớp</th>
                                    <th width="15%" class="text-center">Phòng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                usort($schedule, function($a, $b) {
                                    return ($a['tiet'] ?? 0) <=> ($b['tiet'] ?? 0);
                                });
                                
                                foreach ($schedule as $item): 
                                ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="daily-time">
                                            <strong>Tiết <?php echo $item['tiet'] ?? ''; ?></strong>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="daily-item">
                                            <i class="fas fa-book me-2"></i>
                                            <?php echo htmlspecialchars($item['tenMon'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <i class="fas fa-users me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($item['tenLop'] ?? $item['maLop'] ?? ''); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php if (!empty($item['tenPhong'])): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-door-closed me-1"></i>
                                            <?php echo htmlspecialchars($item['tenPhong']); ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Thống kê đơn giản -->
                    <div class="stats-box">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stats-value"><?php echo count($schedule); ?></div>
                                <div class="stats-label">Tổng số tiết</div>
                            </div>
                            <div class="col-4">
                                <div class="stats-value">
                                    <?php 
                                    $uniqueClasses = array_unique(array_column($schedule, 'maLop'));
                                    echo count($uniqueClasses); 
                                    ?>
                                </div>
                                <div class="stats-label">Số lớp dạy</div>
                            </div>
                            <div class="col-4">
                                <div class="stats-value">
                                    <?php 
                                    $uniqueSubjects = array_unique(array_column($schedule, 'tenMon'));
                                    echo count($uniqueSubjects); 
                                    ?>
                                </div>
                                <div class="stats-label">Số môn dạy</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function changeWeek() {
        const week = document.getElementById('weekPicker')?.value;
        if (week) {
            window.location.href = `index.php?controller=scheduleView&action=teacher&view=week&week=${week}`;
        }
    }
    
    function changeDay() {
        const day = document.getElementById('dayPicker')?.value;
        if (day) {
            window.location.href = `index.php?controller=scheduleView&action=teacher&view=day&day=${day}`;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const weekPicker = document.getElementById('weekPicker');
        if (weekPicker && !weekPicker.value) {
            weekPicker.value = '<?php echo htmlspecialchars($week); ?>';
        }
        
        const dayPicker = document.getElementById('dayPicker');
        if (dayPicker && !dayPicker.value) {
            dayPicker.value = '<?php echo htmlspecialchars($day); ?>';
        }
    });
</script>

<?php include "views/layout/footer.php"; ?>