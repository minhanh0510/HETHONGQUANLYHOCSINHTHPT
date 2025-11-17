// --- 1. CHỨC NĂNG BẬT/TẮT MẬT KHẨU (TỪ YÊU CẦU TRƯỚC) ---
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function (e) {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Đổi biểu tượng
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bx-hide');
                icon.classList.toggle('bx-show');
            }
        });
    }
});


// --- 2. HỆ THỐNG THÔNG BÁO ĐA VAI TRÒ ---
const notificationsData = {
    student: [
        { id:1, content:"Lịch thi đã công bố", time:"1 giờ trước", unread:true },
        { id:2, content:"Cập nhật phòng thi môn Toán", time:"Hôm qua", unread:true },
        { id:3, content:"Nhắc nhở nộp học phí", time:"2 ngày trước", unread:false }
    ],
    teacher: [
        { id:10, content:"Yêu cầu duyệt điểm thi của lớp 10A1", time:"15 phút trước", unread:true },
        { id:11, content:"Lịch họp hội đồng tháng 11", time:"4 giờ trước", unread:true },
        { id:12, content:"Hoàn thành nhập liệu điểm", time:"Hôm qua", unread:false }
    ],
    admin: [
        { id:20, content:"Có yêu cầu hỗ trợ tài khoản mới", time:"Vừa xong", unread:true },
        { id:21, content:"Báo cáo sự cố hệ thống đã được khắc phục", time:"10 giờ trước", unread:false },
        { id:22, content:"Thống kê người dùng mới trong tuần", time:"Hôm qua", unread:false }
    ]
};

// Sửa hàm để nhận 'role' (vai trò) làm tham số
function updateNotifications(role = 'student'){
    const list = document.getElementById('notificationList');
    const badge = document.getElementById('notificationBadge');
    if(!list || !badge) return;

    const arr = notificationsData[role] || [];
    const unread = arr.filter(x => x.unread).length;
    badge.textContent = unread > 99 ? '99+' : unread;

    list.innerHTML = ''; // Xóa nội dung cũ
    
    if (arr.length === 0) {
        list.innerHTML = '<div class="notification-empty">Không có thông báo mới.</div>';
    } else {
        arr.forEach(n => {
            const div = document.createElement('div');
            div.className = 'notification-item' + (n.unread ? ' unread' : '');
            div.innerHTML = `<div class="notification-content">${n.content}</div>
                             <div class="notification-time" style="font-size:12px;color:#7f8c8d">${n.time}</div>`;
            list.appendChild(div);
        });
    }
}

function toggleNotifications(){
    const p = document.getElementById('notificationPanel');
    if(!p) return;
    p.style.display = p.style.display === 'block' ? 'none' : 'block';
}

function markAllAsRead(role = 'student'){
    (notificationsData[role] || []).forEach(n => n.unread = false);
    updateNotifications(role);
}

// Đóng panel khi click ra ngoài
window.addEventListener('click', e => {
    const p = document.getElementById('notificationPanel');
    const i = document.querySelector('.notification-icon');
    if(!p || !i) return;
    // Đảm bảo click không nằm trong panel, không nằm trong icon, và không phải là con của panel
    if (!p.contains(e.target) && !i.contains(e.target) && !e.target.closest('#notificationPanel')) {
        p.style.display = 'none';
    }
});

// Khởi chạy khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
    // THAY ĐỔI VAI TRÒ (ROLE) Ở DƯỚI ĐÂY ĐỂ KIỂM TRA: 'student', 'teacher', 'admin'
    const currentRole = 'student'; 
    updateNotifications(currentRole);

    // Gắn sự kiện cho nút "Đánh dấu đã đọc"
    const markAllBtn = document.getElementById('markAllBtn');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', () => markAllAsRead(currentRole));
    }
});