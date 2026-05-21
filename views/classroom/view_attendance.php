<?php
// views/classroom/view_class_attendance.php - ĐIỂM DANH LỚP VÀ XÉT DUYỆT ĐƠN XIN NGHỈ - FIXED VERSION
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";

// Biến được truyền từ controller
$maLop = $maLop ?? '';
$selectedDate = $selectedDate ?? date('Y-m-d');
$viewMode = $viewMode ?? 'day'; // 'day', 'week', hoặc 'leave_requests'

// Kiểm tra ngày hợp lệ (thứ 2 đến thứ 7)
$dayOfWeek = date('N', strtotime($selectedDate));
if ($dayOfWeek < 1 || $dayOfWeek > 6) {
    $errorMessage = "Chỉ có thể điểm danh từ thứ Hai đến thứ Bảy.";
}

// Chuyển đổi dữ liệu từ DB sang format view
$savedAttendance = [];
$savedNotes = [];
if (isset($savedAttendanceRaw) && is_array($savedAttendanceRaw)) {
    foreach ($savedAttendanceRaw as $maHS => $data) {
        if (isset($data['trangThai'])) {
            switch ($data['trangThai']) {
                case 'CoMat':
                    $savedAttendance[$maHS] = 'present';
                    break;
                case 'Vang':
                case 'CoPhep':
                    $savedAttendance[$maHS] = 'absent';
                    break;
                default:
                    $savedAttendance[$maHS] = 'present';
            }
            
            if (isset($data['ghiChu']) && !empty($data['ghiChu'])) {
                $savedNotes[$maHS] = $data['ghiChu'];
            }
        }
    }
}

// Xử lý dữ liệu tuần
if ($viewMode === 'week') {
    $monday = isset($weekDates) ? date('Y-m-d', strtotime($weekDates[0])) : date('Y-m-d', strtotime('monday this week', strtotime($selectedDate)));
    if (!isset($weekDates)) {
        $weekDates = [];
        for ($i = 0; $i < 6; $i++) {
            $weekDates[] = date('Y-m-d', strtotime("$monday +$i days"));
        }
    }
    
    if (isset($weekAttendanceRaw) && is_array($weekAttendanceRaw)) {
        $weekAttendance = [];
        foreach ($weekAttendanceRaw as $maHS => $dates) {
            foreach ($dates as $date => $status) {
                if (!isset($weekAttendance[$maHS])) {
                    $weekAttendance[$maHS] = [];
                }
                $weekAttendance[$maHS][$date] = $status;
            }
        }
    } else {
        $weekAttendance = [];
    }
}

// Dữ liệu đơn xin nghỉ
$leaveRequests = $leaveRequests ?? []; 
$leaveStats = $leaveStats ?? ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];

// Mảng tên thứ tiếng Việt
$daysOfWeek = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
?>

