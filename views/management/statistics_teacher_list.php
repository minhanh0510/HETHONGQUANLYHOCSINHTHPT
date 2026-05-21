<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>

<div class="main-content" style="color: #000;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard-teacher"></i> Danh sách giáo viên</h2>
        
        <div>
            <button class="btn btn-success" onclick="exportReport('excel')">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </button>
            <button class="btn btn-danger" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf"></i> Xuất PDF
            </button>
        </div>
        <div class="breadcrumb">
            <a href="index.php?controller=statistics&action=dashboard">Thống kê</a> / Danh sách giáo viên
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5><i class="fas fa-filter"></i> Bộ lọc</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="index.php">
                <input type="hidden" name="controller" value="statistics">
                <input type="hidden" name="action" value="teacher_list">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tổ chuyên môn:</label>
                            <select name="to_chuyen_mon" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="Tổ Toán - Tin" <?= ($toChuyenMon ?? '') === 'Tổ Toán - Tin' ? 'selected' : '' ?>>Tổ Toán - Tin</option>
                                <option value="Tổ Ngữ Văn" <?= ($toChuyenMon ?? '') === 'Tổ Ngữ Văn' ? 'selected' : '' ?>>Tổ Ngữ Văn</option>
                                <option value="Tổ Ngoại ngữ" <?= ($toChuyenMon ?? '') === 'Tổ Ngoại ngữ' ? 'selected' : '' ?>>Tổ Ngoại ngữ</option>
                                <option value="Tổ Khoa học Tự nhiên" <?= ($toChuyenMon ?? '') === 'Tổ Khoa học Tự nhiên' ? 'selected' : '' ?>>Tổ Khoa học Tự nhiên</option>
                                <option value="Tổ Khoa học Xã hội" <?= ($toChuyenMon ?? '') === 'Tổ Khoa học Xã hội' ? 'selected' : '' ?>>Tổ Khoa học Xã hội</option>
                                <option value="Tổ Thể dục - GDQP" <?= ($toChuyenMon ?? '') === 'Tổ Thể dục - GDQP' ? 'selected' : '' ?>>Tổ Thể dục - GDQP</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Kết quả -->
    <div class="card">
        <div class="card-header bg-light">
            <h5><i class="fas fa-list"></i> Kết quả (<?= count($teachers) ?> giáo viên)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($teachers)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Không tìm thấy dữ liệu giáo viên
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" style="color: #000;">
                        <thead class="thead-light">
                            <tr>
                                <th>STT</th>
                                <th>Mã GV</th>
                                <th>Họ tên</th>
                                <th>Giới tính</th>
                                <th>Tổ chuyên môn</th>
                                <th>Môn giảng dạy</th>
                                <th>Số lớp</th>
                                <th>Số môn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sắp xếp lại để chắc chắn theo thứ tự GV001, GV002...
                            usort($teachers, function($a, $b) {
                                $numA = intval(substr($a['maGV'], 2));
                                $numB = intval(substr($b['maGV'], 2));
                                return $numA - $numB;
                            });
                            
                            foreach ($teachers as $index => $teacher): 
                            ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= $teacher['maGV'] ?></strong></td>
                                    <td><?= htmlspecialchars($teacher['hoVaTen']) ?></td>
                                    <td>
                                        <?php if ($teacher['gioiTinh'] === 'Nam'): ?>
                                            Nam
                                        <?php elseif ($teacher['gioiTinh'] === 'Nữ'): ?>
                                            Nữ
                                        <?php else: ?>
                                            Khác
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $teacher['toChuyenMon'] ?? 'Chưa phân tổ' ?></td>
                                    <td>
                                        <?php if ($teacher['monGiangDay']): ?>
                                            <?php 
                                            $monArr = explode(',', $teacher['monGiangDay']);
                                            $monNames = [];
                                            foreach ($monArr as $mon): 
                                                $monTrimmed = trim($mon);
                                                switch ($monTrimmed) {
                                                    case 'TOAN': $monNames[] = 'Toán'; break;
                                                    case 'VAN': $monNames[] = 'Ngữ Văn'; break;
                                                    case 'ANH': $monNames[] = 'Tiếng Anh'; break;
                                                    case 'LY': $monNames[] = 'Vật Lý'; break;
                                                    case 'HOA': $monNames[] = 'Hóa Học'; break;
                                                    case 'SINH': $monNames[] = 'Sinh Học'; break;
                                                    case 'SU': $monNames[] = 'Lịch Sử'; break;
                                                    case 'DIA': $monNames[] = 'Địa Lý'; break;
                                                    case 'GDCD': $monNames[] = 'GDCD'; break;
                                                    case 'TD': $monNames[] = 'Thể Dục'; break;
                                                    case 'QP': $monNames[] = 'QP-AN'; break;
                                                    default: $monNames[] = $monTrimmed;
                                                }
                                            endforeach;
                                            echo implode(', ', $monNames);
                                            ?>
                                        <?php else: ?>
                                            Chưa phân môn
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $teacher['soLopPhuTrach'] ?? 0 ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $teacher['soMonPhuTrach'] ?? 0 ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="6" class="text-right"><strong>Tổng cộng:</strong></td>
                                <td class="text-center"><strong><?= array_sum(array_column($teachers, 'soLopPhuTrach')) ?></strong></td>
                                <td class="text-center"><strong><?= array_sum(array_column($teachers, 'soMonPhuTrach')) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- THỐNG KÊ NHANH - KHUNG ĐẦY ĐỦ -->
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line"></i> THỐNG KÊ NHANH</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-primary">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-users mr-2"></i>TỔNG SỐ GIÁO VIÊN
                                        </h6>
                                        <h2 class="display-4 text-dark"><?= count($teachers) ?></h2>
                                        <p class="text-muted mb-0">Giáo viên</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-success">
                                            <i class="fas fa-chalkboard-teacher mr-2"></i>SỐ LỚP TRUNG BÌNH/GV
                                        </h6>
                                        <h2 class="display-4 text-dark">
                                            <?php 
                                            $avgLop = count($teachers) > 0 ? 
                                                round(array_sum(array_column($teachers, 'soLopPhuTrach')) / count($teachers), 1) : 0;
                                            echo $avgLop;
                                            ?>
                                        </h2>
                                        <p class="text-muted mb-0">Lớp/Giáo viên</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-book mr-2"></i>SỐ MÔN TRUNG BÌNH/GV
                                        </h6>
                                        <h2 class="display-4 text-dark">
                                            <?php 
                                            $avgMon = count($teachers) > 0 ? 
                                                round(array_sum(array_column($teachers, 'soMonPhuTrach')) / count($teachers), 1) : 0;
                                            echo $avgMon;
                                            ?>
                                        </h2>
                                        <p class="text-muted mb-0">Môn/Giáo viên</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                       
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Tất cả chữ đều màu đen */
.main-content,
.main-content h2,
.main-content h5,
.main-content label,
.main-content .breadcrumb,
.main-content .breadcrumb a,
.main-content .table th,
.main-content .table td,
.main-content .alert,
.main-content .alert i {
    color: #000 !important;
}

/* Tiêu đề thống kê nhanh màu trắng */
.card-header.bg-primary h5 {
    color: #fff !important;
}

/* Card titles trong thống kê */
.card-title.text-primary,
.card-title.text-success,
.card-title.text-warning {
    font-weight: 600;
}

/* Hiển thị số lớn */
.display-4 {
    font-size: 2.5rem;
    font-weight: 300;
    line-height: 1.2;
}

/* Đảm bảo chữ trong các ô input không bị ảnh hưởng */
.form-control {
    color: #000 !important;
}

/* Border cho các card */
.border-primary { border: 2px solid #007bff !important; }
.border-success { border: 2px solid #28a745 !important; }
.border-warning { border: 2px solid #ffc107 !important; }
</style>

<script>
// Xuất báo cáo
function exportReport(type) {
    const toChuyenMon = document.querySelector('select[name="to_chuyen_mon"]').value;
    window.open(`index.php?controller=statistics&action=export&type=${type}&report_type=teacher_list&to_chuyen_mon=${toChuyenMon}`, '_blank');
}
</script>

<?php include "views/layout/footer.php"; ?>