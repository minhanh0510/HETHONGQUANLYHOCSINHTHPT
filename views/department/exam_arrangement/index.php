<?php
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar_department.php';
?>

<style>
/* exam_arrangement.css */

/* Tổng thể */
.main-content {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Tiêu đề */
.main-content h2 {
    margin-bottom: 20px;
    color: #2c3e50;
    font-weight: 600;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

/* Card chọn kỳ thi */
.exam-select-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    border: 1px solid #dee2e6;
}

.exam-select-card h5 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Thống kê */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #dee2e6;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 10px 0;
}

.stat-1 .stat-number { color: #2c3e50; }
.stat-2 .stat-number { color: #27ae60; }
.stat-3 .stat-number { color: #e74c3c; }
.stat-4 .stat-number { color: #3498db; }

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
}

/* Tabs */
.tab-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-top: 20px;
    border: 1px solid #dee2e6;
}

.nav-tabs-custom {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0 20px;
    display: flex;
}

.nav-tabs-custom .nav-link {
    border: none;
    color: #7f8c8d;
    font-weight: 500;
    padding: 15px 25px;
    border-bottom: 3px solid transparent;
    background: none;
    cursor: pointer;
}

.nav-tabs-custom .nav-link.active {
    color: #3498db;
    border-bottom: 3px solid #3498db;
}

.tab-content {
    padding: 25px;
}

/* Section trong tab */
.tab-section {
    margin-bottom: 30px;
}

.tab-section h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

/* List danh sách */
.student-list, .room-list {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
}

.student-item, .room-item {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    cursor: pointer;
    transition: background-color 0.2s;
}

.student-item:hover, .room-item:hover {
    background-color: #f8f9fa;
}

.student-item.selected, .room-item.selected {
    background-color: rgba(52, 152, 219, 0.1);
    border-left: 4px solid #3498db;
}

.student-code {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.student-name {
    font-weight: 500;
    margin: 5px 0;
    color: #2c3e50;
}

.student-info {
    font-size: 0.85rem;
    color: #7f8c8d;
}

.room-code {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.room-name {
    font-weight: 500;
    color: #2c3e50;
}

.room-info {
    font-size: 0.85rem;
    color: #7f8c8d;
}

.room-capacity {
    background-color: #27ae60;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Thông tin xếp phòng */
.arrangement-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #dee2e6;
    min-height: 150px;
}

.selected-student {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
}

.preference-list {
    margin-left: 20px;
    margin-bottom: 0;
}

.preference-list li {
    margin-bottom: 5px;
}

.selected-room {
    background-color: rgba(39, 174, 96, 0.1);
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
    border: 1px solid #27ae60;
}

/* Form controls */
.form-select, .form-control {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    padding: 10px 15px;
}

.form-select:focus, .form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

/* Nút */
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s ease-in-out;
    font-size: 0.95rem;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-success {
    background-color: #27ae60;
    color: white;
}

.btn-success:hover {
    background-color: #219653;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Bảng */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
}

.data-table th, .data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.data-table th {
    background-color: #3498db;
    color: #fff;
    font-weight: 600;
}

.data-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.data-table tr:hover {
    background-color: #e8f4fc;
}

/* Alert */
.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

/* Scrollbar */
.student-list::-webkit-scrollbar,
.room-list::-webkit-scrollbar {
    width: 8px;
}

.student-list::-webkit-scrollbar-track,
.room-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.student-list::-webkit-scrollbar-thumb,
.room-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.student-list::-webkit-scrollbar-thumb:hover,
.room-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Badge */
.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge.bg-primary {
    background-color: #3498db !important;
}

.badge.bg-secondary {
    background-color: #7f8c8d !important;
}

/* Text */
.text-muted {
    color: #7f8c8d !important;
}

.text-center {
    text-align: center;
}

/* Grid utilities */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 15px;
    padding-left: 15px;
}

.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding-right: 15px;
    padding-left: 15px;
}

.col-md-3 {
    flex: 0 0 25%;
    max-width: 25%;
    padding-right: 15px;
    padding-left: 15px;
}

/* D-flex utilities */
.d-flex {
    display: flex !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.align-items-center {
    align-items: center !important;
}

.align-items-end {
    align-items: flex-end !important;
}

.flex-column {
    flex-direction: column !important;
}

/* Margin & Padding */
.mt-3 { margin-top: 1rem !important; }
.mt-4 { margin-top: 1.5rem !important; }
.mb-3 { margin-bottom: 1rem !important; }
.mb-4 { margin-bottom: 1.5rem !important; }
.m-3 { margin: 1rem !important; }
.py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
.me-2 { margin-right: 0.5rem !important; }

/* Responsive */
@media (max-width: 768px) {
    .col-md-6, .col-md-4, .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .nav-tabs-custom {
        flex-direction: column;
    }
    
    .nav-tabs-custom .nav-link {
        width: 100%;
        text-align: left;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="main-content">
    <h2>🚪 Xếp danh sách thi tuyển sinh</h2>
    
    <!-- Exam Selection -->
    <div class="exam-select-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5><i class="fas fa-calendar-alt"></i> Chọn kỳ thi</h5>
                <div class="form-group">
                    <select id="examSelect" class="form-control form-control-lg">
                        <option value="">-- Chọn kỳ thi --</option>
                        <?php foreach ($exams as $exam): ?>
                        <option value="<?php echo $exam['maKyThi']; ?>" <?php echo $selectedExam == $exam['maKyThi'] ? 'selected' : ''; ?>>
                            <?php echo $exam['tenKyThi']; ?> (<?php echo date('d/m/Y', strtotime($exam['ngayThi'])); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <button id="loadDataBtn" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Tải dữ liệu
                </button>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card stat-1">
            <div class="stat-icon">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="stat-number" id="totalStudents">0</div>
            <div class="stat-label">Tổng thí sinh</div>
        </div>
        <div class="stat-card stat-2">
            <div class="stat-icon">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div class="stat-number" id="arrangedStudents">0</div>
            <div class="stat-label">Đã xếp phòng</div>
        </div>
        <div class="stat-card stat-3">
            <div class="stat-icon">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="stat-number" id="unarrangedStudents">0</div>
            <div class="stat-label">Chưa xếp phòng</div>
        </div>
        <div class="stat-card stat-4">
            <div class="stat-icon">
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
            <div class="stat-number" id="completionRate">0%</div>
            <div class="stat-label">Tỷ lệ hoàn thành</div>
        </div>
    </div>
    
    <!-- Tabs -->
    <div class="tab-container">
        <div class="nav-tabs-custom">
            <button class="nav-link active" id="auto-tab" data-target="#auto">
                <i class="fas fa-robot"></i> Sắp xếp tự động
            </button>
            <button class="nav-link" id="manual-tab" data-target="#manual">
                <i class="fas fa-hand-paper"></i> Sắp xếp thủ công
            </button>
            <button class="nav-link" id="list-tab" data-target="#list">
                <i class="fas fa-list"></i> Danh sách đã xếp
            </button>
        </div>
        
        <div class="tab-content">
            <!-- Auto Arrangement Tab -->
            <div id="auto" class="tab-pane active">
                <div class="tab-section">
                    <h6><i class="fas fa-cogs"></i> Sắp xếp tự động</h6>
                    <p>Hệ thống tự động xếp phòng thi theo các tiêu chí: số phòng, vị trí, trường đăng ký.</p>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tiêu chí sắp xếp:</label>
                            <select id="criteriaSelect" class="form-select">
                                <option value="phong">Theo số phòng thi</option>
                                <option value="vitri">Theo vị trí</option>
                                <option value="truong">Theo trường đăng ký</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button id="autoArrangeBtn" class="btn btn-primary">
                                <i class="fas fa-play-circle"></i> Sắp xếp tự động
                            </button>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button id="saveAutoBtn" class="btn btn-success" disabled>
                                <i class="fas fa-save"></i> Lưu kết quả
                            </button>
                        </div>
                    </div>
                    
                    <div id="autoResults">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chọn kỳ thi và nhấn "Tải dữ liệu", sau đó chọn tiêu chí và nhấn "Sắp xếp tự động"
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Manual Arrangement Tab -->
            <div id="manual" class="tab-pane" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tab-section">
                            <h6><i class="fas fa-user-graduate"></i> DANH SÁCH THÍ SINH CHƯA XẾP PHÒNG</h6>
                            <div id="unarrangedList" class="student-list">
                                <div class="alert alert-info m-3">
                                    <i class="fas fa-info-circle"></i> Chọn kỳ thi và nhấn "Tải dữ liệu" để hiển thị danh sách thí sinh
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="tab-section">
                            <h6><i class="fas fa-info-circle"></i> THÔNG TIN XẾP PHÒNG</h6>
                            <div id="manualArrangementInfo" class="arrangement-info">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user fa-3x mb-3"></i>
                                    <p>Chọn thí sinh để xếp phòng</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-section">
                            <h6><i class="fas fa-door-open"></i> DANH SÁCH PHÒNG THI CÒN TRỐNG</h6>
                            <div id="availableRooms" class="room-list">
                                <div class="alert alert-warning m-3">
                                    <i class="fas fa-exclamation-circle"></i> Chọn thí sinh để hiển thị phòng trống
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button id="saveManualBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-check"></i> Lưu xếp phòng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Arranged List Tab -->
            <div id="list" class="tab-pane" style="display: none;">
                <div class="tab-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6><i class="fas fa-clipboard-list"></i> DANH SÁCH ĐÃ XẾP PHÒNG</h6>
                        <div class="d-flex">
                            <select id="sortSelect" class="form-select me-2" style="width: auto;">
                                <option value="phong">Sắp xếp theo số phòng</option>
                                <option value="vitri">Sắp xếp theo vị trí</option>
                                <option value="truong">Sắp xếp theo trường</option>
                            </select>
                            <button id="exportBtn" class="btn btn-secondary">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                    
                    <div id="arrangedList" class="table-responsive">
                        <div class="alert alert-info m-3">
                            <i class="fas fa-info-circle"></i> Chọn kỳ thi và nhấn "Tải dữ liệu" để hiển thị danh sách đã xếp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentExam = null;
    let selectedStudent = null;
    let selectedRoom = null;
    let autoArrangementResults = [];
    
    // Tab switching
    $('.nav-link').click(function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        
        const target = $(this).data('target');
        $('.tab-pane').hide();
        $(target).show();
    });
    
    // Load data when exam is selected
    $('#loadDataBtn').click(function() {
        currentExam = $('#examSelect').val();
        if (!currentExam) {
            alert('Vui lòng chọn kỳ thi');
            return;
        }
        loadStatistics();
        loadUnarrangedStudents();
        loadArrangedList();
    });
    
    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStatistics',
            data: { exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    const total = parseInt(stats.daXep) + parseInt(stats.chuaXep);
                    
                    $('#totalStudents').text(total.toLocaleString());
                    $('#arrangedStudents').text(parseInt(stats.daXep).toLocaleString());
                    $('#unarrangedStudents').text(parseInt(stats.chuaXep).toLocaleString());
                    
                    const rate = total > 0 ? Math.round((stats.daXep / total) * 100) : 0;
                    $('#completionRate').text(rate + '%');
                }
            }
        });
    }
    
    // Auto arrangement
    $('#autoArrangeBtn').click(function() {
        if (!currentExam) {
            alert('Vui lòng chọn kỳ thi và tải dữ liệu trước');
            return;
        }
        
        const criteria = $('#criteriaSelect').val();
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=autoArrange',
            method: 'POST',
            data: { criteria: criteria, exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    autoArrangementResults = response.data;
                    displayAutoResults(response.data);
                    $('#saveAutoBtn').prop('disabled', false);
                }
            }
        });
    });
    
    function displayAutoResults(results) {
        if (results.length === 0) {
            $('#autoResults').html('<div class="alert alert-warning">Không có thí sinh nào để sắp xếp</div>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="data-table">';
        html += '<thead><tr><th>STT</th><th>Mã HS</th><th>Thí sinh</th><th>Phòng thi</th><th>Số báo danh</th><th>Trường</th></tr></thead><tbody>';
        
        results.forEach((item, index) => {
            html += `<tr class="auto-result-item" 
                    data-student-id="${item.student.maHS}" 
                    data-room-id="${item.room.maPhong}"
                    data-so-bao-danh="${item.soBaoDanh}">
                <td>${index + 1}</td>
                <td><span class="badge bg-secondary">${item.student.maHS}</span></td>
                <td><strong>${item.student.hoVaTen}</strong></td>
                <td>${item.room.tenPhong} <small class="text-muted">(${item.room.maPhong})</small></td>
                <td><span class="badge bg-primary">${item.soBaoDanh}</span></td>
                <td>${item.room.tenTruong}</td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        html += `<div class="mt-3 text-end"><strong>Tổng: ${results.length} thí sinh</strong></div>`;
        $('#autoResults').html(html);
    }
    
    // Save auto arrangement
    $('#saveAutoBtn').click(function() {
        if (autoArrangementResults.length === 0) {
            alert('Không có kết quả nào để lưu');
            return;
        }
        
        if (confirm(`Bạn có chắc chắn muốn lưu kết quả sắp xếp cho ${autoArrangementResults.length} thí sinh?`)) {
            const arrangements = autoArrangementResults.map(item => ({
                studentId: item.student.maHS,
                roomId: item.room.maPhong,
                soBaoDanh: item.soBaoDanh
            }));
            
            $.ajax({
                url: 'index.php?controller=examArrangement&action=saveAutoArrangement',
                method: 'POST',
                data: { arrangements: JSON.stringify(arrangements) },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        // Refresh data
                        loadStatistics();
                        loadUnarrangedStudents();
                        loadArrangedList();
                        $('#saveAutoBtn').prop('disabled', true);
                        $('#autoResults').html('<div class="alert alert-success"><i class="fas fa-check-circle"></i> Đã lưu kết quả sắp xếp thành công!</div>');
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                }
            });
        }
    });
    
    // Load unarranged students for manual arrangement
    function loadUnarrangedStudents() {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getUnarrangedStudents',
            data: { exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayUnarrangedStudents(response.data);
                }
            }
        });
    }
    
    function displayUnarrangedStudents(students) {
        if (students.length === 0) {
            $('#unarrangedList').html('<div class="alert alert-success m-3"><i class="fas fa-check-circle"></i> Không có thí sinh nào chưa xếp phòng</div>');
            return;
        }
        
        let html = '';
        
        students.forEach(student => {
            const birthDate = student.ngaySinh ? new Date(student.ngaySinh).toLocaleDateString('vi-VN') : '';
            html += `<div class="student-item" data-id="${student.maHS}" data-name="${student.hoVaTen}">
                <div class="student-code">${student.maHS}</div>
                <div class="student-name">${student.hoVaTen}</div>
                <div class="student-info">
                    ${birthDate ? birthDate + ' | ' : ''}${student.diaChi || ''}
                </div>
                <div class="mt-2">
                    <small class="text-muted">Nguyện vọng:</small>
                    <ul class="preference-list">
                        ${student.nguyenvong1 ? '<li>1. ' + (student.tenTruong1 || 'Mã trường: ' + student.nguyenvong1) + '</li>' : ''}
                        ${student.nguyenvong2 ? '<li>2. ' + (student.tenTruong2 || 'Mã trường: ' + student.nguyenvong2) + '</li>' : ''}
                        ${student.nguyenvong3 ? '<li>3. ' + (student.tenTruong3 || 'Mã trường: ' + student.nguyenvong3) + '</li>' : ''}
                    </ul>
                </div>
            </div>`;
        });
        
        $('#unarrangedList').html(html);
        
        // Add click event for student items
        $('.student-item').click(function() {
            const studentId = $(this).data('id');
            const studentName = $(this).data('name');
            
            selectStudent(studentId, studentName);
            $('.student-item').removeClass('selected');
            $(this).addClass('selected');
        });
    }
    
    function selectStudent(studentId, studentName) {
        selectedStudent = studentId;
        
        // Load student preferences
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStudentPreferences',
            data: { studentId: studentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const prefs = response.data;
                    displayStudentInfo(studentName, prefs);
                    
                    // Lấy trường nguyện vọng 1 để tìm phòng
                    const schoolId = prefs.nguyenvong1;
                    if (schoolId) {
                        loadAvailableRooms(schoolId);
                    } else {
                        $('#availableRooms').html('<div class="alert alert-warning m-3">Thí sinh chưa có nguyện vọng</div>');
                    }
                }
            }
        });
    }
    
    function displayStudentInfo(studentName, prefs) {
        let html = `<div class="selected-student">${studentName}</div>`;
        html += '<p><strong>Nguyện vọng:</strong></p>';
        html += '<ol class="preference-list">';
        if (prefs.tenTruong1) html += `<li>${prefs.tenTruong1}</li>`;
        if (prefs.tenTruong2) html += `<li>${prefs.tenTruong2}</li>`;
        if (prefs.tenTruong3) html += `<li>${prefs.tenTruong3}</li>`;
        html += '</ol>';
        
        $('#manualArrangementInfo').html(html);
    }
    
    function loadAvailableRooms(schoolId) {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getAvailableRooms',
            data: { truong: schoolId, exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayAvailableRooms(response.data);
                }
            }
        });
    }
    
    function displayAvailableRooms(rooms) {
        if (rooms.length === 0) {
            $('#availableRooms').html('<div class="alert alert-warning m-3">Không có phòng thi trống trong trường này</div>');
            return;
        }
        
        let html = '';
        
        rooms.forEach(room => {
            const availableSeats = room.soChoTrong || (room.soLuongToiDa - room.soLuongHienTai);
            html += `<div class="room-item" data-id="${room.maPhong}" data-name="${room.tenPhong}">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="room-code">${room.maPhong}</div>
                        <div class="room-name">${room.tenPhong}</div>
                        <div class="room-info">${room.tenTruong} - ${room.tenMon}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="room-capacity mb-2">${availableSeats} chỗ trống</span>
                        <small class="text-muted">Sức chứa: ${room.soLuongToiDa}</small>
                    </div>
                </div>
            </div>`;
        });
        
        $('#availableRooms').html(html);
        
        // Add click event for room items
        $('.room-item').click(function() {
            selectedRoom = $(this).data('id');
            $('.room-item').removeClass('selected');
            $(this).addClass('selected');
            $('#saveManualBtn').prop('disabled', false);
            
            // Show selected room info
            const roomName = $(this).data('name');
            const roomInfo = $(this).find('.room-info').text();
            $('#manualArrangementInfo').append(`
                <div class="selected-room mt-3">
                    <strong><i class="fas fa-check-circle"></i> Phòng thi đã chọn:</strong><br>
                    ${roomName} - ${roomInfo}
                </div>
            `);
        });
    }
    
    // Save manual arrangement
    $('#saveManualBtn').click(function() {
        if (!selectedStudent || !selectedRoom) {
            alert('Vui lòng chọn thí sinh và phòng thi');
            return;
        }
        
        if (confirm('Bạn có chắc chắn muốn xếp phòng cho thí sinh này?')) {
            $.ajax({
                url: 'index.php?controller=examArrangement&action=manualArrange',
                method: 'POST',
                data: { studentId: selectedStudent, roomId: selectedRoom },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(`Đã xếp phòng thành công! Số báo danh: ${response.soBaoDanh}`);
                        // Refresh data
                        loadStatistics();
                        loadUnarrangedStudents();
                        loadArrangedList();
                        // Reset selection
                        $('#saveManualBtn').prop('disabled', true);
                        selectedStudent = null;
                        selectedRoom = null;
                        $('.student-item').removeClass('selected');
                        $('.room-item').removeClass('selected');
                        $('#manualArrangementInfo').html('<div class="text-center text-muted py-4"><i class="fas fa-user fa-3x mb-3"></i><p>Chọn thí sinh để xếp phòng</p></div>');
                        $('#availableRooms').html('<div class="alert alert-warning m-3"><i class="fas fa-exclamation-circle"></i> Chọn thí sinh để hiển thị phòng trống</div>');
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
    
    // Load arranged list
    function loadArrangedList() {
        const sortBy = $('#sortSelect').val();
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getArrangedList',
            data: { exam: currentExam, sort: sortBy },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayArrangedList(response.data);
                }
            }
        });
    }
    
    function displayArrangedList(list) {
        if (list.length === 0) {
            $('#arrangedList').html('<div class="alert alert-info m-3"><i class="fas fa-info-circle"></i> Chưa có thí sinh nào được xếp phòng</div>');
            return;
        }
        
        let html = '<table class="data-table">';
        html += '<thead><tr><th>STT</th><th>Số báo danh</th><th>Thí sinh</th><th>Phòng thi</th><th>Trường</th><th>Môn thi</th><th>Ngày thi</th><th>Ca thi</th></tr></thead><tbody>';
        
        list.forEach((item, index) => {
            html += `<tr>
                <td>${index + 1}</td>
                <td><span class="badge bg-primary">${item.soBaoDanh}</span></td>
                <td><strong>${item.hoVaTen}</strong><br><small class="text-muted">Mã HS: ${item.maHS}</small></td>
                <td>${item.tenPhong}</td>
                <td>${item.tenTruong}</td>
                <td>${item.tenMon}</td>
                <td>${item.ngayThi}</td>
                <td><span class="badge bg-secondary">${item.caThi}</span></td>
            </tr>`;
        });
        
        html += '</tbody></table>';
        html += `<div class="mt-3 text-end"><strong>Tổng: ${list.length} thí sinh</strong></div>`;
        $('#arrangedList').html(html);
    }
    
    // Sort change event
    $('#sortSelect').change(function() {
        if (currentExam) {
            loadArrangedList();
        }
    });
    
    // Export button
    $('#exportBtn').click(function() {
        if (!currentExam) {
            alert('Vui lòng chọn kỳ thi trước');
            return;
        }
        
        const sortBy = $('#sortSelect').val();
        window.open(`index.php?controller=examArrangement&action=exportList&exam=${currentExam}&sort=${sortBy}`);
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>