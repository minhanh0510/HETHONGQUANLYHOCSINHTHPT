<!-- FILE: views/teacher/assignment/submission_detail.php -->
<?php
$pageTitle = "Chi tiết bài nộp";
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";

// Kiểm tra dữ liệu submission
if (!isset($submission) || empty($submission)) {
    echo '<div class="alert alert-danger">Không tìm thấy dữ liệu bài nộp!</div>';
    include "views/layout/footer.php";
    exit;
}

// Hàm format file size
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Hàm lấy icon theo loại file
function getFileIcon($loaiFile) {
    $icons = [
        'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => '#dc3545'],
        'doc' => ['icon' => 'fas fa-file-word', 'color' => '#2b579a'],
        'docx' => ['icon' => 'fas fa-file-word', 'color' => '#2b579a'],
        'xls' => ['icon' => 'fas fa-file-excel', 'color' => '#217346'],
        'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => '#217346'],
        'ppt' => ['icon' => 'fas fa-file-powerpoint', 'color' => '#d24726'],
        'pptx' => ['icon' => 'fas fa-file-powerpoint', 'color' => '#d24726'],
        'jpg' => ['icon' => 'fas fa-file-image', 'color' => '#17a2b8'],
        'jpeg' => ['icon' => 'fas fa-file-image', 'color' => '#17a2b8'],
        'png' => ['icon' => 'fas fa-file-image', 'color' => '#17a2b8'],
        'gif' => ['icon' => 'fas fa-file-image', 'color' => '#17a2b8'],
        'zip' => ['icon' => 'fas fa-file-archive', 'color' => '#6f42c1'],
        'rar' => ['icon' => 'fas fa-file-archive', 'color' => '#6f42c1'],
        'txt' => ['icon' => 'fas fa-file-alt', 'color' => '#6c757d']
    ];
    
    return isset($icons[$loaiFile]) ? $icons[$loaiFile] : ['icon' => 'fas fa-file', 'color' => '#6c757d'];
}

// Xử lý đường dẫn file
$hasFile = !empty($submission['tenFile']) && !empty($submission['duongDanFile']);
$files = [];
if ($hasFile) {
    $fileNames = explode('|', $submission['tenFile']);
    $filePaths = explode('|', $submission['duongDanFile']);
    $fileCount = min(count($fileNames), count($filePaths));
    
    for ($i = 0; $i < $fileCount; $i++) {
        $filePath = trim($filePaths[$i]);
        
        if (strpos($filePath, 'http') !== 0) {
            if (strpos($filePath, 'uploads/') === 0) {
                $fullPath = $filePath;
            } else if (strpos($filePath, '/') === 0) {
                $fullPath = ltrim($filePath, '/');
            } else {
                $fullPath = 'uploads/submissions/' . $filePath;
            }
        } else {
            $fullPath = $filePath;
        }
        
        $files[] = [
            'name' => trim($fileNames[$i]),
            'path' => $fullPath
        ];
    }
}
?>

