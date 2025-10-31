-- ===================================
-- HỆ THỐNG QUẢN LÝ HỌC SINH
-- Database Schema for XAMPP (MySQL/MariaDB)
-- ===================================

-- Xóa database nếu tồn tại
DROP DATABASE IF EXISTS QuanLyHocSinh;
CREATE DATABASE QuanLyHocSinh CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE QuanLyHocSinh;

-- ===================================
-- BẢNG NGƯỜI DÙNG VÀ TÀI KHOẢN
-- ===================================

-- Bảng Người dùng (Base table)
CREATE TABLE NGUOIDUNG (
    maNguoiDung VARCHAR(20) PRIMARY KEY,
    hoVaTen VARCHAR(100) NOT NULL,
    gioiTinh ENUM('Nam', 'Nữ', 'Khác'),
    ngaySinh DATE,
    diaChi TEXT,
    soDienThoai VARCHAR(15),
    email VARCHAR(100),
    ngayTao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Tài khoản
CREATE TABLE TAIKHOAN (
    tenDangNhap VARCHAR(50) PRIMARY KEY,
    matKhau VARCHAR(255) NOT NULL,
    vaiTro ENUM('QuanTri', 'BanGiamHieu', 'GiaoVien', 'PhuHuynh', 'HocSinh', 'SoGiaoDuc') NOT NULL,
    trangThai ENUM('HoatDong', 'BiKhoa') DEFAULT 'HoatDong',
    email VARCHAR(100),
    maNguoiDung VARCHAR(20),
    ngayTao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Sở Giáo dục
CREATE TABLE SOGIAODUC (
    maSoGD VARCHAR(20) PRIMARY KEY,
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Ban giám hiệu
CREATE TABLE BANGIAMHIEU (
    maBGH VARCHAR(20) PRIMARY KEY,
    chucVu VARCHAR(50),
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Giáo viên
CREATE TABLE GIAOVIEN (
    maGV VARCHAR(20) PRIMARY KEY,
    toChuyenMon VARCHAR(50),
    monGiangDay VARCHAR(50),
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG HỌC SINH VÀ PHỤ HUYNH
-- ===================================

-- Bảng Phụ huynh
CREATE TABLE PHUHUYNH (
    maPH VARCHAR(20) PRIMARY KEY,
    ngheNghiep VARCHAR(100),
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Học sinh
CREATE TABLE HOCSINH (
    maHS VARCHAR(20) PRIMARY KEY,
    dangThaiHocTap VARCHAR(50),
    soBaoDanh VARCHAR(20),
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Hồ sơ tuyển sinh (cho học sinh mới)
CREATE TABLE HOSOTUYENSINH (
    maHS VARCHAR(20) PRIMARY KEY,
    dangThai VARCHAR(50) DEFAULT 'ChoXetTuyen',
    ngayNop TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Quan hệ Phụ huynh - Học sinh
CREATE TABLE QUANHECHILD (
    maPH VARCHAR(20),
    maHS VARCHAR(20),
    quanHe VARCHAR(50),
    PRIMARY KEY (maPH, maHS),
    FOREIGN KEY (maPH) REFERENCES PHUHUYNH(maPH) ON DELETE CASCADE,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG KHỐI, LỚP, BAN
-- ===================================

-- Bảng Ban (Tự nhiên/Xã hội)
CREATE TABLE BANKP (
    maBan VARCHAR(20) PRIMARY KEY,
    tenBan VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Khối (10, 11, 12)
CREATE TABLE KHOI (
    maKhoi VARCHAR(20) PRIMARY KEY,
    tenKhoi VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Lớp
CREATE TABLE LOP (
    maLop VARCHAR(20) PRIMARY KEY,
    tenLop VARCHAR(50) NOT NULL,
    siSo INT DEFAULT 0,
    namHoc VARCHAR(20),
    maKhoi VARCHAR(20),
    maBan VARCHAR(20),
    FOREIGN KEY (maKhoi) REFERENCES KHOI(maKhoi),
    FOREIGN KEY (maBan) REFERENCES BANKP(maBan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Phân công khối/lớp cho học sinh
CREATE TABLE PHANCONG (
    maPC INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    maLop VARCHAR(20),
    maBan VARCHAR(20),
    namHoc VARCHAR(20),
    trangThai ENUM('DangHoc', 'DaDong') DEFAULT 'DangHoc',
    ngayPhanCong TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE,
    FOREIGN KEY (maLop) REFERENCES LOP(maLop),
    FOREIGN KEY (maBan) REFERENCES BANKP(maBan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG MÔN HỌC VÀ PHÂN CÔNG GIÁO VIÊN
-- ===================================

-- Bảng Môn học
CREATE TABLE MONHOC (
    maMon VARCHAR(20) PRIMARY KEY,
    tenMon VARCHAR(100) NOT NULL,
    soTiet INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Phân công giáo viên chủ nhiệm
CREATE TABLE PCGVCN (
    maPCGVCN INT AUTO_INCREMENT PRIMARY KEY,
    maGV VARCHAR(20),
    maLop VARCHAR(20),
    namHoc VARCHAR(20),
    FOREIGN KEY (maGV) REFERENCES GIAOVIEN(maGV) ON DELETE CASCADE,
    FOREIGN KEY (maLop) REFERENCES LOP(maLop) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Phân công giáo viên bộ môn
CREATE TABLE PCGVBM (
    maPCGVBM INT AUTO_INCREMENT PRIMARY KEY,
    maGV VARCHAR(20),
    maLop VARCHAR(20),
    maMon VARCHAR(20),
    namHoc VARCHAR(20),
    FOREIGN KEY (maGV) REFERENCES GIAOVIEN(maGV) ON DELETE CASCADE,
    FOREIGN KEY (maLop) REFERENCES LOP(maLop) ON DELETE CASCADE,
    FOREIGN KEY (maMon) REFERENCES MONHOC(maMon) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG THỜI KHÓA BIỂU
-- ===================================

-- Bảng Phòng học
CREATE TABLE PHONGHOC (
    maPhong VARCHAR(20) PRIMARY KEY,
    tenPhong VARCHAR(50) NOT NULL,
    sucChua INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Thời khóa biểu
CREATE TABLE THOIKHOABIEU (
    maTKB INT AUTO_INCREMENT PRIMARY KEY,
    maLop VARCHAR(20),
    maMon VARCHAR(20),
    maGV VARCHAR(20),
    ngay DATE,
    tiet INT,
    maPhong VARCHAR(20),
    namHoc VARCHAR(20),
    hocKy INT,
    FOREIGN KEY (maLop) REFERENCES LOP(maLop),
    FOREIGN KEY (maMon) REFERENCES MONHOC(maMon),
    FOREIGN KEY (maGV) REFERENCES GIAOVIEN(maGV),
    FOREIGN KEY (maPhong) REFERENCES PHONGHOC(maPhong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG ĐIỂM VÀ ĐÁNH GIÁ
-- ===================================

-- Bảng Điểm
CREATE TABLE DIEM (
    maDiem INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    maMon VARCHAR(20),
    diemSo DECIMAL(4,2),
    loaiDiem ENUM('MiengTX', 'Giua15Phut', 'MotTiet', 'GiuaKy', 'CuoiKy'),
    hocKy INT,
    namHoc VARCHAR(20),
    ngayNhap TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE,
    FOREIGN KEY (maMon) REFERENCES MONHOC(maMon)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Lịch sử sửa điểm (cho phúc khảo)
CREATE TABLE LICHSUSUADIEM (
    maLichSu INT AUTO_INCREMENT PRIMARY KEY,
    maDiem INT,
    diemCu DECIMAL(4,2),
    diemMoi DECIMAL(4,2),
    lyDo TEXT,
    nguoiSua VARCHAR(20),
    ngaySua TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maDiem) REFERENCES DIEM(maDiem) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Hạnh kiểm
CREATE TABLE HANHKIEM (
    maHK INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    xepLoai ENUM('Tot', 'Kha', 'TrungBinh', 'Yeu'),
    hocKy INT,
    namHoc VARCHAR(20),
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG BÀI TẬP VÀ KIỂM TRA
-- ===================================

-- Bảng Bài tập
CREATE TABLE BAITAP (
    maBaiTap INT AUTO_INCREMENT PRIMARY KEY,
    tenBaiTap VARCHAR(200) NOT NULL,
    moTa TEXT,
    noiDung TEXT,
    thoiHanNop DATETIME,
    maLop VARCHAR(20),
    maMon VARCHAR(20),
    maGV VARCHAR(20),
    ngayTao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    trangThai ENUM('DangMo', 'DaDong') DEFAULT 'DangMo',
    FOREIGN KEY (maLop) REFERENCES LOP(maLop),
    FOREIGN KEY (maMon) REFERENCES MONHOC(maMon),
    FOREIGN KEY (maGV) REFERENCES GIAOVIEN(maGV)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Minh chứng (File đính kèm bài tập)
CREATE TABLE MINHCHUNG (
    maMinhChung INT AUTO_INCREMENT PRIMARY KEY,
    tenFile VARCHAR(200),
    duongDan TEXT,
    loai ENUM('BaiTap', 'BaiNop', 'TaiLieu'),
    maBaiTap INT,
    FOREIGN KEY (maBaiTap) REFERENCES BAITAP(maBaiTap) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Báo cáo thống kê (Kết quả làm bài của học sinh)
CREATE TABLE BAOCAOTHONGKE (
    maBaoCao INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    maBaiTap INT,
    noiDung TEXT,
    diem DECIMAL(4,2),
    nhanXet TEXT,
    ngayNop TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    trangThai ENUM('ChuaNop', 'DaNop', 'DaCham') DEFAULT 'ChuaNop',
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE,
    FOREIGN KEY (maBaiTap) REFERENCES BAITAP(maBaiTap) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG TUYỂN SINH
-- ===================================

-- Bảng Trường học (cho tuyển sinh)
CREATE TABLE TRUONGHOC (
    maTruong VARCHAR(20) PRIMARY KEY,
    tenTruong VARCHAR(200) NOT NULL,
    diaChi TEXT,
    soDienThoai VARCHAR(15),
    chiTieuTuyenSinh INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Chi tiêu tuyển sinh
CREATE TABLE CHITIEU (
    maChiTieu INT AUTO_INCREMENT PRIMARY KEY,
    maTruong VARCHAR(20),
    namHoc VARCHAR(20),
    soHocSinh INT,
    soLopHoc INT,
    FOREIGN KEY (maTruong) REFERENCES TRUONGHOC(maTruong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Nguyện vọng tuyển sinh
CREATE TABLE NHANXETDANHGIA (
    maNhanXet INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    loaiNhanXet VARCHAR(50),
    noiDung TEXT,
    ngayDanhGia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Phòng thi
CREATE TABLE PHONGTHI (
    maPhong VARCHAR(20) PRIMARY KEY,
    tenPhong VARCHAR(50),
    soLuongHienTai INT DEFAULT 0,
    soLuongToiDa INT,
    maTruong VARCHAR(20),
    FOREIGN KEY (maTruong) REFERENCES TRUONGHOC(maTruong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Danh sách thi
CREATE TABLE DANHSACHTHI (
    maDSThi INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    maPhong VARCHAR(20),
    soBaoDanh VARCHAR(20),
    trangThai VARCHAR(50) DEFAULT 'DaXepPhong',
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE,
    FOREIGN KEY (maPhong) REFERENCES PHONGTHI(maPhong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Phân công giám thị
CREATE TABLE PCGIAMTHI (
    maPCGT INT AUTO_INCREMENT PRIMARY KEY,
    maGV VARCHAR(20),
    maPhong VARCHAR(20),
    ngayThi DATE,
    caThi VARCHAR(20),
    FOREIGN KEY (maGV) REFERENCES GIAOVIEN(maGV),
    FOREIGN KEY (maPhong) REFERENCES PHONGTHI(maPhong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- BẢNG THÔNG BÁO VÀ ĐIỂM DANH
-- ===================================

-- Bảng Thông báo
CREATE TABLE THONGBAO (
    maThongBao INT AUTO_INCREMENT PRIMARY KEY,
    tieuDe VARCHAR(200) NOT NULL,
    noiDung TEXT,
    nguoiGui VARCHAR(20),
    ngayGui TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    doiTuong ENUM('TatCa', 'HocSinh', 'PhuHuynh', 'GiaoVien', 'Lop')
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Đơn nghỉ học (Xin phép nghỉ)
CREATE TABLE DONNGHIHOC (
    maDon INT AUTO_INCREMENT PRIMARY KEY,
    maHS VARCHAR(20),
    lyDo TEXT,
    ngayNghi DATE,
    trangThai ENUM('ChoXuLy', 'DaDuyet', 'TuChoi') DEFAULT 'ChoXuLy',
    ngayGui TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maHS) REFERENCES HOCSINH(maHS) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Bảng Quản trị (Lưu thông tin cấp tài khoản)
CREATE TABLE QUANTRI (
    maQT VARCHAR(20) PRIMARY KEY,
    maNguoiDung VARCHAR(20),
    FOREIGN KEY (maNguoiDung) REFERENCES NGUOIDUNG(maNguoiDung) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ===================================
-- DỮ LIỆU MẪU CHI TIẾT
-- ===================================

-- 1. Thêm dữ liệu cho Khối
INSERT INTO KHOI (maKhoi, tenKhoi) VALUES 
('K10', 'Khối 10'),
('K11', 'Khối 11'),
('K12', 'Khối 12');

-- 2. Thêm dữ liệu cho Ban
INSERT INTO BANKP (maBan, tenBan) VALUES 
('TN', 'Tự nhiên'),
('XH', 'Xã hội');

-- 3. Thêm dữ liệu cho Môn học
INSERT INTO MONHOC (maMon, tenMon, soTiet) VALUES 
('TOAN', 'Toán', 5),
('VAN', 'Ngữ Văn', 5),
('ANH', 'Tiếng Anh', 4),
('LY', 'Vật Lý', 4),
('HOA', 'Hóa Học', 4),
('SINH', 'Sinh Học', 3),
('SU', 'Lịch Sử', 3),
('DIA', 'Địa Lý', 3),
('GDCD', 'Giáo dục công dân', 2),
('TD', 'Thể dục', 2),
('CNTT', 'Tin học', 2);

-- 4. Thêm dữ liệu Người dùng (Quản trị)
INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES
('ND001', 'Nguyễn Văn Admin', 'Nam', '1980-05-15', '123 Đường Lê Lợi, TP.HCM', '0901234567', 'admin@school.edu.vn'),
('ND002', 'Trần Thị Hiệu Trưởng', 'Nữ', '1975-08-20', '456 Đường Nguyễn Huệ, TP.HCM', '0902345678', 'hieutruong@school.edu.vn');

-- Tài khoản Quản trị
INSERT INTO TAIKHOAN (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung) VALUES
('admin', MD5('admin123'), 'QuanTri', 'HoatDong', 'admin@school.edu.vn', 'ND001'),
('hieutruong', MD5('ht123'), 'BanGiamHieu', 'HoatDong', 'hieutruong@school.edu.vn', 'ND002');

INSERT INTO QUANTRI (maQT, maNguoiDung) VALUES ('QT001', 'ND001');
INSERT INTO BANGIAMHIEU (maBGH, chucVu, maNguoiDung) VALUES ('BGH001', 'Hiệu trưởng', 'ND002');

-- 5. Thêm dữ liệu Giáo viên
INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES
('ND003', 'Lê Văn Toán', 'Nam', '1985-03-10', '789 Đường Trần Hưng Đạo, TP.HCM', '0903456789', 'levantoan@school.edu.vn'),
('ND004', 'Phạm Thị Văn', 'Nữ', '1987-07-22', '321 Đường Hai Bà Trưng, TP.HCM', '0904567890', 'phamthivan@school.edu.vn'),
('ND005', 'Hoàng Minh Lý', 'Nam', '1983-11-05', '654 Đường Lý Thường Kiệt, TP.HCM', '0905678901', 'hoangminhly@school.edu.vn'),
('ND006', 'Nguyễn Thị Hóa', 'Nữ', '1986-09-18', '987 Đường Võ Thị Sáu, TP.HCM', '0906789012', 'nguyenthihoa@school.edu.vn'),
('ND007', 'Trần Văn Anh', 'Nam', '1984-12-30', '147 Đường Phan Đình Phùng, TP.HCM', '0907890123', 'tranvananh@school.edu.vn');

INSERT INTO TAIKHOAN (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung) VALUES
('gv001', MD5('gv123'), 'GiaoVien', 'HoatDong', 'levantoan@school.edu.vn', 'ND003'),
('gv002', MD5('gv123'), 'GiaoVien', 'HoatDong', 'phamthivan@school.edu.vn', 'ND004'),
('gv003', MD5('gv123'), 'GiaoVien', 'HoatDong', 'hoangminhly@school.edu.vn', 'ND005'),
('gv004', MD5('gv123'), 'GiaoVien', 'HoatDong', 'nguyenthihoa@school.edu.vn', 'ND006'),
('gv005', MD5('gv123'), 'GiaoVien', 'HoatDong', 'tranvananh@school.edu.vn', 'ND007');

INSERT INTO GIAOVIEN (maGV, toChuyenMon, monGiangDay, maNguoiDung) VALUES
('GV001', 'Tổ Toán - Tin', 'Toán', 'ND003'),
('GV002', 'Tổ Ngữ Văn', 'Ngữ Văn', 'ND004'),
('GV003', 'Tổ Khoa học Tự nhiên', 'Vật Lý', 'ND005'),
('GV004', 'Tổ Khoa học Tự nhiên', 'Hóa Học', 'ND006'),
('GV005', 'Tổ Ngoại ngữ', 'Tiếng Anh', 'ND007');

-- 6. Thêm dữ liệu Lớp học
INSERT INTO LOP (maLop, tenLop, siSo, namHoc, maKhoi, maBan) VALUES
('L10A1', '10A1', 35, '2024-2025', 'K10', 'TN'),
('L10A2', '10A2', 38, '2024-2025', 'K10', 'XH'),
('L11A1', '11A1', 40, '2024-2025', 'K11', 'TN'),
('L11A2', '11A2', 37, '2024-2025', 'K11', 'XH'),
('L12A1', '12A1', 36, '2024-2025', 'K12', 'TN'),
('L12A2', '12A2', 39, '2024-2025', 'K12', 'XH');

-- 7. Thêm dữ liệu Phụ huynh
INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES
('ND008', 'Nguyễn Văn Bình', 'Nam', '1978-04-12', '111 Đường Lê Duẩn, TP.HCM', '0908901234', 'nvbinh@gmail.com'),
('ND009', 'Trần Thị Mai', 'Nữ', '1980-06-25', '222 Đường Nguyễn Thị Minh Khai, TP.HCM', '0909012345', 'ttmai@gmail.com'),
('ND010', 'Lê Văn Cường', 'Nam', '1976-09-08', '333 Đường Cách Mạng Tháng 8, TP.HCM', '0910123456', 'lvcuong@gmail.com'),
('ND011', 'Phạm Thị Dung', 'Nữ', '1979-11-15', '444 Đường 3 Tháng 2, TP.HCM', '0911234567', 'ptdung@gmail.com');

INSERT INTO TAIKHOAN (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung) VALUES
('ph001', MD5('ph123'), 'PhuHuynh', 'HoatDong', 'nvbinh@gmail.com', 'ND008'),
('ph002', MD5('ph123'), 'PhuHuynh', 'HoatDong', 'ttmai@gmail.com', 'ND009'),
('ph003', MD5('ph123'), 'PhuHuynh', 'HoatDong', 'lvcuong@gmail.com', 'ND010'),
('ph004', MD5('ph123'), 'PhuHuynh', 'HoatDong', 'ptdung@gmail.com', 'ND011');

INSERT INTO PHUHUYNH (maPH, ngheNghiep, maNguoiDung) VALUES
('PH001', 'Kỹ sư', 'ND008'),
('PH002', 'Giáo viên', 'ND009'),
('PH003', 'Bác sĩ', 'ND010'),
('PH004', 'Kinh doanh', 'ND011');

-- 8. Thêm dữ liệu Học sinh
INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES
('ND012', 'Nguyễn Minh An', 'Nam', '2009-01-15', '111 Đường Lê Duẩn, TP.HCM', '0912345678', 'nman@student.edu.vn'),
('ND013', 'Trần Thị Bảo', 'Nữ', '2009-03-20', '222 Đường Nguyễn Thị Minh Khai, TP.HCM', '0913456789', 'ttbao@student.edu.vn'),
('ND014', 'Lê Văn Cường', 'Nam', '2009-05-10', '333 Đường Cách Mạng Tháng 8, TP.HCM', '0914567890', 'lvcuong@student.edu.vn'),
('ND015', 'Phạm Thị Duyên', 'Nữ', '2009-07-25', '444 Đường 3 Tháng 2, TP.HCM', '0915678901', 'ptduyen@student.edu.vn'),
('ND016', 'Hoàng Văn Em', 'Nam', '2008-02-18', '555 Đường Điện Biên Phủ, TP.HCM', '0916789012', 'hvem@student.edu.vn'),
('ND017', 'Võ Thị Phương', 'Nữ', '2008-04-22', '666 Đường Hoàng Văn Thụ, TP.HCM', '0917890123', 'vtphuong@student.edu.vn'),
('ND018', 'Đặng Văn Giang', 'Nam', '2007-06-30', '777 Đường Pasteur, TP.HCM', '0918901234', 'dvgiang@student.edu.vn'),
('ND019', 'Bùi Thị Hoa', 'Nữ', '2007-08-15', '888 Đường Lý Tự Trọng, TP.HCM', '0919012345', 'bthoa@student.edu.vn');

INSERT INTO TAIKHOAN (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung) VALUES
('hs001', MD5('hs123'), 'HocSinh', 'HoatDong', 'nman@student.edu.vn', 'ND012'),
('hs002', MD5('hs123'), 'HocSinh', 'HoatDong', 'ttbao@student.edu.vn', 'ND013'),
('hs003', MD5('hs123'), 'HocSinh', 'HoatDong', 'lvcuong@student.edu.vn', 'ND014'),
('hs004', MD5('hs123'), 'HocSinh', 'HoatDong', 'ptduyen@student.edu.vn', 'ND015'),
('hs005', MD5('hs123'), 'HocSinh', 'HoatDong', 'hvem@student.edu.vn', 'ND016'),
('hs006', MD5('hs123'), 'HocSinh', 'HoatDong', 'vtphuong@student.edu.vn', 'ND017'),
('hs007', MD5('hs123'), 'HocSinh', 'HoatDong', 'dvgiang@student.edu.vn', 'ND018'),
('hs008', MD5('hs123'), 'HocSinh', 'HoatDong', 'bthoa@student.edu.vn', 'ND019');

INSERT INTO HOCSINH (maHS, dangThaiHocTap, soBaoDanh, maNguoiDung) VALUES
('HS001', 'Đang học', 'BD001', 'ND012'),
('HS002', 'Đang học', 'BD002', 'ND013'),
('HS003', 'Đang học', 'BD003', 'ND014'),
('HS004', 'Đang học', 'BD004', 'ND015'),
('HS005', 'Đang học', 'BD005', 'ND016'),
('HS006', 'Đang học', 'BD006', 'ND017'),
('HS007', 'Đang học', 'BD007', 'ND018'),
('HS008', 'Đang học', 'BD008', 'ND019');

-- 9. Quan hệ Phụ huynh - Học sinh
INSERT INTO QUANHECHILD (maPH, maHS, quanHe) VALUES
('PH001', 'HS001', 'Bố'),
('PH002', 'HS002', 'Mẹ'),
('PH003', 'HS003', 'Bố'),
('PH004', 'HS004', 'Mẹ');

-- 10. Phân công học sinh vào lớp
INSERT INTO PHANCONG (maHS, maLop, maBan, namHoc, trangThai) VALUES
('HS001', 'L10A1', 'TN', '2024-2025', 'DangHoc'),
('HS002', 'L10A1', 'TN', '2024-2025', 'DangHoc'),
('HS003', 'L10A2', 'XH', '2024-2025', 'DangHoc'),
('HS004', 'L10A2', 'XH', '2024-2025', 'DangHoc'),
('HS005', 'L11A1', 'TN', '2024-2025', 'DangHoc'),
('HS006', 'L11A2', 'XH', '2024-2025', 'DangHoc'),
('HS007', 'L12A1', 'TN', '2024-2025', 'DangHoc'),
('HS008', 'L12A2', 'XH', '2024-2025', 'DangHoc');

-- 11. Phân công giáo viên chủ nhiệm
INSERT INTO PCGVCN (maGV, maLop, namHoc) VALUES
('GV001', 'L10A1', '2024-2025'),
('GV002', 'L10A2', '2024-2025'),
('GV003', 'L11A1', '2024-2025'),
('GV004', 'L11A2', '2024-2025'),
('GV005', 'L12A1', '2024-2025');

-- 12. Phân công giáo viên bộ môn
INSERT INTO PCGVBM (maGV, maLop, maMon, namHoc) VALUES
('GV001', 'L10A1', 'TOAN', '2024-2025'),
('GV001', 'L11A1', 'TOAN', '2024-2025'),
('GV002', 'L10A1', 'VAN', '2024-2025'),
('GV002', 'L10A2', 'VAN', '2024-2025'),
('GV003', 'L10A1', 'LY', '2024-2025'),
('GV004', 'L10A1', 'HOA', '2024-2025'),
('GV005', 'L10A1', 'ANH', '2024-2025'),
('GV005', 'L10A2', 'ANH', '2024-2025');

-- 13. Thêm Phòng học
INSERT INTO PHONGHOC (maPhong, tenPhong, sucChua) VALUES
('P101', 'Phòng 101', 45),
('P102', 'Phòng 102', 45),
('P201', 'Phòng 201', 45),
('P202', 'Phòng 202', 45),
('P301', 'Phòng 301', 45),
('P302', 'Phòng 302', 45);

-- 14. Thời khóa biểu mẫu
INSERT INTO THOIKHOABIEU (maLop, maMon, maGV, ngay, tiet, maPhong, namHoc, hocKy) VALUES
('L10A1', 'TOAN', 'GV001', '2024-11-04', 1, 'P101', '2024-2025', 1),
('L10A1', 'VAN', 'GV002', '2024-11-04', 2, 'P101', '2024-2025', 1),
('L10A1', 'ANH', 'GV005', '2024-11-04', 3, 'P101', '2024-2025', 1),
('L10A1', 'LY', 'GV003', '2024-11-04', 4, 'P101', '2024-2025', 1),
('L10A1', 'HOA', 'GV004', '2024-11-04', 5, 'P101', '2024-2025', 1);

-- 15. Thêm điểm cho học sinh
INSERT INTO DIEM (maHS, maMon, diemSo, loaiDiem, hocKy, namHoc) VALUES
('HS001', 'TOAN', 8.5, 'MiengTX', 1, '2024-2025'),
('HS001', 'TOAN', 7.5, 'Giua15Phut', 1, '2024-2025'),
('HS001', 'TOAN', 8.0, 'GiuaKy', 1, '2024-2025'),
('HS001', 'VAN', 9.0, 'MiengTX', 1, '2024-2025'),
('HS002', 'TOAN', 7.0, 'MiengTX', 1, '2024-2025'),
('HS002', 'VAN', 8.5, 'MiengTX', 1, '2024-2025'),
('HS003', 'TOAN', 6.5, 'MiengTX', 1, '2024-2025'),
('HS004', 'VAN', 7.5, 'MiengTX', 1, '2024-2025');

-- 16. Hạnh kiểm
INSERT INTO HANHKIEM (maHS, xepLoai, hocKy, namHoc) VALUES
('HS001', 'Tot', 1, '2024-2025'),
('HS002', 'Tot', 1, '2024-2025'),
('HS003', 'Kha', 1, '2024-2025'),
('HS004', 'Tot', 1, '2024-2025'),
('HS005', 'Tot', 1, '2024-2025'),
('HS006', 'Kha', 1, '2024-2025'),
('HS007', 'Tot', 1, '2024-2025'),
('HS008', 'Kha', 1, '2024-2025');

-- 17. Bài tập
INSERT INTO BAITAP (tenBaiTap, moTa, noiDung, thoiHanNop, maLop, maMon, maGV, trangThai) VALUES
('Bài tập Chương 1 - Hàm số', 'Làm bài tập SGK trang 20-25', 'Giải các bài tập về hàm số bậc nhất và bậc hai', '2024-11-10 23:59:59', 'L10A1', 'TOAN', 'GV001', 'DangMo'),
('Làm văn nghị luận xã hội', 'Viết bài nghị luận về tác hại của rác thải nhựa', 'Bài luận từ 500-700 từ', '2024-11-12 23:59:59', 'L10A1', 'VAN', 'GV002', 'DangMo'),
('Bài tập Vật Lý - Chuyển động', 'Giải bài tập về chuyển động thẳng đều', 'Làm bài 1-5 trang 30 SGK', '2024-11-08 23:59:59', 'L10A1', 'LY', 'GV003', 'DangMo');

-- 18. Báo cáo bài tập (Bài nộp của học sinh)
INSERT INTO BAOCAOTHONGKE (maHS, maBaiTap, noiDung, diem, nhanXet, trangThai) VALUES
('HS001', 1, 'Em đã hoàn thành đầy đủ các bài tập', 9.0, 'Bài làm tốt, trình bày rõ ràng', 'DaCham'),
('HS002', 1, 'Em làm được 80% bài tập', 8.0, 'Cần chú ý thêm về phương pháp giải', 'DaCham'),
('HS001', 2, 'Bài luận về rác thải nhựa', 8.5, 'Ý tưởng hay, cần bổ sung thêm dẫn chứng', 'DaCham');

-- 19. Trường học (cho tuyển sinh)
INSERT INTO TRUONGHOC (maTruong, tenTruong, diaChi, soDienThoai, chiTieuTuyenSinh) VALUES
('TH001', 'THPT Lê Hồng Phong', '123 Đường Nguyễn Huệ, Q.1, TP.HCM', '0283822123', 500),
('TH002', 'THPT Trần Đại Nghĩa', '456 Đường Điện Biên Phủ, Q.3, TP.HCM', '0283911456', 600),
('TH003', 'THPT Gia Định', '789 Đường Phan Đăng Lưu, PN, TP.HCM', '0283966789', 450);

-- 20. Chỉ tiêu tuyển sinh
INSERT INTO CHITIEU (maTruong, namHoc, soHocSinh, soLopHoc) VALUES
('TH001', '2024-2025', 500, 12),
('TH002', '2024-2025', 600, 15),
('TH003', '2024-2025', 450, 10);

-- 21. Phòng thi
INSERT INTO PHONGTHI (maPhong, tenPhong, soLuongHienTai, soLuongToiDa, maTruong) VALUES
('PT001', 'Phòng thi 1', 0, 30, 'TH001'),
('PT002', 'Phòng thi 2', 0, 30, 'TH001'),
('PT003', 'Phòng thi 3', 0, 30, 'TH002'),
('PT004', 'Phòng thi 4', 0, 30, 'TH002'),
('PT005', 'Phòng thi 5', 0, 30, 'TH003');

-- 22. Thông báo
INSERT INTO THONGBAO (tieuDe, noiDung, nguoiGui, doiTuong) VALUES
('Thông báo lịch thi học kỳ 1', 'Lịch thi học kỳ 1 năm học 2024-2025 sẽ diễn ra từ ngày 15/12/2024', 'BGH001', 'TatCa'),
('Thông báo nghỉ lễ Quốc khánh', 'Trường nghỉ lễ từ 31/8 đến 3/9/2024', 'BGH001', 'TatCa'),
('Họp phụ huynh đầu năm học', 'Kính mời phụ huynh học sinh lớp 10 tham dự buổi họp vào 8h00 ngày 20/9/2024', 'GV001', 'PhuHuynh');

-- 23. Đơn xin nghỉ học
INSERT INTO DONNGHIHOC (maHS, lyDo, ngayNghi, trangThai) VALUES
('HS001', 'Em bị ốm, cần nghỉ dưỡng', '2024-11-05', 'DaDuyet'),
('HS003', 'Gia đình có việc đột xuất', '2024-11-06', 'ChoXuLy');

-- 24. Sở Giáo dục
INSERT INTO NGUOIDUNG (maNguoiDung, hoVaTen, gioiTinh, ngaySinh, diaChi, soDienThoai, email) VALUES
('ND020', 'Võ Văn Sơn', 'Nam', '1970-01-10', '01 Hoàng Việt, Q.Tân Bình, TP.HCM', '0283997001', 'vvson@sgd.hcm.gov.vn');

INSERT INTO TAIKHOAN (tenDangNhap, matKhau, vaiTro, trangThai, email, maNguoiDung) VALUES
('sogd001', MD5('sgd123'), 'SoGiaoDuc', 'HoatDong', 'vvson@sgd.hcm.gov.vn', 'ND020');

INSERT INTO SOGIAODUC (maSoGD, maNguoiDung) VALUES ('SGD001', 'ND020');
