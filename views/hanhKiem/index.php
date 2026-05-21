<?php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        overflow-x: hidden;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f7fa;
        margin: 0;
        padding: 0;
    }

    /* Main Content - Compact Height */
    .main-content {
        background: #f5f7fa;
        min-height: 100vh;
        padding: 16px;
        overflow-y: auto;
    }

    /* Fixed Header Section - Smaller */
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        background: white;
        padding: 16px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
    }

    .page-title i {
        color: #667eea;
        margin-right: 10px;
        font-size: 20px;
    }

    /* Class Info Card - Smaller */
    .class-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 16px 20px;
        color: white;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
        position: relative;
    }

    .class-info-card h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .class-info-meta {
        display: flex;
        gap: 16px;
        font-size: 13px;
        opacity: 0.95;
    }

    .class-info-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .class-info-meta i {
        font-size: 12px;
    }

    /* Stats Grid - Compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
        background: #f5f7fa;
        padding: 8px 0;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .stat-icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-number {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Filter Section - Compact */
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        position: sticky;
        top: 76px;
        z-index: 90;
    }

    .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 8px 14px 8px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }

    .form-select {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        background-color: white;
        transition: all 0.2s;
        height: 36px;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .count-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        height: 36px;
        display: flex;
        align-items: center;
    }

    /* Alerts - Smaller */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        border: none;
        position: relative;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-close {
        margin-left: auto;
        opacity: 0.5;
        cursor: pointer;
        font-size: 12px;
    }

    /* Table Container - Adjust Height */
    .table-container {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        margin-bottom: 16px;
        display: block;
        width: 100%;
        height: calc(100vh - 380px); /* Reduced height */
        min-height: 300px;
    }

    /* Table Wrapper */
    .table-wrapper {
        overflow: auto;
        display: block;
        width: 100%;
        height: 100%;
        scroll-behavior: smooth;
    }

    /* Cải thiện scrollbar */
    .table-wrapper::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f8fafc;
    }

    .data-table th {
        padding: 12px 10px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        background: #f8fafc;
        position: sticky;
        top: 0;
        z-index: 70;
    }

    .data-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
        vertical-align: middle;
        word-wrap: break-word;
    }

    .data-table tbody tr {
        transition: background-color 0.2s;
        height: 52px;
    }

    .data-table tbody tr:hover {
        background: #fafbfc;
    }

    /* Student Info - Compact */
    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 180px;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
        min-width: 140px;
        font-size: 13px;
    }

    /* Badges */
    .badge-xeploai {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    .badge-tot {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-kha {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-trungbinh {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-yeu {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-chuanhap {
        background: #f1f5f9;
        color: #475569;
    }

    /* Buttons - Smaller */
    .btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #64748b;
        color: white;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        white-space: nowrap;
    }

    .btn i {
        font-size: 12px;
    }

    /* Column widths - Compact */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
        width: 50px;
        min-width: 50px;
    }
    
    .data-table th:nth-child(2),
    .data-table td:nth-child(2) {
        min-width: 200px;
        width: auto;
    }
    
    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
        width: 70px;
        min-width: 70px;
    }
    
    .data-table th:nth-child(4),
    .data-table td:nth-child(4) {
        width: 90px;
        min-width: 90px;
    }
    
    .data-table th:nth-child(5),
    .data-table td:nth-child(5) {
        width: 100px;
        min-width: 100px;
    }
    
    .data-table th:nth-child(6),
    .data-table td:nth-child(6) {
        min-width: 180px;
        width: auto;
    }
    
    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
        width: 100px;
        min-width: 100px;
    }

    /* Empty State - Smaller */
    .empty-state {
        padding: 40px 16px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    /* Scroll to top button - Smaller */
    #scrollToTopBtn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        border: none;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        z-index: 1000;
        transition: all 0.3s;
    }

    #scrollToTopBtn:hover {
        background: #5568d3;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(102, 126, 234, 0.4);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .main-content {
            padding: 14px;
        }
        
        .table-container {
            height: calc(100vh - 360px);
        }
    }

    @media (max-width: 992px) {
        .filter-card {
            position: static !important;
            top: auto;
        }
        
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .filter-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .data-table th:nth-child(3),
        .data-table td:nth-child(3),
        .data-table th:nth-child(4),
        .data-table td:nth-child(4) {
            display: none;
        }
        
        .student-info {
            min-width: 160px;
        }
        
        .data-table th:nth-child(2),
        .data-table td:nth-child(2) {
            min-width: 160px;
        }
        
        .data-table th:nth-child(6),
        .data-table td:nth-child(6) {
            min-width: 140px;
        }
        
        .table-container {
            height: calc(100vh - 340px);
        }
        
        .data-table tbody tr {
            height: 48px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 12px;
        }
        
        .content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
        }
        
        .page-title {
            font-size: 20px;
        }
        
        .page-title i {
            font-size: 18px;
            margin-right: 8px;
        }
        
        .class-info-card {
            padding: 14px;
        }
        
        .class-info-card h2 {
            font-size: 18px;
        }
        
        .class-info-meta {
            font-size: 12px;
            gap: 12px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .stat-card {
            padding: 12px;
            gap: 10px;
        }
        
        .stat-icon-wrapper {
            width: 36px;
            height: 36px;
            font-size: 18px;
        }
        
        .stat-number {
            font-size: 18px;
        }
        
        .stat-label {
            font-size: 11px;
        }
        
        .filter-card {
            padding: 12px;
        }
        
        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 12px;
        }
        
        .data-table th {
            font-size: 11px;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }
        
        .data-table {
            min-width: 600px;
        }
        
        .table-container {
            height: calc(100vh - 320px);
            min-height: 280px;
        }
        
        .data-table tbody tr {
            height: 44px;
        }
        
        #scrollToTopBtn {
            width: 36px;
            height: 36px;
            font-size: 14px;
            bottom: 16px;
            right: 16px;
        }
        
        .student-avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        
        .student-name {
            font-size: 12px;
            min-width: 130px;
        }
    }

    @media (max-width: 576px) {
        .main-content {
            padding: 10px;
        }
        
        .content-header {
            margin-bottom: 12px;
        }
        
        .class-info-card h2 {
            font-size: 16px;
        }
        
        .class-info-meta {
            flex-direction: column;
            gap: 6px;
            font-size: 11px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .empty-state {
            padding: 32px 12px;
        }
        
        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
        }
        
        .empty-state p {
            font-size: 13px;
        }
        
        .data-table {
            min-width: 550px;
        }
        
        .table-container {
            height: calc(100vh - 300px);
            min-height: 250px;
        }
        
        .data-table tbody tr {
            height: 40px;
        }
        
        .badge-xeploai {
            padding: 3px 8px;
            font-size: 10px;
        }
    }

    @media (max-width: 400px) {
        .main-content {
            padding: 8px;
        }
        
        .table-container {
            height: calc(100vh - 280px);
            min-height: 220px;
        }
        
        .data-table th:nth-child(6),
        .data-table td:nth-child(6) {
            display: none;
        }
    }
