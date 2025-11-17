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
<title>Chi tiết thông báo</title>
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
    max-width: 800px;
    margin: 40px auto;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.container:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

/* Tiêu đề */
.container h2 {
    font-size: 28px;
    color: #1e3a8a;
    font-weight: 700;
    margin-bottom: 16px;
}

/* Thông tin người gửi và ngày gửi */
.info {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #4b5563;
}

.info span {
    font-weight: 600;
}

/* Nội dung thông báo */
.content {
    font-size: 15px;
    color: #374151;
    margin-bottom: 30px;
    white-space: pre-line; /* giữ xuống dòng */
}

/* Quay lại danh sách */
.back-link {
    display: inline-block;
    text-decoration: none;
    color: #3b82f6;
    font-weight: 500;
    padding: 8px 16px;
    border: 1px solid #3b82f6;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.back-link:hover {
    background: #3b82f6;
    color: #ffffff;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 20px;
        margin: 20px;
    }
    .container h2 {
        font-size: 24px;
    }
    .info {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
</head>
<body>
<div class="container">
  <h2><?= htmlspecialchars($notification['tieuDe']) ?></h2>
  <div class="info">
      <span>Người gửi: <?= htmlspecialchars($notification['nguoiGui']) ?></span>
      <span>Ngày gửi: <?= date('d/m/Y', strtotime($notification['ngayGui'])) ?></span>
  </div>
  <hr>
  <div class="content">
    <p><b>Nội dung:</b> <?= nl2br(htmlspecialchars($notification['noiDung'])) ?></p>
      <p><b>Mô tả:</b> <?= nl2br(htmlspecialchars($notification['moTa'])) ?></p>
      <p><b>Hướng dẫn thực hiện:</b> <?= nl2br(htmlspecialchars($notification['huongDanThucHien'])) ?></p>
      <p><b>Lưu ý:</b> <?= nl2br(htmlspecialchars($notification['luuY'])) ?></p>
     
  </div>
  <a class="back-link" href="index.php?controller=notificationParent&action=index">⬅ Quay lại danh sách</a>
</div>
</body>
</html>
