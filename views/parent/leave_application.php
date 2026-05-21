<?php
// views/parent/leave_application.php

// Lấy thông báo từ session
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
$formErrors = $_SESSION['form_errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [
    'lyDo' => '', 
    'ngayNghi' => '',
    'ngayBatDau' => '', 
    'ngayKetThuc' => '',
    'leaveType' => 'single'
];

// Xóa session messages sau khi đã lấy
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['form_errors'], $_SESSION['old_input']);

// Tạo giá trị mặc định cho ngày
$tomorrow = date('Y-m-d', strtotime('+1 day'));
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_parent.php"; ?>

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --danger: #f72585;
        --warning: #f8961e;
        --info: #4895ef;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --gray-light: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .leave-application-container {
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .page-title {
        color: var(--dark);
        font-weight: 700;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title i {
        margin-right: 0.75rem;
        color: var(--primary);
        background: rgba(67, 97, 238, 0.1);
        padding: 0.5rem;
        border-radius: 50%;
    }

    .card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: var(--transition);
        border: none;
    }

    .card:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-light);
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        margin: 0;
    }

    .card-title i {
        margin-right: 0.5rem;
        color: var(--primary);
    }

    .student-info-card {
        background: linear-gradient(135deg, #4361ee, #3f37c9);
        color: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--box-shadow);
    }

    .student-info-content {
        display: flex;
        align-items: center;
    }

    .student-info-content i {
        font-size: 2.5rem;
        margin-right: 1rem;
        opacity: 0.9;
    }

    .student-info-text h4 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .student-info-details {
        display: flex;
        gap: 1.5rem;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .student-info-details span {
        display: flex;
        align-items: center;
    }

    .student-info-details i {
        font-size: 1rem;
        margin-right: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-light);
        border-radius: var(--border-radius);
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-text {
        background: var(--light);
        border: 1px solid var(--gray-light);
        border-right: none;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius) 0 0 var(--border-radius);
        color: var(--gray);
    }

    .form-control-with-icon {
        border-radius: 0 var(--border-radius) var(--border-radius) 0;
    }

    .form-text {
        font-size: 0.875rem;
        color: var(--gray);
        margin-top: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--border-radius);
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn i {
        margin-right: 0.5rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    .alert-danger {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .alert i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    th {
        background: var(--light);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid var(--gray-light);
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-light);
    }

    tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .ChoXuLy {
        background: #fff3cd;
        color: #856404;
    }

    .DaDuyet {
        background: #d4edda;
        color: #155724;
    }

    .TuChoi {
        background: #f8d7da;
        color: #721c24;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.1rem;
    }

    /* Leave Type Selection */
    .leave-type-selection {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .leave-type-option {
        flex: 1;
        border: 2px solid var(--gray-light);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .leave-type-option:hover {
        border-color: var(--primary-light);
        background: rgba(67, 97, 238, 0.05);
    }

    .leave-type-option.selected {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.1);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }

    .leave-type-option i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .leave-type-option h4 {
        margin: 0 0 0.5rem 0;
        color: var(--dark);
        font-size: 1.1rem;
    }

    .leave-type-option p {
        margin: 0;
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* Date Range Container */
    .date-range-container {
        display: none;
        gap: 1rem;
        align-items: center;
    }

    .date-range-container.show {
        display: flex;
    }

    .date-range-container .form-group {
        flex: 1;
    }

    .days-counter {
        background: var(--primary);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        min-width: 120px;
        text-align: center;
        box-shadow: var(--box-shadow);
    }

    .days-counter .days-number {
        font-size: 1.5rem;
        display: block;
    }

    .days-counter .days-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    /* Single Date Container */
    .single-date-container {
        display: none;
    }

    .single-date-container.show {
        display: block;
    }

    /* Character counter */
    .char-counter {
        text-align: right;
        font-size: 0.875rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    .char-counter.limit {
        color: var(--danger);
        font-weight: 600;
    }

    /* Error styling */
    .is-invalid {
        border-color: #f44336 !important;
    }

    .invalid-feedback {
        color: #f44336;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 0.25em 0.5em;
        font-size: 0.875em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .bg-info {
        background-color: var(--info) !important;
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .leave-application-container {
            padding: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .student-info-details {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .leave-type-selection {
            flex-direction: column;
        }
        
        .date-range-container {
            flex-direction: column;
        }
        
        .days-counter {
            width: 100%;
            margin-top: 0.5rem;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        .btn {
            width: 100%;
        }
    }

    /* Date input custom */
    input[type="date"] {
        font-family: 'Inter', sans-serif;
    }
</style>

<div class="main-content">
    <div class="leave-application-container">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-medical-alt"></i>
                Gửi đơn xin nghỉ học
            </h1>
        </div>

        <!-- Thông báo -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($formErrors)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="mb-0" style="margin-left: 1.5rem;">
                    <?php foreach ($formErrors as $errorMsg): ?>
                        <li><?php echo htmlspecialchars($errorMsg); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Thông tin học sinh -->
        <?php if (isset($student) && $student): ?>
        <div class="student-info-card">
            <div class="student-info-content">
                <i class="fas fa-user-graduate"></i>
                <div class="student-info-text">
                    <h4><?php echo htmlspecialchars($student['hoVaTen'] ?? ''); ?></h4>
                    <div class="student-info-details">
                        <span>
                            <i class="fas fa-id-card"></i>
                            Mã học sinh: <?php echo htmlspecialchars($student['maHS'] ?? ''); ?>
                        </span>
                        <span>
                            <i class="fas fa-school"></i>
                            Lớp: <?php echo htmlspecialchars($student['maLop'] ?? ''); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Form tạo đơn -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-edit"></i>
                    Thông tin đơn xin nghỉ
                </h2>
            </div>
            
            <form method="POST" action="index.php?controller=leaveApplication&action=store" id="leaveForm">
                <!-- Loại đơn -->
                <div class="form-group">
                    <label>Loại đơn xin nghỉ <span style="color: #f44336;">*</span></label>
                    <div class="leave-type-selection">
                        <div class="leave-type-option <?php echo ($oldInput['leaveType'] ?? 'single') === 'single' ? 'selected' : ''; ?>" 
                             data-type="single">
                            <i class="fas fa-calendar-day"></i>
                            <h4>Nghỉ 1 ngày</h4>
                            <p>Chỉ chọn một ngày duy nhất</p>
                        </div>
                        <div class="leave-type-option <?php echo ($oldInput['leaveType'] ?? 'single') === 'multiple' ? 'selected' : ''; ?>" 
                             data-type="multiple">
                            <i class="fas fa-calendar-week"></i>
                            <h4>Nghỉ nhiều ngày</h4>
                            <p>Chọn khoảng thời gian nghỉ</p>
                        </div>
                    </div>
                    <input type="hidden" name="leaveType" id="leaveType" value="<?php echo htmlspecialchars($oldInput['leaveType'] ?? 'single'); ?>">
                </div>

                <!-- Phần chọn ngày đơn lẻ -->
                <div class="single-date-container <?php echo ($oldInput['leaveType'] ?? 'single') === 'single' ? 'show' : ''; ?>">
                    <div class="form-group">
                        <label for="ngayNghi">Ngày nghỉ học <span style="color: #f44336;">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" 
                                   id="ngayNghi"
                                   name="ngayNghi" 
                                   class="form-control form-control-with-icon"
                                   value="<?php echo htmlspecialchars($oldInput['ngayNghi'] ?: $tomorrow); ?>"
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-text">
                            Chọn ngày học sinh sẽ nghỉ (từ hôm nay trở đi)
                        </div>
                    </div>
                </div>

                <!-- Phần chọn khoảng ngày -->
                <div class="date-range-container <?php echo ($oldInput['leaveType'] ?? 'single') === 'multiple' ? 'show' : ''; ?>">
                    <div class="form-group">
                        <label for="ngayBatDau" style="font-size: 0.9rem; margin-bottom: 0.25rem;">Ngày bắt đầu</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-plus"></i>
                            </span>
                            <input type="date" 
                                   id="ngayBatDau"
                                   name="ngayBatDau" 
                                   class="form-control form-control-with-icon"
                                   value="<?php echo htmlspecialchars($oldInput['ngayBatDau'] ?: $tomorrow); ?>"
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ngayKetThuc" style="font-size: 0.9rem; margin-bottom: 0.25rem;">Ngày kết thúc</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar-minus"></i>
                            </span>
                            <input type="date" 
                                   id="ngayKetThuc"
                                   name="ngayKetThuc" 
                                   class="form-control form-control-with-icon"
                                   value="<?php echo htmlspecialchars($oldInput['ngayKetThuc'] ?: $tomorrow); ?>"
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="days-counter" id="daysCounter">
                        <span class="days-number" id="daysNumber">1</span>
                        <span class="days-label" id="daysLabel">ngày nghỉ</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="lyDo">Lý do nghỉ học <span style="color: #f44336;">*</span></label>
                    <textarea id="lyDo"
                              name="lyDo" 
                              class="form-control" 
                              rows="4" 
                              placeholder="Nhập lý do nghỉ học (tối đa 500 ký tự)..."
                              required
                              maxlength="500"><?php echo htmlspecialchars($oldInput['lyDo']); ?></textarea>
                    <div class="form-text">
                        Ví dụ: Học sinh bị ốm, gia đình có việc đột xuất, đi du lịch cùng gia đình,...
                    </div>
                    <div class="char-counter" id="charCounter">
                        <span id="charCount">0</span>/500 ký tự
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Gửi đơn
                    </button>
                    <a href="index.php?controller=parent&action=dashboard" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>

        <!-- Danh sách đơn đã gửi -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    Lịch sử xin nghỉ
                </h2>
            </div>
            
            <?php if (empty($applications)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Chưa có đơn xin nghỉ nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày nghỉ</th>
                                <th>Số ngày</th>
                                <th>Ngày gửi</th>
                                <th>Lý do</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <?php if (isset($app['ngayBatDauFmt']) && isset($app['ngayKetThucFmt'])): ?>
                                        <?php if ($app['ngayBatDauFmt'] === $app['ngayKetThucFmt']): ?>
                                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                                            <?php echo htmlspecialchars($app['ngayBatDauFmt'] ?? ''); ?>
                                        <?php else: ?>
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                            <?php echo htmlspecialchars($app['ngayBatDauFmt'] ?? ''); ?>
                                            <i class="fas fa-arrow-right mx-1 text-secondary"></i>
                                            <?php echo htmlspecialchars($app['ngayKetThucFmt'] ?? ''); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($app['ngayNghiFmt'] ?? ''); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($app['soNgay'])): ?>
                                        <span class="badge bg-info">
                                            <?php 
                                            $days = $app['soNgay'] ?? 1;
                                            echo $days . ' ngày';
                                            ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info">1 ngày</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-clock me-2 text-secondary"></i>
                                    <?php echo htmlspecialchars($app['ngayGuiFmt'] ?? ''); ?>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;" 
                                         title="<?php echo htmlspecialchars($app['lyDo'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($app['lyDo'] ?? ''); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = 'ChoXuLy';
                                    $statusText = 'Chờ xử lý';
                                    
                                    if (isset($app['trangThai'])) {
                                        if ($app['trangThai'] === 'DaDuyet') {
                                            $statusClass = 'DaDuyet';
                                            $statusText = 'Đã duyệt';
                                        } elseif ($app['trangThai'] === 'TuChoi') {
                                            $statusClass = 'TuChoi';
                                            $statusText = 'Từ chối';
                                        }
                                    }
                                    ?>
                                    <span class="status <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        const todayFormatted = today.toISOString().split('T')[0];
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        
        // Elements
        const leaveTypeOptions = document.querySelectorAll('.leave-type-option');
        const leaveTypeInput = document.getElementById('leaveType');
        const singleDateContainer = document.querySelector('.single-date-container');
        const dateRangeContainer = document.querySelector('.date-range-container');
        const singleDateInput = document.getElementById('ngayNghi');
        const startDateInput = document.getElementById('ngayBatDau');
        const endDateInput = document.getElementById('ngayKetThuc');
        const daysNumber = document.getElementById('daysNumber');
        const daysLabel = document.getElementById('daysLabel');
        const daysCounter = document.getElementById('daysCounter');
        
        // Khởi tạo ngày mặc định
        if (singleDateInput && !singleDateInput.value) {
            singleDateInput.value = tomorrowFormatted;
        }
        
        if (startDateInput && !startDateInput.value) {
            startDateInput.value = tomorrowFormatted;
        }
        
        if (endDateInput && !endDateInput.value) {
            endDateInput.value = tomorrowFormatted;
        }
        
        // Hàm tính số ngày cho khoảng thời gian
        function calculateDays() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (!startDateInput.value || !endDateInput.value || startDate > endDate) {
                daysNumber.textContent = '0';
                daysCounter.style.background = '#f44336';
                return 0;
            }
            
            // Tính số ngày (bao gồm cả ngày kết thúc)
            const timeDiff = endDate.getTime() - startDate.getTime();
            const days = Math.floor(timeDiff / (1000 * 3600 * 24)) + 1;
            
            // Cập nhật hiển thị
            daysNumber.textContent = days;
            
            // Đổi màu nếu quá 30 ngày
            if (days > 30) {
                daysCounter.style.background = '#f44336';
                daysLabel.textContent = 'ngày (quá giới hạn)';
            } else if (days > 7) {
                daysCounter.style.background = '#f8961e';
                daysLabel.textContent = 'ngày nghỉ';
            } else {
                daysCounter.style.background = '#4361ee';
                daysLabel.textContent = 'ngày nghỉ';
            }
            
            return days;
        }
        
        // Tính số ngày ban đầu nếu đang ở chế độ nhiều ngày
        if (leaveTypeInput.value === 'multiple') {
            calculateDays();
        }
        
        // Xử lý chọn loại đơn
        leaveTypeOptions.forEach(option => {
            option.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                
                // Cập nhật UI
                leaveTypeOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                leaveTypeInput.value = type;
                
                // Hiển thị phần tử phù hợp
                if (type === 'single') {
                    singleDateContainer.classList.add('show');
                    dateRangeContainer.classList.remove('show');
                    
                    // Đảm bảo có giá trị ngày đơn
                    if (!singleDateInput.value) {
                        singleDateInput.value = tomorrowFormatted;
                    }
                } else {
                    singleDateContainer.classList.remove('show');
                    dateRangeContainer.classList.add('show');
                    
                    // Đảm bảo có giá trị ngày khoảng
                    if (!startDateInput.value) {
                        startDateInput.value = tomorrowFormatted;
                    }
                    if (!endDateInput.value) {
                        endDateInput.value = tomorrowFormatted;
                    }
                    
                    calculateDays();
                }
            });
        });
        
        // Theo dõi sự thay đổi của ngày
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                // Đảm bảo ngày kết thúc không nhỏ hơn ngày bắt đầu
                if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                    endDateInput.value = this.value;
                }
                endDateInput.min = this.value;
                calculateDays();
            });
            
            endDateInput.addEventListener('change', function() {
                // Đảm bảo ngày bắt đầu không lớn hơn ngày kết thúc
                if (startDateInput.value && new Date(startDateInput.value) > new Date(this.value)) {
                    startDateInput.value = this.value;
                }
                calculateDays();
            });
        }
        
        // Character counter cho textarea
        const reasonTextarea = document.getElementById('lyDo');
        const charCounter = document.getElementById('charCounter');
        const charCount = document.getElementById('charCount');
        
        if (reasonTextarea && charCounter && charCount) {
            function updateCharCount() {
                const count = reasonTextarea.value.length;
                charCount.textContent = count;
                
                if (count >= 450) {
                    charCounter.classList.add('limit');
                } else {
                    charCounter.classList.remove('limit');
                }
                
                if (count > 500) {
                    reasonTextarea.value = reasonTextarea.value.substring(0, 500);
                    charCount.textContent = 500;
                }
            }
            
            reasonTextarea.addEventListener('input', updateCharCount);
            updateCharCount();
        }
        
        // Form validation
        const form = document.getElementById('leaveForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const leaveType = leaveTypeInput.value;
                const reasonInput = this.querySelector('textarea[name="lyDo"]');
                
                let isValid = true;
                let errorMessage = '';
                
                // Clear previous error styles
                singleDateInput.classList.remove('is-invalid');
                if (startDateInput) startDateInput.classList.remove('is-invalid');
                if (endDateInput) endDateInput.classList.remove('is-invalid');
                reasonInput.classList.remove('is-invalid');
                
                // Validate date based on leave type
                if (leaveType === 'single') {
                    if (!singleDateInput.value) {
                        singleDateInput.classList.add('is-invalid');
                        errorMessage = 'Vui lòng chọn ngày nghỉ.\n';
                        isValid = false;
                    } else {
                        const selectedDate = new Date(singleDateInput.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        
                        if (selectedDate < today) {
                            singleDateInput.classList.add('is-invalid');
                            errorMessage = 'Ngày nghỉ phải là ngày hiện tại hoặc sau ngày hôm nay.\n';
                            isValid = false;
                        }
                    }
                } else {
                    // Validate multiple days
                    if (!startDateInput.value || !endDateInput.value) {
                        if (startDateInput) startDateInput.classList.add('is-invalid');
                        if (endDateInput) endDateInput.classList.add('is-invalid');
                        errorMessage = 'Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc.\n';
                        isValid = false;
                    } else {
                        const start = new Date(startDateInput.value);
                        const end = new Date(endDateInput.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        
                        if (start < today) {
                            startDateInput.classList.add('is-invalid');
                            errorMessage = 'Ngày bắt đầu nghỉ phải là ngày hiện tại hoặc sau ngày hôm nay.\n';
                            isValid = false;
                        }
                        
                        if (end < start) {
                            endDateInput.classList.add('is-invalid');
                            errorMessage += 'Ngày kết thúc phải sau ngày bắt đầu.\n';
                            isValid = false;
                        }
                        
                        // Kiểm tra số ngày
                        const days = calculateDays();
                        if (days > 30) {
                            errorMessage += 'Thời gian nghỉ tối đa là 30 ngày. Vui lòng liên hệ trực tiếp với giáo viên chủ nhiệm.\n';
                            isValid = false;
                        }
                    }
                }
                
                // Validate reason
                if (!reasonInput.value.trim()) {
                    reasonInput.classList.add('is-invalid');
                    errorMessage += 'Vui lòng nhập lý do nghỉ học.';
                    isValid = false;
                } else if (reasonInput.value.trim().length < 10) {
                    reasonInput.classList.add('is-invalid');
                    errorMessage += 'Lý do nghỉ học phải có ít nhất 10 ký tự.';
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng kiểm tra lại thông tin:\n\n' + errorMessage);
                    return;
                }
                
                // Confirm submission
                let confirmMessage = '';
                if (leaveType === 'single') {
                    confirmMessage = 'Bạn có chắc chắn muốn gửi đơn xin nghỉ học ngày ' + singleDateInput.value + '?\n\nĐơn sau khi gửi sẽ được gửi đến giáo viên chủ nhiệm xét duyệt.';
                } else {
                    const days = calculateDays();
                    confirmMessage = 'Bạn có chắc chắn muốn gửi đơn xin nghỉ học từ ' + startDateInput.value + ' đến ' + endDateInput.value + ' (' + days + ' ngày)?\n\nĐơn sau khi gửi sẽ được gửi đến giáo viên chủ nhiệm xét duyệt.';
                }
                
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                }
            });
        }
        
        // Real-time validation
        if (singleDateInput) {
            singleDateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    this.classList.add('is-invalid');
                    alert('Ngày nghỉ phải là ngày hiện tại hoặc sau ngày hôm nay.');
                    this.value = tomorrowFormatted;
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }
    });
</script>

<?php include "views/layout/footer.php"; ?>