</style>

<div class="main-content">
    <!-- Page Header -->
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-clipboard-check"></i> Xếp Loại Hạnh Kiểm
        </h1>
        <a href="index.php?controller=teacher&action=dashboard" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Class Info -->
    <div class="class-info-card">
        <h2><?= htmlspecialchars($lopChuNhiem['tenLop']) ?></h2>
        <div class="class-info-meta">
            <span><i class="fas fa-calendar-alt"></i> Năm học <?= htmlspecialchars($namHoc) ?></span>
            <span><i class="fas fa-book"></i> Học kỳ <?= htmlspecialchars($hocKy) ?></span>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <?php
        $tongHS = count($danhSachHS);
        $soTot = 0;
        $soKha = 0;
        $soTrungBinh = 0;
        $soYeu = 0;
        $chuaNhap = 0;
        
        foreach ($thongKe as $tk) {
            switch ($tk['xepLoai']) {
                case 'Tot':
                    $soTot = $tk['soLuong'];
                    break;
                case 'Kha':
                    $soKha = $tk['soLuong'];
                    break;
                case 'TrungBinh':
                    $soTrungBinh = $tk['soLuong'];
                    break;
                case 'Yeu':
                    $soYeu = $tk['soLuong'];
                    break;
            }
        }
        $chuaNhap = $tongHS - ($soTot + $soKha + $soTrungBinh + $soYeu);
        ?>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #2563eb;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #2563eb; margin-left: 30px"><?= $tongHS ?></div>
                <div class="stat-label">Tổng học sinh</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #059669; margin-left: 3px"><?= $soTot ?></div>
                <div class="stat-label">Tốt</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #dbeafe; color: #1d4ed8;">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #1d4ed8; margin-left: 3px"><?= $soKha ?></div>
                <div class="stat-label">Khá</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fef3c7; color: #b45309;">
                <i class="fas fa-hand-paper"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #b45309; margin-left: 22px"><?= $soTrungBinh ?></div>
                <div class="stat-label">Trung bình</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #dc2626; margin-left: 4px"><?= $soYeu ?></div>
                <div class="stat-label">Yếu</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number" style="color: #64748b; margin-left: 24px"><?= $chuaNhap ?></div>
                <div class="stat-label">Chưa nhập</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <div class="filter-row">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên học sinh...">
            </div>
            <select id="filterXepLoai" class="form-select">
                <option value="">Tất cả xếp loại</option>
                <option value="Tot">Tốt</option>
                <option value="Kha">Khá</option>
                <option value="TrungBinh">Trung bình</option>
                <option value="Yeu">Yếu</option>
                <option value="ChuaNhap">Chưa nhập</option>
            </select>
            <select id="filterHocKy" class="form-select" onchange="location.href='index.php?controller=hanhKiem&action=index&hocKy=' + this.value + '&namHoc=<?= $namHoc ?>'">
                <option value="1" <?= $hocKy == 1 ? 'selected' : '' ?>>Học kỳ 1</option>
                <option value="2" <?= $hocKy == 2 ? 'selected' : '' ?>>Học kỳ 2</option>
            </select>
            <div class="count-badge">
                <span id="displayCount"><?= $tongHS ?></span> / <?= $tongHS ?> HS
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible">
            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper" id="tableWrapper">
            <table class="data-table" id="studentTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Học sinh</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Xếp loại</th>
                        <th>Nhận xét</th>
                        <th style="text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($danhSachHS)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Không có học sinh nào trong lớp</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($danhSachHS as $index => $hs): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            <?= strtoupper(mb_substr($hs['hoVaTen'], 0, 1, 'UTF-8')) ?>
                                        </div>
                                        <span class="student-name"><?= htmlspecialchars($hs['hoVaTen']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($hs['gioiTinh'] == 'Nam'): ?>
                                        <i class="fas fa-mars" style="color: #3b82f6;"></i> Nam
                                    <?php else: ?>
                                        <i class="fas fa-venus" style="color: #ec4899;"></i> Nữ
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($hs['ngaySinh'])) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'badge-chuanhap';
                                    $xepLoaiText = 'Chưa nhập';
                                    
                                    if ($hs['xepLoai']) {
                                        switch ($hs['xepLoai']) {
                                            case 'Tot':
                                                $badgeClass = 'badge-tot';
                                                $xepLoaiText = 'Tốt';
                                                break;
                                            case 'Kha':
                                                $badgeClass = 'badge-kha';
                                                $xepLoaiText = 'Khá';
                                                break;
                                            case 'TrungBinh':
                                                $badgeClass = 'badge-trungbinh';
                                                $xepLoaiText = 'Trung bình';
                                                break;
                                            case 'Yeu':
                                                $badgeClass = 'badge-yeu';
                                                $xepLoaiText = 'Yếu';
                                                break;
                                        }
                                    }
                                    ?>
                                    <span class="badge badge-xeploai <?= $badgeClass ?>">
                                        <?= $xepLoaiText ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($hs['nhanXet']): ?>
                                        <span style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                              title="<?= htmlspecialchars($hs['nhanXet']) ?>">
                                            <?= htmlspecialchars($hs['nhanXet']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-style: italic;">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($hs['maHK']): ?>
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="editHanhKiem('<?= $hs['maHS'] ?>', '<?= $hs['maHK'] ?>')">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="addHanhKiem('<?= $hs['maHS'] ?>')">
                                            <i class="fas fa-plus"></i> Nhập
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form (giữ nguyên) -->
<div class="modal fade" id="hanhKiemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check"></i>
                    <span id="modalTitle">Nhập hạnh kiểm</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="hanhKiemForm">
                    <input type="hidden" id="maHS" name="maHS">
                    <input type="hidden" id="maHK" name="maHK">
                    <input type="hidden" name="hocKy" value="<?= $hocKy ?>">
                    <input type="hidden" name="namHoc" value="<?= $namHoc ?>">
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-star" style="color: #f59e0b;"></i>
                            Xếp loại hạnh kiểm <span style="color: #dc2626;">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="xepLoai" id="xepLoaiTot" value="Tot" required>
                                    <label class="form-check-label" for="xepLoaiTot">
                                        <span class="badge badge-xeploai badge-tot">Tốt</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="xepLoai" id="xepLoaiKha" value="Kha" required>
                                    <label class="form-check-label" for="xepLoaiKha">
                                        <span class="badge badge-xeploai badge-kha">Khá</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="xepLoai" id="xepLoaiTB" value="TrungBinh" required>
                                    <label class="form-check-label" for="xepLoaiTB">
                                        <span class="badge badge-xeploai badge-trungbinh">Trung bình</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="xepLoai" id="xepLoaiYeu" value="Yeu" required>
                                    <label class="form-check-label" for="xepLoaiYeu">
                                        <span class="badge badge-xeploai badge-yeu">Yếu</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nhanXet" class="form-label">
                            <i class="fas fa-comment-dots" style="color: #3b82f6;"></i>
                            Nhận xét
                        </label>
                        <textarea class="form-control" id="nhanXet" name="nhanXet" rows="4" 
                                  placeholder="Nhập nhận xét về hạnh kiểm, thái độ học tập, ý thức kỷ luật..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="fas fa-save"></i> Lưu lại
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let modal;
    let modalElement;
    
    document.addEventListener('DOMContentLoaded', function() {
        modalElement = document.getElementById('hanhKiemModal');
        
        // Khởi tạo Bootstrap Modal khi DOM đã sẵn sàng
        if (typeof bootstrap !== 'undefined') {
            modal = new bootstrap.Modal(modalElement);
        }
        
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('filterXepLoai').addEventListener('change', filterTable);
        
        // Thêm nút scroll to top
        addScrollToTopButton();
        
        // Thêm sự kiện scroll để hiển thị/ẩn nút scroll to top
        const tableWrapper = document.getElementById('tableWrapper');
        if (tableWrapper) {
            tableWrapper.addEventListener('scroll', toggleScrollToTopButton);
        }
    });
    
    function addHanhKiem(maHS) {
        document.getElementById('modalTitle').textContent = 'Nhập hạnh kiểm';
        document.getElementById('hanhKiemForm').reset();
        document.getElementById('maHS').value = maHS;
        document.getElementById('maHK').value = '';
        
        // Hiển thị modal
        showModal();
    }
    
    async function editHanhKiem(maHS, maHK) {
        try {
            const response = await fetch(`index.php?controller=hanhKiem&action=form&maHS=${maHS}&hocKy=<?= $hocKy ?>&namHoc=<?= $namHoc ?>`);
            
            // Kiểm tra response
            if (!response.ok) {
                throw new Error('Không thể tải dữ liệu từ server');
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Reset form trước
                document.getElementById('hanhKiemForm').reset();
                
                // Cập nhật tiêu đề
                document.getElementById('modalTitle').textContent = 'Chỉnh sửa hạnh kiểm';
                
                // Set hidden fields
                document.getElementById('maHS').value = maHS;
                document.getElementById('maHK').value = maHK;
                
                // Set radio button xếp loại
                const radioButton = document.querySelector(`input[name="xepLoai"][value="${data.xepLoai}"]`);
                if (radioButton) {
                    radioButton.checked = true;
                } else {
                    console.error('Không tìm thấy radio button cho xếp loại:', data.xepLoai);
                }
                
                // Set nhận xét
                document.getElementById('nhanXet').value = data.nhanXet || '';
                
                // Hiển thị modal
                showModal();
                
            } else {
                throw new Error(data.message || 'Không thể tải dữ liệu');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu: ' + error.message);
        }
    }
    
    // Helper function để show modal
    function showModal() {
        if (modal && typeof modal.show === 'function') {
            modal.show();
        } else if (typeof bootstrap !== 'undefined') {
            modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback: Hiển thị modal bằng cách thêm class trực tiếp
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
            
            // Tạo backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'customBackdrop';
            document.body.appendChild(backdrop);
        }
    }
    
    // Helper function để hide modal
    function hideModal() {
        if (modal && typeof modal.hide === 'function') {
            modal.hide();
        } else {
            // Fallback: Ẩn modal bằng cách xóa class
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            
            // Xóa backdrop
            const backdrop = document.getElementById('customBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }
    
    async function submitForm() {
        const form = document.getElementById('hanhKiemForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const formData = new FormData(form);
        const maHK = document.getElementById('maHK').value;
        const action = maHK ? 'capNhat' : 'them';
        
        try {
            const response = await fetch(`index.php?controller=hanhKiem&action=${action}`, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                hideModal();
                location.reload();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }
    
    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const filterXepLoai = document.getElementById('filterXepLoai').value;
        const table = document.getElementById('studentTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        let visibleCount = 0;
        
        for (let row of rows) {
            const name = row.cells[1]?.textContent.toLowerCase() || '';
            const xepLoai = row.cells[4]?.textContent.trim() || '';
            
            let showRow = true;
            
            if (searchValue && !name.includes(searchValue)) {
                showRow = false;
            }
            
            if (filterXepLoai) {
                if (filterXepLoai === 'ChuaNhap' && xepLoai !== 'Chưa nhập') {
                    showRow = false;
                } else if (filterXepLoai !== 'ChuaNhap') {
                    const xepLoaiMap = {
                        'Tot': 'Tốt',
                        'Kha': 'Khá',
                        'TrungBinh': 'Trung bình',
                        'Yeu': 'Yếu'
                    };
                    if (xepLoai !== xepLoaiMap[filterXepLoai]) {
                        showRow = false;
                    }
                }
            }
            
            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        }
        
        document.getElementById('displayCount').textContent = visibleCount;
    }
    
    // Thêm nút scroll to top
    function addScrollToTopButton() {
        // Kiểm tra xem nút đã tồn tại chưa
        if (document.getElementById('scrollToTopBtn')) {
            return;
        }
        
        const scrollBtn = document.createElement('button');
        scrollBtn.id = 'scrollToTopBtn';
        scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollBtn.title = 'Lên đầu trang';
        scrollBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
            z-index: 1000;
            transition: all 0.3s;
        `;
        
        scrollBtn.onclick = scrollToTop;
        
        document.body.appendChild(scrollBtn);
    }
    
    // Hiển thị/ẩn nút scroll to top
    function toggleScrollToTopButton() {
        const tableWrapper = document.getElementById('tableWrapper');
        const scrollBtn = document.getElementById('scrollToTopBtn');
        
        if (tableWrapper && scrollBtn) {
            if (tableWrapper.scrollTop > 100) {
                scrollBtn.style.display = 'flex';
            } else {
                scrollBtn.style.display = 'none';
            }
        }
    }
    
    // Cuộn lên đầu trang
    function scrollToTop() {
        const tableWrapper = document.getElementById('tableWrapper');
        if (tableWrapper) {
            tableWrapper.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
</script>

<?php include "views/layout/footer.php"; ?>