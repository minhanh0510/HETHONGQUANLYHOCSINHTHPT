<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-chart-pie"></i> Thống kê cơ cấu học sinh
        </h1>
        <div class="breadcrumb">
            <a href="index.php?controller=statistics&action=dashboard">Thống kê</a> / Cơ cấu học sinh
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="filter-section">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-filter"></i> Chọn phạm vi thống kê
            </div>
            <div class="card-body">
                <form method="GET" action="index.php" class="filter-form">
                    <input type="hidden" name="controller" value="statistics">
                    <input type="hidden" name="action" value="studentStructure">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="scope_type">Phạm vi:</label>
                                <select name="scope_type" id="scope_type" class="form-control" onchange="toggleScopeValue()">
                                    <option value="all" <?php echo ($scopeType ?? 'all') === 'all' ? 'selected' : ''; ?>>Toàn trường</option>
                                    <option value="khoi" <?php echo ($scopeType ?? '') === 'khoi' ? 'selected' : ''; ?>>Theo khối</option>
                                    <option value="lop" <?php echo ($scopeType ?? '') === 'lop' ? 'selected' : ''; ?>>Theo lớp</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="scope_value_container" style="display: <?php echo ($scopeType ?? 'all') === 'all' ? 'none' : 'block'; ?>">
                                <label for="scope_value">Chọn:</label>
                                <select name="scope_value" id="scope_value" class="form-control">
                                    <?php if (($scopeType ?? '') === 'khoi'): ?>
                                        <?php foreach ($filterOptions['khoi'] as $khoi): ?>
                                            <option value="<?php echo $khoi['value']; ?>" <?php echo ($scopeValue ?? '') == $khoi['value'] ? 'selected' : ''; ?>>
                                                <?php echo $khoi['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php elseif (($scopeType ?? '') === 'lop'): ?>
                                        <?php foreach ($filterOptions['lop'] as $lop): ?>
                                            <option value="<?php echo $lop['value']; ?>" <?php echo ($scopeValue ?? '') == $lop['value'] ? 'selected' : ''; ?>>
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
                                    <i class="fas fa-chart-pie"></i> Thống kê
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Kết quả thống kê -->
    <div class="result-section">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Kết quả thống kê
                <div class="header-actions">
                    <a href="index.php?controller=statistics&action=export&report_type=student_structure&scope_type=<?php echo $scopeType; ?>&scope_value=<?php echo $scopeValue; ?>" 
                       class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($structure)): ?>
                    <!-- Biểu đồ -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="chart-container">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-box">
                                <h5><i class="fas fa-info-circle"></i> Tóm tắt</h5>
                                <?php foreach ($structure as $item): ?>
                                    <div class="summary-item">
                                        <div class="summary-title"><?php echo $item['tenPhamVi']; ?></div>
                                        <div class="summary-stats">
                                            <div class="stat-row">
                                                <span class="stat-label">Tổng số:</span>
                                                <span class="stat-value"><?php echo $item['tongSo']; ?> học sinh</span>
                                            </div>
                                            <div class="stat-row">
                                                <span class="stat-label">Nam:</span>
                                                <span class="stat-value"><?php echo $item['soNam']; ?> (<?php echo $item['tyLeNam']; ?>%)</span>
                                            </div>
                                            <div class="stat-row">
                                                <span class="stat-label">Nữ:</span>
                                                <span class="stat-value"><?php echo $item['soNu']; ?> (<?php echo $item['tyLeNu']; ?>%)</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bảng chi tiết -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Phạm vi</th>
                                    <th>Tổng số</th>
                                    <th>Số nam</th>
                                    <th>Tỷ lệ nam</th>
                                    <th>Số nữ</th>
                                    <th>Tỷ lệ nữ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($structure as $item): ?>
                                    <tr>
                                        <td><strong><?php echo $item['tenPhamVi']; ?></strong></td>
                                        <td><?php echo $item['tongSo']; ?></td>
                                        <td>
                                            <span class="badge badge-info"><?php echo $item['soNam']; ?></span>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: <?php echo $item['tyLeNam']; ?>%">
                                                    <?php echo $item['tyLeNam']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-pink"><?php echo $item['soNu']; ?></span>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-pink" style="width: <?php echo $item['tyLeNu']; ?>%">
                                                    <?php echo $item['tyLeNu']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có dữ liệu để hiển thị. Vui lòng chọn phạm vi thống kê.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleScopeValue() {
    const scopeType = document.getElementById('scope_type').value;
    const container = document.getElementById('scope_value_container');
    const select = document.getElementById('scope_value');
    
    if (scopeType === 'all') {
        container.style.display = 'none';
        select.innerHTML = '';
    } else {
        container.style.display = 'block';
        
        // Load options theo loại scope
        const type = scopeType;
        fetch(`index.php?controller=statistics&action=getFilterOptions&type=${type === 'lop' ? 'class' : 'grade'}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Chọn --</option>';
                data.forEach(item => {
                    options += `<option value="${item.value}">${item.label}</option>`;
                });
                select.innerHTML = options;
            })
            .catch(error => {
                console.error('Error loading scope options:', error);
            });
    }
}

// Vẽ biểu đồ
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($structure)): ?>
        const structureData = <?php echo json_encode($structure); ?>;
        
        if (structureData.length > 0) {
            const item = structureData[0];
            const ctx = document.getElementById('genderChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Nam (' + item.tyLeNam + '%)', 'Nữ (' + item.tyLeNu + '%)'],
                    datasets: [{
                        data: [item.soNam, item.soNu],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Phân bố giới tính - ' + item.tenPhamVi
                        }
                    }
                }
            });
        }
    <?php endif; ?>
});
</script>

<style>
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

.summary-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #dee2e6;
}

.summary-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-title {
    font-weight: bold;
    color: #495057;
    margin-bottom: 10px;
    font-size: 1.1em;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 0.95em;
}

.stat-label {
    color: #6c757d;
}

.stat-value {
    font-weight: 500;
    color: #212529;
}

.badge-pink {
    background-color: #e83e8c;
    color: white;
}

.bg-pink {
    background-color: #e83e8c !important;
}
</style>

<?php include "views/layout/footer.php"; ?>