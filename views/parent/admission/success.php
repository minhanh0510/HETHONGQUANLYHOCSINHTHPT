<?php include __DIR__ . '/../../layout/header.php'; ?>
<?php include __DIR__ . '/../../layout/sidebar_parent.php'; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">✅ Đăng ký thành công</div>
    </div>

    <div class="alert alert-success text-center">
        <div style="font-size: 48px; margin-bottom: 20px;">🎉</div>
        <h3>Đăng ký tuyển sinh thành công!</h3>
        <p class="lead">Hồ sơ của bạn đã được ghi nhận với trạng thái <strong>"Chờ xét tuyển"</strong></p>
        
        <?php if (!empty($maHS)): ?>
            <div class="success-info">
                <p><strong>Mã hồ sơ:</strong> <span class="badge badge-primary"><?= htmlspecialchars($maHS) ?></span></p>
                <p>Vui lòng ghi nhớ mã hồ sơ này để tra cứu kết quả sau này.</p>
            </div>
        <?php endif; ?>
        
        <div class="action-buttons" style="margin-top: 20px;">
            <a href="index.php?controller=parent&action=dashboard" class="btn btn-outline">🏠 Về trang chủ</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
