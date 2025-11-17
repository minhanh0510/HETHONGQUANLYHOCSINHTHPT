<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">
            <h3>QUẢN LÝ SỬA ĐIỂM</h3>
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

    <!-- Search Box -->
    <div class="card search-card mb-4">
        <div class="card-body">
            <div class="search-box">
                <input type="text" class="form-control search-input" placeholder="🔍 Tìm kiếm minh chứng theo tên học sinh...">
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Minh chứng -->
        <div class="col-md-6">
            <!-- Danh sách minh chứng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>📋 DANH SÁCH MINH CHỨNG SỬA ĐIỂM</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($minhChungList)): ?>
                        <div class="minhchung-list">
                            <?php foreach ($minhChungList as $mc): ?>
                                <div class="minhchung-item">
                                    <div class="minhchung-header">
                                        <strong class="minhchung-code">MC<?= str_pad($mc['maMinhChung'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        <span class="minhchung-date"><?= date('d/m/Y', strtotime($mc['ngayTao'])) ?></span>
                                    </div>
                                    <div class="minhchung-content">
                                        <div class="student-info">
                                            <strong><?= htmlspecialchars($mc['tenHS']) ?></strong>
                                            <span class="student-id">- Mã: <?= $mc['maHS'] ?></span>
                                        </div>
                                        <p class="minhchung-reason"><?= htmlspecialchars($mc['lyDoYeuCau']) ?></p>
                                        <div class="minhchung-actions">
                                            <button class="btn btn-primary btn-sm" 
                                                    onclick="window.location.href='index.php?controller=scoreEdit&action=showStudent&maHS=<?= $mc['maHS'] ?>&maMinhChung=<?= $mc['maMinhChung'] ?>'">
                                                📝 Xử lý
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            📭 Không có minh chứng nào cần xử lý
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

   
        <!-- Right Column - Lịch sử sửa điểm -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>📊 LỊCH SỬ SỬA ĐIỂM</h3>
                     <a href="index.php?controller=scoreEdit&action=history" class="btn btn-outline-primary btn-sm">
                    📋 Xem tất cả
                 </a>
                </div>
                <div class="card-body">
                    <div class="history-list">
                        <?php if (!empty($lichSuSuaDiem)): ?>
                            <?php foreach ($lichSuSuaDiem as $lichsu): ?>
                                <div class="history-item">
                                    <div class="history-header">
                                        <strong><?= htmlspecialchars($lichsu['tenHS']) ?></strong>
                                        <span class="history-date"><?= date('d/m/Y H:i', strtotime($lichsu['ngaySua'])) ?></span>
                                    </div>
                                    <div class="history-content">
                                        <div class="history-subject">
                                            <?= htmlspecialchars($lichsu['tenMon']) ?> 
                                            (Mã điểm: <?= $lichsu['maDiem'] ?>)
                                        </div>
                                        <div class="history-score">
                                            <span class="score-old"><?= number_format($lichsu['diemCu'], 1) ?></span> 
                                            → 
                                            <span class="score-new"><?= number_format($lichsu['diemMoi'], 1) ?></span>
                                        </div>
                                        <div class="history-reason">
                                            <?= htmlspecialchars($lichsu['lyDo']) ?>
                                        </div>
                                        <div class="history-meta">
                                            <small class="text-muted">
                                                Sửa bởi: <?= htmlspecialchars($lichsu['tenNguoiSua']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                📭 Chưa có lịch sử sửa điểm
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Search Box */
.search-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
}
.search-input {
    border: none;
    background: transparent;
    font-size: 16px;
    padding: 12px 15px;
}
.search-input:focus {
    outline: none;
    box-shadow: none;
}

/* Minh chứng list */
.minhchung-list {
    max-height: 400px;
    overflow-y: auto;
}
.minhchung-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    background: #fff;
}
.minhchung-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.minhchung-code {
    color: #007bff;
    font-weight: bold;
}
.minhchung-date {
    color: #6c757d;
    font-size: 0.9em;
}
.student-info {
    margin-bottom: 8px;
}
.student-id {
    color: #6c757d;
    font-size: 0.9em;
}
.minhchung-reason {
    color: #495057;
    margin-bottom: 10px;
    font-size: 0.95em;
}

/* Lịch sử sửa điểm */
/* Lịch sử sửa điểm - Phiên bản nhỏ gọn */
.history-list {
    max-height: 500px;
    overflow-y: auto;
}

.history-item {
    padding: 12px;
    border: 1px solid #e1e8ed;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.history-item:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding-bottom: 6px;
    border-bottom: 1px solid #f1f1f1;
}

.history-header strong {
    color: #2c3e50;
    font-size: 0.95em;
    font-weight: 600;
}

.history-date {
    color: #7f8c8d;
    font-size: 0.8em;
}

.history-content {
    padding: 0 3px;
}

.history-subject {
    color: #34495e;
    font-weight: 500;
    margin-bottom: 6px;
    font-size: 0.9em;
}

.history-score {
    margin: 8px 0;
    font-size: 1em;
    font-weight: 600;
    text-align: center;
}

.score-old {
    color: #e74c3c;
    text-decoration: line-through;
    padding: 3px 6px;
    background: #ffeaea;
    border-radius: 4px;
    margin-right: 8px;
    font-size: 0.9em;
}

.score-new {
    color: #27ae60;
    padding: 3px 6px;
    background: #e8f6ef;
    border-radius: 4px;
    margin-left: 8px;
    font-size: 0.9em;
}

.history-reason {
    color: #555;
    font-size: 0.85em;
    margin: 8px 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #3498db;
    line-height: 1.3;
}

.history-meta {
    color: #95a5a6;
    font-size: 0.8em;
    text-align: right;
    margin-top: 5px;
}
</style>
<script>
// Tìm kiếm minh chứng
document.querySelector('.search-input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.minhchung-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

<?php include "views/layout/footer.php"; ?>