<main class="main-content">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">
                                <i class="fas fa-file-alt me-2"></i>Chi tiết bài nộp
                            </h3>
                            <p class="text-muted mb-0">
                                <i class="fas fa-user-graduate me-2"></i>
                                <?= htmlspecialchars($submission['tenHocSinh'] ?? 'N/A') ?>
                            </p>
                        </div>
                        <a href="index.php?controller=assignmentTeacher&action=danhsachbainop&id=<?= htmlspecialchars($submission['maBaiTap'] ?? '') ?>" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show modern-alert">
                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show modern-alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Thông tin tổng quan -->
                <div class="row g-4 mb-4">
                    <!-- Thông tin bài tập -->
                    <div class="col-lg-6">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-book me-2"></i>Thông tin bài tập
                            </div>
                            <div class="info-card-body">
                                <div class="info-item">
                                    <div class="info-label">Tên bài tập</div>
                                    <div class="info-value"><?= htmlspecialchars($submission['tenBaiTap'] ?? 'N/A') ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Môn học</div>
                                    <div class="info-value">
                                        <span class="badge-custom badge-info">
                                            <i class="fas fa-book-open me-1"></i><?= htmlspecialchars($submission['tenMon'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Lớp</div>
                                    <div class="info-value">
                                        <span class="badge-custom badge-secondary">
                                            <i class="fas fa-users me-1"></i><?= htmlspecialchars($submission['tenLop'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Thời hạn nộp</div>
                                    <div class="info-value">
                                        <?php if (!empty($submission['thoiHanNop'])): ?>
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <?= date('d/m/Y H:i', strtotime($submission['thoiHanNop'])) ?>
                                            <?php if (strtotime($submission['thoiHanNop']) < time()): ?>
                                                <span class="badge-custom badge-danger ms-2">Đã quá hạn</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin học sinh -->
                    <div class="col-lg-6">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-user-graduate me-2"></i>Thông tin học sinh
                            </div>
                            <div class="info-card-body">
                                <div class="info-item">
                                    <div class="info-label">Họ và tên</div>
                                    <div class="info-value fw-bold"><?= htmlspecialchars($submission['tenHocSinh'] ?? 'N/A') ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Mã học sinh</div>
                                    <div class="info-value"><?= htmlspecialchars($submission['maHS'] ?? 'N/A') ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Ngày nộp</div>
                                    <div class="info-value">
                                        <?php if (!empty($submission['ngayNop'])): ?>
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            <?= date('d/m/Y H:i', strtotime($submission['ngayNop'])) ?>
                                            <?php 
                                            if (!empty($submission['thoiHanNop'])) {
                                                $thoiHanNop = strtotime($submission['thoiHanNop']);
                                                $ngayNop = strtotime($submission['ngayNop']);
                                            ?>
                                            <br>
                                            <?php if ($ngayNop > $thoiHanNop): ?>
                                                <span class="badge-custom badge-warning mt-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Nộp trễ
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-custom badge-success mt-2">
                                                    <i class="fas fa-check me-1"></i>Đúng hạn
                                                </span>
                                            <?php endif; ?>
                                            <?php } ?>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa nộp</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="info-item mb-0">
                                    <div class="info-label">Trạng thái</div>
                                    <div class="info-value">
                                        <?php 
                                        $trangThai = $submission['trangThai'] ?? 'ChuaNop';
                                        if ($trangThai === 'DaCham'): 
                                        ?>
                                            <span class="badge-custom badge-success">
                                                <i class="fas fa-check-circle me-1"></i>Đã chấm
                                            </span>
                                        <?php elseif ($trangThai === 'DaNop'): ?>
                                            <span class="badge-custom badge-warning">
                                                <i class="fas fa-clock me-1"></i>Chờ chấm điểm
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-secondary">
                                                <i class="fas fa-info-circle me-1"></i>Chưa nộp
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Đề bài -->
                <div class="content-card mb-4">
                    <div class="content-card-header bg-info">
                        <i class="fas fa-clipboard-list me-2"></i>Đề bài / Yêu cầu bài tập
                    </div>
                    <div class="content-card-body">
                        <?php 
                        if (!empty($submission['moTaBaiTap'])) {
                            echo nl2br(htmlspecialchars($submission['moTaBaiTap']));
                        } else {
                            echo '<p class="text-muted mb-0"><i class="fas fa-info-circle me-2"></i>Không có mô tả chi tiết</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- File đính kèm -->
                <?php if ($hasFile): ?>
                <div class="content-card mb-4">
                    <div class="content-card-header bg-warning">
                        <i class="fas fa-paperclip me-2"></i>File đính kèm (<?= count($files) ?> file)
                    </div>
                    <div class="content-card-body">
                        <div class="files-grid">
                            <?php foreach ($files as $index => $file): 
                                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                $fileInfo = getFileIcon($fileExt);
                                $fileSize = $submission['kichThuocFile'] ?? 0;
                                if (count($files) > 1) {
                                    $fileSize = round($fileSize / count($files));
                                }
                            ?>
                            <div class="file-card">
                                <div class="file-card-icon" style="color: <?= $fileInfo['color'] ?>">
                                    <i class="<?= $fileInfo['icon'] ?>"></i>
                                </div>
                                <div class="file-card-body">
                                    <div class="file-card-name" title="<?= htmlspecialchars($file['name']) ?>">
                                        <?= htmlspecialchars($file['name']) ?>
                                    </div>
                                    <div class="file-card-meta">
                                        <span class="file-meta-item">
                                            <i class="fas fa-tag me-1"></i><?= strtoupper($fileExt) ?>
                                        </span>
                                        <?php if ($fileSize > 0): ?>
                                        <span class="file-meta-item">
                                            <i class="fas fa-hdd me-1"></i><?= formatFileSize($fileSize) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="file-card-actions">
                                    <?php if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'pdf'])): ?>
                                    <button type="button" class="btn-icon btn-icon-primary" 
                                            onclick="previewFile('<?= htmlspecialchars($file['path'], ENT_QUOTES) ?>', '<?= $fileExt ?>', '<?= htmlspecialchars($file['name'], ENT_QUOTES) ?>')"
                                            title="Xem trước">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php endif; ?>
                                    <a href="<?= htmlspecialchars($file['path']) ?>" 
                                       class="btn-icon btn-icon-success" 
                                       download="<?= htmlspecialchars($file['name']) ?>"
                                       target="_blank"
                                       title="Tải về">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Bài làm của học sinh -->
                <div class="content-card mb-4">
                    <div class="content-card-header bg-success">
                        <i class="fas fa-pencil-alt me-2"></i>Bài làm của học sinh
                        <?php if (!$hasFile && empty($submission['noiDung'])): ?>
                            <span class="badge bg-warning text-dark ms-2">Chưa có nội dung</span>
                        <?php elseif ($hasFile): ?>
                            <span class="badge bg-light text-dark ms-2">Có file đính kèm</span>
                        <?php endif; ?>
                    </div>
                    <div class="content-card-body">
                        <?php 
                        if (!empty($submission['noiDung'])) {
                            echo nl2br(htmlspecialchars($submission['noiDung']));
                        } elseif ($hasFile) {
                            echo '<div class="empty-state">';
                            echo '<i class="fas fa-file-alt fa-3x text-success mb-3"></i>';
                            echo '<p class="mb-1"><strong>Học sinh đã nộp file đính kèm</strong></p>';
                            echo '<small class="text-muted">Xem file bên trên để kiểm tra bài làm</small>';
                            echo '</div>';
                        } else {
                            echo '<div class="empty-state">';
                            echo '<i class="fas fa-inbox fa-3x text-muted mb-3"></i>';
                            echo '<p class="mb-1"><strong>Học sinh chưa nhập nội dung bài làm</strong></p>';
                            echo '<small class="text-muted">Bài làm sẽ hiển thị tại đây khi học sinh nộp bài</small>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Form chấm điểm -->
                <div class="grading-card">
                    <div class="grading-card-header">
                        <i class="fas fa-star me-2"></i>Chấm điểm và nhận xét
                    </div>
                    
                    <?php if (($submission['trangThai'] ?? '') === 'DaCham'): ?>
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <strong>Bài này đã được chấm điểm</strong><br>
                                <small>Bạn có thể cập nhật lại điểm và nhận xét nếu cần</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=assignmentTeacher&action=gradeSubmission" 
                          method="POST" 
                          id="formChamDiem" 
                          class="needs-validation" 
                          novalidate>
                        <input type="hidden" name="maBaoCao" value="<?= htmlspecialchars($submission['maBaoCao'] ?? '') ?>">
                        
                        <div class="row g-4">
                            <!-- Điểm số -->
                            <div class="col-lg-3">
                                <div class="score-section">
                                    <label class="form-label fw-bold mb-3">
                                        <i class="fas fa-award text-warning me-2"></i>Điểm số
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control form-control-lg text-center score-input" 
                                           id="diem" 
                                           name="diem" 
                                           min="0" 
                                           max="10" 
                                           step="0.25"
                                           value="<?= htmlspecialchars($submission['diem'] ?? '') ?>"
                                           placeholder="0.00"
                                           required>
                                    <div class="invalid-feedback text-center">
                                        Vui lòng nhập điểm từ 0 đến 10
                                    </div>
                                    <small class="d-block text-center text-muted mt-2">
                                        Thang điểm: 0 - 10 (bước 0.25)
                                    </small>
                                    
                                    <!-- Gợi ý mức điểm -->
                                    <div class="mt-4">
                                        <small class="text-muted fw-bold d-block mb-2">
                                            <i class="fas fa-lightbulb me-1"></i>Gợi ý mức điểm
                                        </small>
                                        <div class="quick-scores">
                                            <button type="button" class="quick-score-btn excellent" onclick="setDiem(10)">
                                                <i class="fas fa-star"></i>
                                                <span>10</span>
                                                <small>Xuất sắc</small>
                                            </button>
                                            <button type="button" class="quick-score-btn good" onclick="setDiem(8.5)">
                                                <i class="fas fa-medal"></i>
                                                <span>8.5</span>
                                                <small>Giỏi</small>
                                            </button>
                                            <button type="button" class="quick-score-btn average" onclick="setDiem(7)">
                                                <i class="fas fa-thumbs-up"></i>
                                                <span>7</span>
                                                <small>Khá</small>
                                            </button>
                                            <button type="button" class="quick-score-btn fair" onclick="setDiem(5.5)">
                                                <i class="fas fa-check"></i>
                                                <span>5.5</span>
                                                <small>TB</small>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nhận xét -->
                            <div class="col-lg-9">
                                <div class="comment-section">
                                    <label class="form-label fw-bold mb-3">
                                        <i class="fas fa-comment-alt text-info me-2"></i>Nhận xét chi tiết
                                    </label>
                                    <textarea class="form-control comment-textarea" 
                                              id="nhanXet" 
                                              name="nhanXet" 
                                              rows="8" 
                                              placeholder="Nhập nhận xét chi tiết về bài làm của học sinh...

📌 Những điểm làm tốt
📌 Những điểm cần cải thiện  
📌 Gợi ý học tập"><?= htmlspecialchars($submission['nhanXet'] ?? '') ?></textarea>
                                    
                                    <!-- Gợi ý nhận xét -->
                                    <div class="mt-3">
                                        <small class="text-muted fw-bold d-block mb-2">
                                            <i class="fas fa-magic me-1"></i>Nhận xét mẫu
                                        </small>
                                        <div class="comment-suggestions">
                                            <button type="button" class="suggestion-btn" onclick="addNhanXet('✅ Bài làm tốt, trình bày rõ ràng.')">
                                                <i class="fas fa-plus"></i> Tốt
                                            </button>
                                            <button type="button" class="suggestion-btn" onclick="addNhanXet('📝 Cần cải thiện cách trình bày.')">
                                                <i class="fas fa-plus"></i> Cải thiện
                                            </button>
                                            <button type="button" class="suggestion-btn" onclick="addNhanXet('💪 Nắm vững kiến thức.')">
                                                <i class="fas fa-plus"></i> Vững kiến thức
                                            </button>
                                            <button type="button" class="suggestion-btn" onclick="addNhanXet('📚 Cần ôn lại bài học.')">
                                                <i class="fas fa-plus"></i> Ôn lại
                                            </button>
                                            <button type="button" class="suggestion-btn" onclick="addNhanXet('⭐ Làm bài nghiêm túc, chỉn chu.')">
                                                <i class="fas fa-plus"></i> Nghiêm túc
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="form-actions">
                            <a href="index.php?controller=assignmentTeacher&action=danhsachbainop&id=<?= htmlspecialchars($submission['maBaiTap'] ?? '') ?>" 
                               class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                <?= (($submission['trangThai'] ?? '') === 'DaCham') ? 'Cập nhật điểm' : 'Lưu điểm' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal xem file -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewTitle">
                    <i class="fas fa-eye me-2"></i>Xem trước file
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="filePreviewContent" style="min-height: 500px;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-3 text-muted">Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern design styles */
:root {
    --primary: #667eea;
    --primary-dark: #5568d3;
    --secondary: #764ba2;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
    --light: #f8fafc;
    --dark: #1e293b;
    --border-radius: 16px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --box-shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Page header */
.page-header-card {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 0;
}

.page-header-card h3 {
    font-weight: 700;
    font-size: 1.75rem;
}

/* Info card */
.info-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--box-shadow-lg);
}

.info-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.25rem 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--dark);
    border-bottom: 3px solid var(--primary);
}

