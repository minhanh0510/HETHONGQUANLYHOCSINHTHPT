<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_department.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">📊 Nhập chỉ tiêu tuyển sinh</div>
        <div class="header-actions">
            <button type="button" class="btn btn-warning" onclick="confirmExit()">🚪 Kết thúc</button>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="alert alert-info">
        <strong>Thông tin:</strong> Nhập chỉ tiêu tuyển sinh cho năm học <?= date('Y') . '-' . (date('Y') + 1) ?>
    </div>

    <form method="POST" action="index.php?controller=quota&action=save" id="quotaForm">
        <div class="table-responsive">
            <table class="data-table" style="width: 100%">
                <thead>
                    <tr>
                        <th width="5%">STT</th>
                        <th width="25%">Tên trường</th>
                        <th width="10%">Chỉ tiêu năm trước</th>
                        <th width="10%">Số lớp hiện có</th>
                        <th width="10%">Quy mô gợi ý</th>
                        <th width="15%">Số lớp dự kiến</th>
                        <th width="15%">Chỉ tiêu tuyển sinh</th>
                        <th width="10%">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($schools)): ?>
                        <?php foreach ($schools as $index => $school): 
                            $currentQuota = $currentQuotas[$school['maTruong']] ?? null;
                            $goiYQuyMo = $school['soLopHienTai'] ? 
                                ($school['soLopHienTai'] - 1) . '-' . ($school['soLopHienTai'] + 1) : 
                                '8-10';
                        ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($school['tenTruong']) ?></strong>
                                    <input type="hidden" name="maTruong[]" value="<?= htmlspecialchars($school['maTruong']) ?>">
                                </td>
                                <td class="text-center"><?= $school['chiTieuNamTruoc'] ?></td>
                                <td class="text-center"><?= $school['soLopHienTai'] ?></td>
                                <td class="text-center"><?= $goiYQuyMo ?></td>
                                <td>
                                    <input type="number" 
                                           name="soLopHoc[]" 
                                           class="form-control" 
                                           min="1" 
                                           value="<?= $currentQuota['soLopHoc'] ?? '' ?>"
                                           placeholder="Nhập số lớp"
                                           onchange="calculateQuota(this, <?= $index ?>)">
                                </td>
                                <td>
                                    <input type="number" 
                                           name="soHocSinh[]" 
                                           id="soHocSinh_<?= $index ?>"
                                           class="form-control" 
                                           min="1" 
                                           value="<?= $currentQuota['soHocSinh'] ?? '' ?>"
                                           placeholder="Nhập chỉ tiêu">
                                </td>
                                <td class="text-center">
                                    <?php if ($currentQuota): ?>
                                        <span class="status-badge status-success">Đã nhập</span>
                                    <?php else: ?>
                                        <span class="status-badge status-warning">Chưa nhập</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có trường học nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions" style="margin-top: 20px; text-align: center;">
            <button type="submit" class="btn btn-success btn-lg">💾 Lưu chỉ tiêu</button>
            <button type="button" class="btn btn-warning btn-lg" onclick="clearForm()">🗑️ Xóa hết</button>
        </div>
    </form>

    <div class="user-info-footer" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
        <div><strong>Lưu ý:</strong> Xin chào, <?= htmlspecialchars($user['ho_ten'] ?? 'Quản trị viên') ?></div>
        <div><strong>Đơn vị:</strong> Sở Giáo dục và Đào tạo</div>
    </div>
</div>

<!-- Modal xác nhận kết thúc -->
<div id="exitModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>⚠️ Xác nhận kết thúc</h3>
            <span class="close" onclick="closeExitModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Bạn có chắc muốn kết thúc nhập chỉ tiêu? Các thay đổi chưa lưu sẽ bị mất.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" onclick="exitQuota()">✅ Xác nhận kết thúc</button>
            <button class="btn btn-secondary" onclick="closeExitModal()">❌ Hủy</button>
        </div>
    </div>
</div>

<script>
function calculateQuota(input, index) {
    const soLop = parseInt(input.value);
    if (soLop > 0) {
        const chiTieu = soLop * 40;
        document.getElementById('soHocSinh_' + index).value = chiTieu;
    }
}

function clearForm() {
    if (confirm('Bạn có chắc muốn xóa tất cả dữ liệu đã nhập?')) {
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.value = '';
        });
    }
}

function confirmExit() {
    document.getElementById('exitModal').style.display = 'block';
}

function closeExitModal() {
    document.getElementById('exitModal').style.display = 'none';
}

function exitQuota() {
    window.location.href = 'index.php?controller=department&action=index';
}

document.getElementById('quotaForm').addEventListener('submit', function(e) {
    let hasData = false;
    document.querySelectorAll('input[name="soHocSinh[]"]').forEach(input => {
        if (input.value.trim() !== '') {
            hasData = true;
        }
    });
    
    if (!hasData) {
        e.preventDefault();
        alert('Còn trường chưa được nhập chỉ tiêu');
        return false;
    }
});
</script>

<?php include "views/layout/footer.php"; ?>