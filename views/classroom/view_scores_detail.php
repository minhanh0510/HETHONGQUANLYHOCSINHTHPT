<?php
// views/classroom/view_scores_detail.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">Bảng điểm học sinh</h1>
        <a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <?php if (isset($_SESSION['info'])): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> <?= $_SESSION['info']; unset($_SESSION['info']); ?>
        </div>
    <?php endif; ?>

    <!-- Thông tin học sinh -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
            <i class="fas fa-user"></i> Thông tin học sinh
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Mã học sinh:</strong>
                <div style="margin-top: 5px;">
                    <span class="student-id" style="font-size: 16px;"><?= htmlspecialchars($studentInfo['maHS']) ?></span>
                </div>
            </div>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Họ và tên:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50; font-weight: bold;">
                    <?= htmlspecialchars($studentInfo['hoVaTen']) ?>
                </div>
            </div>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Số báo danh:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['soBaoDanh']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chọn môn học -->
    <div class="filter-section" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50;">
            <i class="fas fa-filter"></i> Chọn môn học
        </h3>
        <form method="GET" class="form-row">
            <input type="hidden" name="controller" value="classroom">
            <input type="hidden" name="action" value="viewScores">
            <input type="hidden" name="maHS" value="<?= htmlspecialchars($_GET['maHS']) ?>">
            <input type="hidden" name="maLop" value="<?= htmlspecialchars($_GET['maLop']) ?>">
            
            <div class="form-col">
                <label class="form-label" for="maMon">Môn học:</label>
                <select name="maMon" id="maMon" class="form-control" required>
                    <option value="">-- Chọn môn học --</option>
                    <?php foreach ($subjectList as $subject): ?>
                        <option value="<?= $subject['maMon'] ?>" 
                                <?= (isset($_GET['maMon']) && $_GET['maMon'] === $subject['maMon']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject['tenMon']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Xem điểm
                </button>
            </div>
        </form>
    </div>

    <!-- Bảng điểm -->
    <?php if (!empty($_GET['maMon'])): ?>
        <?php if (empty($scores)): ?>
            <!-- Chưa có điểm -->
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <strong>Chưa có điểm cho môn học này</strong>
                <p style="margin: 5px 0 0 0;">Vui lòng liên hệ giáo viên bộ môn để cập nhật điểm.</p>
            </div>
        <?php else: ?>
            <!-- Thống kê điểm -->
            <?php
            $tongDiem = 0;
            $soDiem = count($scores);
            foreach ($scores as $score) {
                $tongDiem += $score['diemSo'];
            }
            $diemTB = $soDiem > 0 ? $tongDiem / $soDiem : 0;
            ?>
            
            <div class="dashboard-cards" style="margin-bottom: 20px;">
                <div class="dashboard-card">
                    <div class="card-icon" style="color: #3498db;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="card-title">Số lượng điểm</div>
                    <div class="card-value"><?= $soDiem ?></div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-icon" style="color: <?= $diemTB >= 8 ? '#2ecc71' : ($diemTB >= 6.5 ? '#3498db' : ($diemTB >= 5 ? '#f39c12' : '#e74c3c')) ?>;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-title">Điểm trung bình</div>
                    <div class="card-value" style="color: <?= $diemTB >= 8 ? '#2ecc71' : ($diemTB >= 6.5 ? '#3498db' : ($diemTB >= 5 ? '#f39c12' : '#e74c3c')) ?>;">
                        <?= number_format($diemTB, 2) ?>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-icon" style="color: #f39c12;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-title">Môn học</div>
                    <div class="card-value" style="font-size: 18px;"><?= htmlspecialchars($scores[0]['tenMon']) ?></div>
                </div>
            </div>

            <!-- Bảng điểm chi tiết -->
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px;">
                <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #2ecc71; padding-bottom: 10px;">
                    <i class="fas fa-chart-bar"></i> Bảng điểm chi tiết
                </h3>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">STT</th>
                                <th>Loại điểm</th>
                                <th style="width: 120px; text-align: center;">Điểm số</th>
                                <th style="width: 100px; text-align: center;">Học kỳ</th>
                                <th style="width: 120px; text-align: center;">Năm học</th>
                                <th style="width: 150px; text-align: center;">Ngày nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $loaiDiemMapping = [
                                'MiengTX' => 'Điểm miệng / TX',
                                'Giua15Phut' => 'Kiểm tra 15 phút',
                                'MotTiet' => 'Kiểm tra 1 tiết',
                                'GiuaKy' => 'Giữa kỳ',
                                'CuoiKy' => 'Cuối kỳ'
                            ];
                            
                            foreach ($scores as $index => $score): 
                                $badgeClass = 'status-info';
                                if ($score['diemSo'] >= 8) $badgeClass = 'status-success';
                                elseif ($score['diemSo'] >= 6.5) $badgeClass = 'status-info';
                                elseif ($score['diemSo'] >= 5) $badgeClass = 'status-warning';
                                else $badgeClass = 'status-danger';
                            ?>
                                <tr>
                                    <td style="text-align: center;"><?= $index + 1 ?></td>
                                    <td><?= $loaiDiemMapping[$score['loaiDiem']] ?? $score['loaiDiem'] ?></td>
                                    <td style="text-align: center;">
                                        <span class="status-badge <?= $badgeClass ?>" style="font-size: 14px; padding: 8px 15px;">
                                            <?= number_format($score['diemSo'], 2) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">HK <?= $score['hocKy'] ?></td>
                                    <td style="text-align: center;"><?= htmlspecialchars($score['namHoc']) ?></td>
                                    <td style="text-align: center;"><?= date('d/m/Y H:i', strtotime($score['ngayNhap'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include "views/layout/footer.php"; ?>