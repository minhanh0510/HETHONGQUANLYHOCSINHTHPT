<div class="sidebar">
  <h3>📋 Menu Admin</h3>

  <a href="index.php?controller=studentAdmin&action=index" class="nav-link">👩‍🎓 Quản lý hồ sơ học sinh</a>
  <a href="index.php?controller=scheduleAdmin&action=index" class="nav-link">📚 Quản lý lịch học</a>
  <a href="index.php?controller=assignment&action=index" class="nav-link">🏫 Phân công khối lớp</a>
  <a href="index.php?controller=student&action=examRoom" class="nav-link">🧾 Xem lịch thi (chế độ học sinh)</a>
  <a href="index.php?controller=student&action=examRoom" class="nav-link">🧾 Xem lịch thi (chế độ học sinh)</a>
</div>

<style>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100vh;
  background: #1e293b;
  color: #fff;
  padding: 20px;
  z-index: 99999;
}
.sidebar h3 {
  margin-bottom: 20px;
  border-bottom: 1px solid #334155;
  padding-bottom: 10px;
  text-align: center;
}
.sidebar a {
  display: block;
  color: #e2e8f0;
  text-decoration: none;
  padding: 10px 15px;
  margin: 8px 0;
  border-radius: 6px;
  transition: background 0.2s;
}
.sidebar a:hover {
  background: #334155;
}
</style>

<script>
// ✅ Gỡ toàn bộ hành vi ngăn click cũ (nếu template cũ có)
document.querySelectorAll('.sidebar a').forEach(a => {
  a.onclick = e => {
    const href = a.getAttribute('href');
    if (href && href.startsWith('index.php')) {
      window.location.href = href; // ép điều hướng thật
      e.stopPropagation(); // dừng JS cũ
      return true;
    }
  };
});
</script>
