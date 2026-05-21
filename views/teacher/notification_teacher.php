<?php
// Bắt đầu session và kiểm tra user
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
$user = $_SESSION['user'];
?>

<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/sidebar_teacher.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h2>📢 Danh sách thông báo</h2>
        <p class="info-text">Thông báo từ Ban Giám hiệu và hệ thống (mới nhất đến cũ nhất)</p>
    </div>

    <?php if (!empty($notifications)): ?>
        <div class="notifications-list">
            <?php foreach ($notifications as $tb): ?>
                <?php
                  $isNew = false;
                  if (!empty($tb['ngayGui'])) {
                      $isNew = (time() - strtotime($tb['ngayGui']) <= 3*24*60*60); // MỚI: 3 ngày
                  }
                ?>
                <a href="index.php?controller=notificationTeacher&action=detail&id=<?= $tb['maThongBao'] ?>" class="notification-link">
                    <div class="notification-card <?= $isNew ? 'new' : '' ?>">
                        <div class="notification-header">
                            <h3>
                                <?= htmlspecialchars($tb['tieuDe']) ?>
                                <?php if ($isNew): ?>
                                    <span class="badge-new">MỚI</span>
                                <?php endif; ?>
                            </h3>
                            <span class="notification-date"><?= date('d/m/Y H:i', strtotime($tb['ngayGui'])) ?></span>
                        </div>
                        <div class="notification-meta">
                            <span class="sender"><i class="fas fa-user"></i> <?= htmlspecialchars($tb['nguoiGui']) ?></span>
                            <span class="target"><i class="fas fa-users"></i> Đối tượng: <?= htmlspecialchars($tb['doiTuong']) ?></span>
                        </div>
                        <div class="notification-content">
                            <p><?= nl2br(htmlspecialchars(substr($tb['noiDung'], 0, 150))) ?><?= strlen($tb['noiDung']) > 150 ? '...' : '' ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>Hiện chưa có thông báo nào.</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* ===== Main Content ===== */
.main-content {
    margin-left: 80px;
    padding: 30px;
    min-height: 100vh;
    background: #f5f7fa;
}

.page-header {
    margin-bottom: 25px;
}

.page-header h2 {
    color: #1e3a8a;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.info-text {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

/* ===== Notifications List ===== */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.notification-link {
    text-decoration: none;
    display: block;
}

.notification-card {
    background: #ffffff;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    padding: 20px 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    border-left-color: #2563eb;
}

.notification-card.new {
    background: linear-gradient(to right, #eff6ff 0%, #ffffff 100%);
    border-left-color: #ef4444;
}

/* Notification Header */
.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.notification-header h3 {
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    flex: 1;
}

.notification-date {
    color: #9ca3af;
    font-size: 13px;
    white-space: nowrap;
    margin-left: 12px;
}

.badge-new {
    background: #ef4444;
    color: #ffffff;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 8px;
    display: inline-block;
}

/* Notification Meta */
.notification-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #6b7280;
}

.notification-meta i {
    margin-right: 6px;
    color: #9ca3af;
}

.sender, .target {
    display: flex;
    align-items: center;
}

/* Notification Content */
.notification-content {
    color: #4b5563;
    font-size: 14px;
    line-height: 1.6;
}

.notification-content p {
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-state p {
    color: #9ca3af;
    font-size: 16px;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px 15px;
    }

    .notification-header {
        flex-direction: column;
    }

    .notification-date {
        margin-left: 0;
        margin-top: 8px;
    }

    .notification-meta {
        flex-direction: column;
        gap: 8px;
    }

    .page-header h2 {
        font-size: 24px;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>