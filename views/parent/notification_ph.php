<?php
// Bắt đầu session và kiểm tra user
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$user = $_SESSION['user'];
$vaiTro = $user['vaiTro'] ?? '';

?>

<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/sidebar_parent.php'; ?>

<div class="main-content">
    <h2>📢 Danh sách thông báo</h2>
    <p class="info-text">Danh sách thông báo từ mới nhất đến cũ nhất</p>

    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $tb): ?>
            <?php
              $isNew = false;
                if (!empty($tb['ngayGui'])) {
                    $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                    $ngayGui = new DateTime($tb['ngayGui'], new DateTimeZone('Asia/Ho_Chi_Minh'));

                    $diff = $now->getTimestamp() - $ngayGui->getTimestamp();

                    $isNew = ($diff <= 3*24*60*60); // TEST 5 giây
                }
            ?>

            <a href="index.php?controller=notificationParent&action=detail&id=<?= $tb['maThongBao'] ?>" style="text-decoration:none;">
                <div class="notification-card <?= $isNew ? 'new' : '' ?>">
                    <h3>
                        <?= htmlspecialchars($tb['tieuDe']) ?>
                        <?php if ($isNew): ?>
                            <span class="badge-new">MỚI</span>
                        <?php endif; ?>
                    </h3>
                    <p><b>Người gửi:</b> <?= htmlspecialchars($tb['nguoiGui']) ?></p>
                    <p><?= nl2br(htmlspecialchars($tb['noiDung'])) ?></p>
                    <small><?= date('d/m/Y', strtotime($tb['ngayGui'])) ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>Hiện chưa có thông báo nào.</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* ===== Notification card ===== */
.notification-card {
    background: #fff;
    border-left: 5px solid #3b82f6; /* xanh dương */
    margin: 12px 0;
    padding: 18px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    transition: all 0.2s ease;
}
.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.badge-new {
    background: #ef4444;
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 12px;
    margin-left: 6px;
}
.info-text {
    color: #6b7280;
    margin-bottom: 16px;
}
.notification-card h3 {
    margin-bottom: 6px;
    font-size: 16px;
}
.notification-card p {
    margin: 4px 0;
    color: #374151;
}
.notification-card small {
    color: #9ca3af;
}
.empty-state {
    text-align: center;
    color: #9ca3af;
    padding: 40px 0;
}
.badge-new {
    background: #ef4444; /* Màu đỏ */
    color: #fff; /* Màu chữ trắng */
    padding: 3px 8px; /* Khoảng cách bên trong */
    border-radius: 5px; /* Bo góc */
    font-size: 12px; /* Kích thước chữ */
    margin-left: 
}

/* Responsive */
@media (max-width: 768px) {
    .notification-card {
        padding: 14px 16px;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>
