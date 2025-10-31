<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<div class="main-content">
  <div class="content-header">
    <div class="page-title">🏫 Phân công khối / lớp</div>
  </div>

  <div class="filter-section">
    <a href="index.php?controller=assignment&action=index&mode=new" 
       class="btn <?= ($mode==='new')?'btn-primary':'btn-outline' ?>">Phân công mới</a>
    <a href="index.php?controller=assignment&action=index&mode=edit" 
       class="btn <?= ($mode==='edit')?'btn-primary':'btn-outline' ?>">Điều chỉnh phân công</a>
  </div>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php?controller=assignment&action=save" id="assignForm">
    <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">

    <div class="form-row">
      <div class="form-col">
        <label class="form-label">Khối</label>
        <select id="gradeSelect" class="form-control" onchange="filterByGrade()">
          <option value="">Tất cả</option>
          <?php foreach(['10','11','12'] as $g): ?>
            <option value="<?= $g ?>"><?= $g ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-col">
        <label class="form-label">Lớp</label>
        <select name="lop" id="classSelect" class="form-control" required>
          <option value="">-- Chọn lớp --</option>
          <?php foreach($lopList as $lop): ?>
            <option value="<?= $lop['maLop'] ?>" data-grade="<?= substr($lop['maLop'], 1, 2) ?>">
              <?= htmlspecialchars($lop['tenLop']) ?> (<?= $lop['siSo'] ?>/40)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-col">
        <label class="form-label">Ban</label>
        <select name="ban" class="form-control" required>
          <option value="TN">Tự nhiên</option>
          <option value="XH">Xã hội</option>
        </select>
      </div>

      <div class="form-col" style="display:flex;align-items:flex-end;">
        <button type="submit" class="btn btn-success">💾 Lưu phân công</button>
        <button type="button" class="btn btn-warning" style="margin-left:10px;" onclick="cancelAssign()">❌ Hủy</button>
      </div>
    </div>

    <div class="content-header" style="margin-top: 15px;">
      <h3><?= ($mode==='new')?'Danh sách học sinh chưa phân công':'Danh sách học sinh đã phân công' ?></h3>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th><input type="checkbox" id="chkAll" onclick="toggleAll(this)"></th>
          <th>Mã HS</th>
          <th>Họ tên</th>
          <th>Giới tính</th>
          <th>Ngày sinh</th>
          <th>Lớp hiện tại</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rows)): foreach($rows as $r): ?>
          <tr>
            <td><input type="checkbox" name="hoc_sinh[]" value="<?= htmlspecialchars($r['maHS']) ?>"></td>
            <td><?= htmlspecialchars($r['maHS']) ?></td>
            <td><?= htmlspecialchars($r['hoVaTen']) ?></td>
            <td><?= htmlspecialchars($r['gioiTinh']) ?></td>
            <td><?= htmlspecialchars($r['ngaySinh']) ?></td>
            <td><?= htmlspecialchars($r['tenLop'] ?? ($r['maLop'] ?? 'Chưa phân công')) ?></td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6" style="text-align:center;">Không có học sinh nào.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </form>
</div>

<script>
function toggleAll(src){
    document.querySelectorAll('input[name="hoc_sinh[]"]').forEach(c=>c.checked=src.checked);
}

function filterByGrade(){
  const g = document.getElementById('gradeSelect').value;
  document.querySelectorAll('#classSelect option').forEach(o=>{
    if(!o.value) return;
    const grade = o.dataset.grade;
    o.style.display = (g==='' || grade === g) ? 'block' : 'none';
  });
}

function cancelAssign(){
  if(confirm("Bạn có chắc muốn hủy thao tác?")) {
    window.location='index.php?controller=assignment&action=index';
  }
}

// Khởi tạo filter khi trang load
document.addEventListener('DOMContentLoaded', function() {
    filterByGrade();
});
</script>

<?php include "views/layout/footer.php"; ?>