.info-card-body {
    padding: 1.5rem;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    color: var(--dark);
    font-weight: 500;
}

/* Badge custom */
.badge-custom {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.badge-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* Content card */
.content-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.content-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-lg);
}

.content-card-header {
    padding: 1.25rem 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.content-card-header.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.content-card-header.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.content-card-header.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.content-card-body {
    padding: 1.5rem;
    line-height: 1.8;
}

/* Files grid */
.files-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.file-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.file-card:hover {
    border-color: var(--primary);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.file-card-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-radius: 12px;
    flex-shrink: 0;
}

.file-card-body {
    flex: 1;
    min-width: 0;
}

.file-card-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.file-meta-item {
    display: inline-flex;
    align-items: center;
    font-size: 0.75rem;
    color: #64748b;
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

.file-card-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.btn-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-icon-primary {
    background: #dbeafe;
    color: #3b82f6;
}

.btn-icon-primary:hover {
    background: #3b82f6;
    color: white;
    transform: scale(1.1);
}

.btn-icon-success {
    background: #d1fae5;
    color: #10b981;
}

.btn-icon-success:hover {
    background: #10b981;
    color: white;
    transform: scale(1.1);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
}

/* Grading card */
.grading-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2rem;
}

.grading-card-header {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid var(--primary);
}

/* Score section */
.score-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.score-input {
    font-size: 2rem !important;
    font-weight: 700;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem !important;
    transition: all 0.3s ease;
}

.score-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.score-input.is-invalid {
    border-color: var(--danger);
}

/* Quick scores */
.quick-scores {
    display: grid;
    gap: 0.75rem;
}

.quick-score-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
}

