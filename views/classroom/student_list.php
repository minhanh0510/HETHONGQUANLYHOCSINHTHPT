<?php
// views/classroom/student_list.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<style>
/* ================================================= */
/* RESET & BASE STYLES */
/* ================================================= */
html, body {
    height: auto;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    overflow-y: auto;
    font-family: 'Arial', sans-serif; /* Cải thiện font cơ bản */
    line-height: 1.6;
}

.content-wrapper {
    margin-left: 250px;
    min-height: 100vh;
    background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%); /* Gradient nền nhẹ */
    padding: 30px 30px 50px 30px;
    width: calc(100vw - 250px);
    box-sizing: border-box;
    border-radius: 0 0 15px 15px; /* Bo góc nhẹ cho wrapper */
}

/* ================================================= */
/* BREADCRUMB */
/* ================================================= */
.breadcrumb {
    background: rgba(255, 255, 255, 0.8);
    padding: 15px 20px;
    margin: 0 0 25px 0;
    list-style: none;
    display: flex;
    font-size: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.breadcrumb-item {
    display: inline-block;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    padding: 0 10px;
    color: #6c757d;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

.breadcrumb-item a {
    color: #28a745; /* Màu xanh lá cho links */
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-item a:hover {
    color: #218838;
    text-decoration: underline;
}

/* ================================================= */
/* CLASS INFO CARD */
/* ================================================= */
.class-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 35px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    width: 100%;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}

.class-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    pointer-events: none;
}

.class-info-card h2 {
    margin: 0 0 25px 0;
    font-size: 34px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    z-index: 1;
}

.class-info-card h2 i {
    font-size: 38px;
}

.class-info-details {
    display: flex;
    gap: 60px;
    font-size: 16px;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}

.class-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform 0.3s;
}

.class-info-item:hover {
    transform: translateY(-3px);
}

.class-info-item i {
    font-size: 22px;
    opacity: 0.9;
}

/* ================================================= */
/* SEARCH BOX */
/* ================================================= */
.search-box {
    background: #fff;
    padding: 30px 35px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #e9ecef;
}

.search-box h3 {
    margin: 0 0 25px 0;
    font-size: 20px;
    color: #333;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-form {
    display: flex;
    gap: 20px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.search-form .form-group {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}

.search-form label {
    font-size: 15px;
    color: #555;
    white-space: nowrap;
    font-weight: 500;
    display: block;
}

.search-form input[type="text"],
.search-form select {
    padding: 12px 18px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    min-width: 240px;
    height: 48px;
    transition: all 0.3s;
    box-sizing: border-box;
    background: #f8f9fa;
}

.search-form input[type="text"]:focus,
.search-form select:focus {
    outline: none;
    border-color: #28a745; /* Màu xanh lá cho focus */
    box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.15);
    background: #fff;
}

.search-form button,
.search-form a {
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
    text-decoration: none;
    font-weight: 600;
    height: 48px;
    box-sizing: border-box;
}