<div class="main-content">
    <div class="content-header">
        <div class="header-left">
            <a href="index.php?controller=classroom&action=manage&maLop=<?= htmlspecialchars($maLop) ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Điểm danh lớp <?= htmlspecialchars($maLop) ?></h1>
        </div>
        <div class="header-right">
            <button class="btn-export" onclick="exportToExcel()">
                <i class="fas fa-download"></i> Xuất Excel
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message']) || isset($errorMessage)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error_message'] ?? $errorMessage) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Toolbar với tabs -->
    <div class="toolbar">
        <div class="view-mode">
            <button type="button" class="btn-mode <?= $viewMode === 'day' ? 'active' : '' ?>" onclick="changeView('day')">
                <i class="fas fa-calendar-day"></i> Điểm danh theo ngày
            </button>
            <button type="button" class="btn-mode <?= $viewMode === 'week' ? 'active' : '' ?>" onclick="changeView('week')">
                <i class="fas fa-calendar-week"></i> Xem theo tuần
            </button>
            <button type="button" class="btn-mode <?= $viewMode === 'leave_requests' ? 'active' : '' ?>" onclick="changeView('leave_requests')">
                <i class="fas fa-file-alt"></i> Đơn xin nghỉ
            </button>
        </div>

        <?php if ($viewMode === 'day'): ?>
        <div class="date-selector">
            <button type="button" class="btn-nav" onclick="changeDate(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <input type="date" id="dateInput" value="<?= htmlspecialchars($selectedDate) ?>" 
                   onchange="onDateChange()" class="date-input">
            <button type="button" class="btn-nav" onclick="changeDate(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
            <span class="current-date">
                <?= $daysOfWeek[$dayOfWeek] ?? '' ?>, <?= date('d/m/Y', strtotime($selectedDate)) ?>
            </span>
        </div>
        <?php elseif ($viewMode === 'week'): ?>
        <div class="week-selector">
            <button type="button" class="btn-nav" onclick="changeWeek(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span class="current-week">
                Tuần: <?= date('d/m', strtotime($monday)) ?> - <?= date('d/m/Y', strtotime("$monday +5 days")) ?>
            </span>
            <button type="button" class="btn-nav" onclick="changeWeek(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!isset($errorMessage)): ?>
        <?php if ($viewMode === 'day'): ?>
            <!-- ĐIỂM DANH THEO NGÀY -->
            <div class="attendance-container">
                <form method="POST" action="index.php?controller=classroom&action=saveAttendance" id="attendanceForm">
                    <input type="hidden" name="maLop" value="<?= htmlspecialchars($maLop) ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">
                    
                    <div class="quick-actions">
                        <span class="total-students">
                            Tổng số: <strong><?= count($students ?? []) ?></strong> học sinh
                        </span>
                        <div class="action-buttons">
                            <button type="button" class="btn-quick" onclick="markAll('present')">
                                <i class="fas fa-check-circle"></i> Có mặt tất cả
                            </button>
                            <button type="button" class="btn-quick btn-danger" onclick="markAll('absent')">
                                <i class="fas fa-times-circle"></i> Vắng tất cả
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th width="60">STT</th>
                                    <th width="120">Mã HS</th>
                                    <th>Họ và tên</th>
                                    <th width="120">Số báo danh</th>
                                    <th width="250">Điểm danh</th>
                                    <th width="100">Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students)): ?>
                                    <?php foreach ($students as $index => $student): ?>
                                        <?php 
                                        $currentStatus = $savedAttendance[$student['maHS']] ?? 'present';
                                        $currentNote = $savedNotes[$student['maHS']] ?? '';
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $index + 1 ?></td>
                                            <td class="text-center"><?= htmlspecialchars($student['maHS']) ?></td>
                                            <td class="student-name"><?= htmlspecialchars($student['hoVaTen']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($student['soBaoDanh']) ?></td>
                                            <td>
                                                <div class="attendance-options">
                                                    <label class="radio-option option-present">
                                                        <input type="radio" name="attendance[<?= $student['maHS'] ?>]" 
                                                               value="present" <?= $currentStatus === 'present' ? 'checked' : '' ?>>
                                                        <span class="radio-custom"></span>
                                                        <i class="fas fa-check-circle"></i>
                                                        Có mặt
                                                    </label>
                                                    <label class="radio-option option-absent">
                                                        <input type="radio" name="attendance[<?= $student['maHS'] ?>]" 
                                                               value="absent" <?= $currentStatus === 'absent' ? 'checked' : '' ?>>
                                                        <span class="radio-custom"></span>
                                                        <i class="fas fa-times-circle"></i>
                                                        Vắng
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn-note" 
                                                        onclick="openNoteModal('<?= $student['maHS'] ?>', '<?= htmlspecialchars($student['hoVaTen']) ?>', '<?= htmlspecialchars($currentNote) ?>')"
                                                        title="<?= !empty($currentNote) ? htmlspecialchars($currentNote) : 'Thêm ghi chú' ?>">
                                                    <i class="fas fa-comment"></i>
                                                    <?php if (!empty($currentNote)): ?>
                                                        <span class="note-indicator" title="Đã có ghi chú">•</span>
                                                    <?php endif; ?>
                                                </button>
                                                <input type="hidden" id="note_<?= $student['maHS'] ?>" 
                                                       name="notes[<?= $student['maHS'] ?>]" 
                                                       value="<?= htmlspecialchars($currentNote) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center no-data">
                                            <i class="fas fa-inbox"></i>
                                            <p>Không có học sinh trong lớp</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-footer">
                        <div class="summary">
                            <span class="summary-item">
                                <i class="fas fa-check-circle" style="color: #4CAF50;"></i>
                                Có mặt: <strong id="presentCount">0</strong>
                            </span>
                            <span class="summary-item">
                                <i class="fas fa-times-circle" style="color: #f44336;"></i>
                                Vắng: <strong id="absentCount">0</strong>
                            </span>
                        </div>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Lưu điểm danh
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif ($viewMode === 'week'): ?>
            <!-- XEM THEO TUẦN -->
            <div class="week-container">
                <div class="table-container">
                    <table class="week-table">
                        <thead>
                            <tr>
                                <th width="50" class="sticky-col">STT</th>
                                <th width="120" class="sticky-col">Mã HS</th>
                                <th width="200" class="sticky-col">Họ và tên</th>
                                <?php foreach ($weekDates as $date): ?>
                                    <?php $dow = date('N', strtotime($date)); ?>
                                    <th class="date-col">
                                        <div><?= $daysOfWeek[$dow] ?></div>
                                        <div class="date-small"><?= date('d/m', strtotime($date)) ?></div>
                                    </th>
                                <?php endforeach; ?>
                                <th width="100">Thống kê</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $index => $student): ?>
                                    <tr>
                                        <td class="text-center sticky-col"><?= $index + 1 ?></td>
                                        <td class="text-center sticky-col"><?= htmlspecialchars($student['maHS']) ?></td>
                                        <td class="sticky-col student-info">
                                            <div class="student-name"><?= htmlspecialchars($student['hoVaTen']) ?></div>
                                            <div class="student-meta">SBD: <?= htmlspecialchars($student['soBaoDanh']) ?></div>
                                        </td>
                                        <?php 
                                        $presentCount = 0;
                                        foreach ($weekDates as $date): 
                                            $status = $weekAttendance[$student['maHS']][$date] ?? null;
                                            if ($status === 'present' || $status === null) $presentCount++;
                                        ?>
                                            <td class="text-center status-cell">
                                                <?php if ($status === 'present' || $status === null): ?>
                                                    <span class="status-icon status-present" title="Có mặt">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                <?php elseif ($status === 'absent'): ?>
                                                    <span class="status-icon status-absent" title="Vắng">
                                                        <i class="fas fa-times-circle"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="status-icon" style="color: #999;" title="Chưa điểm danh">
                                                        <i class="fas fa-minus"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="text-center stats-cell">
                                            <span class="stats-badge"><?= $presentCount ?>/6</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center no-data">
                                        <i class="fas fa-inbox"></i>
                                        <p>Không có học sinh trong lớp</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="week-note">
                    <i class="fas fa-info-circle"></i>
                    Chế độ xem tuần chỉ hiển thị dữ liệu. Để điểm danh, chọn "Điểm danh theo ngày".
                </div>
            </div>

        <?php elseif ($viewMode === 'leave_requests'): ?>
            <!-- XÉT DUYỆT ĐƠN XIN NGHỈ -->
            <div class="leave-requests-container">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Đơn xin nghỉ học
                    </h2>
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterRequests('all')">
                            Tất cả <span class="badge"><?= $leaveStats['total'] ?></span>
                        </button>
                        <button class="filter-tab" onclick="filterRequests('ChoXuLy')">
                            Chờ duyệt <span class="badge badge-warning"><?= $leaveStats['pending'] ?></span>
                        </button>
                        <button class="filter-tab" onclick="filterRequests('DaDuyet')">
                            Đã duyệt <span class="badge badge-success"><?= $leaveStats['approved'] ?></span>
                        </button>
                        <button class="filter-tab" onclick="filterRequests('TuChoi')">
                            Từ chối <span class="badge badge-danger"><?= $leaveStats['rejected'] ?></span>
                        </button>
                    </div>
                </div>

                <div class="requests-list">
                    <?php if (!empty($leaveRequests)): ?>
                        <?php foreach ($leaveRequests as $request): ?>
                            <?php 
                            $ngayBatDau = isset($request['ngayBatDau']) ? $request['ngayBatDau'] : '';
                            $ngayKetThuc = isset($request['ngayKetThuc']) ? $request['ngayKetThuc'] : '';
                            $soNgayNghi = isset($request['soNgayNghi']) ? $request['soNgayNghi'] : 1;
                            
                            $ngayNghiDisplay = '';
                            if ($ngayBatDau) {
                                $ngayNghiDisplay = date('d/m/Y', strtotime($ngayBatDau));
                                if ($ngayKetThuc && $ngayKetThuc != $ngayBatDau) {
                                    $ngayNghiDisplay .= ' - ' . date('d/m/Y', strtotime($ngayKetThuc));
                                    $ngayNghiDisplay .= ' (' . $soNgayNghi . ' ngày)';
                                }
                            }
                            ?>
                            <div class="request-card" data-status="<?= htmlspecialchars($request['trangThai']) ?>">
                                <div class="request-header">
                                    <div class="request-date">
                                        <i class="fas fa-calendar"></i>
                                        <strong>Ngày nghỉ:</strong> 
                                        <?= htmlspecialchars($ngayNghiDisplay) ?>
                                        <span class="student-info-small">
                                            (<?= htmlspecialchars($request['maHS']) ?> 
                                            <?php if (!empty($request['hoVaTen'])): ?>
                                                - <?= htmlspecialchars($request['hoVaTen']) ?>
                                            <?php endif; ?>)
                                        </span>
                                    </div>
                                    <div class="request-status status-<?= strtolower($request['trangThai']) ?>">
                                        <?php
                                        $statusLabels = [
                                            'ChoXuLy' => '<i class="fas fa-clock"></i> Chờ xử lý',
                                            'DaDuyet' => '<i class="fas fa-check-circle"></i> Đã duyệt',
                                            'TuChoi' => '<i class="fas fa-times-circle"></i> Từ chối'
                                        ];
                                        echo $statusLabels[$request['trangThai']] ?? htmlspecialchars($request['trangThai']);
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="request-body">
                                    <div class="request-reason">
                                        <strong>Lý do:</strong>
                                        <p><?= htmlspecialchars($request['lyDo']) ?></p>
                                    </div>
                                    
                                    <div class="request-meta">
                                        <span>
                                            <i class="fas fa-paper-plane"></i>
                                            Gửi: <?= date('d/m/Y H:i', strtotime($request['ngayGui'])) ?>
                                        </span>
                                        <?php if ($request['trangThai'] !== 'ChoXuLy' && !empty($request['ngayXuLy'])): ?>
                                            <span>
                                                <i class="fas fa-check"></i>
                                                Xử lý: <?= date('d/m/Y H:i', strtotime($request['ngayXuLy'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($request['trangThai'] === 'ChoXuLy'): ?>
                                    <div class="request-actions">
                                        <form method="POST" action="index.php?controller=classroom&action=approveLeave" style="display: inline;">
                                            <input type="hidden" name="maDon" value="<?= $request['maDon'] ?>">
                                            <input type="hidden" name="maLop" value="<?= htmlspecialchars($maLop) ?>">
                                            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Bạn có chắc chắn muốn PHÊ DUYỆT đơn này?')">
                                                <i class="fas fa-check"></i> Phê duyệt
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="index.php?controller=classroom&action=rejectLeave" style="display: inline;">
                                            <input type="hidden" name="maDon" value="<?= $request['maDon'] ?>">
                                            <input type="hidden" name="maLop" value="<?= htmlspecialchars($maLop) ?>">
                                            <button type="submit" class="btn-action btn-reject" onclick="return confirm('Bạn có chắc chắn muốn TỪ CHỐI đơn này?')">
                                                <i class="fas fa-times"></i> Từ chối
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-inbox"></i>
                            <p>Chưa có đơn xin nghỉ học nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Modal for Notes -->
<div id="noteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Thêm ghi chú</h3>
            <button type="button" class="btn-close" onclick="closeNoteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="student-info-modal">
                <strong id="modalStudentId"></strong>
                <span id="modalStudentName"></span>
            </div>
            <div class="form-group">
                <label for="noteContent">Ghi chú:</label>
                <textarea id="noteContent" rows="4" placeholder="Nhập ghi chú cho học sinh này..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeNoteModal()">Hủy</button>
            <button type="button" class="btn-save" onclick="saveNote()">Lưu ghi chú</button>
        </div>
    </div>
</div>

<style>
/* Reset CSS */
* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
    color: #333;
}

.main-content {
    padding: 20px;
    padding-bottom: 120px;
    min-height: calc(100vh - 60px);
    overflow-y: auto;
}

/* Header */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-back {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: white;
    border: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    color: #333;
}

.btn-back:hover {
    background: #f5f5f5;
    border-color: #4CAF50;
    color: #4CAF50;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #1a1a1a;
}

.btn-export {
    padding: 8px 16px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
}

.btn-export:hover {
    background: #45a049;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(76, 175, 80, 0.3);
}

/* Alerts */
.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.3s ease;
    font-size: 14px;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.alert-success {
    background: #e8f5e9;
    border-left: 4px solid #4CAF50;
    color: #2e7d32;
}

.alert-danger {
    background: #ffebee;
    border-left: 4px solid #f44336;
    color: #c62828;
}

/* Toolbar */
.toolbar {
    background: white;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    flex-wrap: wrap;
    gap: 12px;
}

.view-mode {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-mode {
    padding: 8px 14px;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    color: #666;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-mode:hover {
    border-color: #4CAF50;
    color: #4CAF50;
}

.btn-mode.active {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.date-selector, .week-selector {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-nav {
    width: 32px;
    height: 32px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 13px;
}

.btn-nav:hover {
    background: #f5f5f5;
    border-color: #4CAF50;
    color: #4CAF50;
}

.date-input {
    padding: 6px 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    min-width: 140px;
}

.current-date, .current-week {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    min-width: 160px;
    text-align: center;
}

/* Attendance Container */
.attendance-container, .week-container, .leave-requests-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 30px;
}

.quick-actions {
    padding: 16px;
    background: #fafafa;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.total-students {
    font-size: 14px;
    color: #666;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-quick {
    padding: 7px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
    background: #4CAF50;
    color: white;
    transition: all 0.3s;
}

.btn-quick:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(76, 175, 80, 0.3);
}

.btn-quick.btn-danger {
    background: #f44336;
}

.btn-quick.btn-danger:hover {
    box-shadow: 0 3px 8px rgba(244, 67, 54, 0.3);
}

/* Tables */
.table-container {
    overflow-x: auto;
    max-height: calc(100vh - 420px);
    overflow-y: auto;
}

.attendance-table, .week-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.attendance-table thead th, .week-table thead th {
    background: #4CAF50;
    color: white;
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #45a049;
    position: sticky;
    top: 0;
    z-index: 10;
}

.attendance-table tbody tr, .week-table tbody tr {
    border-bottom: 1px solid #e0e0e0;
    transition: background-color 0.2s;
}

.attendance-table tbody tr:hover, .week-table tbody tr:hover {
    background: #f9f9f9;
}

.attendance-table td, .week-table td {
    padding: 12px 10px;
    color: #333;
}

.text-center {
    text-align: center;
}

.student-name {
    font-weight: 500;
    color: #1a1a1a;
}

/* Attendance Options */
.attendance-options {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 6px;
    transition: all 0.2s;
    position: relative;
    font-size: 13px;
}

.radio-option input[type="radio"] {
    display: none;
}

.radio-custom {
    width: 16px;
    height: 16px;
    border: 2px solid #ccc;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
}

.radio-option input[type="radio"]:checked + .radio-custom {
    border-color: #4CAF50;
}

.radio-option input[type="radio"]:checked + .radio-custom::after {
    content: '';
    position: absolute;
    width: 8px;
    height: 8px;
    background: #4CAF50;
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.option-present {
    color: #4CAF50;
}

.option-present:hover {
    background: #e8f5e9;
}

.option-absent {
    color: #f44336;
}

.option-absent:hover {
    background: #ffebee;
}

.option-absent input[type="radio"]:checked + .radio-custom {
    border-color: #f44336;
}

.option-absent input[type="radio"]:checked + .radio-custom::after {
    background: #f44336;
}

/* Note Button */
.btn-note {
    width: 30px;
    height: 30px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    color: #666;
    transition: all 0.2s;
    font-size: 13px;
    position: relative;
}

.btn-note:hover {
    background: #FFC107;
    color: white;
    border-color: #FFC107;
}

.note-indicator {
    position: absolute;
    top: -3px;
    right: -3px;
    width: 8px;
    height: 8px;
    background: #4CAF50;
    border-radius: 50%;
    display: block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

/* Form Footer */
.form-footer {
    padding: 16px;
    background: #fafafa;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    position: sticky;
    bottom: 0;
    z-index: 5;
}

.summary {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #666;
}

.btn-save {
    padding: 10px 24px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
    min-width: 150px;
    justify-content: center;
}

.btn-save:hover {
    background: #45a049;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(76, 175, 80, 0.3);
}

/* Week View */
.week-table .sticky-col {
    position: sticky;
    background: #4CAF50;
    z-index: 10;
}

.week-table .sticky-col:nth-child(1) { left: 0; width: 50px; }
.week-table .sticky-col:nth-child(2) { left: 50px; width: 120px; }
.week-table .sticky-col:nth-child(3) { left: 170px; width: 200px; }

.week-table tbody .sticky-col {
    background: white;
    border-right: 2px solid #e0e0e0;
}

.week-table tbody tr:hover .sticky-col {
    background: #f9f9f9;
}

.date-col {
    text-align: center;
    min-width: 80px;
}

.date-small {
    font-size: 11px;
    font-weight: normal;
    opacity: 0.9;
    margin-top: 2px;
}

.student-info {
    padding-left: 12px;
}

.student-meta {
    font-size: 11px;
    color: #999;
    margin-top: 2px;
}

.status-cell {
    padding: 8px;
}

.status-icon {
    font-size: 18px;
    display: inline-block;
}

.status-present {
    color: #4CAF50;
}

.status-absent {
    color: #f44336;
}

.stats-badge {
    display: inline-block;
    padding: 4px 8px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 12px;
    font-weight: 600;
    font-size: 11px;
}

.week-note {
    padding: 14px 16px;
    background: #fff3cd;
    border-top: 1px solid #ffeaa7;
    color: #856404;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}

/* Leave Requests */
.section-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 7px 14px;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    color: #666;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-tab:hover {
    border-color: #4CAF50;
    color: #4CAF50;
}

.filter-tab.active {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.badge {
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.filter-tab .badge {
    background: rgba(0,0,0,0.1);
}

.filter-tab.active .badge {
    background: rgba(255,255,255,0.3);
}

.badge-warning {
    background: #fff3e0;
    color: #e65100;
}

.badge-success {
    background: #e8f5e9;
    color: #2e7d32;
}

.badge-danger {
    background: #ffebee;
    color: #c62828;
}

.requests-list {
    padding: 16px 20px;
    max-height: calc(100vh - 350px);
    overflow-y: auto;
}

.request-card {
    background: #fafafa;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 14px;
    transition: all 0.3s;
}

.request-card:last-child {
    margin-bottom: 0;
}

.request-card:hover {
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    border-color: #4CAF50;
}

.request-card.hidden {
    display: none;
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
    flex-wrap: wrap;
    gap: 10px;
}

.request-date {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #333;
}

.student-info-small {
    font-size: 12px;
    color: #666;
    margin-left: 6px;
}

.request-status {
    padding: 5px 10px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-choxuly {
    background: #fff3e0;
    color: #e65100;
}

.status-daduyet {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-tuchoi {
    background: #ffebee;
    color: #c62828;
}

.request-body {
    margin-bottom: 14px;
}

.request-reason {
    margin-bottom: 10px;
}

.request-reason strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-size: 13px;
}

.request-reason p {
    color: #666;
    line-height: 1.6;
    padding: 10px;
    background: white;
    border-radius: 6px;
    border-left: 3px solid #4CAF50;
    font-size: 13px;
}

.request-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #999;
    flex-wrap: wrap;
}

.request-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.request-actions {
    display: flex;
    gap: 8px;
    padding-top: 14px;
    border-top: 1px solid #e0e0e0;
    flex-wrap: wrap;
}

.btn-action {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.btn-approve {
    background: #4CAF50;
    color: white;
}

.btn-approve:hover {
    background: #45a049;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(76, 175, 80, 0.3);
}

.btn-reject {
    background: #f44336;
    color: white;
}

.btn-reject:hover {
    background: #e53935;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(244, 67, 54, 0.3);
}

.no-data {
    text-align: center;
    padding: 50px 20px;
    color: #999;
}

.no-data i {
    font-size: 42px;
    margin-bottom: 14px;
    opacity: 0.5;
}

.no-data p {
    font-size: 15px;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    animation: slideIn 0.3s;
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.btn-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.btn-close:hover {
    background: #f5f5f5;
    color: #f44336;
}

.modal-body {
    padding: 20px;
}

.student-info-modal {
    background: #f5f7fa;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 14px;
}

.student-info-modal strong {
    color: #4CAF50;
    margin-right: 8px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-family: inherit;
    font-size: 14px;
    resize: vertical;
    transition: border-color 0.3s;
}

.form-group textarea:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
}

.modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel {
    padding: 8px 16px;
    background: #f5f5f5;
    color: #666;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #e0e0e0;
}

/* Toast Notification */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    background: #4CAF50;
    color: white;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 9999;
    transform: translateX(150%);
    transition: transform 0.3s ease;
    font-size: 14px;
}

.toast.show {
    transform: translateX(0);
}

.toast-error {
    background: #f44336;
}

.toast i {
    font-size: 16px;
}

/* Scroll bar styling */
.table-container::-webkit-scrollbar,
.requests-list::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-container::-webkit-scrollbar-track,
.requests-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-container::-webkit-scrollbar-thumb,
.requests-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover,
.requests-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .main-content {
        padding: 12px;
        padding-bottom: 100px;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .view-mode {
        width: 100%;
    }
    
    .btn-mode {
        flex: 1;
        min-width: 140px;
        font-size: 12px;
        padding: 7px 10px;
    }
    
    .date-selector, .week-selector {
        width: 100%;
        justify-content: center;
    }
    
    .quick-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .btn-quick {
        flex: 1;
        justify-content: center;
    }
    
    .table-container {
        max-height: calc(100vh - 450px);
    }
    
    .form-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .summary {
        justify-content: space-around;
    }
    
    .btn-save {
        width: 100%;
    }
    
    .request-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .request-actions {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
    
    .filter-tabs {
        width: 100%;
    }
    
    .filter-tab {
        flex: 1;
        justify-content: center;
        font-size: 12px;
        padding: 6px 10px;
    }
    
    .modal-content {
        margin: 5% auto;
        width: 95%;
    }
}
</style>

<script>
// JavaScript functions
function changeView(mode) {
    const maLop = '<?= htmlspecialchars($maLop) ?>';
    const date = document.getElementById('dateInput')?.value || '<?= $selectedDate ?>';
    window.location.href = `index.php?controller=classroom&action=classAttendance&maLop=${maLop}&viewMode=${mode}&date=${date}&refresh=${Date.now()}`;
}

function changeDate(offset) {
    const dateInput = document.getElementById('dateInput');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + offset);
    
    let dayOfWeek = currentDate.getDay();
    if (dayOfWeek === 0) {
        currentDate.setDate(currentDate.getDate() + (offset > 0 ? 1 : -2));
    } else if (dayOfWeek === 6) {
        currentDate.setDate(currentDate.getDate() + (offset > 0 ? 2 : -1));
    }
    
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');
    dateInput.value = `${year}-${month}-${day}`;
    
    onDateChange();
}

function onDateChange() {
    const maLop = '<?= htmlspecialchars($maLop) ?>';
    const date = document.getElementById('dateInput').value;
    window.location.href = `index.php?controller=classroom&action=classAttendance&maLop=${maLop}&viewMode=day&date=${date}&t=${Date.now()}`;
}

function changeWeek(offset) {
    const maLop = '<?= htmlspecialchars($maLop) ?>';
    const currentMonday = new Date('<?= $monday ?? date("Y-m-d", strtotime("monday this week")) ?>');
    currentMonday.setDate(currentMonday.getDate() + (offset * 7));
    
    const year = currentMonday.getFullYear();
    const month = String(currentMonday.getMonth() + 1).padStart(2, '0');
    const day = String(currentMonday.getDate()).padStart(2, '0');
    const newDate = `${year}-${month}-${day}`;
    
    window.location.href = `index.php?controller=classroom&action=classAttendance&maLop=${maLop}&viewMode=week&date=${newDate}&t=${Date.now()}`;
}

function markAll(status) {
    const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });
    updateSummary();
}

function updateSummary() {
    const presentCount = document.querySelectorAll('input[value="present"]:checked').length;
    const absentCount = document.querySelectorAll('input[value="absent"]:checked').length;
    
    const presentElement = document.getElementById('presentCount');
    const absentElement = document.getElementById('absentCount');
    
    if (presentElement) presentElement.textContent = presentCount;
    if (absentElement) absentElement.textContent = absentCount;
}

function filterRequests(status) {
    const cards = document.querySelectorAll('.request-card');
    const tabs = document.querySelectorAll('.filter-tab');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.closest('.filter-tab').classList.add('active');
    
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

function exportToExcel() {
    alert('Chức năng xuất Excel đang được phát triển');
}

// Note modal functionality
let currentStudentId = '';
let currentStudentName = '';

function openNoteModal(studentId, studentName, currentNote = '') {
    currentStudentId = studentId;
    currentStudentName = studentName;
    
    document.getElementById('modalStudentId').textContent = studentId;
    document.getElementById('modalStudentName').textContent = studentName;
    
    const decodedNote = decodeHTMLEntities(currentNote);
    document.getElementById('noteContent').value = decodedNote;
    
    document.getElementById('noteModal').style.display = 'block';
    
    setTimeout(() => {
        document.getElementById('noteContent').focus();
        document.getElementById('noteContent').select();
    }, 100);
}

function decodeHTMLEntities(text) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = text;
    return textArea.value;
}

function closeNoteModal() {
    document.getElementById('noteModal').style.display = 'none';
    currentStudentId = '';
    currentStudentName = '';
    document.getElementById('noteContent').value = '';
}

function saveNote() {
    const noteContent = document.getElementById('noteContent').value.trim();
    
    const hiddenInput = document.getElementById(`note_${currentStudentId}`);
    if (hiddenInput) {
        hiddenInput.value = noteContent;
    }
    
    const noteButton = document.querySelector(`button[onclick*="${currentStudentId}"]`);
    if (noteButton) {
        noteButton.title = noteContent ? noteContent : 'Thêm ghi chú';
        
        let indicator = noteButton.querySelector('.note-indicator');
        if (noteContent) {
            if (!indicator) {
                indicator = document.createElement('span');
                indicator.className = 'note-indicator';
                indicator.title = 'Đã có ghi chú';
                indicator.textContent = '•';
                noteButton.appendChild(indicator);
            }
        } else {
            if (indicator) {
                indicator.remove();
            }
        }
    }
    
    showToast('Đã lưu ghi chú!', 'success');
    closeNoteModal();
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('noteModal');
    if (event.target === modal) {
        closeNoteModal();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeNoteModal();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const dayView = document.getElementById('attendanceForm');
    if (dayView) {
        updateSummary();
        
        const radios = document.querySelectorAll('input[type="radio"][name^="attendance"]');
        radios.forEach(radio => {
            radio.addEventListener('change', updateSummary);
        });
        
        dayView.addEventListener('submit', function(e) {
            const checkedRadios = document.querySelectorAll('input[type="radio"]:checked').length;
            if (checkedRadios === 0) {
                e.preventDefault();
                alert('Vui lòng điểm danh cho ít nhất một học sinh!');
                return false;
            }
            
            const saveBtn = this.querySelector('.btn-save');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
            saveBtn.disabled = true;
            
            setTimeout(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }, 3000);
        });
    }
});
</script>

<?php include "views/layout/footer.php"; ?>