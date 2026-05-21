-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 24, 2025 lúc 06:49 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlyhocsinh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baitap`
--

CREATE TABLE `baitap` (
  `maBaiTap` int(11) NOT NULL,
  `tenBaiTap` varchar(200) NOT NULL,
  `moTa` text DEFAULT NULL,
  `noiDung` text DEFAULT NULL,
  `thoiHanNop` datetime DEFAULT NULL,
  `maLop` varchar(20) DEFAULT NULL,
  `maMon` varchar(20) DEFAULT NULL,
  `maGV` varchar(20) DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangThai` enum('DangMo','DaDong') DEFAULT 'DangMo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `baitap`
--

INSERT INTO `baitap` (`maBaiTap`, `tenBaiTap`, `moTa`, `noiDung`, `thoiHanNop`, `maLop`, `maMon`, `maGV`, `ngayTao`, `trangThai`) VALUES
(1, 'Bài tập Chương 1 - Hàm số', 'Làm bài tập SGK trang 20-25', 'Giải các bài tập về hàm số bậc nhất và bậc hai', '2024-11-10 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-04 02:04:56', 'DangMo'),
(2, 'Làm văn nghị luận xã hội', 'Viết bài nghị luận về tác hại của rác thải nhựa', 'Bài luận từ 500-700 từ', '2024-11-12 23:59:59', 'L10A1', 'VAN', 'GV002', '2025-10-31 04:33:15', 'DangMo'),
(3, 'Bài tập Vật Lý - Chuyển động', 'Giải bài tập về chuyển động thẳng đều', 'Làm bài 1-5 trang 30 SGK', '2024-11-08 23:59:59', 'L10A1', 'LY', 'GV003', '2025-10-31 04:33:15', 'DangMo'),
(4, 'Bài tập Chương 1 - Hàm số', 'Làm bài tập SGK trang 20-25', 'Giải các bài tập về hàm số bậc nhất và bậc hai, vẽ đồ thị', '2025-12-15 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(5, 'Bài tập Chương 2 - Phương trình và bất phương trình', 'Bài tập về giải phương trình bậc 2', 'Giải các bài 1-10 trang 45-48 SGK. Áp dụng công thức nghiệm', '2025-12-20 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(6, 'Bài tập Chương 3 - Hệ phương trình', 'Giải hệ phương trình bằng nhiều phương pháp', 'Làm bài tập 1-8 trang 60-62. Yêu cầu giải bằng cả 3 phương pháp: thế, cộng đại số, đồ thị', '2025-12-25 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(7, 'Kiểm tra 15 phút - Chương 1', 'Kiểm tra kiến thức Chương 1', '5 câu trắc nghiệm về hàm số. Thời gian: 15 phút', '2025-12-10 14:30:00', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(9, 'Bài tập nâng cao - Ứng dụng hàm số', 'Bài tập vận dụng cao', 'Giải các bài toán thực tế ứng dụng hàm số: bài toán tối ưu, bài toán kinh tế', '2025-12-28 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(10, 'Ôn tập giữa kỳ 1', 'Ôn tập toàn bộ kiến thức đã học', 'Làm đề cương ôn tập giữa kỳ. Bao gồm tất cả dạng bài đã học', '2026-01-05 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(11, 'Bài thực hành - Giải toán trên máy tính Casio', 'Thực hành sử dụng máy tính', 'Giải các bài toán về phương trình, hệ phương trình bằng máy tính Casio', '2025-12-18 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(12, 'Bài tập nhóm - Dự án toán học', 'Làm việc theo nhóm 4-5 người', 'Chọn một chủ đề thực tế và ứng dụng kiến thức toán học đã học. Làm slide thuyết trình', '2026-01-10 23:59:59', 'L10A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(13, 'Bài tập Chương 1 - Hàm số lượng giác', 'Ôn tập và nâng cao', 'Giải bài tập 1-12 trang 25-28 SGK Toán 11', '2025-12-15 23:59:59', 'L11A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(14, 'Bài tập Chương 2 - Tổ hợp và xác suất', 'Bài tập về hoán vị, chỉnh hợp, tổ hợp', 'Làm bài 1-15 trang 42-45. Chú ý phân biệt các công thức', '2025-12-20 23:59:59', 'L11A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(15, 'Kiểm tra 15 phút - Hàm lượng giác', 'Kiểm tra nhanh kiến thức', '5 câu trắc nghiệm về công thức lượng giác cơ bản', '2025-12-12 14:30:00', 'L11A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(16, 'Kiểm tra 1 tiết - Tổ hợp xác suất', 'Kiểm tra chương 2', 'Phần trắc nghiệm và tự luận về tổ hợp, xác suất', '2025-12-23 07:30:00', 'L11A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo'),
(17, 'Ôn tập cuối kỳ 1', 'Ôn tập tổng hợp', 'Làm đề cương ôn tập cuối kỳ. Ôn tất cả chương đã học trong học kỳ 1', '2026-01-15 23:59:59', 'L11A1', 'TOAN', 'GV001', '2025-12-03 02:31:54', 'DangMo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bangiamhieu`
--

CREATE TABLE `bangiamhieu` (
  `maBGH` varchar(20) NOT NULL,
  `chucVu` varchar(50) DEFAULT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bangiamhieu`
--

INSERT INTO `bangiamhieu` (`maBGH`, `chucVu`, `maNguoiDung`) VALUES
('BGH001', 'Hiệu trưởng', 'ND002');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bankp`
--

CREATE TABLE `bankp` (
  `maBan` varchar(20) NOT NULL,
  `tenBan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bankp`
--

INSERT INTO `bankp` (`maBan`, `tenBan`) VALUES
('TN', 'Tự nhiên'),
('XH', 'Xã hội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baocaothongke`
--

CREATE TABLE `baocaothongke` (
  `maBaoCao` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maBaiTap` int(11) DEFAULT NULL,
  `noiDung` text DEFAULT NULL,
  `deBai` text DEFAULT NULL COMMENT 'Đề bài kiểm tra/bài tập',
  `tenFileDe` varchar(255) DEFAULT NULL COMMENT 'Tên file đề bài',
  `duongDanFileDe` text DEFAULT NULL COMMENT 'Đường dẫn file đề',
  `tenFile` varchar(255) DEFAULT NULL,
  `duongDanFile` text DEFAULT NULL,
  `loaiFile` varchar(50) DEFAULT NULL,
  `kichThuocFile` int(11) DEFAULT NULL COMMENT 'Kích thước file (bytes)',
  `diem` decimal(4,2) DEFAULT NULL,
  `nhanXet` text DEFAULT NULL,
  `ngayNop` timestamp NOT NULL DEFAULT current_timestamp(),
  `trangThai` enum('ChuaNop','DaNop','DaCham') DEFAULT 'ChuaNop'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `baocaothongke`
--

INSERT INTO `baocaothongke` (`maBaoCao`, `maHS`, `maBaiTap`, `noiDung`, `deBai`, `tenFileDe`, `duongDanFileDe`, `tenFile`, `duongDanFile`, `loaiFile`, `kichThuocFile`, `diem`, `nhanXet`, `ngayNop`, `trangThai`) VALUES
(1, 'HS001', 1, 'Làm bài tập SGK trang 20-25 về hàm số bậc nhất và bậc hai', 'ĐỀ BÀI TẬP:\r\nBài 1: Cho hàm số y = 2x + 3\r\na) Tìm tọa độ giao điểm của đồ thị với trục Ox, Oy\r\nb) Vẽ đồ thị hàm số\r\n\r\nBài 2: Cho hàm số y = x² - 4x + 3  \r\na) Tìm tọa độ đỉnh của parabol\r\nb) Lập bảng biến thiên\r\nc) Vẽ đồ thị hàm số\r\n\r\nBài 3: Tìm m để phương trình x² - 2x + m = 0 có hai nghiệm phân biệt\r\n\r\nBài 4-10: (Các bài tập từ SGK trang 20-25)', 'DeBaiTap_Toan_Chuong1.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_baitap1.pdf', 'BaiTap_Toan_Chuong1_NguyenMinhAn.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS001_baitap1_20241108.pdf', 'pdf', 2458624, 9.00, 'Bài làm tốt, trình bày rõ ràng, vẽ đồ thị chính xác. Em cần chú ý kiểm tra điều kiện xác định ở bài 10.', '2024-11-08 03:30:00', 'DaCham'),
(2, 'HS002', 1, 'Làm bài tập SGK trang 20-25 về hàm số bậc nhất và bậc hai', 'ĐỀ BÀI TẬP:\r\nBài 1: Cho hàm số y = 2x + 3\r\na) Tìm tọa độ giao điểm của đồ thị với trục Ox, Oy\r\nb) Vẽ đồ thị hàm số\r\n\r\nBài 2: Cho hàm số y = x² - 4x + 3  \r\na) Tìm tọa độ đỉnh của parabol\r\nb) Lập bảng biến thiên\r\nc) Vẽ đồ thị hàm số\r\n\r\nBài 3: Tìm m để phương trình x² - 2x + m = 0 có hai nghiệm phân biệt\r\n\r\nBài 4-10: (Các bài tập từ SGK trang 20-25)', 'DeBaiTap_Toan_Chuong1.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_baitap1.pdf', 'BaiTap_Toan_Chuong1_TranThiBao.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS002_baitap1_20241109.pdf', 'pdf', 2156032, 8.00, 'Làm được 8/10 bài. Cần ôn lại phần tìm tham số và điều kiện có nghiệm của phương trình bậc hai.', '2024-11-09 07:15:00', 'DaCham'),
(3, 'HS001', 2, 'Viết bài nghị luận xã hội về tác hại của rác thải nhựa', 'ĐỀ BÀI:\r\nViết bài văn nghị luận xã hội (500-700 từ) về chủ đề: \"Tác hại của rác thải nhựa đối với môi trường và giải pháp khắc phục\"\r\n\r\nYêu cầu:\r\n- Có mở bài giới thiệu vấn đề\r\n- Thân bài: Phân tích tác hại và đưa ra giải pháp cụ thể\r\n- Kết bài: Khẳng định lại quan điểm, kêu gọi hành động\r\n- Có dẫn chứng thực tế', 'DeBaiVan_RacThaiNhua.pdf', '/uploads/debai/2024-2025/VAN/L10A1/debai_van_racthai.pdf', 'BaiLuan_Van_RacThaiNhua_NguyenMinhAn.docx', '/uploads/baitap/2024-2025/VAN/L10A1/HS001_baitap2_20241110.docx', 'docx', 1572864, 8.50, 'Bài viết có bố cục rõ ràng, nội dung phong phú. Cần bổ sung thêm số liệu cụ thể và trích nguồn tham khảo.', '2024-11-10 02:00:00', 'DaCham'),
(4, 'HS001', 3, 'Làm bài tập SGK trang 30 về chuyển động thẳng đều', 'ĐỀ BÀI TẬP VẬT LÝ:\r\n\r\nBài 1: Một ô tô chuyển động thẳng đều với vận tốc 60 km/h. Tính quãng đường ô tô đi được sau 2 giờ.\r\n\r\nBài 2: Một vật chuyển động với phương trình s = 10 + 5t (s: m, t: s)\r\na) Xác định vận tốc và tọa độ ban đầu\r\nb) Vẽ đồ thị s-t\r\n\r\nBài 3: Hai xe xuất phát cùng lúc từ hai địa điểm A, B cách nhau 100km, chuyển động ngược chiều với vận tốc 40km/h và 60km/h. Tìm thời điểm và vị trí hai xe gặp nhau.\r\n\r\nBài 4-5: (SGK trang 30)', 'DeBaiTap_Ly_ChuyenDong.pdf', '/uploads/debai/2024-2025/LY/L10A1/debai_ly_chuyendong.pdf', 'BaiTap_Ly_ChuyenDong_NguyenMinhAn.pdf', '/uploads/baitap/2024-2025/LY/L10A1/HS001_baitap3_20241107.pdf', 'pdf', 1920000, 9.50, 'Bài làm tốt, đồ thị vẽ chuẩn, giải thích đúng các khái niệm vật lý.', '2024-11-07 08:30:00', 'DaCham'),
(6, 'HS001', 4, 'Làm bài tập nâng cao về hàm số', 'ĐỀ BÀI TẬP NÂNG CAO:\r\n\r\nBài 1: Cho hàm số y = -x² + 4x - 3\r\na) Tìm tập xác định, tập giá trị\r\nb) Xét tính đồng biến, nghịch biến\r\nc) Tìm GTLN, GTNN của hàm số\r\n\r\nBài 2: Tìm m để hàm số y = x² - 2mx + m² - 1 luôn dương với mọi x\r\n\r\nBài 3: Cho hàm số y = ax² + bx + c. Biết đồ thị đi qua A(1;3), B(2;8), C(-1;5). Tìm a, b, c.\r\n\r\nBài 4-10: (Bài tập nâng cao từ SBT)', 'DeBaiTap_Toan_NangCao.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_nangcao.pdf', 'BaiTap_Toan_NangCao_NguyenMinhAn.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS001_baitap4_20241115.pdf', 'pdf', 2680000, 9.50, 'Bài làm tốt, đồ thị vẽ chuẩn, trình bày rõ ràng, có phân tích sâu.', '2024-11-15 07:00:00', 'DaCham'),
(7, 'HS002', 4, 'Làm bài tập nâng cao về hàm số', 'ĐỀ BÀI TẬP NÂNG CAO:\r\n\r\nBài 1: Cho hàm số y = -x² + 4x - 3\r\na) Tìm tập xác định, tập giá trị\r\nb) Xét tính đồng biến, nghịch biến\r\nc) Tìm GTLN, GTNN của hàm số\r\n\r\nBài 2: Tìm m để hàm số y = x² - 2mx + m² - 1 luôn dương với mọi x\r\n\r\nBài 3: Cho hàm số y = ax² + bx + c. Biết đồ thị đi qua A(1;3), B(2;8), C(-1;5). Tìm a, b, c.\r\n\r\nBài 4-10: (Bài tập nâng cao từ SBT)', 'DeBaiTap_Toan_NangCao.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_nangcao.pdf', 'BaiTap_Toan_NangCao_TranThiBao.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS002_baitap4_20241115.pdf', 'pdf', 2100000, 8.00, 'Làm được 8/10 bài, 2 bài còn thiếu đồ thị. Cần chú ý vẽ đồ thị đầy đủ.', '2024-11-15 09:30:00', 'DaCham'),
(8, 'HS003', 4, 'Làm bài tập nâng cao về hàm số', 'ĐỀ BÀI TẬP NÂNG CAO:\r\n\r\nBài 1: Cho hàm số y = -x² + 4x - 3\r\na) Tìm tập xác định, tập giá trị\r\nb) Xét tính đồng biến, nghịch biến\r\nc) Tìm GTLN, GTNN của hàm số\r\n\r\nBài 2: Tìm m để hàm số y = x² - 2mx + m² - 1 luôn dương với mọi x\r\n\r\nBài 3: Cho hàm số y = ax² + bx + c. Biết đồ thị đi qua A(1;3), B(2;8), C(-1;5). Tìm a, b, c.\r\n\r\nBài 4-10: (Bài tập nâng cao từ SBT)', 'DeBaiTap_Toan_NangCao.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_nangcao.pdf', 'BaiTap_Toan_NangCao_LeVanCuong.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS003_baitap4_20241114.pdf', 'pdf', 3200000, 10.00, 'Xuất sắc! Hoàn thành tất cả bài tập, có thêm bài tập nâng cao tự làm thêm. Tư duy toán học tốt.', '2024-11-14 13:00:00', 'DaCham'),
(19, 'HS001', 7, 'Kiểm tra 15 phút chương Hàm số', 'ĐỀ KIỂM TRA 15 PHÚT - MÔN TOÁN\r\nHọ và tên: ........................... Lớp: 10A1\r\n\r\nCâu 1 (2đ): Tìm tập xác định của hàm số y = √(x-1)\r\n\r\nCâu 2 (3đ): Cho hàm số y = 2x + 1. Tính giá trị của hàm số tại x = 2\r\n\r\nCâu 3 (3đ): Xét tính chẵn lẻ của hàm số y = x³\r\n\r\nCâu 4 (2đ): Cho hàm số y = -x² + 4. Tìm GTLN của hàm số', 'DeKiemTra_15Phut_Toan_HamSo.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/de15phut_hamso.pdf', 'BaiLamKT_15Phut_NguyenMinhAn.pdf', '/uploads/kiemtra/2024-2025/TOAN/L10A1/HS001_kt15phut_20241210.pdf', 'pdf', 856000, 10.00, 'Làm đúng 5/5 câu. Điểm tuyệt đối!', '2024-12-10 00:45:00', 'DaCham'),
(20, 'HS002', 7, 'Kiểm tra 15 phút chương Hàm số', 'ĐỀ KIỂM TRA 15 PHÚT - MÔN TOÁN\r\nHọ và tên: ........................... Lớp: 10A1\r\n\r\nCâu 1 (2đ): Tìm tập xác định của hàm số y = √(x-1)\r\n\r\nCâu 2 (3đ): Cho hàm số y = 2x + 1. Tính giá trị của hàm số tại x = 2\r\n\r\nCâu 3 (3đ): Xét tính chẵn lẻ của hàm số y = x³\r\n\r\nCâu 4 (2đ): Cho hàm số y = -x² + 4. Tìm GTLN của hàm số', 'DeKiemTra_15Phut_Toan_HamSo.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/de15phut_hamso.pdf', 'BaiLamKT_15Phut_TranThiBao.pdf', '/uploads/kiemtra/2024-2025/TOAN/L10A1/HS002_kt15phut_20241210.pdf', 'pdf', 924000, 8.00, 'Làm đúng 4/5 câu. Câu 3 sai do nhầm lẫn về định nghĩa hàm lẻ. Tốt.', '2024-12-10 00:45:00', 'DaCham'),
(21, 'HS003', 7, 'Kiểm tra 15 phút chương Hàm số', 'ĐỀ KIỂM TRA 15 PHÚT - MÔN TOÁN\r\nHọ và tên: ........................... Lớp: 10A1\r\n\r\nCâu 1 (2đ): Tìm tập xác định của hàm số y = √(x-1)\r\n\r\nCâu 2 (3đ): Cho hàm số y = 2x + 1. Tính giá trị của hàm số tại x = 2\r\n\r\nCâu 3 (3đ): Xét tính chẵn lẻ của hàm số y = x³\r\n\r\nCâu 4 (2đ): Cho hàm số y = -x² + 4. Tìm GTLN của hàm số', 'DeKiemTra_15Phut_Toan_HamSo.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/de15phut_hamso.pdf', 'BaiLamKT_15Phut_LeVanCuong.pdf', '/uploads/kiemtra/2024-2025/TOAN/L10A1/HS003_kt15phut_20241210.pdf', 'pdf', 1020000, 10.00, 'Làm đúng 5/5 câu. Xuất sắc!', '2024-12-10 00:45:00', 'DaCham'),
(59, 'HS017', 4, 'Làm bài tập nâng cao về hàm số', 'ĐỀ BÀI TẬP NÂNG CAO:\r\n\r\nBài 1: Cho hàm số y = -x² + 4x - 3\r\na) Tìm tập xác định, tập giá trị\r\nb) Xét tính đồng biến, nghịch biến\r\nc) Tìm GTLN, GTNN của hàm số\r\n\r\nBài 2: Tìm m để hàm số y = x² - 2mx + m² - 1 luôn dương với mọi x\r\n\r\nBài 3-10: (Bài tập nâng cao từ SBT)', 'DeBaiTap_Toan_NangCao.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_nangcao.pdf', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-17 01:00:00', 'ChuaNop'),
(88, 'HS003', 1, 'Làm bài tập SGK trang 20-25 về hàm số bậc nhất và bậc hai', 'ĐỀ BÀI TẬP:\r\nBài 1: Cho hàm số y = 2x + 3\r\na) Tìm tọa độ giao điểm của đồ thị với trục Ox, Oy\r\nb) Vẽ đồ thị hàm số\r\n\r\nBài 2: Cho hàm số y = x² - 4x + 3  \r\na) Tìm tọa độ đỉnh của parabol\r\nb) Lập bảng biến thiên\r\nc) Vẽ đồ thị hàm số\r\n\r\nBài 3: Tìm m để phương trình x² - 2x + m = 0 có hai nghiệm phân biệt\r\n\r\nBài 4-10: (Các bài tập từ SGK trang 20-25)', 'DeBaiTap_Toan_Chuong1.pdf', '/uploads/debai/2024-2025/TOAN/L10A1/debai_baitap1.pdf', 'BaiTap_Toan_Chuong1_LeVanCuong.pdf', '/uploads/baitap/2024-2025/TOAN/L10A1/HS003_baitap1_20241107.pdf', 'pdf', 2560000, 8.50, 'Bài làm khá tốt, trình bày rõ ràng, có phân tích đầy đủ.', '2024-11-07 08:20:00', 'DaCham');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitieu`
--

CREATE TABLE `chitieu` (
  `maChiTieu` int(11) NOT NULL,
  `maTruong` varchar(20) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL,
  `soHocSinh` int(11) DEFAULT NULL,
  `soLopHoc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chitieu`
--

INSERT INTO `chitieu` (`maChiTieu`, `maTruong`, `namHoc`, `soHocSinh`, `soLopHoc`) VALUES
(1, 'TH001', '2024-2025', 500, 12),
(2, 'TH002', '2024-2025', 600, 15),
(3, 'TH003', '2024-2025', 450, 10),
(4, 'TH003', '2025-2026', 440, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhsachthi`
--

CREATE TABLE `danhsachthi` (
  `maDSThi` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maPhong` varchar(20) DEFAULT NULL,
  `soBaoDanh` varchar(20) DEFAULT NULL,
  `trangThai` varchar(50) DEFAULT 'DaXepPhong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhsachthi`
--

INSERT INTO `danhsachthi` (`maDSThi`, `maHS`, `maPhong`, `soBaoDanh`, `trangThai`) VALUES
(1, 'HS001', 'PT001', 'T001', 'DaXepPhong'),
(2, 'HS002', 'PT001', 'T002', 'DaXepPhong'),
(3, 'HS003', 'PT001', 'T003', 'DaXepPhong'),
(4, 'HS004', 'PT001', 'T004', 'DaXepPhong'),
(5, 'HS005', 'PT001', 'T005', 'DaXepPhong'),
(6, 'HS006', 'PT001', 'T006', 'DaXepPhong'),
(7, 'HS007', 'PT001', 'T007', 'DaXepPhong'),
(8, 'HS008', 'PT001', 'T008', 'DaXepPhong'),
(9, 'HS009', 'PT001', 'T009', 'DaXepPhong'),
(10, 'HS010', 'PT001', 'T010', 'DaXepPhong'),
(11, 'HS001', 'PT002', 'H001', 'DaXepPhong'),
(12, 'HS002', 'PT002', 'H002', 'DaXepPhong'),
(13, 'HS003', 'PT002', 'H003', 'DaXepPhong'),
(14, 'HS004', 'PT002', 'H004', 'DaXepPhong'),
(15, 'HS005', 'PT002', 'H005', 'DaXepPhong'),
(16, 'HS006', 'PT002', 'H006', 'DaXepPhong'),
(17, 'HS007', 'PT002', 'H007', 'DaXepPhong'),
(18, 'HS008', 'PT002', 'H008', 'DaXepPhong'),
(19, 'HS009', 'PT002', 'H009', 'DaXepPhong'),
(20, 'HS010', 'PT002', 'H010', 'DaXepPhong'),
(21, 'HS011', 'PT003', 'S011', 'DaXepPhong'),
(22, 'HS012', 'PT003', 'S012', 'DaXepPhong'),
(23, 'HS013', 'PT003', 'S013', 'DaXepPhong'),
(24, 'HS014', 'PT003', 'S014', 'DaXepPhong'),
(25, 'HS015', 'PT003', 'S015', 'DaXepPhong'),
(26, 'HS016', 'PT003', 'S016', 'DaXepPhong'),
(27, 'HS017', 'PT004', 'L017', 'DaXepPhong'),
(28, 'HS018', 'PT004', 'L018', 'DaXepPhong'),
(29, 'HS019', 'PT004', 'L019', 'DaXepPhong'),
(30, 'HS020', 'PT004', 'L020', 'DaXepPhong'),
(31, 'HS021', 'PT005', 'E021', 'DaXepPhong'),
(32, 'HS022', 'PT005', 'E022', 'DaXepPhong'),
(33, 'HS023', 'PT005', 'E023', 'DaXepPhong');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diem`
--

CREATE TABLE `diem` (
  `maDiem` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maMon` varchar(20) DEFAULT NULL,
  `diemSo` decimal(4,2) DEFAULT NULL,
  `loaiDiem` enum('MiengTX','Giua15Phut','MotTiet','GiuaKy','CuoiKy') DEFAULT NULL,
  `hocKy` int(11) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL,
  `ngayNhap` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `diem`
--

INSERT INTO `diem` (`maDiem`, `maHS`, `maMon`, `diemSo`, `loaiDiem`, `hocKy`, `namHoc`, `ngayNhap`) VALUES
(1, 'HS001', 'TOAN', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-08 08:00:11'),
(2, 'HS001', 'TOAN', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-08 08:08:41'),
(3, 'HS001', 'TOAN', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-08 08:04:17'),
(4, 'HS001', 'VAN', 9.50, 'MiengTX', 1, '2024-2025', '2025-11-08 05:45:16'),
(5, 'HS002', 'TOAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-10-31 04:33:15'),
(6, 'HS002', 'VAN', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-08 08:08:31'),
(7, 'HS003', 'TOAN', 6.50, 'MiengTX', 1, '2024-2025', '2025-10-31 04:33:15'),
(8, 'HS004', 'VAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-10-31 04:33:15'),
(9, 'HS003', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(10, 'HS003', 'TOAN', 8.00, 'Giua15Phut', 1, '2024-2025', '2025-11-10 07:03:12'),
(11, 'HS003', 'TOAN', 7.00, 'GiuaKy', 1, '2024-2025', '2025-11-10 07:03:12'),
(12, 'HS004', 'TOAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(13, 'HS004', 'TOAN', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-11-10 07:03:12'),
(14, 'HS005', 'TOAN', 6.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(15, 'HS005', 'TOAN', 6.50, 'Giua15Phut', 1, '2024-2025', '2025-11-10 07:03:12'),
(16, 'HS006', 'TOAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(17, 'HS007', 'TOAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(18, 'HS008', 'TOAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(19, 'HS009', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(20, 'HS010', 'TOAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(21, 'HS011', 'TOAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(22, 'HS012', 'TOAN', 6.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(23, 'HS013', 'TOAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(24, 'HS014', 'TOAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(25, 'HS015', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(26, 'HS016', 'TOAN', 9.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(27, 'HS017', 'TOAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(28, 'HS018', 'TOAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(29, 'HS019', 'TOAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(30, 'HS020', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(31, 'HS021', 'TOAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(32, 'HS022', 'TOAN', 6.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(33, 'HS023', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(50, 'HS003', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(51, 'HS003', 'VAN', 7.50, 'Giua15Phut', 1, '2024-2025', '2025-11-10 07:03:12'),
(52, 'HS004', 'VAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(53, 'HS005', 'VAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(54, 'HS006', 'VAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(55, 'HS007', 'VAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(56, 'HS008', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(57, 'HS009', 'VAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(58, 'HS010', 'VAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(59, 'HS011', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(60, 'HS012', 'VAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(61, 'HS013', 'VAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(62, 'HS014', 'VAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(63, 'HS015', 'VAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(64, 'HS016', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(65, 'HS017', 'VAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(66, 'HS018', 'VAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(67, 'HS019', 'VAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(68, 'HS020', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(69, 'HS021', 'VAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(70, 'HS022', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(71, 'HS023', 'VAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(88, 'HS003', 'ANH', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(89, 'HS004', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(90, 'HS005', 'ANH', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(91, 'HS006', 'ANH', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(92, 'HS007', 'ANH', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(93, 'HS008', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(94, 'HS009', 'ANH', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(95, 'HS010', 'ANH', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(96, 'HS011', 'ANH', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(97, 'HS012', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(98, 'HS013', 'ANH', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(99, 'HS014', 'ANH', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(100, 'HS015', 'ANH', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(101, 'HS016', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(102, 'HS017', 'ANH', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(103, 'HS018', 'ANH', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(104, 'HS019', 'ANH', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(105, 'HS020', 'ANH', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(106, 'HS021', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(107, 'HS022', 'ANH', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(108, 'HS023', 'ANH', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(125, 'HS003', 'LY', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(126, 'HS004', 'LY', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(127, 'HS005', 'LY', 6.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(128, 'HS006', 'LY', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(129, 'HS007', 'LY', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(130, 'HS008', 'LY', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(131, 'HS009', 'LY', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(132, 'HS010', 'LY', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(133, 'HS011', 'LY', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(134, 'HS012', 'LY', 6.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(135, 'HS013', 'LY', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(136, 'HS014', 'LY', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(137, 'HS015', 'LY', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(138, 'HS016', 'LY', 9.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(139, 'HS017', 'LY', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(140, 'HS018', 'LY', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(141, 'HS019', 'LY', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(142, 'HS020', 'LY', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(143, 'HS021', 'LY', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(144, 'HS022', 'LY', 6.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(145, 'HS023', 'LY', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(162, 'HS003', 'HOA', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(163, 'HS004', 'HOA', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(164, 'HS005', 'HOA', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(165, 'HS006', 'HOA', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(166, 'HS007', 'HOA', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(167, 'HS008', 'HOA', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(168, 'HS009', 'HOA', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(169, 'HS010', 'HOA', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(170, 'HS011', 'HOA', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(171, 'HS012', 'HOA', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(172, 'HS013', 'HOA', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(173, 'HS014', 'HOA', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(174, 'HS015', 'HOA', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(175, 'HS016', 'HOA', 9.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(176, 'HS017', 'HOA', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(177, 'HS018', 'HOA', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(178, 'HS019', 'HOA', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(179, 'HS020', 'HOA', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(180, 'HS021', 'HOA', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(181, 'HS022', 'HOA', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(182, 'HS023', 'HOA', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-10 07:03:12'),
(199, 'HS001', 'TOAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(200, 'HS001', 'TOAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(201, 'HS001', 'TOAN', 7.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(202, 'HS001', 'TOAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(203, 'HS001', 'TOAN', 8.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(204, 'HS001', 'TOAN', 7.50, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(205, 'HS001', 'TOAN', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(206, 'HS001', 'TOAN', 8.50, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(207, 'HS001', 'TOAN', 7.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(208, 'HS001', 'TOAN', 8.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(209, 'HS001', 'TOAN', 9.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(210, 'HS002', 'TOAN', 7.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(211, 'HS002', 'TOAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(212, 'HS002', 'TOAN', 6.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(213, 'HS002', 'TOAN', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-12-20 18:25:34'),
(214, 'HS002', 'TOAN', 8.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(215, 'HS002', 'TOAN', 7.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(216, 'HS002', 'TOAN', 7.50, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(217, 'HS002', 'TOAN', 8.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(218, 'HS003', 'TOAN', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(219, 'HS003', 'TOAN', 9.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(220, 'HS003', 'TOAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(221, 'HS003', 'TOAN', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(222, 'HS003', 'TOAN', 8.50, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(223, 'HS003', 'TOAN', 9.50, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(224, 'HS003', 'TOAN', 9.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(225, 'HS003', 'TOAN', 9.50, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(226, 'HS001', 'VAN', 8.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(227, 'HS001', 'VAN', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(228, 'HS001', 'VAN', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-12-20 18:23:27'),
(229, 'HS001', 'VAN', 8.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(230, 'HS001', 'VAN', 8.50, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(231, 'HS001', 'VAN', 8.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(232, 'HS001', 'VAN', 8.50, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(233, 'HS001', 'ANH', 9.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(234, 'HS001', 'ANH', 8.50, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(235, 'HS001', 'ANH', 9.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(236, 'HS001', 'ANH', 8.50, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(237, 'HS001', 'ANH', 9.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(238, 'HS001', 'ANH', 8.50, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(239, 'HS023', 'TOAN', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(240, 'HS023', 'TOAN', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(241, 'HS023', 'TOAN', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(242, 'HS023', 'TOAN', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(243, 'HS023', 'TOAN', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(244, 'HS023', 'VAN', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(245, 'HS023', 'VAN', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(246, 'HS023', 'VAN', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(247, 'HS023', 'VAN', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(248, 'HS023', 'VAN', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(249, 'HS023', 'ANH', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(250, 'HS023', 'ANH', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(251, 'HS023', 'ANH', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(252, 'HS023', 'ANH', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(253, 'HS023', 'ANH', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(254, 'HS023', 'LY', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(255, 'HS023', 'LY', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(256, 'HS023', 'LY', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(257, 'HS023', 'LY', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(258, 'HS023', 'LY', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(259, 'HS023', 'HOA', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(260, 'HS023', 'HOA', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(261, 'HS023', 'HOA', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(262, 'HS023', 'HOA', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(263, 'HS023', 'HOA', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(264, 'HS023', 'SINH', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(265, 'HS023', 'SINH', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(266, 'HS023', 'SINH', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(267, 'HS023', 'SINH', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(268, 'HS023', 'SINH', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(269, 'HS023', 'SU', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(270, 'HS023', 'SU', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(271, 'HS023', 'SU', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(272, 'HS023', 'SU', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(273, 'HS023', 'SU', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(274, 'HS023', 'DIA', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(275, 'HS023', 'DIA', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(276, 'HS023', 'DIA', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(277, 'HS023', 'DIA', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(278, 'HS023', 'DIA', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(279, 'HS023', 'GDCD', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(280, 'HS023', 'GDCD', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(281, 'HS023', 'GDCD', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(282, 'HS023', 'GDCD', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(283, 'HS023', 'GDCD', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(284, 'HS023', 'CNTT', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(285, 'HS023', 'CNTT', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(286, 'HS023', 'CNTT', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(287, 'HS023', 'CNTT', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(288, 'HS023', 'CNTT', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(289, 'HS023', 'TD', 10.00, 'MiengTX', 1, '2024-2025', '2025-11-26 11:29:03'),
(290, 'HS023', 'TD', 10.00, 'Giua15Phut', 1, '2024-2025', '2025-11-26 11:29:03'),
(291, 'HS023', 'TD', 10.00, 'MotTiet', 1, '2024-2025', '2025-11-26 11:29:03'),
(292, 'HS023', 'TD', 10.00, 'GiuaKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(293, 'HS023', 'TD', 10.00, 'CuoiKy', 1, '2024-2025', '2025-11-26 11:29:03'),
(294, 'HS001', 'GDCD', 8.50, 'MiengTX', 1, '2024-2025', '2025-12-20 18:42:22'),
(295, 'HS001', 'GDCD', 7.00, 'Giua15Phut', 1, '2024-2025', '2025-12-20 18:42:22'),
(296, 'HS001', 'GDCD', 7.50, 'MotTiet', 1, '2024-2025', '2025-12-20 18:42:22'),
(297, 'HS001', 'GDCD', 10.00, 'GiuaKy', 1, '2024-2025', '2025-12-23 12:13:15'),
(298, 'HS001', 'GDCD', 9.00, 'CuoiKy', 1, '2024-2025', '2025-12-20 18:44:48'),
(299, 'HS001', 'ANH', 9.00, 'CuoiKy', 2, '2023-2024', '2025-12-20 18:57:40'),
(300, 'HS001', 'ANH', 9.00, 'MiengTX', 2, '2023-2024', '2025-12-20 18:59:13'),
(301, 'HS001', 'ANH', 9.00, 'Giua15Phut', 2, '2023-2024', '2025-12-20 18:59:40'),
(302, 'HS001', 'ANH', 9.00, 'MotTiet', 2, '2023-2024', '2025-12-20 18:59:40'),
(303, 'HS001', 'CNTT', 9.00, 'MiengTX', 2, '2023-2024', '2025-12-20 20:08:38'),
(304, 'HS001', 'ANH', 9.00, 'MiengTX', 1, '2023-2024', '2025-12-20 20:46:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diemdanh`
--

CREATE TABLE `diemdanh` (
  `maDiemDanh` int(11) NOT NULL,
  `maHS` varchar(20) NOT NULL,
  `maLop` varchar(20) NOT NULL,
  `ngayDiemDanh` date NOT NULL,
  `trangThai` enum('CoMat','Vang','DiTre','CoPhep') DEFAULT 'CoMat',
  `ghiChu` text DEFAULT NULL,
  `nguoiDiemDanh` varchar(20) DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `diemdanh`
--

INSERT INTO `diemdanh` (`maDiemDanh`, `maHS`, `maLop`, `ngayDiemDanh`, `trangThai`, `ghiChu`, `nguoiDiemDanh`, `ngayTao`) VALUES
(9, 'HS001', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:25:24'),
(10, 'HS002', 'L10A1', '2025-11-10', 'Vang', NULL, 'GV001', '2025-11-11 02:25:24'),
(11, 'HS003', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:25:24'),
(27, 'HS001', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(28, 'HS002', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(29, 'HS003', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(30, 'HS004', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(31, 'HS005', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(32, 'HS006', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(33, 'HS007', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(34, 'HS008', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(35, 'HS009', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(36, 'HS010', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(37, 'HS011', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(38, 'HS012', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(39, 'HS013', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(40, 'HS014', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(41, 'HS015', 'L10A1', '2025-11-10', 'CoMat', NULL, 'GV001', '2025-11-11 02:26:33'),
(111, 'HS008', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(112, 'HS007', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(113, 'HS020', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(116, 'HS005', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(117, 'HS015', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(118, 'HS011', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(121, 'HS003', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(124, 'HS019', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(125, 'HS001', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(126, 'HS021', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(127, 'HS009', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(128, 'HS013', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(131, 'HS016', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(132, 'HS012', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(134, 'HS023', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(135, 'HS004', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(136, 'HS018', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(139, 'HS022', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(141, 'HS017', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(142, 'HS010', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(143, 'HS002', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(145, 'HS014', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(147, 'HS006', 'L10A1', '2025-11-11', 'Vang', '', 'GV001', '2025-11-11 08:17:56'),
(155, 'HS005', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15'),
(156, 'HS003', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15'),
(157, 'HS001', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15'),
(158, 'HS023', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15'),
(159, 'HS004', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15'),
(160, 'HS002', 'L10A1', '2025-12-23', 'CoMat', '', 'GV001', '2025-12-23 16:52:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diem_tuyensinh`
--

CREATE TABLE `diem_tuyensinh` (
  `id` int(11) NOT NULL,
  `maHS` varchar(20) NOT NULL,
  `tenMon` varchar(100) NOT NULL,
  `soBaoDanh` varchar(20) NOT NULL,
  `diem` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donnghihoc`
--

CREATE TABLE `donnghihoc` (
  `maDon` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `lyDo` text DEFAULT NULL,
  `ngayBatDau` date DEFAULT NULL,
  `ngayKetThuc` date DEFAULT NULL,
  `trangThai` enum('ChoXuLy','DaDuyet','TuChoi') DEFAULT 'ChoXuLy',
  `ngayGui` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngayXuLy` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donnghihoc`
--

INSERT INTO `donnghihoc` (`maDon`, `maHS`, `lyDo`, `ngayBatDau`, `ngayKetThuc`, `trangThai`, `ngayGui`, `ngayXuLy`) VALUES
(1, 'HS001', 'Em bị ốm, cần nghỉ dưỡng', '2024-11-05', '2024-11-05', 'DaDuyet', '2025-10-31 04:33:15', NULL),
(2, 'HS003', 'Gia đình có việc đột xuất', '2024-11-06', '2024-11-06', 'ChoXuLy', '2025-10-31 04:33:15', NULL),
(3, 'HS005', 'Em bị sốt cao, cần nghỉ để đi khám bệnh', '2024-11-11', '2024-11-11', 'ChoXuLy', '2025-11-09 01:30:00', NULL),
(4, 'HS012', 'Gia đình có việc cấp bách cần em về quê', '2024-11-12', '2024-11-12', 'ChoXuLy', '2025-11-09 02:15:00', NULL),
(5, 'HS018', 'Em bị đau răng, cần đi nha sĩ', '2024-11-13', '2024-11-13', 'DaDuyet', '2025-11-09 03:00:00', '2025-11-10 18:20:41'),
(6, 'HS022', 'Em bị cảm cúm, cần nghỉ dưỡng bệnh', '2024-11-14', '2024-11-14', 'ChoXuLy', '2025-11-09 04:20:00', NULL),
(10, 'HS007', 'Em bị đau bụng, cần đi khám bác sĩ', '2024-11-18', '2024-11-18', 'ChoXuLy', '2025-11-10 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giaovien`
--

CREATE TABLE `giaovien` (
  `maGV` varchar(20) NOT NULL,
  `toChuyenMon` varchar(50) DEFAULT NULL,
  `monGiangDay` varchar(50) DEFAULT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giaovien`
--

INSERT INTO `giaovien` (`maGV`, `toChuyenMon`, `monGiangDay`, `maNguoiDung`) VALUES
('GV001', 'Tổ Toán - Tin', 'TOAN', 'ND003'),
('GV002', 'Tổ Ngữ Văn', 'VAN', 'ND004'),
('GV003', 'Tổ Khoa học Tự nhiên', 'LY', 'ND005'),
('GV004', 'Tổ Khoa học Tự nhiên', 'HOA', 'ND006'),
('GV005', 'Tổ Ngoại ngữ', 'ANH', 'ND007'),
('GV006', 'Tổ Khoa học Tự nhiên', 'SINH', 'ND201'),
('GV007', 'Tổ Khoa học Xã hội', 'SU', 'ND202'),
('GV008', 'Tổ Khoa học Xã hội', 'DIA', 'ND203'),
('GV009', 'Tổ Thể dục - GDQP', 'TD', 'ND204'),
('GV010', 'Tổ Khoa học Xã hội', 'GDCD', 'ND205'),
('GV011', 'Tổ Toán - Tin', 'CNTT', 'ND206'),
('GV013', 'Tổ Toán - Tin', 'TOAN', 'ND211'),
('GV014', 'Tổ Ngữ Văn', 'VAN', 'ND212'),
('GV015', 'Tổ Ngoại ngữ', 'ANH', 'ND213'),
('GV016', 'Tổ Toán - Tin', 'TOAN', 'ND214'),
('GV017', 'Tổ Toán - Tin', 'TOAN', 'ND215'),
('GV018', 'Tổ Ngữ Văn', 'VAN', 'ND216'),
('GV019', 'Tổ Ngữ Văn', 'VAN', 'ND217'),
('GV020', 'Tổ Ngoại ngữ', 'ANH', 'ND218'),
('GV021', 'Tổ Ngoại ngữ', 'ANH', 'ND219'),
('GV022', 'Tổ Khoa học Tự nhiên', 'LY', 'ND220'),
('GV023', 'Tổ Khoa học Tự nhiên', 'LY', 'ND221'),
('GV024', 'Tổ Khoa học Tự nhiên', 'HOA', 'ND222'),
('GV025', 'Tổ Khoa học Tự nhiên', 'HOA', 'ND223'),
('GV026', 'Tổ Khoa học Tự nhiên', 'SINH', 'ND224'),
('GV027', 'Tổ Khoa học Tự nhiên', 'SINH', 'ND225'),
('GV500', 'Tổ Quốc phòng và An ninh', 'QPAN', 'ND500');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hanhkiem`
--

CREATE TABLE `hanhkiem` (
  `maHK` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `xepLoai` enum('Tot','Kha','TrungBinh','Yeu') DEFAULT NULL,
  `nhanXet` text DEFAULT NULL COMMENT 'Nhận xét về hạnh kiểm của học sinh',
  `nguoiNhap` varchar(20) DEFAULT NULL COMMENT 'Mã người nhập (giáo viên)',
  `ngayNhap` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày nhập hạnh kiểm',
  `hocKy` int(11) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hanhkiem`
--

INSERT INTO `hanhkiem` (`maHK`, `maHS`, `xepLoai`, `nhanXet`, `nguoiNhap`, `ngayNhap`, `hocKy`, `namHoc`) VALUES
(1, 'HS001', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(2, 'HS002', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(16, 'HS003', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(17, 'HS004', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(18, 'HS005', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(19, 'HS006', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(20, 'HS007', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(21, 'HS008', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(22, 'HS009', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(23, 'HS010', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(24, 'HS011', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(25, 'HS012', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(26, 'HS013', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(27, 'HS014', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(28, 'HS015', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(29, 'HS016', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(30, 'HS017', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(31, 'HS018', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(32, 'HS019', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(33, 'HS020', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(34, 'HS021', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(35, 'HS022', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025'),
(53, 'HS023', 'Tot', NULL, NULL, '2025-12-15 16:34:12', 1, '2024-2025');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocsinh`
--

CREATE TABLE `hocsinh` (
  `maHS` varchar(20) NOT NULL,
  `dangThaiHocTap` varchar(50) DEFAULT NULL,
  `soBaoDanh` varchar(20) DEFAULT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL,
  `maPhong` varchar(10) DEFAULT NULL,
  `maLop` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hocsinh`
--

INSERT INTO `hocsinh` (`maHS`, `dangThaiHocTap`, `soBaoDanh`, `maNguoiDung`, `maPhong`, `maLop`) VALUES
('HS001', 'Đang học', 'BD001', 'ND012', 'PT001', 'L10A1'),
('HS002', 'Đang học', 'BD002', 'ND013', 'PT001', 'L10A1'),
('HS003', 'Đang học', 'BD003', 'ND014', 'P37_A01', 'L10A1'),
('HS004', 'Đang học', 'BD004', 'ND015', 'PT001', 'L10A1'),
('HS005', 'Đang học', 'BD005', 'ND016', 'PT001', 'L10A1'),
('HS006', 'Đang học', 'BD006', 'ND017', 'P37_A01', 'L10A2'),
('HS007', 'Đang học', 'BD007', 'ND018', 'P37_A01', 'L10A2'),
('HS008', 'Đang học', 'BD008', 'ND019', 'P37_A01', 'L10A2'),
('HS009', 'Đang học', 'BD015', 'ND027', 'P37_A01', 'L10A2'),
('HS010', 'Đang học', 'BD016', 'ND028', 'P37_T01', 'L10A2'),
('HS011', 'Đang học', 'BD017', 'ND029', 'P37_T01', 'L11A1'),
('HS012', 'Đang học', 'BD018', 'ND030', 'P38_A01', 'L11A1'),
('HS013', 'Đang học', 'BD019', 'ND031', 'P38_A01', 'L11A1'),
('HS014', 'Đang học', 'BD020', 'ND032', 'PT25001', 'L11A1'),
('HS015', 'Đang học', 'BD021', 'ND033', 'P38_V01', 'L11A1'),
('HS016', 'Đang học', 'BD022', 'ND034', 'P38_V01', 'L11A2'),
('HS017', 'Đang học', 'BD023', 'ND035', 'PT25001', 'L11A2'),
('HS018', 'Đang học', 'BD024', 'ND036', NULL, 'L11A2'),
('HS019', 'Đang học', 'BD025', 'ND037', 'P37_A01', 'L11A2'),
('HS020', 'Đang học', 'BD026', 'ND038', NULL, 'L11A2'),
('HS021', 'Đang học', 'BD027', 'ND039', 'P37_A01', 'L12A1'),
('HS022', 'Đang học', 'BD028', 'ND040', NULL, 'L12A1'),
('HS023', 'Đang học', 'BD029', 'ND041', NULL, 'L10A1'),
('HS2025310', 'Đang xét tuyển', NULL, 'ND2025680', 'P39_A01', NULL),
('HS2025695', 'Đang xét tuyển', NULL, 'ND2025473', NULL, NULL),
('HS5185', 'Đang học', 'BD5185', 'ND1033', NULL, 'L10A2'),
('HS5792', 'Tạm nghỉ', 'BD5792', 'ND9592', 'P39_A01', 'L10A2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hosotuyensinh`
--

CREATE TABLE `hosotuyensinh` (
  `maHS` varchar(20) NOT NULL,
  `dangThai` varchar(50) DEFAULT 'ChoXetTuyen',
  `ngayNop` timestamp NOT NULL DEFAULT current_timestamp(),
  `maKyTS` varchar(20) DEFAULT NULL,
  `hoTenHS` varchar(100) DEFAULT NULL,
  `gioiTinhHS` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `ngaySinhHS` date DEFAULT NULL,
  `diaChiHS` text DEFAULT NULL,
  `soDienThoaiHS` varchar(15) DEFAULT NULL,
  `emailHS` varchar(100) DEFAULT NULL,
  `hoTenPH` varchar(100) DEFAULT NULL,
  `soDienThoaiPH` varchar(15) DEFAULT NULL,
  `emailPH` varchar(100) DEFAULT NULL,
  `quanHe` varchar(50) DEFAULT NULL,
  `maPH` varchar(20) DEFAULT NULL,
  `ngayDangKy` timestamp NOT NULL DEFAULT current_timestamp(),
  `tonGiao` varchar(50) DEFAULT NULL,
  `danToc` varchar(30) DEFAULT NULL,
  `maPhongTS` int(11) DEFAULT NULL COMMENT 'Mã phòng thi đã xếp',
  `soBaoDanh` varchar(20) DEFAULT NULL COMMENT 'Số báo danh',
  `trangThaiXepPhong` enum('ChuaXep','DaXep','HuyXep') DEFAULT 'ChuaXep' COMMENT 'Trạng thái xếp phòng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hosotuyensinh`
--

INSERT INTO `hosotuyensinh` (`maHS`, `dangThai`, `ngayNop`, `maKyTS`, `hoTenHS`, `gioiTinhHS`, `ngaySinhHS`, `diaChiHS`, `soDienThoaiHS`, `emailHS`, `hoTenPH`, `soDienThoaiPH`, `emailPH`, `quanHe`, `maPH`, `ngayDangKy`, `tonGiao`, `danToc`, `maPhongTS`, `soBaoDanh`, `trangThaiXepPhong`) VALUES
('HS2025310', 'ChoXetTuyen', '2025-12-23 10:10:50', 'KTS2026', 'Nguyễn Minh Anh', 'Nữ', '2004-10-05', '12 Nguyễn Văn Bảo, P1, GV', '0344461922', '0888155@gmail.com', 'nfjdvfjd', '0344461922', 'wdcf@gmail.com', 'Bố', 'PH001', '2025-12-23 10:10:50', 'Không', 'Kinh', NULL, NULL, 'ChuaXep'),
('HS2025695', 'ChoXetTuyen', '2025-12-23 11:33:07', 'KTS2026', 'ssss', 'Nam', '2000-10-05', 'jsdnfjebnsfvjdbsj', '0345812566', 'kkkkk@gmail.com', 'kkkkk', '0345812566', 'kkkkk@gmail.com', 'Bố', 'PH001', '2025-12-23 11:33:07', 'Không', 'Kinh', NULL, NULL, 'ChuaXep');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoatuan`
--

CREATE TABLE `khoatuan` (
  `maKhoaTuan` int(11) NOT NULL,
  `tuanBatDau` date NOT NULL,
  `tuanKetThuc` date NOT NULL,
  `daKhoa` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khoi`
--

CREATE TABLE `khoi` (
  `maKhoi` varchar(20) NOT NULL,
  `tenKhoi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khoi`
--

INSERT INTO `khoi` (`maKhoi`, `tenKhoi`) VALUES
('K10', 'Khối 10'),
('K11', 'Khối 11'),
('K12', 'Khối 12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kythi`
--

CREATE TABLE `kythi` (
  `maKyThi` int(11) NOT NULL,
  `tenKyThi` varchar(100) NOT NULL,
  `ngayThi` date NOT NULL,
  `caThi` varchar(20) NOT NULL,
  `trangThai` varchar(20) DEFAULT 'DangMo',
  `hocKy` int(11) NOT NULL,
  `namHoc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kythi`
--

INSERT INTO `kythi` (`maKyThi`, `tenKyThi`, `ngayThi`, `caThi`, `trangThai`, `hocKy`, `namHoc`) VALUES
(1, 'Kỳ thi giữa kỳ 1 - 2024', '2024-12-10', 'Sáng', 'DangMo', 1, '2024-2025'),
(2, 'Kỳ thi cuối kỳ 1 - 2024', '2024-12-20', 'Sáng', 'DangMo', 2, '2024-2025'),
(3, 'Kỳ thi học kỳ 1 - 2024', '2024-12-15', 'Chiều', 'DangMo', 1, '2024-2025'),
(4, 'Kỳ thi giữa kỳ 2 - 2025', '2025-03-10', 'Sáng', 'DangMo', 2, '2025-2026'),
(5, 'Kỳ thi cuối kỳ 2 - 2025', '2025-05-20', 'Sáng', 'DangMo', 1, '2025-2026'),
(6, 'Kỳ thi học kỳ 2 - 2025', '2025-05-25', 'Chiều', 'DangMo', 2, '2025-2026'),
(9, 'Kiểm tra 15 phút Toán lần 1', '2024-11-20', 'Sáng', 'DangMo', 1, '2024-2025'),
(10, 'Kiểm tra 15 phút Toán lần 1', '2024-11-20', 'Chiều', 'DangMo', 1, '2024-2025'),
(11, 'Kiểm tra 1 tiết Toán lần 1', '2024-11-25', 'Sáng', 'DangMo', 1, '2024-2025'),
(12, 'Kiểm tra 1 tiết Toán lần 1', '2024-11-25', 'Chiều', 'DangMo', 1, '2024-2025'),
(13, 'Kiểm tra 15 phút Ngữ Văn', '2024-11-22', 'Sáng', 'DangMo', 1, '2024-2025'),
(14, 'Kiểm tra 15 phút Ngữ Văn', '2024-11-22', 'Chiều', 'DangMo', 1, '2024-2025'),
(15, 'Kiểm tra 1 tiết Ngữ Văn', '2024-11-27', 'Sáng', 'DangMo', 1, '2024-2025'),
(16, 'Kiểm tra 1 tiết Ngữ Văn', '2024-11-27', 'Chiều', 'DangMo', 1, '2024-2025'),
(17, 'Kiểm tra 15 phút Tiếng Anh', '2024-11-21', 'Sáng', 'DangMo', 1, '2024-2025'),
(18, 'Kiểm tra 15 phút Tiếng Anh', '2024-11-21', 'Chiều', 'DangMo', 1, '2024-2025'),
(19, 'Kiểm tra 1 tiết Tiếng Anh', '2024-11-26', 'Sáng', 'DangMo', 1, '2024-2025'),
(20, 'Kiểm tra 1 tiết Tiếng Anh', '2024-11-26', 'Chiều', 'DangMo', 1, '2024-2025'),
(21, 'Kiểm tra giữa kỳ Vật Lý', '2024-11-28', 'Sáng', 'DangMo', 1, '2024-2025'),
(22, 'Kiểm tra giữa kỳ Vật Lý', '2024-11-28', 'Chiều', 'DangMo', 1, '2024-2025'),
(23, 'Kiểm tra giữa kỳ Hóa Học', '2024-11-29', 'Sáng', 'DangMo', 1, '2024-2025'),
(24, 'Kiểm tra giữa kỳ Hóa Học', '2024-11-29', 'Chiều', 'DangMo', 1, '2024-2025'),
(25, 'Thi giữa kỳ I - Toán', '2024-12-05', 'Sáng', 'DangMo', 1, '2024-2025'),
(26, 'Thi giữa kỳ I - Toán', '2024-12-05', 'Chiều', 'DangMo', 1, '2024-2025'),
(27, 'Thi giữa kỳ I - Ngữ Văn', '2024-12-06', 'Sáng', 'DangMo', 1, '2024-2025'),
(28, 'Thi giữa kỳ I - Ngữ Văn', '2024-12-06', 'Chiều', 'DangMo', 1, '2024-2025'),
(29, 'Thi giữa kỳ I - Tiếng Anh', '2024-12-07', 'Sáng', '', 1, '2024-2025'),
(30, 'Thi giữa kỳ I - Tiếng Anh', '2024-12-07', 'Chiều', 'DangMo', 1, '2024-2025'),
(31, 'Thi cuối kỳ I - Toán', '2025-01-10', 'Sáng', 'DangMo', 1, '2024-2025'),
(32, 'Thi cuối kỳ I - Toán', '2025-01-10', 'Chiều', 'DangMo', 1, '2024-2025'),
(33, 'Thi cuối kỳ I - Ngữ Văn', '2025-01-11', 'Sáng', 'DangMo', 1, '2024-2025'),
(34, 'Thi cuối kỳ I - Ngữ Văn', '2025-01-11', 'Chiều', 'DangMo', 1, '2024-2025'),
(35, 'Kỳ thi giữa kỳ 1 - 2025', '2025-10-15', 'Sáng', 'DangMo', 1, '2025-2026'),
(36, 'Kỳ thi cuối kỳ 1 - 2025', '2025-12-20', 'Sáng', 'DangMo', 1, '2025-2026'),
(37, 'Kỳ thi cuối kỳ 1 - 2025', '2025-12-25', 'Sáng', 'DangMo', 1, '2025-2026'),
(38, 'Kỳ thi giữa kỳ 1 - 2025', '2025-12-26', 'Sáng', 'DangMo', 1, '2025-2026'),
(39, 'Kỳ thi cuối kỳ 1 - 2025', '2025-12-30', 'Sáng', 'DangMo', 1, '2025-2026'),
(40, 'Kỳ thi giữa kỳ 2 - 2026', '2026-03-15', 'Sáng', 'DangMo', 2, '2025-2026'),
(43, 'Kỳ thi giữa kỳ 2 - 2026', '2026-03-15', 'Sáng', 'DangMo', 2, '2025-2026');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ky_tuyensinh`
--

CREATE TABLE `ky_tuyensinh` (
  `maKyTS` varchar(20) NOT NULL,
  `tenKyTS` varchar(200) NOT NULL,
  `namHoc` varchar(20) DEFAULT NULL,
  `ngayBatDau` date DEFAULT NULL,
  `ngayKetThuc` date DEFAULT NULL,
  `trangThai` enum('DangMo','DaDong') DEFAULT 'DaDong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ky_tuyensinh`
--

INSERT INTO `ky_tuyensinh` (`maKyTS`, `tenKyTS`, `namHoc`, `ngayBatDau`, `ngayKetThuc`, `trangThai`) VALUES
('KTS2024', 'Kỳ tuyển sinh năm học 2024-2025', '2024-2025', '2023-11-01', '2024-01-31', 'DaDong'),
('KTS2026', 'Kỳ tuyển sinh năm học 2026-2027', '2026-2027', '2025-08-15', '2025-12-31', 'DangMo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichsu_suadiem`
--

CREATE TABLE `lichsu_suadiem` (
  `maLichSu` int(11) NOT NULL,
  `maDiem` int(11) DEFAULT NULL,
  `diemCu` decimal(4,2) DEFAULT NULL,
  `diemMoi` decimal(4,2) DEFAULT NULL,
  `maNguoiSua` varchar(20) DEFAULT NULL,
  `lyDo` text DEFAULT NULL,
  `ngaySua` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichsu_suadiem`
--

INSERT INTO `lichsu_suadiem` (`maLichSu`, `maDiem`, `diemCu`, `diemMoi`, `maNguoiSua`, `lyDo`, `ngaySua`) VALUES
(1, 1, 8.50, 9.00, 'ND002', 'Phúc khảo bài thi giữa kỳ', '2025-11-08 00:14:07'),
(2, 2, 7.50, 8.00, 'ND002', 'Điều chỉnh điểm 15 phút', '2025-11-08 00:14:07'),
(3, 4, 9.00, 8.50, 'ND002', 'Chấm lại điểm miệng', '2025-11-08 00:14:07'),
(4, 3, 8.00, 9.50, 'ND002', 'Nhập sai', '2025-11-08 00:28:06'),
(5, 3, 9.50, 8.00, 'ND002', 'nhập lại', '2025-11-08 00:31:24'),
(6, 6, 8.50, 8.00, 'ND002', 'nhập sai', '2025-11-08 12:32:21'),
(7, 4, 9.00, 9.50, 'ND002', 'sửa điểm miệng do giáo viên nhập sai\r\n', '2025-11-08 12:45:16'),
(8, 2, 7.50, 9.00, 'ND002', 'nhập nhầm', '2025-11-08 12:56:53'),
(9, 1, 8.50, 10.00, 'ND002', 'phúc khảo chấm lại', '2025-11-08 15:00:11'),
(10, 3, 8.00, 10.00, 'ND002', 'nhập lại', '2025-11-08 15:04:17'),
(13, 6, 8.00, 10.00, 'ND002', 'ggggggggg', '2025-11-08 15:08:31'),
(14, 2, 9.00, 10.00, 'ND002', '3eeeeeeeeee', '2025-11-08 15:08:41'),
(15, 228, 7.50, 9.00, 'ND002', 'MMMMMMMM', '2025-12-21 01:23:27'),
(16, 213, 7.50, 9.00, 'ND002', 'RRRRRRRR', '2025-12-21 01:25:34'),
(17, 297, 8.50, 10.00, 'ND002', 'Nhập sai', '2025-12-23 19:13:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichthilop`
--

CREATE TABLE `lichthilop` (
  `maLichThi` int(11) NOT NULL,
  `maLop` varchar(20) NOT NULL COMMENT 'Mã lớp',
  `maMon` varchar(20) NOT NULL COMMENT 'Mã môn thi',
  `ngayThi` date NOT NULL COMMENT 'Ngày thi',
  `buoiThi` enum('Sáng','Chiều') NOT NULL DEFAULT 'Sáng' COMMENT 'Buổi thi',
  `gioBatDau` time NOT NULL COMMENT 'Giờ bắt đầu',
  `gioKetThuc` time NOT NULL COMMENT 'Giờ kết thúc',
  `maPhong` varchar(20) DEFAULT NULL COMMENT 'Mã phòng thi (FK tới phongthi)',
  `hocKy` enum('HK1','HK2') DEFAULT 'HK1' COMMENT 'Học kỳ',
  `namHoc` varchar(20) DEFAULT '2024-2025' COMMENT 'Năm học',
  `ghiChu` text DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lichthilop`
--

INSERT INTO `lichthilop` (`maLichThi`, `maLop`, `maMon`, `ngayThi`, `buoiThi`, `gioBatDau`, `gioKetThuc`, `maPhong`, `hocKy`, `namHoc`, `ghiChu`, `ngayTao`) VALUES
(1, 'L10A1', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(2, 'L10A1', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(3, 'L10A1', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(4, 'L10A1', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(5, 'L10A1', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(6, 'L10A1', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(7, 'L10A1', 'SU', '2025-12-26', 'Sáng', '07:30:00', '08:30:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(8, 'L10A1', 'DIA', '2025-12-26', 'Chiều', '13:30:00', '14:30:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(9, 'L10A1', 'GDCD', '2025-12-27', 'Sáng', '07:30:00', '08:30:00', 'PT001', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(10, 'L10A2', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(11, 'L10A2', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(12, 'L10A2', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(13, 'L10A2', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(14, 'L10A2', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(15, 'L10A2', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(16, 'L10A2', 'SU', '2025-12-26', 'Sáng', '07:30:00', '08:30:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(17, 'L10A2', 'DIA', '2025-12-26', 'Chiều', '13:30:00', '14:30:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(18, 'L10A2', 'GDCD', '2025-12-27', 'Sáng', '07:30:00', '08:30:00', 'PT002', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(19, 'L11A1', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(20, 'L11A1', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(21, 'L11A1', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(22, 'L11A1', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(23, 'L11A1', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(24, 'L11A1', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT003', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(25, 'L11A2', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(26, 'L11A2', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(27, 'L11A2', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(28, 'L11A2', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(29, 'L11A2', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(30, 'L11A2', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT004', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(31, 'L12A1', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(32, 'L12A1', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(33, 'L12A1', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(34, 'L12A1', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(35, 'L12A1', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(36, 'L12A1', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT005', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(37, 'L12A2', 'TOAN', '2025-12-23', 'Sáng', '07:30:00', '09:30:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(38, 'L12A2', 'VAN', '2025-12-23', 'Chiều', '13:30:00', '15:30:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(39, 'L12A2', 'ANH', '2025-12-24', 'Sáng', '07:30:00', '09:00:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(40, 'L12A2', 'LY', '2025-12-24', 'Chiều', '13:30:00', '15:00:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(41, 'L12A2', 'HOA', '2025-12-25', 'Sáng', '07:30:00', '09:00:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02'),
(42, 'L12A2', 'SINH', '2025-12-25', 'Chiều', '13:30:00', '15:00:00', 'PT006', 'HK1', '2025-2026', 'Thi cuối kỳ 1', '2025-12-21 08:49:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop`
--

CREATE TABLE `lop` (
  `maLop` varchar(20) NOT NULL,
  `tenLop` varchar(50) NOT NULL,
  `siSo` int(11) DEFAULT 0,
  `namHoc` varchar(20) DEFAULT NULL,
  `maKhoi` varchar(20) DEFAULT NULL,
  `maBan` varchar(20) DEFAULT NULL,
  `maPhong` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop`
--

INSERT INTO `lop` (`maLop`, `tenLop`, `siSo`, `namHoc`, `maKhoi`, `maBan`, `maPhong`) VALUES
('L10A1', '10A1', 23, '2024-2025', 'K10', 'TN', 'P101'),
('L10A2', '10A2', 38, '2024-2025', 'K10', 'XH', 'P102'),
('L11A1', '11A1', 40, '2024-2025', 'K11', 'TN', 'P201'),
('L11A2', '11A2', 37, '2024-2025', 'K11', 'XH', 'P202'),
('L12A1', '12A1', 36, '2024-2025', 'K12', 'TN', 'P301'),
('L12A2', '12A2', 39, '2024-2025', 'K12', 'XH', 'P302');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `minhchung_suadiem`
--

CREATE TABLE `minhchung_suadiem` (
  `maMinhChung` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maMon` varchar(20) DEFAULT NULL,
  `maGV` varchar(20) DEFAULT NULL,
  `loaiMinhChung` enum('YeuCauSuaDiem','PhucKhao') DEFAULT NULL,
  `lyDoYeuCau` text DEFAULT NULL,
  `trangThai` enum('ChoXuLy','DaXuLy','Huy') DEFAULT 'ChoXuLy',
  `ngayTao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `minhchung_suadiem`
--

INSERT INTO `minhchung_suadiem` (`maMinhChung`, `maHS`, `maMon`, `maGV`, `loaiMinhChung`, `lyDoYeuCau`, `trangThai`, `ngayTao`) VALUES
(1, 'HS001', 'TOAN', 'GV001', 'PhucKhao', 'Học sinh phúc khảo điểm giữa kỳ môn Toán', 'DaXuLy', '2025-11-08 00:14:00'),
(2, 'HS002', 'VAN', 'GV002', 'YeuCauSuaDiem', 'Giáo viên yêu cầu sửa điểm miệng do nhập nhầm', 'DaXuLy', '2025-11-08 00:14:00'),
(3, 'HS003', 'ANH', 'GV005', 'PhucKhao', 'Phụ huynh đề nghị phúc khảo điểm cuối kỳ', 'ChoXuLy', '2025-11-08 00:14:00'),
(4, 'HS001', 'TOAN', 'GV001', 'YeuCauSuaDiem', 'Nhập sai điểm kiểm tra 1 tiết', 'DaXuLy', '2025-12-21 01:04:15'),
(5, 'HS002', 'VAN', 'GV002', 'PhucKhao', 'Yêu cầu phúc khảo bài thi HK1', 'DaXuLy', '2025-12-21 01:04:15'),
(9, 'HS001', 'TOAN', 'GV001', 'PhucKhao', 'Phụ huynh đề nghị phúc khảo điểm kiểm tra giữa kỳ', 'DaXuLy', '2025-11-09 08:30:00'),
(10, 'HS002', 'VAN', 'GV002', 'PhucKhao', 'Học sinh yêu cầu phúc khảo bài thi học kỳ I', 'DaXuLy', '2025-11-10 14:45:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc` (
  `maMon` varchar(20) NOT NULL,
  `tenMon` varchar(100) NOT NULL,
  `soTiet` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `monhoc`
--

INSERT INTO `monhoc` (`maMon`, `tenMon`, `soTiet`) VALUES
('ANH', 'Tiếng Anh', 3),
('CNTT', 'Tin học', 1),
('DIA', 'Địa Lý', 2),
('GDCD', 'Giáo dục công dân', 1),
('HOA', 'Hóa Học', 3),
('LY', 'Vật Lý', 3),
('QPAN', 'Giáo dục Quốc phòng và An ninh', 3),
('SINH', 'Sinh Học', 2),
('SU', 'Lịch Sử', 2),
('TD', 'Thể dục', 3),
('TOAN', 'Toán', 4),
('VAN', 'Ngữ Văn', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `maNguoiDung` varchar(20) NOT NULL,
  `hoVaTen` varchar(100) NOT NULL,
  `gioiTinh` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `ngaySinh` date DEFAULT NULL,
  `diaChi` text DEFAULT NULL,
  `soDienThoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`maNguoiDung`, `hoVaTen`, `gioiTinh`, `ngaySinh`, `diaChi`, `soDienThoai`, `email`, `ngayTao`) VALUES
('ND001', 'Nguyễn Văn Admin', 'Nam', '1980-05-15', '123 Đường Lê Lợi, TP.HCM', '0901234567', 'admin@school.edu.vn', '2025-10-31 04:33:14'),
('ND002', 'Trần Thị Hiệu Trưởng', 'Nữ', '1975-08-20', '456 Đường Nguyễn Huệ, TP.HCM', '0902345678', 'hieutruong@school.edu.vn', '2025-10-31 04:33:14'),
('ND003', 'Lê Văn Toán', 'Nam', '1985-03-10', '789 Đường Trần Hưng Đạo, TP.HCM', '0903456789', 'levantoan@school.edu.vn', '2025-10-31 04:33:14'),
('ND004', 'Phạm Thị Văn', 'Nữ', '1987-07-22', '321 Đường Hai Bà Trưng, TP.HCM', '0904567890', 'phamthivan@school.edu.vn', '2025-10-31 04:33:14'),
('ND005', 'Hoàng Minh Lý', 'Nam', '1983-11-05', '654 Đường Lý Thường Kiệt, TP.HCM', '0905678901', 'hoangminhly@school.edu.vn', '2025-10-31 04:33:14'),
('ND006', 'Nguyễn Thị Hóa', 'Nữ', '1986-09-18', '987 Đường Võ Thị Sáu, TP.HCM', '0906789012', 'nguyenthihoa@school.edu.vn', '2025-10-31 04:33:14'),
('ND007', 'Trần Văn Anh', 'Nam', '1984-12-30', '147 Đường Phan Đình Phùng, TP.HCM', '0907890123', 'tranvananh@school.edu.vn', '2025-10-31 04:33:14'),
('ND008', 'Nguyễn Văn Bình', 'Nam', '1978-04-12', '111 Đường Lê Duẩn, TP.HCM', '0908901234', 'nvbinh@gmail.com', '2025-10-31 04:33:14'),
('ND009', 'Trần Thị Mai', 'Nữ', '1980-06-25', '222 Đường Nguyễn Thị Minh Khai, TP.HCM', '0909012345', 'ttmai@gmail.com', '2025-10-31 04:33:14'),
('ND010', 'Lê Văn Cường', 'Nam', '1976-09-08', '333 Đường Cách Mạng Tháng 8, TP.HCM', '0910123456', 'lvcuong@gmail.com', '2025-10-31 04:33:14'),
('ND011', 'Phạm Thị Dung', 'Nữ', '1979-11-15', '444 Đường 3 Tháng 2, TP.HCM', '0911234567', 'ptdung@gmail.com', '2025-10-31 04:33:14'),
('ND012', 'Nguyễn Minh An', 'Nam', '2009-01-15', '111 Đường Lê Duẩn, TP.HCM', '0912345678', 'nman@student.edu.vn', '2025-10-31 04:33:15'),
('ND013', 'Trần Thị Bảo', 'Nữ', '2009-03-20', '222 Đường Nguyễn Thị Minh Khai, TP.HCM', '0913456789', 'ttbao@student.edu.vn', '2025-10-31 04:33:15'),
('ND014', 'Lê Văn Cường', 'Nam', '2009-05-10', '333 Đường Cách Mạng Tháng 8, TP.HCM', '0914567890', 'lvcuong@student.edu.vn', '2025-10-31 04:33:15'),
('ND015', 'Phạm Thị Duyên', 'Nữ', '2009-07-25', '444 Đường 3 Tháng 2, TP.HCM', '0915678901', 'ptduyen@student.edu.vn', '2025-10-31 04:33:15'),
('ND016', 'Hoàng Văn Em', 'Nam', '2008-02-18', '555 Đường Điện Biên Phủ, TP.HCM', '0916789012', 'hvem@student.edu.vn', '2025-10-31 04:33:15'),
('ND017', 'Võ Thị Phương', 'Nữ', '2008-04-22', '666 Đường Hoàng Văn Thụ, TP.HCM', '0917890123', 'vtphuong@student.edu.vn', '2025-10-31 04:33:15'),
('ND018', 'Đặng Văn Giang', 'Nam', '2007-06-30', '777 Đường Pasteur, TP.HCM', '0918901234', 'dvgiang@student.edu.vn', '2025-10-31 04:33:15'),
('ND019', 'Bùi Thị Hoa', 'Nữ', '2007-08-15', '888 Đường Lý Tự Trọng, TP.HCM', '0919012345', 'bthoa@student.edu.vn', '2025-10-31 04:33:15'),
('ND020', 'Võ Văn Sơn', 'Nam', '1970-01-10', '01 Hoàng Việt, Q.Tân Bình, TP.HCM', '0283997001', 'vvson@sgd.hcm.gov.vn', '2025-10-31 04:33:15'),
('ND021', 'Nguyễn Minh Hoàng', 'Nam', '2009-01-12', '12 Nguyễn Văn Cừ, Q.5, TP.HCM', '0901111001', 'nmhoang@student.edu.vn', '2025-11-09 15:38:39'),
('ND022', 'Trần Thị Mai Anh', 'Nữ', '2009-02-18', '45 Nguyễn Trãi, Q.1, TP.HCM', '0901111002', 'ttmaianh@student.edu.vn', '2025-11-09 15:38:39'),
('ND023', 'Lê Hoàng Phúc', 'Nam', '2009-03-25', '56 CMT8, Q.3, TP.HCM', '0901111003', 'lhphuc@student.edu.vn', '2025-11-09 15:38:39'),
('ND024', 'Phạm Thu Trang', 'Nữ', '2009-04-03', '78 Hai Bà Trưng, Q.1, TP.HCM', '0901111004', 'pttrang@student.edu.vn', '2025-11-09 15:38:39'),
('ND025', 'Võ Thanh Tùng', 'Nam', '2009-05-09', '23 Điện Biên Phủ, Q.10, TP.HCM', '0901111005', 'vttung@student.edu.vn', '2025-11-09 15:38:39'),
('ND026', 'Đặng Ngọc Bảo', 'Nam', '2009-06-15', '89 Nguyễn Huệ, Q.1, TP.HCM', '0901111006', 'dngocbao@student.edu.vn', '2025-11-09 15:38:39'),
('ND027', 'Nguyễn Thị Hạnh', 'Nữ', '2009-07-08', '12 Lý Thường Kiệt, Q.10, TP.HCM', '0901111007', 'nthanh@student.edu.vn', '2025-11-09 15:38:39'),
('ND028', 'Trần Quốc Duy', 'Nam', '2009-08-22', '45 Trần Phú, Q.5, TP.HCM', '0901111008', 'tqduy@student.edu.vn', '2025-11-09 15:38:39'),
('ND029', 'Lê Thanh Hương', 'Nữ', '2009-09-17', '99 Pasteur, Q.3, TP.HCM', '0901111009', 'lthuong@student.edu.vn', '2025-11-09 15:38:39'),
('ND030', 'Phạm Anh Tuấn', 'Nam', '2009-10-11', '78 Nguyễn Thị Minh Khai, Q.1', '0901111010', 'patuan@student.edu.vn', '2025-11-09 15:38:39'),
('ND031', 'Nguyễn Thị Kim Oanh', 'Nữ', '2009-10-30', '11 Nguyễn Văn Linh, Q.7', '0901111011', 'ntkoanh@student.edu.vn', '2025-11-09 15:38:39'),
('ND032', 'Võ Đức Long', 'Nam', '2009-11-12', '88 Lê Duẩn, Q.1', '0901111012', 'vdlong@student.edu.vn', '2025-11-09 15:38:39'),
('ND033', 'Lê Bảo Ngọc', 'Nữ', '2009-11-28', '34 Tôn Đức Thắng, Q.4', '0901111013', 'lbngoc@student.edu.vn', '2025-11-09 15:38:39'),
('ND034', 'Nguyễn Văn Nam', 'Nam', '2009-12-07', '22 Nguyễn Thái Học, Q.1', '0901111014', 'nvnam@student.edu.vn', '2025-11-09 15:38:39'),
('ND035', 'Trần Ngọc Lan', 'Nữ', '2009-12-18', '100 Võ Văn Kiệt, Q.5', '0901111015', 'tnlan@student.edu.vn', '2025-11-09 15:38:39'),
('ND036', 'Phan Hữu Phước', 'Nam', '2009-12-29', '58 Cách Mạng Tháng 8, Q.10', '0901111016', 'phphuoc@student.edu.vn', '2025-11-09 15:38:39'),
('ND037', 'Lưu Thị Hồng', 'Nữ', '2009-01-08', '70 Nguyễn Tri Phương, Q.10', '0901111017', 'lthong@student.edu.vn', '2025-11-09 15:38:39'),
('ND038', 'Đỗ Anh Khoa', 'Nam', '2009-01-17', '89 Lê Lợi, Q.1', '0901111018', 'dakhoa@student.edu.vn', '2025-11-09 15:38:39'),
('ND039', 'Nguyễn Nhật Linh', 'Nữ', '2009-02-09', '50 Trần Quang Khải, Q.1', '0901111019', 'nnlinh@student.edu.vn', '2025-11-09 15:38:39'),
('ND040', 'Trần Hữu Tài', 'Nam', '2009-02-25', '120 Nguyễn Thông, Q.3', '0901111020', 'thutai@student.edu.vn', '2025-11-09 15:38:39'),
('ND041', 'Phạm Quỳnh Trang', 'Nữ', '2009-03-08', '210 Nguyễn Thiện Thuật, Q.3', '0901111021', 'pqtrang@student.edu.vn', '2025-11-09 15:38:39'),
('ND043', 'Trịnh Thị Minh Thư', 'Nữ', '2009-04-13', '56 Bà Triệu, Q.5', '0901111023', 'ttmthu@student.edu.vn', '2025-11-09 15:38:39'),
('ND044', 'Võ Hữu Nghĩa', 'Nam', '2009-04-24', '101 Nguyễn Văn Cừ, Q.5', '0901111024', 'vhnghia@student.edu.vn', '2025-11-09 15:38:39'),
('ND045', 'Lê Thu Hằng', 'Nữ', '2009-05-03', '33 Nguyễn Kiệm, Q.Phú Nhuận', '0901111025', 'lthang@student.edu.vn', '2025-11-09 15:38:39'),
('ND046', 'Nguyễn Văn Bình', 'Nam', '2009-05-27', '99 Trần Hưng Đạo, Q.5', '0901111026', 'nvbinh2@student.edu.vn', '2025-11-09 15:38:39'),
('ND047', 'Trần Hoài An', 'Nữ', '2009-06-09', '15 Nguyễn Khắc Nhu, Q.1', '0901111027', 'than@student.edu.vn', '2025-11-09 15:38:39'),
('ND048', 'Lê Văn Tài', 'Nam', '2009-07-02', '22 Trường Sa, Q.3', '0901111028', 'lvtai@student.edu.vn', '2025-11-09 15:38:39'),
('ND049', 'Phan Thị Như Ý', 'Nữ', '2009-07-21', '36 Hoàng Sa, Q.3', '0901111029', 'ptny@student.edu.vn', '2025-11-09 15:38:39'),
('ND050', 'Đỗ Minh Quân', 'Nam', '2009-08-10', '55 Nguyễn Du, Q.1', '0901111030', 'dmquan@student.edu.vn', '2025-11-09 15:38:39'),
('ND051', 'Nguyễn Thị Thanh Tuyền', 'Nữ', '2009-08-26', '120 Nguyễn Huệ, Q.1', '0901111031', 'ntttuyen@student.edu.vn', '2025-11-09 15:38:39'),
('ND052', 'Võ Văn Hưng', 'Nam', '2009-09-02', '76 Trần Quang Diệu, Q.3', '0901111032', 'vvhung@student.edu.vn', '2025-11-09 15:38:39'),
('ND053', 'Lê Thị Minh Phương', 'Nữ', '2009-09-18', '45 Nguyễn Trãi, Q.5', '0901111033', 'ltmphuong@student.edu.vn', '2025-11-09 15:38:39'),
('ND054', 'Phạm Hữu Đạt', 'Nam', '2009-10-01', '90 Điện Biên Phủ, Q.3', '0901111034', 'phdat@student.edu.vn', '2025-11-09 15:38:39'),
('ND055', 'Trần Khánh Vy', 'Nữ', '2009-10-17', '22 Nguyễn Tri Phương, Q.5', '0901111035', 'tkvy@student.edu.vn', '2025-11-09 15:38:39'),
('ND056', 'Lưu Quốc Anh', 'Nam', '2009-11-05', '77 Lý Thường Kiệt, Q.10', '0901111036', 'lqan@student.edu.vn', '2025-11-09 15:38:39'),
('ND057', 'Bùi Ngọc Yến', 'Nữ', '2009-12-01', '44 Trần Phú, Q.5', '0901111037', 'bnyen@student.edu.vn', '2025-11-09 15:38:39'),
('ND058', 'Nguyễn Văn Cường', 'Nam', '1975-03-15', '12 Nguyễn Văn Cừ, Q.5, TP.HCM', '0902345001', 'nvcuong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND059', 'Trần Thị Mai', 'Nữ', '1978-06-20', '45 Nguyễn Trãi, Q.1, TP.HCM', '0902345002', 'ttmai.ph@gmail.com', '2025-11-10 07:03:12'),
('ND060', 'Lê Hoàng Sơn', 'Nam', '1976-09-10', '56 CMT8, Q.3, TP.HCM', '0902345003', 'lhson.ph@gmail.com', '2025-11-10 07:03:12'),
('ND061', 'Phạm Thu Hà', 'Nữ', '1977-11-25', '78 Hai Bà Trưng, Q.1, TP.HCM', '0902345004', 'ptha.ph@gmail.com', '2025-11-10 07:03:12'),
('ND062', 'Võ Minh Tuấn', 'Nam', '1974-04-08', '23 Điện Biên Phủ, Q.10, TP.HCM', '0902345005', 'vmtuan.ph@gmail.com', '2025-11-10 07:03:12'),
('ND063', 'Đặng Ngọc Lan', 'Nữ', '1979-07-12', '89 Nguyễn Huệ, Q.1, TP.HCM', '0902345006', 'dnlan.ph@gmail.com', '2025-11-10 07:03:12'),
('ND064', 'Nguyễn Văn Bình', 'Nam', '1973-02-28', '12 Lý Thường Kiệt, Q.10, TP.HCM', '0902345007', 'nvbinh.ph@gmail.com', '2025-11-10 07:03:12'),
('ND065', 'Trần Thị Hương', 'Nữ', '1980-05-17', '45 Trần Phú, Q.5, TP.HCM', '0902345008', 'tthuong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND066', 'Lê Văn Đức', 'Nam', '1975-08-22', '99 Pasteur, Q.3, TP.HCM', '0902345009', 'lvduc.ph@gmail.com', '2025-11-10 07:03:12'),
('ND067', 'Phạm Thị Nga', 'Nữ', '1978-10-30', '78 Nguyễn Thị Minh Khai, Q.1', '0902345010', 'ptnga.ph@gmail.com', '2025-11-10 07:03:12'),
('ND068', 'Nguyễn Minh Khoa', 'Nam', '1976-12-05', '11 Nguyễn Văn Linh, Q.7', '0902345011', 'nmkhoa.ph@gmail.com', '2025-11-10 07:03:12'),
('ND069', 'Võ Thị Thanh', 'Nữ', '1977-01-18', '88 Lê Duẩn, Q.1', '0902345012', 'vtthanh.ph@gmail.com', '2025-11-10 07:03:12'),
('ND070', 'Lê Quốc Hùng', 'Nam', '1974-03-22', '34 Tôn Đức Thắng, Q.4', '0902345013', 'lqhung.ph@gmail.com', '2025-11-10 07:03:12'),
('ND071', 'Nguyễn Thị Phương', 'Nữ', '1979-05-14', '22 Nguyễn Thái Học, Q.1', '0902345014', 'ntphuong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND072', 'Trần Văn Long', 'Nam', '1975-07-09', '100 Võ Văn Kiệt, Q.5', '0902345015', 'tvlong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND073', 'Phan Thị Ánh', 'Nữ', '1978-09-23', '58 Cách Mạng Tháng 8, Q.10', '0902345016', 'ptanh.ph@gmail.com', '2025-11-10 07:03:12'),
('ND074', 'Lưu Văn Tâm', 'Nam', '1976-11-16', '70 Nguyễn Tri Phương, Q.10', '0902345017', 'lvtam.ph@gmail.com', '2025-11-10 07:03:12'),
('ND075', 'Đỗ Thị Hồng', 'Nữ', '1977-01-29', '89 Lê Lợi, Q.1', '0902345018', 'dthong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND076', 'Nguyễn Văn Hải', 'Nam', '1974-04-11', '50 Trần Quang Khải, Q.1', '0902345019', 'nvhai.ph@gmail.com', '2025-11-10 07:03:12'),
('ND077', 'Trần Thị Linh', 'Nữ', '1979-06-25', '120 Nguyễn Thông, Q.3', '0902345020', 'ttlinh.ph@gmail.com', '2025-11-10 07:03:12'),
('ND078', 'Lê Văn Nam', 'Nam', '1975-08-07', '210 Nguyễn Thiện Thuật, Q.3', '0902345021', 'lvnam.ph@gmail.com', '2025-11-10 07:03:12'),
('ND079', 'Phạm Thị Yến', 'Nữ', '1978-10-19', '99 Lý Tự Trọng, Q.1', '0902345022', 'ptyen.ph@gmail.com', '2025-11-10 07:03:12'),
('ND080', 'Đoàn Văn Tú', 'Nam', '1976-12-03', '56 Bà Triệu, Q.5', '0902345023', 'dvtu.ph@gmail.com', '2025-11-10 07:03:12'),
('ND081', 'Trịnh Thị Loan', 'Nữ', '1977-02-14', '101 Nguyễn Văn Cừ, Q.5', '0902345024', 'ttloan.ph@gmail.com', '2025-11-10 07:03:12'),
('ND082', 'Võ Văn Tài', 'Nam', '1974-04-28', '33 Nguyễn Kiệm, Q.Phú Nhuận', '0902345025', 'vvtai.ph@gmail.com', '2025-11-10 07:03:12'),
('ND083', 'Lê Thị Hà', 'Nữ', '1979-06-12', '99 Trần Hưng Đạo, Q.5', '0902345026', 'ltha.ph@gmail.com', '2025-11-10 07:03:12'),
('ND084', 'Nguyễn Văn Kiên', 'Nam', '1975-08-24', '15 Nguyễn Khắc Nhu, Q.1', '0902345027', 'nvkien.ph@gmail.com', '2025-11-10 07:03:12'),
('ND085', 'Trần Thị Ngọc', 'Nữ', '1978-10-05', '22 Trường Sa, Q.3', '0902345028', 'ttngoc.ph@gmail.com', '2025-11-10 07:03:12'),
('ND086', 'Lê Văn Phong', 'Nam', '1976-12-18', '36 Hoàng Sa, Q.3', '0902345029', 'lvphong.ph@gmail.com', '2025-11-10 07:03:12'),
('ND087', 'Phan Thị Thảo', 'Nữ', '1977-02-27', '55 Nguyễn Du, Q.1', '0902345030', 'ptthao.ph@gmail.com', '2025-11-10 07:03:12'),
('ND088', 'Đỗ Văn Hùng', 'Nam', '1974-05-09', '120 Nguyễn Huệ, Q.1', '0902345031', 'dvhung.ph@gmail.com', '2025-11-10 07:03:12'),
('ND089', 'Nguyễn Thị Tuyết', 'Nữ', '1979-07-21', '76 Trần Quang Diệu, Q.3', '0902345032', 'nttuyet.ph@gmail.com', '2025-11-10 07:03:12'),
('ND090', 'Võ Văn Đạt', 'Nam', '1975-09-13', '45 Nguyễn Trãi, Q.5', '0902345033', 'vvdat.ph@gmail.com', '2025-11-10 07:03:12'),
('ND091', 'Lê Thị My', 'Nữ', '1978-11-26', '90 Điện Biên Phủ, Q.3', '0902345034', 'ltmy.ph@gmail.com', '2025-11-10 07:03:12'),
('ND092', 'Phan Văn Quang', 'Nam', '1976-01-08', '22 Nguyễn Tri Phương, Q.5', '0902345035', 'pvquang.ph@gmail.com', '2025-11-10 07:03:12'),
('ND1033', 'Nguyễn B', 'Nam', '2004-11-05', 'Bến Tre', '0321321123', 'sangtranquocgl@gmail.com', '2025-12-24 04:06:09'),
('ND201', 'Nguyễn Thị Sinh', 'Nữ', '1985-03-15', '123 Đường Lê Lợi, Q.1, TP.HCM', '0901111201', 'nguyenthisinh@school.edu.vn', '2025-11-17 13:37:41'),
('ND202', 'Trần Văn Sử', 'Nam', '1982-07-22', '456 Đường Nguyễn Huệ, Q.1, TP.HCM', '0901111202', 'tranvansu@school.edu.vn', '2025-11-17 13:37:41'),
('ND2025473', 'ssss', 'Nam', '2000-10-05', 'jsdnfjebnsfvjdbsj', '0345812566', 'kkkkk@gmail.com', '2025-12-23 11:33:07'),
('ND2025680', 'Nguyễn Minh Anh', 'Nữ', '2004-10-05', '12 Nguyễn Văn Bảo, P1, GV', '0344461922', '0888155@gmail.com', '2025-12-23 10:10:50'),
('ND203', 'Lê Thị Địa', 'Nữ', '1986-11-08', '789 Đường Pasteur, Q.3, TP.HCM', '0901111203', 'lethidia@school.edu.vn', '2025-11-17 13:37:41'),
('ND204', 'Phạm Văn Thể', 'Nam', '1980-05-30', '321 Đường Hai Bà Trưng, Q.1, TP.HCM', '0901111204', 'phamvanthe@school.edu.vn', '2025-11-17 13:37:41'),
('ND205', 'Hoàng Thị Công', 'Nữ', '1983-09-14', '654 Đường Lý Tự Trọng, Q.1, TP.HCM', '0901111205', 'hoangthicong@school.edu.vn', '2025-11-17 13:37:41'),
('ND206', 'Võ Minh Tin', 'Nam', '1984-02-18', '111 Đường CMT8, Q.3, TP.HCM', '0901111206', 'vominhtin@school.edu.vn', '2025-11-17 13:37:41'),
('ND207', 'Đặng Thị Nhạc', 'Nữ', '1981-12-05', '222 Đường Lê Văn Sỹ, Q.3, TP.HCM', '0901111207', 'dangthinac@school.edu.vn', '2025-11-17 13:37:41'),
('ND208', 'Bùi Văn Kỹ', 'Nam', '1979-08-25', '333 Đường Võ Thị Sáu, Q.3, TP.HCM', '0901111208', 'buivanky@school.edu.vn', '2025-11-17 13:37:41'),
('ND209', 'Nguyễn Thị Mỹ', 'Nữ', '1987-04-12', '444 Đường Nguyễn Trãi, Q.1, TP.HCM', '0901111209', 'nguyenthimy@school.edu.vn', '2025-11-17 13:37:41'),
('ND210', 'Trần Văn Công Nghệ', 'Nam', '1988-06-20', '555 Đường Lý Thường Kiệt, Q.10, TP.HCM', '0901111210', 'tranvancongnghe@school.edu.vn', '2025-11-17 13:37:41'),
('ND211', 'Trần Thị Toán', 'Nữ', '1983-04-15', '789 Đường Lê Duẩn, Q.1, TP.HCM', '0901111211', 'tranthitoan@school.edu.vn', '2025-11-17 13:47:47'),
('ND212', 'Lê Văn Văn', 'Nam', '1981-08-22', '456 Đường Nguyễn Trãi, Q.1, TP.HCM', '0901111212', 'levanvan@school.edu.vn', '2025-11-17 13:47:47'),
('ND213', 'Phạm Thị Anh', 'Nữ', '1986-12-10', '123 Đường Pasteur, Q.3, TP.HCM', '0901111213', 'phamthianh@school.edu.vn', '2025-11-17 13:47:47'),
('ND214', 'Lê Thị Toán', 'Nữ', '1984-03-20', '111 Đường Lý Thường Kiệt, Q.10, TP.HCM', '0901111214', 'lethitoan@school.edu.vn', '2025-11-17 14:05:35'),
('ND215', 'Phạm Văn Toán', 'Nam', '1982-11-15', '222 Đường Nguyễn Văn Cừ, Q.5, TP.HCM', '0901111215', 'phamvantoan@school.edu.vn', '2025-11-17 14:05:35'),
('ND216', 'Trần Thị Văn', 'Nữ', '1985-07-08', '333 Đường Hùng Vương, Q.5, TP.HCM', '0901111216', 'tranthivan@school.edu.vn', '2025-11-17 14:05:35'),
('ND217', 'Nguyễn Văn Văn', 'Nam', '1983-09-25', '444 Đường Trần Hưng Đạo, Q.1, TP.HCM', '0901111217', 'nguyenvanvan@school.edu.vn', '2025-11-17 14:05:35'),
('ND218', 'Hoàng Thị Anh', 'Nữ', '1986-12-30', '555 Đường Lê Văn Sỹ, Q.3, TP.HCM', '0901111218', 'hoangthianh@school.edu.vn', '2025-11-17 14:05:35'),
('ND219', 'Võ Văn Anh', 'Nam', '1981-04-18', '666 Đường Cách Mạng Tháng 8, Q.3, TP.HCM', '0901111219', 'vovananh@school.edu.vn', '2025-11-17 14:05:35'),
('ND220', 'Đặng Thị Lý', 'Nữ', '1987-06-12', '777 Đường 3 Tháng 2, Q.10, TP.HCM', '0901111220', 'dangthily@school.edu.vn', '2025-11-17 14:05:35'),
('ND221', 'Bùi Văn Lý', 'Nam', '1980-08-22', '888 Đường Lý Thái Tổ, Q.3, TP.HCM', '0901111221', 'buivanly@school.edu.vn', '2025-11-17 14:05:35'),
('ND222', 'Lưu Thị Hóa', 'Nữ', '1985-01-14', '999 Đường Nguyễn Thị Minh Khai, Q.1, TP.HCM', '0901111222', 'luuthihoa@school.edu.vn', '2025-11-17 14:05:35'),
('ND223', 'Phan Văn Hóa', 'Nam', '1983-05-28', '101 Đường Bà Hạt, Q.10, TP.HCM', '0901111223', 'phanvanhoa@school.edu.vn', '2025-11-17 14:05:35'),
('ND224', 'Trịnh Thị Sinh', 'Nữ', '1986-10-05', '202 Đường Nguyễn Tri Phương, Q.5, TP.HCM', '0901111224', 'trinhthisinh@school.edu.vn', '2025-11-17 14:05:35'),
('ND225', 'Đỗ Văn Sinh', 'Nam', '1982-02-17', '303 Đường Nguyễn Trãi, Q.1, TP.HCM', '0901111225', 'dovansinh@school.edu.vn', '2025-11-17 14:05:35'),
('ND500', 'Nguyễn Văn Quốc Phòng', 'Nam', '1975-09-20', 'Bộ môn Quốc phòng – An ninh', '0987654321', 'qp@school.edu.vn', '2025-12-18 10:44:19'),
('ND9592', 'Nguyễn Võ Ngọc My', 'Nữ', '2004-06-09', 'Bến Tre', '0123556677', 'ngocmy0906@gmail.com', '2025-12-21 06:53:39'),
('NguoiDung001', 'tai', 'Nam', '2004-10-05', '2', '1234', 'a@a', '2025-12-16 14:30:02'),
('NguoiDung002', 'j', 'Nam', '2222-02-22', '4', '3', 'abc@iuh.edu.vn', '2025-12-16 14:44:09'),
('NguoiDung003', 'b', 'Nam', '2028-11-11', 'long an', '123', 'tai@gmail.com', '2025-12-17 06:41:24'),
('NguoiDung004', 'b', 'Nam', '2028-11-11', '232', '3', 'tai@gmail.com', '2025-12-17 06:48:27'),
('NguoiDung005', 'a', 'Nam', '2004-10-10', '12', '123', 'vantai14157@gmail.com', '2025-12-17 06:53:33'),
('NguoiDung006', 'Nguyễn Văn Tài', 'Nam', '2004-10-05', '232', '123', 'vantai14157@gmail.com', '2025-12-17 08:27:24'),
('NguoiDung007', 'a', 'Nam', '2004-10-05', '232', '12', 'tai@gmail.com', '2025-12-21 09:33:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguyenvong_tuyensinh`
--

CREATE TABLE `nguyenvong_tuyensinh` (
  `maNguyenVong` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maTruong` varchar(20) DEFAULT NULL,
  `monChuyen` varchar(20) DEFAULT NULL,
  `thuTuNguyenVong` int(11) DEFAULT NULL,
  `monTuChon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguyenvong_tuyensinh`
--

INSERT INTO `nguyenvong_tuyensinh` (`maNguyenVong`, `maHS`, `maTruong`, `monChuyen`, `thuTuNguyenVong`, `monTuChon`) VALUES
(8, 'HS2025310', 'TH001', 'HOA', 1, 'THE_DUC'),
(9, 'HS2025310', 'TH003', 'TOAN', 2, 'AM_NHAC'),
(10, 'HS2025310', 'TH003', 'LY', 3, ''),
(11, 'HS2025695', 'TH001', 'TOAN', 1, ''),
(12, 'HS2025695', 'TH003', 'SU', 2, ''),
(13, 'HS2025695', 'TH003', 'HOA', 3, '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanxetdanhgia`
--

CREATE TABLE `nhanxetdanhgia` (
  `maNhanXet` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `nhanXet` varchar(100) DEFAULT NULL,
  `danhGia` text DEFAULT NULL,
  `ngayThucHien` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanxetdanhgia`
--

INSERT INTO `nhanxetdanhgia` (`maNhanXet`, `maHS`, `nhanXet`, `danhGia`, `ngayThucHien`) VALUES
(13, 'HS023', 'Học sinh xuất sắc toàn diện', 'Hoàn thành tốt mọi nhiệm vụ học tập và rèn luyện', '2025-11-26 11:29:03'),
(22, 'HS021', 'học giỏi ', '8', '2025-12-17 08:40:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pcgiamthi`
--

CREATE TABLE `pcgiamthi` (
  `maPCGT` int(11) NOT NULL,
  `maGV` varchar(20) DEFAULT NULL,
  `maPhong` varchar(20) DEFAULT NULL,
  `ngayThi` date DEFAULT NULL,
  `caThi` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pcgiamthi`
--

INSERT INTO `pcgiamthi` (`maPCGT`, `maGV`, `maPhong`, `ngayThi`, `caThi`) VALUES
(7, 'GV001', 'PT001', '2025-12-10', 'Sáng'),
(8, 'GV002', 'PT002', '2025-12-10', 'Chiều'),
(9, 'GV003', 'PT003', '2025-12-11', 'Sáng'),
(10, 'GV004', 'PT004', '2025-12-11', 'Chiều'),
(11, 'GV005', 'PT005', '2025-12-12', 'Sáng'),
(12, 'GV019', 'PT004', '2024-12-20', 'Sáng'),
(13, 'GV004', 'PT001', '2024-12-10', 'Sáng'),
(14, 'GV001', 'PT101', '2024-11-20', 'Sáng'),
(15, 'GV002', 'PT101', '2024-11-20', 'Sáng'),
(16, 'GV003', 'PT102', '2024-11-20', 'Sáng'),
(17, 'GV004', 'PT102', '2024-11-20', 'Sáng'),
(18, 'GV005', 'PT111', '2024-11-22', 'Sáng'),
(19, 'GV006', 'PT111', '2024-11-22', 'Sáng'),
(20, 'GV007', 'PT112', '2024-11-22', 'Sáng'),
(21, 'GV008', 'PT120', '2024-11-21', 'Sáng'),
(22, 'GV009', 'PT120', '2024-11-21', 'Sáng'),
(23, 'GV010', 'PT121', '2024-11-21', 'Sáng'),
(24, 'GV011', 'PT129', '2024-11-28', 'Sáng'),
(25, 'GV013', 'PT129', '2024-11-28', 'Sáng'),
(26, 'GV014', 'PT130', '2024-11-28', 'Sáng'),
(27, 'GV015', 'PT134', '2024-11-29', 'Sáng'),
(28, 'GV016', 'PT134', '2024-11-29', 'Sáng'),
(29, 'GV010', 'PT25001', '2025-12-20', 'Sáng'),
(30, 'GV014', 'PT25001', '2025-12-20', 'Sáng'),
(31, 'GV001', 'P37_A01', '2025-12-25', 'Sáng'),
(32, 'GV024', 'P37_A01', '2025-12-25', 'Sáng'),
(33, 'GV002', 'P38_V01', '2025-12-26', 'Sáng'),
(34, 'GV019', 'P39_T02', '2025-12-30', 'Sáng'),
(35, 'GV500', 'P39_T02', '2025-12-30', 'Sáng'),
(38, 'GV014', 'P37_T02', '2025-12-25', 'Sáng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pcgvbm`
--

CREATE TABLE `pcgvbm` (
  `maPCGVBM` int(11) NOT NULL,
  `maGV` varchar(20) DEFAULT NULL,
  `maLop` varchar(20) DEFAULT NULL,
  `maMon` varchar(20) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL,
  `hocky` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pcgvbm`
--

INSERT INTO `pcgvbm` (`maPCGVBM`, `maGV`, `maLop`, `maMon`, `namHoc`, `hocky`) VALUES
(87, 'GV001', 'L10A1', 'TOAN', '2025-2026', 1),
(88, 'GV010', 'L10A1', 'GDCD', '2025-2026', 1),
(89, 'GV008', 'L10A1', 'DIA', '2025-2026', 1),
(90, 'GV004', 'L10A2', 'HOA', '2025-2026', 1),
(91, 'GV500', 'L10A1', 'QPAN', '2025-2026', 1),
(92, 'GV024', 'L10A1', 'HOA', '2025-2026', 1),
(93, 'GV007', 'L10A1', 'SU', '2025-2026', 1),
(94, 'GV014', 'L10A1', 'VAN', '2025-2026', 1),
(95, 'GV027', 'L10A1', 'SINH', '2025-2026', 1),
(96, 'GV009', 'L10A1', 'TD', '2025-2026', 1),
(97, 'GV020', 'L10A1', 'ANH', '2025-2026', 1),
(98, 'GV011', 'L10A1', 'CNTT', '2025-2026', 1),
(99, 'GV023', 'L10A1', 'LY', '2025-2026', 1),
(100, 'GV008', 'L10A2', 'DIA', '2025-2026', 0),
(101, 'GV500', 'L10A2', 'QPAN', '2025-2026', 0),
(102, 'GV010', 'L10A2', 'GDCD', '2025-2026', 0),
(103, 'GV007', 'L10A2', 'SU', '2025-2026', 0),
(104, 'GV009', 'L10A2', 'TD', '2025-2026', 0),
(105, 'GV010', 'L11A2', 'GDCD', '2025-2026', 0),
(106, 'GV010', 'L12A1', 'GDCD', '2025-2026', 0),
(107, 'GV006', 'L11A1', 'SINH', '2025-2026', 0),
(108, 'GV026', 'L12A1', 'SINH', '2025-2026', 0),
(109, 'GV025', 'L12A1', 'HOA', '2025-2026', 0),
(110, 'GV007', 'L11A2', 'SU', '2025-2026', 0),
(111, 'GV002', 'L12A1', 'VAN', '2025-2026', 0),
(112, 'GV005', 'L12A1', 'ANH', '2025-2026', 0),
(113, 'GV011', 'L12A1', 'CNTT', '2025-2026', 0),
(114, 'GV007', 'L12A2', 'SU', '2025-2026', 0),
(115, 'GV500', 'L11A2', 'QPAN', '2025-2026', 0),
(116, 'GV002', 'L11A2', 'VAN', '2025-2026', 0),
(117, 'GV009', 'L12A1', 'TD', '2025-2026', 0),
(118, 'GV007', 'L12A1', 'SU', '2025-2026', 0),
(119, 'GV500', 'L12A1', 'QPAN', '2025-2026', 0),
(120, 'GV004', 'L11A2', 'HOA', '2025-2026', 0),
(121, 'GV008', 'L12A1', 'DIA', '2025-2026', 0),
(122, 'GV025', 'L11A1', 'HOA', '2025-2026', 0),
(123, 'GV006', 'L10A2', 'SINH', '2025-2026', 0),
(124, 'GV500', 'L12A2', 'QPAN', '2025-2026', 0),
(125, 'GV019', 'L11A1', 'VAN', '2025-2026', 0),
(126, 'GV022', 'L12A1', 'LY', '2025-2026', 0),
(127, 'GV011', 'L10A2', 'CNTT', '2025-2026', 0),
(128, 'GV021', 'L10A2', 'ANH', '2025-2026', 0),
(129, 'GV017', 'L10A2', 'TOAN', '2025-2026', 0),
(130, 'GV003', 'L10A2', 'LY', '2025-2026', 0),
(131, 'GV018', 'L10A2', 'VAN', '2025-2026', 0),
(132, 'GV500', 'L11A1', 'QPAN', '2025-2026', 0),
(133, 'GV015', 'L11A1', 'ANH', '2025-2026', 0),
(134, 'GV017', 'L12A1', 'TOAN', '2025-2026', 0),
(135, 'GV007', 'L11A1', 'SU', '2025-2026', 0),
(136, 'GV010', 'L11A1', 'GDCD', '2025-2026', 0),
(137, 'GV017', 'L11A1', 'TOAN', '2025-2026', 0),
(138, 'GV009', 'L11A2', 'TD', '2025-2026', 0),
(139, 'GV011', 'L11A1', 'CNTT', '2025-2026', 0),
(140, 'GV009', 'L11A1', 'TD', '2025-2026', 0),
(141, 'GV022', 'L11A1', 'LY', '2025-2026', 0),
(142, 'GV008', 'L11A1', 'DIA', '2025-2026', 0),
(143, 'GV005', 'L11A2', 'ANH', '2025-2026', 0),
(144, 'GV022', 'L11A2', 'LY', '2025-2026', 0),
(145, 'GV008', 'L11A2', 'DIA', '2025-2026', 0),
(146, 'GV017', 'L11A2', 'TOAN', '2025-2026', 0),
(147, 'GV011', 'L11A2', 'CNTT', '2025-2026', 0),
(148, 'GV006', 'L11A2', 'SINH', '2025-2026', 0),
(149, 'GV009', 'L12A2', 'TD', '2025-2026', 0),
(150, 'GV002', 'L12A2', 'VAN', '2025-2026', 0),
(151, 'GV015', 'L12A2', 'ANH', '2025-2026', 0),
(152, 'GV006', 'L12A2', 'SINH', '2025-2026', 0),
(153, 'GV017', 'L12A2', 'TOAN', '2025-2026', 0),
(154, 'GV011', 'L12A2', 'CNTT', '2025-2026', 0),
(155, 'GV010', 'L12A2', 'GDCD', '2025-2026', 0),
(156, 'GV022', 'L12A2', 'LY', '2025-2026', 0),
(157, 'GV008', 'L12A2', 'DIA', '2025-2026', 0),
(158, 'GV004', 'L12A2', 'HOA', '2025-2026', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pcgvcn`
--

CREATE TABLE `pcgvcn` (
  `maPCGVCN` int(11) NOT NULL,
  `maGV` varchar(20) DEFAULT NULL,
  `maLop` varchar(20) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pcgvcn`
--

INSERT INTO `pcgvcn` (`maPCGVCN`, `maGV`, `maLop`, `namHoc`) VALUES
(1, 'GV001', 'L10A1', '2025-2026'),
(2, 'GV002', 'L10A2', '2025-2026'),
(4, 'GV004', 'L11A2', '2025-2026'),
(5, 'GV005', 'L12A1', '2025-2026'),
(6, 'GV010', 'L11A1', '2025-2026'),
(7, 'GV001', 'L10A1', '2024-2025');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
--

CREATE TABLE `phancong` (
  `maPC` int(11) NOT NULL,
  `maHS` varchar(20) DEFAULT NULL,
  `maLop` varchar(20) DEFAULT NULL,
  `maBan` varchar(20) DEFAULT NULL,
  `namHoc` varchar(20) DEFAULT NULL,
  `trangThai` enum('DangHoc','DaDong') DEFAULT 'DangHoc',
  `ngayPhanCong` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phancong`
--

INSERT INTO `phancong` (`maPC`, `maHS`, `maLop`, `maBan`, `namHoc`, `trangThai`, `ngayPhanCong`) VALUES
(1, 'HS001', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-10-31 04:33:15'),
(2, 'HS002', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-10-31 04:33:15'),
(16, 'HS003', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(17, 'HS004', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(18, 'HS005', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(19, 'HS006', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(20, 'HS007', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(21, 'HS008', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(22, 'HS009', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(23, 'HS010', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(24, 'HS011', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(25, 'HS012', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(26, 'HS013', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(27, 'HS014', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(28, 'HS015', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(29, 'HS016', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(30, 'HS017', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(31, 'HS018', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(32, 'HS019', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(33, 'HS020', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(34, 'HS021', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(35, 'HS022', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-09 15:38:39'),
(53, 'HS023', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-11-26 11:29:03'),
(54, 'HS001', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(55, 'HS002', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(56, 'HS003', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(57, 'HS004', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(58, 'HS005', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(59, 'HS006', 'L10A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(60, 'HS007', 'L10A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(61, 'HS008', 'L10A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(62, 'HS009', 'L10A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(63, 'HS010', 'L10A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(64, 'HS011', 'L11A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(65, 'HS012', 'L11A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(66, 'HS013', 'L11A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(67, 'HS014', 'L11A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(68, 'HS015', 'L11A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(69, 'HS016', 'L11A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(70, 'HS017', 'L11A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(71, 'HS018', 'L11A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(72, 'HS019', 'L11A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(73, 'HS020', 'L11A2', 'XH', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(74, 'HS021', 'L12A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(75, 'HS022', 'L12A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29'),
(76, 'HS023', 'L10A1', 'TN', '2024-2025', 'DangHoc', '2025-12-20 03:36:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phonghoc`
--

CREATE TABLE `phonghoc` (
  `maPhong` varchar(20) NOT NULL,
  `tenPhong` varchar(50) NOT NULL,
  `sucChua` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phonghoc`
--

INSERT INTO `phonghoc` (`maPhong`, `tenPhong`, `sucChua`) VALUES
('P101', 'Phòng 101', 45),
('P102', 'Phòng 102', 45),
('P201', 'Phòng 201', 45),
('P202', 'Phòng 202', 45),
('P301', 'Phòng 301', 45),
('P302', 'Phòng 302', 45),
('SAN', 'Sân trường', 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongthi`
--

CREATE TABLE `phongthi` (
  `maPhong` varchar(20) NOT NULL,
  `tenPhong` varchar(50) DEFAULT NULL,
  `soLuongHienTai` int(11) DEFAULT 0,
  `soLuongToiDa` int(11) DEFAULT NULL,
  `maTruong` varchar(20) DEFAULT NULL,
  `maMon` varchar(10) DEFAULT NULL,
  `maKyThi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongthi`
--

INSERT INTO `phongthi` (`maPhong`, `tenPhong`, `soLuongHienTai`, `soLuongToiDa`, `maTruong`, `maMon`, `maKyThi`) VALUES
('P37_A01', 'Phòng Anh 1 - Kỳ 37', 7, 30, NULL, 'ANH', 37),
('P37_T01', 'Phòng Toán 1 - Kỳ 37', 2, 30, NULL, 'TOAN', 37),
('P37_T02', 'Phòng Toán 2 - Kỳ 37', 0, 30, NULL, 'TOAN', 37),
('P37_V01', 'Phòng Văn 1 - Kỳ 37', 0, 30, NULL, 'VAN', 37),
('P37_V02', 'Phòng Văn 2 - Kỳ 37', 0, 30, NULL, 'VAN', 37),
('P38_A01', 'Phòng Anh 1 - Kỳ 38', 2, 30, NULL, 'ANH', 38),
('P38_T01', 'Phòng Toán 1 - Kỳ 38', 0, 30, NULL, 'TOAN', 38),
('P38_T02', 'Phòng Toán 2 - Kỳ 38', 0, 30, NULL, 'TOAN', 38),
('P38_V01', 'Phòng Văn 1 - Kỳ 38', 2, 30, NULL, 'VAN', 38),
('P38_V02', 'Phòng Văn 2 - Kỳ 38', 0, 30, NULL, 'VAN', 38),
('P39_A01', 'Phòng Anh 1 - Kỳ 39', 2, 30, NULL, 'ANH', 39),
('P39_T01', 'Phòng Toán 1 - Kỳ 39', 0, 30, NULL, 'TOAN', 39),
('P39_T02', 'Phòng Toán 2 - Kỳ 39', 0, 30, NULL, 'TOAN', 39),
('P39_V01', 'Phòng Văn 1 - Kỳ 39', 0, 30, NULL, 'VAN', 39),
('P39_V02', 'Phòng Văn 2 - Kỳ 39', 0, 30, NULL, 'VAN', 39),
('PT001', 'Phòng thi 1', 4, 30, 'TH001', 'TOAN', 1),
('PT002', 'Phòng thi 2', 0, 30, 'TH001', 'HOA', 1),
('PT003', 'Phòng thi 3', 0, 30, 'TH002', 'SINH', 1),
('PT004', 'Phòng thi 4', 0, 30, 'TH002', 'LY', 2),
('PT005', 'Phòng thi 5', 0, 30, 'TH003', 'ANH', 2),
('PT006', 'Phòng thi 6', 0, 30, 'TH001', 'TOAN', 3),
('PT007', 'Phòng thi 7', 0, 30, 'TH001', 'TOAN', 3),
('PT008', 'Phòng thi 8', 0, 30, 'TH001', 'VAN', 4),
('PT011', 'Phòng thi 11', 0, 30, 'TH001', 'VAN', 5),
('PT101', 'Phòng thi 101', 0, 30, 'TH001', 'TOAN', 9),
('PT102', 'Phòng thi 102', 0, 30, 'TH001', 'TOAN', 9),
('PT104', 'Phòng thi 104', 0, 30, 'TH001', 'TOAN', 10),
('PT105', 'Phòng thi 105', 0, 30, 'TH002', 'TOAN', 10),
('PT106', 'Phòng thi 106', 0, 30, 'TH001', 'TOAN', 11),
('PT111', 'Phòng thi 111', 0, 30, 'TH001', 'VAN', 13),
('PT112', 'Phòng thi 112', 0, 30, 'TH001', 'VAN', 13),
('PT113', 'Phòng thi 113', 0, 30, 'TH001', 'VAN', 14),
('PT115', 'Phòng thi 115', 0, 30, 'TH001', 'VAN', 15),
('PT117', 'Phòng thi 117', 0, 30, 'TH002', 'VAN', 15),
('PT119', 'Phòng thi 119', 0, 30, 'TH002', 'VAN', 16),
('PT120', 'Phòng thi 120', 0, 30, 'TH001', 'ANH', 17),
('PT121', 'Phòng thi 121', 0, 30, 'TH001', 'ANH', 17),
('PT127', 'Phòng thi 127', 0, 30, 'TH001', 'ANH', 20),
('PT129', 'Phòng thi 129', 0, 30, 'TH001', 'LY', 21),
('PT130', 'Phòng thi 130', 0, 30, 'TH001', 'LY', 21),
('PT134', 'Phòng thi 134', 0, 30, 'TH001', 'HOA', 23),
('PT25001', 'Phòng 1', 2, 30, 'TH001', 'TOAN', 36);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_tuyensinh`
--

CREATE TABLE `phong_tuyensinh` (
  `maPhongTS` int(11) NOT NULL,
  `maKyTS` varchar(20) NOT NULL,
  `maTruong` varchar(20) DEFAULT NULL,
  `tenPhongTS` varchar(100) NOT NULL,
  `diaDiem` varchar(255) DEFAULT NULL,
  `soLuongToiDa` int(11) DEFAULT 30,
  `soLuongHienTai` int(11) DEFAULT 0,
  `trangThai` varchar(20) DEFAULT 'ConTrong',
  `ghiChu` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_tuyensinh`
--

INSERT INTO `phong_tuyensinh` (`maPhongTS`, `maKyTS`, `maTruong`, `tenPhongTS`, `diaDiem`, `soLuongToiDa`, `soLuongHienTai`, `trangThai`, `ghiChu`, `createdAt`) VALUES
(1, 'KTS2026', 'TH001', 'Phòng 1', 'Trường Lê Hồng Phong', 30, 0, 'ConTrong', NULL, '2025-12-23 15:40:26'),
(2, 'KTS2026', 'TH001', 'Phòng thi A1', 'Trường THPT Lê Hồng Phong - Tầng 1', 30, 0, 'ConTrong', NULL, '2025-12-23 16:04:15'),
(3, 'KTS2026', 'TH001', 'Phòng thi A2', 'Trường THPT Lê Hồng Phong - Tầng 1', 30, 0, 'ConTrong', NULL, '2025-12-23 16:04:15'),
(4, 'KTS2026', 'TH002', 'Phòng thi B1', 'Trường THPT Trần Đại Nghĩa - Tầng 2', 25, 0, 'ConTrong', NULL, '2025-12-23 16:04:15'),
(5, 'KTS2026', 'TH003', 'Phòng thi C1', 'Trường THPT Gia Định - Tầng 3', 35, 0, 'ConTrong', NULL, '2025-12-23 16:04:15'),
(6, 'KTS2026', 'TH001', 'Phòng 1 - THPT Lê Hồng Phong', 'Tầng 1, Dãy A', 30, 0, 'ConTrong', NULL, '2025-12-23 16:14:42'),
(7, 'KTS2026', 'TH001', 'Phòng 2 - THPT Lê Hồng Phong', 'Tầng 1, Dãy B', 30, 0, 'ConTrong', NULL, '2025-12-23 16:14:42'),
(8, 'KTS2026', 'TH002', 'Phòng 1 - THPT Trần Đại Nghĩa', 'Tầng 2, Dãy A', 25, 0, 'ConTrong', NULL, '2025-12-23 16:14:42'),
(9, 'KTS2026', NULL, 'Phòng chung 1', 'Tầng 3, Dãy A', 40, 0, 'ConTrong', NULL, '2025-12-23 16:14:42'),
(10, 'KTS2026', NULL, 'Phòng chung 2', 'Tầng 3, Dãy B', 40, 0, 'ConTrong', NULL, '2025-12-23 16:14:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phuhuynh`
--

CREATE TABLE `phuhuynh` (
  `maPH` varchar(20) NOT NULL,
  `ngheNghiep` varchar(100) DEFAULT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phuhuynh`
--

INSERT INTO `phuhuynh` (`maPH`, `ngheNghiep`, `maNguoiDung`) VALUES
('PH001', 'Kỹ sư', 'ND008'),
('PH002', 'Giáo viên', 'ND009'),
('PH003', 'Bác sĩ', 'ND010'),
('PH004', 'Kinh doanh', 'ND011'),
('PH005', 'Kỹ sư', 'ND058'),
('PH006', 'Giáo viên', 'ND059'),
('PH007', 'Bác sĩ', 'ND060'),
('PH008', 'Kế toán', 'ND061'),
('PH009', 'Kinh doanh', 'ND062'),
('PH010', 'Y tá', 'ND063'),
('PH011', 'Công nhân', 'ND064'),
('PH012', 'Nội trợ', 'ND065'),
('PH013', 'Tài xế', 'ND066'),
('PH014', 'Giáo viên', 'ND067'),
('PH015', 'Kỹ sư', 'ND068'),
('PH016', 'Bác sĩ', 'ND069'),
('PH017', 'Luật sư', 'ND070'),
('PH018', 'Nhân viên văn phòng', 'ND071'),
('PH019', 'Kinh doanh', 'ND072'),
('PH020', 'Giáo viên', 'ND073'),
('PH021', 'Kỹ thuật viên', 'ND074'),
('PH022', 'Y tá', 'ND075'),
('PH023', 'Thợ điện', 'ND076'),
('PH024', 'Nội trợ', 'ND077'),
('PH025', 'Nhân viên ngân hàng', 'ND078'),
('PH026', 'Giáo viên', 'ND079'),
('PH027', 'Kinh doanh', 'ND080'),
('PH028', 'Kế toán', 'ND081'),
('PH029', 'Kỹ sư', 'ND082'),
('PH030', 'Y tá', 'ND083'),
('PH031', 'Công nhân', 'ND084'),
('PH032', 'Nội trợ', 'ND085'),
('PH033', 'Tài xế', 'ND086'),
('PH034', 'Giáo viên', 'ND087'),
('PH035', 'Bác sĩ', 'ND088'),
('PH036', 'Kinh doanh', 'ND089'),
('PH037', 'Kỹ sư', 'ND090'),
('PH038', 'Giáo viên', 'ND091'),
('PH039', 'Luật sư', 'ND092');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quanhechild`
--

CREATE TABLE `quanhechild` (
  `maPH` varchar(20) NOT NULL,
  `maHS` varchar(20) NOT NULL,
  `quanHe` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quanhechild`
--

INSERT INTO `quanhechild` (`maPH`, `maHS`, `quanHe`) VALUES
('PH001', 'HS001', 'Bố'),
('PH002', 'HS002', 'Mẹ'),
('PH003', 'HS003', 'Bố'),
('PH004', 'HS004', 'Mẹ'),
('PH005', 'HS003', 'Bố'),
('PH006', 'HS004', 'Mẹ'),
('PH007', 'HS005', 'Bố'),
('PH008', 'HS006', 'Mẹ'),
('PH009', 'HS007', 'Bố'),
('PH010', 'HS008', 'Mẹ'),
('PH011', 'HS009', 'Bố'),
('PH012', 'HS010', 'Mẹ'),
('PH013', 'HS011', 'Bố'),
('PH014', 'HS012', 'Mẹ'),
('PH015', 'HS013', 'Bố'),
('PH016', 'HS014', 'Mẹ'),
('PH017', 'HS015', 'Bố'),
('PH018', 'HS016', 'Mẹ'),
('PH019', 'HS017', 'Bố'),
('PH020', 'HS018', 'Mẹ'),
('PH021', 'HS019', 'Bố'),
('PH022', 'HS020', 'Mẹ'),
('PH023', 'HS021', 'Bố'),
('PH024', 'HS022', 'Mẹ'),
('PH025', 'HS023', 'Bố');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quantri`
--

CREATE TABLE `quantri` (
  `maQT` varchar(20) NOT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quantri`
--

INSERT INTO `quantri` (`maQT`, `maNguoiDung`) VALUES
('QT001', 'ND001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sogiaoduc`
--

CREATE TABLE `sogiaoduc` (
  `maSoGD` varchar(20) NOT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sogiaoduc`
--

INSERT INTO `sogiaoduc` (`maSoGD`, `maNguoiDung`) VALUES
('SGD001', 'ND020');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `tenDangNhap` varchar(50) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `vaiTro` enum('QuanTri','BanGiamHieu','GiaoVien','PhuHuynh','HocSinh','SoGiaoDuc') NOT NULL,
  `trangThai` enum('HoatDong','BiKhoa') DEFAULT 'HoatDong',
  `email` varchar(100) DEFAULT NULL,
  `maNguoiDung` varchar(20) DEFAULT NULL,
  `ngayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`tenDangNhap`, `matKhau`, `vaiTro`, `trangThai`, `email`, `maNguoiDung`, `ngayTao`) VALUES
('admin', 'admin', 'QuanTri', 'HoatDong', 'admin@school.edu.vn', 'ND001', '2025-10-31 04:33:14'),
('gv_anh2', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'phamthianh@school.edu.vn', 'ND213', '2025-11-17 13:47:47'),
('gv_anh3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'hoangthianh@school.edu.vn', 'ND218', '2025-11-17 14:05:35'),
('gv_anh4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'vovananh@school.edu.vn', 'ND219', '2025-11-17 14:05:35'),
('gv_congnghe', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'tranvancongnghe@school.edu.vn', 'ND210', '2025-11-17 13:37:59'),
('gv_dia', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'lethidia@school.edu.vn', 'ND203', '2025-11-17 13:37:59'),
('gv_gdcd', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'hoangthicong@school.edu.vn', 'ND205', '2025-11-17 13:37:59'),
('gv_hoa3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'luuthihoa@school.edu.vn', 'ND222', '2025-11-17 14:05:35'),
('gv_hoa4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'phanvanhoa@school.edu.vn', 'ND223', '2025-11-17 14:05:35'),
('gv_kythuat', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'buivanky@school.edu.vn', 'ND208', '2025-11-17 13:37:59'),
('gv_ly3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'dangthily@school.edu.vn', 'ND220', '2025-11-17 14:05:35'),
('gv_ly4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'buivanly@school.edu.vn', 'ND221', '2025-11-17 14:05:35'),
('gv_mythuat', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'nguyenthimy@school.edu.vn', 'ND209', '2025-11-17 13:37:59'),
('gv_nhac', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'dangthinac@school.edu.vn', 'ND207', '2025-11-17 13:37:59'),
('gv_sinh', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'nguyenthisinh@school.edu.vn', 'ND201', '2025-11-17 13:37:59'),
('gv_sinh3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'trinhthisinh@school.edu.vn', 'ND224', '2025-11-17 14:05:35'),
('gv_sinh4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'dovansinh@school.edu.vn', 'ND225', '2025-11-17 14:05:35'),
('gv_su', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'tranvansu@school.edu.vn', 'ND202', '2025-11-17 13:37:59'),
('gv_theduc', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'phamvanthe@school.edu.vn', 'ND204', '2025-11-17 13:37:59'),
('gv_tin', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'vominhtin@school.edu.vn', 'ND206', '2025-11-17 13:37:59'),
('gv_toan2', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'tranthitoan@school.edu.vn', 'ND211', '2025-11-17 13:47:47'),
('gv_toan3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'lethitoan@school.edu.vn', 'ND214', '2025-11-17 14:05:35'),
('gv_toan4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'phamvantoan@school.edu.vn', 'ND215', '2025-11-17 14:05:35'),
('gv_van2', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'levanvan@school.edu.vn', 'ND212', '2025-11-17 13:47:47'),
('gv_van3', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'tranthivan@school.edu.vn', 'ND216', '2025-11-17 14:05:35'),
('gv_van4', 'e10adc3949ba59abbe56e057f20f883e', 'GiaoVien', 'HoatDong', 'nguyenvanvan@school.edu.vn', 'ND217', '2025-11-17 14:05:35'),
('gv001', 'gv001', 'GiaoVien', 'HoatDong', 'levantoan@school.edu.vn', 'ND003', '2025-10-31 04:33:14'),
('gv002', 'gv002', 'GiaoVien', 'HoatDong', 'phamthivan@school.edu.vn', 'ND004', '2025-10-31 04:33:14'),
('gv003', 'c051826674a3fb70541d6376df16ec4f', 'GiaoVien', 'HoatDong', 'hoangminhly@school.edu.vn', 'ND005', '2025-10-31 04:33:14'),
('gv004', '123', 'GiaoVien', 'HoatDong', 'nguyenthihoa@school.edu.vn', 'ND006', '2025-10-31 04:33:14'),
('gv005', 'c051826674a3fb70541d6376df16ec4f', 'GiaoVien', 'HoatDong', 'tranvananh@school.edu.vn', 'ND007', '2025-10-31 04:33:14'),
('hieutruong', 'hieutruong001', 'BanGiamHieu', 'HoatDong', 'hieutruong@school.edu.vn', 'ND002', '2025-10-31 04:33:14'),
('hs001', 'hs001', 'HocSinh', 'HoatDong', 'nman@student.edu.vn', 'ND012', '2025-10-31 04:33:15'),
('hs002', 'hs002', 'HocSinh', 'HoatDong', 'ttbao@student.edu.vn', 'ND013', '2025-10-31 04:33:15'),
('hs003', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'lvcuong@student.edu.vn', 'ND014', '2025-10-31 04:33:15'),
('hs004', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'ptduyen@student.edu.vn', 'ND015', '2025-10-31 04:33:15'),
('hs005', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'hvem@student.edu.vn', 'ND016', '2025-10-31 04:33:15'),
('hs006', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'vtphuong@student.edu.vn', 'ND017', '2025-10-31 04:33:15'),
('hs007', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'dvgiang@student.edu.vn', 'ND018', '2025-10-31 04:33:15'),
('hs008', '9fb962c9785f93c775ecc253cad2cd32', 'HocSinh', 'HoatDong', 'bthoa@student.edu.vn', 'ND019', '2025-10-31 04:33:15'),
('hs009', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'nthanh@student.edu.vn', 'ND027', '2025-11-09 15:38:39'),
('hs010', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'tqduy@student.edu.vn', 'ND028', '2025-11-09 15:38:39'),
('hs011', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lthuong@student.edu.vn', 'ND029', '2025-11-09 15:38:39'),
('hs012', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'patuan@student.edu.vn', 'ND030', '2025-11-09 15:38:39'),
('hs013', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'ntkoanh@student.edu.vn', 'ND031', '2025-11-09 15:38:39'),
('hs014', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'vdlong@student.edu.vn', 'ND032', '2025-11-09 15:38:39'),
('hs015', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lbngoc@student.edu.vn', 'ND033', '2025-11-09 15:38:39'),
('hs016', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'nvnam@student.edu.vn', 'ND034', '2025-11-09 15:38:39'),
('hs017', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'tnlan@student.edu.vn', 'ND035', '2025-11-09 15:38:39'),
('hs019', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lthong@student.edu.vn', 'ND037', '2025-11-09 15:38:39'),
('hs020', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'dakhoa@student.edu.vn', 'ND038', '2025-11-09 15:38:39'),
('hs021', '123', 'HocSinh', 'HoatDong', 'nnlinh@student.edu.vn', 'ND039', '2025-11-09 15:38:39'),
('hs022', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'thutai@student.edu.vn', 'ND040', '2025-11-09 15:38:39'),
('hs023', '123', 'HocSinh', 'HoatDong', 'pqtrang@student.edu.vn', 'ND041', '2025-11-09 15:38:39'),
('hs025', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'ttmthu@student.edu.vn', 'ND043', '2025-11-09 15:38:39'),
('hs026', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'vhnghia@student.edu.vn', 'ND044', '2025-11-09 15:38:39'),
('hs027', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lthang@student.edu.vn', 'ND045', '2025-11-09 15:38:39'),
('hs028', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'nvbinh2@student.edu.vn', 'ND046', '2025-11-09 15:38:39'),
('hs029', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'than@student.edu.vn', 'ND047', '2025-11-09 15:38:39'),
('hs030', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lvtai@student.edu.vn', 'ND048', '2025-11-09 15:38:39'),
('hs031', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'ptny@student.edu.vn', 'ND049', '2025-11-09 15:38:39'),
('hs032', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'dmquan@student.edu.vn', 'ND050', '2025-11-09 15:38:39'),
('hs033', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'ntttuyen@student.edu.vn', 'ND051', '2025-11-09 15:38:39'),
('hs034', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'vvhung@student.edu.vn', 'ND052', '2025-11-09 15:38:39'),
('hs035', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'ltmphuong@student.edu.vn', 'ND053', '2025-11-09 15:38:39'),
('hs036', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'phdat@student.edu.vn', 'ND054', '2025-11-09 15:38:39'),
('hs037', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'tkvy@student.edu.vn', 'ND055', '2025-11-09 15:38:39'),
('hs038', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'lqan@student.edu.vn', 'ND056', '2025-11-09 15:38:39'),
('hs039', 'e10adc3949ba59abbe56e057f20f883e', 'HocSinh', 'HoatDong', 'bnyen@student.edu.vn', 'ND057', '2025-11-09 15:38:39'),
('j', 'j', 'QuanTri', 'HoatDong', 'abc@iuh.edu.vn', 'NguoiDung002', '2025-12-16 14:44:09'),
('ph001', '123', 'PhuHuynh', 'HoatDong', 'nvbinh@gmail.com', 'ND008', '2025-10-31 04:33:14'),
('ph002', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ttmai@gmail.com', 'ND009', '2025-10-31 04:33:14'),
('ph003', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lvcuong@gmail.com', 'ND010', '2025-10-31 04:33:14'),
('ph004', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptdung@gmail.com', 'ND011', '2025-10-31 04:33:14'),
('ph005', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'nvcuong.ph@gmail.com', 'ND058', '2025-11-10 07:03:12'),
('ph006', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ttmai.ph@gmail.com', 'ND059', '2025-11-10 07:03:12'),
('ph007', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lhson.ph@gmail.com', 'ND060', '2025-11-10 07:03:12'),
('ph008', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptha.ph@gmail.com', 'ND061', '2025-11-10 07:03:12'),
('ph009', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'vmtuan.ph@gmail.com', 'ND062', '2025-11-10 07:03:12'),
('ph010', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'dnlan.ph@gmail.com', 'ND063', '2025-11-10 07:03:12'),
('ph011', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'nvbinh.ph@gmail.com', 'ND064', '2025-11-10 07:03:12'),
('ph012', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'tthuong.ph@gmail.com', 'ND065', '2025-11-10 07:03:12'),
('ph013', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lvduc.ph@gmail.com', 'ND066', '2025-11-10 07:03:12'),
('ph014', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptnga.ph@gmail.com', 'ND067', '2025-11-10 07:03:12'),
('ph015', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'nmkhoa.ph@gmail.com', 'ND068', '2025-11-10 07:03:12'),
('ph016', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'vtthanh.ph@gmail.com', 'ND069', '2025-11-10 07:03:12'),
('ph017', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lqhung.ph@gmail.com', 'ND070', '2025-11-10 07:03:12'),
('ph018', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ntphuong.ph@gmail.com', 'ND071', '2025-11-10 07:03:12'),
('ph019', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'tvlong.ph@gmail.com', 'ND072', '2025-11-10 07:03:12'),
('ph020', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptanh.ph@gmail.com', 'ND073', '2025-11-10 07:03:12'),
('ph021', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lvtam.ph@gmail.com', 'ND074', '2025-11-10 07:03:12'),
('ph022', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'dthong.ph@gmail.com', 'ND075', '2025-11-10 07:03:12'),
('ph023', '123', 'PhuHuynh', 'HoatDong', 'nvhai.ph@gmail.com', 'ND076', '2025-11-10 07:03:12'),
('ph024', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ttlinh.ph@gmail.com', 'ND077', '2025-11-10 07:03:12'),
('ph025', '123', 'PhuHuynh', 'HoatDong', 'lvnam.ph@gmail.com', 'ND078', '2025-11-10 07:03:12'),
('ph026', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptyen.ph@gmail.com', 'ND079', '2025-11-10 07:03:12'),
('ph027', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'dvtu.ph@gmail.com', 'ND080', '2025-11-10 07:03:12'),
('ph028', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ttloan.ph@gmail.com', 'ND081', '2025-11-10 07:03:12'),
('ph029', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'vvtai.ph@gmail.com', 'ND082', '2025-11-10 07:03:12'),
('ph030', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ltha.ph@gmail.com', 'ND083', '2025-11-10 07:03:12'),
('ph031', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'nvkien.ph@gmail.com', 'ND084', '2025-11-10 07:03:12'),
('ph032', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ttngoc.ph@gmail.com', 'ND085', '2025-11-10 07:03:12'),
('ph033', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'lvphong.ph@gmail.com', 'ND086', '2025-11-10 07:03:12'),
('ph034', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ptthao.ph@gmail.com', 'ND087', '2025-11-10 07:03:12'),
('ph035', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'dvhung.ph@gmail.com', 'ND088', '2025-11-10 07:03:12'),
('ph036', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'nttuyet.ph@gmail.com', 'ND089', '2025-11-10 07:03:12'),
('ph037', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'vvdat.ph@gmail.com', 'ND090', '2025-11-10 07:03:12'),
('ph038', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'ltmy.ph@gmail.com', 'ND091', '2025-11-10 07:03:12'),
('ph039', '8cabea410e9782a0e2f4f82a06838a58', 'PhuHuynh', 'HoatDong', 'pvquang.ph@gmail.com', 'ND092', '2025-11-10 07:03:12'),
('ph111', 'ph001', 'PhuHuynh', 'HoatDong', 'ph001@school.edu.vn', 'ND079', '2025-11-12 08:43:43'),
('ph123', 'ph123', 'PhuHuynh', 'HoatDong', 'phuhuynh@school.edu.vn', 'ND037', '2025-11-12 07:08:02'),
('phuhuynh', 'ph123', 'PhuHuynh', 'HoatDong', 'phuhuynh@school.edu.vn', 'ND037', '2025-11-12 07:06:25'),
('sogd001', 'b238034cd4eb9ecdda0c58869113370e', 'SoGiaoDuc', 'HoatDong', 'vvson@sgd.hcm.gov.vn', 'ND020', '2025-10-31 04:33:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thoikhoabieu`
--

CREATE TABLE `thoikhoabieu` (
  `maTKB` int(11) NOT NULL,
  `maLop` varchar(20) NOT NULL,
  `thu` int(11) NOT NULL COMMENT '2-7 (Thứ 2 đến Thứ 7)',
  `tiet` int(11) NOT NULL COMMENT '1-5',
  `maMon` varchar(20) NOT NULL,
  `maGV` varchar(20) NOT NULL,
  `hocKy` int(11) NOT NULL DEFAULT 1,
  `namHoc` varchar(20) NOT NULL DEFAULT '2024-2025',
  `tuanBatDau` date NOT NULL,
  `tuanKetThuc` date NOT NULL,
  `maPhong` varchar(20) DEFAULT NULL,
  `is_template` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thoikhoabieu`
--

INSERT INTO `thoikhoabieu` (`maTKB`, `maLop`, `thu`, `tiet`, `maMon`, `maGV`, `hocKy`, `namHoc`, `tuanBatDau`, `tuanKetThuc`, `maPhong`, `is_template`) VALUES
(813, 'L10A1', 4, 2, 'VAN', 'GV014', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(814, 'L10A1', 5, 1, 'VAN', 'GV014', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(815, 'L10A1', 3, 1, 'VAN', 'GV014', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(816, 'L10A1', 3, 2, 'VAN', 'GV014', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(817, 'L10A1', 5, 2, 'TOAN', 'GV001', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(818, 'L10A1', 5, 3, 'TOAN', 'GV001', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(819, 'L10A1', 3, 3, 'TOAN', 'GV001', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(820, 'L10A1', 4, 1, 'TOAN', 'GV001', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(821, 'L10A1', 4, 3, 'HOA', 'GV024', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(822, 'L10A1', 6, 2, 'HOA', 'GV024', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(823, 'L10A1', 2, 1, 'HOA', 'GV024', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(824, 'L10A1', 2, 2, 'ANH', 'GV020', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(825, 'L10A1', 5, 4, 'ANH', 'GV020', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(826, 'L10A1', 2, 3, 'ANH', 'GV020', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(827, 'L10A1', 4, 4, 'LY', 'GV023', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(828, 'L10A1', 6, 3, 'LY', 'GV023', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(829, 'L10A1', 6, 1, 'LY', 'GV023', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(830, 'L10A1', 3, 4, 'DIA', 'GV008', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(831, 'L10A1', 7, 2, 'DIA', 'GV008', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(832, 'L10A1', 7, 1, 'SU', 'GV007', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(833, 'L10A1', 7, 3, 'SU', 'GV007', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(834, 'L10A1', 3, 5, 'SINH', 'GV027', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(835, 'L10A1', 5, 5, 'SINH', 'GV027', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(836, 'L10A1', 2, 4, 'GDCD', 'GV010', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(837, 'L10A1', 4, 5, 'CNTT', 'GV011', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(838, 'L10A1', 2, 6, 'QPAN', 'GV500', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(839, 'L10A1', 2, 7, 'QPAN', 'GV500', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(840, 'L10A1', 2, 8, 'QPAN', 'GV500', 2, '2025-2026', '2026-01-16', '2026-05-31', 'P101', 0),
(841, 'L10A1', 3, 6, 'TD', 'GV009', 2, '2025-2026', '2026-01-16', '2026-05-31', 'SAN', 0),
(842, 'L10A1', 3, 7, 'TD', 'GV009', 2, '2025-2026', '2026-01-16', '2026-05-31', 'SAN', 0),
(843, 'L10A1', 3, 8, 'TD', 'GV009', 2, '2025-2026', '2026-01-16', '2026-05-31', 'SAN', 0),
(844, 'L10A1', 5, 1, 'VAN', 'GV014', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(845, 'L10A1', 3, 3, 'VAN', 'GV014', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(846, 'L10A1', 3, 2, 'VAN', 'GV014', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(847, 'L10A1', 4, 1, 'VAN', 'GV014', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(848, 'L10A1', 3, 1, 'TOAN', 'GV001', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(849, 'L10A1', 4, 2, 'TOAN', 'GV001', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(850, 'L10A1', 5, 3, 'TOAN', 'GV001', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(851, 'L10A1', 5, 2, 'TOAN', 'GV001', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(852, 'L10A1', 4, 3, 'HOA', 'GV024', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(853, 'L10A1', 6, 3, 'HOA', 'GV024', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(854, 'L10A1', 2, 1, 'HOA', 'GV024', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(855, 'L10A1', 6, 1, 'ANH', 'GV020', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(856, 'L10A1', 4, 4, 'ANH', 'GV020', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(857, 'L10A1', 2, 3, 'ANH', 'GV020', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(858, 'L10A1', 2, 2, 'LY', 'GV023', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(859, 'L10A1', 5, 4, 'LY', 'GV023', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(860, 'L10A1', 3, 4, 'LY', 'GV023', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(861, 'L10A1', 6, 2, 'DIA', 'GV008', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(862, 'L10A1', 7, 2, 'DIA', 'GV008', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(863, 'L10A1', 7, 1, 'SU', 'GV007', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(864, 'L10A1', 7, 3, 'SU', 'GV007', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(865, 'L10A1', 4, 5, 'SINH', 'GV027', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(866, 'L10A1', 2, 4, 'SINH', 'GV027', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(867, 'L10A1', 3, 5, 'GDCD', 'GV010', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(868, 'L10A1', 6, 4, 'CNTT', 'GV011', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(869, 'L10A1', 2, 6, 'QPAN', 'GV500', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(870, 'L10A1', 2, 7, 'QPAN', 'GV500', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(871, 'L10A1', 2, 8, 'QPAN', 'GV500', 1, '2025-2026', '2025-09-01', '2026-01-15', 'P101', 0),
(872, 'L10A1', 3, 6, 'TD', 'GV009', 1, '2025-2026', '2025-09-01', '2026-01-15', 'SAN', 0),
(873, 'L10A1', 3, 7, 'TD', 'GV009', 1, '2025-2026', '2025-09-01', '2026-01-15', 'SAN', 0),
(874, 'L10A1', 3, 8, 'TD', 'GV009', 1, '2025-2026', '2025-09-01', '2026-01-15', 'SAN', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thoikhoabieu_tuan`
--

CREATE TABLE `thoikhoabieu_tuan` (
  `maTKB` int(11) NOT NULL,
  `maLop` varchar(10) NOT NULL,
  `thu` int(11) NOT NULL,
  `tiet` int(11) NOT NULL,
  `maMon` varchar(10) NOT NULL,
  `maGV` varchar(10) NOT NULL,
  `maPhong` varchar(10) DEFAULT NULL,
  `hocKy` int(11) NOT NULL DEFAULT 1,
  `namHoc` varchar(20) NOT NULL,
  `tuanBatDau` date NOT NULL,
  `tuanKetThuc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao` (
  `maThongBao` int(11) NOT NULL,
  `tieuDe` varchar(200) NOT NULL,
  `noiDung` text DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `huongDanThucHien` text DEFAULT NULL,
  `luuY` text DEFAULT NULL,
  `nguoiGui` varchar(20) DEFAULT NULL,
  `ngayGui` timestamp NOT NULL DEFAULT current_timestamp(),
  `doiTuong` enum('TatCa','HocSinh','PhuHuynh','GiaoVien','Lop') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thongbao`
--

INSERT INTO `thongbao` (`maThongBao`, `tieuDe`, `noiDung`, `moTa`, `huongDanThucHien`, `luuY`, `nguoiGui`, `ngayGui`, `doiTuong`) VALUES
(1, 'Thông báo lịch thi học kỳ 1', 'Lịch thi học kỳ 1 năm học 2024-2025 sẽ diễn ra từ ngày 15/12/2024', 'Lịch thi học kỳ 1 dành cho tất cả khối lớp, bao gồm các môn học bắt buộc và tự chọn.', 'Sinh viên xem chi tiết lịch thi tại mục Lịch học/Lịch thi trên hệ thống. Mang thẻ học sinh khi đi thi.', 'Đến sớm 15 phút trước giờ thi, mang đầy đủ dụng cụ học tập.', 'BGH001', '2025-10-31 04:33:15', 'TatCa'),
(7, 'Họp phụ huynh cuối học kỳ', 'Nhà trường tổ chức họp phụ huynh vào ngày 25/12/2025.', 'Họp trực tiếp tại trường', 'Phụ huynh đến đúng phòng học của lớp con em mình.', 'Mang theo sổ liên lạc học sinh.', 'GV002', '2025-12-17 08:24:04', 'TatCa'),
(8, 'Thông báo kiểm tra giữa kỳ', 'Nhà trường tổ chức kiểm tra giữa kỳ học kỳ I.', 'Áp dụng cho toàn bộ học sinh khối 10, 11, 12.', 'Học sinh xem lịch kiểm tra trên hệ thống.', 'Đi học đúng giờ, mang đầy đủ dụng cụ học tập.', 'Ban giám hiệu', '2025-12-24 05:37:27', 'HocSinh'),
(9, 'Nghỉ học do bảo trì cơ sở vật chất', 'Nhà trường thông báo nghỉ học 1 ngày.', 'Bảo trì hệ thống điện và phòng học.', 'Học sinh theo dõi thông báo mới trên website.', 'Không đến trường trong ngày thông báo.', 'Văn phòng', '2025-12-24 05:37:27', 'HocSinh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `truonghoc`
--

CREATE TABLE `truonghoc` (
  `maTruong` varchar(20) NOT NULL,
  `tenTruong` varchar(200) NOT NULL,
  `diaChi` text DEFAULT NULL,
  `soDienThoai` varchar(15) DEFAULT NULL,
  `chiTieuTuyenSinh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `truonghoc`
--

INSERT INTO `truonghoc` (`maTruong`, `tenTruong`, `diaChi`, `soDienThoai`, `chiTieuTuyenSinh`) VALUES
('TH001', 'THPT Lê Hồng Phong', '123 Đường Nguyễn Huệ, Q.1, TP.HCM', '0283822123', 500),
('TH002', 'THPT Trần Đại Nghĩa', '456 Đường Điện Biên Phủ, Q.3, TP.HCM', '0283911456', 600),
('TH003', 'THPT Gia Định', '789 Đường Phan Đăng Lưu, PN, TP.HCM', '0283966789', 450);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `baitap`
--
ALTER TABLE `baitap`
  ADD PRIMARY KEY (`maBaiTap`),
  ADD KEY `maLop` (`maLop`),
  ADD KEY `maMon` (`maMon`),
  ADD KEY `maGV` (`maGV`),
  ADD KEY `idx_baitap_trangthai` (`trangThai`);

--
-- Chỉ mục cho bảng `bangiamhieu`
--
ALTER TABLE `bangiamhieu`
  ADD PRIMARY KEY (`maBGH`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Chỉ mục cho bảng `bankp`
--
ALTER TABLE `bankp`
  ADD PRIMARY KEY (`maBan`);

--
-- Chỉ mục cho bảng `baocaothongke`
--
ALTER TABLE `baocaothongke`
  ADD PRIMARY KEY (`maBaoCao`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `maBaiTap` (`maBaiTap`);

--
-- Chỉ mục cho bảng `chitieu`
--
ALTER TABLE `chitieu`
  ADD PRIMARY KEY (`maChiTieu`),
  ADD KEY `maTruong` (`maTruong`);

--
-- Chỉ mục cho bảng `danhsachthi`
--
ALTER TABLE `danhsachthi`
  ADD PRIMARY KEY (`maDSThi`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `maPhong` (`maPhong`);

--
-- Chỉ mục cho bảng `diem`
--
ALTER TABLE `diem`
  ADD PRIMARY KEY (`maDiem`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `maMon` (`maMon`),
  ADD KEY `idx_diem_hocky` (`hocKy`,`namHoc`);

--
-- Chỉ mục cho bảng `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD PRIMARY KEY (`maDiemDanh`),
  ADD KEY `idx_diemdanh_malop_ngay` (`maLop`,`ngayDiemDanh`),
  ADD KEY `idx_diemdanh_mahs` (`maHS`),
  ADD KEY `idx_diemdanh_trangthai` (`trangThai`),
  ADD KEY `idx_diemdanh_ngay` (`ngayDiemDanh`);

--
-- Chỉ mục cho bảng `diem_tuyensinh`
--
ALTER TABLE `diem_tuyensinh`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `donnghihoc`
--
ALTER TABLE `donnghihoc`
  ADD PRIMARY KEY (`maDon`),
  ADD KEY `maHS` (`maHS`);

--
-- Chỉ mục cho bảng `giaovien`
--
ALTER TABLE `giaovien`
  ADD PRIMARY KEY (`maGV`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Chỉ mục cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  ADD PRIMARY KEY (`maHK`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `idx_hanhkiem_nguoinhap` (`nguoiNhap`);

--
-- Chỉ mục cho bảng `hocsinh`
--
ALTER TABLE `hocsinh`
  ADD PRIMARY KEY (`maHS`),
  ADD KEY `maNguoiDung` (`maNguoiDung`),
  ADD KEY `idx_hocsinh_dangthai` (`dangThaiHocTap`),
  ADD KEY `fk_hocsinh_phongthi` (`maPhong`);

--
-- Chỉ mục cho bảng `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD PRIMARY KEY (`maHS`),
  ADD KEY `maKyTS` (`maKyTS`),
  ADD KEY `idx_phongts` (`maPhongTS`),
  ADD KEY `idx_trangthaixep` (`trangThaiXepPhong`);

--
-- Chỉ mục cho bảng `khoatuan`
--
ALTER TABLE `khoatuan`
  ADD PRIMARY KEY (`maKhoaTuan`);

--
-- Chỉ mục cho bảng `khoi`
--
ALTER TABLE `khoi`
  ADD PRIMARY KEY (`maKhoi`);

--
-- Chỉ mục cho bảng `kythi`
--
ALTER TABLE `kythi`
  ADD PRIMARY KEY (`maKyThi`);

--
-- Chỉ mục cho bảng `ky_tuyensinh`
--
ALTER TABLE `ky_tuyensinh`
  ADD PRIMARY KEY (`maKyTS`);

--
-- Chỉ mục cho bảng `lichsu_suadiem`
--
ALTER TABLE `lichsu_suadiem`
  ADD PRIMARY KEY (`maLichSu`),
  ADD KEY `maDiem` (`maDiem`),
  ADD KEY `maNguoiSua` (`maNguoiSua`);

--
-- Chỉ mục cho bảng `lichthilop`
--
ALTER TABLE `lichthilop`
  ADD PRIMARY KEY (`maLichThi`),
  ADD KEY `idx_lop` (`maLop`),
  ADD KEY `idx_mon` (`maMon`),
  ADD KEY `idx_ngaythi` (`ngayThi`),
  ADD KEY `idx_phong` (`maPhong`);

--
-- Chỉ mục cho bảng `lop`
--
ALTER TABLE `lop`
  ADD PRIMARY KEY (`maLop`),
  ADD KEY `maKhoi` (`maKhoi`),
  ADD KEY `maBan` (`maBan`),
  ADD KEY `maPhong` (`maPhong`);

--
-- Chỉ mục cho bảng `minhchung_suadiem`
--
ALTER TABLE `minhchung_suadiem`
  ADD PRIMARY KEY (`maMinhChung`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `maMon` (`maMon`),
  ADD KEY `maGV` (`maGV`);

--
-- Chỉ mục cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`maMon`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maNguoiDung`);

--
-- Chỉ mục cho bảng `nguyenvong_tuyensinh`
--
ALTER TABLE `nguyenvong_tuyensinh`
  ADD PRIMARY KEY (`maNguyenVong`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `fk_nguyenvong_truong` (`maTruong`);

--
-- Chỉ mục cho bảng `nhanxetdanhgia`
--
ALTER TABLE `nhanxetdanhgia`
  ADD PRIMARY KEY (`maNhanXet`),
  ADD KEY `maHS` (`maHS`);

--
-- Chỉ mục cho bảng `pcgiamthi`
--
ALTER TABLE `pcgiamthi`
  ADD PRIMARY KEY (`maPCGT`),
  ADD KEY `maGV` (`maGV`),
  ADD KEY `maPhong` (`maPhong`);

--
-- Chỉ mục cho bảng `pcgvbm`
--
ALTER TABLE `pcgvbm`
  ADD PRIMARY KEY (`maPCGVBM`),
  ADD KEY `maGV` (`maGV`),
  ADD KEY `maLop` (`maLop`),
  ADD KEY `maMon` (`maMon`);

--
-- Chỉ mục cho bảng `pcgvcn`
--
ALTER TABLE `pcgvcn`
  ADD PRIMARY KEY (`maPCGVCN`),
  ADD KEY `maGV` (`maGV`),
  ADD KEY `maLop` (`maLop`);

--
-- Chỉ mục cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD PRIMARY KEY (`maPC`),
  ADD KEY `maHS` (`maHS`),
  ADD KEY `maLop` (`maLop`),
  ADD KEY `maBan` (`maBan`),
  ADD KEY `idx_phancong_namhoc` (`namHoc`);

--
-- Chỉ mục cho bảng `phonghoc`
--
ALTER TABLE `phonghoc`
  ADD PRIMARY KEY (`maPhong`);

--
-- Chỉ mục cho bảng `phongthi`
--
ALTER TABLE `phongthi`
  ADD PRIMARY KEY (`maPhong`),
  ADD KEY `maTruong` (`maTruong`),
  ADD KEY `maMon` (`maMon`),
  ADD KEY `fk_phongthi_kythi` (`maKyThi`);

--
-- Chỉ mục cho bảng `phong_tuyensinh`
--
ALTER TABLE `phong_tuyensinh`
  ADD PRIMARY KEY (`maPhongTS`),
  ADD KEY `fk_phong_kyts` (`maKyTS`),
  ADD KEY `fk_phong_truong` (`maTruong`);

--
-- Chỉ mục cho bảng `phuhuynh`
--
ALTER TABLE `phuhuynh`
  ADD PRIMARY KEY (`maPH`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Chỉ mục cho bảng `quanhechild`
--
ALTER TABLE `quanhechild`
  ADD PRIMARY KEY (`maPH`,`maHS`),
  ADD KEY `maHS` (`maHS`);

--
-- Chỉ mục cho bảng `quantri`
--
ALTER TABLE `quantri`
  ADD PRIMARY KEY (`maQT`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Chỉ mục cho bảng `sogiaoduc`
--
ALTER TABLE `sogiaoduc`
  ADD PRIMARY KEY (`maSoGD`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`tenDangNhap`),
  ADD KEY `maNguoiDung` (`maNguoiDung`),
  ADD KEY `idx_taikhoan_vaitro` (`vaiTro`);

--
-- Chỉ mục cho bảng `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD PRIMARY KEY (`maTKB`),
  ADD UNIQUE KEY `unique_lop_thu_tiet` (`maLop`,`thu`,`tiet`,`hocKy`,`namHoc`),
  ADD KEY `maMon` (`maMon`),
  ADD KEY `maGV` (`maGV`),
  ADD KEY `fk_tkb_phong` (`maPhong`);

--
-- Chỉ mục cho bảng `thoikhoabieu_tuan`
--
ALTER TABLE `thoikhoabieu_tuan`
  ADD PRIMARY KEY (`maTKB`),
  ADD UNIQUE KEY `unique_slot` (`maLop`,`thu`,`tiet`,`tuanBatDau`);

--
-- Chỉ mục cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`maThongBao`),
  ADD KEY `idx_thongbao_ngay` (`ngayGui`);

--
-- Chỉ mục cho bảng `truonghoc`
--
ALTER TABLE `truonghoc`
  ADD PRIMARY KEY (`maTruong`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `baitap`
--
ALTER TABLE `baitap`
  MODIFY `maBaiTap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `baocaothongke`
--
ALTER TABLE `baocaothongke`
  MODIFY `maBaoCao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT cho bảng `chitieu`
--
ALTER TABLE `chitieu`
  MODIFY `maChiTieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `danhsachthi`
--
ALTER TABLE `danhsachthi`
  MODIFY `maDSThi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `diem`
--
ALTER TABLE `diem`
  MODIFY `maDiem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT cho bảng `diemdanh`
--
ALTER TABLE `diemdanh`
  MODIFY `maDiemDanh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT cho bảng `diem_tuyensinh`
--
ALTER TABLE `diem_tuyensinh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `donnghihoc`
--
ALTER TABLE `donnghihoc`
  MODIFY `maDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  MODIFY `maHK` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `khoatuan`
--
ALTER TABLE `khoatuan`
  MODIFY `maKhoaTuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `kythi`
--
ALTER TABLE `kythi`
  MODIFY `maKyThi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `lichsu_suadiem`
--
ALTER TABLE `lichsu_suadiem`
  MODIFY `maLichSu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `lichthilop`
--
ALTER TABLE `lichthilop`
  MODIFY `maLichThi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `minhchung_suadiem`
--
ALTER TABLE `minhchung_suadiem`
  MODIFY `maMinhChung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `nguyenvong_tuyensinh`
--
ALTER TABLE `nguyenvong_tuyensinh`
  MODIFY `maNguyenVong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `nhanxetdanhgia`
--
ALTER TABLE `nhanxetdanhgia`
  MODIFY `maNhanXet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `pcgiamthi`
--
ALTER TABLE `pcgiamthi`
  MODIFY `maPCGT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `pcgvbm`
--
ALTER TABLE `pcgvbm`
  MODIFY `maPCGVBM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT cho bảng `pcgvcn`
--
ALTER TABLE `pcgvcn`
  MODIFY `maPCGVCN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `phancong`
--
ALTER TABLE `phancong`
  MODIFY `maPC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT cho bảng `phong_tuyensinh`
--
ALTER TABLE `phong_tuyensinh`
  MODIFY `maPhongTS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  MODIFY `maTKB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=875;

--
-- AUTO_INCREMENT cho bảng `thoikhoabieu_tuan`
--
ALTER TABLE `thoikhoabieu_tuan`
  MODIFY `maTKB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `maThongBao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `baitap`
--
ALTER TABLE `baitap`
  ADD CONSTRAINT `baitap_ibfk_1` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`),
  ADD CONSTRAINT `baitap_ibfk_2` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`),
  ADD CONSTRAINT `baitap_ibfk_3` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`);

--
-- Các ràng buộc cho bảng `bangiamhieu`
--
ALTER TABLE `bangiamhieu`
  ADD CONSTRAINT `bangiamhieu_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `baocaothongke`
--
ALTER TABLE `baocaothongke`
  ADD CONSTRAINT `baocaothongke_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `baocaothongke_ibfk_2` FOREIGN KEY (`maBaiTap`) REFERENCES `baitap` (`maBaiTap`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chitieu`
--
ALTER TABLE `chitieu`
  ADD CONSTRAINT `chitieu_ibfk_1` FOREIGN KEY (`maTruong`) REFERENCES `truonghoc` (`maTruong`);

--
-- Các ràng buộc cho bảng `danhsachthi`
--
ALTER TABLE `danhsachthi`
  ADD CONSTRAINT `danhsachthi_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhsachthi_ibfk_2` FOREIGN KEY (`maPhong`) REFERENCES `phongthi` (`maPhong`);

--
-- Các ràng buộc cho bảng `diem`
--
ALTER TABLE `diem`
  ADD CONSTRAINT `diem_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `diem_ibfk_2` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`);

--
-- Các ràng buộc cho bảng `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD CONSTRAINT `fk_diemdanh_hocsinh` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_diemdanh_lop` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `donnghihoc`
--
ALTER TABLE `donnghihoc`
  ADD CONSTRAINT `donnghihoc_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `giaovien`
--
ALTER TABLE `giaovien`
  ADD CONSTRAINT `giaovien_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  ADD CONSTRAINT `hanhkiem_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hocsinh`
--
ALTER TABLE `hocsinh`
  ADD CONSTRAINT `fk_hocsinh_phongthi` FOREIGN KEY (`maPhong`) REFERENCES `phongthi` (`maPhong`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `hocsinh_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD CONSTRAINT `hosotuyensinh_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `hosotuyensinh_ibfk_2` FOREIGN KEY (`maKyTS`) REFERENCES `ky_tuyensinh` (`maKyTS`);

--
-- Các ràng buộc cho bảng `lichsu_suadiem`
--
ALTER TABLE `lichsu_suadiem`
  ADD CONSTRAINT `lichsu_suadiem_ibfk_1` FOREIGN KEY (`maDiem`) REFERENCES `diem` (`maDiem`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lichsu_suadiem_ibfk_2` FOREIGN KEY (`maNguoiSua`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `lichthilop`
--
ALTER TABLE `lichthilop`
  ADD CONSTRAINT `fk_lichthilop_lop` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lichthilop_mon` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lichthilop_phong` FOREIGN KEY (`maPhong`) REFERENCES `phongthi` (`maPhong`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `lop`
--
ALTER TABLE `lop`
  ADD CONSTRAINT `lop_ibfk_1` FOREIGN KEY (`maKhoi`) REFERENCES `khoi` (`maKhoi`),
  ADD CONSTRAINT `lop_ibfk_2` FOREIGN KEY (`maBan`) REFERENCES `bankp` (`maBan`),
  ADD CONSTRAINT `lop_ibfk_3` FOREIGN KEY (`maPhong`) REFERENCES `phonghoc` (`maPhong`);

--
-- Các ràng buộc cho bảng `minhchung_suadiem`
--
ALTER TABLE `minhchung_suadiem`
  ADD CONSTRAINT `minhchung_suadiem_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `minhchung_suadiem_ibfk_2` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `minhchung_suadiem_ibfk_3` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `nguyenvong_tuyensinh`
--
ALTER TABLE `nguyenvong_tuyensinh`
  ADD CONSTRAINT `fk_nguyenvong_truong` FOREIGN KEY (`maTruong`) REFERENCES `truonghoc` (`maTruong`),
  ADD CONSTRAINT `nguyenvong_tuyensinh_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hosotuyensinh` (`maHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhanxetdanhgia`
--
ALTER TABLE `nhanxetdanhgia`
  ADD CONSTRAINT `nhanxetdanhgia_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pcgiamthi`
--
ALTER TABLE `pcgiamthi`
  ADD CONSTRAINT `pcgiamthi_ibfk_1` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`),
  ADD CONSTRAINT `pcgiamthi_ibfk_2` FOREIGN KEY (`maPhong`) REFERENCES `phongthi` (`maPhong`);

--
-- Các ràng buộc cho bảng `pcgvbm`
--
ALTER TABLE `pcgvbm`
  ADD CONSTRAINT `pcgvbm_ibfk_1` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`) ON DELETE CASCADE,
  ADD CONSTRAINT `pcgvbm_ibfk_2` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`) ON DELETE CASCADE,
  ADD CONSTRAINT `pcgvbm_ibfk_3` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pcgvcn`
--
ALTER TABLE `pcgvcn`
  ADD CONSTRAINT `pcgvcn_ibfk_1` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`) ON DELETE CASCADE,
  ADD CONSTRAINT `pcgvcn_ibfk_2` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD CONSTRAINT `phancong_ibfk_1` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `phancong_ibfk_2` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`),
  ADD CONSTRAINT `phancong_ibfk_3` FOREIGN KEY (`maBan`) REFERENCES `bankp` (`maBan`);

--
-- Các ràng buộc cho bảng `phongthi`
--
ALTER TABLE `phongthi`
  ADD CONSTRAINT `fk_phongthi_kythi` FOREIGN KEY (`maKyThi`) REFERENCES `kythi` (`maKyThi`),
  ADD CONSTRAINT `phongthi_ibfk_1` FOREIGN KEY (`maTruong`) REFERENCES `truonghoc` (`maTruong`),
  ADD CONSTRAINT `phongthi_ibfk_2` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`);

--
-- Các ràng buộc cho bảng `phuhuynh`
--
ALTER TABLE `phuhuynh`
  ADD CONSTRAINT `phuhuynh_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quanhechild`
--
ALTER TABLE `quanhechild`
  ADD CONSTRAINT `quanhechild_ibfk_1` FOREIGN KEY (`maPH`) REFERENCES `phuhuynh` (`maPH`) ON DELETE CASCADE,
  ADD CONSTRAINT `quanhechild_ibfk_2` FOREIGN KEY (`maHS`) REFERENCES `hocsinh` (`maHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quantri`
--
ALTER TABLE `quantri`
  ADD CONSTRAINT `quantri_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sogiaoduc`
--
ALTER TABLE `sogiaoduc`
  ADD CONSTRAINT `sogiaoduc_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD CONSTRAINT `fk_tkb_phong` FOREIGN KEY (`maPhong`) REFERENCES `phonghoc` (`maPhong`),
  ADD CONSTRAINT `thoikhoabieu_ibfk_1` FOREIGN KEY (`maLop`) REFERENCES `lop` (`maLop`),
  ADD CONSTRAINT `thoikhoabieu_ibfk_2` FOREIGN KEY (`maMon`) REFERENCES `monhoc` (`maMon`),
  ADD CONSTRAINT `thoikhoabieu_ibfk_3` FOREIGN KEY (`maGV`) REFERENCES `giaovien` (`maGV`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