.quick-score-btn i {
    font-size: 1.25rem;
    width: 32px;
    text-align: center;
}

.quick-score-btn span {
    font-size: 1.25rem;
    font-weight: 700;
    min-width: 40px;
}

.quick-score-btn small {
    font-size: 0.875rem;
    color: #64748b;
}

.quick-score-btn:hover {
    transform: translateX(8px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.quick-score-btn.excellent:hover {
    border-color: #10b981;
    background: #ecfdf5;
}

.quick-score-btn.excellent i {
    color: #10b981;
}

.quick-score-btn.good:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.quick-score-btn.good i {
    color: #3b82f6;
}

.quick-score-btn.average:hover {
    border-color: #f59e0b;
    background: #fffbeb;
}

.quick-score-btn.average i {
    color: #f59e0b;
}

.quick-score-btn.fair:hover {
    border-color: #64748b;
    background: #f8fafc;
}

.quick-score-btn.fair i {
    color: #64748b;
}

/* Comment section */
.comment-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.comment-textarea {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
    resize: vertical;
}

.comment-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.comment-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.suggestion-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.suggestion-btn:hover {
    border-color: var(--primary);
    background: #f0f4ff;
    transform: translateY(-2px);
}

/* Form actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e2e8f0;
}

/* Modern alert */
.modern-alert {
    border-radius: 12px;
    border: none;
    box-shadow: var(--box-shadow);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal styles */
.modal-content {
    border-radius: var(--border-radius);
    border: none;
    box-shadow: var(--box-shadow-lg);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    padding: 1.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

#filePreviewContent {
    background: #f8fafc;
    border-radius: 8px;
}

#filePreviewContent img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: var(--box-shadow);
}

#filePreviewContent iframe {
    width: 100%;
    height: 600px;
    border: none;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 991px) {
    .files-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-scores {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .page-header-card {
        padding: 1.5rem;
    }
    
    .page-header-card h3 {
        font-size: 1.5rem;
    }
    
    .file-card {
        flex-direction: column;
        text-align: center;
    }
    
    .file-card-actions {
        width: 100%;
        justify-content: center;
    }
    
    .quick-scores {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

@media print {
    .btn, .file-card-actions, .grading-card, .page-header-card a {
        display: none !important;
    }
}
</style>

<script>
// Preview file function
function previewFile(filePath, fileExt, fileName) {
    console.log('Previewing:', filePath);
    
    const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const previewContent = document.getElementById('filePreviewContent');
    const modalTitle = document.getElementById('filePreviewTitle');
    
    modalTitle.innerHTML = '<i class="fas fa-eye me-2"></i>' + fileName;
    
    previewContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3 text-muted">Đang tải file...</p>
        </div>
    `;
    
    modal.show();
    
    // Check file exists
    fetch(filePath, { method: 'HEAD' })
        .then(response => {
            if (!response.ok) {
                throw new Error('File không tồn tại (Status: ' + response.status + ')');
            }
            
            setTimeout(() => {
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt.toLowerCase())) {
                    previewContent.innerHTML = `
                        <div class="text-center p-4">
                            <img src="${filePath}" 
                                 alt="Preview" 
                                 class="img-fluid" 
                                 style="max-height: 600px;"
                                 onerror="this.parentElement.innerHTML='<div class=\\'alert alert-danger\\'><i class=\\'fas fa-times-circle\\'></i> Không thể tải ảnh</div>'">
                        </div>
                    `;
                } else if (fileExt.toLowerCase() === 'pdf') {
                    previewContent.innerHTML = `
                        <div class="ratio ratio-16x9" style="height: 600px;">
                            <iframe src="${filePath}#toolbar=1" frameborder="0"></iframe>
                        </div>
                        <div class="text-center mt-3 p-3">
                            <a href="${filePath}" class="btn btn-primary me-2" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Mở tab mới
                            </a>
                            <a href="${filePath}" class="btn btn-success" download="${fileName}">
                                <i class="fas fa-download me-2"></i>Tải về
                            </a>
                        </div>
                    `;
                } else {
                    previewContent.innerHTML = `
                        <div class="alert alert-info text-center m-4">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="mb-3">Không thể xem trước loại file này</p>
                            <a href="${filePath}" class="btn btn-primary" download="${fileName}">
                                <i class="fas fa-download me-2"></i>Tải về để xem
                            </a>
                        </div>
                    `;
                }
            }, 300);
        })
        .catch(error => {
            previewContent.innerHTML = `
                <div class="alert alert-danger m-4">
                    <h5><i class="fas fa-exclamation-triangle"></i> Lỗi!</h5>
                    <p>${error.message}</p>
                    <hr>
                    <p class="mb-2"><strong>Đường dẫn:</strong></p>
                    <code>${filePath}</code>
                </div>
            `;
        });
}

// Set score
function setDiem(diem) {
    const diemInput = document.getElementById('diem');
    diemInput.value = diem;
    diemInput.classList.remove('is-invalid');
    
    // Visual feedback
    diemInput.style.background = '#d4edda';
    diemInput.style.transform = 'scale(1.05)';
    setTimeout(() => {
        diemInput.style.background = '';
        diemInput.style.transform = '';
    }, 500);
}

// Add comment
function addNhanXet(text) {
    const nhanXetTextarea = document.getElementById('nhanXet');
    const currentValue = nhanXetTextarea.value.trim();
    nhanXetTextarea.value = currentValue ? currentValue + '\n' + text : text;
    
    // Visual feedback
    nhanXetTextarea.style.background = '#d4edda';
    setTimeout(() => {
        nhanXetTextarea.style.background = '';
    }, 500);
    
    nhanXetTextarea.focus();
}

// Form validation
(function() {
    'use strict';
    
    const form = document.getElementById('formChamDiem');
    if (!form) return;
    
    form.addEventListener('submit', function(event) {
        const diemInput = document.getElementById('diem');
        const diem = parseFloat(diemInput.value);
        
        if (isNaN(diem) || diem < 0 || diem > 10) {
            event.preventDefault();
            event.stopPropagation();
            diemInput.classList.add('is-invalid');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng nhập điểm từ 0 đến 10',
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert('Vui lòng nhập điểm từ 0 đến 10');
            }
            return false;
        }
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Đang lưu...',
                    text: 'Vui lòng đợi',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            setTimeout(() => {
                form.submit();
            }, 500);
        }
        
        form.classList.add('was-validated');
    }, false);
    
    // Remove invalid on input
    const diemInput = document.getElementById('diem');
    if (diemInput) {
        diemInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }
})();

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-info)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (typeof bootstrap !== 'undefined') {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});

// Form changed warning
let formChanged = false;
const form = document.getElementById('formChamDiem');
if (form) {
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    form.addEventListener('submit', function() {
        formChanged = false;
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to submit
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const submitBtn = document.querySelector('#formChamDiem button[type="submit"]');
        if (submitBtn) submitBtn.click();
    }
    
    // Escape to close modal
    if (e.key === 'Escape') {
        const modalEl = document.getElementById('filePreviewModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }
});
</script>

<?php include "views/layout/footer.php"; ?>