<?php require_once 'views/layout/header.php'; ?>
<?php require_once 'views/layout/sidebar_department.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* CSS Styles - Same as the previous code */
.main-content {
    padding: 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

.exam-select-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-1 { border-top: 4px solid #3498db; }
.stat-2 { border-top: 4px solid #2ecc71; }
.stat-3 { border-top: 4px solid #e74c3c; }
.stat-4 { border-top: 4px solid #f39c12; }

.stat-icon {
    margin-bottom: 10px;
    color: #3498db;
}

.stat-number {
    font-size: 28px;
    font-weight: bold;
    margin: 10px 0;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Tabs */
.tab-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.nav-tabs-custom {
    display: flex;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 0;
    border-radius: 10px 10px 0 0;
}

.nav-link {
    flex: 1;
    padding: 15px 20px;
    background: none;
    border: none;
    font-weight: 500;
    color: rgba(255,255,255,0.8);
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    position: relative;
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.nav-link.active {
    background: white;
    color: #3498db;
    border-radius: 8px 8px 0 0;
    margin-top: -5px;
    padding-top: 20px;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 5px;
    background: white;
}

.tab-content {
    padding: 30px;
}

.tab-section {
    margin-bottom: 30px;
}

.tab-section h6 {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f1f1;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Tables */
.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.data-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.data-table td {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    color: #6c757d;
}

.data-table tr:hover {
    background-color: #f8f9fa;
}

.data-table tr:last-child td {
    border-bottom: none;
}

/* Student and room items */
.student-list, .room-list {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
}

.student-item, .room-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.student-item:hover, .room-item:hover {
    background: #f8f9fa;
    border-color: #3498db;
    transform: translateX(5px);
}

.student-item.selected, .room-item.selected {
    background: #e3f2fd;
    border-color: #3498db;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
}

.student-code {
    font-weight: bold;
    color: #3498db;
    margin-bottom: 5px;
    font-size: 14px;
}

.student-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
}

.student-info {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 5px;
    line-height: 1.5;
}

.room-code {
    font-weight: bold;
    color: #e74c3c;
    font-size: 14px;
}

.room-name {
    font-size: 16px;
    font-weight: 600;
    margin: 8px 0;
    color: #2c3e50;
}

.room-info {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 5px;
}

.room-capacity {
    font-weight: bold;
    color: #27ae60;
    font-size: 18px;
}

/* Flow steps */
.arrangement-flow {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 30px;
    color: white;
}

.arrangement-flow h6 {
    color: white;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.flow-step {
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}

.flow-step.active {
    border-left-color: #3498db;
    background: rgba(52, 152, 219, 0.2);
}

.flow-step.completed {
    border-left-color: #27ae60;
    background: rgba(39, 174, 96, 0.2);
}

.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    background: rgba(255,255,255,0.2);
    color: white;
    border-radius: 50%;
    margin-right: 15px;
    font-weight: bold;
}

.flow-step.active .step-number {
    background: #3498db;
}

.flow-step.completed .step-number {
    background: #27ae60;
}

.manual-instruction {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 6px;
    padding: 12px;
    margin-top: 10px;
    font-size: 14px;
    color: rgba(255,255,255,0.9);
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 204, 113, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(149, 165, 166, 0.4);
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
}

/* Forms */
.form-control, .form-select {
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #495057;
}

/* Alerts */
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: none;
    font-size: 15px;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
    border-left: 4px solid #3498db;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #2ecc71;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #f39c12;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #e74c3c;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bg-primary { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; 
    color: white;
}
.bg-secondary { 
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%) !important; 
    color: white;
}
.bg-success { 
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important; 
    color: white;
}
.bg-danger { 
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important; 
    color: white;
}
.bg-warning { 
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important; 
    color: white;
}

/* Progress bars */
.progress {
    height: 12px;
    background-color: #ecf0f1;
    border-radius: 6px;
    overflow: hidden;
    margin: 15px 0;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.6s ease;
}

/* School selector */
.school-selector {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.school-selector .btn {
    flex: 1;
    min-width: 200px;
    text-align: left;
    padding: 15px;
    border: 2px solid #e9ecef;
    background: white;
    color: #495057;
    border-radius: 8px;
    transition: all 0.3s;
}

.school-selector .btn:hover {
    border-color: #667eea;
    background: #f8f9fa;
}

.school-selector .btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Preference details */
.preference-details {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 20px;
    margin-top: 15px;
    border: 1px solid #dee2e6;
}

.preference-item {
    padding: 15px;
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 10px;
    border-radius: 6px;
    background: white;
    transition: all 0.3s;
}

.preference-item:hover {
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preference-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.preference-item strong {
    color: #2c3e50;
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .school-selector {
        flex-direction: column;
    }
    
    .school-selector .btn {
        min-width: 100%;
    }
    
    .flow-step {
        padding: 15px;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .nav-tabs-custom {
        flex-direction: column;
    }
    
    .nav-link {
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .nav-link.active {
        margin-top: 0;
        border-radius: 6px;
    }
    
    .data-table {
        font-size: 14px;
    }
    
    .data-table th, .data-table td {
        padding: 10px;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.student-item, .room-item, .flow-step, .tab-section {
    animation: fadeIn 0.5s ease-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a3f8f 100%);
}
</style>

<div class="main-content">
    <h2 style="color: #2c3e50; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
        <i class="fas fa-door-closed" style="color: #667eea; font-size: 32px;"></i>
        <span>Xếp Danh Sách Thi Tuyển Sinh</span>
    </h2>
    
    <!-- Exam Selection -->
    <div class="exam-select-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 style="color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calendar-alt" style="color: #667eea;"></i>
                    <span>Chọn Kỳ Thi Tuyển Sinh</span>
                </h5>
                <div class="form-group">
                    <select id="examSelect" class="form-control form-control-lg">
                        <option value="">-- Chọn kỳ tuyển sinh --</option>
                        <?php foreach ($exams as $exam): ?>
                        <option value="<?php echo $exam['maKyTS']; ?>" <?php echo $selectedExam == $exam['maKyTS'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($exam['tenKyTS']); ?> 
                            (<?php echo date('d/m/Y', strtotime($exam['ngayBatDau'])); ?> - 
                            <?php echo date('d/m/Y', strtotime($exam['ngayKetThuc'])); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button id="loadDataBtn" class="btn btn-primary btn-lg" style="padding: 12px 30px; font-size: 16px;">
                    <i class="fas fa-sync-alt"></i> Tải Dữ Liệu
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
            <div class="stat-label">Tổng thí sinh đăng ký</div>
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
            <button class="nav-link" id="rooms-tab" data-target="#rooms">
                <i class="fas fa-door-open"></i> Quản lý phòng thi
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
                    <p style="color: #6c757d; margin-bottom: 20px; line-height: 1.6;">
                        Hệ thống tự động xếp phòng thi theo các tiêu chí: số phòng, vị trí, số lượng còn trống, nguyện vọng. 
                        Phù hợp cho việc sắp xếp hàng loạt thí sinh đã đăng ký đúng hạn.
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label class="form-label">Tiêu chí sắp xếp:</label>
                            <select id="criteriaSelect" class="form-select">
                                <option value="phong">Theo số phòng thi</option>
                                <option value="vitri">Theo vị trí</option>
                                <option value="nguyenvong">Theo nguyện vọng</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button id="autoArrangeBtn" class="btn btn-primary w-100">
                                <i class="fas fa-play-circle"></i> Sắp xếp tự động
                            </button>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button id="saveAutoBtn" class="btn btn-success w-100" disabled>
                                <i class="fas fa-save"></i> Lưu kết quả
                            </button>
                        </div>
                    </div>
                    
                    <div id="autoResults">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Hướng dẫn:</strong> 
                            <ol style="margin-top: 10px; margin-bottom: 0; padding-left: 20px;">
                                <li>Chọn kỳ thi từ danh sách phía trên</li>
                                <li>Nhấn nút "Tải Dữ Liệu" để load thông tin</li>
                                <li>Chọn tiêu chí sắp xếp</li>
                                <li>Nhấn "Sắp xếp tự động" để hệ thống xử lý</li>
                                <li>Kiểm tra kết quả và nhấn "Lưu kết quả" để lưu vào CSDL</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Manual Arrangement Tab -->
            <div id="manual" class="tab-pane" style="display: none;">
                <div class="arrangement-flow">
                    <h6><i class="fas fa-list-ol"></i> QUY TRÌNH XẾP PHÒNG THỦ CÔNG</h6>
                    
                    <div class="flow-step active" id="step1">
                        <span class="step-number">1</span>
                        <strong>Chọn thí sinh chưa xếp phòng</strong>
                        <div class="manual-instruction mt-2">
                            Chọn thí sinh từ danh sách bên dưới hoặc tìm theo trường
                        </div>
                    </div>
                    
                    <div class="flow-step" id="step2">
                        <span class="step-number">2</span>
                        <strong>Xem thông tin nguyện vọng</strong>
                        <div class="manual-instruction mt-2">
                            Hệ thống hiển thị nguyện vọng của thí sinh và danh sách trường theo nguyện vọng
                        </div>
                    </div>
                    
                    <div class="flow-step" id="step3">
                        <span class="step-number">3</span>
                        <strong>Chọn trường và kiểm tra chỉ tiêu</strong>
                        <div class="manual-instruction mt-2">
                            Chọn trường từ danh sách nguyện vọng và kiểm tra số lượng thí sinh của trường
                        </div>
                    </div>
                    
                    <div class="flow-step" id="step4">
                        <span class="step-number">4</span>
                        <strong>Chọn phòng thi còn trống</strong>
                        <div class="manual-instruction mt-2">
                            Chọn phòng thi từ danh sách phòng trống của trường
                        </div>
                    </div>
                    
                    <div class="flow-step" id="step5">
                        <span class="step-number">5</span>
                        <strong>Xác nhận và lưu kết quả</strong>
                        <div class="manual-instruction mt-2">
                            Xác nhận thông tin và lưu kết quả xếp phòng với trạng thái "Đã xếp phòng thi"
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="tab-section">
                            <h6><i class="fas fa-user-graduate"></i> DANH SÁCH THÍ SINH CHƯA XẾP PHÒNG</h6>
                            
                            <div class="school-selector mb-3">
                                <button class="btn btn-outline-secondary active" onclick="loadUnarrangedStudents()">
                                    <i class="fas fa-users"></i> Tất cả thí sinh
                                </button>
                                <button class="btn btn-outline-secondary" onclick="loadUnarrangedBySchool('TH001')">
                                    <i class="fas fa-school"></i> THPT Lê Hồng Phong
                                </button>
                                <button class="btn btn-outline-secondary" onclick="loadUnarrangedBySchool('TH002')">
                                    <i class="fas fa-school"></i> THPT Trần Đại Nghĩa
                                </button>
                            </div>
                            
                            <div id="unarrangedList" class="student-list">
                                <div class="alert alert-info m-3">
                                    <i class="fas fa-info-circle"></i> 
                                    Chọn kỳ thi và nhấn "Tải dữ liệu" để hiển thị danh sách thí sinh
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="tab-section">
                            <h6><i class="fas fa-info-circle"></i> THÔNG TIN XẾP PHÒNG</h6>
                            <div id="manualArrangementInfo" class="arrangement-info">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user fa-3x mb-3" style="color: #dee2e6;"></i>
                                    <p style="color: #6c757d;">Chọn thí sinh để bắt đầu xếp phòng</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-section">
                            <h6><i class="fas fa-door-open"></i> DANH SÁCH PHÒNG THI CÒN TRỐNG</h6>
                            <div id="availableRooms" class="room-list">
                                <div class="alert alert-warning m-3">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    Chọn thí sinh và trường để hiển thị phòng trống
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button id="saveManualBtn" class="btn btn-primary" style="padding: 12px 40px; font-size: 16px;" disabled>
                                <i class="fas fa-check"></i> Lưu Xếp Phòng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Room Management Tab -->
            <div id="rooms" class="tab-pane" style="display: none;">
                <div class="tab-section">
                    <h6><i class="fas fa-plus-circle"></i> TẠO PHÒNG THI MỚI</h6>
                    <div class="room-management">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="room-form p-4" style="background: #f8f9fa; border-radius: 8px;">
                                    <div class="mb-3">
                                        <label class="form-label">Tên phòng thi:</label>
                                        <input type="text" id="roomName" class="form-control" placeholder="VD: Phòng 1, Phòng A101">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Địa điểm:</label>
                                        <input type="text" id="roomLocation" class="form-control" placeholder="VD: Tầng 1, Khu A, Nhà Hội Trường">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sức chứa:</label>
                                        <input type="number" id="roomCapacity" class="form-control" placeholder="Số lượng thí sinh tối đa" value="30" min="1" max="100">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Trường sử dụng:</label>
                                        <select id="roomSchool" class="form-control">
                                            <option value="">-- Dùng chung cho tất cả trường --</option>
                                            <option value="TH001">THPT Lê Hồng Phong</option>
                                            <option value="TH002">THPT Trần Đại Nghĩa</option>
                                            <option value="TH003">THPT Gia Định</option>
                                        </select>
                                    </div>
                                    <button id="createRoomBtn" class="btn btn-success w-100">
                                        <i class="fas fa-plus"></i> Tạo Phòng Thi
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info h-100">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                                    <h6>Lưu ý khi tạo phòng thi:</h6>
                                    <ul style="margin-top: 15px;">
                                        <li>Phòng thi sẽ được tạo cho kỳ thi đang chọn</li>
                                        <li>Để trống "Trường sử dụng" nếu phòng dùng chung cho nhiều trường</li>
                                        <li>Phòng dành riêng cho trường sẽ được ưu tiên khi xếp phòng cho thí sinh của trường đó</li>
                                        <li>Sức chứa tối đa đề xuất: 30-50 thí sinh/phòng</li>
                                        <li>Có thể tạo nhiều phòng cùng địa điểm</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mt-5"><i class="fas fa-list"></i> DANH SÁCH PHÒNG THI</h6>
                    <div id="roomManagementList" class="room-list-management mt-3">
                        <div class="alert alert-info m-3">
                            <i class="fas fa-info-circle"></i> Chọn kỳ thi để hiển thị danh sách phòng
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Arranged List Tab -->
            <div id="list" class="tab-pane" style="display: none;">
                <div class="tab-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6><i class="fas fa-clipboard-list"></i> DANH SÁCH ĐÃ XẾP PHÒNG</h6>
                        <div class="d-flex gap-2">
                            <select id="sortSelect" class="form-select" style="width: auto;">
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

<!-- JavaScript Code - Same as before -->
<script>
$(document).ready(function() {
    let currentExam = null;
    let selectedStudent = null;
    let selectedRoom = null;
    let selectedSchool = null;
    let autoArrangementResults = [];
    let currentStudentPreferences = null;
    let currentStep = 1;
    
    // Khởi tạo tabs
    function initTabs() {
        $('.tab-pane').hide();
        $('#auto').show();
        
        $('.nav-link').removeClass('active');
        $('#auto-tab').addClass('active');
    }
    
    initTabs();
    
    // Tab switching
    $('.nav-link').click(function(e) {
        e.preventDefault();
        
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        
        const target = $(this).data('target');
        
        $('.tab-pane').hide();
        $(target).show();
        
        if (target === '#manual' && currentExam) {
            loadUnarrangedStudents();
            resetManualFlow();
        }
        
        if (target === '#rooms' && currentExam) {
            loadRoomManagementList();
        }
        
        if (target === '#list' && currentExam) {
            loadArrangedList();
        }
    });
    
    // Reset flow xếp thủ công
    function resetManualFlow() {
        currentStep = 1;
        selectedStudent = null;
        selectedRoom = null;
        selectedSchool = null;
        
        $('.flow-step').removeClass('active completed');
        $('#step1').addClass('active');
        
        $('#manualArrangementInfo').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-user fa-3x mb-3" style="color: #dee2e6;"></i>
                <p style="color: #6c757d;">Chọn thí sinh để bắt đầu xếp phòng</p>
            </div>
        `);
        
        $('#availableRooms').html(`
            <div class="alert alert-warning m-3">
                <i class="fas fa-exclamation-circle"></i> 
                Chọn thí sinh và trường để hiển thị phòng trống
            </div>
        `);
        
        $('#saveManualBtn').prop('disabled', true);
    }
    
    // Cập nhật bước flow
    function updateFlowStep(step) {
        $('.flow-step').removeClass('active');
        $(`#step${step}`).addClass('active');
        
        // Đánh dấu các bước trước đó đã hoàn thành
        for (let i = 1; i < step; i++) {
            $(`#step${i}`).addClass('completed');
        }
        
        currentStep = step;
    }
    
    // Load data khi chọn kỳ thi
    $('#loadDataBtn').click(function() {
        currentExam = $('#examSelect').val();
        if (!currentExam) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng chọn kỳ thi',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        loadStatistics();
        loadUnarrangedStudents();
        loadRoomManagementList();
        loadArrangedList();
        
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: 'Đã tải dữ liệu thành công',
            timer: 1500,
            showConfirmButton: false,
            background: '#f8f9fa'
        });
    });
    
    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStatistics',
            method: 'POST',
            data: { exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    const total = parseInt(stats.tong);
                    const daXep = parseInt(stats.daXep);
                    const chuaXep = parseInt(stats.chuaXep);
                    
                    $('#totalStudents').text(total.toLocaleString());
                    $('#arrangedStudents').text(daXep.toLocaleString());
                    $('#unarrangedStudents').text(chuaXep.toLocaleString());
                    
                    const rate = total > 0 ? Math.round((daXep / total) * 100) : 0;
                    $('#completionRate').text(rate + '%');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể tải thống kê',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    }
    
    // Auto arrangement
    // Trong file exam_arrangement_index.php, tìm phần này và sửa:

// Auto arrangement
$('#autoArrangeBtn').click(function() {
    if (!currentExam) {
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn kỳ thi và tải dữ liệu trước',
            confirmButtonColor: '#667eea'
        });
        return;
    }
    
    const criteria = $('#criteriaSelect').val();
    
    Swal.fire({
        title: 'Đang xử lý...',
        text: 'Hệ thống đang sắp xếp phòng thi theo tiêu chí: ' + getCriteriaText(criteria),
        allowOutsideClick: false,
        showConfirmButton: false,
        background: '#f8f9fa',
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: 'index.php?controller=examArrangement&action=autoArrangeEnhanced',
        method: 'POST',
        data: { 
            criteria: criteria, 
            exam: currentExam 
        },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            if (response.success) {
                autoArrangementResults = response.data;
                displayAutoResults(response.data);
                $('#saveAutoBtn').prop('disabled', false);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: response.message || `Đã xếp phòng cho ${response.count} thí sinh`,
                    confirmButtonColor: '#2ecc71'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: response.message || 'Lỗi khi sắp xếp',
                    confirmButtonColor: '#e74c3c'
                });
                
                // Hiển thị partial results nếu có
                if (response.partialResults) {
                    displayAutoResults(response.partialResults);
                }
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('AJAX Error:', error);
            console.error('Response:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi kết nối',
                text: 'Lỗi kết nối server. Vui lòng thử lại.',
                confirmButtonColor: '#e74c3c'
            });
        }
    });
});

// Thêm function getCriteriaText
function getCriteriaText(criteria) {
    const criteriaMap = {
        'phong': 'Theo số phòng thi',
        'vitri': 'Theo vị trí',
        'nguyenvong': 'Theo nguyện vọng'
    };
    return criteriaMap[criteria] || criteria;
}
    
    function getCriteriaText(criteria) {
        const criteriaMap = {
            'phong': 'Theo số phòng thi',
            'vitri': 'Theo vị trí',
            'nguyenvong': 'Theo nguyện vọng'
        };
        return criteriaMap[criteria] || criteria;
    }
    
    function displayAutoResults(results) {
        if (results.length === 0) {
            $('#autoResults').html('<div class="alert alert-warning">Không có thí sinh nào để sắp xếp</div>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="data-table">';
        html += '<thead><tr><th>STT</th><th>Mã HS</th><th>Thí sinh</th><th>Phòng thi</th><th>Số báo danh</th><th>Nguyện vọng</th><th>Trường</th><th>Địa điểm</th></tr></thead><tbody>';
        
        results.forEach((item, index) => {
            html += `<tr class="auto-result-item" 
                    data-student-id="${item.studentId}" 
                    data-room-id="${item.roomId}"
                    data-so-bao-danh="${item.soBaoDanh}">
                <td>${index + 1}</td>
                <td><span class="badge bg-secondary">${item.student.maHS}</span></td>
                <td><strong>${item.student.hoTenHS}</strong></td>
                <td>${item.room.tenPhongTS} <small class="text-muted">(${item.room.maPhongTS})</small></td>
                <td><span class="badge bg-primary">${item.soBaoDanh}</span></td>
                <td>${item.preferenceOrder > 0 ? 'NV' + item.preferenceOrder : 'Không có'}</td>
                <td>${item.schoolName || 'Chưa có'}</td>
                <td>${item.room.diaDiem || 'Chưa có'}</td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        html += `<div class="mt-3 text-end"><strong>Tổng: ${results.length} thí sinh</strong></div>`;
        $('#autoResults').html(html);
    }
    
    // Save auto arrangement
    $('#saveAutoBtn').click(function() {
        if (autoArrangementResults.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: 'Không có kết quả nào để lưu',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận lưu kết quả',
            text: `Bạn có chắc chắn muốn lưu kết quả sắp xếp cho ${autoArrangementResults.length} thí sinh?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            background: '#f8f9fa'
        }).then((result) => {
            if (result.isConfirmed) {
                const arrangements = autoArrangementResults.map(item => ({
                    studentId: item.studentId,
                    roomId: item.roomId,
                    soBaoDanh: item.soBaoDanh
                }));
                
                $.ajax({
                    url: 'index.php?controller=examArrangement&action=saveAutoArrangement',
                    method: 'POST',
                    data: { arrangements: JSON.stringify(arrangements) },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                confirmButtonColor: '#2ecc71'
                            });
                            
                            // Refresh data
                            loadStatistics();
                            loadUnarrangedStudents();
                            loadArrangedList();
                            loadRoomManagementList();
                            $('#saveAutoBtn').prop('disabled', true);
                            $('#autoResults').html('<div class="alert alert-success"><i class="fas fa-check-circle"></i> Đã lưu kết quả sắp xếp thành công!</div>');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Lỗi kết nối server khi lưu kết quả',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });
    });
    
    // Load unarranged students
    function loadUnarrangedStudents() {
        if (!currentExam) return;
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getUnarrangedStudents',
            method: 'POST',
            data: { exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayUnarrangedStudents(response.data);
                }
            },
            error: function() {
                $('#unarrangedList').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Lỗi khi tải danh sách thí sinh</div>');
            }
        });
    }
    
    // Load unarranged students by school
    function loadUnarrangedBySchool(schoolId) {
        if (!currentExam) return;
        
        $('.school-selector .btn').removeClass('active');
        $(`.school-selector .btn:contains('${getSchoolName(schoolId)}')`).addClass('active');
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getUnarrangedBySchool',
            method: 'POST',
            data: { 
                exam: currentExam,
                schoolId: schoolId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayUnarrangedBySchool(response.data, schoolId);
                }
            },
            error: function() {
                $('#unarrangedList').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Lỗi khi tải danh sách thí sinh</div>');
            }
        });
    }
    
    function getSchoolName(schoolId) {
        const schools = {
            'TH001': 'THPT Lê Hồng Phong',
            'TH002': 'THPT Trần Đại Nghĩa',
            'TH003': 'THPT Gia Định'
        };
        return schools[schoolId] || schoolId;
    }
    
    function displayUnarrangedStudents(students) {
        if (students.length === 0) {
            $('#unarrangedList').html('<div class="alert alert-success m-3"><i class="fas fa-check-circle"></i> Không có thí sinh nào chưa xếp phòng</div>');
            return;
        }
        
        let html = '';
        
        students.forEach((student, index) => {
            const birthDate = student.ngaySinhHS ? new Date(student.ngaySinhHS).toLocaleDateString('vi-VN') : '';
            html += `<div class="student-item" data-id="${student.maHS}" data-name="${student.hoTenHS}">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <div class="student-code">${student.maHS}</div>
                        <div class="student-name">${student.hoTenHS}</div>
                        <div class="student-info">
                            ${birthDate ? '<i class="fas fa-birthday-cake me-1"></i>' + birthDate : ''}
                            ${student.gioiTinhHS ? ' | <i class="fas fa-venus-mars me-1"></i>' + student.gioiTinhHS : ''}
                        </div>
                        ${student.diaChiHS ? `<div class="student-info"><i class="fas fa-map-marker-alt me-1"></i>${student.diaChiHS}</div>` : ''}
                        ${student.hoTenPH ? `<div class="student-info"><i class="fas fa-user-friends me-1"></i>${student.hoTenPH} (${student.quanHe || 'Phụ huynh'})</div>` : ''}
                        ${student.soDienThoaiPH ? `<div class="student-info"><i class="fas fa-phone me-1"></i>${student.soDienThoaiPH}</div>` : ''}
                    </div>
                    <span class="badge bg-secondary" style="font-size: 12px;">#${index + 1}</span>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary view-pref-btn" data-id="${student.maHS}">
                        <i class="fas fa-eye"></i> Xem nguyện vọng
                    </button>
                    <button class="btn btn-sm btn-outline-success select-btn" data-id="${student.maHS}" data-name="${student.hoTenHS}">
                        <i class="fas fa-check"></i> Chọn
                    </button>
                </div>
            </div>`;
        });
        
        $('#unarrangedList').html(html);
        
        // Add click event for student items
        $('.student-item').click(function(e) {
            if (!$(e.target).closest('button').length) {
                const studentId = $(this).data('id');
                const studentName = $(this).data('name');
                
                selectStudent(studentId, studentName);
                $('.student-item').removeClass('selected');
                $(this).addClass('selected');
                updateFlowStep(2);
            }
        });
        
        // Add click event for view preference buttons
        $('.view-pref-btn').click(function(e) {
            e.stopPropagation();
            const studentId = $(this).data('id');
            viewStudentPreferences(studentId);
        });
        
        // Add click event for select buttons
        $('.select-btn').click(function(e) {
            e.stopPropagation();
            const studentId = $(this).data('id');
            const studentName = $(this).data('name');
            
            selectStudent(studentId, studentName);
            $('.student-item').removeClass('selected');
            $(this).closest('.student-item').addClass('selected');
            updateFlowStep(2);
        });
    }
    
    function displayUnarrangedBySchool(students, schoolId) {
        if (students.length === 0) {
            $('#unarrangedList').html(`
                <div class="alert alert-success m-3">
                    <i class="fas fa-check-circle"></i> Không có thí sinh nào chưa xếp phòng cho trường ${getSchoolName(schoolId)}
                </div>
            `);
            return;
        }
        
        let html = '<div class="mb-3 d-flex justify-content-between align-items-center">';
        html += `<div>
            <button class="btn btn-sm btn-secondary" onclick="loadUnarrangedStudents()">
                <i class="fas fa-arrow-left"></i> Xem tất cả thí sinh
            </button>
            <span class="ms-3"><strong>Trường:</strong> <span class="badge bg-primary">${getSchoolName(schoolId)}</span></span>
        </div>`;
        html += `<span class="badge bg-info">${students.length} thí sinh</span>`;
        html += '</div>';
        
        students.forEach((student, index) => {
            const birthDate = student.ngaySinhHS ? new Date(student.ngaySinhHS).toLocaleDateString('vi-VN') : '';
            html += `<div class="student-item" data-id="${student.maHS}" data-name="${student.hoTenHS}">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <div class="student-code">${student.maHS}</div>
                        <div class="student-name"><strong>${student.hoTenHS}</strong></div>
                        <div class="student-info">
                            ${birthDate} | ${student.gioiTinhHS || ''}
                        </div>`;
            
            // Hiển thị nguyện vọng
            if (student.nguyenVongDetails && student.nguyenVongDetails.length > 0) {
                html += '<div class="student-info"><strong><i class="fas fa-graduation-cap me-1"></i>Nguyện vọng:</strong><div class="mt-2">';
                student.nguyenVongDetails.forEach(pref => {
                    const isCurrentSchool = pref.tenTruong.includes(getSchoolName(schoolId));
                    const badgeClass = isCurrentSchool ? 'bg-primary' : 'bg-secondary';
                    
                    html += `<span class="badge ${badgeClass} me-1 mb-1" style="font-size: 11px;">
                        <i class="fas fa-${isCurrentSchool ? 'star' : 'school'} me-1"></i>
                        NV${pref.thuTu}: ${pref.tenTruong}
                    </span>`;
                });
                html += '</div></div>';
            }
            
            html += `</div>
                    <span class="badge bg-secondary" style="font-size: 12px;">#${index + 1}</span>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary w-100 arrange-btn" 
                            data-id="${student.maHS}" 
                            data-school="${schoolId}">
                        <i class="fas fa-door-open"></i> Xếp phòng cho trường này
                    </button>
                </div>
            </div>`;
        });
        
        $('#unarrangedList').html(html);
        
        // Add click event for arrange buttons
        $('.arrange-btn').click(function(e) {
            e.stopPropagation();
            const studentId = $(this).data('id');
            const schoolId = $(this).data('school');
            const studentItem = $(this).closest('.student-item');
            const studentName = studentItem.data('name');
            
            selectStudentForSchool(studentId, studentName, schoolId);
            $('.student-item').removeClass('selected');
            studentItem.addClass('selected');
        });
        
        // Add click event for student items
        $('.student-item').click(function(e) {
            if (!$(e.target).closest('button').length) {
                const studentId = $(this).data('id');
                const studentName = $(this).data('name');
                
                selectStudent(studentId, studentName);
                $('.student-item').removeClass('selected');
                $(this).addClass('selected');
                updateFlowStep(2);
            }
        });
    }
    
    function selectStudentForSchool(studentId, studentName, schoolId) {
        selectedStudent = studentId;
        selectedSchool = schoolId;
        
        // Load thông tin nguyện vọng
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStudentPreferences',
            method: 'POST',
            data: { studentId: studentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayStudentInfoForSchool(response.data, schoolId);
                    updateFlowStep(3);
                    
                    // Load chỉ tiêu trường
                    checkSchoolQuota(schoolId);
                    
                    // Load phòng trống
                    loadRoomsBySchool(schoolId);
                }
            }
        });
    }
    
    function selectStudent(studentId, studentName) {
        selectedStudent = studentId;
        selectedSchool = null;
        
        // Load thông tin nguyện vọng
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStudentPreferences',
            method: 'POST',
            data: { studentId: studentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayStudentInfo(response.data);
                    updateFlowStep(2);
                }
            }
        });
    }
    
    function viewStudentPreferences(studentId) {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getStudentPreferences',
            method: 'POST',
            data: { studentId: studentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const prefs = response.data;
                    let html = '<div class="preference-details">';
                    html += '<h6 style="color: #2c3e50; margin-bottom: 15px;">Thông tin nguyện vọng</h6>';
                    
                    if (prefs.preferences && prefs.preferences.length > 0) {
                        prefs.preferences.forEach((pref, index) => {
                            html += `<div class="preference-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge ${index === 0 ? 'bg-primary' : 'bg-secondary'}">Nguyện vọng ${pref.thuTuNguyenVong}</span>
                                        <strong style="display: block; margin-top: 5px;">${pref.tenTruong}</strong>
                                        ${pref.diaChiTruong ? `<small><i class="fas fa-map-marker-alt me-1"></i>${pref.diaChiTruong}</small>` : ''}
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary select-pref-btn" 
                                            data-school-id="${pref.maTruong}"
                                            data-school-name="${pref.tenTruong}">
                                        <i class="fas fa-check"></i> Chọn
                                    </button>
                                </div>
                            </div>`;
                        });
                    } else {
                        html += '<div class="alert alert-warning">Thí sinh chưa có nguyện vọng</div>';
                    }
                    
                    html += '</div>';
                    
                    Swal.fire({
                        title: `Nguyện vọng của ${prefs.hoTenHS}`,
                        html: html,
                        confirmButtonText: 'Đóng',
                        background: '#f8f9fa',
                        width: '600px'
                    });
                    
                    // Add event for select preference buttons
                    $('.select-pref-btn').click(function() {
                        const schoolId = $(this).data('school-id');
                        const schoolName = $(this).data('school-name');
                        
                        selectedSchool = schoolId;
                        Swal.close();
                        
                        // Load chỉ tiêu trường
                        checkSchoolQuota(schoolId);
                        
                        // Load phòng trống
                        loadRoomsBySchool(schoolId);
                        
                        // Update display
                        displayStudentInfo(prefs);
                        $('#manualArrangementInfo').append(`
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle"></i> Đã chọn trường: <strong>${schoolName}</strong>
                            </div>
                        `);
                        updateFlowStep(3);
                    });
                }
            }
        });
    }
    
    function displayStudentInfo(prefs) {
        let html = `<div class="selected-student-info">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-graduate fa-2x me-3" style="color: #667eea;"></i>
                    <div>
                        <strong>Thí sinh:</strong> ${prefs.hoTenHS}<br>
                        <strong>Mã HS:</strong> <span class="badge bg-secondary">${prefs.maHS}</span>
                    </div>
                </div>
            </div>`;
        
        if (prefs.preferences && prefs.preferences.length > 0) {
            html += '<p><strong><i class="fas fa-graduation-cap me-1"></i>Nguyện vọng của thí sinh:</strong></p>';
            html += '<div class="preference-details">';
            
            prefs.preferences.forEach((pref, index) => {
                html += `<div class="preference-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge ${index === 0 ? 'bg-primary' : 'bg-secondary'}">Nguyện vọng ${pref.thuTuNguyenVong}</span>
                            <strong style="display: block; margin-top: 5px;">${pref.tenTruong}</strong>
                            ${pref.diaChiTruong ? `<small><i class="fas fa-map-marker-alt me-1"></i>${pref.diaChiTruong}</small>` : ''}
                        </div>
                        <button class="btn btn-sm ${selectedSchool === pref.maTruong ? 'btn-success' : 'btn-outline-primary'} select-school-btn" 
                                data-school-id="${pref.maTruong}"
                                data-school-name="${pref.tenTruong}">
                            <i class="fas fa-${selectedSchool === pref.maTruong ? 'check' : 'school'}"></i>
                            ${selectedSchool === pref.maTruong ? 'Đã chọn' : 'Chọn'}
                        </button>
                    </div>
                </div>`;
            });
            
            html += '</div>';
        } else {
            html += '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Thí sinh chưa có nguyện vọng</div>';
        }
        
        html += '</div>';
        
        $('#manualArrangementInfo').html(html);
        
        // Thêm sự kiện cho nút chọn trường
        $('.select-school-btn').click(function() {
            const schoolId = $(this).data('school-id');
            const schoolName = $(this).data('school-name');
            
            selectedSchool = schoolId;
            updateFlowStep(3);
            
            // Load chỉ tiêu trường
            checkSchoolQuota(schoolId);
            
            // Load phòng trống của trường
            loadRoomsBySchool(schoolId);
            
            // Refresh buttons
            $('.select-school-btn').removeClass('btn-success').addClass('btn-outline-primary')
                .html('<i class="fas fa-school"></i> Chọn');
            $(this).removeClass('btn-outline-primary').addClass('btn-success')
                .html('<i class="fas fa-check"></i> Đã chọn');
            
            // Cập nhật giao diện
            $('#manualArrangementInfo').append(`
                <div class="alert alert-success mt-3">
                    <i class="fas fa-check-circle"></i> Đã chọn trường: <strong>${schoolName}</strong>
                </div>
            `);
        });
    }
    
    function displayStudentInfoForSchool(prefs, schoolId) {
        let html = `<div class="selected-student-info">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-graduate fa-2x me-3" style="color: #667eea;"></i>
                    <div>
                        <strong>Thí sinh:</strong> ${prefs.hoTenHS}<br>
                        <strong>Mã HS:</strong> <span class="badge bg-secondary">${prefs.maHS}</span>
                    </div>
                </div>
            </div>`;
        
        if (prefs.preferences && prefs.preferences.length > 0) {
            html += '<p><strong><i class="fas fa-graduation-cap me-1"></i>Nguyện vọng của thí sinh:</strong></p>';
            html += '<div class="preference-details">';
            
            prefs.preferences.forEach(pref => {
                const isSelected = pref.maTruong == schoolId;
                const badgeClass = isSelected ? 'bg-success' : 'bg-secondary';
                
                html += `<div class="preference-item ${isSelected ? 'selected-preference' : ''}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge ${badgeClass}">Nguyện vọng ${pref.thuTuNguyenVong}</span>
                            <strong style="display: block; margin-top: 5px;">${pref.tenTruong}</strong>
                            ${pref.diaChiTruong ? `<small><i class="fas fa-map-marker-alt me-1"></i>${pref.diaChiTruong}</small>` : ''}
                        </div>
                        ${isSelected ? '<span class="badge bg-success"><i class="fas fa-check"></i> Đã chọn</span>' : ''}
                    </div>
                </div>`;
            });
            
            html += '</div>';
            
            // Hiển thị trường đã chọn
            const selectedPref = prefs.preferences.find(p => p.maTruong == schoolId);
            if (selectedPref) {
                html += `<div class="alert alert-success mt-3">
                    <i class="fas fa-check-circle"></i> 
                    Đã chọn trường theo nguyện vọng ${selectedPref.thuTuNguyenVong}: 
                    <strong>${selectedPref.tenTruong}</strong>
                </div>`;
            }
        }
        
        html += '</div>';
        
        $('#manualArrangementInfo').html(html);
    }
    
    // Kiểm tra chỉ tiêu trường
    function checkSchoolQuota(schoolId) {
        $.ajax({
            url: 'index.php?controller=examArrangement&action=checkSchoolQuota',
            method: 'POST',
            data: { 
                exam: currentExam,
                schoolId: schoolId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    displaySchoolQuota(response.data, schoolId);
                }
            }
        });
    }
    
    function displaySchoolQuota(quota, schoolId) {
        const percent = quota.percent || 0;
        const progressClass = percent >= 100 ? 'bg-danger' : 
                            percent >= 80 ? 'bg-warning' : 'bg-success';
        
        let html = `<div class="school-quota-card">
            <h6><i class="fas fa-chart-bar me-2"></i> Chỉ tiêu trường ${getSchoolName(schoolId)}</h6>
            <div class="d-flex justify-content-between mt-3">
                <div class="text-center">
                    <div class="stat-number" style="font-size: 20px; color: #3498db;">${quota.daXep || 0}</div>
                    <div class="stat-label">Đã xếp</div>
                </div>
                <div class="text-center">
                    <div class="stat-number" style="font-size: 20px; color: #2ecc71;">${quota.chiTieu || '∞'}</div>
                    <div class="stat-label">Chỉ tiêu</div>
                </div>
                <div class="text-center">
                    <div class="stat-number" style="font-size: 20px; color: #e74c3c;">${quota.conLai || 'N/A'}</div>
                    <div class="stat-label">Còn lại</div>
                </div>
            </div>
            <div class="progress quota-progress mt-3">
                <div class="progress-bar ${progressClass}" style="width: ${Math.min(percent, 100)}%"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-muted">${percent}% chỉ tiêu</small>
                <small class="text-muted">${Math.max(0, quota.conLai)} chỗ trống</small>
            </div>`;
        
        if (quota.conLai <= 0) {
            html += `<div class="alert alert-danger mt-3">
                <i class="fas fa-exclamation-triangle"></i> Trường đã đủ chỉ tiêu! Vui lòng chọn trường khác hoặc tạo thêm phòng.
            </div>`;
        }
        
        html += '</div>';
        
        $('#manualArrangementInfo').append(html);
    }
    
    // Load phòng theo trường
    function loadRoomsBySchool(schoolId) {
        updateFlowStep(4);
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getRoomsBySchool',
            method: 'POST',
            data: { 
                exam: currentExam,
                schoolId: schoolId 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayAvailableRooms(response.data, schoolId);
                } else {
                    $('#availableRooms').html(`
                        <div class="alert alert-danger m-3">
                            <i class="fas fa-times-circle"></i> ${response.message}
                        </div>
                    `);
                    $('#saveManualBtn').prop('disabled', true);
                }
            },
            error: function() {
                $('#availableRooms').html('<div class="alert alert-danger m-3">Lỗi khi tải phòng thi</div>');
            }
        });
    }
    
    function displayAvailableRooms(rooms, schoolId = null) {
        if (rooms.length === 0) {
            $('#availableRooms').html('<div class="alert alert-warning m-3">Không có phòng thi trống</div>');
            $('#saveManualBtn').prop('disabled', true);
            return;
        }
        
        let html = '';
        
        if (schoolId) {
            html += `<div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                Phòng thi trống của trường <strong>${getSchoolName(schoolId)}</strong>
                <span class="badge bg-primary ms-2">${rooms.length} phòng</span>
            </div>`;
        }
        
        rooms.forEach((room, index) => {
            const availableSeats = room.soChoTrong || (room.soLuongToiDa - room.soLuongHienTai);
            const isPriority = schoolId && room.maTruong == schoolId;
            const priorityClass = isPriority ? 'priority' : '';
            const priorityBadge = isPriority ? '<span class="priority-badge"><i class="fas fa-star me-1"></i>Ưu tiên</span>' : '';
            const occupancyRate = Math.round((room.soLuongHienTai / room.soLuongToiDa) * 100);
            const occupancyColor = occupancyRate >= 80 ? 'danger' : occupancyRate >= 50 ? 'warning' : 'success';
            
            html += `<div class="room-item ${priorityClass}" data-id="${room.maPhongTS}" data-name="${room.tenPhongTS}">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="flex: 1;">
                        <div class="d-flex align-items-center mb-2">
                            <div class="room-code">${room.maPhongTS}</div>
                            ${priorityBadge}
                            <span class="badge bg-${occupancyColor} ms-2">${occupancyRate}%</span>
                        </div>
                        <div class="room-name">${room.tenPhongTS}</div>
                        <div class="room-info mt-2">
                            <i class="fas fa-map-marker-alt me-1"></i>${room.diaDiem || 'Chưa có địa điểm'}
                        </div>
                        <div class="room-info small mt-1">
                            <i class="fas fa-school me-1"></i>
                            <strong>Trường:</strong> ${room.tenTruong || 'Dùng chung'}
                            ${room.maTruong ? `(${room.maTruong})` : ''}
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end" style="min-width: 120px;">
                        <div class="text-center mb-3">
                            <div class="room-capacity" style="font-size: 24px;">${availableSeats}</div>
                            <small class="text-muted">chỗ trống</small>
                        </div>
                        <div class="text-center">
                            <small class="text-muted">Sức chứa: ${room.soLuongToiDa}</small><br>
                            <small class="text-muted">Đã xếp: ${room.soLuongHienTai}</small>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        
        $('#availableRooms').html(html);
        
        // Clear previous selection
        selectedRoom = null;
        $('#saveManualBtn').prop('disabled', true);
        
        // Add click event for room items
        $('.room-item').click(function() {
            selectedRoom = $(this).data('id');
            const roomName = $(this).data('name');
            
            $('.room-item').removeClass('selected');
            $(this).addClass('selected');
            $('#saveManualBtn').prop('disabled', false);
            updateFlowStep(5);
            
            // Show selected room info
            $('.selected-room').remove();
            $('#manualArrangementInfo').append(`
                <div class="alert alert-primary mt-3 selected-room">
                    <i class="fas fa-check-circle"></i> 
                    <strong>Phòng thi đã chọn:</strong> ${roomName}<br>
                    <small><i class="fas fa-hashtag me-1"></i>Mã phòng: ${selectedRoom}</small>
                </div>
            `);
        });
    }
    
    // Save manual arrangement
    $('#saveManualBtn').click(function() {
        if (!selectedStudent || !selectedRoom) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng chọn thí sinh và phòng thi',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận xếp phòng',
            text: 'Bạn có chắc chắn muốn xếp phòng cho thí sinh này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            background: '#f8f9fa'
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    studentId: selectedStudent,
                    roomId: selectedRoom
                };
                
                if (selectedSchool) {
                    data.schoolId = selectedSchool;
                }
                
                $.ajax({
                    url: 'index.php?controller=examArrangement&action=manualArrange',
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: `Đã xếp phòng thành công! Số báo danh: ${response.soBaoDanh}`,
                                confirmButtonColor: '#2ecc71'
                            });
                            
                            // Refresh data
                            loadStatistics();
                            loadUnarrangedStudents();
                            loadArrangedList();
                            loadRoomManagementList();
                            
                            // Reset selection
                            resetManualFlow();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Lỗi kết nối server khi lưu xếp phòng',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });
    });
    
    // Room management
    $('#createRoomBtn').click(function() {
        if (!currentExam) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng chọn kỳ thi trước',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        const roomName = $('#roomName').val();
        const roomLocation = $('#roomLocation').val();
        const roomCapacity = $('#roomCapacity').val();
        const roomSchool = $('#roomSchool').val() || null;
        
        if (!roomName || !roomCapacity) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng nhập tên phòng và sức chứa',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=createExamRoom',
            method: 'POST',
            data: { 
                examId: currentExam,
                roomName: roomName,
                location: roomLocation,
                capacity: roomCapacity,
                maTruong: roomSchool
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: response.message,
                        confirmButtonColor: '#2ecc71'
                    });
                    
                    // Clear form
                    $('#roomName').val('');
                    $('#roomLocation').val('');
                    $('#roomCapacity').val('30');
                    $('#roomSchool').val('');
                    
                    // Reload room list
                    loadRoomManagementList();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: response.message,
                        confirmButtonColor: '#e74c3c'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Lỗi kết nối server khi tạo phòng',
                    confirmButtonColor: '#e74c3c'
                });
            }
        });
    });
    
    function loadRoomManagementList() {
        if (!currentExam) return;
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getAvailableRooms',
            method: 'POST',
            data: { exam: currentExam },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayRoomManagementList(response.data);
                }
            },
            error: function() {
                $('#roomManagementList').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Lỗi khi tải danh sách phòng</div>');
            }
        });
    }
    
    function displayRoomManagementList(rooms) {
        if (rooms.length === 0) {
            $('#roomManagementList').html('<div class="alert alert-info m-3">Chưa có phòng thi nào. Hãy tạo phòng mới.</div>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="data-table">';
        html += '<thead><tr><th>Mã phòng</th><th>Tên phòng</th><th>Địa điểm</th><th>Trường</th><th>Sức chứa</th><th>Đã xếp</th><th>Tỷ lệ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>';
        
        rooms.forEach(room => {
            const availableSeats = room.soChoTrong || (room.soLuongToiDa - room.soLuongHienTai);
            const occupiedSeats = room.soLuongHienTai;
            const occupancyRate = Math.round((occupiedSeats / room.soLuongToiDa) * 100);
            const statusColor = occupancyRate >= 90 ? 'bg-danger' : 
                              occupancyRate >= 50 ? 'bg-warning' : 'bg-success';
            const statusText = occupancyRate >= 90 ? 'Gần đầy' : 
                              occupancyRate >= 50 ? 'Trung bình' : 'Trống';
            const canDelete = occupiedSeats === 0;
            
            html += `<tr>
                <td><span class="badge bg-secondary">${room.maPhongTS}</span></td>
                <td><strong>${room.tenPhongTS}</strong></td>
                <td>${room.diaDiem || 'Chưa có'}</td>
                <td>${room.tenTruong || 'Dùng chung'}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div style="width: 60px;">${room.soLuongToiDa}</div>
                        <div class="progress ms-2" style="height: 6px; width: 60px;">
                            <div class="progress-bar ${statusColor}" style="width: ${occupancyRate}%"></div>
                        </div>
                    </div>
                </td>
                <td>${occupiedSeats}</td>
                <td>${occupancyRate}%</td>
                <td><span class="badge ${statusColor}">${statusText}</span></td>
                <td>
                    <button class="btn btn-sm btn-danger delete-room-btn" 
                            data-id="${room.maPhongTS}" 
                            ${!canDelete ? 'disabled' : ''}
                            title="${!canDelete ? 'Không thể xóa phòng đã có thí sinh' : 'Xóa phòng'}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        });
        
        html += '</tbody></table></div>';
        $('#roomManagementList').html(html);
        
        // Add delete event
        $('.delete-room-btn').click(function() {
            const roomId = $(this).data('id');
            if ($(this).prop('disabled')) return;
            
            deleteExamRoom(roomId);
        });
    }
    
    function deleteExamRoom(roomId) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc chắn muốn xóa phòng thi này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            background: '#f8f9fa'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php?controller=examArrangement&action=deleteExamRoom',
                    method: 'POST',
                    data: { roomId: roomId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                confirmButtonColor: '#2ecc71'
                            });
                            
                            // Reload room list
                            loadRoomManagementList();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Lỗi kết nối server khi xóa phòng',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });
    }
    
    // Load arranged list
    function loadArrangedList() {
        if (!currentExam) return;
        
        const sortBy = $('#sortSelect').val();
        
        $.ajax({
            url: 'index.php?controller=examArrangement&action=getArrangedList',
            method: 'POST',
            data: { exam: currentExam, sort: sortBy },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayArrangedList(response.data);
                }
            },
            error: function() {
                $('#arrangedList').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Lỗi khi tải danh sách đã xếp</div>');
            }
        });
    }
    
    // Trong phần displayArrangedList
