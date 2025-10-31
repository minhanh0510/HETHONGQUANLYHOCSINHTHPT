<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<div class="main-content">
  <div class="content-header">
    <div class="page-title">📝 <?= empty($row) ? 'Thêm mới' : 'Sửa' ?> lịch học</div>
    <a href="index.php?controller=scheduleAdmin&action=index" class="btn btn-outline">← Quay lại</a>
  </div>

  <form method="POST" action="index.php?controller=scheduleAdmin&action=save">
    <input type="hidden" name="is_edit" value="<?= !empty($row) ? '1' : '' ?>">
    <input type="hidden" name="maTKB" value="<?= $row['maTKB'] ?? '' ?>">

    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Lớp</label>
        <select name="maLop" class="form-control" required>
          <option value="">-- Chọn lớp --</option>
          <!-- Cần thêm danh sách lớp từ database -->
        </select>
      </div>
      <div class="form-col">
        <label class="form-label">Môn học</label>
        <select name="maMon" class="form-control" required>
          <option value="">-- Chọn môn --</option>
          <!-- Cần thêm danh sách môn từ database -->
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Giáo viên</label>
        <select name="maGV" class="form-control" required>
          <option value="">-- Chọn giáo viên --</option>
          <!-- Cần thêm danh sách giáo viên từ database -->
        </select>
      </div>
      <div class="form-col">
        <label class="form-label">Ngày học</label>
        <input type="date" name="ngay" class="form-control" value="<?= $row['ngay'] ?? '' ?>" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Tiết</label>
        <input type="number" name="tiet" class="form-control" value="<?= $row['tiet'] ?? '' ?>" min="1" max="10" required>
      </div>
      <div class="form-col">
        <label class="form-label">Phòng</label>
        <select name="maPhong" class="form-control" required>
          <option value="">-- Chọn phòng --</option>
          <!-- Cần thêm danh sách phòng từ database -->
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Năm học</label>
        <input type="text" name="namHoc" class="form-control" value="<?= $row['namHoc'] ?? '2024-2025' ?>" required>
      </div>
      <div class="form-col">
        <label class="form-label">Học kỳ</label>
        <select name="hocKy" class="form-control" required>
          <option value="1" <?= ($row['hocKy'] ?? '') == '1' ? 'selected' : '' ?>>Học kỳ 1</option>
          <option value="2" <?= ($row['hocKy'] ?? '') == '2' ? 'selected' : '' ?>>Học kỳ 2</option>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-success">💾 Lưu</button>
      <a href="index.php?controller=scheduleAdmin&action=index" class="btn btn-warning">❌ Hủy</a>
    </div>
  </form>
</div>

<?php include "views/layout/footer.php"; ?>