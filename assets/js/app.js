// Thông báo demo cho học sinh
const notificationsData = {
  student: [
    { id:1, content:"Lịch thi đã công bố", time:"1 giờ trước", unread:true },
    { id:2, content:"Cập nhật phòng thi môn Toán", time:"Hôm qua", unread:true }
  ]
};

function updateNotifications(role='student'){
  const list = document.getElementById('notificationList');
  const badge = document.getElementById('notificationBadge');
  if(!list || !badge) return;

  const arr = notificationsData[role] || [];
  const unread = arr.filter(x=>x.unread).length;
  badge.textContent = unread;

  list.innerHTML = '';
  arr.forEach(n=>{
    const div = document.createElement('div');
    div.className = 'notification-item' + (n.unread?' unread':'');
    div.innerHTML = `<div class="notification-content">${n.content}</div>
                     <div class="notification-time" style="font-size:12px;color:#7f8c8d">${n.time}</div>`;
    list.appendChild(div);
  });
}
function toggleNotifications(){
  const p = document.getElementById('notificationPanel');
  if(!p) return;
  p.style.display = p.style.display==='block'?'none':'block';
}
function markAllAsRead(){
  (notificationsData['student']||[]).forEach(n=>n.unread=false);
  updateNotifications('student');
}
window.addEventListener('click', e=>{
  const p = document.getElementById('notificationPanel');
  const i = document.querySelector('.notification-icon');
  if(!p || !i) return;
  if (!p.contains(e.target) && !i.contains(e.target)) p.style.display='none';
});
document.addEventListener('DOMContentLoaded', ()=>updateNotifications('student'));
