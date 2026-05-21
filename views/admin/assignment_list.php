<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar_admin.php"; ?>

<div class="main-content">
  <!-- TIÊU ĐỀ TRANG -->
  <div class="content-header">
    <div class="page-title">🏫 Phân công khối, lớp</div>
  </div>

  <!-- THANH CÔNG CỤ: LỌC KHỐI + CHỌN CHẾ ĐỘ -->
  <div class="card" style="margin-bottom:15px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <strong>Thanh công cụ phân công</strong>

    </div>
    <div class="card-body">
      <form method="GET" action="index.php" class="filter-section" style="margin:0;">
        <input type="hidden" name="controller" value="assignment">
        <input type="hidden" name="action" value="index">
        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">

        <div class="form-row" style="align-items:flex-end;">
          <div class="form-col">
            <label class="form-label">Khối</label>
            <select name="khoi" class="form-control">
              <option value="">Tất cả</option>
              <option value="K10" <?= ($khoiFilter === 'K10' ? 'selected' : '') ?>>Khối 10</option>
              <option value="K11" <?= ($khoiFilter === 'K11' ? 'selected' : '') ?>>Khối 11</option>
              <option value="K12" <?= ($khoiFilter === 'K12' ? 'selected' : '') ?>>Khối 12</option>
            </select>
          </div>

          <div class="form-col" style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary">Lọc</button>

            <a href="index.php?controller=assignment&action=index&mode=new" 
               class="btn <?= ($mode==='new')?'btn-success':'btn-outline' ?>">
              ➕ Phân công mới
            </a>

            <a href="index.php?controller=assignment&action=index&mode=edit" 
               class="btn <?= ($mode==='edit')?'btn-warning':'btn-outline' ?>">
              ✏️ Điều chỉnh phân công
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <!-- KHỐI CHÍNH: FORM PHÂN CÔNG -->
  <div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <strong><?= ($mode === 'edit') ? 'Điều chỉnh phân công' : 'Phân công mới' ?></strong>

    </div>
    <div class="card-body">
      <form method="POST" action="index.php?controller=assignment&action=save" id="assignForm">
        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">

        <!-- HÀNG 1: HỌC SINH + KHỐI -->
        <div class="form-row">
          <!-- HỌC SINH -->
          <div class="form-col">
            <label class="form-label">Học sinh</label>
            <?php if ($mode === 'edit' && !empty($selectedStudent)): ?>
              <input type="hidden" name="maHS" value="<?= htmlspecialchars($selectedStudent['maHS']) ?>">
              <select class="form-control" disabled>
                <option>
                  <?= htmlspecialchars($selectedStudent['maHS'] . ' - ' . $selectedStudent['hoVaTen']) ?>
                </option>
              </select>
            <?php else: ?>
              <select name="maHS" class="form-control" required>
                <option value="">-- Chọn học sinh --</option>
                <?php foreach($studentOptions as $st): ?>
                  <option value="<?= htmlspecialchars($st['maHS']) ?>">
                    <?= htmlspecialchars($st['maHS'] . ' - ' . $st['hoVaTen']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
          </div>

          <!-- KHỐI -->
          <div class="form-col">
            <label class="form-label">Khối</label>
            <?php $khoiValue = $selectedStudent['maKhoi'] ?? ''; ?>
            <select id="khoiSelectForm" class="form-control" onchange="syncKhoiAndFilterLop()">
              <option value="">-- Chọn khối --</option>
              <option value="K10" <?= ($khoiValue === 'K10' ? 'selected' : '') ?>>Khối 10</option>
              <option value="K11" <?= ($khoiValue === 'K11' ? 'selected' : '') ?>>Khối 11</option>
              <option value="K12" <?= ($khoiValue === 'K12' ? 'selected' : '') ?>>Khối 12</option>
            </select>
          </div>
        </div>

        <!-- HÀNG 2: LỚP + NÚT LƯU -->
        <div class="form-row" style="margin-top:12px;align-items:flex-end;">
          <!-- LỚP -->
          <div class="form-col">
            <label class="form-label">Lớp</label>
            <?php $lopCurrent = $selectedStudent['maLop'] ?? ''; ?>
            <select name="lop" id="lopSelectForm" class="form-control" required>
              <option value="">-- Chọn lớp --</option>
              <?php foreach($lopList as $lop): ?>
                <?php
                  $khoiLop  = $lop['maKhoi'] ?? '';
                  $selected = ($lopCurrent === $lop['maLop']) ? 'selected' : '';
                ?>
                <option value="<?= htmlspecialchars($lop['maLop']) ?>"
                        data-khoi="<?= htmlspecialchars($khoiLop) ?>"
                        <?= $selected ?>>
                  <?= htmlspecialchars($lop['tenLop']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- NÚT LƯU / HỦY -->
          <div class="form-col" style="display:flex;justify-content:flex-end;gap:10px;">
            <button type="submit" class="btn btn-primary">
              💾 Lưu phân công
            </button>
            <a href="index.php?controller=assignment&action=index&mode=<?= htmlspecialchars($mode) ?>" 
               class="btn btn-secondary">
              ❌ Hủy thao tác
            </a>
          </div>
        </div>


      </form>
    </div>
  </div>

  <!-- BẢNG DANH SÁCH PHÂN CÔNG -->
  <div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3 style="margin:0;font-size:16px;">Danh sách phân công hiện tại</h3>
      <?php if ($mode === 'edit'): ?>
        <span style="font-size:12px;color:#888;">
          Chọn "Điều chỉnh" để nạp lại thông tin vào form
        </span>
      <?php endif; ?>
    </div>
    <div class="card-body" style="padding-top:10px;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Mã HS</th>
            <th>Họ tên</th>
            <th>Khối</th>
            <th>Lớp</th>
            <th>Ban</th>
            <?php if ($mode === 'edit'): ?>
              <th>Thao tác</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)): foreach($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['maHS']) ?></td>
              <td><?= htmlspecialchars($r['hoVaTen']) ?></td>
              <td><?= htmlspecialchars($r['tenKhoi'] ?? $r['maKhoi']) ?></td>
              <td><?= htmlspecialchars($r['tenLop']) ?></td>
              <td><?= htmlspecialchars($r['maBan']) ?></td>
              <?php if ($mode === 'edit'): ?>
                <td>
                  <a href="index.php?controller=assignment&action=index&mode=edit&maHS=<?= urlencode($r['maHS']) ?>&khoi=<?= urlencode($khoiFilter) ?>"
                     class="btn btn-info btn-sm">
                    Điều chỉnh
                  </a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="<?= ($mode === 'edit') ? 6 : 5 ?>" style="text-align:center;">
                Không có dữ liệu phân công.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function syncKhoiAndFilterLop() {
  const khoi = document.getElementById('khoiSelectForm').value;
  const lopSelect = document.getElementById('lopSelectForm');
  Array.from(lopSelect.options).forEach(opt => {
    if (!opt.value) return;
    const k = opt.getAttribute('data-khoi');
    opt.style.display = (!khoi || khoi === k) ? 'block' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
  syncKhoiAndFilterLop();
});
</script>

<?php include "views/layout/footer.php"; ?>