.btn-search {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-search:hover {
    background: linear-gradient(135deg, #218838 0%, #17a2b8 100%);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-reset {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.btn-reset:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

/* ================================================= */
/* TABLE CONTAINER */
/* ================================================= */
.table-container {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
}

.table-wrapper {
    overflow-x: auto;
    overflow-y: visible;
}

.table-wrapper::-webkit-scrollbar {
    height: 10px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a5acd 100%);
}

/* ================================================= */
/* TABLE STYLING */
/* ================================================= */
.student-list-table {
    width: 100%;
    min-width: 1200px;
    border-collapse: collapse;
    margin: 0;
    font-size: 15px;
}

.student-list-table thead th {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 16px 14px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.student-list-table tbody td {
    padding: 14px 12px;
    border-bottom: 1px solid #e9ecef;
    text-align: center;
    vertical-align: middle;
    font-size: 15px;
    color: #495057;
    transition: background 0.3s;
}

.student-list-table tbody td.text-left {
    text-align: left;
    padding-left: 18px;
}

.student-list-table tbody tr {
    transition: all 0.3s;
}

.student-list-table tbody tr:hover {
    background: linear-gradient(90deg, #f8f9fc 0%, #e3f2fd 100%);
    transform: scale(1.005);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.student-list-table tbody tr:nth-child(even) {
    background: #f9f9f9; /* Stripes nhẹ */
}

.student-list-table tbody tr:last-child td {
    border-bottom: none;
}

.student-list-table .checkbox-col {
    width: 60px;
    text-align: center;
}

.student-list-table input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #28a745;
    border-radius: 4px;
}

/* Column widths */
.col-stt { 
    width: 70px;
    font-weight: 500;
}

.col-mahs { 
    width: 130px;
    font-family: 'Courier New', monospace;
    color: #d63384;
    font-weight: 500;
}

.col-hoten { 
    min-width: 180px;
    max-width: 250px;
    font-weight: 500;
    color: #212529;
}

.col-ngaysinh { 
    width: 110px;
}

.col-lop { 
    width: 70px;
    font-weight: 500;
}

.col-gioitinh { 
    width: 90px;
}

.col-dantoc { 
    width: 90px;
}

.col-diachi { 
    min-width: 300px;
    max-width: 500px;
    word-wrap: break-word;
    white-space: normal;
}

.sort-icon,
.filter-icon {
    color: #adb5bd;
    font-size: 13px;
    margin-left: 8px;
    cursor: pointer;
    transition: color 0.3s, transform 0.3s;
}

.sort-icon:hover,
.filter-icon:hover {
    color: #28a745;
    transform: scale(1.2);
}

/* ================================================= */
/* TABLE FOOTER */
/* ================================================= */
.table-footer {
    padding: 18px 30px;
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 2px solid #dee2e6;
    font-size: 15px;
    font-weight: 600;
    color: #495057;
}

/* ================================================= */
/* PAGINATION */
/* ================================================= */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 30px;
    background: #fff;
    border-top: 1px solid #dee2e6;
}

.pagination-info {
    font-size: 15px;
    color: #6c757d;
}

.pagination-info strong {
    color: #495057;
    font-weight: 600;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pagination-controls input {
    width: 75px;
    padding: 8px 12px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    text-align: center;
    font-size: 15px;
    font-weight: 500;
    transition: border-color 0.3s;
}

.pagination-controls input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
}

.pagination-controls button {
    padding: 8px 14px;
    border: 2px solid #dee2e6;
    background: white;
    cursor: pointer;
    border-radius: 8px;
    color: #6c757d;
    transition: all 0.3s;
    font-size: 18px;
}

.pagination-controls button:hover:not(:disabled) {
    background: #28a745;
    border-color: #28a745;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.pagination-controls button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* ================================================= */
/* EMPTY STATE */
/* ================================================= */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #adb5bd;
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.empty-state i {
    font-size: 80px;
    margin-bottom: 25px;
    display: block;
    opacity: 0.6;
    color: #28a745;
}

.empty-state p {
    font-size: 18px;
    margin: 0;
    color: #6c757d;
}

/* ================================================= */
/* RESPONSIVE */
/* ================================================= */
@media (max-width: 768px) {
    .content-wrapper {
        margin-left: 0;
        width: 100%;
        padding: 20px;
    }
    
    .class-info-card {
        padding: 25px;
    }
    
    .class-info-card h2 {
        font-size: 28px;
    }
    
    .class-info-details {
        flex-direction: column;
        gap: 20px;
    }
    
    .search-box {
        padding: 25px;
    }
    
    .search-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-form .form-group {
        width: 100%;
    }
    
    .search-form input[type="text"],
    .search-form select,
    .search-form button,
    .search-form a {
        width: 100%;
        min-width: auto;
    }
    
    /* Ẩn một số cột trên mobile để tiết kiệm không gian */
    .col-dantoc,
    .col-diachi {
        display: none;
    }
    
    .pagination-container {
        flex-direction: column;
        gap: 20px;
        align-items: stretch;
    }
    
    .pagination-controls {
        justify-content: center;
    }
}
</style>

<div class="content-wrapper">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?controller=classroom&action=index">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?? '' ?>">Quản lý lớp chủ nhiệm</a></li>
        <li class="breadcrumb-item active">Danh sách học sinh lớp <?= htmlspecialchars($classroom['tenLop'] ?? 'N/A') ?></li>
    </ol>

    <!-- Class Info Card -->
    <div class="class-info-card">
        <h2>
            <i class="fas fa-school"></i>
            <?= htmlspecialchars($classroom['tenLop'] ?? '10A1') ?>
        </h2>
        <div class="class-info-details">
            <div class="class-info-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>GVCN: <strong><?= htmlspecialchars($classroom['tenGVCN'] ?? $user['hoVaTen'] ?? 'Lê Văn Toàn') ?></strong></span>
            </div>
            <div class="class-info-item">
                <i class="fas fa-users"></i>
                               <span>Sĩ số: <strong><?= count($studentList) ?> học sinh</strong></span>
            </div>
            <div class="class-info-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Năm học: <strong>2024-2025</strong></span>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <h3><i class="fas fa-search"></i> Tìm kiếm học sinh</h3>
        <form method="GET" class="search-form">
            <input type="hidden" name="controller" value="classroom">
            <input type="hidden" name="action" value="studentList">
            <?php if(isset($_GET['maLop']) && !empty($_GET['maLop'])): ?>
                <input type="hidden" name="maLop" value="<?= htmlspecialchars($_GET['maLop']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="keyword">Từ khóa:</label>
                <input type="text" 
                        name="keyword" 
                        id="keyword" 
                        placeholder="Nhập tên, mã học sinh..." 
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="gioiTinh">Giới tính:</label>
                <select name="gioiTinh" id="gioiTinh">
                    <option value="">-- Tất cả --</option>
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
        <div class="table-wrapper">
            <table class="student-list-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="col-stt">
                            STT
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="col-mahs">
                            Mã học sinh
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="col-hoten">
                            Họ tên
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="col-ngaysinh">
                            Ngày sinh
                            <i class="far fa-calendar-alt filter-icon"></i>
                        </th>
                        <th class="col-lop">
                            Lớp
                            <i class="fas fa-caret-down sort-icon"></i>
                        </th>
                        <th class="col-gioitinh">
                            Giới tính
                            <i class="fas fa-caret-down sort-icon"></i>
                        </th>
                        <th class="col-dantoc">
                            Dân tộc
                            <i class="fas fa-caret-down sort-icon"></i>
                        </th>
                        <th class="col-diachi">
                            Chỗ ở hiện tại
                            <i class="fas fa-filter filter-icon"></i>
                        </th>
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
                                <td class="checkbox-col">
                                    <input type="checkbox" class="student-checkbox" value="<?= $student['maHS'] ?>">
                                </td>
                                <td><?= $index + 1 ?></td>
                                <td class="col-mahs"><?= htmlspecialchars($student['maHS']) ?></td>
                                <td class="text-left col-hoten"><?= htmlspecialchars($student['hoVaTen']) ?></td>
                                <td><?= date('d/m/Y', strtotime($student['ngaySinh'])) ?></td>
                                <td><?= htmlspecialchars($classroom['tenLop'] ?? $student['tenLop'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['gioiTinh']) ?></td>
                                <td>Kinh</td>
                                <td class="text-left"><?= htmlspecialchars($student['diaChi'] ?? 'Chưa cập nhật') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="table-footer">
            Tổng số <?= count($studentList) ?> học sinh
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <div class="pagination-info">
                Trang <strong>1</strong> trên tổng số <strong>1</strong> trang
            </div>
            <div class="pagination-controls">
                <button disabled title="Trang đầu">
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button disabled title="Trang trước">
                    <i class="fas fa-angle-left"></i>
                </button>
                <input type="number" value="1" min="1" max="1" readonly>
                <button disabled title="Trang sau">
                    <i class="fas fa-angle-right"></i>
                </button>
                <button disabled title="Trang cuối">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Select all checkbox functionality
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Individual checkbox update select all
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