function displayArrangedList(list) {
    if (list.length === 0) {
        $('#arrangedList').html('<div class="alert alert-info m-3"><i class="fas fa-info-circle"></i> Chưa có thí sinh nào được xếp phòng</div>');
        return;
    }
    
    let html = '<table class="data-table">';
    html += '<thead><tr><th>STT</th><th>Số báo danh</th><th>Thí sinh</th><th>Phòng thi</th><th>Địa điểm</th><th>Nguyện vọng 1</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>';
    
    list.forEach((item, index) => {
        const birthDate = item.ngaySinhHS ? new Date(item.ngaySinhHS).toLocaleDateString('vi-VN') : '';
        html += `<tr>
            <td>${index + 1}</td>
            <td><span class="badge bg-primary">${item.soBaoDanh || ''}</span></td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle me-2" style="color: #667eea; font-size: 20px;"></i>
                    <div>
                        <strong>${item.hoTenHS || ''}</strong><br>
                        <small class="text-muted">Mã HS: ${item.maHS || ''}</small><br>
                        <small class="text-muted">${birthDate} | ${item.gioiTinhHS || ''}</small>
                    </div>
                </div>
            </td>
            <td>
                <strong>${item.tenPhongTS || 'Chưa xếp'}</strong><br>
                <small class="text-muted">Mã: ${item.maPhongTS || ''}</small>
            </td>
            <td>${item.diaDiem || ''}</td>
            <td>
                ${item.tenTruong1 || 'Không có'}
                ${item.maTruong ? `<br><small class="text-muted">(${item.maTruong})</small>` : ''}
            </td>
            <td><span class="badge bg-success">Đã xếp phòng</span></td>
            <td>
                <button class="btn btn-sm btn-danger cancel-arrangement-btn" data-id="${item.maHS}">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </td>
        </tr>`;
    });
    
    html += '</tbody></table>';
    html += `<div class="mt-3 text-end">
        <strong>Tổng: ${list.length} thí sinh</strong>
        <span class="badge bg-primary ms-2">${list.length} bản ghi</span>
    </div>`;
    $('#arrangedList').html(html);
    
    // Add cancel arrangement event
    $('.cancel-arrangement-btn').click(function() {
        const studentId = $(this).data('id');
        cancelArrangement(studentId);
    });
}
    
    function cancelArrangement(studentId) {
        Swal.fire({
            title: 'Xác nhận hủy xếp phòng',
            text: 'Bạn có chắc chắn muốn hủy xếp phòng cho thí sinh này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Hủy xếp phòng',
            cancelButtonText: 'Đóng',
            background: '#f8f9fa'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php?controller=examArrangement&action=cancelArrangement',
                    method: 'POST',
                    data: { studentId: studentId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                confirmButtonColor: '#2ecc71'
                            });
                            
                            // Refresh data
                            loadStatistics();
                            loadUnarrangedStudents();
                            loadArrangedList();
                            loadRoomManagementList();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Lỗi kết nối server khi hủy xếp phòng',
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                });
            }
        });
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
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin',
                text: 'Vui lòng chọn kỳ thi trước',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        const sortBy = $('#sortSelect').val();
        window.open(`index.php?controller=examArrangement&action=exportList&exam=${currentExam}&sort=${sortBy}`);
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
// ... (phần JavaScript hiện tại) ...

