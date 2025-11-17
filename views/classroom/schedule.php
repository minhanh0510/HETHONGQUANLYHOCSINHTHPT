<?php
// views/classroom/schedule.php
include "views/layout/header.php";
include "views/layout/sidebar_teacher.php";

$currentDate = $_GET['date'] ?? date('Y-m-d');
$view = $_GET['view'] ?? 'week';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lịch dạy</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php?controller=classroom&action=index">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Lịch dạy</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Thống kê -->
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-chalkboard-teacher"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tổng tiết (30 ngày)</span>
                            <span class="info-box-number"><?= $stats['tongTiet'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Số lớp giảng dạy</span>
                            <span class="info-box-number"><?= $stats['soLop'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Số môn giảng dạy</span>
                            <span class="info-box-number"><?= $stats['soMon'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tiết sắp tới</span>
                            <span class="info-box-number"><?= $stats['tietSapToi'] ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Môn giảng dạy -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book"></i> Môn giảng dạy</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($subjects)): ?>
                        <div class="alert alert-info">Chưa có phân công giảng dạy</div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($subjects as $subject): ?>
                                <div class="col-md-4 mb-2">
                                    <span class="badge badge-primary badge-lg" style="font-size: 14px; padding: 8px 12px;">
                                        <i class="fas fa-book"></i> <?= htmlspecialchars($subject['tenMon']) ?> 
                                        <small>(<?= $subject['soTiet'] ?> tiết/tuần)</small>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Điều hướng và chọn ngày -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> 
                                <?php if ($view === 'week'): ?>
                                    Lịch tuần (<?= date('d/m/Y', strtotime($currentDate)) ?>)
                                <?php else: ?>
                                    Lịch ngày <?= date('d/m/Y', strtotime($currentDate)) ?>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group">
                                <a href="?controller=classroom&action=schedule&view=day&date=<?= $currentDate ?>" 
                                   class="btn btn-sm <?= $view === 'day' ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-calendar-day"></i> Ngày
                                </a>
                                <a href="?controller=classroom&action=schedule&view=week&date=<?= $currentDate ?>" 
                                   class="btn btn-sm <?= $view === 'week' ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-calendar-week"></i> Tuần
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="?controller=classroom&action=schedule&view=<?= $view ?>&date=<?= date('Y-m-d', strtotime('-1 ' . ($view === 'week' ? 'week' : 'day'), strtotime($currentDate))) ?>" 
                                   class="btn btn-default">
                                    <i class="fas fa-chevron-left"></i> Trước
                                </a>
                                <a href="?controller=classroom&action=schedule&view=<?= $view ?>&date=<?= date('Y-m-d') ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-calendar-day"></i> Hôm nay
                                </a>
                                <a href="?controller=classroom&action=schedule&view=<?= $view ?>&date=<?= date('Y-m-d', strtotime('+1 ' . ($view === 'week' ? 'week' : 'day'), strtotime($currentDate))) ?>" 
                                   class="btn btn-default">
                                    Sau <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="form-inline float-right">
                                <input type="hidden" name="controller" value="classroom">
                                <input type="hidden" name="action" value="schedule">
                                <input type="hidden" name="view" value="<?= $view ?>">
                                <input type="date" name="date" class="form-control mr-2" value="<?= $currentDate ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Xem
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if ($view === 'week' && !empty($weekSchedule)): ?>
                        <!-- Lịch theo tuần -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 80px;">Tiết</th>
                                        <th>Thứ 2</th>
                                        <th>Thứ 3</th>
                                        <th>Thứ 4</th>
                                        <th>Thứ 5</th>
                                        <th>Thứ 6</th>
                                        <th>Thứ 7</th>
                                        <th>Chủ nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($tiet = 1; $tiet <= 5; $tiet++): ?>
                                        <tr>
                                            <td class="text-center"><strong>Tiết <?= $tiet ?></strong></td>
                                            <?php for ($thu = 2; $thu <= 8; $thu++): ?>
                                                <td>
                                                    <?php if (isset($weekSchedule[$thu][$tiet])): 
                                                        $item = $weekSchedule[$thu][$tiet];
                                                    ?>
                                                        <div class="schedule-item p-2" style="background: #e3f2fd; border-left: 3px solid #2196f3;">
                                                            <strong class="text-primary"><?= htmlspecialchars($item['tenMon']) ?></strong><br>
                                                            <small>
                                                                <i class="fas fa-users"></i> <?= htmlspecialchars($item['tenLop']) ?><br>
                                                                <i class="fas fa-door-open"></i> <?= htmlspecialchars($item['tenPhong']) ?>
                                                            </small>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-muted text-center">-</div>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($view === 'day'): ?>
                        <!-- Lịch theo ngày -->
                        <?php if (empty($scheduleData)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Không có lịch dạy trong ngày này
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="width: 80px;">Tiết</th>
                                        <th>Môn học</th>
                                        <th>Lớp</th>
                                        <th>Phòng</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scheduleData as $item): ?>
                                        <tr>
                                            <td class="text-center"><strong>Tiết <?= $item['tiet'] ?></strong></td>
                                            <td><strong class="text-primary"><?= htmlspecialchars($item['tenMon']) ?></strong></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= htmlspecialchars($item['tenLop']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($item['tenPhong']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($item['ngay'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "views/layout/footer.php"; ?>