<?php include __DIR__ . '/../../layout/header.php'; ?>
<?php include __DIR__ . '/../../layout/sidebar_parent.php'; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">📭 Kỳ tuyển sinh đã đóng</div>
    </div>

    <div class="alert alert-warning text-center">
        <div style="font-size: 48px; margin-bottom: 20px;">⏰</div>
        <h3>Hiện không có kỳ tuyển sinh nào đang mở!</h3>
        <p class="lead">Vui lòng quay lại sau khi kỳ tuyển sinh mới được mở.</p>
        
        <div class="action-buttons" style="margin-top: 20px;">
            <a href="index.php?controller=parent&action=dashboard" class="btn btn-primary">🏠 Về trang chủ</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
