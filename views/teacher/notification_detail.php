<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết thông báo - Giáo viên</title>
<style>
/* ====== Reset cơ bản ====== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-color: #f5f7fa;
    color: #1f2937;
    line-height: 1.6;
}

/* Container chính */
.container {
    background: #ffffff;
    max-width: 900px;
    margin: 40px auto;
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.container:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

/* Header thông báo */
.notification-header {
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.container h2 {
    font-size: 28px;
    color: #1e40af;
    font-weight: 700;
    margin-bottom: 16px;
    line-height: 1.3;
}

/* Thông tin người gửi và ngày gửi */
.info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 10px;
    font-size: 14px;
    color: #4b5563;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.info-item span {
    font-weight: 600;
}

.info-icon {
    font-size: 16px;
}

/* Đối tượng nhận */
.target-audience {
    display: inline-block;
    background: #dbeafe;
    color: #1e40af;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-top: 10px;
}

/* Nội dung thông báo */
.content {
    font-size: 16px;
    color: #374151;
    margin-bottom: 35px;
    white-space: pre-line;
    line-height: 1.8;
    padding: 20px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

/* Nút quay lại */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #2563eb;
    font-weight: 500;
    padding: 10px 20px;
    border: 2px solid #2563eb;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.back-link:hover {
    background: #2563eb;
    color: #ffffff;
    transform: translateX(-4px);
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 25px 20px;
        margin: 20px 10px;
    }
    
    .container h2 {
        font-size: 22px;
    }
    
    .info {
        flex-direction: column;
        gap: 10px;
    }
    
    .content {
        font-size: 15px;
        padding: 15px;
    }
}
</style>
</head>
<body>
<div class="container">
  <div class="notification-header">
    <h2><?= htmlspecialchars($notification['tieuDe']) ?></h2>
    
    <div class="info">
        <div class="info-item">
            <span class="info-icon">👤</span>
            <span>Người gửi:</span> <?= htmlspecialchars($notification['nguoiGui']) ?>
        </div>
        <div class="info-item">
            <span class="info-icon">📅</span>
            <span>Ngày gửi:</span> <?= date('d/m/Y H:i', strtotime($notification['ngayGui'])) ?>
        </div>
    </div>
    
    <?php if (isset($notification['doiTuong'])): ?>
    <span class="target-audience">
        🎯 Đối tượng: <?= htmlspecialchars($notification['doiTuong']) ?>
    </span>
    <?php endif; ?>
  </div>
  
  <div class="content">
    <?= nl2br(htmlspecialchars($notification['noiDung'])) ?>
  </div>
  
  <a class="back-link" href="index.php?controller=notification&action=teacherIndex">
    ⬅ Quay lại danh sách thông báo
  </a>
</div>
</body>
</html>