// Thêm hàm loadRoomManagementList
function loadRoomManagementList() {
    if (!currentExam) return;
    
    $.ajax({
        url: 'index.php?controller=examArrangement&action=getAvailableRooms',
        method: 'POST',
        data: { exam: currentExam },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayRoomManagementList(response.data);
            }
        },
        error: function() {
            $('#roomManagementList').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Lỗi khi tải danh sách phòng</div>');
        }
    });
}

// Thêm hàm displayRoomManagementList
function displayRoomManagementList(rooms) {
    if (!rooms || rooms.length === 0) {
        $('#roomManagementList').html('<div class="alert alert-info m-3">Chưa có phòng thi nào. Hãy tạo phòng mới.</div>');
        return;
    }
    
    let html = '<div class="table-responsive"><table class="data-table">';
    html += '<thead><tr><th>Mã phòng</th><th>Tên phòng</th><th>Địa điểm</th><th>Trường</th><th>Sức chứa</th><th>Đã xếp</th><th>Tỷ lệ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>';
    
    rooms.forEach(room => {
        const availableSeats = room.soChoTrong || (room.soLuongToiDa - room.soLuongHienTai);
        const occupiedSeats = room.soLuongHienTai;
        const occupancyRate = Math.round((occupiedSeats / room.soLuongToiDa) * 100);
        const statusColor = occupancyRate >= 90 ? 'bg-danger' : 
                          occupancyRate >= 50 ? 'bg-warning' : 'bg-success';
        const statusText = occupancyRate >= 90 ? 'Gần đầy' : 
                          occupancyRate >= 50 ? 'Trung bình' : 'Trống';
        const canDelete = occupiedSeats === 0;
        
        html += `<tr>
            <td><span class="badge bg-secondary">${room.maPhongTS}</span></td>
            <td><strong>${room.tenPhongTS}</strong></td>
            <td>${room.diaDiem || 'Chưa có'}</td>
            <td>${room.tenTruong || 'Dùng chung'}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div style="width: 60px;">${room.soLuongToiDa}</div>
                    <div class="progress ms-2" style="height: 6px; width: 60px;">
                        <div class="progress-bar ${statusColor}" style="width: ${occupancyRate}%"></div>
                    </div>
                </div>
            </td>
            <td>${occupiedSeats}</td>
            <td>${occupancyRate}%</td>
            <td><span class="badge ${statusColor}">${statusText}</span></td>
            <td>
                <button class="btn btn-sm btn-danger delete-room-btn" 
                        data-id="${room.maPhongTS}" 
                        ${!canDelete ? 'disabled' : ''}
                        title="${!canDelete ? 'Không thể xóa phòng đã có thí sinh' : 'Xóa phòng'}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });
    
    html += '</tbody></table></div>';
    $('#roomManagementList').html(html);
    
    // Thêm sự kiện xóa phòng
    $('.delete-room-btn').click(function() {
        const roomId = $(this).data('id');
        if ($(this).prop('disabled')) return;
        
        deleteExamRoom(roomId);
    });
}

// Thêm hàm deleteExamRoom
function deleteExamRoom(roomId) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa phòng thi này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        background: '#f8f9fa'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'index.php?controller=examArrangement&action=deleteExamRoom',
                method: 'POST',
                data: { roomId: roomId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            confirmButtonColor: '#2ecc71'
                        });
                        
                        // Reload room list
                        loadRoomManagementList();
                        loadAvailableRooms();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message,
                            confirmButtonColor: '#e74c3c'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Lỗi kết nối server khi xóa phòng',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            });
        }
    });
}
</script>

<?php require_once 'views/layout/footer.php'; ?>