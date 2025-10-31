<h2>Quản lý hồ sơ học sinh</h2>
<table border="1" cellpadding="10">
<tr><th>Mã HS</th><th>Họ tên</th><th>Lớp</th><th>Ngày sinh</th><th>Email</th></tr>
<?php foreach ($students as $st): ?>
<tr>
  <td><?= $st['student_code'] ?></td>
  <td><?= $st['name'] ?></td>
  <td><?= $st['class'] ?></td>
  <td><?= $st['dob'] ?></td>
  <td><?= $st['email'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<a href="index.php?controller=auth&action=logout">Đăng xuất</a>
