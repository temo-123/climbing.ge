-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Янв 21 2023 г., 15:35
-- Версия сервера: 8.0.31-0ubuntu0.20.04.2
-- Версия PHP: 8.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `climbing,loc_test_2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` int DEFAULT NULL,
  `fb_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inst_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twit_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `star` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `closed_time` time DEFAULT NULL,
  `weather` text COLLATE utf8mb4_unicode_ci,
  `map` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_article_id` bigint UNSIGNED NOT NULL,
  `ka_article_id` bigint UNSIGNED NOT NULL,
  `ru_article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `category`, `url_title`, `published`, `fb_link`, `inst_link`, `google_link`, `twit_link`, `web_link`, `star`, `price_from`, `open_time`, `closed_time`, `weather`, `map`, `image`, `us_article_id`, `ka_article_id`, `ru_article_id`, `created_at`, `updated_at`) VALUES
(1, 'outdoor', 'test_article_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 3, '2022-10-21 11:07:37', '2022-07-12 11:07:40'),
(2, 'news', 'news_test_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 6, '2022-10-20 09:15:22', NULL),
(11, 'tech_tip', 'tip_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 18, 17, '2022-07-19 20:35:08', '2022-07-19 20:35:08'),
(12, 'tech_tip', 'tip_2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 21, 20, '2022-07-19 20:35:08', '2022-07-19 20:35:08'),
(13, 'tech_tip', 'tip_3', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, 24, 23, '2022-07-19 20:35:08', '2022-07-19 20:35:08'),
(14, 'tech_tip', 'tip_4', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, 26, 27, '2022-10-09 20:35:08', '2022-10-15 20:35:08'),
(15, 'indoor', 'test_indoor_1', 1, NULL, NULL, NULL, NULL, NULL, '5', '10', '11:00:00', '23:00:00', NULL, NULL, NULL, 28, 29, 30, '2022-10-18 20:46:01', '2022-10-21 10:55:31'),
(16, 'other', 'other_actity_test_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, 32, 33, '2022-10-23 10:51:30', '2022-10-23 10:51:34'),
(25, 'outdoor', 'temo', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 102, 100, 101, '2022-08-05 16:05:58', '2022-08-05 16:05:58'),
(35, 'mount_route', 'mount_route_1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 14, 15, '2022-10-15 10:52:06', '2022-10-23 10:52:14'),
(38, 'news', 'temo123', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 120, 118, 119, '2022-08-30 15:20:23', '2022-08-30 15:20:23'),
(40, 'news', 'samsonadzetemo9@gmailcom', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 126, 124, 125, '2022-08-30 15:25:02', '2022-08-30 15:25:02'),
(42, 'news', 'samsonadzetemo9@gmailcomddd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 132, 130, 131, '2022-08-30 15:27:37', '2022-08-30 15:27:37'),
(43, 'news', 'stone', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 135, 133, 134, '2022-08-30 15:36:09', '2022-08-30 15:36:09'),
(45, 'news', 'stonekk', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 141, 139, 140, '2022-08-30 15:42:41', '2022-08-30 15:42:41'),
(46, 'news', 'stonek', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 144, 142, 143, '2022-08-30 15:43:02', '2022-08-30 15:43:02'),
(47, 'ice', 'stonekj', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 147, 145, 146, '2022-08-30 15:43:17', '2022-08-30 15:43:17'),
(49, 'mount_route', 'stonekjkk', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 153, 151, 152, '2022-08-30 15:48:20', '2022-08-30 15:48:21'),
(75, 'news', 'temoddd', 1, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', '', '', '2022-11-25-18-11-38.jpg', 230, 228, 229, '2022-11-25 14:53:39', '2022-11-25 14:53:39'),
(101, 'outdoor', 'dd_dddlll_gggg', 1, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', '', 'dddddddddddddddddddd', '2023-01-09-22-01-50-1673301770{581647}.png', 344, 342, 343, '2022-11-26 18:43:50', '2023-01-09 18:03:45'),
(102, 'ice', 'test_2', 1, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', 'sdcdc', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1802.8266849535078!2d44.639158!3d42.6529294!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x441c48a0a1446fc9!2zNDLCsDM5JzA5LjIiTiA0NMKwMzgnMjMuMyJF!5e1!3m2!1sen!2sge!4v1651777510420!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-12-25-07-12-18-1671953958{873258}.png', 347, 345, 346, '2022-12-25 03:39:18', '2022-12-25 03:39:18'),
(103, 'other', 'temo_dddddd', 1, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', '', '<iframe src=\"https://www.google.com/maps/d/embed?mid=15BvN2xGNmjk0Bu_-B3gBZZ7penH7HLg&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', '2022-12-25-10-12-20-1671965420{210923}.jpg', 350, 348, 349, '2022-12-25 06:50:20', '2022-12-25 06:50:20'),
(105, 'outdoor', 'temonnn', 1, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', '', '', '2023-01-09-22-01-30-1673304690{888436}.png', 356, 354, 355, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(107, 'outdoor', 'lllllllll', 0, '', '', '', '', '', NULL, '', '00:00:00', '00:00:00', '', '', '2023-01-10-20-01-29-1673383949{957336}.png', 362, 360, 361, '2023-01-09 21:02:07', '2023-01-18 18:32:14');

-- --------------------------------------------------------

--
-- Структура таблицы `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `surname` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `text` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  `deleted_reason` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `article_comments`
--

INSERT INTO `article_comments` (`id`, `name`, `surname`, `email`, `text`, `article_id`, `deleted_reason`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'Temo Samsonadze', 'Samsonadze', 'admin@climbing.loc', 'ddd', 2, 'Does not match the theme of the site', '2022-11-14 16:00:59', '2022-11-05 09:49:30', '2022-11-14 16:32:59'),
(14, 'Temo Samsonadze', 'Samsonadze', 'admin@climbing.loc', 'ssssssssssssss', 2, NULL, NULL, '2022-11-15 16:19:23', '2022-11-15 16:19:23'),
(15, 'Temo Samsonadze', 'Samsonadze', 'admin@climbing.loc', 'dscdc', 1, 'This is my comment', '2022-11-15 16:00:53', '2022-11-15 16:52:08', '2022-11-15 16:53:53'),
(17, 'Temoooo', 'Samsonadze', 'admin@climbing.loc', 'jjkdjjwjerfnr', 25, NULL, NULL, '2022-12-25 04:53:02', '2022-12-25 04:53:02'),
(18, 'Temo', 'Samsonadze', 'samsonadzsssse.temo9@gmail.com', 'ssssssss', 102, NULL, NULL, '2023-01-20 09:48:39', '2023-01-20 09:48:39'),
(19, 'Temoooo', 'Samsonadze', 'admin@climbing.loc', 'ddddd', 104, NULL, NULL, '2023-01-20 09:54:22', '2023-01-20 09:54:22');

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_complaints`
--

CREATE TABLE `article_comment_complaints` (
  `id` int NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `comment_id` int DEFAULT NULL,
  `complaint` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cause` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_queries`
--

CREATE TABLE `article_comment_queries` (
  `id` int NOT NULL,
  `comment_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_user`
--

CREATE TABLE `article_comment_user` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `comment_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `article_comment_user`
--

INSERT INTO `article_comment_user` (`id`, `user_id`, `comment_id`, `created_at`, `updated_at`) VALUES
(2, 3, 1, NULL, NULL),
(3, 3, 2, NULL, NULL),
(4, 3, NULL, '2022-11-15 16:15:01', '2022-11-15 16:15:01'),
(5, 3, NULL, '2022-11-15 16:16:07', '2022-11-15 16:16:07'),
(6, 3, NULL, '2022-11-15 16:38:55', '2022-11-15 16:38:55'),
(7, 3, NULL, '2022-11-15 16:39:27', '2022-11-15 16:39:27'),
(8, 3, NULL, '2022-11-15 16:48:36', '2022-11-15 16:48:36'),
(9, 3, NULL, '2022-11-15 16:53:20', '2022-11-15 16:53:20'),
(10, 3, NULL, '2022-12-25 09:54:25', '2022-12-25 09:54:25'),
(11, 3, NULL, '2023-01-20 09:54:40', '2023-01-20 09:54:40');

-- --------------------------------------------------------

--
-- Структура таблицы `article_mount`
--

