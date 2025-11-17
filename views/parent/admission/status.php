<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_parent.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">📋 Trạng thái hồ sơ tuyển sinh</div>
    </div>

    <?php if (empty($hoSoList)): ?>
        <div class="alert alert-info text-center">
            <p>Bạn chưa có hồ sơ tuyển sinh nào.</p>
            <a href="index.php?controller=admission&action=register" class="btn btn-primary">📝 Đăng ký ngay</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã HS</th>
                        <th>Học sinh</th>
                        <th>Kỳ tuyển sinh</th>
                        <th>Ngày đăng ký</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($hoSoList as $hoSo): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($hoSo['maHS']) ?></strong></td>
                        <td><?= htmlspecialchars($hoSo['hoTenHS']) ?></td>
                        <td><?= htmlspecialchars($hoSo['tenKyTS']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($hoSo['ngayDangKy'])) ?></td>
                        <td>
                            <span class="status-badge status-info">
                                <?= htmlspecialchars($hoSo['dangThai']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>