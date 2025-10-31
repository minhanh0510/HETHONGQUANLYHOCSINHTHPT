<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<div class="main-content">
  <div class="content-header">
    <div class="page-title">📚 Quản lý lịch học</div>
    <a href="index.php?controller=scheduleAdmin&action=form" class="btn btn-primary">➕ Thêm lịch học</a>
  </div>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <table class="data-table">
    <thead>
      <tr>
        <th>Mã HS</th>
        <th>Họ và tên</th>
        <th>Giới tính</th>
        <th>Ngày sinh</th>
        <th>Lớp</th>
        <th>Ban</th>
        <th>Năm học</th>
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
          <td><?= htmlspecialchars($r['maLop']) ?></td>
          <td><?= htmlspecialchars($r['maBan']) ?></td>
          <td><?= htmlspecialchars($r['namHoc']) ?></td>
          <td>
            <a href="index.php?controller=studentAdmin&action=edit&id=<?= $r['maHS'] ?>" class="btn btn-info">✏️</a>
            <a href="index.php?controller=studentAdmin&action=delete&id=<?= $r['maHS'] ?>" 
               class="btn btn-danger" onclick="return confirm('Xóa học sinh này?')">🗑️</a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="8" style="text-align:center;">Không có học sinh nào.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include "views/layout/footer.php"; ?>