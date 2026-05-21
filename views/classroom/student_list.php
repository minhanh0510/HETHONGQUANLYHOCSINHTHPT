<?php
// views/classroom/student_list.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    overflow: hidden;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 0;
    font-size: 14px;
}

/* Main Content - Full Height with Scroll */
.main-content {
    padding: 15px;
    height: calc(100vh - 60px);
    margin-left: 10px;
    width: calc(100% - 300px);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-shrink: 0;
    gap: 15px;
}

.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
    white-space: nowrap;
    height: 36px;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

/* Info Card */
.info-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,.08);
    padding: 15px;
    margin-bottom: 15px;
    flex-shrink: 0;
}

.info-card h3 {
    margin-bottom: 12px;
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 8px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.class-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.info-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #3498db;
}

.info-item strong {
    display: block;
    color: #555;
    font-size: 12px;
    margin-bottom: 5px;
}

.info-item span {
    display: block;
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
}

/* Search Section */
.search-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,.08);
    padding: 15px;
    margin-bottom: 15px;
    flex-shrink: 0;
}

.search-card h3 {
    margin-bottom: 12px;
    color: #2c3e50;
    border-bottom: 2px solid #27ae60;
    padding-bottom: 8px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-form {
    display: grid;
    grid-template-columns: 1.5fr 1fr auto auto;
    gap: 10px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-size: 12px;
    color: #555;
    font-weight: 500;
}

.form-group input,
.form-group select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 13px;
    transition: all 0.3s;
    height: 36px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
}

.btn-search {
    background: #27ae60;
    color: white;
    padding: 0 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    height: 36px;
}

.btn-search:hover {
    background: #229954;
}

.btn-reset {
    background: #95a5a6;
    color: white;
    padding: 0 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    height: 36px;
}

.btn-reset:hover {
    background: #7f8c8d;
}

/* Table Container */
.table-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: visible; /* Không có scroll cục bộ */
}

.table-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,.08);
    overflow: visible; /* Không có overflow */
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Table Wrapper - KHÔNG CÓ SCROLL */
.table-wrapper {
    flex: 1;
    overflow: visible; /* Không có scroll cục bộ */
    min-height: auto;
}

.table-responsive {
    width: 100%;
    overflow: visible;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    table-layout: auto; /* Để bảng tự động điều chỉnh kích thước */
}

.data-table thead {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
}

.data-table th {
    padding: 10px 8px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
    font-size: 12px;
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 10;
}

.data-table td {
    padding: 8px 8px;
    border-bottom: 1px solid #e9ecef;
    color: #2c3e50;
    vertical-align: middle;
    white-space: nowrap;
}

.data-table tbody tr {
    transition: all 0.2s;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Không cần đặt width cố định cho cột */
/* Để bảng tự động co giãn */

/* Text alignment */
.text-center {
    text-align: center !important;
}

.text-left {
    text-align: left !important;
}

/* Checkbox */
input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
    accent-color: #3498db;
}

