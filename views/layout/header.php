<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hệ thống Quản lý Giáo dục</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f4f6f9;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    header {
      background: #283593;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 60px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    header h1 { 
      font-size: 20px; 
      margin: 0; 
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    
    header .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
    }
    
    header .user-info .username {
      font-weight: 600;
    }
    
    header .user-info .separator {
      color: rgba(255,255,255,0.6);
    }
    
    header .user-info a { 
      color: #fff; 
      text-decoration: none;
      transition: opacity 0.2s;
    }
    
    header .user-info a:hover {
      opacity: 0.8;
      text-decoration: underline;
    }

    .container {
      display: flex;
      flex: 1;
      margin-top: 60px;
      height: calc(100vh - 60px);
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background: #1e293b;
      color: white;
      padding: 20px;
      position: fixed;
      top: 60px;
      bottom: 0;
      left: 0;
      overflow-y: auto;
      z-index: 999;
    }

    .sidebar a {
      display: block;
      color: #e2e8f0;
      text-decoration: none;
      padding: 10px 15px;
      margin: 6px 0;
      border-radius: 6px;
      transition: background 0.2s;
    }

    .sidebar a:hover { 
      background: #334155; 
    }

    .main-content {
      position: fixed;
      left: 270px;
      top: 80px;
      right: 0;
      bottom: 40px;
      padding: 20px;
      background: white;
      overflow-y: auto;
      z-index: 1;
    }

    footer {
      background: #283593;
      color: white;
      text-align: center;
      padding: 8px;
      font-size: 13px;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }
  </style>
</head>
<body>
<header>
  <h1>HỆ THỐNG QUẢN LÝ GIÁO DỤC</h1>
  <div class="user-info">
    <span class="username">
      <?php 
      // Hiển thị tên người dùng
      if (isset($_SESSION['user']['hoVaTen'])) {
          echo htmlspecialchars($_SESSION['user']['hoVaTen']);
      } elseif (isset($_SESSION['user']['ho_ten'])) {
          echo htmlspecialchars($_SESSION['user']['ho_ten']);
      } else {
          echo 'Người dùng';
      }
      ?>
    </span>
    <span class="separator">|</span>
    <a href="index.php?controller=auth&action=logout">
      <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
  </div>
</header>

<div class="container">