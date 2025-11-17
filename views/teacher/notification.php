<?php
// Bắt đầu session và kiểm tra user
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
$user = $_SESSION['user'];
$vaiTro = $user['vaiTro'] ?? '';

switch ($vaiTro) {
  case 'GiaoVien': 
      $displayName = 'Giáo viên: ' . $user['ho_ten']; 
      $avatarText = 'GV'; 
      break;
  default: 
      $displayName = 'Người dùng: ' . $user['ho_ten']; 
      $avatarText = 'ND'; 
      break;
}
?>

<?php include __DIR__ . '/../layout/header.php'; ?>
<?php include __DIR__ . '/../layout/sidebar_teacher.php'; ?>

<div class="main-content">
    <h2>📢 Danh sách thông báo (Giáo viên)</h2>
    <p class="info-text">Danh sách thông báo từ Ban Giám hiệu gửi đến giáo viên</p>

    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $tb): ?>
            <?php
              $isNew = false;
              if (!empty($tb['ngayGui'])) {
                  $isNew = (time() - strtotime($tb['ngayGui']) <= 3 * 24 * 60 * 60); // MỚI: 3 ngày
              }
            ?>
            <div class="notification-card <?= $isNew ? 'new' : '' ?>">
                <h3>
                    <a href="index.php?controller=notification&action=teacherDetail&id=<?= $tb['maThongBao'] ?>" 
                       style="color:#1e3a8a; text-decoration:none;">
                        <?= htmlspecialchars($tb['tieuDe']) ?>
                    </a>
                    <?php if ($isNew): ?><span class="badge-new">MỚI</span><?php endif; ?>
                </h3>
                <p><b>Người gửi:</b> <?= htmlspecialchars($tb['nguoiGui']) ?></p>
                <p class="notification-preview"><?= nl2br(htmlspecialchars(substr($tb['noiDung'], 0, 150))) ?><?= strlen($tb['noiDung']) > 150 ? '...' : '' ?></p>
                <small>📅 <?= date('d/m/Y H:i', strtotime($tb['ngayGui'])) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>📭 Hiện chưa có thông báo nào.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.main-content {
    padding: 20px;
}

.main-content h2 {
    color: #1e3a8a;
    margin-bottom: 10px;
    font-size: 24px;
}

.info-text {
    color: #6b7280;
    margin-bottom: 20px;
    font-size: 14px;
}

.notification-card {
    background: #fff;
    border-left: 5px solid #3b82f6;
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

.notification-card.new {
    border-left-color: #ef4444;
    background: linear-gradient(to right, #fef2f2 0%, #fff 100%);
}

.badge-new {
    background: #ef4444;
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 11px;
    margin-left: 6px;
    font-weight: 600;
}

.notification-card h3 {
    margin-bottom: 8px;
    font-size: 17px;
    font-weight: 600;
}

.notification-card h3 a {
    transition: color 0.2s;
}

.notification-card h3 a:hover {
    color: #3b82f6 !important;
}

.notification-card p {
    margin: 6px 0;
    color: #374151;
    font-size: 14px;
}

.notification-preview {
    color: #6b7280 !important;
    line-height: 1.5;
}

.notification-card small {
    color: #9ca3af;
    font-size: 12px;
    display: inline-block;
    margin-top: 8px;
}

.empty-state {
    text-align: center;
    color: #9ca3af;
    padding: 60px 20px;
    background: #f9fafb;
    border-radius: 8px;
    margin-top: 20px;
}

.empty-state p {
    font-size: 16px;
}

@media (max-width: 768px) {
    .main-content {
        padding: 15px;
    }
    
    .main-content h2 {
        font-size: 20px;
    }
    
    .notification-card {
        padding: 14px 16px;
    }
    
    .notification-card h3 {
        font-size: 15px;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer_teacher.php'; ?>