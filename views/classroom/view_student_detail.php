<?php
// views/classroom/view_student_detail.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";
?>

<div class="main-content">
    <div class="content-header">
        <h1 class="page-title">Hồ sơ học sinh</h1>
        <a href="index.php?controller=classroom&action=manage&maLop=<?= $_GET['maLop'] ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Thông tin cá nhân -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
            <i class="fas fa-user"></i> Thông tin cá nhân
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
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
                <strong style="color: #555;">Giới tính:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['gioiTinh']) ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Ngày sinh:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= date('d/m/Y', strtotime($studentInfo['ngaySinh'])) ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Số báo danh:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['soBaoDanh']) ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Trạng thái học:</strong>
                <div style="margin-top: 5px;">
                    <?php if ($studentInfo['dangThaiHocTap'] === 'Đang học'): ?>
                        <span class="status-badge status-success">Đang học</span>
                    <?php else: ?>
                        <span class="status-badge status-warning"><?= htmlspecialchars($studentInfo['dangThaiHocTap']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Lớp:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['tenLop']) ?> - <?= htmlspecialchars($studentInfo['tenKhoi']) ?> (<?= htmlspecialchars($studentInfo['tenBan']) ?>)
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Địa chỉ:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['diaChi']) ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Điện thoại:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['soDienThoai']) ?>
                </div>
            </div>
            
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <strong style="color: #555;">Email:</strong>
                <div style="margin-top: 5px; font-size: 16px; color: #2c3e50;">
                    <?= htmlspecialchars($studentInfo['email']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin phụ huynh -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #17a2b8; padding-bottom: 10px;">
            <i class="fas fa-users"></i> Thông tin phụ huynh
        </h3>
        
        <?php if (empty($parentInfo)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Chưa có thông tin phụ huynh
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                <?php foreach ($parentInfo as $parent): ?>
                    <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #17a2b8;">
                        <h4 style="color: #17a2b8; margin-bottom: 10px;"><?= htmlspecialchars($parent['quanHe']) ?></h4>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div>
                                <strong style="color: #555;">Họ tên:</strong>
                                <span style="color: #2c3e50; margin-left: 10px;"><?= htmlspecialchars($parent['hoVaTen']) ?></span>
                            </div>
                            <div>
                                <strong style="color: #555;">Giới tính:</strong>
                                <span style="color: #2c3e50; margin-left: 10px;"><?= htmlspecialchars($parent['gioiTinh']) ?></span>
                            </div>
                            <div>
                                <strong style="color: #555;">Nghề nghiệp:</strong>
                                <span style="color: #2c3e50; margin-left: 10px;"><?= htmlspecialchars($parent['ngheNghiep']) ?></span>
                            </div>
                            <div>
                                <strong style="color: #555;">Điện thoại:</strong>
                                <span style="color: #2c3e50; margin-left: 10px;"><?= htmlspecialchars($parent['soDienThoai']) ?></span>
                            </div>
                            <div>
                                <strong style="color: #555;">Email:</strong>
                                <span style="color: #2c3e50; margin-left: 10px;"><?= htmlspecialchars($parent['email']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kết quả học tập -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #2ecc71; padding-bottom: 10px;">
            <i class="fas fa-chart-line"></i> Kết quả học tập
        </h3>
        
        <?php if (empty($academicResults)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Chưa có kết quả học tập
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Môn học</th>
                            <th style="text-align: center;">Điểm miệng</th>
                            <th style="text-align: center;">15 phút</th>
                            <th style="text-align: center;">1 tiết</th>
                            <th style="text-align: center;">Giữa kỳ</th>
                            <th style="text-align: center;">Cuối kỳ</th>
                            <th style="text-align: center;">Học kỳ</th>
                            <th style="text-align: center;">Năm học</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($academicResults as $result): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($result['tenMon']) ?></strong></td>
                                <td style="text-align: center;"><?= $result['diemMieng'] ? number_format($result['diemMieng'], 2) : '-' ?></td>
                                <td style="text-align: center;"><?= $result['diem15Phut'] ? number_format($result['diem15Phut'], 2) : '-' ?></td>
                                <td style="text-align: center;"><?= $result['diem1Tiet'] ? number_format($result['diem1Tiet'], 2) : '-' ?></td>
                                <td style="text-align: center;"><?= $result['diemGiuaKy'] ? number_format($result['diemGiuaKy'], 2) : '-' ?></td>
                                <td style="text-align: center;"><?= $result['diemCuoiKy'] ? number_format($result['diemCuoiKy'], 2) : '-' ?></td>
                                <td style="text-align: center;">HK<?= $result['hocKy'] ?></td>
                                <td style="text-align: center;"><?= htmlspecialchars($result['namHoc']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hạnh kiểm -->
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 10px;">
            <i class="fas fa-star"></i> Hạnh kiểm
        </h3>
        
        <?php if (empty($conductInfo)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Chưa có đánh giá hạnh kiểm
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Học kỳ</th>
                            <th style="text-align: center;">Năm học</th>
                            <th style="text-align: center;">Xếp loại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conductInfo as $conduct): ?>
                            <tr>
                                <td style="text-align: center;">Học kỳ <?= $conduct['hocKy'] ?></td>
                                <td style="text-align: center;"><?= htmlspecialchars($conduct['namHoc']) ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    $badgeClass = 'status-info';
                                    if ($conduct['xepLoai'] === 'Tot') $badgeClass = 'status-success';
                                    elseif ($conduct['xepLoai'] === 'Kha') $badgeClass = 'status-info';
                                    elseif ($conduct['xepLoai'] === 'TrungBinh') $badgeClass = 'status-warning';
                                    elseif ($conduct['xepLoai'] === 'Yeu') $badgeClass = 'status-danger';
                                    ?>
                                    <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($conduct['xepLoai']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "views/layout/footer.php"; ?>