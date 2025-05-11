-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 02, 2025 lúc 05:15 PM
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
-- Cơ sở dữ liệu: `coffeeshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bonuses_penalties`
--

CREATE TABLE `bonuses_penalties` (
  `bonus_penalty_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `type` enum('bonus','penalty') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text NOT NULL,
  `date` date NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bonuses_penalties`
--

INSERT INTO `bonuses_penalties` (`bonus_penalty_id`, `employee_id`, `type`, `amount`, `reason`, `date`, `deleted_at`) VALUES
(1, 5, 'bonus', 20000.00, 'Phục vụ tốt', '2025-03-27', NULL),
(2, 4, 'penalty', -10000.00, 'Nghỉ không lý do', '2025-03-27', NULL),
(3, 6, 'bonus', 30000.00, 'Làm chăm chỉ', '2025-04-05', NULL),
(4, 3, 'penalty', -10000.00, 'Làm bể ly', '2025-04-10', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `deleted_at`) VALUES
(1, 'Cà phê', NULL),
(2, 'Trà', NULL),
(3, 'Sinh tố', NULL),
(4, 'Nước đóng chai', NULL),
(6, 'Bánh ngọt', NULL),
(7, 'Sữa chua', NULL),
(8, 'Ăn vặt', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `phone_number`, `notes`, `deleted_at`) VALUES
(1, 'Nguyễn Văn Hùng', '0912345678', NULL, NULL),
(2, 'Trần Thị Mai', '0987654321', NULL, NULL),
(3, 'Phạm Minh Hoàng', '0909123456', NULL, NULL),
(4, 'Nguyễn Lan Anh', '0703974149', 'Khách hàng thân thiết', NULL),
(8, 'Hồ Như Ý', '0774490972', NULL, NULL),
(9, 'Trần Nguyệt Quế', '0912349871', NULL, NULL),
(10, 'Trần Hùng Cường', '0895632586', NULL, NULL),
(11, 'Nguyễn Hữu Ánh', '0978085369', NULL, NULL),
(12, 'Trần An', '0789456123', NULL, NULL),
(13, 'Hoàng Kim Anh', '0907334528', NULL, NULL),
(14, 'Trần Văn An', '0939789324', NULL, NULL),
(15, 'Nguyễn Vân', '0909123455', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff_serve','staff_counter','staff_barista') NOT NULL,
  `status` enum('active','locked') NOT NULL DEFAULT 'active',
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `username`, `password`, `role`, `status`, `phone_number`, `email`, `address`, `start_date`, `hourly_rate`, `deleted_at`) VALUES
(1, 'Admin', 'admin', '$2y$12$qiRjjCzUm.GfxD54VnpgyeNsSjOM2fWqbVmg7lho79GsVljsKIcR6', 'admin', 'active', '0911223344', 'admin123@gmail.com', 'Ninh Kiều, Cần Thơ', '2024-02-01', 0.00, NULL),
(2, 'Lê Thị Bông', 'bongle123', '$2y$12$Xl40W/tZjdGMPuSIM.kqpeaeuoEAa4/DO.asJVc2iBXXSsnfF5ok6', 'staff_barista', 'active', '0988776655', 'bongle123@gmail.com', '456 Đường Lê Lợi', '2024-02-05', 18000.00, NULL),
(3, 'Trần Văn Cảnh', 'canhtran456', '$2y$12$zkqYKVVJPMlp4o4K9npQz.gsnBTmohyTDikt6oLaER57NNLW6suyK', 'staff_barista', 'active', '0909001122', 'canhtran456@gmail.com', '789 Đường Hùng Vương', '2024-03-01', 18000.00, NULL),
(4, 'Phạm Thị Dung', 'dungpham789', '$2y$12$cfarWi5EUQAUfoaOVr1nzuEO9U.c8xBS.6jbJoHVpN4g1xy2mCvea', 'staff_serve', 'active', '0922334455', 'dungpham789@gmail.com', '567 Đường Trần Hưng Đạo', '2024-03-10', 15000.00, NULL),
(5, 'Lê Hồng Ngọc', 'ngoc4567', '$2y$12$0HOqQid4qE7ixKF1qWc6Bu4494KlLJSSSs2G02bwIeWX9B0V6XkMO', 'staff_serve', 'active', '0903465967', 'ngoc4567@gmail.com', 'Vĩnh Long', '2025-01-27', 15000.00, NULL),
(6, 'Nguyễn Hải Nam', 'nam789', '$2y$12$qFibBu95DHf.vpva0ewCaeZd6fds/VDJdkmBoSfMVd9aMBMtEoBfm', 'staff_counter', 'active', '0903465123', 'nam789@gmail.com', 'Đà Nẵng', '2025-01-28', 20000.00, NULL),
(7, 'Võ Thu Hà', 'thuha090', '$2y$12$u3oXVW.KbJqvh8kJhPlxLubFav1yyPH2jqxfioNQDTDr/ZZBze8tO', 'staff_counter', 'active', '0987892090', 'thuha090@gmail.com', 'Bạc Liêu', '2025-03-19', 20000.00, NULL),
(8, 'Lê Văn Thanh', 'thanh876', '$2y$12$KeV8gJBo8LaRKRVaVeXDZ.eFHktKntTTT39UFG9aIlKxkUszH5a2.', 'staff_serve', 'active', '0779230876', 'thanh876@gmail.com', 'Sóc Giang', '2025-03-23', 15000.00, NULL),
(9, 'Trần Thị Mai', 'maitran123', '$2y$12$lJWCwzUGX3SaaBqrCpBH0u8O0TqdWNR5R4XzP1JGFU.rQN4MEYMQK', 'staff_serve', 'locked', '0903149858', 'maitran123@gmail.com', 'Vĩnh Long', '2025-04-22', 15000.00, '2025-04-22 23:05:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `min_quantity` decimal(10,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `reserved_quantity` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `quantity`, `unit`, `cost_price`, `min_quantity`, `last_updated`, `deleted_at`, `reserved_quantity`) VALUES
(1, 'Hạt cà phê', 49.38, 'kg', 300000.00, 10.00, '2025-04-24 00:40:01', NULL, 0.00),
(2, 'Sữa tươi', 192.30, 'lít', 40000.00, 50.00, '2025-04-24 00:41:59', NULL, 0.00),
(3, 'Đường', 109.07, 'kg', 30000.00, 20.00, '2025-04-23 14:41:35', NULL, 0.00),
(4, 'Lá trà', 34.21, 'kg', 200000.00, 5.00, '2025-04-24 00:42:40', NULL, 0.02),
(5, 'Dâu tươi', 23.40, 'kg', 200000.00, 5.00, '2025-04-24 00:41:59', NULL, 0.00),
(6, 'Trà lipton', 229.00, 'gói', 2000.00, 10.00, '2025-04-23 07:30:18', NULL, 0.00),
(7, 'Xoài', 11.20, 'kg', 30000.00, 1.00, '2025-04-22 07:52:49', NULL, 0.00),
(8, 'Muối', 4.68, 'kg', 10000.00, 0.50, '2025-04-23 12:10:32', NULL, 0.00),
(9, 'Đào tươi', 9.86, 'kg', 50000.00, 0.50, '2025-04-24 00:42:40', NULL, 0.02),
(10, 'Lon trái vải', 24.30, 'lon', 50000.00, 1.00, '2025-04-23 07:30:18', NULL, 0.00),
(11, 'Chanh dây', 9.00, 'kg', 30000.00, 1.00, '2025-04-23 14:41:35', NULL, 0.00),
(12, 'Tắc', 6.65, 'kg', 10000.00, 0.50, '2025-04-23 08:32:44', NULL, 0.00),
(13, 'Bơ', 5.60, 'kg', 30000.00, 1.00, '2025-04-23 14:44:53', NULL, 0.00),
(14, 'Sting', 1.00, 'chai', 10000.00, 10.00, '2025-04-24 00:48:45', NULL, 0.00),
(15, 'Pepsi', 171.00, 'chai', 10000.00, 10.00, '2025-04-10 14:04:18', NULL, 0.00),
(16, '7 Up', 164.00, 'chai', 10000.00, 10.00, '2025-04-15 14:38:38', NULL, 0.00),
(17, 'C2', 5.00, 'chai', 10862.07, 10.00, '2025-04-22 07:53:53', NULL, 0.00),
(18, 'Bột mì', 20.00, 'kg', 20000.00, 2.00, '2025-04-17 14:59:29', NULL, 0.00),
(19, 'Trứng', 50.00, 'Quả', 6000.00, 10.00, '2025-04-17 15:00:17', NULL, 0.00),
(20, 'Phô mai', 5.00, 'kg', 130000.00, 0.50, '2025-04-17 15:02:53', NULL, 0.00),
(21, 'Bột cacao', 10.00, 'kg', 300000.00, 1.00, '2025-04-17 15:04:45', NULL, 0.00),
(22, 'Sữa chua Vinamilk vị dâu', 98.00, 'hộp', 7000.00, 10.00, '2025-04-22 08:38:04', NULL, 0.00),
(23, 'Sữa chua Vinamilk có đường', 97.00, 'hộp', 7000.00, 10.00, '2025-04-23 07:51:37', NULL, 0.00),
(24, 'Sữa chua Vinamilk không đường', 95.00, 'hộp', 7000.00, 10.00, '2025-04-22 08:34:40', NULL, 0.00),
(25, 'Sữa chua Vinamilk nha đam', 119.00, 'hộp', 7000.00, 10.00, '2025-04-23 12:02:39', NULL, 0.00),
(26, 'Khô gà', 68.00, 'gói', 25000.00, 5.00, '2025-04-22 08:24:07', NULL, 0.00),
(27, 'Khô heo', 60.00, 'gói', 30000.00, 5.00, '2025-04-17 15:10:26', NULL, 0.00),
(28, 'Khô bò', 45.00, 'gói', 40000.00, 5.00, '2025-04-18 15:44:46', NULL, 1.00),
(29, 'Hạt hướng dương', 48.00, 'gói', 12000.00, 5.00, '2025-04-22 07:48:34', NULL, 0.00),
(30, 'Sữa chua Vinamilk trái cây', 120.00, 'hộp', 7000.00, 10.00, '2025-04-22 14:18:58', NULL, 0.00),
(31, 'Sữa chua Vinamilk nếp cẩm', 120.00, 'hộp', 7000.00, 10.00, '2025-04-22 14:19:22', NULL, 0.00),
(32, 'Sữa chua Vinamilk việt quốc', 110.00, 'hộp', 7000.00, 10.00, '2025-04-22 14:20:04', NULL, 0.00),
(33, 'Bánh tráng phô mai', 100.00, 'gói', 12000.00, 10.00, '2025-04-23 14:44:53', NULL, 0.00),
(34, 'Bánh tráng sate hành', 100.00, 'gói', 12000.00, 10.00, '2025-04-22 14:51:08', NULL, 0.00),
(35, 'Bánh tráng sate tỏi', 100.00, 'gói', 12000.00, 10.00, '2025-04-22 14:54:09', NULL, 0.00),
(36, 'Cá viên', 50.00, 'kg', 100000.00, 2.00, '2025-04-22 15:10:02', NULL, 0.00),
(37, 'Xúc xích', 20.00, 'kg', 100000.00, 2.00, '2025-04-22 15:08:09', NULL, 0.00),
(38, 'Tôm viên', 25.00, 'kg', 120000.00, 2.00, '2025-04-22 15:10:29', NULL, 0.00),
(39, 'Khoai tây', 10.00, 'kg', 25000.00, 0.50, '2025-04-22 15:13:58', NULL, 0.00),
(40, 'Dầu ăn', 100.00, 'chai', 60000.00, 5.00, '2025-04-22 15:14:34', NULL, 0.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredient_logs`
--

CREATE TABLE `ingredient_logs` (
  `log_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity_change` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `new_cost_price` decimal(10,2) DEFAULT NULL,
  `log_type` enum('import','export','adjustment') NOT NULL DEFAULT 'adjustment',
  `employee_id` int(11) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredient_logs`
--

INSERT INTO `ingredient_logs` (`log_id`, `ingredient_id`, `quantity_change`, `reason`, `price`, `new_cost_price`, `log_type`, `employee_id`, `changed_at`) VALUES
(4, 5, 5.00, 'Mua thêm', 200000.00, 200000.00, 'import', 2, '2025-02-16 17:05:56'),
(5, 6, 200.00, 'Thêm mới nguyên liệu', 2000.00, 2000.00, 'import', 2, '2025-02-20 13:48:04'),
(6, 7, 15.00, 'Thêm mới nguyên liệu', 30000.00, 30000.00, 'import', 2, '2025-02-20 16:54:04'),
(7, 8, 5.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 16:57:39'),
(8, 9, 10.00, 'Thêm mới nguyên liệu', 50000.00, 50000.00, 'import', 2, '2025-02-20 17:01:26'),
(9, 10, 25.00, 'Thêm mới nguyên liệu', 50000.00, 50000.00, 'import', 2, '2025-02-20 17:03:28'),
(10, 11, 10.00, 'Thêm mới nguyên liệu', 30000.00, 30000.00, 'import', 2, '2025-02-20 17:05:55'),
(11, 12, 5.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 17:07:24'),
(12, 13, 10.00, 'Thêm mới nguyên liệu', 30000.00, 30000.00, 'import', 2, '2025-02-20 17:10:05'),
(13, 14, 100.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 17:13:56'),
(14, 15, 150.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 17:14:17'),
(15, 16, 120.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 17:14:41'),
(16, 17, 160.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 2, '2025-02-20 17:16:24'),
(18, 16, 20.00, 'Mua thêm', 10000.00, 10000.00, 'import', 2, '2025-02-22 14:57:04'),
(19, 6, 50.00, 'Vừa nhập thêm', 2000.00, 2000.00, 'import', 1, '2025-02-25 09:04:01'),
(21, 17, -10.00, 'Hết hạn sử dụng', NULL, 10000.00, 'export', 3, '2025-03-06 18:16:18'),
(22, 16, 20.00, 'Mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-06 18:20:23'),
(23, 16, 15.00, 'Mua thêm', 10000.00, 10000.00, 'import', 1, '2025-03-07 11:39:57'),
(36, 14, 50.00, 'Vừa mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-09 14:23:21'),
(37, 7, -2.00, 'Bị hỏng', NULL, 30000.00, 'export', 3, '2025-03-09 14:23:51'),
(38, 12, 2.00, 'Vừa mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-09 14:46:15'),
(39, 3, 10.00, 'Vừa mua thêm', 30000.00, 30000.00, 'import', 2, '2025-03-09 14:47:37'),
(70, 17, 20.00, 'Mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-17 17:43:17'),
(71, 15, 20.00, 'Mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-17 17:55:53'),
(72, 15, 20.00, 'Mua thêm', 10000.00, 10000.00, 'import', 3, '2025-03-17 17:56:31'),
(73, 15, -10.00, 'Hết hạn', NULL, 10000.00, 'export', 3, '2025-03-17 18:00:53'),
(74, 4, 5.00, 'Mua thêm', 200000.00, 200000.00, 'import', 3, '2025-03-17 18:02:17'),
(75, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #29', NULL, 300000.00, 'export', 3, '2025-03-20 04:43:35'),
(76, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #29', NULL, 10000.00, 'export', 3, '2025-03-20 04:43:35'),
(77, 1, -0.04, 'Dùng cho món \'Cà phê sữa\' trong đơn hàng #30', NULL, 300000.00, 'export', 3, '2025-03-20 04:43:45'),
(78, 2, -0.30, 'Dùng cho món \'Cà phê sữa\' trong đơn hàng #30', NULL, 40000.00, 'export', 3, '2025-03-20 04:43:45'),
(79, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #30', NULL, 300000.00, 'export', 3, '2025-03-20 04:43:45'),
(80, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #30', NULL, 10000.00, 'export', 3, '2025-03-20 04:43:45'),
(81, 2, -0.15, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #31', NULL, 40000.00, 'export', 3, '2025-03-20 04:43:54'),
(82, 7, -0.20, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #31', NULL, 30000.00, 'export', 3, '2025-03-20 04:43:54'),
(83, 3, -0.02, 'Dùng cho món \'Trà vải\' trong đơn hàng #31', NULL, 30000.00, 'export', 3, '2025-03-20 04:43:54'),
(84, 6, -1.00, 'Dùng cho món \'Trà vải\' trong đơn hàng #31', NULL, 2000.00, 'export', 3, '2025-03-20 04:43:54'),
(85, 10, -0.10, 'Dùng cho món \'Trà vải\' trong đơn hàng #31', NULL, 50000.00, 'export', 3, '2025-03-20 04:43:54'),
(86, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #31', NULL, 30000.00, 'export', 3, '2025-03-20 04:43:54'),
(87, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #31', NULL, 200000.00, 'export', 3, '2025-03-20 04:43:54'),
(88, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #31', NULL, 10000.00, 'export', 3, '2025-03-20 04:43:54'),
(89, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #31', NULL, 40000.00, 'export', 3, '2025-03-20 04:43:54'),
(90, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #31', NULL, 30000.00, 'export', 3, '2025-03-20 04:43:54'),
(91, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #31', NULL, 10000.00, 'export', 3, '2025-03-20 04:43:54'),
(92, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #52', NULL, 200000.00, 'export', 3, '2025-03-25 13:46:11'),
(93, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #52', NULL, 10000.00, 'export', 3, '2025-03-25 13:46:11'),
(94, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #53', NULL, 200000.00, 'export', 3, '2025-03-25 16:20:24'),
(95, 1, -0.04, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #53', NULL, 300000.00, 'export', 3, '2025-03-25 16:20:24'),
(96, 8, -0.04, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #53', NULL, 10000.00, 'export', 3, '2025-03-25 16:20:24'),
(97, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #54', NULL, 30000.00, 'export', 3, '2025-03-25 16:35:00'),
(98, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #54', NULL, 2000.00, 'export', 3, '2025-03-25 16:35:00'),
(99, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #61', NULL, 300000.00, 'export', 3, '2025-03-25 17:15:57'),
(100, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #61', NULL, 10000.00, 'export', 3, '2025-03-25 17:15:57'),
(101, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #62', NULL, 30000.00, 'export', 3, '2025-03-25 17:42:21'),
(102, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #62', NULL, 200000.00, 'export', 3, '2025-03-25 17:42:21'),
(103, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #62', NULL, 10000.00, 'export', 3, '2025-03-25 17:42:21'),
(104, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #63', NULL, 30000.00, 'export', 3, '2025-03-25 18:10:33'),
(105, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #63', NULL, 2000.00, 'export', 3, '2025-03-25 18:10:33'),
(106, 3, -0.03, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #64', NULL, 30000.00, 'export', 3, '2025-03-25 18:14:03'),
(107, 4, -0.02, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #64', NULL, 200000.00, 'export', 3, '2025-03-25 18:14:03'),
(108, 11, -0.10, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #64', NULL, 30000.00, 'export', 3, '2025-03-25 18:14:03'),
(109, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #65', NULL, 40000.00, 'export', 3, '2025-03-25 18:32:56'),
(110, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #65', NULL, 200000.00, 'export', 3, '2025-03-25 18:32:56'),
(111, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #66', NULL, 30000.00, 'export', 3, '2025-03-25 18:37:42'),
(112, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #66', NULL, 2000.00, 'export', 3, '2025-03-25 18:37:42'),
(113, 15, -1.00, 'Dùng cho món \'Pepsi\' trong đơn hàng #70', NULL, 10000.00, 'export', 3, '2025-03-25 19:44:48'),
(114, 2, -0.15, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #79', NULL, 40000.00, 'export', 3, '2025-03-25 19:45:35'),
(115, 7, -0.20, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #79', NULL, 30000.00, 'export', 3, '2025-03-25 19:45:35'),
(116, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #80', NULL, 40000.00, 'export', 3, '2025-03-25 19:53:52'),
(117, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #80', NULL, 30000.00, 'export', 3, '2025-03-25 19:53:52'),
(118, 15, -1.00, 'Dùng cho món \'Pepsi\' trong đơn hàng #81', NULL, 10000.00, 'export', 3, '2025-03-25 20:02:24'),
(119, 3, -0.02, 'Dùng cho món \'Trà vải\' trong đơn hàng #82', NULL, 30000.00, 'export', 3, '2025-03-26 08:29:39'),
(120, 6, -1.00, 'Dùng cho món \'Trà vải\' trong đơn hàng #82', NULL, 2000.00, 'export', 3, '2025-03-26 08:29:39'),
(121, 10, -0.10, 'Dùng cho món \'Trà vải\' trong đơn hàng #82', NULL, 50000.00, 'export', 3, '2025-03-26 08:29:39'),
(122, 2, -0.15, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #83', NULL, 40000.00, 'export', 3, '2025-03-26 08:40:09'),
(123, 7, -0.20, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #83', NULL, 30000.00, 'export', 3, '2025-03-26 08:40:09'),
(124, 15, -1.00, 'Dùng cho món \'Pepsi\' trong đơn hàng #83', NULL, 10000.00, 'export', 3, '2025-03-26 08:40:09'),
(125, 16, -1.00, 'Dùng cho món \'7 Up\' trong đơn hàng #83', NULL, 10000.00, 'export', 3, '2025-03-26 08:40:09'),
(126, 4, -0.03, 'Dùng cho món \'Trà xanh\' trong đơn hàng #84', NULL, 200000.00, 'export', 3, '2025-03-26 08:51:29'),
(127, 3, -0.06, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #85', NULL, 30000.00, 'export', 3, '2025-03-26 08:57:38'),
(128, 6, -3.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #85', NULL, 2000.00, 'export', 3, '2025-03-26 08:57:38'),
(129, 2, -0.45, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #86', NULL, 40000.00, 'export', 3, '2025-03-26 09:11:43'),
(130, 13, -0.60, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #86', NULL, 30000.00, 'export', 3, '2025-03-26 09:11:43'),
(131, 4, -0.04, 'Dùng cho món \'Trà xanh\' trong đơn hàng #87', NULL, 200000.00, 'export', 3, '2025-03-26 09:24:33'),
(132, 14, -2.00, 'Dùng cho món \'Sting\' trong đơn hàng #88', NULL, 10000.00, 'export', 3, '2025-03-26 09:32:45'),
(133, 16, -1.00, 'Dùng cho món \'7 Up\' trong đơn hàng #88', NULL, 10000.00, 'export', 3, '2025-03-26 09:32:45'),
(134, 2, -0.45, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #89', NULL, 40000.00, 'export', 3, '2025-03-26 12:34:43'),
(135, 5, -0.30, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #89', NULL, 200000.00, 'export', 3, '2025-03-26 12:34:43'),
(136, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #89', NULL, 10000.00, 'export', 3, '2025-03-26 12:34:43'),
(137, 3, -0.06, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #95', NULL, 30000.00, 'export', 3, '2025-03-26 12:38:04'),
(138, 4, -0.04, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #95', NULL, 200000.00, 'export', 3, '2025-03-26 12:38:04'),
(139, 11, -0.20, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #95', NULL, 30000.00, 'export', 3, '2025-03-26 12:38:04'),
(140, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #93', NULL, 30000.00, 'export', 3, '2025-03-26 12:39:50'),
(141, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #93', NULL, 2000.00, 'export', 3, '2025-03-26 12:39:50'),
(142, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #93', NULL, 40000.00, 'export', 3, '2025-03-26 12:39:50'),
(143, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #93', NULL, 30000.00, 'export', 3, '2025-03-26 12:39:50'),
(144, 2, -0.30, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #90', NULL, 40000.00, 'export', 3, '2025-03-26 12:49:47'),
(145, 5, -0.20, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #90', NULL, 200000.00, 'export', 3, '2025-03-26 12:49:47'),
(146, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #90', NULL, 10000.00, 'export', 3, '2025-03-26 12:49:47'),
(147, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #94', NULL, 300000.00, 'export', 3, '2025-03-26 13:04:48'),
(148, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #94', NULL, 300000.00, 'export', 3, '2025-03-26 13:04:48'),
(149, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #94', NULL, 10000.00, 'export', 3, '2025-03-26 13:04:48'),
(150, 3, -0.02, 'Dùng cho món \'Trà vải\' trong đơn hàng #96', NULL, 30000.00, 'export', 3, '2025-03-26 14:46:08'),
(151, 6, -1.00, 'Dùng cho món \'Trà vải\' trong đơn hàng #96', NULL, 2000.00, 'export', 3, '2025-03-26 14:46:09'),
(152, 10, -0.10, 'Dùng cho món \'Trà vải\' trong đơn hàng #96', NULL, 50000.00, 'export', 3, '2025-03-26 14:46:09'),
(153, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #96', NULL, 30000.00, 'export', 3, '2025-03-26 14:46:09'),
(154, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #96', NULL, 200000.00, 'export', 3, '2025-03-26 14:46:09'),
(155, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #96', NULL, 10000.00, 'export', 3, '2025-03-26 14:46:09'),
(156, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #92', NULL, 10000.00, 'export', 3, '2025-03-26 14:46:17'),
(157, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #98', NULL, 30000.00, 'export', 3, '2025-03-26 14:48:19'),
(158, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #98', NULL, 200000.00, 'export', 3, '2025-03-26 14:48:19'),
(159, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #98', NULL, 10000.00, 'export', 3, '2025-03-26 14:48:19'),
(160, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #98', NULL, 10000.00, 'export', 3, '2025-03-26 14:48:19'),
(161, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #99', NULL, 200000.00, 'export', 3, '2025-03-26 14:49:27'),
(162, 2, -0.30, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #100', NULL, 40000.00, 'export', 3, '2025-03-26 14:53:00'),
(163, 13, -0.40, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #100', NULL, 30000.00, 'export', 3, '2025-03-26 14:53:00'),
(164, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #101', NULL, 10000.00, 'export', 3, '2025-03-26 14:53:12'),
(165, 4, -0.02, 'Dùng cho món \'Trà xanh\' trong đơn hàng #102', NULL, 200000.00, 'export', 3, '2025-03-26 14:59:35'),
(166, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #103', NULL, 40000.00, 'export', 3, '2025-03-26 14:59:46'),
(167, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #103', NULL, 200000.00, 'export', 3, '2025-03-26 14:59:46'),
(168, 16, -2.00, 'Dùng cho món \'7 Up\' trong đơn hàng #104', NULL, 10000.00, 'export', 3, '2025-03-26 16:59:16'),
(169, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #106', NULL, 40000.00, 'export', 3, '2025-03-26 16:59:29'),
(170, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #106', NULL, 30000.00, 'export', 3, '2025-03-26 16:59:29'),
(171, 3, -0.04, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #108', NULL, 30000.00, 'export', 3, '2025-03-26 17:41:04'),
(172, 6, -2.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #108', NULL, 2000.00, 'export', 3, '2025-03-26 17:41:04'),
(173, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #109', NULL, 40000.00, 'export', 3, '2025-03-26 17:41:07'),
(174, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #109', NULL, 200000.00, 'export', 3, '2025-03-26 17:41:07'),
(175, 2, -0.15, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #109', NULL, 40000.00, 'export', 3, '2025-03-26 17:41:07'),
(176, 7, -0.20, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #109', NULL, 30000.00, 'export', 3, '2025-03-26 17:41:08'),
(181, 16, -1.00, 'Dùng cho món \'7 Up\' trong đơn hàng #111', NULL, 10000.00, 'export', 3, '2025-03-26 18:48:16'),
(182, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #111', NULL, 10000.00, 'export', 3, '2025-03-26 18:48:16'),
(183, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #110', NULL, 10000.00, 'export', 3, '2025-03-26 18:48:31'),
(191, 17, 50.00, 'Nhập thêm', 11000.00, 10862.07, 'import', 1, '2025-04-02 18:15:33'),
(194, 8, -0.10, 'chênh lệch kho', NULL, 10000.00, 'adjustment', 1, '2025-04-03 18:43:48'),
(195, 17, 0.00, 'điều chỉnh giá', NULL, 10900.00, 'adjustment', 1, '2025-04-03 18:44:43'),
(196, 2, -0.20, 'Bị đổ', NULL, 40000.00, 'export', 3, '2025-04-03 19:13:24'),
(197, 17, -6.00, 'Dùng cho món \'C2\' trong đơn hàng #137', NULL, 10862.07, 'export', 3, '2025-04-05 11:57:54'),
(198, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #138', NULL, 300000.00, 'export', 3, '2025-04-05 12:14:00'),
(199, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #138', NULL, 10000.00, 'export', 3, '2025-04-05 12:14:00'),
(200, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #139', NULL, 300000.00, 'export', 3, '2025-04-05 12:19:59'),
(201, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #139', NULL, 40000.00, 'export', 3, '2025-04-05 12:19:59'),
(202, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #139', NULL, 200000.00, 'export', 3, '2025-04-05 12:19:59'),
(203, 1, -0.04, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #145', NULL, 300000.00, 'export', 3, '2025-04-10 11:38:32'),
(204, 3, -0.03, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #145', NULL, 30000.00, 'export', 3, '2025-04-10 11:38:32'),
(205, 4, -0.02, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #145', NULL, 200000.00, 'export', 3, '2025-04-10 11:38:32'),
(206, 11, -0.10, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #145', NULL, 30000.00, 'export', 3, '2025-04-10 11:38:32'),
(207, 7, -0.20, 'Bị úng', NULL, 30000.00, 'export', 3, '2025-04-10 14:10:55'),
(212, 17, -4.00, 'Bị hết hạn', NULL, 10862.07, 'export', 3, '2025-04-10 14:37:53'),
(213, 3, -0.04, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #147', NULL, 30000.00, 'export', 3, '2025-04-10 15:50:31'),
(214, 6, -2.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #147', NULL, 2000.00, 'export', 3, '2025-04-10 15:50:31'),
(215, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #147', NULL, 40000.00, 'export', 3, '2025-04-10 15:50:31'),
(216, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #147', NULL, 30000.00, 'export', 3, '2025-04-10 15:50:31'),
(217, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #147', NULL, 10000.00, 'export', 3, '2025-04-10 15:50:31'),
(218, 1, -0.04, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #148', NULL, 300000.00, 'export', 3, '2025-04-11 03:00:42'),
(219, 8, -0.04, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #148', NULL, 10000.00, 'export', 3, '2025-04-11 03:00:42'),
(220, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #149', NULL, 300000.00, 'export', 3, '2025-04-13 13:50:06'),
(221, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #149', NULL, 40000.00, 'export', 3, '2025-04-13 13:50:06'),
(222, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #149', NULL, 30000.00, 'export', 3, '2025-04-13 13:50:06'),
(223, 16, -1.00, 'Dùng cho món \'7 Up\' trong đơn hàng #152', NULL, 10000.00, 'export', 3, '2025-04-14 06:30:21'),
(224, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #152', NULL, 10862.07, 'export', 3, '2025-04-14 06:30:21'),
(225, 16, 0.00, 'Dùng cho món \'7 Up\' trong đơn hàng #152', NULL, 10000.00, 'export', 3, '2025-04-15 14:25:20'),
(226, 17, 0.00, 'Dùng cho món \'C2\' trong đơn hàng #152', NULL, 10862.07, 'export', 3, '2025-04-15 14:25:20'),
(227, 16, -1.00, 'Dùng cho món \'7 Up\' trong đơn hàng #152', NULL, 10000.00, 'export', 3, '2025-04-15 14:38:38'),
(228, 17, 0.00, 'Dùng cho món \'C2\' trong đơn hàng #152', NULL, 10862.07, 'export', 3, '2025-04-15 14:38:38'),
(229, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #153', NULL, 300000.00, 'export', 3, '2025-04-15 14:44:19'),
(230, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #153', NULL, 10000.00, 'export', 3, '2025-04-15 14:44:19'),
(231, 3, -0.06, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 30000.00, 'export', 3, '2025-04-15 14:44:19'),
(232, 4, -0.04, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 200000.00, 'export', 3, '2025-04-15 14:44:19'),
(233, 11, -0.20, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 30000.00, 'export', 3, '2025-04-15 14:44:19'),
(234, 1, 0.00, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #153', NULL, 300000.00, 'export', 3, '2025-04-15 14:45:25'),
(235, 8, 0.00, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #153', NULL, 10000.00, 'export', 3, '2025-04-15 14:45:25'),
(236, 3, 0.00, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 30000.00, 'export', 3, '2025-04-15 14:45:25'),
(237, 4, 0.00, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 200000.00, 'export', 3, '2025-04-15 14:45:25'),
(238, 11, 0.00, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #153', NULL, 30000.00, 'export', 3, '2025-04-15 14:45:25'),
(239, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #153', NULL, 30000.00, 'export', 3, '2025-04-15 14:45:25'),
(240, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #153', NULL, 200000.00, 'export', 3, '2025-04-15 14:45:25'),
(241, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #153', NULL, 10000.00, 'export', 3, '2025-04-15 14:45:25'),
(242, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #154', NULL, 200000.00, 'export', 3, '2025-04-15 15:00:41'),
(243, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #154', NULL, 40000.00, 'export', 3, '2025-04-15 15:00:41'),
(244, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #154', NULL, 30000.00, 'export', 3, '2025-04-15 15:00:41'),
(245, 4, -0.02, 'Dùng cho món \'Trà xanh\' trong đơn hàng #155', NULL, 200000.00, 'export', 3, '2025-04-15 15:29:07'),
(246, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #155', NULL, 40000.00, 'export', 3, '2025-04-15 15:29:07'),
(247, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #155', NULL, 200000.00, 'export', 3, '2025-04-15 15:29:07'),
(248, 4, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #156', NULL, 200000.00, 'export', 3, '2025-04-17 14:38:10'),
(249, 9, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #156', NULL, 50000.00, 'export', 3, '2025-04-17 14:38:10'),
(250, 2, -0.30, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #156', NULL, 40000.00, 'export', 3, '2025-04-17 14:38:10'),
(251, 7, -0.40, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #156', NULL, 30000.00, 'export', 3, '2025-04-17 14:38:10'),
(252, 18, 20.00, 'Thêm mới nguyên liệu', 2.00, 2.00, 'import', 1, '2025-04-17 14:56:48'),
(253, 19, 50.00, 'Thêm mới nguyên liệu', 6000.00, 6000.00, 'import', 1, '2025-04-17 15:00:17'),
(254, 20, 5.00, 'Thêm mới nguyên liệu', 130000.00, 130000.00, 'import', 1, '2025-04-17 15:02:53'),
(255, 21, 10.00, 'Thêm mới nguyên liệu', 300000.00, 300000.00, 'import', 1, '2025-04-17 15:04:45'),
(256, 22, 100.00, 'Thêm mới nguyên liệu', 10000.00, 10000.00, 'import', 1, '2025-04-17 15:06:38'),
(257, 23, 100.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-17 15:07:19'),
(258, 24, 100.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-17 15:08:24'),
(259, 25, 120.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-17 15:08:58'),
(260, 26, 70.00, 'Thêm mới nguyên liệu', 25000.00, 25000.00, 'import', 1, '2025-04-17 15:09:52'),
(261, 27, 60.00, 'Thêm mới nguyên liệu', 30000.00, 30000.00, 'import', 1, '2025-04-17 15:10:26'),
(262, 28, 50.00, 'Thêm mới nguyên liệu', 40000.00, 40000.00, 'import', 1, '2025-04-17 15:11:18'),
(263, 29, 50.00, 'Thêm mới nguyên liệu', 12000.00, 12000.00, 'import', 1, '2025-04-17 15:13:14'),
(264, 28, -1.00, 'Dùng cho món \'Khô bò\' trong đơn hàng #157', NULL, 40000.00, 'export', 3, '2025-04-17 15:30:30'),
(265, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #158', NULL, 30000.00, 'export', 3, '2025-04-18 11:24:22'),
(266, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #158', NULL, 2000.00, 'export', 3, '2025-04-18 11:24:22'),
(268, 3, -0.02, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #158', NULL, 30000.00, 'export', 3, '2025-04-18 14:21:59'),
(269, 6, -1.00, 'Dùng cho món \'Trà lipton nóng\' trong đơn hàng #158', NULL, 2000.00, 'export', 3, '2025-04-18 14:21:59'),
(270, 28, 1.00, 'Dùng cho món \'Khô bò\' trong đơn hàng #158', NULL, 40000.00, 'export', 3, '2025-04-18 14:21:59'),
(273, 28, -1.00, 'Dùng cho món \'Khô bò\' trong đơn hàng #158', NULL, 40000.00, 'export', 3, '2025-04-18 15:43:48'),
(274, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #161', NULL, 10862.07, 'export', 3, '2025-04-18 15:44:00'),
(275, 28, -1.00, 'Dùng cho món \'Khô bò\' trong đơn hàng #161', NULL, 40000.00, 'export', 3, '2025-04-18 15:44:00'),
(277, 28, -1.00, 'Dùng cho món \'Khô bò\' trong đơn hàng #161', NULL, 40000.00, 'export', 3, '2025-04-18 15:44:46'),
(278, 24, -1.00, 'Dùng cho món \'Sữa chua Vinamilk không đường\' trong đơn hàng #159', NULL, 7000.00, 'export', 3, '2025-04-18 16:27:57'),
(279, 29, -1.00, 'Dùng cho món \'Hạt hướng dương\' trong đơn hàng #159', NULL, 12000.00, 'export', 3, '2025-04-18 16:27:57'),
(282, 1, -0.04, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #163', NULL, 300000.00, 'export', 3, '2025-04-20 16:19:07'),
(283, 3, -0.03, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #163', NULL, 30000.00, 'export', 3, '2025-04-20 16:19:07'),
(284, 4, -0.02, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #163', NULL, 200000.00, 'export', 3, '2025-04-20 16:19:07'),
(285, 11, -0.10, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #163', NULL, 30000.00, 'export', 3, '2025-04-20 16:19:07'),
(286, 2, -0.15, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #165', NULL, 40000.00, 'export', 3, '2025-04-20 16:40:08'),
(287, 7, -0.20, 'Dùng cho món \'Sinh tố xoài\' trong đơn hàng #165', NULL, 30000.00, 'export', 3, '2025-04-20 16:40:08'),
(288, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #165', NULL, 40000.00, 'export', 3, '2025-04-20 16:40:08'),
(289, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #165', NULL, 30000.00, 'export', 3, '2025-04-20 16:40:08'),
(290, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #165', NULL, 10000.00, 'export', 3, '2025-04-20 16:40:08'),
(291, 23, -2.00, 'Dùng cho món \'Sữa chua Vinamilk có đường\' trong đơn hàng #166', NULL, 7000.00, 'export', 3, '2025-04-20 16:41:45'),
(292, 26, -1.00, 'Dùng cho món \'Khô gà\' trong đơn hàng #166', NULL, 25000.00, 'export', 3, '2025-04-20 16:41:45'),
(293, 1, -0.02, 'Dùng cho món \'Cà phê sữa\' trong đơn hàng #168', NULL, 300000.00, 'export', 3, '2025-04-22 07:48:34'),
(294, 2, -0.15, 'Dùng cho món \'Cà phê sữa\' trong đơn hàng #168', NULL, 40000.00, 'export', 3, '2025-04-22 07:48:34'),
(295, 29, -1.00, 'Dùng cho món \'Hạt hướng dương\' trong đơn hàng #168', NULL, 12000.00, 'export', 3, '2025-04-22 07:48:34'),
(296, 17, -1.00, 'Dùng cho món \'C2\' trong đơn hàng #169', NULL, 10862.07, 'export', 3, '2025-04-22 07:53:53'),
(297, 24, -1.00, 'Dùng cho món \'Sữa chua Vinamilk không đường\' trong đơn hàng #169', NULL, 7000.00, 'export', 3, '2025-04-22 07:53:53'),
(298, 24, -1.00, 'Dùng cho món \'Sữa chua Vinamilk không đường\' trong đơn hàng #169', NULL, 7000.00, 'export', 3, '2025-04-22 08:07:46'),
(299, 4, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #170', NULL, 200000.00, 'export', 3, '2025-04-22 08:15:40'),
(300, 9, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #170', NULL, 50000.00, 'export', 3, '2025-04-22 08:15:40'),
(301, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #170', NULL, 300000.00, 'export', 3, '2025-04-22 08:15:40'),
(302, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #170', NULL, 10000.00, 'export', 3, '2025-04-22 08:15:40'),
(303, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #171', NULL, 200000.00, 'export', 3, '2025-04-22 08:24:07'),
(304, 22, -1.00, 'Dùng cho món \'Sữa chua Vinamilk vị dâu\' trong đơn hàng #171', NULL, 7000.00, 'export', 3, '2025-04-22 08:24:07'),
(305, 26, -1.00, 'Dùng cho món \'Khô gà\' trong đơn hàng #171', NULL, 25000.00, 'export', 3, '2025-04-22 08:24:07'),
(306, 3, -0.02, 'Dùng cho món \'Trà vải\' trong đơn hàng #172', NULL, 30000.00, 'export', 3, '2025-04-22 08:29:39'),
(307, 6, -1.00, 'Dùng cho món \'Trà vải\' trong đơn hàng #172', NULL, 2000.00, 'export', 3, '2025-04-22 08:29:39'),
(308, 10, -0.10, 'Dùng cho món \'Trà vải\' trong đơn hàng #172', NULL, 50000.00, 'export', 3, '2025-04-22 08:29:39'),
(309, 4, -0.01, 'Dùng cho món \'Trà xanh\' trong đơn hàng #173', NULL, 200000.00, 'export', 3, '2025-04-22 08:34:40'),
(310, 24, -2.00, 'Dùng cho món \'Sữa chua Vinamilk không đường\' trong đơn hàng #173', NULL, 7000.00, 'export', 3, '2025-04-22 08:34:40'),
(311, 2, -0.30, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #174', NULL, 40000.00, 'export', 3, '2025-04-22 08:38:04'),
(312, 5, -0.20, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #174', NULL, 200000.00, 'export', 3, '2025-04-22 08:38:04'),
(313, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #174', NULL, 40000.00, 'export', 3, '2025-04-22 08:38:04'),
(314, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #174', NULL, 30000.00, 'export', 3, '2025-04-22 08:38:04'),
(315, 22, -1.00, 'Dùng cho món \'Sữa chua Vinamilk vị dâu\' trong đơn hàng #174', NULL, 7000.00, 'export', 3, '2025-04-22 08:38:04'),
(316, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #176', NULL, 40000.00, 'export', 3, '2025-04-22 13:44:48'),
(317, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #176', NULL, 30000.00, 'export', 3, '2025-04-22 13:44:48'),
(318, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #176', NULL, 10000.00, 'export', 3, '2025-04-22 13:44:48'),
(319, 30, 120.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-22 14:18:58'),
(320, 31, 120.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-22 14:19:22'),
(321, 32, 110.00, 'Thêm mới nguyên liệu', 7000.00, 7000.00, 'import', 1, '2025-04-22 14:20:04'),
(322, 33, 100.00, 'Thêm mới nguyên liệu', 12000.00, 12000.00, 'import', 1, '2025-04-22 14:50:41'),
(323, 34, 100.00, 'Thêm mới nguyên liệu', 12000.00, 12000.00, 'import', 1, '2025-04-22 14:51:08'),
(324, 35, 100.00, 'Thêm mới nguyên liệu', 12000.00, 12000.00, 'import', 1, '2025-04-22 14:51:44'),
(325, 36, 50.00, 'Thêm mới nguyên liệu', 15000.00, 15000.00, 'import', 1, '2025-04-22 15:06:48'),
(326, 37, 20.00, 'Thêm mới nguyên liệu', 100000.00, 100000.00, 'import', 1, '2025-04-22 15:08:09'),
(327, 38, 25.00, 'Thêm mới nguyên liệu', 120000.00, 120000.00, 'import', 1, '2025-04-22 15:10:29'),
(328, 39, 10.00, 'Thêm mới nguyên liệu', 25000.00, 25000.00, 'import', 1, '2025-04-22 15:13:58'),
(329, 40, 100.00, 'Thêm mới nguyên liệu', 60000.00, 60000.00, 'import', 1, '2025-04-22 15:14:34'),
(330, 5, -0.20, 'Bị hỏng', NULL, 200000.00, 'export', 3, '2025-04-22 16:17:15'),
(331, 4, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #177', NULL, 200000.00, 'export', 3, '2025-04-23 07:41:39'),
(332, 9, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #177', NULL, 50000.00, 'export', 3, '2025-04-23 07:41:39'),
(333, 4, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #177', NULL, 200000.00, 'export', 3, '2025-04-23 07:42:16'),
(334, 9, -0.02, 'Dùng cho món \'Trà đào\' trong đơn hàng #177', NULL, 50000.00, 'export', 3, '2025-04-23 07:42:16'),
(335, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #177', NULL, 30000.00, 'export', 3, '2025-04-23 07:42:16'),
(336, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #177', NULL, 200000.00, 'export', 3, '2025-04-23 07:42:16'),
(337, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #177', NULL, 10000.00, 'export', 3, '2025-04-23 07:42:16'),
(338, 2, -0.15, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #178', NULL, 40000.00, 'export', 3, '2025-04-23 07:45:21'),
(339, 13, -0.20, 'Dùng cho món \'Sinh tố bơ\' trong đơn hàng #178', NULL, 30000.00, 'export', 3, '2025-04-23 07:45:21'),
(340, 4, -0.02, 'Dùng cho món \'Trà xanh\' trong đơn hàng #179', NULL, 200000.00, 'export', 3, '2025-04-23 07:50:38'),
(341, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #179', NULL, 40000.00, 'export', 3, '2025-04-23 07:50:38'),
(342, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #179', NULL, 200000.00, 'export', 3, '2025-04-23 07:50:38'),
(343, 23, -1.00, 'Dùng cho món \'Sữa chua Vinamilk có đường\' trong đơn hàng #179', NULL, 7000.00, 'export', 3, '2025-04-23 07:51:37'),
(344, 3, -0.03, 'Dùng cho món \'Trà tắc\' trong đơn hàng #180', NULL, 30000.00, 'export', 3, '2025-04-23 08:32:44'),
(345, 4, -0.02, 'Dùng cho món \'Trà tắc\' trong đơn hàng #180', NULL, 200000.00, 'export', 3, '2025-04-23 08:32:44'),
(346, 12, -0.05, 'Dùng cho món \'Trà tắc\' trong đơn hàng #180', NULL, 10000.00, 'export', 3, '2025-04-23 08:32:44'),
(347, 3, -0.03, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #181', NULL, 30000.00, 'export', 3, '2025-04-23 08:54:41'),
(348, 4, -0.02, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #181', NULL, 200000.00, 'export', 3, '2025-04-23 08:54:41'),
(349, 11, -0.10, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #181', NULL, 30000.00, 'export', 3, '2025-04-23 08:54:41'),
(350, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #182', NULL, 300000.00, 'export', 3, '2025-04-23 08:55:33'),
(351, 1, -0.02, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #183', NULL, 300000.00, 'export', 3, '2025-04-23 12:01:49'),
(352, 25, -1.00, 'Dùng cho món \'Sữa chua Vinamilk nha đam\' trong đơn hàng #183', NULL, 7000.00, 'export', 3, '2025-04-23 12:02:39'),
(353, 1, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #184', NULL, 300000.00, 'export', 3, '2025-04-23 12:10:32'),
(354, 8, -0.02, 'Dùng cho món \'Cà phê muối\' trong đơn hàng #184', NULL, 10000.00, 'export', 3, '2025-04-23 12:10:32'),
(355, 3, -0.03, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #185', NULL, 30000.00, 'export', 3, '2025-04-23 14:41:35'),
(356, 4, -0.02, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #185', NULL, 200000.00, 'export', 3, '2025-04-23 14:41:35'),
(357, 11, -0.10, 'Dùng cho món \'Trà chanh dây\' trong đơn hàng #185', NULL, 30000.00, 'export', 3, '2025-04-23 14:41:35'),
(358, 1, -0.04, 'Dùng cho món \'Cà phê đen\' trong đơn hàng #187', NULL, 300000.00, 'export', 3, '2025-04-24 00:40:01'),
(359, 2, -0.15, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #188', NULL, 40000.00, 'export', 3, '2025-04-24 00:41:59'),
(360, 5, -0.10, 'Dùng cho món \'Sinh tố dâu\' trong đơn hàng #188', NULL, 200000.00, 'export', 3, '2025-04-24 00:41:59'),
(361, 14, -1.00, 'Dùng cho món \'Sting\' trong đơn hàng #189', NULL, 10000.00, 'export', 3, '2025-04-24 00:48:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu_ingredients`
--

CREATE TABLE `menu_ingredients` (
  `item_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity_per_unit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menu_ingredients`
--

INSERT INTO `menu_ingredients` (`item_id`, `ingredient_id`, `quantity_per_unit`) VALUES
(1, 1, 0.02),
(2, 1, 0.02),
(2, 2, 0.15),
(3, 4, 0.01),
(4, 4, 0.02),
(4, 9, 0.02),
(5, 2, 0.15),
(5, 5, 0.10),
(6, 2, 0.15),
(6, 7, 0.20),
(11, 3, 0.01),
(11, 6, 1.00),
(15, 3, 0.02),
(15, 6, 1.00),
(16, 1, 0.02),
(16, 8, 0.02),
(17, 3, 0.02),
(17, 6, 1.00),
(17, 10, 0.10),
(18, 3, 0.03),
(18, 4, 0.02),
(18, 11, 0.10),
(19, 3, 0.03),
(19, 4, 0.02),
(19, 12, 0.05),
(20, 2, 0.15),
(20, 13, 0.20),
(21, 14, 1.00),
(22, 15, 1.00),
(23, 16, 1.00),
(24, 17, 1.00),
(30, 22, 1.00),
(31, 23, 1.00),
(32, 24, 1.00),
(33, 25, 1.00),
(34, 26, 1.00),
(35, 27, 1.00),
(36, 28, 1.00),
(37, 29, 1.00),
(42, 32, 1.00),
(43, 30, 1.00),
(45, 31, 1.00),
(46, 33, 1.00),
(47, 34, 1.00),
(48, 35, 1.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `reason` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `name`, `image_url`, `description`, `price`, `is_available`, `reason`, `deleted_at`, `category_id`) VALUES
(1, 'Cà phê đen', 'capheden.jpg', 'Cà phê nguyên chất pha phin.', 20000.00, 1, NULL, NULL, 1),
(2, 'Cà phê sữa', 'caphesua.jpg', 'Cà phê pha phin với sữa đặc.', 25000.00, 0, 'Hết hàng', NULL, 1),
(3, 'Trà xanh', 'traxanh.jpg', 'Trà xanh nguyên chất, thanh mát.', 15000.00, 1, NULL, NULL, 2),
(4, 'Trà đào', 'tradao.jpg', 'Trà đào ngọt dịu với miếng đào tươi.', 30000.00, 1, NULL, NULL, 2),
(5, 'Sinh tố dâu', 'sinhtodau.jpg', 'Sinh tố dâu tươi, mát lạnh.', 35000.00, 1, NULL, NULL, 3),
(6, 'Sinh tố xoài', 'sinhtoxoai.jpg', 'Sinh tố xoài chín ngọt ngào.', 32000.00, 1, NULL, NULL, 3),
(11, 'Trà lipton đá', 'tralipton.png', 'Trà lipton mát lạnh', 15000.00, 0, 'Ngừng phục vụ', NULL, 2),
(15, 'Trà lipton nóng', 'traliptonnong.jpg', 'Trà lipton nóng', 15000.00, 0, 'Hết hàng', NULL, 2),
(16, 'Cà phê muối', 'caphemuoi.jpg', 'Cà phê muối thơm ngon', 25000.00, 1, NULL, NULL, 1),
(17, 'Trà vải', 'travai.jpg', 'Trà mải thơm mát', 20000.00, 1, NULL, NULL, 2),
(18, 'Trà chanh dây', 'trachanhday.jpg', 'Trà chanh dây', 20000.00, 1, NULL, NULL, 2),
(19, 'Trà tắc', 'tratac.jpg', NULL, 15000.00, 1, NULL, NULL, 2),
(20, 'Sinh tố bơ', 'sinhtobo.jpg', NULL, 30000.00, 1, NULL, NULL, 3),
(21, 'Sting', 'sting.jpg', NULL, 15000.00, 1, NULL, NULL, 4),
(22, 'Pepsi', 'pepsi.jpg', NULL, 15000.00, 0, 'Ngừng phục vụ', NULL, 4),
(23, '7 Up', '7up.jpg', NULL, 15000.00, 1, NULL, NULL, 4),
(24, 'C2', 'c2.jpg', NULL, 15000.00, 1, NULL, NULL, 4),
(26, 'Bánh flan', 'banhFlan.png', 'Bánh flan mềm mịn', 10000.00, 1, NULL, NULL, 6),
(27, 'Bánh Tiramisu', 'banhTiramisu.png', NULL, 35000.00, 1, NULL, NULL, 6),
(28, 'Bánh su kem', 'banhSuKem.png', NULL, 29000.00, 1, NULL, NULL, 6),
(29, 'Bánh Croissant', 'banhCroissant.png', NULL, 29000.00, 1, NULL, NULL, 6),
(30, 'Sữa chua Vinamilk vị dâu', 'suaChuaVinamilkDauTay.png', NULL, 10000.00, 1, NULL, NULL, 7),
(31, 'Sữa chua Vinamilk có đường', 'suaChuaVinamilkCoDuong.png', NULL, 10000.00, 1, NULL, NULL, 7),
(32, 'Sữa chua Vinamilk không đường', 'suaChuaVinamilkKhongDuong.png', NULL, 10000.00, 1, NULL, NULL, 7),
(33, 'Sữa chua Vinamilk nha đam', 'suaChuaVinamilkNhaDam.png', NULL, 10000.00, 1, NULL, NULL, 7),
(34, 'Khô gà', 'khoGa.jpg', NULL, 30000.00, 1, NULL, NULL, 8),
(35, 'Khô heo', 'khoHeo.jpg', NULL, 35000.00, 1, NULL, NULL, 8),
(36, 'Khô bò', 'khoBo.jpg', NULL, 45000.00, 0, 'Hết hàng', NULL, 8),
(37, 'Hạt hướng dương', 'hatHuongDuong.jpg', NULL, 15000.00, 1, NULL, NULL, 8),
(38, 'Bánh chuối', 'banhChuoi.png', NULL, 25000.00, 1, NULL, NULL, 6),
(39, 'Bánh sữa chua phô mai', 'banhSuaChuaPhoMai.png', NULL, 35000.00, 1, NULL, NULL, 6),
(40, 'Bánh phô mai trà xanh', 'banhPhoMaiTraXanh.png', NULL, 30000.00, 1, NULL, NULL, 6),
(41, 'Bánh phô mai chanh dây', 'banhPhoMaiChanhDay.png', NULL, 30000.00, 1, NULL, NULL, 6),
(42, 'Sữa chua Vinamilk việt quốc', 'suaChuaVinamilkVietQuoc.png', NULL, 10000.00, 1, NULL, NULL, 7),
(43, 'Sữa chua Vinamilk trái cây', 'suaChuaVinamilkTraiCay.png', NULL, 10000.00, 1, NULL, NULL, 7),
(44, 'Trà đá', 'kemOcQueDau.png', NULL, 15000.00, 1, NULL, '2025-04-22 21:45:29', 1),
(45, 'Sữa chua Vinamilk nếp cẩm', 'suaChuaVinamilkNepCam.png', NULL, 10000.00, 1, NULL, NULL, 7),
(46, 'Bánh tráng phô mai', 'banhTrangPhoMai.png', NULL, 15000.00, 1, NULL, NULL, 8),
(47, 'Bánh tráng sate hành', 'banhTrangSateHanh.png', NULL, 15000.00, 1, NULL, NULL, 8),
(48, 'Bánh tráng sate tỏi', 'banhTrangSateToi.png', NULL, 15000.00, 1, NULL, NULL, 8),
(49, 'Cá viên chiên', 'caVienChien.jpg', NULL, 20000.00, 1, NULL, NULL, 8),
(50, 'Xúc xích chiên', 'xucXichChien.jpg', NULL, 20000.00, 1, NULL, NULL, 8),
(51, 'Tôm viên chiên', 'tomVienChien.jpg', NULL, 20000.00, 1, NULL, NULL, 8),
(52, 'Khoai tây chiên', 'khoaiTayChien.jpg', NULL, 15000.00, 1, NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_type` enum('dine_in','takeaway') NOT NULL,
  `status` enum('confirmed','received','pending_payment','paid','cancelled') NOT NULL DEFAULT 'confirmed',
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `table_id`, `customer_id`, `order_type`, `status`, `total_price`, `created_at`, `deleted_at`) VALUES
(29, NULL, 11, 'takeaway', 'paid', 35000.00, '2025-03-19 23:00:14', NULL),
(30, NULL, 9, 'takeaway', 'paid', 75000.00, '2025-03-19 23:01:19', NULL),
(31, NULL, 12, 'takeaway', 'paid', 112000.00, '2025-03-19 23:05:31', NULL),
(52, 4, 9, 'dine_in', 'paid', 30000.00, '2025-03-25 13:43:54', NULL),
(53, 10, 9, 'dine_in', 'paid', 65000.00, '2025-03-25 16:19:40', NULL),
(54, 2, 1, 'dine_in', 'paid', 15000.00, '2025-03-25 16:23:43', NULL),
(61, 1, 4, 'dine_in', 'paid', 25000.00, '2025-03-25 22:13:51', NULL),
(62, 1, 3, 'dine_in', 'paid', 15000.00, '2025-03-25 22:42:08', NULL),
(63, 7, 9, 'dine_in', 'paid', 15000.00, '2025-03-25 22:10:03', NULL),
(64, 4, 8, 'dine_in', 'paid', 20000.00, '2025-03-25 22:13:44', NULL),
(65, 2, 4, 'dine_in', 'paid', 35000.00, '2025-03-25 22:32:42', NULL),
(66, 9, 2, 'dine_in', 'paid', 15000.00, '2025-03-25 22:37:28', NULL),
(70, 8, 10, 'dine_in', 'paid', 15000.00, '2025-03-25 22:48:39', NULL),
(79, 2, 3, 'dine_in', 'paid', 32000.00, '2025-03-25 22:45:24', NULL),
(80, 7, 4, 'dine_in', 'paid', 30000.00, '2025-03-25 22:53:35', NULL),
(81, 9, 3, 'dine_in', 'paid', 15000.00, '2025-03-25 22:01:46', NULL),
(82, 8, 4, 'dine_in', 'paid', 20000.00, '2025-03-26 08:29:22', NULL),
(83, 9, 12, 'dine_in', 'paid', 62000.00, '2025-03-26 08:39:55', NULL),
(84, 10, 3, 'dine_in', 'paid', 45000.00, '2025-03-26 08:50:54', NULL),
(85, 7, 4, 'dine_in', 'paid', 45000.00, '2025-03-26 08:57:27', NULL),
(86, 4, 4, 'dine_in', 'paid', 90000.00, '2025-03-26 09:11:18', NULL),
(87, 3, 3, 'dine_in', 'paid', 60000.00, '2025-03-26 09:24:20', NULL),
(88, 2, 10, 'dine_in', 'paid', 45000.00, '2025-03-26 09:32:37', NULL),
(89, NULL, 10, 'takeaway', 'paid', 120000.00, '2025-03-26 11:49:26', NULL),
(90, NULL, 10, 'takeaway', 'paid', 85000.00, '2025-03-26 11:56:41', NULL),
(91, NULL, 8, 'takeaway', 'cancelled', 20000.00, '2025-03-26 11:58:37', NULL),
(92, NULL, 4, 'takeaway', 'paid', 15000.00, '2025-03-26 12:08:35', NULL),
(93, NULL, 3, 'takeaway', 'paid', 45000.00, '2025-03-26 12:21:51', NULL),
(94, NULL, 3, 'takeaway', 'paid', 45000.00, '2025-03-26 12:23:57', NULL),
(95, NULL, 3, 'takeaway', 'paid', 40000.00, '2025-03-26 12:28:24', NULL),
(96, NULL, 3, 'takeaway', 'paid', 35000.00, '2025-03-26 13:05:08', NULL),
(97, 4, 9, 'dine_in', 'cancelled', 50000.00, '2025-03-26 14:46:43', NULL),
(98, 4, 4, 'dine_in', 'paid', 30000.00, '2025-03-26 14:47:53', NULL),
(99, 7, 12, 'dine_in', 'paid', 15000.00, '2025-03-26 14:49:20', NULL),
(100, 10, 9, 'dine_in', 'paid', 60000.00, '2025-03-26 14:52:30', NULL),
(101, 3, 11, 'dine_in', 'paid', 15000.00, '2025-03-26 14:52:50', NULL),
(102, 9, 9, 'dine_in', 'paid', 30000.00, '2025-03-26 14:59:02', NULL),
(103, 8, 1, 'dine_in', 'paid', 35000.00, '2025-03-26 14:59:26', NULL),
(104, 4, 4, 'dine_in', 'paid', 30000.00, '2025-03-26 15:08:22', NULL),
(105, 1, 4, 'dine_in', 'cancelled', 30000.00, '2025-03-26 15:20:08', NULL),
(106, 1, 4, 'dine_in', 'paid', 30000.00, '2025-03-26 16:27:31', NULL),
(107, 4, 3, 'dine_in', 'cancelled', 64000.00, '2025-03-26 17:33:59', NULL),
(108, 2, 4, 'dine_in', 'paid', 30000.00, '2025-03-26 17:38:21', NULL),
(109, 9, 12, 'dine_in', 'paid', 67000.00, '2025-03-26 17:40:25', NULL),
(110, 4, 3, 'dine_in', 'paid', 15000.00, '2025-03-26 17:59:08', NULL),
(111, NULL, 4, 'takeaway', 'paid', 30000.00, '2025-03-26 18:44:48', NULL),
(134, NULL, 4, 'takeaway', 'cancelled', 120000.00, '2025-04-04 18:28:46', NULL),
(136, NULL, 3, 'takeaway', 'cancelled', 15000.00, '2025-04-04 18:58:28', NULL),
(137, 1, 3, 'dine_in', 'paid', 90000.00, '2025-04-05 11:31:32', NULL),
(138, 4, 4, 'dine_in', 'paid', 25000.00, '2025-04-05 12:13:30', NULL),
(139, 10, 4, 'dine_in', 'paid', 55000.00, '2025-04-05 12:16:53', NULL),
(145, 3, 14, 'dine_in', 'paid', 60000.00, '2025-04-10 11:36:08', NULL),
(146, 10, 2, 'dine_in', 'cancelled', 30000.00, '2025-04-10 14:03:19', NULL),
(147, NULL, 4, 'takeaway', 'paid', 75000.00, '2025-04-10 15:48:59', NULL),
(148, 1, 3, 'dine_in', 'paid', 50000.00, '2025-04-11 03:00:21', NULL),
(149, 4, 4, 'dine_in', 'paid', 50000.00, '2025-04-13 13:43:09', NULL),
(152, 1, 3, 'dine_in', 'paid', 75000.00, '2025-04-15 04:31:24', NULL),
(153, 4, 4, 'dine_in', 'paid', 80000.00, '2025-04-15 06:31:23', NULL),
(154, 10, 3, 'dine_in', 'paid', 45000.00, '2025-04-15 15:00:24', NULL),
(155, 11, 4, 'dine_in', 'paid', 65000.00, '2025-04-15 15:07:30', NULL),
(156, NULL, 8, 'takeaway', 'paid', 94000.00, '2025-04-17 14:37:20', NULL),
(157, NULL, 4, 'takeaway', 'paid', 55000.00, '2025-04-17 15:29:55', NULL),
(158, NULL, 9, 'takeaway', 'paid', 149000.00, '2025-04-18 08:56:38', NULL),
(159, 8, 10, 'dine_in', 'paid', 54000.00, '2025-04-18 11:10:29', NULL),
(160, NULL, 3, 'takeaway', 'paid', 100000.00, '2025-04-18 12:55:58', NULL),
(161, NULL, 8, 'takeaway', 'paid', 105000.00, '2025-04-18 15:06:47', NULL),
(163, 3, 14, 'dine_in', 'paid', 60000.00, '2025-04-20 16:16:11', NULL),
(164, 8, 2, 'dine_in', 'cancelled', 32000.00, '2025-04-20 16:37:02', NULL),
(165, NULL, 4, 'takeaway', 'paid', 77000.00, '2025-04-20 16:40:00', NULL),
(166, 10, 9, 'dine_in', 'paid', 50000.00, '2025-04-20 16:41:29', NULL),
(168, 1, 4, 'dine_in', 'paid', 60000.00, '2025-04-22 07:48:09', NULL),
(169, NULL, 14, 'takeaway', 'paid', 105000.00, '2025-04-22 07:53:42', NULL),
(170, 8, 2, 'dine_in', 'paid', 55000.00, '2025-04-22 08:14:22', NULL),
(171, NULL, 13, 'takeaway', 'paid', 55000.00, '2025-04-22 08:23:12', NULL),
(172, 10, 9, 'dine_in', 'paid', 30000.00, '2025-04-22 08:29:33', NULL),
(173, NULL, 9, 'takeaway', 'paid', 35000.00, '2025-04-22 08:33:36', NULL),
(174, NULL, 3, 'takeaway', 'paid', 139000.00, '2025-04-22 08:37:55', NULL),
(175, NULL, 3, 'takeaway', 'cancelled', 20000.00, '2025-04-22 08:46:40', NULL),
(176, NULL, 4, 'takeaway', 'paid', 45000.00, '2025-04-22 13:43:53', NULL),
(177, 4, 15, 'dine_in', 'paid', 115000.00, '2025-04-23 07:28:30', NULL),
(178, 9, 4, 'dine_in', 'paid', 30000.00, '2025-04-23 07:45:13', NULL),
(179, NULL, 8, 'takeaway', 'paid', 75000.00, '2025-04-23 07:49:48', NULL),
(180, 4, 15, 'dine_in', 'paid', 50000.00, '2025-04-23 08:32:29', NULL),
(181, 7, 11, 'dine_in', 'paid', 20000.00, '2025-04-23 08:34:29', NULL),
(182, 2, 14, 'dine_in', 'paid', 49000.00, '2025-04-23 08:55:21', NULL),
(183, 9, 15, 'dine_in', 'paid', 95000.00, '2025-04-23 12:00:13', NULL),
(184, NULL, 8, 'takeaway', 'paid', 75000.00, '2025-04-23 12:10:05', NULL),
(185, 10, 10, 'dine_in', 'paid', 69000.00, '2025-04-23 14:41:05', NULL),
(186, 7, 12, 'dine_in', 'cancelled', 45000.00, '2025-04-23 14:42:59', NULL),
(187, 10, 4, 'dine_in', 'confirmed', 99000.00, '2025-04-24 00:38:51', NULL),
(188, 1, 3, 'dine_in', 'received', 35000.00, '2025-04-24 00:41:19', NULL),
(189, NULL, 3, 'takeaway', 'paid', 15000.00, '2025-04-24 00:48:25', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `status` enum('order','completed','issue') NOT NULL DEFAULT 'order',
  `completed_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`order_id`, `item_id`, `quantity`, `note`, `status`, `completed_quantity`) VALUES
(29, 1, 1, NULL, 'completed', 0),
(29, 24, 1, NULL, 'completed', 0),
(30, 2, 2, NULL, 'completed', 0),
(30, 16, 1, NULL, 'completed', 0),
(31, 6, 1, NULL, 'completed', 0),
(31, 17, 1, NULL, 'completed', 0),
(31, 19, 1, NULL, 'completed', 0),
(31, 20, 1, NULL, 'completed', 0),
(31, 24, 1, NULL, 'completed', 0),
(52, 3, 1, NULL, 'completed', 0),
(52, 21, 1, NULL, 'completed', 0),
(53, 3, 1, NULL, 'completed', 0),
(53, 16, 2, NULL, 'completed', 0),
(54, 15, 1, NULL, 'completed', 0),
(61, 16, 1, NULL, 'completed', 0),
(62, 19, 1, NULL, 'completed', 0),
(63, 15, 1, NULL, 'completed', 0),
(64, 18, 1, NULL, 'completed', 0),
(65, 5, 1, NULL, 'completed', 0),
(66, 15, 1, NULL, 'completed', 0),
(70, 22, 1, NULL, 'completed', 0),
(79, 6, 1, NULL, 'completed', 0),
(80, 20, 1, NULL, 'completed', 0),
(81, 22, 1, NULL, 'completed', 0),
(82, 17, 1, NULL, 'completed', 0),
(83, 6, 1, NULL, 'completed', 0),
(83, 22, 1, NULL, 'completed', 0),
(83, 23, 1, NULL, 'completed', 0),
(84, 3, 3, NULL, 'completed', 0),
(85, 15, 3, NULL, 'completed', 0),
(86, 20, 3, NULL, 'completed', 0),
(87, 3, 4, NULL, 'completed', 0),
(88, 21, 2, NULL, 'completed', 0),
(88, 23, 1, NULL, 'completed', 0),
(89, 5, 3, 'ít sữa', 'completed', 0),
(89, 21, 1, '', 'completed', 0),
(90, 5, 2, '', 'completed', 0),
(90, 24, 1, '', 'completed', 0),
(91, 17, 1, NULL, 'completed', 0),
(92, 24, 1, NULL, 'completed', 0),
(93, 15, 1, NULL, 'completed', 0),
(93, 20, 1, NULL, 'completed', 0),
(94, 1, 1, NULL, 'completed', 0),
(94, 16, 1, NULL, 'completed', 0),
(95, 18, 2, NULL, 'completed', 0),
(96, 17, 1, NULL, 'completed', 0),
(96, 19, 1, NULL, 'completed', 0),
(97, 5, 1, NULL, 'completed', 0),
(97, 22, 1, NULL, 'completed', 0),
(98, 19, 1, NULL, 'completed', 0),
(98, 24, 1, NULL, 'completed', 0),
(99, 3, 1, NULL, 'completed', 0),
(100, 20, 2, NULL, 'completed', 0),
(101, 21, 1, NULL, 'completed', 0),
(102, 3, 2, NULL, 'completed', 0),
(103, 5, 1, NULL, 'completed', 0),
(104, 23, 2, NULL, 'completed', 0),
(105, 15, 1, NULL, 'completed', 0),
(105, 19, 1, NULL, 'completed', 0),
(106, 20, 1, NULL, 'completed', 0),
(107, 6, 2, NULL, 'completed', 0),
(108, 15, 2, NULL, 'completed', 0),
(109, 5, 1, NULL, 'completed', 0),
(109, 6, 1, NULL, 'completed', 0),
(110, 21, 1, NULL, 'completed', 0),
(111, 23, 1, NULL, 'completed', 0),
(111, 24, 1, NULL, 'completed', 0),
(134, 24, 8, '', 'completed', 0),
(136, 24, 1, '', 'completed', 0),
(137, 24, 6, NULL, 'completed', 0),
(138, 16, 1, NULL, 'completed', 0),
(139, 1, 1, NULL, 'completed', 0),
(139, 5, 1, NULL, 'completed', 0),
(145, 1, 2, 'không đường', 'completed', 0),
(145, 18, 1, NULL, 'completed', 0),
(146, 3, 1, NULL, 'completed', 0),
(146, 22, 1, NULL, 'completed', 0),
(147, 15, 2, '', 'completed', 0),
(147, 20, 1, 'Ít đá', 'completed', 0),
(147, 21, 1, '', 'completed', 0),
(148, 16, 2, NULL, 'completed', 0),
(149, 1, 1, NULL, 'completed', 0),
(149, 20, 1, NULL, 'completed', 0),
(152, 23, 4, NULL, 'completed', 4),
(152, 24, 1, NULL, 'completed', 1),
(153, 16, 1, NULL, 'completed', 1),
(153, 18, 2, NULL, 'completed', 2),
(153, 19, 1, NULL, 'completed', 1),
(154, 3, 1, NULL, 'completed', 1),
(154, 20, 1, NULL, 'completed', 1),
(155, 3, 2, NULL, 'completed', 2),
(155, 5, 1, NULL, 'completed', 1),
(156, 4, 1, 'ít đá', 'completed', 1),
(156, 6, 2, NULL, 'completed', 2),
(157, 26, 1, NULL, 'completed', 1),
(157, 36, 1, NULL, 'completed', 1),
(158, 15, 2, NULL, 'completed', 2),
(158, 28, 1, NULL, 'completed', 1),
(158, 36, 2, NULL, 'completed', 2),
(159, 29, 1, NULL, 'completed', 1),
(159, 32, 1, NULL, 'completed', 1),
(159, 37, 1, NULL, 'completed', 1),
(160, 26, 3, NULL, 'completed', 3),
(160, 27, 2, NULL, 'completed', 2),
(161, 24, 1, NULL, 'completed', 1),
(161, 36, 2, NULL, 'completed', 2),
(163, 1, 2, NULL, 'completed', 2),
(163, 18, 1, NULL, 'completed', 1),
(164, 6, 1, NULL, 'order', 0),
(165, 6, 1, NULL, 'completed', 1),
(165, 20, 1, NULL, 'completed', 1),
(165, 21, 1, NULL, 'completed', 1),
(166, 31, 2, NULL, 'completed', 2),
(166, 34, 1, NULL, 'completed', 1),
(168, 2, 1, NULL, 'completed', 1),
(168, 26, 2, NULL, 'completed', 2),
(168, 37, 1, NULL, 'completed', 1),
(169, 24, 1, NULL, 'completed', 1),
(169, 27, 2, NULL, 'completed', 2),
(169, 32, 2, NULL, 'completed', 2),
(170, 4, 1, NULL, 'completed', 1),
(170, 16, 1, NULL, 'completed', 1),
(171, 3, 1, NULL, 'completed', 1),
(171, 30, 1, NULL, 'completed', 1),
(171, 34, 1, NULL, 'completed', 1),
(172, 17, 1, NULL, 'completed', 1),
(172, 26, 1, NULL, 'completed', 1),
(173, 3, 1, NULL, 'completed', 1),
(173, 32, 2, NULL, 'completed', 2),
(174, 5, 2, NULL, 'completed', 2),
(174, 20, 1, NULL, 'completed', 1),
(174, 29, 1, NULL, 'completed', 1),
(174, 30, 1, NULL, 'completed', 1),
(175, 18, 1, NULL, 'order', 0),
(176, 20, 1, NULL, 'completed', 1),
(176, 21, 1, NULL, 'completed', 1),
(177, 4, 2, NULL, 'completed', 2),
(177, 19, 1, NULL, 'completed', 1),
(177, 49, 2, NULL, 'completed', 2),
(178, 20, 1, NULL, 'completed', 1),
(179, 3, 2, 'ít đá', 'completed', 2),
(179, 5, 1, NULL, 'completed', 1),
(179, 31, 1, NULL, 'completed', 1),
(180, 19, 1, NULL, 'completed', 1),
(180, 50, 1, NULL, 'completed', 1),
(180, 52, 1, NULL, 'completed', 1),
(181, 18, 1, NULL, 'completed', 1),
(182, 1, 1, NULL, 'completed', 1),
(182, 28, 1, NULL, 'completed', 1),
(183, 1, 1, 'không đường', 'completed', 1),
(183, 27, 1, NULL, 'completed', 1),
(183, 33, 1, NULL, 'completed', 1),
(183, 40, 1, NULL, 'completed', 1),
(184, 16, 1, NULL, 'completed', 1),
(184, 38, 2, NULL, 'completed', 2),
(185, 18, 1, NULL, 'completed', 1),
(185, 28, 1, NULL, 'completed', 1),
(185, 50, 1, NULL, 'completed', 1),
(186, 20, 1, NULL, 'order', 0),
(186, 46, 1, NULL, 'order', 0),
(187, 1, 2, 'không đường', 'completed', 2),
(187, 4, 1, NULL, 'order', 0),
(187, 29, 1, NULL, 'completed', 1),
(188, 5, 1, NULL, 'completed', 1),
(189, 21, 1, NULL, 'completed', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `final_price` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer') NOT NULL,
  `amount_received` decimal(10,2) NOT NULL,
  `payment_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `employee_id`, `promotion_id`, `discount_amount`, `final_price`, `payment_method`, `amount_received`, `payment_time`) VALUES
(27, 31, 6, 7, 11200.00, 100800.00, 'cash', 120000.00, '2025-03-20 04:54:18'),
(28, 29, 6, NULL, 0.00, 35000.00, 'bank_transfer', 35000.00, '2025-03-20 04:54:56'),
(29, 30, 6, NULL, 0.00, 75000.00, 'cash', 100000.00, '2025-03-20 04:55:36'),
(32, 52, 4, 8, 5000.00, 25000.00, 'cash', 30000.00, '2025-03-25 15:57:44'),
(33, 53, 4, 8, 5000.00, 60000.00, 'cash', 60000.00, '2025-03-25 16:20:39'),
(34, 54, 4, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-25 16:35:12'),
(35, 61, 4, 8, 5000.00, 20000.00, 'bank_transfer', 20000.00, '2025-03-25 22:16:13'),
(36, 66, 4, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-25 22:49:23'),
(37, 63, 4, 8, 5000.00, 10000.00, 'bank_transfer', 10000.00, '2025-03-25 22:49:36'),
(38, 64, 4, 8, 5000.00, 15000.00, 'bank_transfer', 15000.00, '2025-03-25 22:49:55'),
(39, 62, 4, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-25 22:50:07'),
(40, 65, 4, 8, 5000.00, 30000.00, 'bank_transfer', 30000.00, '2025-03-25 22:50:20'),
(41, 70, 4, 8, 5000.00, 10000.00, 'bank_transfer', 10000.00, '2025-03-25 22:45:17'),
(42, 79, 4, 8, 5000.00, 27000.00, 'cash', 30000.00, '2025-03-25 22:53:21'),
(43, 81, 5, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-26 08:29:02'),
(44, 80, 5, 8, 5000.00, 25000.00, 'cash', 30000.00, '2025-03-26 08:29:13'),
(45, 88, 4, 8, 5000.00, 40000.00, 'bank_transfer', 40000.00, '2025-03-26 14:31:06'),
(46, 82, 4, 8, 5000.00, 15000.00, 'cash', 20000.00, '2025-03-26 14:32:52'),
(47, 86, 4, 8, 5000.00, 85000.00, 'cash', 90000.00, '2025-03-26 14:33:11'),
(48, 83, 4, 8, 5000.00, 57000.00, 'cash', 60000.00, '2025-03-26 14:33:29'),
(49, 84, 4, 8, 5000.00, 40000.00, 'bank_transfer', 40000.00, '2025-03-26 14:34:23'),
(50, 85, 4, 8, 5000.00, 40000.00, 'bank_transfer', 40000.00, '2025-03-26 14:34:45'),
(51, 87, 4, 8, 5000.00, 55000.00, 'cash', 100000.00, '2025-03-26 14:35:08'),
(52, 98, 4, 8, 5000.00, 25000.00, 'cash', 25000.00, '2025-03-26 15:01:01'),
(53, 101, 4, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-26 15:01:33'),
(54, 100, 4, 8, 5000.00, 55000.00, 'bank_transfer', 60000.00, '2025-03-26 15:01:57'),
(55, 99, 4, 8, 5000.00, 10000.00, 'bank_transfer', 10000.00, '2025-03-26 15:02:12'),
(56, 102, 4, 8, 5000.00, 25000.00, 'cash', 25000.00, '2025-03-26 15:02:28'),
(57, 103, 4, 8, 5000.00, 30000.00, 'cash', 30000.00, '2025-03-26 15:02:37'),
(58, 89, 6, 8, 5000.00, 115000.00, 'cash', 120000.00, '2025-03-26 16:29:36'),
(59, 94, 6, 8, 5000.00, 40000.00, 'cash', 50000.00, '2025-03-26 16:36:36'),
(60, 95, 6, 8, 5000.00, 35000.00, 'cash', 40000.00, '2025-03-26 16:39:36'),
(61, 93, 6, 8, 5000.00, 40000.00, 'cash', 50000.00, '2025-03-26 16:40:07'),
(62, 90, 6, 8, 5000.00, 80000.00, 'cash', 100000.00, '2025-03-26 16:41:12'),
(63, 96, 6, 8, 5000.00, 30000.00, 'bank_transfer', 30000.00, '2025-03-26 16:51:07'),
(65, 92, 6, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-26 16:58:20'),
(66, 104, 4, 8, 5000.00, 25000.00, 'cash', 25000.00, '2025-03-26 16:59:42'),
(67, 106, 4, 8, 5000.00, 25000.00, 'cash', 25000.00, '2025-03-26 16:59:57'),
(68, 109, 4, 8, 5000.00, 62000.00, 'cash', 65000.00, '2025-03-26 22:41:29'),
(69, 108, 4, 8, 5000.00, 25000.00, 'bank_transfer', 25000.00, '2025-03-26 22:41:55'),
(70, 110, 4, 8, 5000.00, 10000.00, 'cash', 10000.00, '2025-03-27 11:23:39'),
(71, 111, 6, 8, 5000.00, 25000.00, 'cash', 30000.00, '2025-03-27 11:25:35'),
(72, 137, 4, NULL, 0.00, 90000.00, 'bank_transfer', 90000.00, '2025-04-05 11:58:48'),
(73, 138, 4, NULL, 0.00, 25000.00, 'cash', 25000.00, '2025-04-05 12:14:56'),
(74, 139, 4, NULL, 0.00, 55000.00, 'bank_transfer', 55000.00, '2025-04-05 12:20:32'),
(75, 145, 4, 10, 3000.00, 57000.00, 'cash', 60000.00, '2025-04-10 11:41:47'),
(76, 147, 6, 10, 3750.00, 71250.00, 'cash', 72000.00, '2025-04-10 15:51:45'),
(77, 148, 5, 10, 2500.00, 47500.00, 'cash', 50000.00, '2025-04-11 03:02:47'),
(78, 149, 5, NULL, 0.00, 50000.00, 'cash', 50000.00, '2025-04-13 14:13:58'),
(79, 152, 4, NULL, 0.00, 75000.00, 'cash', 75000.00, '2025-04-15 14:43:05'),
(80, 153, 4, NULL, 0.00, 80000.00, 'bank_transfer', 80000.00, '2025-04-15 14:45:55'),
(81, 154, 4, NULL, 0.00, 45000.00, 'cash', 50000.00, '2025-04-15 15:09:07'),
(82, 155, 4, NULL, 0.00, 65000.00, 'bank_transfer', 65000.00, '2025-04-15 15:29:32'),
(83, 156, 7, NULL, 0.00, 94000.00, 'cash', 100000.00, '2025-04-17 14:39:10'),
(84, 157, 7, NULL, 0.00, 55000.00, 'bank_transfer', 55000.00, '2025-04-17 15:31:24'),
(85, 159, 4, NULL, 0.00, 54000.00, 'cash', 60000.00, '2025-04-18 16:28:50'),
(86, 158, 6, NULL, 0.00, 149000.00, 'cash', 150000.00, '2025-04-18 16:29:24'),
(87, 161, 6, NULL, 0.00, 105000.00, 'bank_transfer', 105000.00, '2025-04-18 16:29:37'),
(88, 160, 6, NULL, 0.00, 100000.00, 'bank_transfer', 100000.00, '2025-04-18 16:29:52'),
(89, 163, 4, 11, 3000.00, 57000.00, 'cash', 60000.00, '2025-04-20 16:25:35'),
(90, 165, 7, 11, 3850.00, 73150.00, 'cash', 74000.00, '2025-04-20 16:50:03'),
(92, 166, 4, 11, 2500.00, 47500.00, 'cash', 50000.00, '2025-04-20 16:54:12'),
(93, 169, 6, 13, 10000.00, 95000.00, 'bank_transfer', 95000.00, '2025-04-22 08:11:43'),
(94, 168, 4, 12, 3000.00, 57000.00, 'cash', 60000.00, '2025-04-22 08:12:32'),
(95, 170, 4, 12, 2750.00, 52250.00, 'cash', 55000.00, '2025-04-22 08:16:45'),
(96, 172, 4, NULL, 0.00, 30000.00, 'bank_transfer', 30000.00, '2025-04-22 08:30:06'),
(97, 173, 6, NULL, 0.00, 35000.00, 'cash', 40000.00, '2025-04-22 08:36:02'),
(98, 171, 6, 12, 2750.00, 52250.00, 'cash', 53000.00, '2025-04-22 08:36:34'),
(99, 174, 6, 13, 10000.00, 129000.00, 'bank_transfer', 129000.00, '2025-04-22 08:45:57'),
(100, 176, 7, NULL, 0.00, 45000.00, 'bank_transfer', 45000.00, '2025-04-22 13:45:08'),
(101, 177, 4, 13, 10000.00, 105000.00, 'cash', 110000.00, '2025-04-23 07:43:40'),
(102, 178, 4, NULL, 0.00, 30000.00, 'bank_transfer', 30000.00, '2025-04-23 07:45:37'),
(103, 179, 7, 13, 10000.00, 65000.00, 'cash', 65000.00, '2025-04-23 07:51:57'),
(104, 180, 8, 12, 2500.00, 47500.00, 'cash', 48000.00, '2025-04-23 08:42:42'),
(105, 181, 8, NULL, 0.00, 20000.00, 'bank_transfer', 20000.00, '2025-04-23 08:54:58'),
(106, 182, 8, NULL, 0.00, 49000.00, 'cash', 50000.00, '2025-04-23 09:18:46'),
(107, 183, 8, 13, 10000.00, 85000.00, 'bank_transfer', 85000.00, '2025-04-23 12:04:37'),
(108, 184, 6, 13, 10000.00, 65000.00, 'cash', 65000.00, '2025-04-23 12:21:52'),
(109, 185, 8, 12, 3450.00, 65550.00, 'cash', 66000.00, '2025-04-23 14:43:54'),
(110, 189, 6, NULL, 0.00, 15000.00, 'cash', 20000.00, '2025-04-24 00:49:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `name`, `discount_type`, `discount_value`, `min_order_value`, `start_date`, `end_date`, `is_active`, `deleted_at`) VALUES
(1, 'Giờ vàng giảm giá', 'percentage', 20.00, 0.00, '2025-01-01 10:00:00', '2025-01-01 12:00:00', 0, '2025-04-22 22:56:49'),
(2, 'Giảm giá năm mới', 'fixed', 5000.00, 0.00, '2025-01-01 00:00:00', '2025-01-07 23:59:59', 0, NULL),
(3, 'Giảm giá cuối tháng', 'percentage', 10.00, 0.00, '2025-02-26 00:00:00', '2025-02-28 23:59:00', 0, NULL),
(4, 'Tri ân khách hàng', 'fixed', 10000.00, 60000.00, '2025-02-25 00:00:00', '2025-03-05 23:59:00', 0, NULL),
(5, 'Giảm giá tri ân', 'percentage', 10.00, 50000.00, '2025-03-10 00:00:00', '2025-03-14 23:59:00', 0, NULL),
(6, 'Khuyến mãi đặc biệt tháng 3', 'fixed', 10000.00, 50000.00, '2025-03-11 00:00:00', '2025-03-15 23:59:00', 0, NULL),
(7, 'Giảm giá giữa tuần', 'percentage', 10.00, 100000.00, '2025-03-19 00:00:00', '2025-03-23 23:59:00', 0, NULL),
(8, 'giảm giá cuối tháng 3', 'fixed', 5000.00, 0.00, '2025-03-25 00:00:00', '2025-03-31 23:59:00', 0, NULL),
(9, 'Khuyến mãi cuối tuần 1 tháng 4', 'percentage', 5.00, 50000.00, '2025-04-05 00:00:00', '2025-04-06 23:59:00', 0, NULL),
(10, 'Giảm giá cuối tuần', 'percentage', 5.00, 30000.00, '2025-04-10 00:00:00', '2025-04-12 23:59:00', 0, NULL),
(11, 'Khuyến mãi cuối tuần 3 tháng 4', 'percentage', 5.00, 50000.00, '2025-04-20 00:00:00', '2025-04-20 23:59:00', 0, NULL),
(12, 'Khuyến mãi đặc biệt tháng 4', 'percentage', 5.00, 50000.00, '2025-04-22 00:00:00', '2025-04-30 23:59:00', 1, NULL),
(13, 'Khuyến mãi đặc biệt cuối tháng 4', 'fixed', 10000.00, 70000.00, '2025-04-22 00:00:00', '2025-04-30 23:59:00', 1, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `salaries`
--

CREATE TABLE `salaries` (
  `salary_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `total_hours` decimal(10,2) NOT NULL DEFAULT 0.00,
  `salary_per_hour` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_bonus_penalty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_salary` decimal(10,2) GENERATED ALWAYS AS (`total_salary` + `total_bonus_penalty`) STORED,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `salaries`
--

INSERT INTO `salaries` (`salary_id`, `employee_id`, `month`, `year`, `total_hours`, `salary_per_hour`, `total_salary`, `total_bonus_penalty`, `status`, `paid_date`) VALUES
(43, 2, 3, 2025, 10.00, 18000.00, 180000.00, 0.00, 'paid', '2025-04-20 14:37:56'),
(44, 3, 3, 2025, 32.00, 18000.00, 576000.00, 0.00, 'paid', '2025-04-20 14:38:00'),
(45, 4, 3, 2025, 23.00, 15000.00, 345000.00, -10000.00, 'paid', '2025-04-20 14:38:03'),
(46, 5, 3, 2025, 14.00, 15000.00, 210000.00, 20000.00, 'paid', '2025-04-20 14:38:05'),
(47, 6, 3, 2025, 24.00, 20000.00, 480000.00, 0.00, 'paid', '2025-04-20 14:38:08'),
(48, 7, 3, 2025, 18.00, 20000.00, 360000.00, 0.00, 'paid', '2025-04-20 14:38:11'),
(49, 8, 3, 2025, 5.00, 15000.00, 75000.00, 0.00, 'paid', '2025-04-20 14:38:14'),
(50, 2, 4, 2025, 5.00, 18000.00, 90000.00, 0.00, 'pending', NULL),
(51, 3, 4, 2025, 57.00, 18000.00, 1026000.00, -10000.00, 'pending', NULL),
(52, 4, 4, 2025, 32.00, 15000.00, 480000.00, 0.00, 'pending', NULL),
(53, 5, 4, 2025, 18.00, 15000.00, 270000.00, 0.00, 'pending', NULL),
(54, 6, 4, 2025, 33.00, 20000.00, 660000.00, 30000.00, 'pending', NULL),
(55, 7, 4, 2025, 29.00, 20000.00, 580000.00, 0.00, 'pending', NULL),
(56, 8, 4, 2025, 13.00, 15000.00, 195000.00, 0.00, 'pending', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `store_name`, `address`, `phone_number`) VALUES
(1, 'Hope Cafe', '3/2, Xuân Khánh, Ninh Kiều, Cần Thơ', '0762863326');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shifts`
--

INSERT INTO `shifts` (`shift_id`, `name`, `start_time`, `end_time`) VALUES
(1, 'Sáng', '05:00:00', '10:00:00'),
(2, 'Trưa', '10:00:00', '15:00:00'),
(3, 'Chiều', '15:00:00', '20:00:00'),
(4, 'Tối', '20:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tables`
--

INSERT INTO `tables` (`table_id`, `table_number`, `status_id`, `capacity`, `deleted_at`) VALUES
(1, 1, 2, 4, NULL),
(2, 2, 1, 2, NULL),
(3, 3, 1, 6, NULL),
(4, 4, 1, 8, NULL),
(6, 5, 3, 6, NULL),
(7, 6, 1, 4, NULL),
(8, 7, 1, 6, NULL),
(9, 8, 1, 4, NULL),
(10, 9, 2, 6, NULL),
(11, 10, 1, 4, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `table_statuses`
--

CREATE TABLE `table_statuses` (
  `status_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `table_statuses`
--

INSERT INTO `table_statuses` (`status_id`, `name`) VALUES
(1, 'Trống'),
(2, 'Đang sử dụng'),
(3, 'Đang sửa');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `work_schedules`
--

CREATE TABLE `work_schedules` (
  `schedule_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `work_date` date NOT NULL,
  `status` enum('scheduled','completed','absent') NOT NULL DEFAULT 'scheduled',
  `work_hours` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `work_schedules`
--

INSERT INTO `work_schedules` (`schedule_id`, `employee_id`, `shift_id`, `work_date`, `status`, `work_hours`) VALUES
(1, 2, 1, '2025-03-27', 'completed', 5.00),
(2, 3, 2, '2025-03-27', 'completed', 5.00),
(3, 2, 3, '2025-03-27', 'completed', 5.00),
(4, 3, 4, '2025-03-27', 'completed', 4.00),
(5, 6, 1, '2025-03-27', 'completed', 5.00),
(6, 7, 2, '2025-03-27', 'completed', 5.00),
(7, 6, 3, '2025-03-27', 'completed', 5.00),
(8, 7, 4, '2025-03-27', 'completed', 4.00),
(9, 4, 1, '2025-03-27', 'completed', 5.00),
(10, 5, 2, '2025-03-27', 'absent', 0.00),
(11, 4, 3, '2025-03-27', 'completed', 5.00),
(12, 5, 3, '2025-03-27', 'completed', 5.00),
(13, 5, 4, '2025-03-27', 'completed', 4.00),
(31, 6, 3, '2025-04-05', 'completed', 5.00),
(32, 5, 4, '2025-04-10', 'completed', 5.00),
(33, 3, 2, '2025-04-11', 'completed', 5.00),
(34, 6, 4, '2025-04-18', 'completed', 4.00),
(35, 7, 4, '2025-04-17', 'completed', 4.00),
(36, 4, 4, '2025-04-15', 'completed', 4.00),
(37, 5, 4, '2025-04-13', 'completed', 4.00),
(38, 5, 2, '2025-04-11', 'completed', 5.00),
(39, 4, 3, '2025-04-10', 'completed', 5.00),
(40, 4, 3, '2025-04-05', 'completed', 5.00),
(41, 4, 4, '2025-04-18', 'completed', 4.00),
(42, 8, 4, '2025-04-17', 'completed', 4.00),
(43, 3, 4, '2025-04-18', 'completed', 4.00),
(44, 3, 4, '2025-04-17', 'completed', 4.00),
(45, 7, 4, '2025-04-15', 'completed', 4.00),
(46, 3, 4, '2025-04-15', 'completed', 4.00),
(47, 7, 4, '2025-04-13', 'completed', 4.00),
(48, 3, 4, '2025-04-13', 'completed', 4.00),
(49, 6, 2, '2025-04-11', 'completed', 5.00),
(50, 6, 4, '2025-04-10', 'completed', 4.00),
(51, 3, 4, '2025-04-10', 'completed', 4.00),
(52, 2, 3, '2025-04-10', 'completed', 5.00),
(53, 6, 3, '2025-04-10', 'completed', 5.00),
(54, 3, 3, '2025-04-05', 'completed', 5.00),
(55, 4, 4, '2025-03-26', 'completed', 4.00),
(56, 7, 4, '2025-03-26', 'completed', 4.00),
(57, 3, 4, '2025-03-26', 'completed', 4.00),
(58, 5, 3, '2025-03-26', 'completed', 5.00),
(59, 6, 3, '2025-03-26', 'completed', 5.00),
(60, 3, 3, '2025-03-26', 'completed', 5.00),
(61, 4, 1, '2025-03-26', 'completed', 5.00),
(62, 7, 1, '2025-03-26', 'completed', 5.00),
(63, 3, 1, '2025-03-26', 'completed', 5.00),
(64, 4, 4, '2025-03-25', 'completed', 4.00),
(65, 3, 4, '2025-03-25', 'completed', 4.00),
(66, 6, 4, '2025-03-25', 'completed', 4.00),
(67, 6, 2, '2025-03-20', 'completed', 5.00),
(68, 3, 2, '2025-03-20', 'completed', 5.00),
(69, 8, 2, '2025-03-20', 'completed', 5.00),
(70, 4, 4, '2025-04-20', 'completed', 4.00),
(71, 3, 4, '2025-04-20', 'completed', 4.00),
(72, 7, 4, '2025-04-20', 'completed', 4.00),
(73, 3, 3, '2025-04-22', 'completed', 5.00),
(74, 4, 3, '2025-04-22', 'completed', 5.00),
(75, 6, 3, '2025-04-22', 'completed', 5.00),
(76, 5, 4, '2025-04-22', 'completed', 4.00),
(77, 7, 4, '2025-04-22', 'completed', 4.00),
(78, 3, 4, '2025-04-22', 'completed', 4.00),
(80, 7, 2, '2025-04-23', 'completed', 5.00),
(81, 3, 2, '2025-04-23', 'completed', 5.00),
(82, 4, 2, '2025-04-23', 'completed', 5.00),
(83, 6, 3, '2025-04-23', 'completed', 5.00),
(84, 3, 3, '2025-04-23', 'completed', 5.00),
(85, 8, 3, '2025-04-23', 'completed', 5.00),
(86, 3, 4, '2025-04-23', 'completed', 4.00),
(87, 7, 4, '2025-04-23', 'completed', 4.00),
(88, 8, 4, '2025-04-23', 'completed', 4.00),
(89, 3, 1, '2025-04-24', 'scheduled', 0.00),
(90, 6, 1, '2025-04-24', 'scheduled', 0.00),
(91, 4, 1, '2025-04-24', 'scheduled', 0.00),
(93, 3, 2, '2025-04-24', 'scheduled', 0.00),
(94, 7, 2, '2025-04-24', 'scheduled', 0.00),
(95, 8, 2, '2025-04-24', 'scheduled', 0.00),
(96, 2, 3, '2025-04-24', 'scheduled', 0.00),
(97, 5, 3, '2025-04-24', 'scheduled', 0.00),
(98, 6, 3, '2025-04-24', 'scheduled', 0.00),
(99, 4, 4, '2025-04-24', 'scheduled', 0.00),
(100, 2, 4, '2025-04-24', 'scheduled', 0.00),
(101, 7, 4, '2025-04-24', 'scheduled', 0.00),
(102, 6, 1, '2025-04-25', 'scheduled', 0.00),
(103, 3, 1, '2025-04-25', 'scheduled', 0.00),
(104, 8, 1, '2025-04-25', 'scheduled', 0.00),
(105, 2, 2, '2025-04-25', 'scheduled', 0.00),
(106, 7, 2, '2025-04-25', 'scheduled', 0.00),
(107, 5, 2, '2025-04-25', 'scheduled', 0.00),
(108, 8, 3, '2025-04-25', 'scheduled', 0.00),
(109, 5, 3, '2025-04-25', 'scheduled', 0.00);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bonuses_penalties`
--
ALTER TABLE `bonuses_penalties`
  ADD PRIMARY KEY (`bonus_penalty_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_phone` (`phone_number`);

--
-- Chỉ mục cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `ingredient_logs`
--
ALTER TABLE `ingredient_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Chỉ mục cho bảng `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  ADD PRIMARY KEY (`item_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Chỉ mục cho bảng `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `unique_menu_item_name` (`name`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `table_id` (`table_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Chỉ mục cho bảng `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Chỉ mục cho bảng `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `table_number` (`table_number`),
  ADD KEY `status_id` (`status_id`);

--
-- Chỉ mục cho bảng `table_statuses`
--
ALTER TABLE `table_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bonuses_penalties`
--
ALTER TABLE `bonuses_penalties`
  MODIFY `bonus_penalty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `ingredient_logs`
--
ALTER TABLE `ingredient_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=362;

--
-- AUTO_INCREMENT cho bảng `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `table_statuses`
--
ALTER TABLE `table_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bonuses_penalties`
--
ALTER TABLE `bonuses_penalties`
  ADD CONSTRAINT `bonuses_penalties_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ingredient_logs`
--
ALTER TABLE `ingredient_logs`
  ADD CONSTRAINT `inventory_logs_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `inventory_logs_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Các ràng buộc cho bảng `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  ADD CONSTRAINT `menu_ingredients_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`),
  ADD CONSTRAINT `menu_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`);

--
-- Các ràng buộc cho bảng `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`);

--
-- Các ràng buộc cho bảng `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tables`
--
ALTER TABLE `tables`
  ADD CONSTRAINT `tables_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `table_statuses` (`status_id`);

--
-- Các ràng buộc cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD CONSTRAINT `work_schedules_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_schedules_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`shift_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
