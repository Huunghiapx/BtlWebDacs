-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 19, 2024 lúc 02:21 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webdacs`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baiviet`
--

CREATE TABLE `baiviet` (
  `id` int(11) NOT NULL,
  `chude` varchar(255) DEFAULT NULL,
  `noidung` text DEFAULT NULL,
  `tacgia` varchar(100) DEFAULT NULL,
  `ngaydang` datetime NOT NULL DEFAULT current_timestamp(),
  `chuyenmuc_id` int(11) DEFAULT NULL,
  `luotthich` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `baiviet`
--

INSERT INTO `baiviet` (`id`, `chude`, `noidung`, `tacgia`, `ngaydang`, `chuyenmuc_id`, `luotthich`) VALUES
(1, 'Thông báo ngày ra mắt web', 'Webdacs diễn dàn ô tô hd', 'Hữu Nghĩa', '2024-07-14 08:20:04', 2, 24),
(2, 'Bán ô tô', 'Vinfast VF5', 'Admin', '2024-07-14 09:26:29', 8, 4),
(3, 'Học lái xe b2', 'hhhhhh', 'Admin', '2024-07-14 11:34:49', 6, 4),
(4, 'Chung kết Euro', 'Anh vs Tây ban nha', 'Admin', '2024-07-14 15:02:51', 9, 15),
(5, 'Họp ', 'báo cáo tuần', 'Admin', '2024-07-16 10:43:01', 2, 2),
(6, 'Nội quy diễn đàn', 'abcdef', 'Hữu Nghĩa', '2024-07-16 19:49:31', 1, 1),
(7, 'Bán KIA K5', '2022', 'Hữu Nghĩa', '2024-07-17 08:13:10', 8, 0),
(8, 'Sinh nhật diễn dàn', 'hpbd', 'Hữu Nghĩa', '2024-07-17 08:35:25', 2, 0),
(9, 'Cách uống bia không say', 'không uống', 'Hữu Nghĩa', '2024-07-17 08:36:58', 9, 0),
(10, 'Chào mọi người', 'hiiiiii', 'Hữu Nghĩa', '2024-07-17 08:41:44', 2, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `binhluan`
--

CREATE TABLE `binhluan` (
  `id` int(11) NOT NULL,
  `noidung` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  `baiviet_id` int(11) DEFAULT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `thichbinhluan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `binhluan`
--

INSERT INTO `binhluan` (`id`, `noidung`, `ngaytao`, `baiviet_id`, `nguoidung_id`, `thichbinhluan`) VALUES
(1, 'xin chào mọi người', '2024-07-14 01:31:04', 1, 1, 32),
(2, 'aaaaaaaaa', '2024-07-14 04:04:58', 1, 1, 10),
(3, 'bbbb', '2024-07-14 04:33:21', 1, 1, 5),
(4, 'kk', '2024-07-14 04:35:11', 3, 1, 8),
(5, 'Xin giá', '2024-07-14 08:01:22', 2, 1, 7),
(6, 'bbb', '2024-07-14 08:44:41', 1, 1, 1),
(7, 'Tây Ban Nha thắng Anh với tỷ số 2-1', '2024-07-16 03:37:27', 4, 1, 1),
(8, 'hhh', '2024-07-16 04:20:03', 1, 2, 2),
(9, 'aqqqq', '2024-07-16 04:20:16', 1, 2, 2),
(10, 'vv', '2024-07-16 05:27:00', 4, 1, 0),
(11, 'hayy', '2024-07-16 05:27:35', 4, 2, 0),
(12, 'ddddd', '2024-07-16 05:28:44', 4, 2, 1),
(13, 'tra loi huunghia', '2024-07-16 05:29:09', 4, 2, 0),
(14, 'hhhhhhh', '2024-07-16 08:28:24', 4, 2, 0),
(15, 'bbb', '2024-07-16 08:28:40', 4, 2, 0),
(16, 'bbb', '2024-07-16 08:28:52', 4, 2, 0),
(17, 'trloi  hn', '2024-07-16 09:48:03', 1, 2, 1),
(18, 'tra loi admin\r\n', '2024-07-16 09:48:31', 1, 2, 2),
(19, 'aaaaaa', '2024-07-16 10:10:38', 3, 2, 0),
(20, 'qqqqqqqqqqq', '2024-07-17 04:23:18', 1, 2, 0),
(21, 'hhhhh', '2024-07-17 08:26:57', 3, 2, 0),
(22, 'hhhhh', '2024-07-17 08:27:34', 3, 2, 0),
(23, 'truong kon', '2024-07-17 08:30:39', 3, 2, 0),
(24, 'mua', '2024-07-17 09:04:20', 2, 2, 1),
(25, 'văn minh ', '2024-07-18 01:41:03', 6, 2, 1),
(26, 'noooooooo', '2024-07-18 10:00:31', 1, 2, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyenmuc`
--

CREATE TABLE `chuyenmuc` (
  `id` int(11) NOT NULL,
  `ten` varchar(255) DEFAULT NULL,
  `danhmuc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyenmuc`
--

INSERT INTO `chuyenmuc` (`id`, `ten`, `danhmuc_id`) VALUES
(1, 'Nội Quy', 1),
(2, 'Thông báo', 1),
(3, 'Thảo luận và góp ý', 1),
(4, 'Tin tức trong nước', 2),
(5, 'Tin tức nước ngoài', 2),
(6, 'Kiến thức cơ bản', 3),
(7, 'Kiến thức nâng cao', 3),
(8, 'Mua Bán', 4),
(9, 'Chém gió', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL,
  `ten` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`id`, `ten`) VALUES
(1, 'Diễn Đàn Chính'),
(2, 'Tin Tức'),
(3, 'Kiến Thức'),
(4, 'Mua Bán'),
(5, 'Giải Trí');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diendan`
--

CREATE TABLE `diendan` (
  `id` int(11) NOT NULL,
  `chude` varchar(255) DEFAULT NULL,
  `chuyenmuc` varchar(255) DEFAULT NULL,
  `binhluan` text DEFAULT NULL,
  `luotxem` int(11) DEFAULT NULL,
  `tacgia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `username`, `password`, `email`) VALUES
(1, 'Admin', 'admin123', 'admin@gmail.com'),
(2, 'Hữu Nghĩa', 'nghiapx03', 'nghiahn.px@gmail.com'),
(3, 'Minh Hiền', 'hien123', 'minhhien@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `traloi`
--

CREATE TABLE `traloi` (
  `id` int(11) NOT NULL,
  `binhluan_id` int(11) DEFAULT NULL,
  `nguoidung_id` int(11) DEFAULT NULL,
  `noidung` text DEFAULT NULL,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  `thichtraloi` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `traloi`
--

INSERT INTO `traloi` (`id`, `binhluan_id`, `nguoidung_id`, `noidung`, `ngaytao`, `thichtraloi`) VALUES
(2, 1, 2, 'ssssrr', '2024-07-17 09:24:09', 1),
(3, 1, 2, 'chel', '2024-07-17 09:25:17', 1),
(4, 5, 2, '1ty', '2024-07-17 10:11:09', 0),
(5, 24, 2, 'chot', '2024-07-17 10:11:20', 6),
(6, 1, 2, 'hiii', '2024-07-17 10:11:35', 0),
(7, 25, 2, 'lịch sự', '2024-07-18 01:41:17', 3),
(8, 1, 2, 'fffffff', '2024-07-18 03:09:52', 1),
(9, 1, 2, 'ddddd', '2024-07-18 03:09:59', 0),
(10, 2, 2, 'b', '2024-07-18 03:10:10', 0),
(11, 2, 2, 'c', '2024-07-18 03:10:18', 0),
(12, 2, 2, 'dd', '2024-07-18 04:13:37', 0),
(13, 1, 2, 'hhhhh', '2024-07-18 04:15:29', 0),
(14, 7, 2, 'yes', '2024-07-18 04:34:35', 0),
(15, 25, 2, 'thanh lịch', '2024-07-18 04:39:47', 1),
(16, 2, 2, 'eee', '2024-07-18 10:00:41', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chuyenmuc` (`chuyenmuc_id`);

--
-- Chỉ mục cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_baiviet` (`baiviet_id`),
  ADD KEY `fk_nguoidung` (`nguoidung_id`);

--
-- Chỉ mục cho bảng `chuyenmuc`
--
ALTER TABLE `chuyenmuc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_danhmuc` (`danhmuc_id`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `diendan`
--
ALTER TABLE `diendan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `traloi`
--
ALTER TABLE `traloi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `binhluan_id` (`binhluan_id`),
  ADD KEY `nguoidung_id` (`nguoidung_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `chuyenmuc`
--
ALTER TABLE `chuyenmuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `diendan`
--
ALTER TABLE `diendan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `traloi`
--
ALTER TABLE `traloi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  ADD CONSTRAINT `fk_chuyenmuc` FOREIGN KEY (`chuyenmuc_id`) REFERENCES `chuyenmuc` (`id`);

--
-- Các ràng buộc cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `fk_baiviet` FOREIGN KEY (`baiviet_id`) REFERENCES `baiviet` (`id`),
  ADD CONSTRAINT `fk_nguoidung` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`);

--
-- Các ràng buộc cho bảng `chuyenmuc`
--
ALTER TABLE `chuyenmuc`
  ADD CONSTRAINT `fk_danhmuc` FOREIGN KEY (`danhmuc_id`) REFERENCES `danhmuc` (`id`);

--
-- Các ràng buộc cho bảng `traloi`
--
ALTER TABLE `traloi`
  ADD CONSTRAINT `traloi_ibfk_1` FOREIGN KEY (`binhluan_id`) REFERENCES `binhluan` (`id`),
  ADD CONSTRAINT `traloi_ibfk_2` FOREIGN KEY (`nguoidung_id`) REFERENCES `nguoidung` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
