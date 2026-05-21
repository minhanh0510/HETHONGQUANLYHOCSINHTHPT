<?php
// Kiểm tra session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /PTUD_HETHONGQUANLYHOCSINHC3/index.php?controller=auth&action=login");
    exit;
}

$user = $_SESSION['user'];
if ($user['role'] !== 'parent') {
    die("Bạn không có quyền truy cập trang này");
}
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_parent.php"; ?>

<div class="main-content">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e9ecef;">
        <h1 style="font-size: 1.8rem; margin: 0; color: #2c3e50;">👨‍👩‍👧‍👦 Phụ huynh</h1>
        <div style="color: #6c757d; font-size: 1rem;">
            <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y') ?>
        </div>
    </div>

    <!-- Welcome Section -->
    <div style="background: white; border-radius: 10px; padding: 40px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2c3e50; margin-bottom: 15px;">
            Chào mừng, <?= htmlspecialchars($user['hoVaTen'] ?? $user['username'] ?? 'Phụ huynh') ?>!
        </h2>
        
        <div style="text-align: center; margin-top: 20px;">
        <img src="/PTUD_HETHONGQUANLYHOCSINHC3/assets/img/1.jpg" alt="Dashboard" style="max-width: 100%; height: auto;">
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>