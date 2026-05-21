// statistics.js - Chứa các hàm JavaScript cho trang thống kê

// Khởi tạo biểu đồ
function initCharts() {
    // Biểu đồ cột cho phân bố học sinh
    if (document.getElementById('studentDistributionChart')) {
        initStudentDistributionChart();
    }
    
    // Biểu đồ tròn cho cơ cấu học sinh
    if (document.getElementById('studentStructureChart')) {
        initStudentStructureChart();
    }
    
    // Biểu đồ học lực
    if (document.getElementById('academicChart')) {
        initAcademicChart();
    }
}

// Biểu đồ phân bố học sinh theo lớp
function initStudentDistributionChart() {
    const ctx = document.getElementById('studentDistributionChart').getContext('2d');
    // Dữ liệu sẽ được lấy từ server hoặc tính toán từ bảng
}

// Biểu đồ cơ cấu học sinh
function initStudentStructureChart() {
    const ctx = document.getElementById('studentStructureChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Nam', 'Nữ', 'Khác'],
            datasets: [{
                data: [60, 40, 0], // Dữ liệu mẫu
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + '%';
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Xuất báo cáo
function exportReport(type, reportType, params = {}) {
    let url = `index.php?controller=statistics&action=export&type=${type}&report_type=${reportType}`;
    
    // Thêm các tham số bổ sung
    for (const [key, value] of Object.entries(params)) {
        if (value) {
            url += `&${key}=${encodeURIComponent(value)}`;
        }
    }
    
    window.open(url, '_blank');
}

// AJAX load dữ liệu lọc
function loadFilterOptions(filterType, targetSelectId) {
    const selectElement = document.getElementById(targetSelectId);
    if (!selectElement) return;
    
    fetch(`index.php?controller=statistics&action=get_filter_options&type=${filterType}`)
        .then(response => response.json())
        .then(data => {
            selectElement.innerHTML = '<option value="">-- Chọn --</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.value || item.id;
                option.textContent = item.label || item.name;
                selectElement.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading filter options:', error);
        });
}

// Cập nhật URL với bộ lọc
function updateFilterURL() {
    const form = document.querySelector('form[method="GET"]');
    if (!form) return;
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    window.history.replaceState({}, '', `?${params.toString()}`);
}

// Thống kê nhanh - Refresh
function refreshQuickStats() {
    fetch('index.php?controller=statistics&action=get_quick_stats')
        .then(response => response.json())
        .then(data => {
            updateQuickStatsDisplay(data);
        });
}

function updateQuickStatsDisplay(stats) {
    const elements = {
        'totalStudents': document.getElementById('totalStudents'),
        'totalTeachers': document.getElementById('totalTeachers'),
        'totalClasses': document.getElementById('totalClasses'),
        'avgScore': document.getElementById('avgScore')
    };
    
    for (const [id, value] of Object.entries(stats)) {
        if (elements[id]) {
            animateCounter(elements[id], value);
        }
    }
}

// Hiệu ứng đếm số
function animateCounter(element, targetValue) {
    const current = parseFloat(element.textContent) || 0;
    const increment = (targetValue - current) / 50;
    let currentValue = current;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if ((increment > 0 && currentValue >= targetValue) || 
            (increment < 0 && currentValue <= targetValue)) {
            element.textContent = targetValue.toFixed(2);
            clearInterval(timer);
        } else {
            element.textContent = currentValue.toFixed(2);
        }
    }, 20);
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', function() {
    initCharts();
    
    // Auto-refresh quick stats mỗi 5 phút
    setInterval(refreshQuickStats, 300000);
    
    // Lắng nghe sự kiện thay đổi filter
    document.querySelectorAll('.filter-control').forEach(control => {
        control.addEventListener('change', updateFilterURL);
    });
});