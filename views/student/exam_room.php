<?php
// View này render đầy đủ layout (header, sidebar, content) cho role Học sinh
// Biến đầu vào: $user, $rows (danh sách phòng thi), $dsMon, $dsPhong, $soNgayThi, $soTietHomNay
// Đảm bảo đã session_start() ở controller
$displayName = 'Học sinh: ' . htmlspecialchars($user['ho_ten']);
$avatarText  = 'HS';
$mon_loc   = $_GET['mon_thi']  ?? '';
$ngay_loc  = $_GET['ngay_thi'] ?? '';
$phong_loc = $_GET['phong']    ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Hệ thống Quản lý Giáo dục - Xem phòng thi</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<div class="header">
  <div class="logo">HỆ THỐNG QUẢN LÝ GIÁO DỤC</div>
  <div class="user-info">
    <div class="notification-icon" onclick="toggleNotifications()">
      <span>🔔</span>
      <div class="notification-badge" id="notificationBadge">0</div>
    </div>
    <div class="user-avatar"><?= $avatarText ?></div>
    <div id="userName"><?= $displayName ?></div>
    <a class="logout-btn" href="index.php?controller=auth&action=logout">Đăng xuất</a>
  </div>
</div>

<div class="notification-panel" id="notificationPanel">
  <div class="notification-header">
    <div class="notification-title">Thông báo</div>
    <button class="btn btn-primary" onclick="markAllAsRead()">Đánh dấu đã đọc</button>
  </div>
  <div class="notification-list" id="notificationList"></div>
</div>

