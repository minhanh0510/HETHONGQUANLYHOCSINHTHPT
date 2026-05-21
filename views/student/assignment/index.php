<?php
// views/student/assignment/index.php

// KHỞI TẠO BIẾN
$baiTapList = $baiTapList ?? [];
$monHocList = $monHocList ?? [];
$statistics = $statistics ?? ['total' => 0, 'pending' => 0, 'submitted' => 0, 'expired' => 0];
$user = $user ?? [];
$displayName = $user['name'] ?? $user['hoVaTen'] ?? 'Học sinh';
$avatarText = 'HS';
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_student.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">
            <i class="fas fa-tasks"></i> Bài Tập & Kiểm Tra
        </div>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Thống kê -->
    <div class="dashboard-cards" style="margin-bottom:20px;">
        <div class="dashboard-card">
            <div class="card-icon">📋</div>
            <div class="card-title">Tổng bài tập</div>
            <div class="card-value"><?php echo $statistics['total']; ?></div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon">⏰</div>
            <div class="card-title">Chưa nộp</div>
            <div class="card-value"><?php echo $statistics['pending']; ?></div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon">✅</div>
            <div class="card-title">Đã nộp</div>
            <div class="card-value"><?php echo $statistics['submitted']; ?></div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon">❌</div>
            <div class="card-title">Quá hạn</div>
            <div class="card-value"><?php echo $statistics['expired']; ?></div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-section">
        <form id="filterForm" method="GET" action="index.php" class="w-100">
            <input type="hidden" name="controller" value="assignmentStudent" />
            <input type="hidden" name="action" value="index" />
            <div class="form-row">
                <div class="form-col">
                    <label class="form-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nhập tên bài tập..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>

                <div class="form-col">
                    <label class="form-label"><i class="fas fa-book"></i> Môn học</label>
                    <select name="subject" class="form-control">
                        <option value="">Tất cả môn học</option>
                        <?php foreach($monHocList as $mon): ?>
                            <option value="<?php echo htmlspecialchars($mon['maMon']); ?>" 
                                    <?php echo ($_GET['subject'] ?? '') == $mon['maMon'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mon['tenMon']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-col">
                    <label class="form-label"><i class="fas fa-filter"></i> Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="chuanop" <?php echo ($_GET['status'] ?? '') == 'chuanop' ? 'selected' : ''; ?>>Chưa nộp</option>
                        <option value="danop" <?php echo ($_GET['status'] ?? '') == 'danop' ? 'selected' : ''; ?>>Đã nộp</option>
                        <option value="hethan" <?php echo ($_GET['status'] ?? '') == 'hethan' ? 'selected' : ''; ?>>Hết hạn</option>
                    </select>
                </div>

                <div class="form-col" style="display:flex;align-items:flex-end;gap:10px;">
                    <button type="submit" class="btn btn-primary">🔍 Lọc</button>
                    <button type="button" class="btn btn-warning" onclick="resetFilter()">🔄 Xóa lọc</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Kết quả -->
    <div class="alert alert-info">
        <strong>Kết quả:</strong> Tìm thấy <strong><?php echo count($baiTapList); ?></strong> bài tập
        <?php if(!empty($_GET['subject'])): ?>
            <?php 
            $tenMon = '';
            foreach($monHocList as $mon) {
                if($mon['maMon'] == $_GET['subject']) {
                    $tenMon = $mon['tenMon'];
                    break;
                }
            }
            ?>
            - Môn: <strong><?php echo htmlspecialchars($tenMon); ?></strong>
        <?php endif; ?>
        <?php if(!empty($_GET['status'])): ?>
            - Trạng thái: <strong><?php 
                echo $_GET['status'] == 'chuanop' ? 'Chưa nộp' : 
                     ($_GET['status'] == 'danop' ? 'Đã nộp' : 'Hết hạn'); 
            ?></strong>
        <?php endif; ?>
    </div>

    <!-- Danh sách bài tập -->
    <?php if (empty($baiTapList)): ?>
        <div class="alert alert-warning text-center" style="text-align:center;padding:20px;border-radius:6px;">
            <div style="font-size:48px;margin-bottom:20px;">📭</div>
            <h4>Không có bài tập nào!</h4>
            <p>Chưa có bài tập nào được giao hoặc không tìm thấy kết quả phù hợp.</p>
            <?php if (!empty($_GET['search']) || !empty($_GET['subject']) || !empty($_GET['status'])): ?>
                <button class="btn btn-primary" onclick="resetFilter()">Hiển thị tất cả</button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bài tập</th>
                        <th>Môn học</th>
                        <th>Giáo viên</th>
                        <th>Hạn nộp</th>
                        <th>Thời gian còn lại</th>
                        <th>Trạng thái</th>
                        <th>Điểm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($baiTapList as $bt): 
                    $now = time();
                    $deadline = strtotime($bt['thoiHanNop']);
                    $isExpired = $deadline < $now;
                    $isUrgent = !$isExpired && ($deadline - $now) < 172800; // < 2 ngày
                    
                    // Xác định trạng thái và màu
                    if ($bt['daNop']) {
                        $state = 'Đã nộp';
                        $color = 'success';
                        $icon = '✅';
                        $rowClass = 'success-row';
                    } elseif ($isExpired) {
                        $state = 'Quá hạn';
                        $color = 'danger';
                        $icon = '❌';
                        $rowClass = 'danger-row';
                    } elseif ($isUrgent) {
                        $state = 'Sắp hết hạn';
                        $color = 'warning';
                        $icon = '⏰';
                        $rowClass = 'warning-row';
                    } else {
                        $state = 'Chưa nộp';
                        $color = 'info';
                        $icon = '📝';
                        $rowClass = 'info-row';
                    }
                    
                    // Tính thời gian còn lại
                    $timeLeft = '';
                    if (!$bt['daNop'] && !$isExpired) {
                        $diff = $deadline - $now;
                        $days = floor($diff / 86400);
                        $hours = floor(($diff % 86400) / 3600);
                        if ($days > 0) {
                            $timeLeft = "{$days} ngày {$hours} giờ";
                        } elseif ($hours > 0) {
                            $timeLeft = "{$hours} giờ";
                        } else {
                            $timeLeft = floor($diff / 60) . " phút";
                        }
                    } elseif ($isExpired && !$bt['daNop']) {
                        $timeLeft = 'Đã hết hạn';
                    } else {
                        $timeLeft = '-';
                    }
                ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td>
                            <strong><?php echo htmlspecialchars($bt['tenBaiTap']); ?></strong>
                            <?php if (strpos(strtolower($bt['tenBaiTap']), 'kiểm tra') !== false): ?>
                                <span class="badge badge-warning" style="margin-left:5px;">📝 Kiểm tra</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($bt['tenMon']); ?></td>
                        <td><?php echo htmlspecialchars($bt['tenGV']); ?></td>
                        <td>
                            <span class="date-badge"><?php echo date('d/m/Y H:i', strtotime($bt['thoiHanNop'])); ?></span>
                        </td>
                        <td>
                            <?php if ($timeLeft !== '-'): ?>
                                <span class="time-badge"><?php echo $timeLeft; ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo $color; ?>">
                                <?php echo $icon . ' ' . $state; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($bt['daNop'] && $bt['trangThaiNop'] == 'DaCham' && $bt['diem'] !== null): ?>
                                <strong class="score-display"><?php echo number_format($bt['diem'], 1); ?></strong>
                            <?php elseif ($bt['daNop']): ?>
                                <span class="badge badge-warning">Chờ chấm</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=assignmentStudent&action=detail&id=<?php echo $bt['maBaiTap']; ?>" 
                               class="btn btn-sm btn-primary">
                                <?php if ($bt['daNop']): ?>
                                    👁️ Xem chi tiết
                                <?php elseif ($isExpired): ?>
                                    ℹ️ Xem thông tin
                                <?php else: ?>
                                    ✏️ Làm bài
                                <?php endif; ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function resetFilter() {
    window.location.href = 'index.php?controller=assignmentStudent&action=index';
}
</script>

<style>
/* Các style bổ sung cho bảng bài tập */
.score-display {
    font-size: 18px;
    color: #10b981;
    font-weight: bold;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.time-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.date-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
}

/* Row colors */
.success-row {
    background-color: #f0fdf4 !important;
}

.warning-row {
    background-color: #fffbeb !important;
}

.danger-row {
    background-color: #fef2f2 !important;
}

.info-row {
    background-color: #eff6ff !important;
}
</style>

<?php include "views/layout/footer.php"; ?>