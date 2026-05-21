<?php
// KHỞI TẠO BIẾN Ở ĐẦU FILE
$title = $title ?? 'Thời khóa biểu';
$viewType = $viewType ?? 'week';
$week = $week ?? date('Y-\WW');
$day = $day ?? date('Y-m-d');
$schedule = $schedule ?? [];
$groupedSchedule = $groupedSchedule ?? [];
$maLop = $maLop ?? '';
$hasSchedule = $hasSchedule ?? false;
$error = $error ?? null;
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_student.php"; ?>

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
        background: #f1f8ff;
        border-radius: 4px;
        padding: 8px 4px;
        font-weight: 500;
        color: #1a73e8;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .subject-cell.afternoon-subject {
        background: #fff3e0;
        color: #e65100;
    }
    
    .empty-cell {
        color: #6c757d;
        font-style: normal;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
    }
    
    .daily-table th {
        background-color: #28a745;
    }
    
    .daily-subject {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 10px;
        border-radius: 4px;
        font-weight: 500;
        text-align: center;
    }
    
    .daily-time {
        background: #f8f9fa;
        font-weight: 500;
        color: #495057;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
    }
    
    .no-schedule {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .no-schedule i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #adb5bd;
    }
    
    .stats-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .stats-value {
        font-size: 24px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 13px;
        color: #6c757d;
    }
</style>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title mb-4"><?php echo htmlspecialchars($title); ?></h1>
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
                <a href="index.php?controller=scheduleView&action=student&view=week&week=<?php echo htmlspecialchars($week); ?>" 
                   class="btn <?php echo ($viewType == 'week') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <i class="fas fa-calendar-week"></i> Theo tuần
                </a>
                <a href="index.php?controller=scheduleView&action=student&view=day&day=<?php echo htmlspecialchars($day); ?>" 
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
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Thời khóa biểu tuần <?php echo htmlspecialchars($week); ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!$hasSchedule): ?>
                    <div class="no-schedule">
                        <i class="fas fa-calendar-times"></i>
                        <h5>Không có thời khóa biểu cho tuần này</h5>
                        <p class="mb-0">Vui lòng chọn tuần khác.</p>
                    </div>
                <?php elseif (empty($groupedSchedule)): ?>
                    <div class="no-schedule">
                        <i class="fas fa-calendar-alt"></i>
                        <h5>Tuần này không có tiết học nào</h5>
                        <p class="mb-0">Tất cả các tiết học đều trống.</p>
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
                                <!-- Buổi sáng -->
                                <?php for ($tiet = 1; $tiet <= 5; $tiet++): ?>
                                <tr>
                                    <td class="time-cell align-middle">
                                        Tiết <?php echo $tiet; ?>
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
                                                        <?php echo htmlspecialchars($item['tenMon'] ?? ''); ?>
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
                                
                                <!-- Dòng phân cách buổi chiều -->
                                <tr class="table-secondary">
                                    <td colspan="7" class="text-center py-2 fw-bold" style="background: #f0f0f0; color: #666;">
                                        <i class="fas fa-sun me-2"></i> Buổi chiều
                                    </td>
                                </tr>
                                
                                <!-- Buổi chiều -->
                                <?php for ($tiet = 6; $tiet <= 8; $tiet++): ?>
                                <tr>
                                    <td class="time-cell align-middle">
                                        Tiết <?php echo $tiet; ?>
                                    </td>
                                    <?php for ($thu = 2; $thu <= 7; $thu++): ?>
                                    <td class="align-middle">
                                        <?php 
                                        $found = false;
                                        if (isset($groupedSchedule[$thu])) {
                                            foreach ($groupedSchedule[$thu] as $item) {
                                                if (($item['tiet'] ?? 0) == $tiet) {
                                                    ?>
                                                    <div class="subject-cell afternoon-subject">
                                                        <?php echo htmlspecialchars($item['tenMon'] ?? ''); ?>
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
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2"></i>
                    Thời khóa biểu ngày <?php echo date('d/m/Y', strtotime($day)); ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($schedule)): ?>
                    <div class="no-schedule">
                        <i class="fas fa-book"></i>
                        <h5>Không có lịch học cho ngày này</h5>
                        <p class="mb-0">Chúc bạn có một ngày nghỉ ngơi thật vui vẻ!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%" class="text-center">Tiết</th>
                                    <th width="80%">Môn học</th>
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
                                        <div class="daily-time">Tiết <?php echo $item['tiet'] ?? ''; ?></div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="daily-subject">
                                            <?php echo htmlspecialchars($item['tenMon'] ?? ''); ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Thống kê đơn giản -->
                    <div class="stats-box">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stats-value"><?php echo count($schedule); ?></div>
                                <div class="stats-label">Tổng số tiết</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-value">
                                    <?php 
                                    $uniqueSubjects = array_unique(array_column($schedule, 'tenMon'));
                                    echo count($uniqueSubjects); 
                                    ?>
                                </div>
                                <div class="stats-label">Số môn học</div>
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
            window.location.href = `index.php?controller=scheduleView&action=student&view=week&week=${week}`;
        }
    }
    
    function changeDay() {
        const day = document.getElementById('dayPicker')?.value;
        if (day) {
            window.location.href = `index.php?controller=scheduleView&action=student&view=day&day=${day}`;
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