<div class="container">
  <!-- Sidebar chỉ hiển thị menu Học sinh -->
  <div class="sidebar">
    <div class="menu-title">HỌC SINH</div>
    <a class="menu-item active" href="index.php?controller=student&action=examRoom"><i>📝</i> Xem danh sách phòng thi</a>
  </div>

  <div class="main-content">
    <div class="content-header">
      <div class="page-title">Xem danh sách phòng thi</div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-section">
      <form id="filterForm" method="GET" action="index.php" class="w-100">
        <input type="hidden" name="controller" value="student" />
        <input type="hidden" name="action" value="examRoom" />
        <div class="form-row">
          <div class="form-col">
            <label class="form-label">Môn thi</label>
            <select class="form-control" name="mon_thi" onchange="submitFilterForm()">
              <option value="">Tất cả môn thi</option>
              <?php foreach($dsMon as $m): ?>
                <option value="<?= htmlspecialchars($m['mon_thi']) ?>" <?= $mon_loc===$m['mon_thi']?'selected':'' ?>>
                  <?= htmlspecialchars($m['mon_thi']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-col">
            <label class="form-label">Ngày thi</label>
            <input type="date" class="form-control" name="ngay_thi" value="<?= htmlspecialchars($ngay_loc) ?>">
          </div>

          <div class="form-col">
            <label class="form-label">Phòng thi</label>
            <select class="form-control" name="phong" onchange="submitFilterForm()">
              <option value="">Tất cả phòng</option>
              <?php foreach($dsPhong as $p): ?>
                <option value="<?= htmlspecialchars($p['phong']) ?>" <?= $phong_loc===$p['phong']?'selected':'' ?>>
                  <?= htmlspecialchars($p['phong']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-col" style="display:flex;align-items:flex-end;gap:10px;">
            <button type="submit" class="btn btn-primary">🔍 Lọc</button>
            <button type="button" class="btn btn-warning" onclick="resetFilter()">🔄 Xóa lọc</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Thống kê nhanh -->
    <div class="dashboard-cards" style="margin-bottom:20px;">
      <div class="dashboard-card">
        <div class="card-icon">📝</div>
        <div class="card-title">Tổng số môn thi</div>
        <div class="card-value"><?= count($dsMon) ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-icon">🏫</div>
        <div class="card-title">Số phòng thi</div>
        <div class="card-value"><?= count($dsPhong) ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-icon">📅</div>
        <div class="card-title">Ngày thi</div>
        <div class="card-value"><?= $soNgayThi ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-icon">⏰</div>
        <div class="card-title">Tiết thi hôm nay</div>
        <div class="card-value"><?= $soTietHomNay ?></div>
      </div>
    </div>

    <!-- Kết quả -->
    <div class="alert alert-info">
      <strong>Kết quả lọc:</strong>
      Tìm thấy <strong><?= count($rows) ?></strong> phòng thi
      <?php if($mon_loc): ?> - Môn: <strong><?= htmlspecialchars($mon_loc) ?></strong><?php endif; ?>
      <?php if($ngay_loc): ?> - Ngày: <strong><?= date('d/m/Y', strtotime($ngay_loc)) ?></strong><?php endif; ?>
      <?php if($phong_loc): ?> - Phòng: <strong><?= htmlspecialchars($phong_loc) ?></strong><?php endif; ?>
    </div>

    <?php if(count($rows)>0): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Môn thi</th>
              <th>Ngày thi</th>
              <th>Thứ</th>
              <th>Giờ thi</th>
              <th>Phòng thi</th>
              <th>Số báo danh</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($rows as $pt):
              $ngay_thi = $pt['ngay_thi'];
              $gio_bd = substr($pt['gio_bat_dau'],0,5);
              $gio_kt = substr($pt['gio_ket_thuc'],0,5);

              $thu_index = date('w', strtotime($ngay_thi));
              $thu_text = ['Chủ nhật','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7'][$thu_index];

              $today = date('Y-m-d');
              $now   = date('H:i:s');
              $state = 'Chưa thi'; $color='info'; $icon='📅';
              if ($ngay_thi < $today) { $state='Đã thi'; $color='success'; $icon='✅'; }
              elseif ($ngay_thi === $today) {
                if ($now < $pt['gio_bat_dau']) { $state='Sắp thi'; $color='warning'; $icon='⏰'; }
                elseif ($now <= $pt['gio_ket_thuc']) { $state='Đang thi'; $color='danger'; $icon='📝'; }
                else { $state='Đã thi'; $color='success'; $icon='✅'; }
              }
              $rowClass = ['success'=>'success-row','warning'=>'warning-row','danger'=>'danger-row','info'=>'info-row'][$color];
          ?>
            <tr class="<?= $rowClass ?>">
              <td><strong><?= htmlspecialchars($pt['mon_thi']) ?></strong></td>
              <td><span class="date-badge"><?= date('d/m/Y', strtotime($ngay_thi)) ?></span></td>
              <td><?= $thu_text ?></td>
              <td>
                <div class="time-slot">
                  <span class="time-badge"><?= $gio_bd ?></span>
                  <span class="time-separator">→</span>
                  <span class="time-badge"><?= $gio_kt ?></span>
                </div>
              </td>
              <td><span class="room-badge"><?= htmlspecialchars($pt['phong']) ?></span></td>
              <td><strong class="student-id"><?= htmlspecialchars($pt['ma_hoc_sinh']) ?></strong></td>
              <td><span class="status-badge status-<?= $color ?>"><?= $icon.' '.$state ?></span></td>
              <td>
                <button class="btn btn-sm btn-primary"
                        onclick="viewExamDetails('<?= htmlspecialchars($pt['mon_thi'], ENT_QUOTES) ?>','<?= htmlspecialchars($pt['ngay_thi'], ENT_QUOTES) ?>','<?= $gio_bd ?>','<?= htmlspecialchars($pt['phong'], ENT_QUOTES) ?>','<?= htmlspecialchars($pt['ma_hoc_sinh'], ENT_QUOTES) ?>','<?= htmlspecialchars($user['ho_ten'], ENT_QUOTES) ?>')">
                        👁️ Chi tiết
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning text-center" style="text-align:center;padding:20px;border-radius:6px;">
        <div style="font-size:48px;margin-bottom:20px;">📭</div>
        <h4>Không tìm thấy phòng thi nào!</h4>
        <?php if ($mon_loc || $ngay_loc || $phong_loc): ?>
          <p>Vui lòng thử lại với bộ lọc khác hoặc <a href="javascript:void(0)" onclick="resetFilter()">hiển thị tất cả</a>.</p>
        <?php else: ?>
          <p>Bạn chưa có lịch thi nào được phân công. Vui lòng liên hệ quản trị viên.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal -->
<div id="examDetailModal" class="modal" style="display:none;">
  <div class="modal-content">
    <div class="modal-header">
      <h3>📋 Chi tiết phòng thi</h3>
      <span class="close" onclick="closeExamDetailModal()">&times;</span>
    </div>
    <div class="modal-body" id="examDetailContent"></div>
    <div class="modal-footer">
      <button class="btn btn-primary" onclick="closeExamDetailModal()">Đóng</button>
    </div>
  </div>
</div>

<script src="assets/js/app.js"></script>
<script>
function submitFilterForm(){ document.getElementById('filterForm').submit(); }
function resetFilter(){ window.location.href='index.php?controller=student&action=examRoom'; }

function viewExamDetails(monThi, ngayThi, gioBatDau, phong, maHS, hoTen){
  const dateObj=new Date(ngayThi);
  const ngayVN=dateObj.toLocaleDateString('vi-VN');
  const thuVN=dateObj.toLocaleDateString('vi-VN',{weekday:'long'});
  const content=`
    <div class="exam-detail">
      <div><strong>Môn thi:</strong> ${monThi}</div>
      <div><strong>Ngày thi:</strong> ${ngayVN} (${thuVN})</div>
      <div><strong>Giờ thi:</strong> ${gioBatDau}</div>
      <div><strong>Phòng thi:</strong> ${phong}</div>
      <div><strong>Mã học sinh:</strong> ${maHS}</div>
      <div><strong>Họ tên:</strong> ${hoTen}</div>
      <hr/>
      <div class="alert alert-warning">
        <ul><li>Có mặt trước 15 phút</li><li>Mang thẻ học sinh</li><li>Không mang tài liệu</li></ul>
      </div>
    </div>`;
  document.getElementById('examDetailContent').innerHTML=content;
  document.getElementById('examDetailModal').style.display='block';
}
function closeExamDetailModal(){ document.getElementById('examDetailModal').style.display='none'; }
</script>
</body>
</html>
