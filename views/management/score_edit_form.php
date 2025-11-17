<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">
            <h1>📝 SỬA ĐIỂM HỌC SINH</h1>
            <?php if (isset($studentInfo)): ?>
                <div class="student-info-banner">
                    <strong><?= htmlspecialchars($studentInfo['hoVaTen']) ?></strong>
                    - Mã HS: <?= $maHS ?>
                    <?php if (!empty($studentInfo['tenLop'])): ?>
                        - Lớp: <?= $studentInfo['tenLop'] ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Form sửa điểm - CĂN GIỮA -->
            <div class="card edit-score-card" id="editForm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 SỬA ĐIỂM HỌC SINH</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($scores)): ?>
                        <form method="POST" action="index.php?controller=scoreEdit&action=update" id="scoreEditForm">
                            <input type="hidden" name="maHS" value="<?= $maHS ?>">
                            <!-- THÊM DÒNG NÀY -->
                            <input type="hidden" name="maMinhChung" value="<?= $maMinhChung ?>">
                            
                            <div class="form-group mb-3">
                                <label class="form-label"><strong>Chọn điểm cần sửa:</strong></label>
                                <select name="maDiem" class="form-control form-control-lg" required>
                                    <option value="">-- Chọn điểm --</option>
                                    <?php foreach ($scores as $score): ?>
                                        <option value="<?= $score['maDiem'] ?>">
                                            <?= htmlspecialchars($score['tenMon']) ?> - 
                                            <?= htmlspecialchars($score['loaiDiem']) ?>: 
                                            <strong><?= number_format($score['diemSo'], 1) ?></strong>
                                            (HK<?= $score['hocKy'] ?> - <?= $score['namHoc'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label"><strong>Điểm mới:</strong></label>
                                <input type="number" name="diemMoi" class="form-control form-control-lg" 
                                    step="0.1" min="0" max="10" required 
                                    placeholder="Nhập điểm từ 0 đến 10">
                                <small class="form-text text-muted">Điểm phải trong khoảng 0 - 10</small>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label"><strong>Lý do sửa điểm:</strong></label>
                                <textarea name="lyDo" class="form-control" rows="4" 
                                    placeholder="Nhập lý do sửa điểm (ít nhất 5 ký tự)" required></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button type="submit" class="btn btn-primary btn-lg me-md-2">
                                    ✅ Xác nhận sửa điểm
                                </button>
                                <a href="index.php?controller=scoreEdit&action=cancel" class="btn btn-secondary btn-lg">
                                    ❌ Kết thúc
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <h5>📭 Không tìm thấy điểm</h5>
                            <p>Học sinh này chưa có điểm trong hệ thống.</p>
                            <a href="index.php?controller=scoreEdit&action=index" class="btn btn-primary">
                                ← Quay lại danh sách
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
</div>

<style>
/* CSS để form đẹp hơn */
.justify-content-center {
    justify-content: center !important;
}

.edit-score-card {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
}

.form-control-lg {
    padding: 12px 15px;
    font-size: 16px;
}

.student-info-banner {
    background: #e3f2fd;
    padding: 10px 15px;
    border-radius: 5px;
    margin-top: 10px;
    border-left: 4px solid #2196f3;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-8 {
        width: 100% !important;
    }
}
</style>

<script>
function validateScore(input) {
    const value = parseFloat(input.value);
    if (isNaN(value) || value < 0 || value > 10) {
        alert('❌ Điểm nhập sai phạm vi (0-10). Vui lòng nhập lại!');
        input.focus();
        input.select();
        return false;
    }
    return true;
}

document.getElementById('scoreEditForm')?.addEventListener('submit', function(e) {
    const diemInput = document.querySelector('input[name="diemMoi"]');
    const lyDoInput = document.querySelector('textarea[name="lyDo"]');
    const maDiemSelect = document.querySelector('select[name="maDiem"]');
    
    // Validation điểm
    if (!validateScore(diemInput)) {
        e.preventDefault();
        return;
    }
    
    // Validation lý do
    if (lyDoInput.value.trim().length < 5) {
        alert('❌ Lý do phải có ít nhất 5 ký tự');
        e.preventDefault();
        lyDoInput.focus();
        return;
    }
    
    // Validation chọn điểm
    if (!maDiemSelect.value) {
        alert('❌ Vui lòng chọn điểm cần sửa');
        e.preventDefault();
        return;
    }
    
    // Confirm
    const selectedOption = maDiemSelect.options[maDiemSelect.selectedIndex].text;
    const confirmMsg = `📋 XÁC NHẬN SỬA ĐIỂM\n\n` +
                      `• ${selectedOption}\n` +
                      `• Điểm mới: ${diemInput.value}\n` +
                      `• Lý do: ${lyDoInput.value}\n\n` +
                      `Thao tác này sẽ được ghi vào lịch sử và không thể hoàn tác.`;
    
    if (!confirm(confirmMsg)) {
        e.preventDefault();
    }
});
</script>

<?php include "views/layout/footer.php"; ?>