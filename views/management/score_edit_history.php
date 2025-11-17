<?php 
// views/management/score_edit_history.php
include "views/layout/header.php"; 
include "views/layout/sidebar_management.php"; 
?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">
            <h1>📊 LỊCH SỬ SỬA ĐIỂM</h1>
            <div class="page-actions">
                <a href="index.php?controller=scoreEdit&action=index" class="btn btn-secondary">
                    ← Quay lại
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <!-- Bộ lọc và tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control search-input" placeholder="🔍 Tìm kiếm theo tên học sinh, môn học...">
                </div>
                <div class="col-md-3">
                    <select class="form-control filter-select">
                        <option value="">Tất cả môn học</option>
                        <?php 
                        $monHocList = array_unique(array_column($lichSuSuaDiem, 'tenMon'));
                        foreach ($monHocList as $monHoc): 
                        ?>
                            <option value="<?= htmlspecialchars($monHoc) ?>"><?= htmlspecialchars($monHoc) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control filter-select">
                        <option value="">Tất cả người sửa</option>
                        <?php 
                        $nguoiSuaList = array_unique(array_column($lichSuSuaDiem, 'tenNguoiSua'));
                        foreach ($nguoiSuaList as $nguoiSua): 
                        ?>
                            <option value="<?= htmlspecialchars($nguoiSua) ?>"><?= htmlspecialchars($nguoiSua) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách lịch sử -->
    <div class="card">
        <div class="card-header">
            <h4>📋 Danh sách lịch sử sửa điểm</h4>
            <div class="header-info">
                <span class="text-muted">Hiển thị <?= count($lichSuSuaDiem) ?> / <?= $totalRecords ?> bản ghi</span>
            </div>
        </div>
        <div class="card-body">
            <div class="history-list-full">
                <?php if (!empty($lichSuSuaDiem)): ?>
                    <?php foreach ($lichSuSuaDiem as $lichsu): ?>
                        <div class="history-item-full">
                            <div class="history-header-full">
                                <div class="student-info-full">
                                    <strong><?= htmlspecialchars($lichsu['tenHS']) ?></strong>
                                    <span class="student-id">(Mã: <?= $lichsu['maHS'] ?>)</span>
                                    <?php if (!empty($lichsu['tenLop'])): ?>
                                        <span class="student-class">- Lớp: <?= $lichsu['tenLop'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="history-date-full"><?= date('d/m/Y H:i', strtotime($lichsu['ngaySua'])) ?></span>
                            </div>
                            
                            <div class="history-content-full">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="history-subject-full">
                                            <strong>Môn:</strong> <?= htmlspecialchars($lichsu['tenMon']) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="history-score-full">
                                            <span class="score-old"><?= number_format($lichsu['diemCu'], 1) ?></span> 
                                            → 
                                            <span class="score-new"><?= number_format($lichsu['diemMoi'], 1) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="history-meta-full">
                                            <strong>Người sửa:</strong> <?= htmlspecialchars($lichsu['tenNguoiSua']) ?>
                                            <br>
                                            <strong>Mã điểm:</strong> <?= $lichsu['maDiem'] ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="history-reason-full">
                                    <strong>Lý do:</strong> <?= htmlspecialchars($lichsu['lyDo']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <div style="font-size: 3em;">📭</div>
                        <h4>Chưa có lịch sử sửa điểm</h4>
                        <p>Hệ thống chưa ghi nhận lần sửa điểm nào.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Phân trang -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination-container mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?controller=scoreEdit&action=history&page=<?= $page - 1 ?>">‹ Trước</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="index.php?controller=scoreEdit&action=history&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?controller=scoreEdit&action=history&page=<?= $page + 1 ?>">Sau ›</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Thống kê */
.stat-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.stat-card .card-body {
    display: flex;
    align-items: center;
    padding: 20px;
}
.stat-icon {
    font-size: 2.5em;
    margin-right: 15px;
}
.stat-info h3 {
    margin: 0;
    font-size: 1.8em;
    font-weight: bold;
    color: #2c3e50;
}
.stat-info p {
    margin: 5px 0 0 0;
    color: #7f8c8d;
    font-size: 0.9em;
}

/* Lịch sử đầy đủ */
.history-list-full {
    max-height: 600px;
    overflow-y: auto;
}
.history-item-full {
    border: 1px solid #e1e8ed;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 12px;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}
.history-item-full:hover {
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}
.history-header-full {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f1f1f1;
}
.student-info-full strong {
    color: #2c3e50;
    font-size: 1em;
}
.student-id, .student-class {
    color: #7f8c8d;
    font-size: 0.85em;
}
.history-date-full {
    color: #7f8c8d;
    font-size: 0.85em;
    font-weight: 500;
}
.history-content-full {
    padding: 0 5px;
}
.history-subject-full {
    color: #34495e;
    font-weight: 500;
    font-size: 0.9em;
}
.history-score-full {
    font-size: 1em;
    font-weight: 600;
}
.score-old {
    color: #e74c3c;
    text-decoration: line-through;
    padding: 3px 6px;
    background: #ffeaea;
    border-radius: 4px;
    margin-right: 6px;
    font-size: 0.9em;
}
.score-new {
    color: #27ae60;
    padding: 3px 6px;
    background: #e8f6ef;
    border-radius: 4px;
    margin-left: 6px;
    font-size: 0.9em;
}
.history-meta-full {
    color: #555;
    font-size: 0.85em;
}
.history-reason-full {
    color: #555;
    font-size: 0.9em;
    margin-top: 8px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #3498db;
}

/* Phân trang */
.pagination-container {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
}
.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
.page-link {
    color: #007bff;
}

/* Responsive */
@media (max-width: 768px) {
    .history-header-full {
        flex-direction: column;
        align-items: flex-start;
    }
    .history-date-full {
        margin-top: 5px;
    }
    .stat-card .card-body {
        flex-direction: column;
        text-align: center;
    }
    .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
}
</style>

<script>
// Tìm kiếm và lọc
document.querySelector('.search-input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.history-item-full');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Lọc theo môn học
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function(e) {
        const filterValue = e.target.value.toLowerCase();
        const filterType = e.target.parentElement.querySelector('option').textContent.toLowerCase();
        const items = document.querySelectorAll('.history-item-full');
        
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (!filterValue || text.includes(filterValue)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?php include "views/layout/footer.php"; ?>