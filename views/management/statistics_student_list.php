<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-users"></i> Danh sách học sinh
        </h1>
        <div class="breadcrumb">
            <a href="index.php?controller=statistics&action=dashboard">Thống kê</a> / Danh sách học sinh
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="filter-section">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-filter"></i> Lọc danh sách
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="filter-form" id="filterForm">
                    <input type="hidden" name="controller" value="statistics">
                    <input type="hidden" name="action" value="studentList">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filter_type">Phạm vi:</label>
                                <select name="filter_type" id="filter_type" class="form-control">
                                    <option value="all" <?php echo ($filterType ?? 'all') === 'all' ? 'selected' : ''; ?>>Toàn trường</option>
                                    <option value="khoi" <?php echo ($filterType ?? '') === 'khoi' ? 'selected' : ''; ?>>Theo khối</option>
                                    <option value="lop" <?php echo ($filterType ?? '') === 'lop' ? 'selected' : ''; ?>>Theo lớp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="filter_value_container" style="display: <?php echo ($filterType ?? 'all') === 'all' ? 'none' : 'block'; ?>">
                                <label for="filter_value">Giá trị:</label>
                                <select name="filter_value" id="filter_value" class="form-control">
                                    <?php if (($filterType ?? '') === 'khoi'): ?>
                                        <?php foreach ($filterOptions['khoi'] as $khoi): ?>
                                            <option value="<?php echo $khoi['value']; ?>" <?php echo ($filterValue ?? '') == $khoi['value'] ? 'selected' : ''; ?>>
                                                <?php echo $khoi['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php elseif (($filterType ?? '') === 'lop'): ?>
                                        <?php foreach ($filterOptions['lop'] as $lop): ?>
                                            <option value="<?php echo $lop['value']; ?>" <?php echo ($filterValue ?? '') == $lop['value'] ? 'selected' : ''; ?>>
                                                <?php echo $lop['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">-- Chọn --</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="padding-top: 28px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                                <a href="index.php?controller=statistics&action=studentList" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Xóa lọc
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Kết quả -->
    <div class="result-section">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Kết quả
                <div class="header-actions">
                    <span class="badge badge-info">Tổng: <?php echo count($students); ?> học sinh</span>
                    <a href="index.php?controller=statistics&action=export&report_type=student_list&filter_type=<?php echo $filterType; ?>&filter_value=<?php echo $filterValue; ?>" 
                       class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($students)): ?>
                    <?php 
                    // Sắp xếp mảng students theo mã HS từ bé đến lớn
                    // Vì maHS có dạng "HS001", "HS002",... nên cần tách phần số để so sánh
                    usort($students, function($a, $b) {
                        // Tách phần số từ mã HS (bỏ "HS" ở đầu)
                        $maHS_A = isset($a['maHS']) ? substr($a['maHS'], 2) : '0';
                        $maHS_B = isset($b['maHS']) ? substr($b['maHS'], 2) : '0';
                        
                        // Chuyển sang số nguyên để so sánh
                        $numA = intval($maHS_A);
                        $numB = intval($maHS_B);
                        
                        // So sánh số nguyên
                        return $numA <=> $numB;
                    });
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="studentTable">
                            <thead>
                                <tr>
                                    <th>Mã HS</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Lớp</th>
                                    <th>Khối</th>
                                    <th>Trạng thái học tập</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['maHS']); ?></td>
                                        <td><?php echo htmlspecialchars($student['hoVaTen']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $student['gioiTinh'] == 'Nam' ? 'badge-male' : 'badge-female'; ?>">
                                                <?php echo htmlspecialchars($student['gioiTinh']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($student['tenLop'] ?? 'Chưa phân lớp'); ?></td>
                                        <td><?php echo htmlspecialchars($student['tenKhoi'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($student['dangThaiHocTap']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có dữ liệu học sinh nào.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Biến toàn cục để theo dõi trạng thái
let isLoading = false;

// Hàm tải options
function loadFilterOptions(filterType, selectedValue = '') {
    if (isLoading) return;
    
    const select = document.getElementById('filter_value');
    const container = document.getElementById('filter_value_container');
    
    if (filterType === 'all') {
        container.style.display = 'none';
        select.innerHTML = '';
        return;
    }
    
    container.style.display = 'block';
    isLoading = true;
    
    // Hiển thị loading
    select.innerHTML = '<option value="">Đang tải dữ liệu...</option>';
    select.disabled = true;
    
    // Xác định loại dữ liệu cần tải
    const apiType = filterType === 'lop' ? 'class' : 'grade';
    const url = `index.php?controller=statistics&action=getFilterOptions&type=${apiType}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Xóa options cũ
            select.innerHTML = '<option value="">-- Chọn --</option>';
            
            if (Array.isArray(data) && data.length > 0) {
                // Sử dụng Set để loại bỏ trùng lặp dựa trên value
                const uniqueValues = new Set();
                
                data.forEach(item => {
                    const value = item.value || item.maKhoi || item.maLop || '';
                    const label = item.label || item.tenKhoi || item.tenLop || item.name || '';
                    
                    // Chỉ thêm nếu value hợp lệ và chưa tồn tại
                    if (value && !uniqueValues.has(value)) {
                        uniqueValues.add(value);
                        
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        
                        // Chọn giá trị đã chọn trước đó
                        if (value === selectedValue) {
                            option.selected = true;
                        }
                        
                        select.appendChild(option);
                    }
                });
                
                // Nếu không có options nào, hiển thị thông báo
                if (uniqueValues.size === 0) {
                    select.innerHTML = '<option value="">Không có dữ liệu</option>';
                }
            } else {
                select.innerHTML = '<option value="">Không có dữ liệu</option>';
            }
        })
        .catch(error => {
            console.error('Lỗi tải dữ liệu:', error);
            select.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        })
        .finally(() => {
            select.disabled = false;
            isLoading = false;
        });
}

// Hàm xử lý khi thay đổi loại filter
function handleFilterTypeChange() {
    const filterType = this.value;
    const select = document.getElementById('filter_value');
    
    // Reset giá trị filter khi thay đổi loại
    select.value = '';
    
    // Tải options mới
    loadFilterOptions(filterType);
}

// Hàm khởi tạo khi trang load
function initializePage() {
    const filterTypeSelect = document.getElementById('filter_type');
    const filterValueSelect = document.getElementById('filter_value');
    
    // Lấy giá trị từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const filterType = urlParams.get('filter_type');
    const filterValue = urlParams.get('filter_value');
    
    // Thiết lập giá trị cho dropdown nếu có
    if (filterType && filterTypeSelect) {
        filterTypeSelect.value = filterType;
    }
    
    // Nếu có filterType và không phải là "all", tải options
    if (filterType && filterType !== 'all') {
        loadFilterOptions(filterType, filterValue);
    }
    
    // Gắn sự kiện change cho filter type
    if (filterTypeSelect) {
        filterTypeSelect.addEventListener('change', handleFilterTypeChange);
    }
    
    // Xử lý khi chuyển từ khối sang lớp
    let previousFilterType = filterType || 'all';
    filterTypeSelect.addEventListener('change', function() {
        const currentType = this.value;
        
        if (currentType === 'lop' && previousFilterType === 'khoi') {
            // Reset giá trị khi chuyển từ khối sang lớp
            filterValueSelect.innerHTML = '<option value="">-- Chọn --</option>';
        }
        
        previousFilterType = currentType;
    });
}

// Chạy khi DOM đã load
document.addEventListener('DOMContentLoaded', initializePage);

// Xử lý form submit để tránh submit nhiều lần
document.getElementById('filterForm')?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        // Tự động bật lại sau 2 giây nếu có lỗi
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-search"></i> Lọc';
        }, 2000);
    }
});
</script>

<style>
/* ===== STYLE CHO BADGE GIỚI TÍNH ===== */

/* Giới tính Nam - Màu xanh dương */
.badge-male {
    background-color: #3498db !important; /* Xanh dương nhạt */
    color: white !important;
    border: 1px solid #2980b9 !important;
}

/* Giới tính Nữ - Màu hồng */
.badge-female {
    background-color: #e83e8c !important; /* Hồng */
    color: white !important;
    border: 1px solid #d81b60 !important;
}

/* ===== STYLE CHUNG CHO BADGE ===== */
.badge {
    padding: 4px 10px !important;
    border-radius: 12px !important;
    font-size: 0.85em !important;
    min-width: 60px !important;
    text-align: center !important;
    display: inline-block !important;
    vertical-align: middle !important;
    line-height: 1.2 !important;
    height: 24px !important;
    box-sizing: border-box !important;
}

/* ===== STYLE CHO BẢNG ===== */
/* Style cho loading state */
#filter_value[disabled] {
    background-color: #f8f9fa;
    opacity: 0.7;
}

/* Animation cho loading */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

/* Đảm bảo mã HS được hiển thị đúng */
td:first-child {
    font-weight: 500;
    color: #2c3e50;
    text-align: center;
    vertical-align: middle;
}

/* Căn giữa mã HS */
#studentTable th:first-child,
#studentTable td:first-child {
    text-align: center;
    width: 100px;
}
/* Căn giữa cột tên */
#studentTable th:nth-child(2),
#studentTable td:nth-child(2) {
    text-align: left;
    vertical-align: middle;
}
/* Căn giữa cột giới tính */
#studentTable th:nth-child(3),
#studentTable td:nth-child(3) {
    text-align: center;
    vertical-align: middle;
}

/* Căn giữa cột trạng thái */
#studentTable th:nth-child(6),
#studentTable td:nth-child(6) {
    text-align: center;
    vertical-align: middle;
    color: #000; /* Màu đen cho text trạng thái */
    font-weight: 500;
}

/* Căn giữa cột lớp và khối */
#studentTable th:nth-child(4),
#studentTable td:nth-child(4),
#studentTable th:nth-child(5),
#studentTable td:nth-child(5) {
    text-align: center;
    vertical-align: middle;
}

/* Style cho header actions */
.header-actions .badge-info {
    background-color: #17a2b8;
    padding: 5px 12px;
    font-size: 0.9em;
}

/* Tạo hiệu ứng hover cho hàng trong bảng */
#studentTable tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

/* Style cho các cột văn bản */
#studentTable td:nth-child(2) { /* Họ và tên */
    font-weight: 500;
    color: #2c3e50;
    vertical-align: middle;
}

/* Đảm bảo tất cả các ô trong bảng có cùng chiều cao */
#studentTable tbody td {
    padding: 12px 8px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #dee2e6;
}

/* Đảm bảo các header căn đều */
#studentTable thead th {
    padding: 12px 8px;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    text-align: center;
    vertical-align: middle;
}

/* Căn chỉnh chiều cao dòng */
#studentTable tbody tr {
    height: 48px;
}

/* Responsive table */
.table-responsive {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}
</style>

<?php include "views/layout/footer.php"; ?>