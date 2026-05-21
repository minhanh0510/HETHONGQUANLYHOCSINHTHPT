<?php
// views/student/assignment/detail.php

// KHỞI TẠO BIẾN
$baiTap = $baiTap ?? [];
$user = $user ?? [];
$displayName = $user['name'] ?? $user['hoVaTen'] ?? 'Học sinh';
$avatarText = 'HS';

$now = time();
$deadline = strtotime($baiTap['thoiHanNop']);
$isExpired = $deadline < $now;
$isUrgent = !$isExpired && ($deadline - $now) < 172800; // < 2 ngày
$canSubmit = !$isExpired && !$baiTap['daNop'] && $baiTap['trangThai'] === 'DangMo';
?>

<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_student.php"; ?>

<div class="main-content">
    <div class="content-header">
        <div class="page-title">
            <i class="fas fa-file-alt"></i> Chi tiết bài tập
        </div>
        <a href="index.php?controller=assignmentStudent&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Header thông tin bài tập -->
    <div class="card" style="background: linear-gradient(135deg, <?php 
        echo $baiTap['daNop'] ? '#10b981 0%, #059669 100%' : 
             ($isExpired ? '#ef4444 0%, #dc2626 100%' : 
             ($isUrgent ? '#f59e0b 0%, #d97706 100%' : 
             '#667eea 0%, #764ba2 100%'));
    ?>); color: white; padding: 25px; margin-bottom: 20px; border-radius: 10px;">
        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
            <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 15px; font-size: 13px;">
                <?php echo htmlspecialchars($baiTap['tenMon']); ?>
            </span>
            <?php if (strpos(strtolower($baiTap['tenBaiTap']), 'kiểm tra') !== false): ?>
                <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 15px; font-size: 13px;">
                    <i class="fas fa-file-alt"></i> Kiểm tra
                </span>
            <?php else: ?>
                <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 15px; font-size: 13px;">
                    <i class="fas fa-pencil-alt"></i> Bài tập
                </span>
            <?php endif; ?>
        </div>
        
        <h2 style="margin-bottom: 15px;"><?php echo htmlspecialchars($baiTap['tenBaiTap']); ?></h2>
        
        <div style="display: flex; gap: 25px; flex-wrap: wrap; font-size: 14px; opacity: 0.95;">
            <span><i class="fas fa-user"></i> GV: <?php echo htmlspecialchars($baiTap['tenGV']); ?></span>
            <span><i class="fas fa-chalkboard"></i> Lớp: <?php echo htmlspecialchars($baiTap['tenLop']); ?></span>
            <span><i class="fas fa-calendar"></i> Hạn nộp: <?php echo date('d/m/Y H:i', strtotime($baiTap['thoiHanNop'])); ?></span>
            <span>
                <i class="fas fa-clock"></i>
                <?php 
                if ($baiTap['daNop']) {
                    echo 'Đã nộp';
                } elseif ($isExpired) {
                    echo 'Đã hết hạn';
                } else {
                    $diff = $deadline - $now;
                    $days = floor($diff / 86400);
                    $hours = floor(($diff % 86400) / 3600);
                    if ($days > 0) {
                        echo "Còn {$days} ngày {$hours} giờ";
                    } elseif ($hours > 0) {
                        echo "Còn {$hours} giờ";
                    } else {
                        echo "Còn " . floor($diff / 60) . " phút";
                    }
                }
                ?>
            </span>
        </div>
    </div>

    <!-- Thông tin đã nộp -->
    <?php if ($baiTap['daNop']): ?>
        <div class="card" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 2px solid #10b981; padding: 25px; margin-bottom: 20px;">
            <h3 style="color: #065f46; margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i> Bạn đã nộp bài này
            </h3>
            <div style="color: #065f46; margin-bottom: 10px;">
                <strong>Thời gian nộp:</strong> <?php echo date('d/m/Y H:i', strtotime($baiTap['ngayNop'])); ?>
            </div>
            
            <?php if ($baiTap['trangThaiNop'] == 'DaCham' && $baiTap['diem'] !== null): ?>
                <div style="margin-top: 15px;">
                    <div style="font-size: 14px; color: #065f46;">Điểm số:</div>
                    <div style="font-size: 36px; font-weight: bold; color: #10b981;">
                        <?php echo number_format($baiTap['diem'], 1); ?>
                    </div>
                </div>
                
                <?php if (!empty($baiTap['nhanXet'])): ?>
                    <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #a7f3d0; margin-top: 15px;">
                        <div style="font-weight: 600; color: #065f46; margin-bottom: 8px;">Nhận xét của giáo viên:</div>
                        <div style="color: #374151;"><?php echo nl2br(htmlspecialchars($baiTap['nhanXet'])); ?></div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="margin-top: 10px; color: #92400e; background: white; padding: 10px; border-radius: 5px;">
                    <i class="fas fa-hourglass-half"></i> Bài làm đang chờ giáo viên chấm điểm
                </div>
            <?php endif; ?>

            <!-- Hiển thị bài làm đã nộp -->
            <?php if (!empty($baiTap['noiDungBaiLam'])): ?>
                <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #a7f3d0; margin-top: 15px;">
                    <div style="font-weight: 600; color: #065f46; margin-bottom: 8px;">Nội dung bài làm:</div>
                    <div style="color: #374151;"><?php echo nl2br(htmlspecialchars($baiTap['noiDungBaiLam'])); ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($baiTap['tenFile'])): ?>
                <div style="margin-top: 15px;">
                    <div style="font-weight: 600; color: #065f46; margin-bottom: 8px;">File đính kèm:</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f0fdf4; border: 1px solid #10b981; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-file" style="color: #10b981; font-size: 20px;"></i>
                            <div>
                                <div style="font-weight: 600; color: #374151;"><?php echo htmlspecialchars($baiTap['tenFile']); ?></div>
                                <div style="font-size: 12px; color: #6b7280;">
                                    <?php echo strtoupper($baiTap['loaiFile']); ?> • 
                                    <?php echo number_format($baiTap['kichThuocFile'] / 1024 / 1024, 2); ?> MB
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo htmlspecialchars($baiTap['duongDanFile']); ?>" 
                           class="btn btn-sm btn-primary" download>
                            <i class="fas fa-download"></i> Tải xuống
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Cảnh báo hết hạn -->
    <?php if ($isExpired && !$baiTap['daNop']): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Bài tập đã hết hạn!</strong><br>
            Bài tập này đã quá thời hạn nộp. Bạn không thể nộp bài được nữa.
            Vui lòng liên hệ giáo viên nếu có vấn đề đặc biệt.
        </div>
    <?php endif; ?>

    <!-- Cảnh báo sắp hết hạn -->
    <?php if ($isUrgent && !$isExpired && !$baiTap['daNop']): ?>
        <div class="alert alert-warning">
            <i class="fas fa-clock"></i>
            <strong>Sắp hết hạn!</strong><br>
            <?php 
            $diff = $deadline - $now;
            $hours = floor($diff / 3600);
            echo "Còn {$hours} giờ. Hãy hoàn thành và nộp bài sớm để tránh trễ hạn.";
            ?>
        </div>
    <?php endif; ?>

    <!-- Mô tả bài tập -->
    <div class="card" style="margin-bottom: 20px;">
        <h3><i class="fas fa-info-circle"></i> Mô tả bài tập</h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
            <?php echo nl2br(htmlspecialchars($baiTap['moTa'])); ?>
        </div>
    </div>

    <!-- Nội dung chi tiết -->
    <div class="card" style="margin-bottom: 20px;">
        <h3><i class="fas fa-file-alt"></i> Nội dung chi tiết</h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
            <?php echo nl2br(htmlspecialchars($baiTap['noiDung'])); ?>
        </div>
    </div>

    <!-- Form nộp bài -->
    <?php if ($canSubmit): ?>
        <div class="card">
            <h3><i class="fas fa-pencil-alt"></i> Nộp bài làm</h3>
            
            <form method="POST" action="index.php?controller=assignmentStudent&action=submit" enctype="multipart/form-data">
                <input type="hidden" name="maBaiTap" value="<?php echo $baiTap['maBaiTap']; ?>">
                
                <div class="form-group">
                    <label class="form-label">Nội dung bài làm</label>
                    <textarea name="noiDung" class="form-control" rows="10"
                              placeholder="Nhập nội dung bài làm của bạn..."></textarea>
                    <small style="color: #6b7280; margin-top: 5px; display: block;">
                        Bạn có thể nhập trực tiếp hoặc upload file đính kèm
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Tải lên file bài làm (không bắt buộc)</label>
                    <div class="file-upload-box" onclick="document.getElementById('fileUpload').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
                        <p style="margin: 10px 0; color: #6b7280;">Nhấn để chọn file hoặc kéo thả file vào đây</p>
                        <p style="font-size: 12px; color: #9ca3af;">Hỗ trợ: PDF, DOC, DOCX, JPG, PNG (tối đa 10MB)</p>
                    </div>
                    <input type="file" id="fileUpload" name="fileUpload" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                           style="display: none;" 
                           onchange="handleFileSelect(this)">
                    <div id="fileInfo" style="display: none; margin-top: 10px;"></div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-check"></i> Nộp bài
                    </button>
                    <a href="index.php?controller=assignmentStudent&action=index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.file-upload-box {
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.file-upload-box:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.file-upload-box.has-file {
    border-color: #10b981;
    background: #f0fdf4;
}
</style>

<script>
function handleFileSelect(input) {
    const fileUploadBox = document.querySelector('.file-upload-box');
    const fileInfo = document.getElementById('fileInfo');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        fileUploadBox.classList.add('has-file');
        fileInfo.style.display = 'block';
        fileInfo.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f0fdf4; border: 1px solid #10b981; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-file" style="color: #10b981; font-size: 20px;"></i>
                    <div>
                        <div style="font-weight: 600; color: #374151;">${file.name}</div>
                        <div style="font-size: 12px; color: #6b7280;">${fileSize} MB</div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger" onclick="clearFile()">
                    <i class="fas fa-times"></i> Xóa
                </button>
            </div>
        `;
    }
}

function clearFile() {
    document.getElementById('fileUpload').value = '';
    document.querySelector('.file-upload-box').classList.remove('has-file');
    document.getElementById('fileInfo').style.display = 'none';
}
</script>

<?php include "views/layout/footer.php"; ?>