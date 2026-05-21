<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<div class="main-content">
  <div class="content-header">
    <div class="page-title">📚 Quản lý hồ sơ học sinh</div>
  </div>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- BỘ LỌC -->
  <form method="GET" action="index.php" class="filter-section">
    <input type="hidden" name="controller" value="studentAdmin">
    <input type="hidden" name="action" value="index">
    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Lớp</label>
        <select name="maLop" class="form-control">
          <option value="">Tất cả</option>
          <?php foreach($lopList as $lop): ?>
            <option value="<?= htmlspecialchars($lop['maLop']) ?>" 
              <?= (($_GET['maLop'] ?? '') === $lop['maLop']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($lop['tenLop']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-col">
        <label class="form-label">Giới tính</label>
        <select name="gioiTinh" class="form-control">
          <option value="">Tất cả</option>
          <option value="Nam"   <?= (($_GET['gioiTinh'] ?? '') === 'Nam')?'selected':''; ?>>Nam</option>
          <option value="Nữ"    <?= (($_GET['gioiTinh'] ?? '') === 'Nữ')?'selected':''; ?>>Nữ</option>
        </select>
      </div>
      <div class="form-col">
        <label class="form-label">Từ khóa</label>
        <input type="text" name="keyword" class="form-control"
               value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
               placeholder="Mã HS hoặc Họ tên">
      </div>
      <div class="form-col" style="display:flex;align-items:flex-end;gap:10px;">
        <button type="submit" class="btn btn-primary">Lọc</button>
        <a href="index.php?controller=studentAdmin&action=index" class="btn btn-secondary">Xóa lọc</a>
      </div>
    </div>
  </form>

  <!-- FORM THÊM / SỬA -->
  <div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <strong><?= $student ? "Cập nhật hồ sơ" : "Thêm mới hồ sơ" ?></strong>
      <span style="font-size:12px;color:#888;">* Bắt buộc nhập</span>
    </div>
    <div class="card-body">
      <form method="POST" action="index.php?controller=studentAdmin&action=save">
        <input type="hidden" name="is_edit" value="<?= $student ? 1 : 0 ?>">

        <?php if ($student): ?>
          <input type="hidden" name="maHS" value="<?= htmlspecialchars($student['maHS']) ?>">
          <input type="hidden" name="maNguoiDung" value="<?= htmlspecialchars($student['maNguoiDung']) ?>">
        <?php endif; ?>

        <!-- KHỐI: THÔNG TIN CÁ NHÂN -->
        <div class="form-section" style="margin-bottom:15px;border-bottom:1px solid #eee;padding-bottom:10px;">
          <h4 style="font-size:15px;margin-bottom:10px;">👤 Thông tin cá nhân</h4>
          <div class="form-row">
            <div class="form-col">
              <label class="form-label">Họ và tên *</label>
              <input type="text" name="hoVaTen" class="form-control" required
                     placeholder="Nguyễn Văn A"
                     value="<?= htmlspecialchars($student['hoVaTen'] ?? '') ?>">
            </div>
            <div class="form-col">
              <label class="form-label">Giới tính *</label>
              <select name="gioiTinh" class="form-control" required>
                <option value="">-- Chọn --</option>
                <option value="Nam" <?= (($student['gioiTinh'] ?? '') === 'Nam')?'selected':''; ?>>Nam</option>
                <option value="Nữ"  <?= (($student['gioiTinh'] ?? '') === 'Nữ')?'selected':''; ?>>Nữ</option>
              </select>
            </div>
            <div class="form-col">
              <label class="form-label">Ngày sinh *</label>
              <input type="date" name="ngaySinh" class="form-control" required
                     value="<?= htmlspecialchars($student['ngaySinh'] ?? '') ?>">
            </div>
          </div>

          <div class="form-row" style="margin-top:10px;">
            <div class="form-col" style="flex:2;">
              <label class="form-label">Địa chỉ</label>
              <input type="text" name="diaChi" class="form-control"
                     placeholder="Số nhà, đường, phường/xã, tỉnh/thành"
                     value="<?= htmlspecialchars($student['diaChi'] ?? '') ?>">
            </div>
          </div>
        </div>

        <!-- KHỐI: LIÊN HỆ & TRẠNG THÁI -->
        <div class="form-section">
          <h4 style="font-size:15px;margin-bottom:10px;">📞 Liên hệ & trạng thái</h4>
          <div class="form-row">
            <div class="form-col">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="soDienThoai" class="form-control"
                     placeholder="Ví dụ: 0912345678"
                     value="<?= htmlspecialchars($student['soDienThoai'] ?? '') ?>">
            </div>
            <div class="form-col">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control"
                     placeholder="ví dụ: hs001@example.com"
                     value="<?= htmlspecialchars($student['email'] ?? '') ?>">
            </div>
            <div class="form-col">
              <label class="form-label">Trạng thái học tập</label>
              <?php $dt = $student['dangThaiHocTap'] ?? 'Đang học'; ?>
              <select name="dangThaiHocTap" class="form-control">
                <option value="Đang học" <?= $dt === 'Đang học' || $dt === 'DangHoc' ? 'selected':''; ?>>Đang học</option>
                <option value="Tạm nghỉ" <?= $dt === 'Tạm nghỉ' ? 'selected':''; ?>>Tạm nghỉ</option>
                <option value="Thôi học" <?= $dt === 'Thôi học' ? 'selected':''; ?>>Thôi học</option>
              </select>
            </div>
          </div>
        </div>

        <!-- NÚT LƯU / HỦY -->
        <div class="form-row" style="margin-top:15px;justify-content:flex-end;">
          <div class="form-col" style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="submit" class="btn btn-primary">
              <?= $student ? "Cập nhật" : "Thêm mới" ?>
            </button>
            <?php if ($student): ?>
              <a href="index.php?controller=studentAdmin&action=index" class="btn btn-secondary">
                Hủy
              </a>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- BẢNG DANH SÁCH -->
  <div class="content-header">
    <h3>Danh sách học sinh</h3>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th>Mã HS</th>
        <th>Họ và tên</th>
        <th>Giới tính</th>
        <th>Ngày sinh</th>
        <th>Lớp</th>
        <th>Khối</th>
        <th>Ban</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($rows)): foreach($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['maHS']) ?></td>
          <td><?= htmlspecialchars($r['hoVaTen']) ?></td>
          <td><?= htmlspecialchars($r['gioiTinh']) ?></td>
          <td><?= htmlspecialchars($r['ngaySinh']) ?></td>
          <td><?= htmlspecialchars($r['tenLop'] ?? 'Chưa phân lớp') ?></td>
          <td><?= htmlspecialchars($r['tenKhoi'] ?? '') ?></td>
          <td><?= htmlspecialchars($r['maBan'] ?? '') ?></td>
          <td><?= htmlspecialchars($r['dangThaiHocTap']) ?></td>
          <td>
            <a href="index.php?controller=studentAdmin&action=edit&maHS=<?= urlencode($r['maHS']) ?>" 
               class="btn btn-info btn-sm">✏️</a>
            <a href="index.php?controller=studentAdmin&action=delete&maHS=<?= urlencode($r['maHS']) ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Xóa học sinh này?')">🗑️</a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="9" style="text-align:center;">Không có học sinh nào.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>
