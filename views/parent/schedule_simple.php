<?php 
// views/parent/schedule_simple.php
include "views/layout/header.php"; 
include "views/layout/sidebar_parent.php"; 

$maLop = $maLop ?? '';
$tenLop = $tenLop ?? $maLop;
$tkb = $tkb ?? [];
$hocKy = $hocKy ?? 1;
$namHoc = $namHoc ?? '2025-2026';
$week = $week ?? date('Y-\WW');
$tbd = $tbd ?? '';
$tkt = $tkt ?? '';
$hasSchedule = $hasSchedule ?? false;
$error = $error ?? null;
$student_info = $student_info ?? null;
?>

<style>
.schedule-page { padding: 20px; }
.page-title { font-size: 22px; font-weight: 700; color: #2c3e50; margin-bottom: 20px; }
.filter-section { background: #5c6bc0; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
.filter-group label { display: block; margin-bottom: 5px; font-weight: 600; color: white; font-size: 13px; }
.filter-group select, .filter-group input { padding: 8px 12px; border: none; border-radius: 6px; font-size: 14px; }
.status-bar { display: flex; gap: 10px; padding: 12px 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.status-badge { padding: 5px 12px; border-radius: 15px; font-size: 13px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.schedule-container { background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.schedule-grid { display: grid; grid-template-columns: 80px repeat(6, 1fr); gap: 2px; background: #e0e0e0; border-radius: 6px; overflow: hidden; }
.grid-header { background: #5c6bc0; color: white; padding: 12px 8px; text-align: center; font-weight: 600; font-size: 13px; }
.grid-time { background: #f5f5f5; padding: 12px 8px; text-align: center; font-weight: 600; color: #333; }
.grid-cell { background: white; min-height: 70px; padding: 6px; }
.schedule-entry { background: #7986cb; color: white; padding: 8px; border-radius: 6px; height: 100%; display: flex; flex-direction: column; justify-content: center; }
.schedule-entry .subject { font-weight: 600; font-size: 12px; }
.schedule-entry .teacher { font-size: 11px; opacity: 0.9; margin-top: 3px; }
.empty-cell { background: #fafafa; height: 100%; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #ccc; }
.no-schedule { text-align: center; padding: 50px; color: #666; }
.alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; background: #ffebee; color: #c62828; }
</style>

<div class="main-content">
    <div class="schedule-page">
        <div class="page-title">📅 Thời khóa biểu con em</div>

        <!-- Filter -->
        <div class="filter-section">
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

        <!-- Status -->
        <div class="status-bar">
            <?php if($student_info): ?>
                <span class="status-badge">👤 <?= htmlspecialchars($student_info['hoVaTen'] ?? 'Học sinh') ?></span>
            <?php endif; ?>
            <span class="status-badge">🏫 Lớp: <?= htmlspecialchars($tenLop) ?></span>
            <span class="status-badge">📅 <?= htmlspecialchars($tbd) ?> → <?= htmlspecialchars($tkt) ?></span>
        </div>

        <?php if($error): ?>
            <div class="alert">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if(!$hasSchedule): ?>
            <div class="schedule-container">
                <div class="no-schedule">
                    <h3>Chưa có thời khóa biểu</h3>
                    <p>Vui lòng liên hệ nhà trường.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="schedule-container">
                <div class="schedule-grid">
                    <div class="grid-header">Tiết</div>
                    <div class="grid-header">Thứ 2</div>
                    <div class="grid-header">Thứ 3</div>
                    <div class="grid-header">Thứ 4</div>
                    <div class="grid-header">Thứ 5</div>
                    <div class="grid-header">Thứ 6</div>
                    <div class="grid-header">Thứ 7</div>

                    <?php
                    $grid = [];
                    foreach($tkb as $row) {
                        $grid[$row["tiet"]][$row["thu"]] = $row;
                    }

                    for($tiet = 1; $tiet <= 8; $tiet++):
                    ?>
                        <div class="grid-time">Tiết <?= $tiet ?></div>
                        <?php for($thu = 2; $thu <= 7; $thu++):
                            $cell = $grid[$tiet][$thu] ?? null;
                        ?>
                            <div class="grid-cell">
                                <?php if($cell): ?>
                                    <div class="schedule-entry">
                                        <div class="subject"><?= htmlspecialchars($cell['tenMon']) ?></div>
                                        <div class="teacher"><?= htmlspecialchars($cell['tenGV']) ?></div>
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
    window.location.href = `index.php?controller=scheduleView&action=parent&week=${week}&namHoc=${namHoc}`;
}
</script>

<?php include "views/layout/footer.php"; ?>