/* Student code */
.student-code {
    color: #3498db;
    font-weight: 600;
    font-family: 'Courier New', monospace;
    display: inline-block;
    font-size: 12px;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 40px 15px;
    color: #95a5a6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

.empty-state i {
    font-size: 40px;
    margin-bottom: 15px;
    opacity: 0.4;
}

.empty-state p {
    font-size: 14px;
    color: #7f8c8d;
    max-width: 250px;
}

/* Table footer */
.table-footer {
    padding: 10px 15px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    font-size: 13px;
    color: #495057;
    flex-shrink: 0;
}

.table-footer strong {
    color: #2c3e50;
    font-weight: 600;
}

/* Alert */
.alert {
    padding: 10px 15px;
    border-radius: 6px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    font-size: 13px;
}

.alert-warning {
    background: #fff3cd;
    border-left: 3px solid #ffc107;
    color: #856404;
}

/* Custom Scrollbar cho main-content */
.main-content::-webkit-scrollbar {
    width: 8px;
}

.main-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.main-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.main-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive */
@media (max-width: 1200px) {
    .main-content {
        width: calc(100% - 260px);
        padding: 12px;
    }
}

@media (max-width: 1024px) {
    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 10px;
    }
    
    .search-form {
        grid-template-columns: 1fr 1fr;
    }
    
    .btn-reset {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .search-form {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .btn-reset {
        grid-column: span 1;
    }
    
    .class-info-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .info-card,
    .search-card {
        padding: 12px;
    }
    
    .data-table {
        font-size: 12px;
    }
    
    .data-table th,
    .data-table td {
        padding: 6px 5px;
    }
    
    /* Ẩn cột không quan trọng */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1),
    .data-table th:nth-child(8),
    .data-table td:nth-child(8) {
        display: none;
    }
    
    .empty-state {
        padding: 30px 10px;
        min-height: 150px;
    }
    
    .empty-state i {
        font-size: 32px;
    }
}

@media (max-width: 576px) {
    .main-content {
        padding: 8px;
    }
    
    .page-title {
        font-size: 16px;
    }
    
    .btn {
        padding: 6px 12px;
        font-size: 12px;
        height: 32px;
    }
    
    .form-group input,
    .form-group select {
        height: 32px;
        font-size: 12px;
    }
    
    .btn-search,
    .btn-reset {
        height: 32px;
        font-size: 12px;
    }
    
    .empty-state {
        padding: 20px 8px;
        min-height: 120px;
    }
    
    .empty-state i {
        font-size: 28px;
    }
    
    .empty-state p {
        font-size: 12px;
    }
}
</style>

<div class="main-content">
    <!-- Page Header -->
    <div class="content-header">
        <h1 class="page-title">Danh sách học sinh lớp <?= htmlspecialchars($classroom['tenLop'] ?? '10A1') ?></h1>
        <a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?? '' ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Class Info -->
    <div class="info-card">
        <h3><i class="fas fa-info-circle"></i> Thông tin lớp học</h3>
        <div class="class-info-grid">
            <div class="info-item">
                <strong>Giáo viên chủ nhiệm:</strong>
                <span><?= htmlspecialchars($classroom['tenGVCN'] ?? $user['hoVaTen'] ?? 'Lê Văn Toàn') ?></span>
            </div>
            <div class="info-item">
                <strong>Sĩ số:</strong>
                <span><?= count($studentList) ?> học sinh</span>
            </div>
            <div class="info-item">
                <strong>Năm học:</strong>
                <span>2024-2025</span>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-card">
        <h3><i class="fas fa-search"></i> Tìm kiếm học sinh</h3>
        <form method="GET" class="search-form">
            <input type="hidden" name="controller" value="classroom">
            <input type="hidden" name="action" value="studentList">
            <?php if(isset($_GET['maLop']) && !empty($_GET['maLop'])): ?>
                <input type="hidden" name="maLop" value="<?= htmlspecialchars($_GET['maLop']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="keyword">Tìm kiếm</label>
                <input type="text" 
                       name="keyword" 
                       id="keyword" 
                       placeholder="Nhập tên hoặc mã học sinh..." 
                       value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="gioiTinh">Giới tính</label>
                <select name="gioiTinh" id="gioiTinh">
                    <option value="">Tất cả</option>
                    <option value="Nam" <?= (isset($_GET['gioiTinh']) && $_GET['gioiTinh'] === 'Nam') ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= (isset($_GET['gioiTinh']) && $_GET['gioiTinh'] === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>
            
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            
            <a href="index.php?controller=classroom&action=studentList<?= isset($_GET['maLop']) ? '&maLop='.htmlspecialchars($_GET['maLop']) : '' ?>" 
               class="btn-reset">
                <i class="fas fa-redo"></i> Đặt lại
            </a>
        </form>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-card">
            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>STT</th>
                                <th>Mã học sinh</th>
                                <th>Họ và tên</th>
                                <th>Ngày sinh</th>
                                <th>Lớp</th>
                                <th>Giới tính</th>
                                <th>Dân tộc</th>
                                <th>Địa chỉ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($studentList)): ?>
                                <tr>
                                    <td colspan="9" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Không tìm thấy học sinh nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($studentList as $index => $student): ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="student-checkbox" value="<?= htmlspecialchars($student['maHS']) ?>">
                                        </td>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td class="text-center">
                                            <span class="student-code"><?= htmlspecialchars($student['maHS']) ?></span>
                                        </td>
                                        <td class="text-left">
                                            <strong><?= htmlspecialchars($student['hoVaTen']) ?></strong>
                                        </td>
                                        <td class="text-center"><?= date('d/m/Y', strtotime($student['ngaySinh'])) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($classroom['tenLop'] ?? $student['tenLop'] ?? 'N/A') ?></td>
                                        <td class="text-center"><?= htmlspecialchars($student['gioiTinh']) ?></td>
                                        <td class="text-center">Kinh</td>
                                        <td class="text-left">
                                            <?= htmlspecialchars($student['diaChi'] ?? 'Chưa cập nhật') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                Tổng số: <strong><?= count($studentList) ?> học sinh</strong>
            </div>
        </div>
    </div>
</div>

<script>
// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Update select all when individual checkboxes change
document.querySelectorAll('.student-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.student-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
        }
    });
});
</script>

<?php include "views/layout/footer.php"; ?>