CREATE TABLE `article_mount` (
  `id` int NOT NULL,
  `mount_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `article_mount`
--

INSERT INTO `article_mount` (`id`, `mount_id`, `article_id`, `created_at`, `updated_at`) VALUES
(2, 1, 35, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `article_region`
--

CREATE TABLE `article_region` (
  `id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `region_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `article_region`
--

INSERT INTO `article_region` (`id`, `article_id`, `region_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 25, 1, NULL, NULL),
(3, 101, 2, '2022-11-26 18:43:50', '2023-01-10 16:54:21'),
(4, 105, 1, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(5, 107, 2, '2023-01-09 21:02:07', '2023-01-10 16:54:13');

-- --------------------------------------------------------

--
-- Структура таблицы `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `carts`
--

INSERT INTO `carts` (`id`, `product_id`, `user_id`, `option_id`, `created_at`, `updated_at`, `quantity`) VALUES
(49, NULL, 3, 1, '2022-11-16 06:37:50', '2022-11-16 06:37:50', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `url_title` varchar(191) DEFAULT NULL,
  `published` int DEFAULT NULL,
  `category` varchar(191) DEFAULT NULL,
  `start_data` timestamp NULL DEFAULT NULL,
  `end_data` timestamp NULL DEFAULT NULL,
  `us_event_id` int DEFAULT NULL,
  `ka_event_id` int DEFAULT NULL,
  `ru_event_id` int DEFAULT NULL,
  `map` text,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `url_title`, `published`, `category`, `start_data`, `end_data`, `us_event_id`, `ka_event_id`, `ru_event_id`, `map`, `image`, `created_at`, `updated_at`) VALUES
(3, 'temo_samso', 1, 'event', '2022-12-20 22:42:00', '2022-12-24 22:42:00', 24, 22, 23, 'Delaware', '2022-12-17-22-12-09-1671317229{796077}.jpg', '2022-12-17 16:50:08', '2022-12-22 08:19:13'),
(4, 'dry_tolling_fest', 1, 'event', '2023-01-01 12:00:00', '2023-01-01 12:00:00', 27, 25, 26, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1813.369837340191!2d30.115071054547386!3d69.68008158206685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x45cb4953708eca71%3A0xf124fff12e769bfd!2sB%C3%B8kfjordbrua!5e1!3m2!1sen!2sge!4v1671869662405!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-12-24-08-12-45-1671869685{349793}.jpeg', '2022-12-22 07:59:17', '2022-12-24 04:14:46');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_films`
--

CREATE TABLE `favorite_films` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `film_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `favorite_films`
--

INSERT INTO `favorite_films` (`id`, `user_id`, `film_id`, `created_at`, `updated_at`) VALUES
(2, 3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_outdoor_areas`
--

CREATE TABLE `favorite_outdoor_areas` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `favorite_outdoor_areas`
--

INSERT INTO `favorite_outdoor_areas` (`id`, `user_id`, `article_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2022-10-12 10:50:17', '2022-10-12 10:50:17'),
(4, 3, 25, '2022-12-25 04:27:26', '2022-12-25 04:27:26');

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_products`
--

CREATE TABLE `favorite_products` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `favorite_products`
--

INSERT INTO `favorite_products` (`id`, `product_id`, `user_id`, `created_at`, `updated_at`) VALUES
(16, 1, 3, '2022-10-16 15:01:18', '2022-10-16 15:01:18');

-- --------------------------------------------------------

--
-- Структура таблицы `films`
--

CREATE TABLE `films` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_film_id` int DEFAULT NULL,
  `ka_film_id` int DEFAULT NULL,
  `ru_film_id` int DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `films`
--

INSERT INTO `films` (`id`, `url_title`, `us_film_id`, `ka_film_id`, `ru_film_id`, `image`, `public`, `created_at`, `updated_at`) VALUES
(1, 'test_url_1', 1, 2, 3, '1.jpg', '1', NULL, NULL),
(2, 'test_2', 4, 5, 6, '2.jpg', '1', NULL, NULL),
(3, 'test_3', 7, 8, 9, NULL, '1', NULL, NULL),
(4, 'test_4', 10, 11, 12, '3.jpg', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `film_categories`
--

CREATE TABLE `film_categories` (
  `id` int NOT NULL,
  `name_us` varchar(191) DEFAULT NULL,
  `name_ru` varchar(191) DEFAULT NULL,
  `name_ka` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `film_categories`
--

INSERT INTO `film_categories` (`id`, `name_us`, `name_ru`, `name_ka`, `created_at`, `updated_at`) VALUES
(1, 'fdfjhgh', 'gfgfdg', ',jlkjl', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `film_files`
--

CREATE TABLE `film_files` (
  `id` int NOT NULL,
  `us_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ka_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ru_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `us_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ka_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ru_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `film_id` int DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `film_files`
--

INSERT INTO `film_files` (`id`, `us_name`, `ka_name`, `ru_name`, `us_description`, `ka_description`, `ru_description`, `film_id`, `file`, `created_at`, `updated_at`) VALUES
(1, 'test name 1 us', 'test name 1 ka', 'test name 1 ru', 'test description 1 us', 'test description 1 ka', 'test description 1 ru', 1, 'kdwjflkdjf.torrent', '2022-10-17 12:58:41', '2022-10-20 12:58:41'),
(2, 'test name 2 us', 'test name 2 ka', 'test name 2 ru', 'test descriprion 2 us', 'test descriprion 2 ka', 'test descriprion 2 ru', 2, 'lkdjfddkf.torrent', '2022-10-11 12:58:41', '2022-10-20 12:58:41');

-- --------------------------------------------------------

--
-- Структура таблицы `film_reitings`
--

CREATE TABLE `film_reitings` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `film_id` int DEFAULT NULL,
  `score` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `film_tags`
--

CREATE TABLE `film_tags` (
  `id` int NOT NULL,
  `us_name` varchar(191) DEFAULT NULL,
  `ru_name` varchar(191) DEFAULT NULL,
  `ka_name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `film_tags`
--

INSERT INTO `film_tags` (`id`, `us_name`, `ru_name`, `ka_name`, `created_at`, `updated_at`) VALUES
(1, 'test us 12', 'test ru 1', 'test ka 1', '2022-10-18 09:47:03', '2022-11-12 12:50:00'),
(5, 'dddd', 'dd', 'ddd', '2022-11-12 12:40:23', '2022-11-12 12:40:23');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sector_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mtp_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topic_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_categories`
--

CREATE TABLE `gallery_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gallery_categories`
--

INSERT INTO `gallery_categories` (`id`, `us_name`, `ru_name`, `ka_name`, `created_at`, `updated_at`) VALUES
(1, 'test category 1 us', 'test category 1 ru', 'test category 1 ka', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `image_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `text`, `image`, `published`, `image_type`, `link`, `category_id`, `article_id`, `created_at`, `updated_at`) VALUES
(6, 'ss', 'ssssss', '2022-11-28-12-11-41.jpg', 1, 'Index gallery images', 'ssss', 1, NULL, '2022-11-28 08:04:41', '2023-01-11 16:39:01'),
(9, 'tttt', 'dddd', '2021-11-26-20-11-02.jpg', 1, 'Index head slider images', '', 1, NULL, '2023-01-11 16:27:29', '2023-01-11 16:27:29'),
(10, 'gggggggg', 'jjjjjjjjjjj', '2023-01-11-20-01-09-1673470509{579173}.png', 1, 'Article image', '', 1, NULL, '2023-01-11 16:55:10', '2023-01-11 16:55:10');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_image_article`
--

CREATE TABLE `gallery_image_article` (
  `id` int NOT NULL,
  `image_id` int NOT NULL,
  `article_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `gallery_image_article`
--

INSERT INTO `gallery_image_article` (`id`, `image_id`, `article_id`, `created_at`, `updated_at`) VALUES
(4, 10, 101, '2023-01-11 16:55:10', '2023-01-11 16:55:10');

-- --------------------------------------------------------

--
-- Структура таблицы `general_infos`
--

CREATE TABLE `general_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_ru` text COLLATE utf8mb4_unicode_ci,
  `text_ka` text COLLATE utf8mb4_unicode_ci,
  `text_us` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `general_infos`
--

INSERT INTO `general_infos` (`id`, `title`, `text_ru`, `text_ka`, `text_us`, `created_at`, `updated_at`) VALUES
(1, 'test general info', '<p>test general text 1 ru222</p>', '<p>test general text 1 ka222</p>', '<p>test general text 1 us222</p>', NULL, '2022-11-19 16:41:33');

-- --------------------------------------------------------

--
-- Структура таблицы `general_info_article`
--

CREATE TABLE `general_info_article` (
  `id` bigint UNSIGNED NOT NULL,
  `info_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  `block` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `block_action` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `general_info_article`
--

INSERT INTO `general_info_article` (`id`, `info_id`, `article_id`, `block`, `block_action`, `created_at`, `updated_at`) VALUES
(33, 1, 1, 'routes_info', 'after', '2022-08-05 16:05:58', '2023-01-09 20:16:49'),
(35, 1, 1, 'what_need_info', 'after', '2022-08-05 16:05:58', '2022-08-05 16:05:58'),
(36, 1, 1, 'best_time', 'befor', '2022-08-05 16:05:58', '2022-08-05 16:05:58'),
(37, 1, 58, 'routes_info', 'befor', '2022-09-05 14:45:30', '2022-09-05 14:45:30'),
(38, 1, 58, 'what_need_info', 'instead', '2022-09-05 14:45:30', '2022-09-05 14:45:30'),
(39, 1, 58, 'best_time', 'after', '2022-09-05 14:45:30', '2022-09-05 14:45:30'),
(40, 1, 63, 'info_block', 'instead', '2022-11-20 16:02:56', '2022-11-20 16:02:56'),
(41, 1, 63, 'what_need_info', 'after', '2022-11-20 16:02:56', '2022-11-20 16:02:56'),
(42, 1, 63, 'best_time', 'befor', '2022-11-20 16:02:56', '2022-11-20 16:02:56'),
(43, 1, 74, 'info_block', 'instead', '2022-11-25 14:34:04', '2022-11-25 14:34:04'),
(44, 1, 74, 'what_need_info', 'after', '2022-11-25 14:34:04', '2022-11-25 14:34:04'),
(45, 1, 74, 'best_time', 'befor', '2022-11-25 14:34:04', '2022-11-25 14:34:04'),
(46, 1, 75, 'info_block', 'befor', '2022-11-25 14:53:39', '2022-11-25 14:53:39'),
(47, 1, 25, 'info_block', 'instead', '2023-01-09 20:17:25', '2023-01-09 20:17:25');

-- --------------------------------------------------------

--
-- Структура таблицы `interested_events`
--

CREATE TABLE `interested_events` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `locale_articles`
--

CREATE TABLE `locale_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `route` text COLLATE utf8mb4_unicode_ci,
  `how_get` text COLLATE utf8mb4_unicode_ci,
  `best_time` text COLLATE utf8mb4_unicode_ci,
  `what_need` text COLLATE utf8mb4_unicode_ci,
  `info` text COLLATE utf8mb4_unicode_ci,
  `price_text` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `locale_articles`
--

INSERT INTO `locale_articles` (`id`, `locale`, `title`, `short_description`, `text`, `route`, `how_get`, `best_time`, `what_need`, `info`, `price_text`, `address`, `created_at`, `updated_at`) VALUES
(1, 'us', 'Test article 1 us', '<p>ddddd</p>\n', '<p>test text</p>\n', '<p>test route description test</p>\n', '', '', '', '', NULL, NULL, NULL, '2023-01-09 20:16:49'),
(2, 'ka', 'დფდსფდფდფ test article 1 ka', '<p>ddddddddd</p>\n', '<p>dddddddddd</p>\n', '', '', '', '', '', NULL, NULL, NULL, '2023-01-09 20:16:49'),
(3, 'ru', 'вавававава test article 1 ru', '<p>dddddd</p>\n', '<p>dddddddddd</p>\n', '', '', '', '', '', NULL, NULL, NULL, '2023-01-09 20:16:49'),
(4, 'us', 'us news test 1', 'ddd', 'test text us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'ka', 'ka news test 1', 'llll', 'test text ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'ru', 'ru news test 1', 'vvv', 'test text ru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'us', 'us evwnt test 1', NULL, '<p>Excepteur sint occaecat cupidatat non proident, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat!</p>\r\n\r\n<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Itaque earum rerum hic tenetur a sapiente delectus, nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, qui dolorem eum fugiat, quo voluptas nulla pariatur.</p>\r\n\r\n<p>Quis autem vel eum iure reprehenderit, quis nostrum exercitationem ullam corporis suscipit laboriosam, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Sed ut perspiciatis, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, nisi ut aliquid ex ea commodi consequatur.</p>\r\n\r\n<p>Ut enim ad minima veniam, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, quae ab illo inventore veritatis et quasi architecto beatae...</p>', NULL, NULL, NULL, NULL, '<p>Excepteur sint occaecat cupidatat non proident, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat!</p>\r\n\r\n<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Itaque earum rerum hic tenetur a sapiente delectus, nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, qui dolorem eum fugiat, quo voluptas nulla pariatur.</p>', NULL, NULL, NULL, NULL),
(8, 'ka', 'ka evwnt test 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'ru', 'ru evwnt test 1', NULL, '<p>Практический опыт показывает, что повышение уровня гражданского сознания позволяет выполнить важнейшие задания по разработке дальнейших направлений развития проекта? Практический опыт показывает, что курс на социально-ориентированный национальный проект требует определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Соображения высшего порядка, а также повышение уровня гражданского сознания способствует повышению актуальности форм воздействия.</p>\r\n\r\n<p>Дорогие друзья, курс на социально-ориентированный национальный проект способствует подготовке и реализации ключевых компонентов планируемого обновления? Таким образом, реализация намеченного плана развития способствует подготовке и реализации форм воздействия? Практический опыт показывает, что постоянное информационно-техническое обеспечение нашей деятельности требует от нас системного анализа всесторонне сбалансированных нововведений? Не следует, однако, забывать о том, что курс на социально-ориентированный национальный проект способствует повышению актуальности существующих финансовых и административных условий.</p>\r\n\r\n<p>Таким образом, реализация намеченного плана развития требует от нас анализа существующих финансовых и административных условий. Значимость этих проблем настолько очевидна, что постоянное информационно-техническое обеспечение нашей деятельности позволяет выполнить важнейшие задания по разработке дальнейших направлений развития проекта. Таким образом, рамки и место обучения кадров позволяет оценить значение системы обучения кадров, соответствующей насущным потребностям. Практический опыт показывает, что социально-экономическое развитие требует от нас системного анализа дальнейших направлений развития проекта.</p>\r\n\r\n<p>Не следует, однако, забывать о том, что рамки и место обучения кадров позволяет выполнить важнейшие задания по...</p>', NULL, NULL, NULL, NULL, '<p>Практический опыт показывает, что повышение уровня гражданского сознания позволяет выполнить важнейшие задания по разработке дальнейших направлений развития проекта? Практический опыт показывает, что курс на социально-ориентированный национальный проект требует определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Соображения высшего порядка, а также повышение уровня гражданского сознания способствует повышению актуальности форм воздействия.</p>\r\n\r\n<p>Дорогие друзья, курс на социально-ориентированный национальный проект способствует подготовке и реализации ключевых компонентов планируемого обновления? Таким образом, реализация намеченного плана развития способствует подготовке и реализации форм воздействия? Практический опыт показывает, что постоянное информационно-техническое обеспечение нашей деятельности требует от нас системного анализа всесторонне сбалансированных нововведений? Не следует, однако, забывать о том, что курс на социально-ориентированный национальный проект способствует повышению актуальности существующих финансовых и административных условий.</p>', NULL, NULL, NULL, NULL),
(13, 'us', 'mount route test 1 us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'ka', 'mount route test 1 ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'ka', 'mount route test 1 ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'us', 'us tip 1', 'test tip 1 us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'ru', ' ru tip 1', 'test tip 1 ru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'ka', 'ka tip 1', 'test tip 1 ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'us', 'us tip 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'ru', 'ru tip 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'ka', 'ka tip 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'us', 'us tip 3', 'test us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'ru', 'ru tip 3', 'test ru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'ka', 'ka tip 3', 'test ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'us', 'us tip 4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'ka', 'ka tip 4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'ru', 'ru tip 4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'us', 'us indoor', 'jjefj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'ka', 'ka indoor', 'mddefmkfew', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'ru', 'ru indoor', 'kdkewfk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'us', 'us other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'us', 'us other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'us', 'us other', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 'ka', 'dcdc', '<p>vfs</p>\n', '<p>arewf</p>\n', '', '', '', '', '', NULL, NULL, '2022-08-05 17:22:21', '2023-01-09 20:17:25'),
(101, 'ru', 'evev', '<p>vrevrev</p>\n', '<p>vewvsv</p>\n', '<p>svsv</p>\n', '', '<p>errv</p>\n', '', '<p>vevrev</p>\n', NULL, NULL, '2022-08-05 17:22:21', '2023-01-09 20:17:25'),
(102, 'us', 'dsc', '<p>dsscdsc</p>\n', '<p>dcscdsc</p>\n', '', '', '', '', '', NULL, NULL, '2022-08-05 17:22:21', '2023-01-09 20:17:25'),
(106, 'ka', 'dcdc', '<p>vfs</p>', '<p>arewf</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 17:24:16', '2022-08-05 17:24:16'),
(107, 'ru', 'evev', '<p>vrevrev</p>', '<p>vewvsv</p>', '<p>svsv</p>', NULL, '<p>errv</p>', NULL, '<p>vevrev</p>', NULL, NULL, '2022-08-05 17:24:16', '2022-08-05 17:24:16'),
(108, 'us', 'dsc', '<p>dsscdsc</p>', '<p>dcscdsc</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 17:24:16', '2022-08-05 17:24:16'),
(109, 'ka', 'dcdc', '<p>vfs</p>', '<p>arewf</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 17:40:10', '2022-08-05 17:40:10'),
(110, 'ru', 'evev', '<p>vrevrev</p>', '<p>vewvsv</p>', '<p>svsv</p>', NULL, '<p>errv</p>', NULL, '<p>vevrev</p>', NULL, NULL, '2022-08-05 17:40:10', '2022-08-05 17:40:10'),
(111, 'us', 'dsc', '<p>dsscdsc</p>', '<p>dcscdsc</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-05 17:40:10', '2022-08-05 17:40:10'),
(118, 'ka', 'kkkkkk', '<p>llllllllll</p>', '<p>hhhhhhhh</p>', NULL, NULL, NULL, NULL, '<p>ffffffffffff</p>', NULL, NULL, '2022-08-30 15:20:23', '2022-08-30 15:20:23'),
(119, 'ru', 'uuuu', '<p>rrrrrrrrrrrrr</p>', '<p>yyyyyyyyy</p>', NULL, NULL, NULL, NULL, '<p>vvvv</p>', NULL, NULL, '2022-08-30 15:20:23', '2022-08-30 15:20:23'),
(120, 'us', 'Temo123', '<p>jjjjjj</p>', '<p>kkkkkkk</p>', NULL, NULL, NULL, NULL, '<p>llllllll</p>', NULL, NULL, '2022-08-30 15:20:23', '2022-08-30 15:20:23'),
(121, 'ka', 'kkkkkk', '<p>llllllllll</p>', '<p>hhhhhhhh</p>', NULL, NULL, NULL, NULL, '<p>ffffffffffff</p>', NULL, NULL, '2022-08-30 15:23:20', '2022-08-30 15:23:20'),
(122, 'ru', 'uuuu', '<p>rrrrrrrrrrrrr</p>', '<p>yyyyyyyyy</p>', NULL, NULL, NULL, NULL, '<p>vvvv</p>', NULL, NULL, '2022-08-30 15:23:20', '2022-08-30 15:23:20'),
(123, 'us', 'Temo123', '<p>jjjjjj</p>', '<p>kkkkkkk</p>', NULL, NULL, NULL, NULL, '<p>llllllll</p>', NULL, NULL, '2022-08-30 15:23:20', '2022-08-30 15:23:20'),
(124, 'ka', 'rr', '<p>ggggggggggg</p>', '<p>wwwwwww</p>', NULL, NULL, NULL, NULL, '<p>cccccccc</p>', NULL, NULL, '2022-08-30 15:25:02', '2022-08-30 15:25:02'),
(125, 'ru', '2', '<p>gggg</p>', '<p>rrrrrrrrr</p>', NULL, NULL, NULL, NULL, '<p>ddddddd</p>', NULL, NULL, '2022-08-30 15:25:02', '2022-08-30 15:25:02'),
(126, 'us', 'samsonadze.temo9@gmail.com', '<p>dddddd</p>', '<p>fffffff</p>', NULL, NULL, NULL, NULL, '<p>gggggggggggg</p>', NULL, NULL, '2022-08-30 15:25:02', '2022-08-30 15:25:02'),
(127, 'ka', 'rr', '<p>ggggggggggg</p>', '<p>wwwwwww</p>', NULL, NULL, NULL, NULL, '<p>cccccccc</p>', NULL, NULL, '2022-08-30 15:27:30', '2022-08-30 15:27:30'),
(128, 'ru', '2', '<p>gggg</p>', '<p>rrrrrrrrr</p>', NULL, NULL, NULL, NULL, '<p>ddddddd</p>', NULL, NULL, '2022-08-30 15:27:30', '2022-08-30 15:27:30'),
(129, 'us', 'samsonadze.temo9@gmail.com', '<p>dddddd</p>', '<p>fffffff</p>', NULL, NULL, NULL, NULL, '<p>gggggggggggg</p>', NULL, NULL, '2022-08-30 15:27:30', '2022-08-30 15:27:30'),
(130, 'ka', 'rr', '<p>ggggggggggg</p>', '<p>wwwwwww</p>', NULL, NULL, NULL, NULL, '<p>cccccccc</p>', NULL, NULL, '2022-08-30 15:27:37', '2022-08-30 15:27:37'),
(131, 'ru', '2', '<p>gggg</p>', '<p>rrrrrrrrr</p>', NULL, NULL, NULL, NULL, '<p>ddddddd</p>', NULL, NULL, '2022-08-30 15:27:37', '2022-08-30 15:27:37'),
(132, 'us', 'samsonadze.temo9@gmail.comddd', '<p>dddddd</p>', '<p>fffffff</p>', NULL, NULL, NULL, NULL, '<p>gggggggggggg</p>', NULL, NULL, '2022-08-30 15:27:37', '2022-08-30 15:27:37'),
(133, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:36:09', '2022-08-30 15:36:09'),
(134, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:36:09', '2022-08-30 15:36:09'),
(135, 'us', 'Stone', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:36:09', '2022-08-30 15:36:09'),
(136, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:42:13', '2022-08-30 15:42:13'),
(137, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:42:13', '2022-08-30 15:42:13'),
(138, 'us', 'Stone', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:42:13', '2022-08-30 15:42:13'),
(139, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:42:41', '2022-08-30 15:42:41'),
(140, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:42:41', '2022-08-30 15:42:41'),
(141, 'us', 'Stonekk', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:42:41', '2022-08-30 15:42:41'),
(142, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:43:02', '2022-08-30 15:43:02'),
(143, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:43:02', '2022-08-30 15:43:02'),
(144, 'us', 'Stonek', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:43:02', '2022-08-30 15:43:02'),
(145, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:43:17', '2022-08-30 15:43:17'),
(146, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:43:17', '2022-08-30 15:43:17'),
(147, 'us', 'Stonekj', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:43:17', '2022-08-30 15:43:17'),
(151, 'ka', 'hoiu', '<p>dxrd</p>', '<p>jhjh</p>', NULL, NULL, NULL, NULL, '<p>gvcfc</p>', NULL, NULL, '2022-08-30 15:48:20', '2022-08-30 15:48:20'),
(152, 'ru', 'uh', '<p>xd</p>', '<p>iuhy</p>', NULL, NULL, NULL, NULL, '<p>cy</p>', NULL, NULL, '2022-08-30 15:48:20', '2022-08-30 15:48:20'),
(153, 'us', 'Stonekjkk', '<p>jkj</p>', '<p>hjoj</p>', NULL, NULL, NULL, NULL, '<p>yug</p>', NULL, NULL, '2022-08-30 15:48:20', '2022-08-30 15:48:20'),
(157, 'ka', 'ddddddddd', '<p>ffffffffff</p>', '<p>fffffffffff</p>', '<p>fffffffff</p>', '<p>ffffffffff</p>', '<p>ffffffffff</p>', NULL, '<p>ffffffffff</p>', NULL, NULL, '2022-09-05 14:40:09', '2022-09-05 14:40:09'),
(158, 'ru', 'ggggggggg', '<p>ffffffff</p>', '<p>ggggggggg</p>', '<p>dddddd</p>', '<p>ffffffff</p>', '<p>fffffffffff</p>', NULL, '<p>dddddddddd</p>', NULL, NULL, '2022-09-05 14:40:09', '2022-09-05 14:40:09'),
(159, 'us', 'Temo123dddddddd', '<p>ddddddddd</p>', '<p>ddddddddddd</p>', '<p>ddd</p>', '<p>dddddd</p>', '<p>dddd</p>', NULL, '<p>dddddddd</p>', NULL, NULL, '2022-09-05 14:40:09', '2022-09-05 14:40:09'),
(163, 'ka', 'ddddddddd', '<p>ffffffffff</p>', '<p>fffffffffff</p>', '<p>fffffffff</p>', '<p>ffffffffff</p>', '<p>ffffffffff</p>', NULL, '<p>ffffffffff</p>', NULL, NULL, '2022-09-05 14:41:50', '2022-09-05 14:41:50'),
(164, 'ru', 'ggggggggg', '<p>ffffffff</p>', '<p>ggggggggg</p>', '<p>dddddd</p>', '<p>ffffffff</p>', '<p>fffffffffff</p>', NULL, '<p>dddddddddd</p>', NULL, NULL, '2022-09-05 14:41:50', '2022-09-05 14:41:50'),
(165, 'us', 'Temo123dddddddddddd', '<p>ddddddddd</p>', '<p>ddddddddddd</p>', '<p>ddd</p>', '<p>dddddd</p>', '<p>dddd</p>', NULL, '<p>dddddddd</p>', NULL, NULL, '2022-09-05 14:41:50', '2022-09-05 14:41:50'),
(181, 'ka', 'Temo', '<p>cccccc</p>', '<p>cccccccc</p>', '<p>cccccccccccc</p>', '<p>cccccccc</p>', '<p>cccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 15:49:00', '2022-11-20 15:49:00'),
(182, 'ru', 'Temo', '<p>cccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccccc</p>', '<p>ccccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccc</p>', NULL, NULL, NULL, '2022-11-20 15:49:00', '2022-11-20 15:49:00'),
(183, 'us', 'Temo', '<p>ccccc</p>', '<p>cccc</p>', '<p>dddddd</p>', '<p>ccccc</p>', '<p>ccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 15:49:00', '2022-11-20 15:49:00'),
(184, 'ka', 'Temo', '<p>cccccc</p>', '<p>cccccccc</p>', '<p>cccccccccccc</p>', '<p>cccccccc</p>', '<p>cccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:00:11', '2022-11-20 16:00:11'),
(185, 'ru', 'Temo', '<p>cccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccccc</p>', '<p>ccccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:00:11', '2022-11-20 16:00:11'),
(186, 'us', 'Temo', '<p>ccccc</p>', '<p>cccc</p>', '<p>dddddd</p>', '<p>ccccc</p>', '<p>ccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:00:11', '2022-11-20 16:00:11'),
(190, 'ka', 'Temo', '<p>cccccc</p>', '<p>cccccccc</p>', '<p>cccccccccccc</p>', '<p>cccccccc</p>', '<p>cccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:02:06', '2022-11-20 16:02:06'),
(191, 'ru', 'Temo', '<p>cccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccccc</p>', '<p>ccccccccc</p>', '<p>cccccccccc</p>', '<p>ccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:02:06', '2022-11-20 16:02:06'),
(192, 'us', 'Temodddd', '<p>ccccc</p>', '<p>cccc</p>', '<p>dddddd</p>', '<p>ccccc</p>', '<p>ccccccc</p>', '<p>cccccccccc</p>', NULL, NULL, NULL, '2022-11-20 16:02:06', '2022-11-20 16:02:06'),
(199, 'ka', 'ss', '<p>sss</p>\n', '<p>ddd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-24 16:39:52', '2022-11-24 16:39:52'),
(200, 'ru', 'ss', '<p>ss</p>\n', '<p>ss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-24 16:39:52', '2022-11-24 16:39:52'),
(201, 'us', 'Temo', '<p>sss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-24 16:39:52', '2022-11-24 16:39:52'),
(202, 'ka', 'dd', '<p>ssss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:01:30', '2022-11-25 06:01:30'),
(203, 'ka', 'dd', '<p>ssss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:02:44', '2022-11-25 06:02:44'),
(204, 'us', 'dd', '<p>dd</p>\n', '<p>ddd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:02:44', '2022-11-25 06:02:44'),
(205, 'ru', 'ddd', '<p>ddddd</p>\n', '<p>dddddddd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:02:44', '2022-11-25 06:02:44'),
(209, 'ka', 'dd', '<p>ssss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:03:56', '2022-11-25 06:03:56'),
(210, 'us', 'dd', '<p>dd</p>\n', '<p>ddd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:03:56', '2022-11-25 06:03:56'),
(211, 'ka', 'dd', '<p>ssss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:10:23', '2022-11-25 06:10:23'),
(212, 'ka', 'dd', '<p>ssss</p>\n', '<p>sss</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 06:10:47', '2022-11-25 06:10:47'),
(216, 'ka', 'fffffff', '<p>ffffffffff</p>\n', '<p>ffffffffff</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 14:14:38', '2022-11-25 14:14:38'),
(217, 'ru', 'dddddd', '<p>ffffffffffff</p>\n', '<p>ggggggggggggg</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 14:14:38', '2022-11-25 14:14:38'),
(218, 'us', 'Temo', '<p>dddd</p>\n', '<p>ddddddd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-25 14:14:38', '2022-11-25 14:14:38'),
(228, 'ka', 'dddd', '<p>ddddd</p>\n', '<p>ffffff</p>\n', '', '<p>ggggggg</p>\n', '', '', '<p>ggggggggg</p>\n', NULL, NULL, '2022-11-25 14:53:38', '2022-11-25 14:53:38'),
(229, 'ru', 'ffffff', '<p>eeeeeeee</p>\n', '<p>rrrrrrrr</p>\n', '', '<p>tttttttttt</p>\n', '', '', '<blockquote>\n<p>aaaaaaaa</p>\n</blockquote>\n', NULL, NULL, '2022-11-25 14:53:38', '2022-11-25 14:53:38'),
(230, 'us', 'Temoddd', '<p>ddddd</p>\n', '<p>dddddddddd</p>\n', '', '<p>dddddd</p>\n', '', '', '<p>dddddd</p>\n', NULL, NULL, '2022-11-25 14:53:38', '2022-11-25 14:53:38'),
(231, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:36:39', '2022-11-26 17:36:39'),
(232, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:36:39', '2022-11-26 17:36:39'),
(233, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:36:39', '2022-11-26 17:36:39'),
(234, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:09', '2022-11-26 17:37:09'),
(235, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:09', '2022-11-26 17:37:09'),
(236, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:09', '2022-11-26 17:37:09'),
(237, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:39', '2022-11-26 17:37:39'),
(238, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:39', '2022-11-26 17:37:39'),
(239, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:37:39', '2022-11-26 17:37:39'),
(240, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:12', '2022-11-26 17:38:12'),
(241, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:12', '2022-11-26 17:38:12'),
(242, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:12', '2022-11-26 17:38:12'),
(243, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:31', '2022-11-26 17:38:31'),
(244, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:31', '2022-11-26 17:38:31'),
(245, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:38:31', '2022-11-26 17:38:31'),
(246, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:45:44', '2022-11-26 17:45:44'),
(247, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:45:44', '2022-11-26 17:45:44'),
(248, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:45:44', '2022-11-26 17:45:44'),
(249, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:45', '2022-11-26 17:46:45'),
(250, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:45', '2022-11-26 17:46:45'),
(251, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:45', '2022-11-26 17:46:45'),
(252, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:49', '2022-11-26 17:46:49'),
(253, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:49', '2022-11-26 17:46:49'),
(254, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:46:49', '2022-11-26 17:46:49'),
(255, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:47:48', '2022-11-26 17:47:48'),
(256, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:47:48', '2022-11-26 17:47:48'),
(257, 'us', 'dd', '<p>ddd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 17:47:48', '2022-11-26 17:47:48'),
(327, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:32', '2022-11-26 18:26:32'),
(328, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:32', '2022-11-26 18:26:32'),
(329, 'us', 'dddddddd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:32', '2022-11-26 18:26:32'),
(330, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:54', '2022-11-26 18:26:54'),
(331, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:54', '2022-11-26 18:26:54'),
(332, 'us', 'ddddddddd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:26:54', '2022-11-26 18:26:54'),
(333, 'ka', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:27:13', '2022-11-26 18:27:13'),
(334, 'ru', 'dd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:27:13', '2022-11-26 18:27:13'),
(335, 'us', 'dddddddddd', '<p>dd</p>\n', '<p>dd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:27:13', '2022-11-26 18:27:13'),
(342, 'ka', 'sds', '<p>dfd fdf d</p>\n', '<p>&nbsp;df df d</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:43:49', '2022-11-26 18:43:49'),
(343, 'ru', 'd fdf', '<p>&nbsp;dsfdasf ds f</p>\n', '<p>&nbsp;df df df&nbsp;</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:43:49', '2022-11-26 18:43:49'),
(344, 'us', 'dd Dddlll Gggg', '<p>dds</p>\n', '<p>sdsd</p>\n', '', '', '', '', '', NULL, NULL, '2022-11-26 18:43:49', '2023-01-09 18:03:45'),
(345, 'ka', 'frrewfrewf', '<p>freferfrewfwre</p>\n', '<p>frwqfrewfrewf</p>\n', '', '<p>rewfwrefreffefrw</p>\n', '', '', '', NULL, NULL, '2022-12-25 03:39:17', '2022-12-25 03:39:17'),
(346, 'ru', 'refrewfrwefrw', '<p>erwfrewfewrfre</p>\n', '<p>frefwefrewf</p>\n', '', '<p>refrefewrf</p>\n', '', '', '', NULL, NULL, '2022-12-25 03:39:17', '2022-12-25 03:39:17'),
(347, 'us', 'test 2', '<p>feewfref</p>\n', '<p>fefrfrf</p>\n', '', '<p>ffrfrfrfrf</p>\n', '', '', '', NULL, NULL, '2022-12-25 03:39:18', '2022-12-25 03:39:18'),
(348, 'ka', 'ddddddddddd', '<p>ddddddddddd</p>\n', '<p>ddddddddddd</p>\n', '', '<p>dddddddddd</p>\n', '', '', '', NULL, NULL, '2022-12-25 06:50:20', '2022-12-25 06:50:20'),
(349, 'ru', 'ddddddd', '<p>ddddddddd</p>\n', '<p>ddddddddddd</p>\n', '', '<p>dddddddd</p>\n', '', '', '', NULL, NULL, '2022-12-25 06:50:20', '2022-12-25 06:50:20'),
(350, 'us', 'Temo dddddd', '<p>dddddd</p>\n', '<p>ddddddddddd</p>\n', '', '<p>ddddddddddd</p>\n', '', '', '', NULL, NULL, '2022-12-25 06:50:20', '2022-12-25 06:50:20'),
(351, 'ka', 'jjjjjjjjj', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:17', '2023-01-09 18:51:17'),
(352, 'ru', 'jjjjjjjjjjjjj', '<p>jjjjjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:17', '2023-01-09 18:51:17'),
(353, 'us', 'Temo', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:17', '2023-01-09 18:51:17'),
(354, 'ka', 'jjjjjjjjj', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(355, 'ru', 'jjjjjjjjjjjjj', '<p>jjjjjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(356, 'us', 'Temonnn', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(357, 'ka', 'jjjjjjjjj', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(358, 'ru', 'jjjjjjjjjjjjj', '<p>jjjjjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(359, 'us', 'Temonnn', '<p>jjjjjjjjjjj</p>\n', '<p>jjjjjjjjjjjjjjjj</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 18:51:30', '2023-01-09 18:51:30'),
(360, 'ka', '     nmmmmmmmmmmmmm', '<p>mmmmmmmmmmmmm</p>\n', '<p>nnnnnnnnn</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 21:02:07', '2023-01-09 21:02:07'),
(361, 'ru', 'nnnnnnn', '<p>nnnnnnnn</p>\n', '<p>nnnnnnnnnnn</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 21:02:07', '2023-01-09 21:02:07'),
(362, 'us', 'lllllllll', '<p>;;;;;;;;lllllllll</p>\n', '<p>kkkkkkkkkkkk</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 21:02:07', '2023-01-09 21:02:07'),
(366, 'ka', 'xxxxxx', '<p>xxxxxxxx</p>\n', '<p>cccccccc</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 22:57:09', '2023-01-09 22:57:09'),
(367, 'ru', 'aaaaaa', '<p>aaaaaa</p>\n', '<p>aaaaaaaaa</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 22:57:09', '2023-01-09 22:57:09'),
(368, 'us', 'Temosssss', '<p>xxxxxxx</p>\n', '<p>ssssssssss</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 22:57:09', '2023-01-09 22:57:09'),
(375, 'ka', 'fffff', '<p>fffffff</p>\n', '<p>fffffffff</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 23:04:53', '2023-01-09 23:04:53'),
(376, 'ru', 'yyyyy', '<p>yyyyyyyy</p>\n', '<p>yyyyyyyyy</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 23:04:53', '2023-01-09 23:04:53'),
(377, 'us', 'Temoccccccccccc', '<p>ccccc</p>\n', '<p>ccccccc</p>\n', '', '', '', '', '', NULL, NULL, '2023-01-09 23:04:53', '2023-01-09 23:04:53');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_bisneses`
--

CREATE TABLE `locale_bisneses` (
  `id` int NOT NULL,
  `locale` varchar(191) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `short_description` varchar(191) DEFAULT NULL,
  `text` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `locale_bisneses`
--

INSERT INTO `locale_bisneses` (`id`, `locale`, `title`, `short_description`, `text`, `created_at`, `updated_at`) VALUES
(1, 'ka', 'dddddddd', '<p>dddddddd</p>\n', '<p>dddddddddd</p>\n', '2022-12-02 08:27:01', '2022-12-02 08:27:01'),
(2, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-02 08:27:01', '2022-12-02 08:27:01'),
(3, 'us', 'dd', '<p>dddd</p>\n', '<p>dddddd</p>\n', '2022-12-02 08:27:01', '2022-12-02 08:27:01'),
(4, 'ka', 'dddddddd', '<p>dddddddd</p>\n', '<p>dddddddddd</p>\n', '2022-12-02 08:27:29', '2022-12-02 08:27:29'),
(5, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-02 08:27:29', '2022-12-02 08:27:29'),
(6, 'us', 'dd', '<p>dddd</p>\n', '<p>dddddd</p>\n', '2022-12-02 08:27:30', '2022-12-02 08:27:30'),
(7, 'ka', 'dddddddd', '<p>dddddddd</p>\n', '<p>dddddddddd</p>\n', '2022-12-02 08:27:57', '2022-12-02 08:27:57'),
(8, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-02 08:27:57', '2022-12-02 08:27:57'),
(9, 'us', 'dd', '<p>dddd</p>\n', '<p>dddddd</p>\n', '2022-12-02 08:27:57', '2022-12-02 08:27:57'),
(10, 'ka', 'wcwcq', '<p>cewcewqc</p>\n', '<p>ewcewc</p>\n', '2022-12-02 08:41:04', '2022-12-02 08:41:04'),
(11, 'ru', 'cewqcwqc', '<p>cwdcewcewr</p>\n', '<p>wecewc</p>\n', '2022-12-02 08:41:04', '2022-12-02 08:41:04'),
(12, 'us', 'cdcdc', '<p>dcdcdwac</p>\n', '<p>cdwcewcqc</p>\n', '2022-12-02 08:41:04', '2022-12-02 08:41:04'),
(13, 'ka', 'wcwcq', '<p>ewcewc</p>\n', '<p>ewcewc</p>\n', '2022-12-02 08:43:28', '2022-12-05 09:24:27'),
(14, 'ru', 'cewqcwqc', '<p>ewcewc</p>\n', '<p>ewcewc</p>\n', '2022-12-02 08:43:28', '2022-12-05 09:25:00'),
(15, 'us', 'Sveri advencher camp', '<p>ewcewc</p>\n', '<p>ewcewc</p>\n', '2022-12-02 08:43:28', '2022-12-21 05:04:01'),
(16, 'ka', 'dddddssssssssss', '<p>dddddddsssssssssss</p>\n', '<p>dddddddsssssssssss</p>\n', '2022-12-05 02:11:17', '2022-12-05 09:27:42'),
(17, 'ru', 'ddddddssssssss', '<p>dddddddsssssssssss</p>\n', '<p>dddddddsssssssssss</p>\n', '2022-12-05 02:11:17', '2022-12-05 09:27:42'),
(18, 'us', 'Temoddddddddddddssssssss', '<p>dddddddsssssssssss</p>\n', '<p>dddddddsssssssssss</p>\n', '2022-12-05 02:11:17', '2022-12-05 09:27:42'),
(19, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:16:57', '2022-12-05 02:16:57'),
(20, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:16:57', '2022-12-05 02:16:57'),
(21, 'us', 'Temol', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:16:57', '2022-12-05 02:16:57'),
(22, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:20:39', '2022-12-05 02:20:39'),
(23, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:20:39', '2022-12-05 02:20:39'),
(24, 'us', 'Temold', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:20:39', '2022-12-05 02:20:39'),
(25, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:22:30', '2022-12-05 02:22:30'),
(26, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:22:30', '2022-12-05 02:22:30'),
(27, 'us', 'Temoldm', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:22:30', '2022-12-05 02:22:30'),
(28, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:40:33', '2022-12-05 02:40:33'),
(29, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:40:33', '2022-12-05 02:40:33'),
(30, 'us', 'lTemoldm', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:40:33', '2022-12-05 02:40:33'),
(31, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:43:35', '2022-12-05 02:43:35'),
(32, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:43:35', '2022-12-05 02:43:35'),
(33, 'us', 'lTemoldmd', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:43:35', '2022-12-05 02:43:35'),
(34, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:44:13', '2022-12-05 02:44:13'),
(35, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:44:13', '2022-12-05 02:44:13'),
(36, 'us', 'lTemoldmdd', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:44:13', '2022-12-05 02:44:13'),
(37, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:57:40', '2022-12-05 02:57:40'),
(38, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:57:40', '2022-12-05 02:57:40'),
(39, 'us', 'lTemoldmddd', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 02:57:40', '2022-12-05 02:57:40'),
(40, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:03:01', '2022-12-05 03:03:01'),
(41, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:03:01', '2022-12-05 03:03:01'),
(42, 'us', 'lTemoldmddd,', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:03:01', '2022-12-05 03:03:01'),
(43, 'ka', 'ddddd', '<p>ddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:04:32', '2022-12-05 03:04:32'),
(44, 'ru', 'dddddd', '<p>dddddd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:04:32', '2022-12-05 03:04:32'),
(45, 'us', 'lTemoldmddd,s', '<p>dd</p>\n', '<p>ddddddd</p>\n', '2022-12-05 03:04:32', '2022-12-05 03:04:32'),
(46, 'ka', 'fffffffff', '<p>ffffffffffffffff</p>\n', '<p>ffffffffffffffff</p>\n', '2022-12-25 07:28:36', '2022-12-25 07:29:16'),
(47, 'ru', 'fffffffff', '<p>ffffffffffffffff</p>\n', '<p>ffffffffffffffff</p>\n', '2022-12-25 07:28:36', '2022-12-25 07:29:16'),
(48, 'us', 'fffffff', '<p>ffffffffffffffff</p>\n', '<p>ffffffffffffffff</p>\n', '2022-12-25 07:28:36', '2022-12-25 07:29:16');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_events`
--

CREATE TABLE `locale_events` (
  `id` int NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `short_description` varchar(191) DEFAULT NULL,
  `text` text,
  `info` text,
  `locale` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `locale_events`
--

INSERT INTO `locale_events` (`id`, `title`, `short_description`, `text`, `info`, `locale`, `created_at`, `updated_at`) VALUES
(22, 'qqqqqq editing', '<p>wwwwww&nbsp;editing</p>\n', '<p>eeeeeeeeeee&nbsp;editing</p>\n', '<p>editing</p>\n', 'ka', '2022-12-17 16:50:07', '2022-12-17 18:41:03'),
(23, 'hhhhhhhh editing', '<p>hhhhhh&nbsp;editing</p>\n', '<p>cccccc&nbsp;editing</p>\n', '<p>editing</p>\n', 'ru', '2022-12-17 16:50:07', '2022-12-17 18:41:03'),
(24, 'Temo Samso', '<p>fffffff&nbsp;editing</p>\n', '<p>gggggggggediting</p>\n', '<p>editing</p>\n', 'us', '2022-12-17 16:50:07', '2022-12-17 18:42:54'),
(25, 'ffffffffffff', '<p>ffffffffffffffffffff</p>\n', '<p>ffffffffffffffffff</p>\n', '<p>fffffffffffffffffffffff</p>\n', 'ka', '2022-12-22 07:59:17', '2022-12-22 07:59:17'),
(26, 'sdsadsad', '<p>dsssssssssssssssssss</p>\n', '<p>ddddddddddddddddddd</p>\n', '<p>ffffffffffff</p>\n', 'ru', '2022-12-22 07:59:17', '2022-12-22 07:59:17'),
(27, 'Dry-tolling fest', '<p>Excepteur sint occaecat cupidatat non proident, consectetur adipiscing elit, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo.</p>\n', '<p>Quis autem vel eum iure reprehenderit, nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</p>\n\n<p>Ut enim ad minim veniam, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, velit esse cillum dolore eu fugiat nulla pariatur.</p>\n\n<p>Nemo enim ipsam voluptatem, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, sunt in culpa qui officia deserunt mollit anim id est laborum!</p>\n\n<p>Ut enim ad minim veniam, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, ut et voluptates repudiandae sint et molestiae non recusandae. Duis aute irure dolor in reprehenderit in voluptate, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, sunt in culpa qui officia deserunt mollit anim id est laborum! Sed ut perspiciatis, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat? Ut enim ad minima veniam, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, obcaecati cupiditate non...</p>\n', '<p>Excepteur sint occaecat cupidatat non proident, consectetur adipiscing elit, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo.</p>\n\n<p>Excepteur sint occaecat cupidatat non proident, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<p>Itaque earum rerum hic tenetur a sapiente delectus, nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, nisi ut aliquid ex ea commodi consequatur.</p>\n\n<p>Nemo enim ipsam voluptatem, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, velit esse cillum dolore eu fugiat nulla pariatur? Quis autem vel eum iure reprehenderit, nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit in voluptate, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem eum fugiat, quo voluptas nulla pariatur. Et harum quidem rerum facilis est et expedita distinctio, consectetur adipiscing elit, ut...</p>\n', 'us', '2022-12-22 07:59:17', '2022-12-24 04:14:46');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_films`
--

CREATE TABLE `locale_films` (
  `id` int NOT NULL,
  `locale` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `short_description` varchar(191) DEFAULT NULL,
  `text` text,
  `coutry` varchar(191) DEFAULT NULL,
  `issue_year` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `locale_films`
--

INSERT INTO `locale_films` (`id`, `locale`, `name`, `short_description`, `text`, `coutry`, `issue_year`, `created_at`, `updated_at`) VALUES
(1, 'us', 'test name us 1', 'test description us', 'test text us', NULL, NULL, NULL, NULL),
(2, 'ka', 'test name ka 1', 'test description ka', 'test text ka', NULL, NULL, NULL, NULL),
(3, 'ru', 'test name ru 1', 'test description ru', 'test text ru', NULL, NULL, NULL, NULL),
(4, 'us', 'test 2 us', 'test description 2', NULL, 'USA', '2019', NULL, NULL),
(5, 'ka', 'test 2 ka', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'ru', 'test 2 ru', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'us', 'test 3 us', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'ka', 'test 3 ka', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'ru', 'test 3 ru', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'us', 'us test 4', 'us description test 4', NULL, NULL, NULL, NULL, NULL),
(11, 'ka', 'ka test 4', 'ka description test 4', NULL, NULL, NULL, NULL, NULL),
(12, 'ru', 'ru test 4', 'ru description test 4', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `locale_mounts`
--

CREATE TABLE `locale_mounts` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `how_get` text COLLATE utf8mb4_unicode_ci,
  `best_time` text COLLATE utf8mb4_unicode_ci,
  `what_need` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `locale_mounts`
--

INSERT INTO `locale_mounts` (`id`, `locale`, `title`, `short_description`, `text`, `how_get`, `best_time`, `what_need`, `created_at`, `updated_at`) VALUES
(1, 'us', 'us test mount 1', 'test description us', 'test test us', 'how get us', 'best timr us', 'what need us', NULL, NULL),
(2, 'ka', 'ka test mount 1', 'test description ka', 'test test ka', 'how get ka', 'best timr ka', 'what need ka', NULL, NULL),
(3, 'ru', 'ru test mount 1', 'test description ru', 'test test ur', 'how get ru', 'best timr ru', 'what need ru', NULL, NULL),
(4, 'ka', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:18:42', '2022-11-27 08:18:42'),
(5, 'ru', 'sss', '<p>sss</p>', '<p>sss</p>', NULL, NULL, NULL, '2022-11-27 08:18:42', '2022-11-27 08:18:42'),
(6, 'ka', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:18:54', '2022-11-27 08:18:54'),
(7, 'ru', 'sss', '<p>sss</p>', '<p>sss</p>', NULL, NULL, NULL, '2022-11-27 08:18:54', '2022-11-27 08:18:54'),
(8, 'us', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:18:54', '2022-11-27 08:18:54'),
(9, 'ka', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:19:34', '2022-11-27 08:19:34'),
(10, 'ru', 'sss', '<p>sss</p>', '<p>sss</p>', NULL, NULL, NULL, '2022-11-27 08:19:34', '2022-11-27 08:19:34'),
(11, 'us', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:19:34', '2022-11-27 08:19:34'),
(12, 'ka', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:20:32', '2022-11-27 08:20:32'),
(13, 'ru', 'sss', '<p>sss</p>', '<p>sss</p>', NULL, NULL, NULL, '2022-11-27 08:20:32', '2022-11-27 08:20:32'),
(14, 'us', 'ss', '<p>ss</p>', '<p>ss</p>', NULL, NULL, NULL, '2022-11-27 08:20:32', '2022-11-27 08:20:32');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_products`
--

CREATE TABLE `locale_products` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `locale_products`
--

INSERT INTO `locale_products` (`id`, `locale`, `title`, `short_description`, `text`, `created_at`, `updated_at`) VALUES
(1, 'us', 'us prodddd', '<p>dsdvfvcccccccccccc</p>', '<p>us product 1 text</p>', NULL, '2022-11-30 05:37:15'),
(2, 'ru', 'us proddddfffffffffffffffffffff', '<p>dsdvfvcccccccccccc</p>', '<p>us product 1 text</p>', NULL, '2022-11-30 05:40:17'),
(3, 'ka', 'us prodddd', '<p>dsdvfvcccccccccccc</p>', '<p>us product 1 text</p>', NULL, '2022-11-30 05:39:46'),
(10, 'us', 'us prod 22222ffffffff', '<p>ffffffffff</p>', '<p>us product 2 textfffffff</p>', NULL, '2022-11-30 05:47:11'),
(11, 'ru', 'xxxus prod 2', '<p>xxxxxx</p>', '<p>us product 2 textxxxxxxx</p>', NULL, '2022-11-30 05:47:11'),
(12, 'ka', 'us prod 2sssssss', '<p>sssssssssss</p>', '<p>us product 2 textssssssssss</p>', NULL, '2022-11-30 05:47:11'),
(24, 'ka', 'dcd', '<p>ccc</p>', '<p>dcd</p>', '2022-11-29 15:08:53', '2022-11-29 15:08:53'),
(25, 'ru', 'dcdc', '<p>dcdc</p>', '<p>dcd</p>', '2022-11-29 15:08:53', '2022-11-29 15:08:53'),
(26, 'us', 'Temodcdcd', '<p>dsdc</p>', '<p>dcdc</p>', '2022-11-29 15:08:53', '2022-11-29 15:08:53');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_services`
--

CREATE TABLE `locale_services` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `locale_services`
--

INSERT INTO `locale_services` (`id`, `locale`, `title`, `short_description`, `text`, `created_at`, `updated_at`) VALUES
(1, 'us', 'test service us 1', 'test description us', 'test text q us', NULL, NULL),
(2, 'ru', 'test service ru 1', 'test description ru', 'test text q ru', NULL, NULL),
(3, 'ka', 'test service ka 1', 'test description ka', 'test text q ka', NULL, NULL),
(126, 'ka', 'dddd', '<p>ffffffffff</p>\n', '<p>ggggggggg</p>\n', '2022-12-05 03:29:23', '2022-12-05 03:29:23'),
(127, 'ru', 'gggg', '<p>ggggggggg</p>\n', '<p>ggggggggggggggggg</p>\n', '2022-12-05 03:29:23', '2022-12-05 03:29:23'),
(128, 'us', 'Temo123', '<p>ffffff</p>\n', '<p>fffffffffff</p>\n', '2022-12-05 03:29:23', '2022-12-05 03:29:23'),
(129, 'ka', 'სსსსსსსსსს', '<p>სსსსსსსსსსსსსსსს</p>\n', '<p>სსსსსსსსსსსსსსსს</p>\n', '2022-12-05 15:16:27', '2022-12-05 16:27:08'),
(130, 'ru', 'სსსსსსს', '<p>სსსსსსსსსსსსსსსს</p>\n', '<p>სსსსსსსსსსსსსსსს</p>\n', '2022-12-05 15:16:27', '2022-12-05 16:27:08'),
(131, 'us', 'Temo Samffff', '<p>სსსსსსსსსსსსსსსს</p>\n', '<p>სსსსსსსსსსსსსსსს</p>\n', '2022-12-05 15:16:27', '2022-12-05 16:27:08'),
(132, 'ka', 'fffffffffff', '<p>fffffffffffffffffffffffff</p>\n', '<p>ffffffffffffffffffffffffff</p>\n', '2022-12-16 09:52:39', '2022-12-16 09:52:39'),
(133, 'ru', 'fffffffffff', '<p>ffffffffffffffff</p>\n', '<p>ffffffffffffff</p>\n', '2022-12-16 09:52:39', '2022-12-16 09:52:39'),
(134, 'us', 'Temo', '<p>tttttttt</p>\n', '<p>tttttttttt</p>\n', '2022-12-16 09:52:39', '2022-12-16 09:52:39');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_sites`
--

CREATE TABLE `locale_sites` (
  `id` int NOT NULL,
  `locale` varchar(191) DEFAULT NULL,
  `guid_title` varchar(191) DEFAULT NULL,
  `guid_description` varchar(191) DEFAULT NULL,
  `guid_short_description` varchar(191) DEFAULT NULL,
  `films_title` varchar(191) DEFAULT NULL,
  `films_description` varchar(191) DEFAULT NULL,
  `films_short_description` varchar(191) DEFAULT NULL,
  `forum_title` varchar(191) DEFAULT NULL,
  `forum_description` varchar(191) DEFAULT NULL,
  `forum_short_description` varchar(191) DEFAULT NULL,
  `shop_title` varchar(191) DEFAULT NULL,
  `shop_description` varchar(191) DEFAULT NULL,
  `shop_short_description` varchar(191) DEFAULT NULL,
  `other_activity_description` varchar(191) DEFAULT NULL,
  `mount_description` varchar(191) DEFAULT NULL,
  `event_description` varchar(191) DEFAULT NULL,
  `tech_tips_description` varchar(191) DEFAULT NULL,
  `news_description` varchar(191) DEFAULT NULL,
  `index_gallery_description` varchar(191) DEFAULT NULL,
  `outdoor_description` varchar(191) DEFAULT NULL,
  `indoor_description` varchar(191) DEFAULT NULL,
  `ice_description` varchar(191) DEFAULT NULL,
  `topo_description` varchar(191) DEFAULT NULL,
  `what_we_do_description` varchar(191) DEFAULT NULL,
  `products_description` varchar(191) DEFAULT NULL,
  `services_description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `locale_sites`
--

INSERT INTO `locale_sites` (`id`, `locale`, `guid_title`, `guid_description`, `guid_short_description`, `films_title`, `films_description`, `films_short_description`, `forum_title`, `forum_description`, `forum_short_description`, `shop_title`, `shop_description`, `shop_short_description`, `other_activity_description`, `mount_description`, `event_description`, `tech_tips_description`, `news_description`, `index_gallery_description`, `outdoor_description`, `indoor_description`, `ice_description`, `topo_description`, `what_we_do_description`, `products_description`, `services_description`, `created_at`, `updated_at`) VALUES
(1, 'ka', 'kakakaddddd', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '<p>ggggggg</p>', '2022-12-14 09:43:42', '2023-01-10 19:07:36'),
(2, 'us', 'ffffffffffff', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '<p>sssssssss</p>', '2022-12-08 09:44:22', '2023-01-10 19:04:14'),
(3, 'ru', 'ro', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '<p>ddddddddd</p>', '2022-12-09 09:44:22', '2023-01-10 19:06:13');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_03_16_172210_create_roles_table', 1),
(4, '2019_03_16_172410_create_role_user_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2020_01_15_232900_create_social_accounts_table', 1),
(8, '2021_02_20_205157_create_locale_articles_table', 1),
(9, '2021_02_20_205753_create_locale_products_table', 1),
(10, '2021_02_21_215723_create_sites_table', 1),
(11, '2021_03_18_103449_create_locale_services_table', 1),
(12, '2021_06_09_191117_create_locale_mounts_table', 1),
(13, '2021_11_04_072704_create_films_table', 1),
(14, '2021_12_03_055844_create_user_adreses_table', 1),
(15, '2022_05_08_184051_create_gallery_categories_table', 1),
(16, '2022_05_08_195936_create_permissions_table', 1),
(17, '2022_05_08_200222_create_users_permissions_table', 1),
(18, '2022_05_08_200321_create_users_roles_table', 1),
(19, '2022_05_08_205050_create_general_infos_table', 1),
(20, '2022_05_08_205104_create_general_info_articles_table', 1),
(21, '2022_05_08_205234_create_user_bans_table', 1),
(22, '2022_05_08_205305_create_user_complaints_table', 1),
(23, '2022_05_08_205322_create_user_followers_table', 1),
(24, '2022_05_08_205334_create_user_sites_table', 1),
(25, '2022_05_09_142741_create_roles_permissions_table', 1),
(26, '2022_05_10_162810_create_service_followers_table', 1),
(27, '2022_05_10_163002_create_regions_table', 1),
(28, '2022_05_10_163156_create_topics_table', 1),
(29, '2022_05_10_183058_create_mounts_table', 1),
(30, '2022_05_10_183208_create_services_table', 1),
(31, '2022_05_11_130859_create_product_categories_table', 1),
(32, '2022_05_11_130860_create_products_table', 1),
(33, '2022_05_11_130861_create_favorite_products_table', 1),
(34, '2022_05_11_130862_create_product_options_table', 1),
(35, '2022_05_11_130869_create_orders_table', 1),
(36, '2022_05_11_130870_create_order_products_table', 1),
(37, '2022_05_11_130873_create_service_images_table', 1),
(38, '2022_05_11_130879_create_articles_table', 1),
(39, '2022_05_11_130884_create_interested_events_table', 1),
(40, '2022_05_11_130886_create_sectors_table', 1),
(41, '2022_05_11_130887_create_sector_images_table', 1),
(42, '2022_05_11_130888_create_article_comments_table', 1),
(43, '2022_05_11_130892_create_mount_route_images_table', 1),
(44, '2022_05_11_130893_create_spot_rocks_images_table', 1),
(45, '2022_05_11_130894_create_gallery_images_table', 1),
(46, '2022_05_11_130895_create_product_images_table', 1),
(47, '2022_05_11_130896_create_mtps_table', 1),
(48, '2022_05_11_130897_create_routes_table', 1),
(49, '2022_05_11_130898_create_carts_table', 1),
(50, '2022_05_11_130902_create_mtp_pitchs_table', 1),
(51, '2022_05_11_162814_create_mounts_articles_table', 1),
(52, '2022_05_11_162831_create_regions_articles_table', 1),
(53, '2022_05_28_191905_create_user_requests_table', 1),
(54, '2022_05_28_191906_create_forum_posts_table', 1),
(55, '2022_05_28_191907_create_post_comments_table', 1),
(56, '2022_05_28_191908_create_post_likes_table', 1),
(57, '2022_05_28_191909_create_post_complaints_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mounts`
--

CREATE TABLE `mounts` (
  `id` bigint UNSIGNED NOT NULL,
  `demo_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `us_mount_id` bigint UNSIGNED NOT NULL,
  `ka_mount_id` bigint UNSIGNED NOT NULL,
  `ru_mount_id` bigint UNSIGNED NOT NULL,
  `map` text COLLATE utf8mb4_unicode_ci,
  `weather` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mounts`
--

INSERT INTO `mounts` (`id`, `demo_name`, `published`, `us_mount_id`, `ka_mount_id`, `ru_mount_id`, `map`, `weather`, `image`, `created_at`, `updated_at`) VALUES
(2, 'ss', NULL, 14, 12, 13, NULL, NULL, NULL, '2022-11-27 08:20:32', '2022-11-27 08:20:32');

-- --------------------------------------------------------

--
-- Структура таблицы `mount_route_images`
--

CREATE TABLE `mount_route_images` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `mtps`
--

CREATE TABLE `mtps` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `height` int DEFAULT NULL,
  `first_ascent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mtps`
--

INSERT INTO `mtps` (`id`, `name`, `text`, `height`, `first_ascent`, `author`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(3, 'Temo Samsonadze editing', '<p>sssssssssskkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk</p>', 666, 'ddd editing', 'ddddddddediting', 2, 25, '2022-12-14 18:59:47', '2023-01-19 08:17:01'),
(8, 'Temosss', '<p>mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm</p>', 666, 'ssss', 'ssssss', 1, 17, '2022-12-15 07:28:49', '2022-12-16 16:55:02'),
(9, 'Temo Samsonadze', '<p>dddddddddddddddddddddddddddddddddddddddddddddddddddddddd</p>', 444, 'dwcscds + decdcwdc', 'dfvcvrev + ewfewreqc', 3, 17, '2022-12-15 07:49:11', '2022-12-16 16:55:11'),
(10, 'fdsfdfve', NULL, NULL, NULL, NULL, 1, 24, '2022-12-15 17:23:14', '2022-12-15 17:23:14');

-- --------------------------------------------------------

--
-- Структура таблицы `mtp_pitchs`
--

CREATE TABLE `mtp_pitchs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `or_grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bolts` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_data` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_ascent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `mtp_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mtp_pitchs`
--

INSERT INTO `mtp_pitchs` (`id`, `name`, `text`, `grade`, `or_grade`, `bolts`, `author`, `creation_data`, `height`, `first_ascent`, `category`, `num`, `mtp_id`, `created_at`, `updated_at`) VALUES
(3, 'DelawareEditing', '<p>Editing</p>', '5c+', '6b', '4', 'Editing', '2022-12-07', '3', '2022-12-14', 'sport climbing', 3, 3, '2022-12-16 07:44:47', '2022-12-25 15:32:20'),
(5, 'Delaware', NULL, '5b', NULL, NULL, NULL, NULL, NULL, NULL, 'tred', 1, 3, '2022-12-16 17:26:15', '2022-12-25 15:32:20'),
(6, 'ccccccc', NULL, '5c+', '6a', NULL, NULL, NULL, NULL, NULL, 'sport climbing', 2, 3, '2022-12-16 17:26:28', '2022-12-25 15:32:20'),
(7, 'gggg', NULL, '6a+', NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 4, 3, '2022-12-16 17:29:35', '2022-12-16 17:29:35'),
(8, 'Delaware', NULL, '6a', NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 1, 8, '2022-12-16 17:29:55', '2022-12-16 17:30:33'),
(9, 'bbbb', NULL, '7c', 'Project', NULL, NULL, NULL, NULL, NULL, 'sport climbing', 3, 8, '2022-12-16 17:30:05', '2022-12-16 17:30:33'),
(10, 'Delaware', NULL, '7c+', 'Project', NULL, NULL, NULL, NULL, NULL, 'sport climbing', 2, 8, '2022-12-16 17:30:14', '2022-12-16 17:30:33');

-- --------------------------------------------------------

--
-- Структура таблицы `option_images`
--

CREATE TABLE `option_images` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `option_images`
--

INSERT INTO `option_images` (`id`, `image`, `option_id`, `created_at`, `updated_at`) VALUES
(1, '1.jpg', 1, NULL, NULL),
(2, '2.jpg', 2, NULL, NULL),
(22, '2022-12-01-16-12-14.png', 34, '2022-12-01 12:58:15', '2022-12-01 12:58:15'),
(33, '2022-12-01-17-12-39.JPG', 36, '2022-12-01 13:22:40', '2022-12-01 13:22:40'),
(36, '2022-12-01-17-12-01.JPG', 36, '2022-12-01 13:27:01', '2022-12-01 13:27:01'),
(37, '2022-12-01-17-12-19.JPG', 36, '2022-12-01 13:27:20', '2022-12-01 13:27:20'),
(38, '2022-12-01-17-12-34.JPG', 36, '2022-12-01 13:27:35', '2022-12-01 13:27:35'),
(39, '2022-12-01-17-12-34.JPG', 37, '2022-12-01 13:29:35', '2022-12-01 13:29:35'),
(40, '2022-12-01-17-12-35.JPG', 37, '2022-12-01 13:29:35', '2022-12-01 13:29:35'),
(41, '2023-01-10-14-01-08-1673359628{796958}.png', 34, '2023-01-10 10:07:08', '2023-01-10 10:07:08'),
(42, '2023-01-10-14-01-08-1673359628{632241}.png', 34, '2023-01-10 10:07:08', '2023-01-10 10:07:08'),
(43, '2023-01-10-14-01-08-1673359628{17812}.png', 34, '2023-01-10 10:07:08', '2023-01-10 10:07:08');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `confirm` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `adres_id` bigint UNSIGNED NOT NULL,
  `treatment` int DEFAULT NULL,
  `treatment_data` timestamp NULL DEFAULT NULL,
  `preparation_for_shipment` int DEFAULT NULL,
  `preparation_for_shipment_data` timestamp NULL DEFAULT NULL,
  `ready_to_ship` int DEFAULT NULL,
  `ready_to_ship_data` timestamp NULL DEFAULT NULL,
  `order_has_been_sent` int DEFAULT NULL,
  `order_has_been_sent_data` timestamp NULL DEFAULT NULL,
  `transferred_to_the_delivery_service` int DEFAULT NULL,
  `transferred_to_the_delivery_service_data` timestamp NULL DEFAULT NULL,
  `delivered` int DEFAULT NULL,
  `delivered_data` timestamp NULL DEFAULT NULL,
  `payment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shiping` int DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `confirm`, `user_id`, `adres_id`, `treatment`, `treatment_data`, `preparation_for_shipment`, `preparation_for_shipment_data`, `ready_to_ship`, `ready_to_ship_data`, `order_has_been_sent`, `order_has_been_sent_data`, `transferred_to_the_delivery_service`, `transferred_to_the_delivery_service_data`, `delivered`, `delivered_data`, `payment`, `shiping`, `discount`, `created_at`, `updated_at`) VALUES
(121, 1, 3, 32, 1, '2022-11-11 22:00:49', 1, '2022-11-13 09:00:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'deliverd payment', 20, NULL, '2022-11-11 22:37:49', '2022-11-13 09:53:01');

-- --------------------------------------------------------

--
-- Структура таблицы `order_products`
--

CREATE TABLE `order_products` (
  `id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_option_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `order_products`
--

INSERT INTO `order_products` (`id`, `quantity`, `product_id`, `order_id`, `product_option_id`, `created_at`, `updated_at`) VALUES
(156, 1, 1, 121, 1, '2022-11-11 22:37:49', '2022-11-11 22:37:49');

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(3, 'test 1', '', NULL, NULL),
(4, 'test 2', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_comments`
--

CREATE TABLE `post_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_complaints`
--

CREATE TABLE `post_complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_likes`
--

CREATE TABLE `post_likes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `material` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mead_in_georgia` int DEFAULT NULL,
  `sale_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `us_product_id` bigint UNSIGNED NOT NULL,
  `ka_product_id` bigint UNSIGNED NOT NULL,
  `ru_product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `url_title`, `published`, `discount`, `material`, `mead_in_georgia`, `sale_type`, `user_id`, `category_id`, `us_product_id`, `ka_product_id`, `ru_product_id`, `created_at`, `updated_at`) VALUES
(1, 'test_product_1', 1, 20, 'metal + wood', 1, 'online order', 3, 1, 1, 3, 2, NULL, '2022-11-30 05:25:33'),
(4, 'us_prod_22222ffffffff', 1, NULL, NULL, 0, 'online order', 3, 1, 10, 12, 11, NULL, '2022-12-01 17:33:30'),
(5, 'temodcdcd', 0, NULL, NULL, 1, 'Custom production', NULL, 5, 26, 24, 25, '2022-11-29 15:08:53', '2022-11-29 15:08:53');

-- --------------------------------------------------------

--
-- Структура таблицы `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `product_categories`
--

INSERT INTO `product_categories` (`id`, `us_name`, `ru_name`, `ka_name`, `created_at`, `updated_at`) VALUES
(1, 'category 1 us', 'category 1 us', 'category 1 us', NULL, NULL),
(5, 'Accessoriessssss', 'Аксессуарыsss', 'აკსესუარებიss', '2022-11-12 01:35:23', '2022-11-28 18:29:56');

-- --------------------------------------------------------

--
-- Структура таблицы `product_comments`
--

CREATE TABLE `product_comments` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `text` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_options`
--

CREATE TABLE `product_options` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `product_options`
--

INSERT INTO `product_options` (`id`, `name`, `price`, `currency`, `quantity`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 'option 1', '10', '$', 0, 1, NULL, '2022-11-11 21:54:19'),
(2, 'option 2', '20', '$', 20, 1, NULL, '2022-11-09 11:35:36'),
(34, '-ff', '3', '₾', 1055, 4, '2022-12-01 12:58:14', '2023-01-10 10:07:08'),
(36, 'ttt', '7', '₾', 10, 4, '2022-12-01 13:22:38', '2022-12-01 13:22:38'),
(37, 'Temo', '44', '₾', 10, 4, '2022-12-01 13:29:34', '2022-12-01 13:29:34');

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_text` text COLLATE utf8mb4_unicode_ci,
  `ru_text` text COLLATE utf8mb4_unicode_ci,
  `ka_text` text COLLATE utf8mb4_unicode_ci,
  `map` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `us_name`, `ru_name`, `ka_name`, `us_text`, `ru_text`, `ka_text`, `map`, `created_at`, `updated_at`) VALUES
(1, 'Accessories', 'აკსესუარები', 'Аксессуары', NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2046.2279444999144!2d44.78844087084618!3d41.7076075832345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe7130cc7657d2697!2sBouldering%20club%20Tbilisi!5e0!3m2!1sen!2sge!4v1577311035167!5m2!1sen!2sge\" height=\"450\"  width=\"100%\" frameborder=\"0\" style=\"border:0;\" allowfullscreen=\"\"></iframe>', NULL, '2022-11-14 03:56:09'),
(2, 'test 2 us', 'длывьылв test 2 ru', 'სსსსს ჯჯჯჯtest 2 ka', '<p>test 2 us</p>', '<p>длывьылв test 2 ru</p>', '<p>სსსსს ჯჯჯჯtest 2 ka</p>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2046.2279444999144!2d44.78844087084618!3d41.7076075832345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe7130cc7657d2697!2sBouldering%20club%20Tbilisi!5e0!3m2!1sen!2sge!4v1577311035167!5m2!1sen!2sge\" height=\"450\"  width=\"100%\" frameborder=\"0\" style=\"border:0;\" allowfullscreen=\"\"></iframe>', NULL, '2022-11-14 03:55:35');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'user', NULL, 'Standart User', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(2, 'manager', NULL, 'Global content manager', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(3, 'ka_manager', NULL, 'Georgian Content mnager', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(4, 'ru_mannager', NULL, 'Russian content mennager', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(5, 'us_manager', NULL, 'English content manager', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(6, 'seller', NULL, 'Product Seller', '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(7, 'admin', NULL, 'Site Administrator', '2022-06-01 13:47:01', '2022-06-01 13:47:01');

-- --------------------------------------------------------

--
-- Структура таблицы `role_permission`
--

CREATE TABLE `role_permission` (
  `id` int DEFAULT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(NULL, 7, 3, '2023-01-07 12:14:43', '2023-01-07 12:14:43'),
(NULL, 7, 4, '2023-01-07 12:14:43', '2023-01-07 12:14:43');

-- --------------------------------------------------------

--
-- Структура таблицы `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `or_grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bolts` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_data` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_ascent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anchor_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `routes`
--

INSERT INTO `routes` (`id`, `name`, `text`, `grade`, `or_grade`, `bolts`, `author`, `creation_data`, `height`, `first_ascent`, `anchor_type`, `category`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(58, 'gvitynauria', NULL, '6b+', 'Project', NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 1, 16, '2022-12-14 08:36:39', '2022-12-16 16:52:42'),
(78, 'Delaware', NULL, '8b+', 'Project', NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 1, 22, '2022-12-14 17:23:20', '2022-12-14 17:23:20'),
(79, 'Delaware', NULL, '6b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 2, 16, '2023-01-06 07:25:42', '2023-01-06 07:25:42'),
(80, 'Delaware', '<p>sss</p>', '7a+', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 3, 16, '2023-01-06 07:26:07', '2023-01-06 07:26:07'),
(81, 'xxxx', NULL, '7a+', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 4, 16, '2023-01-06 07:26:51', '2023-01-06 07:26:51'),
(82, 'Delaware', NULL, '7a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sport climbing', 5, 16, '2023-01-06 07:27:03', '2023-01-06 07:27:03'),
(83, 'Delaware', '<p>ddcc</p>', '7a', NULL, '33', '3333', '2023-01-25', '333', '2023-01-20', 'Chain', 'sport climbing', 1, 25, '2023-01-18 18:31:17', '2023-01-18 18:31:17'),
(84, 'jnjmj', '<p>mkkj</p>', 'V5', NULL, NULL, 'grehtrh', '2023-01-17', '8989', '2023-01-24', NULL, 'bouldering', 2, 25, '2023-01-19 07:34:44', '2023-01-19 08:40:10');

-- --------------------------------------------------------

--
-- Структура таблицы `sale_codes`
--

CREATE TABLE `sale_codes` (
  `id` int NOT NULL,
  `discount` int NOT NULL,
  `code` varchar(191) NOT NULL,
  `one_time_code` int DEFAULT NULL,
  `action_data` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sale_codes`
--

INSERT INTO `sale_codes` (`id`, `discount`, `code`, `one_time_code`, `action_data`, `created_at`, `updated_at`) VALUES
(1, 10, 'dwdvvr', NULL, '2022-11-30 12:47:04', NULL, NULL),
(3, 2222, 'buskvercxa', 1, '2022-11-23 13:20:00', '2022-11-13 09:20:36', '2022-11-13 09:27:48');

-- --------------------------------------------------------

--
-- Структура таблицы `sectors`
--

CREATE TABLE `sectors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `all_day_in_shade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `all_day_in_sun` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_the_shade_afternoon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_the_shade_befornoon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_shade_after_10` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_shade_after_15` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_kids` int DEFAULT NULL,
  `for_family` int DEFAULT NULL,
  `wolking_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roof` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overhang` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slabby` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vertical` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `published` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sectors`
--

INSERT INTO `sectors` (`id`, `name`, `text`, `all_day_in_shade`, `all_day_in_sun`, `in_the_shade_afternoon`, `in_the_shade_befornoon`, `in_shade_after_10`, `in_shade_after_15`, `for_kids`, `for_family`, `wolking_time`, `roof`, `overhang`, `slabby`, `vertical`, `article_id`, `published`, `created_at`, `updated_at`) VALUES
(16, 'Temo123', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 101, '1', '2022-12-13 18:28:30', '2022-12-27 18:49:58'),
(17, 'Easy', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5 min', NULL, '1', NULL, '1', 101, '1', '2022-12-13 18:29:48', '2023-01-19 18:05:25'),
(22, 'Temo', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 101, '1', '2022-12-13 18:43:49', '2022-12-27 18:50:04'),
(24, 'Delaware', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 101, '1', '2022-12-15 17:22:37', '2023-01-06 15:44:08'),
(25, 'wedsfd', '', '1', '1', '1', '1', '1', '1', 1, 1, '30 min', '1', '1', '1', '1', 101, '1', '2022-12-27 18:50:31', '2023-01-19 08:44:28');

-- --------------------------------------------------------

--
-- Структура таблицы `sector_images`
--

CREATE TABLE `sector_images` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sector_images`
--

INSERT INTO `sector_images` (`id`, `title`, `image`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(7, NULL, '2022-12-13-22-12-30-000000.jpeg', 1, 16, '2022-12-13 18:28:30', '2022-12-16 17:15:26'),
(9, NULL, '2022-12-13-22-12-48-1670970588.jpg', 2, 17, '2022-12-13 18:29:49', '2022-12-16 17:13:02'),
(10, NULL, '2022-12-13-22-12-49-1670970589.jpeg', 1, 17, '2022-12-13 18:29:49', '2022-12-16 17:13:02'),
(14, NULL, '2022-12-13-22-12-49-1670971429{874593}.jpg', NULL, 22, '2022-12-13 18:43:49', '2022-12-13 18:43:49'),
(21, NULL, '2022-12-15-20-12-23-1671137123{792766}.jpg', 2, 16, '2022-12-15 16:45:23', '2022-12-16 17:15:26'),
(22, NULL, '2022-12-27-22-12-31-1672181431{275677}.png', NULL, 25, '2022-12-27 18:50:32', '2022-12-27 18:50:32');

-- --------------------------------------------------------

--
-- Структура таблицы `sector_local_images`
--

CREATE TABLE `sector_local_images` (
  `id` int NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sector_local_images`
--

INSERT INTO `sector_local_images` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(12, 'test 2', '2023-01-06-19-01-01-1673034361{915337}.png', '2022-11-23 12:40:17', '2023-01-06 15:46:01'),
(13, 'sss', '2022-11-24-10-11-38.jpeg', '2022-11-23 17:01:18', '2022-11-24 06:12:38');

-- --------------------------------------------------------

--
-- Структура таблицы `sector_local_image_sector`
--

CREATE TABLE `sector_local_image_sector` (
  `id` int NOT NULL,
  `sector_id` int DEFAULT NULL,
  `image_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sector_local_image_sector`
--

INSERT INTO `sector_local_image_sector` (`id`, `sector_id`, `image_id`, `created_at`, `updated_at`) VALUES
(15, 16, 13, '2023-01-06 07:07:18', '2023-01-06 07:07:18'),
(16, 17, 13, '2023-01-06 07:07:18', '2023-01-06 07:07:18'),
(17, 22, 12, '2023-01-06 15:46:01', '2023-01-06 15:46:01'),
(18, 24, 12, '2023-01-06 15:46:01', '2023-01-06 15:46:01');

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `us_service_id` bigint UNSIGNED NOT NULL,
  `ka_service_id` bigint UNSIGNED NOT NULL,
  `ru_service_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `url_title`, `published`, `us_service_id`, `ka_service_id`, `ru_service_id`, `created_at`, `updated_at`) VALUES
(29, 'temo_samffff', 1, 131, 129, 130, '2022-12-05 15:16:27', '2022-12-05 16:27:16'),
(30, 'temo', 0, 134, 132, 133, '2022-12-16 09:52:40', '2022-12-16 09:52:40');

-- --------------------------------------------------------

--
-- Структура таблицы `service_followers`
--

CREATE TABLE `service_followers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service_followers`
--

INSERT INTO `service_followers` (`id`, `email`, `service`, `user_id`, `created_at`, `updated_at`) VALUES
(28, 'admin@climbing.loc', 1, NULL, '2022-07-24 00:35:49', '2022-07-24 00:35:49'),
(29, 'samsonadze.temo9@gmail.com', 0, NULL, '2022-10-13 11:32:19', '2022-10-13 11:32:19'),
(30, 'samsonggggadze.temo9@gmail.com', 0, NULL, '2022-10-13 11:37:52', '2022-10-13 11:37:52');

-- --------------------------------------------------------

--
-- Структура таблицы `service_images`
--

CREATE TABLE `service_images` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service_images`
--

INSERT INTO `service_images` (`id`, `image`, `service_id`, `created_at`, `updated_at`) VALUES
(19, '2022-12-06-20-12-55.JPG', 29, '2022-12-06 16:34:56', '2022-12-06 16:34:56'),
(20, '2022-12-06-20-12-56.JPG', 29, '2022-12-06 16:34:56', '2022-12-06 16:34:56'),
(21, '2022-12-06-20-12-56.JPG', 29, '2022-12-06 16:34:57', '2022-12-06 16:34:57'),
(22, '2022-12-16-13-12-40-1671198760{923005}.jpg', 30, '2022-12-16 09:52:40', '2022-12-16 09:52:40'),
(23, '2022-12-16-13-12-40-1671198760{683485}.jpg', 30, '2022-12-16 09:52:40', '2022-12-16 09:52:40'),
(24, '2022-12-16-13-12-40-1671198760{204786}.jpg', 30, '2022-12-16 09:52:41', '2022-12-16 09:52:41');

-- --------------------------------------------------------

--
-- Структура таблицы `shiped_countries`
--

CREATE TABLE `shiped_countries` (
  `id` int NOT NULL,
  `country` varchar(191) DEFAULT NULL,
  `shiping_price` varchar(191) DEFAULT NULL,
  `free_shiping_price_after` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `shiped_countries`
--

INSERT INTO `shiped_countries` (`id`, `country`, `shiping_price`, `free_shiping_price_after`, `created_at`, `updated_at`) VALUES
(1, 'testt', '20', '1000', NULL, '2022-11-07 17:33:11'),
(3, 'ddf', '34', '1000', '2022-11-07 08:31:32', '2022-11-07 17:33:17');

-- --------------------------------------------------------

--
-- Структура таблицы `sites`
--

CREATE TABLE `sites` (
  `id` int NOT NULL,
  `map` text,
  `number` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `us_site_info` int NOT NULL,
  `ka_site_info` int NOT NULL,
  `ru_site_info` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sites`
--

INSERT INTO `sites` (`id`, `map`, `number`, `email`, `us_site_info`, `ka_site_info`, `ru_site_info`, `created_at`, `updated_at`) VALUES
(1, '<iframe src=\"https://www.google.com/maps/d/embed?mid=1vp_Rgh8UmlN7nZGFGHChh_rRy1t5GdOv&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', 'ddd', 'ddd', 2, 1, 3, '2022-12-11 18:09:26', '2023-01-20 15:32:08');

-- --------------------------------------------------------

--
-- Структура таблицы `site_social_links`
--

CREATE TABLE `site_social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `site_social_links`
--

INSERT INTO `site_social_links` (`id`, `title`, `url`, `created_at`, `updated_at`) VALUES
(2, 'test', 'https://www.youtube.com/watch?v=Qd-CwJa1SHE', '2022-12-11 07:59:51', '2022-12-11 07:59:51');

-- --------------------------------------------------------

--
-- Структура таблицы `spot_rocks_images`
--

CREATE TABLE `spot_rocks_images` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `spot_rocks_images`
--

INSERT INTO `spot_rocks_images` (`id`, `title`, `image`, `num`, `article_id`, `created_at`, `updated_at`) VALUES
(2, 'ffd', '2022-11-26-22-11-50.jpg', 2, 1, NULL, NULL),
(4, NULL, '2023-01-09-22-01-30-1673304690{504954}.png', NULL, 105, '2023-01-09 18:51:31', '2023-01-09 18:51:31'),
(12, NULL, '2023-01-10-14-01-27-1673359827{393637}.png', NULL, 1, '2023-01-10 10:10:27', '2023-01-10 10:10:27'),
(13, NULL, '2023-01-10-14-01-27-1673359827{691233}.png', NULL, 1, '2023-01-10 10:10:27', '2023-01-10 10:10:27'),
(14, NULL, '2023-01-18-21-01-58-1674076018{17845}.png', NULL, 101, '2023-01-18 17:06:58', '2023-01-18 17:06:58'),
(15, NULL, '2023-01-18-21-01-58-1674076018{868096}.png', NULL, 101, '2023-01-18 17:06:58', '2023-01-18 17:06:58');

-- --------------------------------------------------------

--
-- Структура таблицы `suport_local_bisneses`
--

CREATE TABLE `suport_local_bisneses` (
  `id` int NOT NULL,
  `url_title` varchar(191) DEFAULT NULL,
  `published_data` timestamp NULL DEFAULT NULL,
  `public_totaly` int DEFAULT NULL,
  `us_bisnes_id` int NOT NULL,
  `ka_bisnes_id` int NOT NULL,
  `ru_bisnes_id` int NOT NULL,
  `article_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `suport_local_bisneses`
--

INSERT INTO `suport_local_bisneses` (`id`, `url_title`, `published_data`, `public_totaly`, `us_bisnes_id`, `ka_bisnes_id`, `ru_bisnes_id`, `article_id`, `created_at`, `updated_at`) VALUES
(2, 'sveri_advencher_camp', NULL, 1, 15, 13, 14, 101, '2022-12-02 08:43:28', '2022-12-21 05:04:01'),
(13, 'fffffff', NULL, 1, 48, 46, 47, 1, '2022-12-25 07:28:36', '2022-12-25 07:29:16');

-- --------------------------------------------------------

--
-- Структура таблицы `suport_local_bisnes_images`
--

CREATE TABLE `suport_local_bisnes_images` (
  `id` int NOT NULL,
  `image` varchar(191) NOT NULL,
  `bisnes_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `suport_local_bisnes_images`
--

INSERT INTO `suport_local_bisnes_images` (`id`, `image`, `bisnes_id`, `created_at`, `updated_at`) VALUES
(19, '2022-12-06-21-12-51.JPG', 2, '2022-12-06 17:20:52', '2022-12-06 17:20:52'),
(20, '2022-12-06-21-12-52.JPG', 2, '2022-12-06 17:20:53', '2022-12-06 17:20:53'),
(27, '2022-12-06-21-12-51.jpg', 2, '2022-12-06 17:38:52', '2022-12-06 17:38:52'),
(28, '2022-12-06-21-12-52.jpeg', 2, '2022-12-06 17:38:52', '2022-12-06 17:38:52'),
(30, '2022-12-25-11-12-36-1671967716{454099}.png', 13, '2022-12-25 07:28:36', '2022-12-25 07:28:36');

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `id` bigint UNSIGNED NOT NULL,
  `public` int DEFAULT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `topics`
--

INSERT INTO `topics` (`id`, `public`, `us_name`, `ru_name`, `ka_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'outdoor', 'test 1 ru', 'test 1 ka', NULL, NULL),
(2, NULL, 'test 2 us', 'test 2 ru', 'test 2 ka', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `country`, `city`, `phone_number`, `image`, `lang`, `grade`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'user 1', NULL, NULL, NULL, NULL, NULL, 'us', 'uk', 'test_user_1@', NULL, '$2y$10$UALxFUAN8zCAwPd8vlRxS.A1IialHXTICjsbKuIEUj77OGwne.NmG', NULL, '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(2, 'user 2', NULL, NULL, NULL, NULL, NULL, 'ka', 'uiaa', 'test_user_2@', NULL, '$2y$10$GDo3g4I/EUYY9YBU5rNH8OIx.w5Q4un01z8N4RDopavikP8r3uJy2', NULL, '2022-06-01 13:47:01', '2022-06-01 13:47:01'),
(3, 'Temoooo', 'Samsonadze', 'Georgia', 'Tbilisi', '598457048', '2022-11-24-11-11-52.jpeg', 'us', 'yds', 'admin@climbing.loc', NULL, '$2y$10$tN71MOobPKGoWu4c3RjCUujkX71jySclEjg3VB4V01FiixwrWYOUO', NULL, '2022-06-01 13:47:02', '2022-11-24 07:45:52');

-- --------------------------------------------------------

--
-- Структура таблицы `user_adreses`
--

CREATE TABLE `user_adreses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entrance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_adreses`
--

INSERT INTO `user_adreses` (`id`, `name`, `country_id`, `city`, `strit`, `number`, `floor`, `flat`, `entrance`, `zip_code`, `user_id`, `created_at`, `updated_at`) VALUES
(32, 'Kvareli St. 16', 1, 'Tbilisi', 'dfdfd', 'fdfd', 'fdf', NULL, NULL, '19726', 3, '2022-11-07 18:37:29', '2022-11-07 19:04:10'),
(45, 'iujewkrewa', 1, 'iuerjtrew', '4qf4', 'yt', 'reyrey', 'ret', 'rgreg', 'tryer', 3, '2022-11-09 11:35:12', '2022-11-09 11:35:12');

-- --------------------------------------------------------

--
-- Структура таблицы `user_bans`
--

CREATE TABLE `user_bans` (
  `id` bigint UNSIGNED NOT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_time` timestamp NULL DEFAULT NULL,
  `ban_start_time` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_complaints`
--

CREATE TABLE `user_complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complainted_user_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_followers`
--

CREATE TABLE `user_followers` (
  `id` bigint UNSIGNED NOT NULL,
  `following_user_id` int DEFAULT NULL,
  `topic_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int NOT NULL,
  `favorite_outdoor` int DEFAULT NULL,
  `favorite_product` tinyint(1) DEFAULT NULL,
  `favorite_film` tinyint(1) DEFAULT NULL,
  `interested_event` tinyint(1) DEFAULT NULL,
  `add_new_sector` tinyint(1) DEFAULT NULL,
  `add_new_outdoor_spot` tinyint(1) DEFAULT NULL,
  `add_new_ice_spot` tinyint(1) DEFAULT NULL,
  `add_new_techtip` tinyint(1) DEFAULT NULL,
  `add_new_gym` tinyint(1) DEFAULT NULL,
  `add_new_product` tinyint(1) DEFAULT NULL,
  `add_new_service` tinyint(1) DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `favorite_outdoor`, `favorite_product`, `favorite_film`, `interested_event`, `add_new_sector`, `add_new_outdoor_spot`, `add_new_ice_spot`, `add_new_techtip`, `add_new_gym`, `add_new_product`, `add_new_service`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 3, NULL, '2022-11-08 07:13:15'),
(2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_permission`
--

CREATE TABLE `user_permission` (
  `id` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_permission`
--

INSERT INTO `user_permission` (`id`, `user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(0, 1, 3, '2022-12-28 21:10:55', '2022-12-28 21:10:55'),
(1, 1, 4, '2022-12-28 21:10:55', '2022-12-28 21:10:55'),
(2, 2, 4, NULL, NULL),
(3, 3, 4, '2023-01-07 18:39:41', '2023-01-07 18:39:41');

-- --------------------------------------------------------

--
-- Структура таблицы `user_requests`
--

CREATE TABLE `user_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `request_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `id` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(0, 1, 1, NULL, NULL),
(1, 3, 7, '2023-01-07 12:00:06', '2023-01-07 12:00:06');

-- --------------------------------------------------------

--
-- Структура таблицы `user_sites`
--

CREATE TABLE `user_sites` (
  `id` bigint UNSIGNED NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_sites`
--

INSERT INTO `user_sites` (`id`, `url`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'https://mailtrap.io/inboxes/ddcft', 3, NULL, '2022-11-13 17:18:34'),
(6, 'https://www.youtube.com/watch?v=Qd-CwJa1SHE', 3, '2022-11-13 17:18:12', '2022-11-13 17:18:12');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_url_title_unique` (`url_title`),
  ADD KEY `articles_us_article_id_foreign` (`us_article_id`),
  ADD KEY `articles_ka_article_id_foreign` (`ka_article_id`),
  ADD KEY `articles_ru_article_id_foreign` (`ru_article_id`);

--
-- Индексы таблицы `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_comment_complaints`
--
ALTER TABLE `article_comment_complaints`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_comment_queries`
--
ALTER TABLE `article_comment_queries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_comment_user`
--
ALTER TABLE `article_comment_user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_mount`
--
ALTER TABLE `article_mount`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_region`
--
ALTER TABLE `article_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regions_articles_article_id_foreign` (`article_id`),
  ADD KEY `regions_articles_region_id_foreign` (`region_id`);

--
-- Индексы таблицы `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_option_id_foreign` (`option_id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite_films`
--
ALTER TABLE `favorite_films`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite_outdoor_areas`
--
ALTER TABLE `favorite_outdoor_areas`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorite_products_product_id_foreign` (`product_id`),
  ADD KEY `favorite_products_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `film_categories`
--
ALTER TABLE `film_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `film_files`
--
ALTER TABLE `film_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `film_reitings`
--
ALTER TABLE `film_reitings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `film_tags`
--
ALTER TABLE `film_tags`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forum_posts_topic_id_foreign` (`topic_id`),
  ADD KEY `forum_posts_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `gallery_categories`
--
ALTER TABLE `gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_images_category_id_foreign` (`category_id`),
  ADD KEY `gallery_images_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `gallery_image_article`
--
ALTER TABLE `gallery_image_article`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `general_infos`
--
ALTER TABLE `general_infos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `general_info_article`
--
ALTER TABLE `general_info_article`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interested_events`
--
ALTER TABLE `interested_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interested_events_user_id_foreign` (`user_id`),
  ADD KEY `interested_events_id_foreign` (`event_id`) USING BTREE;

--
-- Индексы таблицы `locale_articles`
--
ALTER TABLE `locale_articles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_bisneses`
--
ALTER TABLE `locale_bisneses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_events`
--
ALTER TABLE `locale_events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_films`
--
ALTER TABLE `locale_films`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_mounts`
--
ALTER TABLE `locale_mounts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_products`
--
ALTER TABLE `locale_products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_services`
--
ALTER TABLE `locale_services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `locale_sites`
--
ALTER TABLE `locale_sites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mounts`
--
ALTER TABLE `mounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mounts_us_mount_id_foreign` (`us_mount_id`),
  ADD KEY `mounts_ka_mount_id_foreign` (`ka_mount_id`),
  ADD KEY `mounts_ru_mount_id_foreign` (`ru_mount_id`);

--
-- Индексы таблицы `mount_route_images`
--
ALTER TABLE `mount_route_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mount_route_images_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `mtps`
--
ALTER TABLE `mtps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mtps_sector_id_foreign` (`sector_id`);

--
-- Индексы таблицы `mtp_pitchs`
--
ALTER TABLE `mtp_pitchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mtp_pitchs_mtp_id_foreign` (`mtp_id`);

--
-- Индексы таблицы `option_images`
--
ALTER TABLE `option_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_option_id_foreign` (`option_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_adres_id_foreign` (`adres_id`);

--
-- Индексы таблицы `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_products_product_id_foreign` (`product_id`),
  ADD KEY `order_products_order_id_foreign` (`order_id`),
  ADD KEY `order_products_product_option_id_foreign` (`product_option_id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_comments_user_id_foreign` (`user_id`),
  ADD KEY `post_comments_post_id_foreign` (`post_id`);

--
-- Индексы таблицы `post_complaints`
--
ALTER TABLE `post_complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_complaints_user_id_foreign` (`user_id`),
  ADD KEY `post_complaints_post_id_foreign` (`post_id`);

--
-- Индексы таблицы `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_likes_user_id_foreign` (`user_id`),
  ADD KEY `post_likes_post_id_foreign` (`post_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `products_calegory_id_foreign` (`category_id`),
  ADD KEY `products_us_product_id_foreign` (`us_product_id`),
  ADD KEY `products_ka_product_id_foreign` (`ka_product_id`),
  ADD KEY `products_ru_product_id_foreign` (`ru_product_id`);

--
-- Индексы таблицы `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_comments`
--
ALTER TABLE `product_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_options_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `roles_permissions_permission_id_foreign` (`permission_id`);

--
-- Индексы таблицы `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routes_sector_id_foreign` (`sector_id`);

--
-- Индексы таблицы `sale_codes`
--
ALTER TABLE `sale_codes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sectors`
--
ALTER TABLE `sectors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sectors_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `sector_images`
--
ALTER TABLE `sector_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sector_images_sector_id_foreign` (`sector_id`);

--
-- Индексы таблицы `sector_local_images`
--
ALTER TABLE `sector_local_images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sector_local_image_sector`
--
ALTER TABLE `sector_local_image_sector`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_us_service_id_foreign` (`us_service_id`),
  ADD KEY `services_ka_service_id_foreign` (`ka_service_id`),
  ADD KEY `services_ru_service_id_foreign` (`ru_service_id`);

--
-- Индексы таблицы `service_followers`
--
ALTER TABLE `service_followers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service_images`
--
ALTER TABLE `service_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_images_service_id_foreign` (`service_id`);

--
-- Индексы таблицы `shiped_countries`
--
ALTER TABLE `shiped_countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `site_social_links`
--
ALTER TABLE `site_social_links`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `spot_rocks_images`
--
ALTER TABLE `spot_rocks_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spot_rocks_images_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `suport_local_bisneses`
--
ALTER TABLE `suport_local_bisneses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `suport_local_bisnes_images`
--
ALTER TABLE `suport_local_bisnes_images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `user_adreses`
--
ALTER TABLE `user_adreses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_adreses_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_bans`
--
ALTER TABLE `user_bans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_bans_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_complaints`
--
ALTER TABLE `user_complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_complaints_complainted_user_id_foreign` (`complainted_user_id`),
  ADD KEY `user_complaints_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_followers`
--
ALTER TABLE `user_followers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`user_id`,`permission_id`) USING BTREE,
  ADD KEY `users_permissions_permission_id_foreign` (`permission_id`);

--
-- Индексы таблицы `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_requests_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `users_roles_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `user_sites`
--
ALTER TABLE `user_sites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_sites_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT для таблицы `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `article_comment_complaints`
--
ALTER TABLE `article_comment_complaints`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `article_comment_queries`
--
ALTER TABLE `article_comment_queries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `article_comment_user`
--
ALTER TABLE `article_comment_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `article_mount`
--
ALTER TABLE `article_mount`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `article_region`
--
ALTER TABLE `article_region`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorite_films`
--
ALTER TABLE `favorite_films`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `favorite_outdoor_areas`
--
ALTER TABLE `favorite_outdoor_areas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `films`
--
ALTER TABLE `films`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `film_categories`
--
ALTER TABLE `film_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `film_files`
--
ALTER TABLE `film_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `film_reitings`
--
ALTER TABLE `film_reitings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `film_tags`
--
ALTER TABLE `film_tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `gallery_image_article`
--
ALTER TABLE `gallery_image_article`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `general_infos`
--
ALTER TABLE `general_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `general_info_article`
--
ALTER TABLE `general_info_article`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `interested_events`
--
ALTER TABLE `interested_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `locale_articles`
--
ALTER TABLE `locale_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=384;

--
-- AUTO_INCREMENT для таблицы `locale_bisneses`
--
ALTER TABLE `locale_bisneses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `locale_events`
--
ALTER TABLE `locale_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `locale_films`
--
ALTER TABLE `locale_films`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `locale_mounts`
--
ALTER TABLE `locale_mounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `locale_products`
--
ALTER TABLE `locale_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `locale_services`
--
ALTER TABLE `locale_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT для таблицы `locale_sites`
--
ALTER TABLE `locale_sites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `mounts`
--
ALTER TABLE `mounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `mount_route_images`
--
ALTER TABLE `mount_route_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mtps`
--
ALTER TABLE `mtps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `mtp_pitchs`
--
ALTER TABLE `mtp_pitchs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `option_images`
--
ALTER TABLE `option_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT для таблицы `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `post_complaints`
--
ALTER TABLE `post_complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `product_comments`
--
ALTER TABLE `product_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT для таблицы `sale_codes`
--
ALTER TABLE `sale_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `sectors`
--
ALTER TABLE `sectors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `sector_images`
--
ALTER TABLE `sector_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `sector_local_images`
--
ALTER TABLE `sector_local_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `sector_local_image_sector`
--
ALTER TABLE `sector_local_image_sector`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `service_followers`
--
ALTER TABLE `service_followers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `service_images`
--
ALTER TABLE `service_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `shiped_countries`
--
ALTER TABLE `shiped_countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `sites`
--
ALTER TABLE `sites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `site_social_links`
--
ALTER TABLE `site_social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `spot_rocks_images`
--
ALTER TABLE `spot_rocks_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `suport_local_bisneses`
--
ALTER TABLE `suport_local_bisneses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `suport_local_bisnes_images`
--
ALTER TABLE `suport_local_bisnes_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user_adreses`
--
ALTER TABLE `user_adreses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `user_bans`
--
ALTER TABLE `user_bans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_complaints`
--
ALTER TABLE `user_complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_followers`
--
ALTER TABLE `user_followers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_sites`
--
ALTER TABLE `user_sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ka_article_id_foreign` FOREIGN KEY (`ka_article_id`) REFERENCES `locale_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_ru_article_id_foreign` FOREIGN KEY (`ru_article_id`) REFERENCES `locale_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_us_article_id_foreign` FOREIGN KEY (`us_article_id`) REFERENCES `locale_articles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `article_region`
--
ALTER TABLE `article_region`
  ADD CONSTRAINT `regions_articles_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `regions_articles_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  ADD CONSTRAINT `favorite_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gallery_images_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `interested_events`
--
ALTER TABLE `interested_events`
  ADD CONSTRAINT `interested_events_article_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `interested_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mounts`
--
ALTER TABLE `mounts`
  ADD CONSTRAINT `mounts_ka_mount_id_foreign` FOREIGN KEY (`ka_mount_id`) REFERENCES `locale_mounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mounts_ru_mount_id_foreign` FOREIGN KEY (`ru_mount_id`) REFERENCES `locale_mounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mounts_us_mount_id_foreign` FOREIGN KEY (`us_mount_id`) REFERENCES `locale_mounts` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mount_route_images`
--
ALTER TABLE `mount_route_images`
  ADD CONSTRAINT `mount_route_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mtps`
--
ALTER TABLE `mtps`
  ADD CONSTRAINT `mtps_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mtp_pitchs`
--
ALTER TABLE `mtp_pitchs`
  ADD CONSTRAINT `mtp_pitchs_mtp_id_foreign` FOREIGN KEY (`mtp_id`) REFERENCES `mtps` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `option_images`
--
ALTER TABLE `option_images`
  ADD CONSTRAINT `product_images_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_adres_id_foreign` FOREIGN KEY (`adres_id`) REFERENCES `user_adreses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_products_product_option_id_foreign` FOREIGN KEY (`product_option_id`) REFERENCES `product_options` (`id`);

--
-- Ограничения внешнего ключа таблицы `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post_complaints`
--
ALTER TABLE `post_complaints`
  ADD CONSTRAINT `post_complaints_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_calegory_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ka_product_id_foreign` FOREIGN KEY (`ka_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ru_product_id_foreign` FOREIGN KEY (`ru_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_us_product_id_foreign` FOREIGN KEY (`us_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `product_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `roles_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sectors`
--
ALTER TABLE `sectors`
  ADD CONSTRAINT `sectors_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sector_images`
--
ALTER TABLE `sector_images`
  ADD CONSTRAINT `sector_images_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ka_service_id_foreign` FOREIGN KEY (`ka_service_id`) REFERENCES `locale_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_ru_service_id_foreign` FOREIGN KEY (`ru_service_id`) REFERENCES `locale_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_us_service_id_foreign` FOREIGN KEY (`us_service_id`) REFERENCES `locale_services` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `service_images`
--
ALTER TABLE `service_images`
  ADD CONSTRAINT `service_images_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `spot_rocks_images`
--
ALTER TABLE `spot_rocks_images`
  ADD CONSTRAINT `spot_rocks_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_adreses`
--
ALTER TABLE `user_adreses`
  ADD CONSTRAINT `user_adreses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_bans`
--
ALTER TABLE `user_bans`
  ADD CONSTRAINT `user_bans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_complaints`
--
ALTER TABLE `user_complaints`
  ADD CONSTRAINT `user_complaints_complainted_user_id_foreign` FOREIGN KEY (`complainted_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_permission`
--
ALTER TABLE `user_permission`
  ADD CONSTRAINT `users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_requests`
--
ALTER TABLE `user_requests`
  ADD CONSTRAINT `user_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `users_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_sites`
--
ALTER TABLE `user_sites`
  ADD CONSTRAINT `user_sites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
