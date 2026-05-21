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
    <div class="detail-container">
        <!-- Tiêu đề -->
        <h1 class="notification-title">
            <?= htmlspecialchars($notification['tieuDe']) ?>
            <?php 
            $isNew = (time() - strtotime($notification['ngayGui']) <= 3*24*60*60);
            if ($isNew): 
            ?>
                <span class="badge-new">MỚI</span>
            <?php endif; ?>
        </h1>
        
        <!-- Thông tin -->
        <div class="notification-info">
            <div class="info-item">
                <i class="fas fa-user"></i>
                <span><strong>Người gửi:</strong> <?= htmlspecialchars($notification['nguoiGui']) ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-calendar-alt"></i>
                <span><strong>Ngày gửi:</strong> <?= date('d/m/Y H:i', strtotime($notification['ngayGui'])) ?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-users"></i>
                <span><strong>Đối tượng:</strong> <?= htmlspecialchars($notification['doiTuong']) ?></span>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="content-section">
            <h3><i class="fas fa-file-alt"></i> Nội dung</h3>
            <div class="content-text"><?= nl2br(htmlspecialchars($notification['noiDung'])) ?></div>
        </div>

        <?php if (!empty($notification['moTa'])): ?>
        <div class="content-section">
            <h3><i class="fas fa-info-circle"></i> Mô tả</h3>
            <div class="content-text"><?= nl2br(htmlspecialchars($notification['moTa'])) ?></div>
        </div>
        <?php endif; ?>

        <?php if (!empty($notification['huongDanThucHien'])): ?>
        <div class="content-section">
            <h3><i class="fas fa-tasks"></i> Hướng dẫn thực hiện</h3>
            <div class="content-text"><?= nl2br(htmlspecialchars($notification['huongDanThucHien'])) ?></div>
        </div>
        <?php endif; ?>

        <?php if (!empty($notification['luuY'])): ?>
        <div class="content-section">
            <h3><i class="fas fa-exclamation-triangle"></i> Lưu ý</h3>
            <div class="content-text"><?= nl2br(htmlspecialchars($notification['luuY'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Nút quay lại -->
        <a class="back-button" href="index.php?controller=notificationTeacher&action=index">
            <i class="fas fa-arrow-left"></i>
            <span>Quay lại danh sách</span>
        </a>
    </div>
</div>

<style>
/* ===== Main Content ===== */
.main-content {
    margin-left: 80px;
    padding: 30px;
    min-height: 100vh;
    background: #f5f7fa;
}

/* Detail Container */
.detail-container {
    background: #ffffff;
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

/* Tiêu đề */
.notification-title {
    font-size: 32px;
    color: #1e3a8a;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.3;
}

.badge-new {
    background: #ef4444;
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    margin-left: 12px;
}

/* Info Section */
.notification-info {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid #e5e7eb;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6b7280;
}

.info-item i {
    color: #3b82f6;
    font-size: 16px;
}

.info-item strong {
    color: #374151;
    font-weight: 600;
}

/* Content Sections */
.content-section {
    margin-bottom: 28px;
}

.content-section h3 {
    font-size: 18px;
    color: #1f2937;
    font-weight: 600;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.content-section h3 i {
    color: #3b82f6;
    font-size: 20px;
}

.content-text {
    font-size: 15px;
    color: #4b5563;
    line-height: 1.8;
    white-space: pre-line;
    background: #f9fafb;
    padding: 16px 20px;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}

/* Back Button */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #ffffff;
    background: #3b82f6;
    font-weight: 500;
    padding: 12px 24px;
    border-radius: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
}

.back-button:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    text-decoration: none;
    color: #ffffff;
}

.back-button i {
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px 15px;
    }
    
    .detail-container {
        padding: 24px 20px;
    }
    
    .notification-title {
        font-size: 24px;
    }
    
    .notification-info {
        flex-direction: column;
        gap: 12px;
    }
    
    .content-section h3 {
        font-size: 16px;
    }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>