<?php
$current_page = 'dashboard';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar_parent.php';
?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">🏠 Trang chủ Phụ huynh</div>
    </div>

    <div class="welcome-section">
        <h3>Xin chào, <?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Phụ huynh') ?>!</h3>
        <p>Chào mừng bạn đến với hệ thống quản lý giáo dục.</p>
    </div>

    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-icon">📝</div>
            <div class="card-title">Đăng ký tuyển sinh</div>
            <a href="index.php?controller=admission&action=register" class="btn btn-primary">Truy cập</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>