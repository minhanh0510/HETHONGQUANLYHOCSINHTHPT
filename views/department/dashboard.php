<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_department.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">🏢 Dashboard - Sở Giáo dục</div>
    </div>

    <div class="alert alert-info">
        <strong>Chào mừng:</strong> <?= htmlspecialchars($user['ho_ten'] ?? 'Quản trị viên Sở GD') ?>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <img src="/PTUD_HETHONGQUANLYHOCSINHC3/assets/img/1.jpg" alt="Dashboard" style="max-width: 100%; height: auto;">
    </div>
</div>

<?php include "views/layout/footer.php"; ?>