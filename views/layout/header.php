<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hệ thống Quản lý Giáo dục</title>
  <link rel="stylesheet" href="assets/css/style.css">

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
    }

    header h1 { font-size: 20px; margin: 0; }
    header .user-info a { color: #fff; text-decoration: underline; }

    .container {
      display: flex;
      flex: 1;
      margin-top: 60px; /* để header không đè sidebar */
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
      z-index: 9999;
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

    .sidebar a:hover { background: #334155; }

    .main-content {
      flex: 1;
      margin-left: 270px;
      padding: 20px;
      background: white;
      overflow-y: auto;
      min-height: 100vh;
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
    }
  </style>
</head>
<body>
<header>
  <h1>HỆ THỐNG QUẢN LÝ GIÁO DỤC</h1>
  <div class="user-info">
    <?= $_SESSION['user']['ho_ten'] ?? 'Admin'; ?> |
    <a href="index.php?controller=auth&action=logout">Đăng xuất</a>
  </div>
</header>

<div class="container">
