<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">📋 Danh sách phân công giảng dạy</h2>
    <div style="color: #718096; font-size: 0.9rem;">
        <a href="index.php?controller=statistics&action=dashboard" style="color: #667eea; text-decoration: none;">Thống kê</a> / Danh sách phân công giảng dạy
    </div>
</div>

    <!-- BỘ LỌC ĐƠN GIẢN -->
    <form method="GET" class="card mb-4 p-3">
        <input type="hidden" name="controller" value="statistics">
        <input type="hidden" name="action" value="assignmentList">

        <div class="row">
            <!-- Năm học -->
            <div class="col-md-3">
                <label>Năm học</label>
                <select name="nam_hoc" class="form-control">
                    <option value="">Tất cả năm học</option>
                    <?php foreach ($filterOptions['nam_hoc'] as $y): ?>
                        <option value="<?= $y['value'] ?>"
                            <?= (isset($_GET['nam_hoc']) && $_GET['nam_hoc'] == $y['value']) ? 'selected' : '' ?>>
                            <?= $y['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Học kỳ -->
            <div class="col-md-3">
                <label>Học kỳ</label>
                <select name="hoc_ky" class="form-control">
                    <option value="">Tất cả học kỳ</option>
                    <option value="1" <?= (isset($_GET['hoc_ky']) && $_GET['hoc_ky'] == '1') ? 'selected' : '' ?>>Học kỳ 1</option>
                    <option value="2" <?= (isset($_GET['hoc_ky']) && $_GET['hoc_ky'] == '2') ? 'selected' : '' ?>>Học kỳ 2</option>
                </select>
            </div>

            <!-- Tổ chuyên môn -->
            <div class="col-md-3">
                <label>Tổ chuyên môn</label>
                <select name="to_chuyen_mon" class="form-control">
                    <option value="">Tất cả tổ chuyên môn</option>
                    <?php foreach ($filterOptions['to_chuyen_mon'] as $t): ?>
                        <option value="<?= $t['value'] ?>"
                            <?= (isset($_GET['to_chuyen_mon']) && $_GET['to_chuyen_mon'] == $t['value']) ? 'selected' : '' ?>>
                            <?= $t['label'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nút lọc và xóa -->
            <div class="col-md-3 align-self-end d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-search"></i> Lọc danh sách
                </button>
                <a href="?controller=statistics&action=assignmentList" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Xóa lọc
                </a>
            </div>
        </div>
    </form>

    

    <!-- NÚT XUẤT EXCEL -->
    <?php if (!empty($assignments)): ?>
        <div class="mb-3">
            <?php 
            $exportParams = http_build_query([
                'controller' => 'statistics',
                'action' => 'export',
                'report_type' => 'assignment_list',
                'nam_hoc' => $_GET['nam_hoc'] ?? '',
                'hoc_ky' => $_GET['hoc_ky'] ?? '',
                'to_chuyen_mon' => $_GET['to_chuyen_mon'] ?? ''
            ]);
            ?>
            <a href="index.php?<?= $exportParams ?>" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
        </div>
    <?php endif; ?>

    <!-- BẢNG DỮ LIỆU -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($assignments)): ?>
                <div class="alert alert-warning text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>Không tìm thấy dữ liệu phân công</h5>
                    <p class="mb-0">Vui lòng thử lại với bộ lọc khác hoặc kiểm tra dữ liệu phân công trong hệ thống.</p>
                    <a href="?controller=statistics&action=assignmentList" class="btn btn-primary mt-3">
                        <i class="fas fa-redo"></i> Xem tất cả phân công
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="50">STT</th>
                                <th>Họ tên giáo viên</th>
                                <th>Tổ chuyên môn</th>
                                <th>Môn giảng dạy</th>
                                <th>Số lớp phụ trách</th>
                                <th>Các lớp phụ trách (khối)</th>
                                <th>Năm học</th>
                                <th>Học kỳ</th>
                                <th width="100">Tổng số tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; ?>
                            <?php foreach ($assignments as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $stt++ ?></td>
                                    <td><strong><?= htmlspecialchars($row['tenGiaoVien']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['toChuyenMon']) ?></td>
                                    <td><?= htmlspecialchars($row['cacMonDay']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?= $row['soLopPhuTrach'] ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($row['cacLopPhuTrach']) ?></small>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($row['namHoc']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $row['hocKy'] == 1 ? 'primary' : 'success' ?>">
                                            HK<?= $row['hocKy'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark"><?= $row['tongSoTiet'] ?> tiết</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- PHÂN TRANG HOẶC THỐNG KÊ TỔNG -->
                <div class="mt-3 text-end">
                    <span class="text-muted">
                        Hiển thị <strong><?= count($assignments) ?></strong> bản ghi phân công
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>