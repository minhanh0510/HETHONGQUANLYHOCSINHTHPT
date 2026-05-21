<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_management.php"; ?>


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-graduation-cap"></i> Thống kê học lực - điểm số</h2>
        <div>
            <button class="btn btn-success" onclick="exportReport('excel')">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </button>
            <button class="btn btn-danger" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf"></i> Xuất PDF
            </button>
        </div>
    </div>
    
    <!-- Bộ lọc và tiêu chí -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter"></i> Bộ lọc thống kê</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="index.php">
                <input type="hidden" name="controller" value="statistics">
                <input type="hidden" name="action" value="academic_stats">
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tiêu chí thống kê:</label>
                            <select name="criteria" class="form-control" onchange="toggleCriteriaOptions(this.value)">
                                <option value="ty_le_hoc_luc" <?= ($criteria ?? '') === 'ty_le_hoc_luc' ? 'selected' : '' ?>>Tỷ lệ học lực toàn trường</option>
                                <option value="diem_theo_mon" <?= ($criteria ?? '') === 'diem_theo_mon' ? 'selected' : '' ?>>Điểm số theo môn</option>
                                <option value="trung_binh_hoc_ky" <?= ($criteria ?? '') === 'trung_binh_hoc_ky' ? 'selected' : '' ?>>Điểm trung bình học kỳ</option>
                                <option value="so_sanh_khoi" <?= ($criteria ?? '') === 'so_sanh_khoi' ? 'selected' : '' ?>>So sánh kết quả giữa các khối</option>
                                <option value="so_sanh_lop" <?= ($criteria ?? '') === 'so_sanh_lop' ? 'selected' : '' ?>>So sánh tỷ lệ học lực giữa các lớp</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Năm học:</label>
                            <select name="nam_hoc" class="form-control">
                                <?php foreach ($filterOptions['nam_hoc'] as $namHoc): ?>
                                    <option value="<?= $namHoc['namHoc'] ?>" <?= ($filters['namHoc'] ?? '2024-2025') === $namHoc['namHoc'] ? 'selected' : '' ?>>
                                        <?= $namHoc['namHoc'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Học kỳ:</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="1" <?= ($filters['hocKy'] ?? 1) == 1 ? 'selected' : '' ?>>Học kỳ 1</option>
                                <option value="2" <?= ($filters['hocKy'] ?? 1) == 2 ? 'selected' : '' ?>>Học kỳ 2</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Các tùy chọn bổ sung theo tiêu chí -->
                    <div class="col-md-3" id="additionalOptions" style="display: <?= in_array($criteria ?? '', ['diem_theo_mon', 'so_sanh_lop']) ? 'block' : 'none' ?>">
                        <div class="form-group">
                            <label id="additionalLabel">
                                <?= ($criteria ?? '') === 'diem_theo_mon' ? 'Môn học:' : 'Lớp:' ?>
                            </label>
                            <select name="<?= ($criteria ?? '') === 'diem_theo_mon' ? 'ma_mon' : 'ma_lop' ?>" class="form-control" id="additionalSelect">
                                <?php if (($criteria ?? '') === 'diem_theo_mon'): ?>
                                    <option value="all">Tất cả môn</option>
                                    <?php foreach ($filterOptions['mon'] as $mon): ?>
                                        <option value="<?= $mon['maMon'] ?>" <?= ($filters['maMon'] ?? '') === $mon['maMon'] ? 'selected' : '' ?>>
                                            <?= $mon['tenMon'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php elseif (($criteria ?? '') === 'so_sanh_lop'): ?>
                                    <option value="">Tất cả lớp</option>
                                    <?php foreach ($filterOptions['lop'] as $lop): ?>
                                        <option value="<?= $lop['maLop'] ?>" <?= ($filters['maLop'] ?? '') === $lop['maLop'] ? 'selected' : '' ?>>
                                            <?= $lop['tenLop'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-line"></i> Thống kê
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Kết quả -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-chart-area"></i> Kết quả thống kê</h5>
        </div>
        <div class="card-body">
            <?php if (empty($stats)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Không tìm thấy dữ liệu thống kê
                </div>
            <?php else: ?>
                <!-- Hiển thị theo từng tiêu chí -->
                <?php if ($criteria === 'ty_le_hoc_luc'): ?>
                    <!-- Tỷ lệ học lực toàn trường -->
                    <?php
                    $tongHocSinh = 0;
                    $hocLucCounts = ['gioi' => 0, 'kha' => 0, 'trungBinh' => 0, 'yeu' => 0];
                    
                    foreach ($stats as $row) {
                        $tongHocSinh += $row['tongSo'];
                        $hocLucCounts['gioi'] += $row['gioi'];
                        $hocLucCounts['kha'] += $row['kha'];
                        $hocLucCounts['trungBinh'] += $row['trungBinh'];
                        $hocLucCounts['yeu'] += $row['yeu'];
                    }
                    
                    $tyLeGioi = $tongHocSinh > 0 ? round(($hocLucCounts['gioi'] / $tongHocSinh) * 100, 2) : 0;
                    $tyLeKha = $tongHocSinh > 0 ? round(($hocLucCounts['kha'] / $tongHocSinh) * 100, 2) : 0;
                    $tyLeTrungBinh = $tongHocSinh > 0 ? round(($hocLucCounts['trungBinh'] / $tongHocSinh) * 100, 2) : 0;
                    $tyLeYeu = $tongHocSinh > 0 ? round(($hocLucCounts['yeu'] / $tongHocSinh) * 100, 2) : 0;
                    ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-dark text-white">
                                <div class="card-body text-center">
                                    <h6>Tổng số học sinh</h6>
                                    <h1 class="display-4"><?= $tongHocSinh ?></h1>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Học lực Giỏi</h6>
                                    <h1 class="display-4"><?= $hocLucCounts['gioi'] ?></h1>
                                    <p><?= $tyLeGioi ?>%</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Học lực Khá</h6>
                                    <h1 class="display-4"><?= $hocLucCounts['kha'] ?></h1>
                                    <p><?= $tyLeKha ?>%</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>Học lực TB/Yếu</h6>
                                    <h1 class="display-4"><?= $hocLucCounts['trungBinh'] + $hocLucCounts['yeu'] ?></h1>
                                    <p><?= $tyLeTrungBinh + $tyLeYeu ?>%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Biểu đồ -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-chart-pie"></i> Biểu đồ: Phân loại học lực toàn trường</h5>
                            <canvas id="academicLevelChart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-chart-bar"></i> Biểu đồ: Điểm trung bình các khối</h5>
                            <canvas id="gradeAverageChart" height="200"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bảng chi tiết -->
                    <div class="mt-4">
                        <h5><i class="fas fa-table"></i> Chi tiết theo lớp</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Lớp</th>
                                        <th>Khối</th>
                                        <th>Tổng số</th>
                                        <th>Giỏi</th>
                                        <th>Khá</th>
                                        <th>Trung bình</th>
                                        <th>Yếu</th>
                                        <th>Tỷ lệ Khá-Giỏi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $index => $row): ?>
                                        <?php
                                        $tyLeKhaGioi = $row['tongSo'] > 0 ? round((($row['gioi'] + $row['kha']) / $row['tongSo']) * 100, 2) : 0;
                                        ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= $row['tenLop'] ?></strong></td>
                                            <td><?= $row['tenKhoi'] ?></td>
                                            <td class="text-center"><?= $row['tongSo'] ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-success"><?= $row['gioi'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info"><?= $row['kha'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-warning"><?= $row['trungBinh'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-danger"><?= $row['yeu'] ?></span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: <?= $tyLeKhaGioi ?>%" 
                                                         aria-valuenow="<?= $tyLeKhaGioi ?>" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        <?= $tyLeKhaGioi ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                <?php elseif ($criteria === 'diem_theo_mon'): ?>
                    <!-- Điểm số theo môn -->
                    <h5><i class="fas fa-book"></i> Điểm số môn: 
                        <?php 
                        $monName = 'Tất cả môn';
                        if (!empty($filters['maMon']) && $filters['maMon'] !== 'all') {
                            foreach ($filterOptions['mon'] as $mon) {
                                if ($mon['maMon'] === $filters['maMon']) {
                                    $monName = $mon['tenMon'];
                                    break;
                                }
                            }
                        }
                        echo $monName;
                        ?>
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã HS</th>
                                    <th>Họ tên</th>
                                    <th>Lớp</th>
                                    <th>Môn học</th>
                                    <th>Điểm TB HK</th>
                                    <th>Điểm TB Năm</th>
                                    <th>Xếp loại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $index => $row): ?>
                                    <?php
                                    $diemTBHK = $row['diemTBHK'] ? round($row['diemTBHK'], 2) : '';
                                    $diemTBNam = $row['diemTBNam'] ? round($row['diemTBNam'], 2) : '';
                                    
                                    // Xếp loại
                                    $xeploai = '';
                                    $badgeClass = '';
                                    if ($diemTBHK !== '') {
                                        if ($diemTBHK >= 8.0) {
                                            $xeploai = 'Giỏi';
                                            $badgeClass = 'badge-success';
                                        } elseif ($diemTBHK >= 6.5) {
                                            $xeploai = 'Khá';
                                            $badgeClass = 'badge-info';
                                        } elseif ($diemTBHK >= 5.0) {
                                            $xeploai = 'Trung bình';
                                            $badgeClass = 'badge-warning';
                                        } else {
                                            $xeploai = 'Yếu';
                                            $badgeClass = 'badge-danger';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= $row['maHS'] ?></strong></td>
                                        <td><?= htmlspecialchars($row['hoVaTen']) ?></td>
                                        <td><?= $row['tenLop'] ?></td>
                                        <td><?= $row['tenMon'] ?></td>
                                        <td class="text-center">
                                            <?php if ($diemTBHK !== ''): ?>
                                                <span class="badge <?= $badgeClass ?>" style="font-size: 1em;">
                                                    <?= $diemTBHK ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($diemTBNam !== ''): ?>
                                                <span class="badge badge-dark" style="font-size: 1em;">
                                                    <?= $diemTBNam ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($xeploai): ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $xeploai ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php elseif (in_array($criteria, ['so_sanh_khoi', 'so_sanh_lop'])): ?>
                    <!-- So sánh giữa các khối/lớp -->
                    <h5><i class="fas fa-balance-scale"></i> 
                        <?= $criteria === 'so_sanh_khoi' ? 'So sánh kết quả giữa các khối' : 'So sánh tỷ lệ học lực giữa các lớp' ?>
                    </h5>
                    
                    <!-- Biểu đồ so sánh -->
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="comparisonChart" height="100"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bảng số liệu -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>STT</th>
                                    <th><?= $criteria === 'so_sanh_khoi' ? 'Khối' : 'Lớp' ?></th>
                                    <th>Tổng số</th>
                                    <th>Giỏi</th>
                                    <th>Khá</th>
                                    <th>Trung bình</th>
                                    <th>Yếu</th>
                                    <th>Tỷ lệ Khá-Giỏi</th>
                                    <th>Điểm TB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $index => $row): ?>
                                    <?php
                                    $tyLeKhaGioi = $row['tongSo'] > 0 ? round((($row['gioi'] + $row['kha']) / $row['tongSo']) * 100, 2) : 0;
                                    $diemTB = $row['tongSo'] > 0 ? round((
                                        ($row['gioi'] * 8.5 + $row['kha'] * 7.0 + $row['trungBinh'] * 5.5 + $row['yeu'] * 4.0) / $row['tongSo']
                                    ), 2) : 0;
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= $criteria === 'so_sanh_khoi' ? $row['tenKhoi'] : $row['tenLop'] ?></strong></td>
                                        <td class="text-center"><?= $row['tongSo'] ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-success"><?= $row['gioi'] ?> (<?= round(($row['gioi'] / $row['tongSo']) * 100, 1) ?>%)</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info"><?= $row['kha'] ?> (<?= round(($row['kha'] / $row['tongSo']) * 100, 1) ?>%)</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-warning"><?= $row['trungBinh'] ?> (<?= round(($row['trungBinh'] / $row['tongSo']) * 100, 1) ?>%)</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-danger"><?= $row['yeu'] ?> (<?= round(($row['yeu'] / $row['tongSo']) * 100, 1) ?>%)</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?= $tyLeKhaGioi ?>%" 
                                                     aria-valuenow="<?= $tyLeKhaGioi ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= $tyLeKhaGioi ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-dark" style="font-size: 1.1em;">
                                                <?= $diemTB ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ học lực
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($stats) && $criteria === 'ty_le_hoc_luc'): ?>
        // Biểu đồ phân loại học lực
        const ctx1 = document.getElementById('academicLevelChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Giỏi', 'Khá', 'Trung bình', 'Yếu'],
                datasets: [{
                    data: [<?= $hocLucCounts['gioi'] ?>, <?= $hocLucCounts['kha'] ?>, <?= $hocLucCounts['trungBinh'] ?>, <?= $hocLucCounts['yeu'] ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',    // Giỏi - xanh
                        'rgba(23, 162, 184, 0.7)',   // Khá - xanh dương
                        'rgba(255, 193, 7, 0.7)',    // TB - vàng
                        'rgba(220, 53, 69, 0.7)'     // Yếu - đỏ
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const percentage = Math.round((value / <?= $tongHocSinh ?>) * 100);
                                return `${label}: ${value} học sinh (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Biểu đồ điểm trung bình các khối
        <?php
        $gradeAverages = [];
        foreach ($stats as $row) {
            if (!empty($row['tenKhoi'])) {
                if (!isset($gradeAverages[$row['tenKhoi']])) {
                    $gradeAverages[$row['tenKhoi']] = ['total' => 0, 'count' => 0];
                }
                $totalStudents = $row['tongSo'];
                $avgScore = (
                    ($row['gioi'] * 8.5 + $row['kha'] * 7.0 + 
                     $row['trungBinh'] * 5.5 + $row['yeu'] * 4.0) / $totalStudents
                );
                $gradeAverages[$row['tenKhoi']]['total'] += $avgScore * $totalStudents;
                $gradeAverages[$row['tenKhoi']]['count'] += $totalStudents;
            }
        }
        
        $finalAverages = [];
        foreach ($gradeAverages as $grade => $data) {
            $finalAverages[$grade] = $data['count'] > 0 ? round($data['total'] / $data['count'], 2) : 0;
        }
        ?>
        
        const ctx2 = document.getElementById('gradeAverageChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($finalAverages)) ?>,
                datasets: [{
                    label: 'Điểm trung bình',
                    data: <?= json_encode(array_values($finalAverages)) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        title: {
                            display: true,
                            text: 'Điểm trung bình'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Khối'
                        }
                    }
                }
            }
        });
        
    <?php elseif (!empty($stats) && in_array($criteria, ['so_sanh_khoi', 'so_sanh_lop'])): ?>
        // Biểu đồ so sánh
        const comparisonLabels = [];
        const comparisonData = {
            gioi: [],
            kha: [],
            trungBinh: [],
            yeu: []
        };
        
        <?php foreach ($stats as $row): ?>
            comparisonLabels.push(<?= json_encode($criteria === 'so_sanh_khoi' ? $row['tenKhoi'] : $row['tenLop']) ?>);
            comparisonData.gioi.push(<?= $row['gioi'] ?>);
            comparisonData.kha.push(<?= $row['kha'] ?>);
            comparisonData.trungBinh.push(<?= $row['trungBinh'] ?>);
            comparisonData.yeu.push(<?= $row['yeu'] ?>);
        <?php endforeach; ?>
        
        const ctx3 = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: comparisonLabels,
                datasets: [
                    {
                        label: 'Giỏi',
                        data: comparisonData.gioi,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Khá',
                        data: comparisonData.kha,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Trung bình',
                        data: comparisonData.trungBinh,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Yếu',
                        data: comparisonData.yeu,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số học sinh'
                        }
                    }
                }
            }
        });
    <?php endif; ?>
});

// Toggle hiển thị các tùy chọn bổ sung
function toggleCriteriaOptions(criteria) {
    const additionalDiv = document.getElementById('additionalOptions');
    const label = document.getElementById('additionalLabel');
    const select = document.getElementById('additionalSelect');
    
    if (criteria === 'diem_theo_mon') {
        additionalDiv.style.display = 'block';
        label.textContent = 'Môn học:';
        select.name = 'ma_mon';
        
        // Load danh sách môn học
        fetch('index.php?controller=statistics&action=get_subject_options')
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '<option value="all">Tất cả môn</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.label;
                    select.appendChild(option);
                });
            });
            
    } else if (criteria === 'so_sanh_lop') {
        additionalDiv.style.display = 'block';
        label.textContent = 'Lớp:';
        select.name = 'ma_lop';
        
        // Load danh sách lớp
        fetch('index.php?controller=statistics&action=get_class_options')
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '<option value="">Tất cả lớp</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.label;
                    select.appendChild(option);
                });
            });
            
    } else {
        additionalDiv.style.display = 'none';
    }
}

// Xuất báo cáo
function exportReport(type) {
    const params = new URLSearchParams(window.location.search);
    window.open(`index.php?controller=statistics&action=export&type=${type}&report_type=academic_stats&${params}`, '_blank');
}
</script>

<?php include "views/layout/footer.php"; ?>