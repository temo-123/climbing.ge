-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Янв 24 2023 г., 22:08
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
-- База данных: `climbing,loc_test_1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inst_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twit_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weather` text COLLATE utf8mb4_unicode_ci,
  `map` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `us_article_id` bigint UNSIGNED NOT NULL,
  `ka_article_id` bigint UNSIGNED NOT NULL,
  `ru_article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `category`, `url_title`, `fb_link`, `inst_link`, `google_link`, `twit_link`, `web_link`, `price_from`, `open_time`, `closed_time`, `weather`, `map`, `image`, `published`, `us_article_id`, `ka_article_id`, `ru_article_id`, `created_at`, `updated_at`) VALUES
(1, 'outdoor', 'Climbing_In_Sveri(Chiatura)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/sveri_georgia_611801?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fsveri_georgia_611801&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fsveri_georgia_611801&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/sveri_georgia_611801?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=12CPDODNpgnsWFAUlZRxj1sdNL_-64WPt&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', '2021-03-20-07-03-04', 1, 201, 1, 101, '2021-03-02 03:04:04', '2022-07-31 12:10:58'),
(6, 'outdoor', 'Climbing_In_Katskhi_(Chiatura)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/kats%e2%80%99khi_georgia_614096?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fkats%25e2%2580%2599khi_georgia_614096&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fkats%25e2%2580%2599khi_georgia_614096&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/kats%e2%80%99khi_georgia_614096?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1a-cqz5NYhP7Jk-voAaUxFrU9WImkVFq3\" width=\"100%\" height=\"480\"></iframe>', '2021-11-21-19-11-31.jpg', 1, 206, 6, 106, '2021-03-02 09:31:38', '2021-11-21 11:20:31'),
(7, 'outdoor', 'Urban_Climbing_In_Tbilisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/tbilisi%27s-sea_georgia_611714?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Ftbilisi%2527s-sea_georgia_611714&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Ftbilisi%2527s-sea_georgia_611714&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/tbilisi%27s-sea_georgia_611714?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1Nzu-J7c65ivpXrma5iAdWye1a7nhYslt\" width=\"100%\" height=\"480\"></iframe>', '2021-05-18-21-05-11', 1, 207, 7, 107, '2021-03-02 09:36:24', '2022-02-06 11:22:09'),
(8, 'outdoor', 'climbing_in_chiatura', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/chiat%27ura_georgia_615211?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/forecast/week/chiat%27ura_georgia_615211?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1LMcw-rOZ0wZ2p9mYwPy20UGsx43CSfq7\" width=\"100%\" height=\"480\"></iframe>', '2021-03-21-10-03-56', 1, 208, 8, 108, '2021-03-02 09:38:50', '2021-03-31 12:20:57'),
(9, 'outdoor', 'Climbing_In_Mestia_(Chalaadi)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/mestia_georgia_612987?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fmestia_georgia_612987&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fmestia_georgia_612987&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/mestia_georgia_612987?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2912.695062829864!2d42.74269431502385!3d43.110922895344814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDPCsDA2JzM5LjMiTiA0MsKwNDQnNDEuNiJF!5e0!3m2!1sru!2sge!4v1553756606635\" width=\"100%\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '2022-04-07-16-04-01.jpg', 1, 209, 9, 109, '2021-03-02 09:41:37', '2022-04-07 08:06:01'),
(10, 'outdoor', 'Climbing_In_Dzevri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/dzevri_georgia_614820?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fdzevri_georgia_614820&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fdzevri_georgia_614820&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/dzevri_georgia_614820?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d94516.67283665191!2d42.882576641842896!3d42.243374712162456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x405c96d19c1d1499%3A0xf6250f5c4966dc42!2sDzevri!5e0!3m2!1sru!2sge!4v1553759751853\" width=\"100%\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '2022-04-09-20-04-45.jpg', 1, 210, 10, 110, '2021-03-02 09:44:09', '2022-04-09 12:42:45'),
(11, 'outdoor', 'Climbing_In_Kutaisi(Choma)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/kutaisi_georgia_613607?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fkutaisi_georgia_613607&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fkutaisi_georgia_613607&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/kutaisi_georgia_613607?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d738.0248089064232!2d42.705723829207905!3d42.27640499099402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDLCsDE2JzM1LjEiTiA0MsKwNDInMjIuNiJF!5e0!3m2!1sru!2sge!4v1553760347864\" width=\"100%\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '2021-11-16-20-11-56.jpg', 1, 211, 11, 111, '2021-03-02 09:46:18', '2021-11-16 12:11:19'),
(12, 'outdoor', 'Climbing_In_Arsha', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/arsha_georgia_615681?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Farsha_georgia_615681&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Farsha_georgia_615681&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/arsha_georgia_615681?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1K_MJOpCrUNCaJmPM15OOxFDEDs5Fsq_u\" width=\"100%\" height=\"480\"></iframe>', '2022-04-07-16-04-08.jpg', 1, 212, 12, 112, '2021-03-02 09:51:02', '2022-09-05 05:29:35'),
(13, 'outdoor', 'Climbing_At_The_Black_Sea_Coast', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/gonio_georgia_614471?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Fgonio_georgia_614471&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Fgonio_georgia_614471&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/gonio_georgia_614471?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=1CnPlvzCsUXJLEsQLAmVasbGeA2Wxf6rT\" width=\"100%\" height=\"480\"></iframe>', '2021-04-11-11-04-06', 1, 213, 13, 113, '2021-03-02 09:53:23', '2021-07-24 14:03:06'),
(14, 'outdoor', 'Climbing_In_Birtvisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/tbisi_georgia_611713?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/forecast/week/tbisi_georgia_611713?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1aX61Qz6Yy5Kc9D6a_9l2C_jZWpalZpT5\" width=\"100%\" height=\"480\"></iframe>', '2022-04-07-15-04-00.jpg', 1, 214, 14, 114, '2021-03-02 09:55:39', '2022-04-07 07:58:00'),
(15, 'outdoor', 'Climbing_In_\"Tbilisi_Botanical_Garden\"', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/tbilisi_georgia_611717?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light&location_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily%2Ftbilisi_georgia_611717&location_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Ftbilisi_georgia_611717&nolocation_url=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fwidget%2Fdaily&nolocation_mainUrl=https%3A%2F%2Fwww.meteoblue.com%2Fen%2Fweather%2Fweek%2Findex&dailywidth=54&tracking=%3Futm_source%3Dweather_widget%26utm_medium%3Dlinkus%26utm_content%3Ddaily%26utm_campaign%3DWeather%252BWidget\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/tbilisi_georgia_611717?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4067.6210123340215!2d44.79905900852556!3d41.683552202948604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40440c8c64c1b6df%3A0xa8b4317b7d439ce2!2zNDHCsDQxJzAzLjgiTiA0NMKwNDcnNDYuOCJF!5e0!3m2!1sru!2sge!4v1553763372992\" width=\"100%\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '2021-04-07-20-04-15', 1, 215, 15, 115, '2021-03-02 10:29:06', '2021-10-08 09:51:16'),
(16, 'outdoor', 'Climbing_in_Navardzeti(chiatura)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/navardzeti_georgia_612730?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/forecast/week/navardzeti_georgia_612730?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=1XXvM_Sm5slow2QI-C19swgd5q7lvjr9v\" width=\"100%\" height=\"480\"></iframe>', '2021-04-07-20-04-06', 1, 216, 16, 116, '2021-03-02 10:31:44', '2022-04-07 08:11:25'),
(17, 'outdoor', 'climbing_in_tsdo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/tsdo_georgia_611533?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px;height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/tsdo_georgia_611533?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4147.2072490211085!2d44.627911500629224!3d42.692743585379354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDLCsDQxJzM4LjUiTiA0NMKwMzcnNDEuMSJF!5e0!3m2!1sru!2sge!4v1577652331760!5m2!1sru!2sge\" width=\"100%\" height=\"600\" frameborder=\"0\" style=\"border:0;\" allowfullscreen=\"\"></iframe>', '2021-04-08-19-04-20', 1, 217, 17, 117, '2021-03-02 10:36:35', '2021-06-01 11:31:49'),
(18, 'outdoor', 'climbing_to_caukhi_bace_camp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/pereval-chaukhi_georgia_865650?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 317px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/pereval-chaukhi_georgia_865650?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=19Oi7oeNqluJTwaS366g9pWjxpbzrI3aZ\" width=\"100%\" height=\"480\"></iframe>', '2021-03-20-07-03-09', 1, 218, 18, 118, '2021-03-02 10:39:31', '2021-06-01 11:32:26'),
(19, 'indoor', 'Bouldering_Club_Tbilisi', 'https://www.facebook.com/proclimberss', 'https://www.instagram.com/pro_climbers/', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6533.688218816857!2d44.78644046128929!3d41.71127438396913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe7130cc7657d2697!2sBouldering%20club%20Tbilisi!5e0!3m2!1sen!2sge!4v1633626391841!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', '2021-10-07-17-10-58.jpg', 1, 219, 19, 119, '2021-03-02 10:49:57', '2022-05-05 10:09:27'),
(20, 'indoor', 'S.K.Lucky', 'https://www.facebook.com/esca.lucky', 'https://www.instagram.com/s.k.lucky_climbing_gym_tbilisi/', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7083.1260692559945!2d44.74025210093168!3d41.718627227588314!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x15d722a3d7023b6!2sArena!5e0!3m2!1sen!2sge!4v1553855121785!5m2!1sen!2sge\" width=\"100%\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '2021-10-07-16-10-34.jpg', 1, 220, 20, 120, '2021-03-02 10:52:39', '2021-10-07 09:24:49'),
(21, 'indoor', 'vake_clibing_gym', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1_(2021-03-02-18-03-07).jpg', 0, 221, 21, 121, '2021-03-02 10:54:33', '2021-03-02 10:57:07'),
(22, 'ice', 'ice_climbing_in_gveleti_kazbegi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/gveleti_georgia_614324?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 317px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/gveleti_georgia_614324?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=11LORpFzfFZQtjteBmK6df3vQMqrGfKi2\" width=\"100%\" height=\"480\"></iframe>', '2021-03-19-20-03-35', 1, 222, 22, 122, '2021-03-19 12:23:10', '2021-03-19 12:41:30'),
(23, 'ice', 'ice_climbing_in_ateni_valley', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/biisi_georgia_615457?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 315px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/biisi_georgia_615457?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=1DhcIFztSJfWyEcq66PiZ8lWx7kR9JmDP\" width=\"100%\" height=\"480\"></iframe>', '2021-03-19-21-03-32', 1, 223, 23, 123, '2021-03-19 12:47:03', '2021-03-19 13:00:50'),
(24, 'other', 'slackline_in_georgia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1oFOhBPtcIAbR0YMq9XWWngdCw7dN0Gtr\" width=\"640\" height=\"480\"></iframe>', '2021-03-19-21-03-21', 1, 224, 24, 124, '2021-03-19 13:01:44', '2021-03-19 13:09:34'),
(25, 'mount_route', 'Javaxishvili_clasic_(3A)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-03-20-11-03-08.jpg', 1, 225, 25, 125, '2021-10-02 23:01:13', '2022-08-23 12:01:04'),
(26, 'outdoor', 'Climbing_near_Borjomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/k%e2%80%99imotesubani_georgia_613837?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 420px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/k%e2%80%99imotesubani_georgia_613837?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\" rel=\"noopener\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1zLk1GegB8K4QLJKiheI4D1odWy6rPffn\" width=\"100%\" height=\"480\"></iframe>', '2022-06-10-19-06-22.jpg', 1, 226, 26, 126, '2021-11-21 10:30:47', '2022-06-10 11:19:22'),
(27, 'news', 'New_climbing_sectors_near_Borjomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/d/u/0/embed?mid=1zLk1GegB8K4QLJKiheI4D1odWy6rPffn\" width=\"100%\" height=\"480\"></iframe>', '2021-11-23-18-11-30.jpg', 1, 227, 27, 127, '2021-11-23 09:24:11', '2021-11-23 10:30:18'),
(28, 'indoor', 'Climbing_gym_in_Stepantsminda_(Kazbegi)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1802.8266849535078!2d44.639158!3d42.6529294!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x441c48a0a1446fc9!2zNDLCsDM5JzA5LjIiTiA0NMKwMzgnMjMuMyJF!5e1!3m2!1sen!2sge!4v1651777510420!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-05-05-18-05-16.jpg', 0, 228, 28, 128, '2022-05-05 10:52:52', '2022-05-05 11:05:39'),
(29, 'mount_route', '\"Iliauni\"', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-08-23-19-08-47.jpg', 0, 229, 29, 129, '2022-08-23 11:24:25', '2022-09-21 11:50:39'),
(30, 'mount_route', 'leonidze_clasic_4a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 230, 30, 130, '2022-08-23 11:35:54', '2022-08-23 11:39:23'),
(31, 'mount_route', 'didi_digomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 231, 31, 131, '2022-08-23 11:40:16', '2022-08-23 11:43:52'),
(32, 'mount_route', 'Kameruni_klasic_(3A)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 232, 32, 132, '2022-08-23 11:45:32', '2022-09-21 12:09:44'),
(33, 'mount_route', 'asatiani_clasic_4a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 233, 33, 133, '2022-08-23 12:01:41', '2022-08-23 12:07:39'),
(34, 'mount_route', 'myinvartsveri_clasic_2a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 234, 34, 134, '2022-08-23 13:03:43', '2022-08-23 13:05:22'),
(36, 'outdoor', 'Climbing_in_Gveleti', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/gveleti_georgia_614324?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 420px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/gveleti_georgia_614324?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\" rel=\"noopener\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/d/embed?mid=15BvN2xGNmjk0Bu_-B3gBZZ7penH7HLg&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', '2022-09-05-13-09-26.jpg', 1, 236, 36, 136, '2022-09-05 05:30:21', '2022-09-05 07:51:27'),
(37, 'outdoor', 'Climbing_near_Ananuri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.meteoblue.com/en/weather/widget/daily/ananuri_georgia_615758?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&pressure=0&layout=light\"  frameborder=\"0\" scrolling=\"NO\" allowtransparency=\"true\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox\" style=\"width: 270px; height: 420px\"></iframe><div><!-- DO NOT REMOVE THIS LINK --><a href=\"https://www.meteoblue.com/en/weather/week/ananuri_georgia_615758?utm_source=weather_widget&utm_medium=linkus&utm_content=daily&utm_campaign=Weather%2BWidget\" target=\"_blank\" rel=\"noopener\">meteoblue</a></div>', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2509.473540379895!2d44.76771964623844!3d42.14155923603164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x3df9e64868ccec6f!2zNDLCsDA4JzI4LjYiTiA0NMKwNDYnMDUuMiJF!5e0!3m2!1sen!2sge!4v1662385603039!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-09-05-16-09-31.jpg', 0, 237, 37, 137, '2022-09-05 05:41:36', '2022-09-27 04:30:57'),
(38, 'event', 'advanture_2022', 'https://www.facebook.com/events/804352567248887/804352573915553?ref=newsfeed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/d/embed?mid=11369zNgZaTJ4tHJHW08pxwjppE021_8&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', '1.jpg', 0, 238, 38, 138, NULL, NULL),
(40, 'news', 'new_climbing_sector_near_ananuri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-05-16-09-55.jpg', 1, 240, 40, 140, '2022-09-05 08:06:23', '2022-09-05 08:15:55'),
(41, 'news', 'new_climbing_routes_in_gveleti', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/d/embed?mid=11LORpFzfFZQtjteBmK6df3vQMqrGfKi2&ehbc=2E312F\" width=\"100%\" height=\"480\"></iframe>', '2022-09-05-16-09-36.jpg', 1, 241, 41, 141, '2022-09-05 08:16:02', '2022-09-05 08:23:36'),
(42, 'news', 'new_climbing_sector_and_5_sport_climbing_route_near_borjomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1449.6733448912114!2d43.49834917636428!3d41.79944207963903!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6c574ad1385bd216!2zNDHCsDQ3JzU4LjciTiA0M8KwMjknNTcuNyJF!5e0!3m2!1sen!2sge!4v1662395748075!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-09-05-16-09-57.jpg', 1, 242, 42, 142, '2022-09-05 08:24:07', '2022-09-05 08:36:05'),
(43, 'news', 'new_sport_climbing_sector_at_the_black_sea_cast', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1488.139021619491!2d41.74087013087309!3d41.757662734605226!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb5f2b7ba8b399da3!2zNDHCsDQ1JzI3LjYiTiA0McKwNDQnMzAuNiJF!5e0!3m2!1sen!2sge!4v1662395969762!5m2!1sen!2sge\" width=\"100%\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2022-09-05-16-09-52.jpg', 1, 243, 43, 143, '2022-09-05 08:36:13', '2022-09-05 08:49:52'),
(44, 'news', 'Georgian_mountaineers_awarded_by_Golden_Ice_Axe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-20-11-11-56.jpg', 1, 244, 44, 144, '2022-11-20 02:22:07', '2022-11-20 04:54:20');

-- --------------------------------------------------------

--
-- Структура таблицы `article_comments`
--

CREATE TABLE `article_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `article_id` bigint UNSIGNED NOT NULL,
  `deleted_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_complaints`
--

CREATE TABLE `article_comment_complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `complaint` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cause` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_queries`
--

CREATE TABLE `article_comment_queries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_comment_user`
--

CREATE TABLE `article_comment_user` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_mount`
--

CREATE TABLE `article_mount` (
  `id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `mount_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Структура таблицы `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` int DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_data` timestamp NULL DEFAULT NULL,
  `end_data` timestamp NULL DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_event_id` bigint UNSIGNED NOT NULL,
  `ka_event_id` bigint UNSIGNED NOT NULL,
  `ru_event_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `film_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `favorite_outdoor_areas`
--

CREATE TABLE `favorite_outdoor_areas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Структура таблицы `films`
--

CREATE TABLE `films` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_film_id` bigint UNSIGNED NOT NULL,
  `ka_film_id` bigint UNSIGNED NOT NULL,
  `ru_film_id` bigint UNSIGNED NOT NULL,
  `published` int DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `film_categories`
--

CREATE TABLE `film_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `film_files`
--

CREATE TABLE `film_files` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_description` text COLLATE utf8mb4_unicode_ci,
  `ka_description` text COLLATE utf8mb4_unicode_ci,
  `ru_description` text COLLATE utf8mb4_unicode_ci,
  `file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `film_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `film_reitings`
--

CREATE TABLE `film_reitings` (
  `id` bigint UNSIGNED NOT NULL,
  `review_stars` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `film_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `film_tags`
--

CREATE TABLE `film_tags` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(7, 'cat 1', 'cat 1', 'cat 1', '2022-02-06 17:17:22', '2022-02-06 17:21:23'),
(8, 'cat 2', 'cat 2', 'cat 2', '2022-02-06 17:17:22', '2022-02-06 17:21:23');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `text`, `image`, `published`, `image_type`, `link`, `category_id`, `created_at`, `updated_at`) VALUES
(16, 'Gveleti icefool', 'Gveleti icefool', '2022-02-06-21-02-31.jpeg', 1, 'climbing', NULL, 8, '2022-02-06 17:17:22', '2022-02-06 17:21:23'),
(17, 'Climbing in Ateni', 'Climber - Guga dabrundashvili', '2022-02-06-21-02-26.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:20:26', '2022-02-06 17:20:26'),
(18, 'Ice climbing in Gveleti', 'Climber - ?', '2022-02-06-21-02-06.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:25:06', '2022-02-06 17:25:06'),
(19, 'Campetition in Cveleti', 'Ice climbing competition in Gveleti', '2022-02-06-21-02-49.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:26:49', '2022-02-06 17:26:49'),
(21, 'Climbing in Ateni(Tbiisi)', 'Climbing in Ateni(Tbiisi)', '2022-02-06-21-02-51.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:36:51', '2022-02-06 17:36:51'),
(22, 'Climbing in Ateni(Tbiisi)', 'Climbing in Ateni(Tbiisi)', '2022-02-06-21-02-28.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:37:29', '2022-02-06 17:37:29'),
(23, 'Climbing in Ateni(Tbiisi)', 'Climbing in Ateni(Tbiisi)', '2022-02-06-21-02-16.jpg', 1, 'climbing', NULL, 8, '2022-02-06 17:38:00', '2022-02-06 17:39:16'),
(24, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-32.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:22:32', '2022-02-09 16:22:32'),
(25, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-56.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:22:56', '2022-02-09 16:22:56'),
(26, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-15.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:23:15', '2022-02-09 16:23:15'),
(27, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-35.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:23:35', '2022-02-09 16:23:35'),
(28, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-54.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:23:54', '2022-02-09 16:23:54'),
(29, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-17.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:24:17', '2022-02-09 16:24:17'),
(30, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-38.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:24:38', '2022-02-09 16:24:38'),
(31, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-59.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:24:59', '2022-02-09 16:24:59'),
(32, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-21.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:25:21', '2022-02-09 16:25:21'),
(33, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-41.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:25:41', '2022-02-09 16:25:41'),
(34, 'Climbing in Monument', 'Climbing in Monument', '2022-02-09-20-02-05.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:26:05', '2022-02-09 16:26:05'),
(35, 'Climbing near Batumi', 'Climbing near Batumi', '2022-02-09-20-02-35.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:27:35', '2022-02-09 16:27:35'),
(36, 'Climbing near Batumi', 'Climbing near Batumi', '2022-02-09-20-02-55.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:27:55', '2022-02-09 16:27:55'),
(37, 'Birtvis canion', 'Birtvis canion', '2022-02-09-20-02-31.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:28:31', '2022-02-09 16:28:31'),
(38, 'Birtvis canion', 'Birtvis canion', '2022-02-09-20-02-48.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:28:48', '2022-02-09 16:28:48'),
(39, 'Climbing in Tbilisi', 'Climbing in \"Tbilisi Botanical Garden\"', '2022-02-09-20-02-33.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:30:33', '2022-02-09 16:31:23'),
(40, 'Climbing in Tbilisi', 'Climbing in \"Tbilisi Botanical Garden\"', '2022-02-09-20-02-04.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:31:04', '2022-02-09 16:31:04'),
(41, 'Climbing in Sveri', 'Climbing in Sveri', '2022-02-09-20-02-16.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:32:16', '2022-02-09 16:32:16'),
(42, 'Climbing in Sveri', 'Climbing in Sveri', '2022-02-09-20-02-38.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:32:38', '2022-02-09 16:32:38'),
(43, 'Climbing in Sveri', 'Climbing in Sveri', '2022-02-09-20-02-02.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:33:02', '2022-02-09 16:33:02'),
(44, 'Climbing in Sveri', 'Climbing in Sveri', '2022-02-09-20-02-21.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:33:22', '2022-02-09 16:33:22'),
(45, 'Ice climbing in Gveleti', 'Ice climbing in Gveleti', '2022-02-09-20-02-15.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:34:15', '2022-02-09 16:34:15'),
(46, 'Ice climbing in Gveleti', 'Ice climbing in Gveleti', '2022-02-09-20-02-31.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:34:31', '2022-02-09 16:34:31'),
(47, 'Ice climbing in Gveleti', 'Ice climbing in Gveleti', '2022-02-09-20-02-52.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:34:52', '2022-02-09 16:34:52'),
(48, 'Ice climbing in Gveleti', 'Ice climbing in Gveleti', '2022-02-09-20-02-10.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:35:10', '2022-02-09 16:35:10'),
(49, 'Ice climbing in Ateni', 'Ice climbing in Ateni (Biisi)', '2022-02-09-20-02-21.jpg', 1, 'climbing', NULL, 7, '2022-02-09 16:36:21', '2022-02-09 16:36:21'),
(50, 'Ice climbing in Ateni', 'Ice climbing in Ateni Biisi', '2022-02-09-20-02-41.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:36:42', '2022-02-09 16:36:42'),
(51, 'Ice climbing in Ateni', 'Ice climbing in Ateni', '2022-02-09-20-02-33.jpg', 1, 'climbing', NULL, 8, '2022-02-09 16:37:33', '2022-02-09 16:37:33'),
(52, 'Слава Україні!', 'Русский военный корабль, иди нахуй!!!', '2022-03-01-16-03-23.png', 1, 'climbing', NULL, 7, '2022-03-01 12:46:11', '2022-03-05 14:52:16'),
(53, 'Monument', 'Climber - Emili em', '2022-07-23-22-07-50.jpg', 1, 'climbing', NULL, 8, '2022-07-23 18:03:51', '2022-07-23 18:03:51');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_image_article`
--

CREATE TABLE `gallery_image_article` (
  `id` bigint UNSIGNED NOT NULL,
  `image_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gallery_image_article`
--

INSERT INTO `gallery_image_article` (`id`, `image_id`, `article_id`, `created_at`, `updated_at`) VALUES
(1, 16, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(2, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(3, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(4, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(5, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(6, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(7, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(8, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(9, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(10, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(11, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(12, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(13, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(14, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(15, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(16, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(17, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(18, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(19, 17, 13, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(20, 17, 13, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(21, 17, 14, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(22, 17, 14, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(23, 17, 15, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(24, 17, 15, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(25, 17, 1, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(26, 17, 1, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(27, 17, 1, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(28, 17, 1, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(29, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(30, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(31, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(32, 17, 22, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(33, 17, 1, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(34, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(35, 17, 23, '2022-02-06 13:17:22', '2022-02-06 13:21:23'),
(36, 17, 7, '2022-02-06 13:17:22', '2022-02-06 13:21:23');

-- --------------------------------------------------------

--
-- Структура таблицы `general_infos`
--

CREATE TABLE `general_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, 'Bolting info', '<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>используете краску для вашей безопасности!!!</strong></p>', '<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans. Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '2023-01-24 12:09:45', '2023-01-24 12:09:45'),
(2, 'Contact info', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', '<p>გუგა - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>თემო - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', '2023-01-24 12:10:25', '2023-01-24 12:10:25');

-- --------------------------------------------------------

--
-- Структура таблицы `general_info_article`
--

CREATE TABLE `general_info_article` (
  `id` bigint UNSIGNED NOT NULL,
  `info_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `block` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `block_action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'ka', 'ცოცვა სვერში (ჭიათურა)', '<p>სვერის საცოცი რეგიონი მდებარეობს დასავლეთ საქართველოში,&nbsp;იმერეთში.</p>', '<p>სვერი საცოცი რეგიონი&nbsp;შედარებით ახალია მაგრამ მიუხედავად ამისა აქ საკმაოდ ბევრი სპორტული მარშრუტია. მარშრუტების კეთება დაიწყო 2018 წელს. რეგიონი პერსპეკტიულია როგორც საცოცი სექტორების ათვისებისთვის ასევე სხვა სპორტული აქტიობებებისთვის. რეგიონში თქვენ შეგიძლიათ დაკავდეთ არამხოლოდ ცოცვით არამედ ბევრი სხვა აქტიობით</p>\n\n<p><strong>სხვა აქტიობები</strong></p>\n\n<p>სვერშო არის რამდენიმე ძველი ციხესიმაგრე, მონასტერი და მრავალი ლამაზი.&nbsp;ასევე მიმდებარე ტერიტორიაზე არის რამდენიმე ტბა და ჩანჩქერი სადაც შესაძლებელია ნავით გასეირნება და&nbsp;ბანაობა ზაფხულის ცხელ დღეებში.</p>\n\n<p>&nbsp;</p>', '<p>საცოცი სეკტორებთან მისასვლელი გზა საკმაოდ მარტივი მისაგნებია.</p>\n\n<p>მარშრუტების უმეტესობა 10-25 მეტრია, სირთულე&nbsp;5A- დან 7C- მდე.</p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>\n\n<p><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>', '<p>სოფელი სვერი მდებარეობს&nbsp;ქალაქ ჭიათურასთან თბილისიდან 170 კმ-ში.</p>\n\n<p><br />\nსვერამდე მისვლა შეგიძლიათ მიკროავტობუსით&nbsp;თბილისიდან, დიდუბის სადგურიდან, ქალაქ ჭიათურაში. ჭიათურიდან შეგიძლიათ მიკროავტობუსით ან ტაქსით გაემართოთ სოფელ სვერში, რომელიც 11 კმ-თია დაშორებული. სოფელ სვერის ავტობუსის გაჩერებიდან დაახლოებით 400 მეტრია გასავლელი საცოც სექტორამდე.<br />\nტერიტორია არ არის მარკირებული ბოლომდე, მაგრამ არის რამდენიმე მიმართიულების მიმცემი ნიშანი სოფლის დასაწყისში შეგხვდებათ რეგიონის ნიშანი მიმართულებით და&nbsp;სოფლის ბოლოს არის ნიშანი &quot;Climbing Zone&quot; და იქიდან დაახლოებით 50 მეტრია კლდეებამდე.</p>\n\n<p>თუ თქვენ გამოიყენებთ პირად ავტომობილს რეკომენდირებულია იყოლიოთ 4X4ზე მანქანა. წინააღმდეგ შემთხვევაში სოფლის შიგნიტ გადაადგილება შეიძლება გაგიჭირდეთ.</p>\n\n<p>დარჩენა რეკომენდირებულია სექტორების ძირში მდებარე კემპინგში &quot;<a href=\"https://www.campingeorgia.ge/camp/\">Sveri adventure camp</a>&quot;. კემპინგი სექტორებისკენ მიმავალ გზაზა მდებარეოდს და მისი მოძებნა არ გაგიჭირდებათ. აქ თქვენ დაგხვდებათ მოწყობილი საკარვე ადგილი და რამდენიმე კოტეჯი. აგრეთვე შხაპი, სამზარეულო და სველი წერტილები.</p>\n\n<p><br />\nსაკვები და მარაგი</p>\n\n<p>სურსათის შეძენას გირჩევთ&nbsp;ქალაქ ჭიათურაში. აქ არის ადგილობრივი აგრარული&nbsp;ბაზარი, მრავალფეროვანი მაღაზიები და უამრავი საცხობი, რომლებიც გთავაზობთ ყოველდღიურად გამომცხვარ ახალ პურს, ხაჭაპურს და ლობიანს.<br />\nსამწუხაროდ, სვერში საკვები პროდუქტების ბაზარი აღარ არის, ასე რომ თქვენ უნდა თან წაიყვანეთ მთელი თქვენი მარაგი.წყლის აღება შეგიძლიაღ სოფლის წყაროებზე.წყაროები სუფთაა და სასმელად გამოსადექი.</p>', '<p>ზამთრის გარდა, სვერში ცოცვა იდეალურია გაზაფხულიდან შემოდგომამდე.</p>\n\n<p>კლდეებიის დიდი ნაწილი ჩრდილოეთისკენ არის მიმართული, რაც იმას ნიშნავს რომ დღის უმეტესი ნაწილი ისინი ჩრდილშია (1-2 საათის შემდეგ) და ზაპხულის დღეებში აქ ცოცვა ძალიან კომპორტულია. წელიწადის ცივ დროს აქ საცოცვა საკმაოდ არაკომფორტულია ამინდის გამო და ჩვენ გირჩევთ ეწვიოთ <a href=\"http://climbing.ge/outdoor/Climbing_In_Chiatura\">ჭიათურის</a> და <a href=\"http://climbing.ge/outdoor/Climbing_In_Katskhi_%28Chiatura%29\">კაცხის</a> საცოც სექტორებს.</p>', '<ol>\n	<li>12 ექსპრესი</li>\n	<li>50m თოკი</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხოტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნები) და მაგნეზიის ტომარა</li>\n</ol>', '<p>გუგა - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>თემო - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48, (+995) 557 300 700</p>\n\n<p>&nbsp;</p>', NULL, NULL, '2021-03-02 07:04:04', '2021-05-14 15:52:10'),
(6, 'ka', 'ცოცვა კაცხში (ჭიათურა)', '<p>კაცხი ერთ-ერთი ყველაზე საუკეთესო ადგილია საცოცად საქართველოში. კლდის ხარისხი შესანიშნავია ცოცვის&nbsp;სხვადასხვა ტიპისთვის.</p>', '<p>კაცხი ერთ-ერთი ყველაზე პოპულარული ადგილია საცოცად საქართველოში.&nbsp;კლდის ხარისხი შესანიშნავია ცოცვის სხვადასხვა ტიპისთვის. აქ გვაქვს ბევრი სხვადასხვა ტიპის რელიეფი, როგორც უარყოფითი მარშრუტები ასევე სწორუ ფილები, რაც მეტ საშვალებას აძლებს სპორცმენს მაქსიმალურად განვითარდეს.</p>', '<p>მარშრუტები კლასიფიცირებულია ფრანგულ სისტემით.&nbsp;5a&nbsp;&ndash; დან 8b&nbsp;&ndash; მდე.</p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>\n\n<p><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>', '<p>კაცხი მდებარეობს დედაქალაქ თბილისიდან დაახლოებით 180 კმ-ში. მანქანით დაგჭირდებათ დაახლოებით 2 - 2.5 საათი. სასურველია მაღალი გამავლობის 4X4 მანქანით გადადგილება.&nbsp;</p>\n\n<p>ქუთაისის აეროპორტიდან 1 საათი.</p>\n\n<p>სამოქალაქო ტრანსპორტით თქვენ დაგჭირდებათ ცასვლა ქალაქ ჭიათურაში. სამარშრუტო ტაქსები ახორციაელებენ მარშრუტზე მოძრაობას დილის 9დან საღამოს 5ამდე. ჭიათურიდან თქვენ მოგიწევთ ჭიათურა-კაცხი მარშრუტკახე გადაჯდომა და ის ჩამოგიყვანთ სოფელ&nbsp;კაცხის მთავარ მოედანზე.&nbsp;</p>\n\n<p>დასარჩენად საუკეთესო ადგილია &quot;კაცხის სახლი&quot; (იხილეთ რუკა). აქ შესაძლებელია გაჩერება როგორც სახლში ისევე კარავში.</p>', '<p>,სკაცხი მდებარეობს ზღვის დონიდან 700 მ. ცოცვისთვის&nbsp;საუკეთესო პერიოდია ზამთარი, გაზაპხული&nbsp;და შემოდგომა. ზამთარში და ადრე გზაფხულზე ხშირია წვიმა მაგრამ კარ გამინდში თქვენ შეძლებთ ცოცვას გრილ და სასიმოვნო ამინდში. ზაფხოლში უმეტესად ცხელი ამინდებია საცოცად და ცოცვას მოახერხებთ ადრე დილას ან მხოლოდ ინ სეკტორებში რომლების ჩრდილშია.</p>', '<ol>\n	<li>12 ექსპრესი</li>\n	<li>60m თოკი</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხოტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნები) და მაგნეზიის ტომარა</li>\n</ol>', '<p>მოგვწერეთ შეტყობინება და ჩვენ მალე გიპასუხებთ.</p>\n\n<p><strong>Guga</strong> - <em>Mail</em>: gugadabrunda@gmail.com &nbsp; &nbsp;<em>Tel</em>: (+995) 557 300 700</p>\n\n<p><strong>Temo</strong> - <em>Mail</em>: samsonadze.temo9@gmail.com&nbsp; &nbsp; <em>Tel</em>; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:31:38', '2021-03-31 15:46:21'),
(7, 'ka', 'ცოცვა ლოტკინის მონუმენტზე', '<p>კეთილი იყოს თქვენი მობრძანება ლოტკინის მონუმენტზე. ეს არის 40 მეტრიანი ბეტონის კონსტრუქცია. რომელზეც შესაძლებელია ცოცვა.</p>', '<p>კეთილი იყოს თქვენი მობრძანება ლოტკინის მონუმენტზე.</p>\n\n<p>ეს არის 40 მეტრიანი ბეტონის კონსტრუქცია. მონუმენტი მდებარეობს ქალაქის გარეუბანში და მაგრამ მასთან მისვლა არანაირ სირთულეს არ წარმოადგენს. მონუმენტი არის კარგი ადგილი საცოცად თბილისში. აქ გვაქვს 7 მარშრუტი 5B-7B. (იხილეთ მარშრუტების არჭერა)</p>', '<p>დღეისათვის არსებობს 7&nbsp;სპორტული მარშრუტი. 1&nbsp;მაღშრუტი&nbsp;ყველაზე მაღალია 40 მ, 1 მარშრუტი 30 მ და 4&nbsp;მარშრუტი 20 მ.</p>', '<ol>\n	<li>20 ექსპრესი</li>\n	<li>(Min) 40m - (Max) 80m თოკი</li>\n	<li>დეაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი(სკალნები) &amp; მაგნეზიის ტომარა</li>\n</ol>', '<p>აქ მთელი წლის განმავლობაში შეგიძლიათ ცოცვა&nbsp;ამინდის მიხედვით. მონუენტი მდებარეობს ქარიან ადგილას ამიტომაც ხშირია ძლიერი ქარი. ზაფხოლში მეტად სასიამოვნოა ცოცვა საღამოს საათებში როცა მზე ჩასვლას იწყებს. ზამთარში ხშირია ცივი ქარი ამიტომაც აუცილებელოია ამინდის შერჩევა, მაგრამ კარგ ამინდში ზამთარშიც კი აქ ცოცვა ძალიან სასიამოვნოა.</p>', '<p>მონუმენტი მდებარეობს ქალაქის გარეუბანში და მაგრამ მასთან მისვლა არანაირ სირთულეს არ წარმოადგენს. მეტრო <a href=\"https://goo.gl/maps/Rjou6mefmr52\">&#39;სადგურის მოედნიდან</a>&#39; ზედა გასასვლელიდან მიდიან es110, №111, №48 (<a href=\"http://goo.gl/maps/EG9Emms68YN5yGvC7\">გაჩერება 1</a>) და №36 (<a href=\"https://goo.gl/maps/2XZPEGVgAEP7QezZ8\">გაჩერება 2</a>) ავტობუსებით (<a href=\"https://www.google.com/maps/d/u/0/viewer?mid=1Nzu-J7c65ivpXrma5iAdWye1a7nhYslt&amp;ll=41.72681806910237%2C44.81414870000003&amp;z=15\">იხილეთ რუკა</a>). თუ თქვენ პირადი მანქანით მიდიხართ მოგიწავთ მანქანის გაჩერება ქალაქის პარკინგზე. მონუმენტთან მისასვლე გზა გადაკეტილია!</p>', '<p>მოგვწერეთ შეტყობინება და ჩვენ სულ მალე გიპასუხებთ.</p>\n\n<p><strong>Guga</strong> - <em>Mail</em>: gugadabrunda@gmail.com &nbsp; &nbsp;<em>Tel</em>: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- <em>Mail</em>: samsonadze.temo9@gmail.com&nbsp; &nbsp; <em>Tel</em>; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:36:24', '2021-03-31 15:59:55'),
(8, 'ka', 'ცოცვა ჭიათურაში', '<p>ჭიათურა ერთ-ერთი ყველაზე პერსპექტიული საცოცი რეგიონია საქართველოში.</p>', '<p>ჭიათურა ერთ-ერთი ყველაზე პერსპექტიული საცოცი რეგიონია საქართველოში. აქ გვაქვს მაღალი კედლები 50-150მ რომელიც ჯერჯერობით უმეტესად არაა ათვისებული, მაგრამ გვაქვს 2 რეგიონი 30 მარშრუტით სადაც შეგიძლიათ სასიამოვნოდ ცოცვა.</p>', '<p>ამ რეგიონში მუდმივად ვამატებთ ახალ მარშრუტებს. მარშრუტები 5A- დან 8A- მდეა. აგრეთვე გვაქვს 3, 2 თოკიანი მარშრუტი (მულტიპიჩი).</p>\n\n<p><strong>უსაფრთხოების მიზნით, გამოიყენეთ ჩაფხეტი!</strong></p>\n\n<p><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ თქვენ აპირებთ მატშტუტების გაკეთებას ჭიათურაში აუცილებლად შეატყობინეთ ადგილობრივ მეკლდეურებს. წინააღმდეგ შემთხვევაში თქვენი მარშრუტი შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>&quot;Drytooling&quot;-ის ცოცვა რეგიონში სასტიკად აკრძალულია!</strong></p>\n\n<p>&nbsp;</p>', '<p>ჭიათურა მდებარეობს დედაქალაქ თბილისიდან 180 კმ-ში. მანქანით დაგჭირდებათ დაახლოებით 2.0-2.5 საათი. საქალაქთაშიორისო ტრანსპორთით დაგჭირდებათ თბილისი-ჭიათურა სამარშრუტო ტაქსით ჩახვიდეთ ჭიათურაში, აქედან ფეხით ან ტაქსით შეძლებთ ახვიდეთ საცოც სეკტორამდე. (იხილეთ რუკა)</p>\n\n<p>თქვენ შეგიძლიათ დარჩენა ქალაქში ჰოსტელში ან კაცხის სახლში. კაცხის სახელი&nbsp;მდებარეობს ქალაქიდან 9 კილომეტრში, სოფელ კაცხში. (იხილეთ მეტი)</p>', '<p>ჭიათურაში ცოცვისთვის საუკეთესო სეზონია ზამთარი, გაზაფხული და შემოდგომა. ზაპხულში ამ რეგიონში საცოცად ძალიან ცხელა. ცოცვა შესაძლებელია დარე დილას, 10.00-11.00 საათამდე. წლის სხვა პერიოდში სასურველია ამინდის შემოწმება რათა წვიმამ არ შეგიშალოთ ცოცვაში ხელი</p>', '<ol>\n	<li>20 ექსპრესი</li>\n	<li>თოკი 60მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>მოგვწერეთ შეტყობინენა და ჩვენ სულ მალე გიპასუხებთ.</p>\n\n<p><strong>Guga</strong> - <em>Mail</em>: gugadabrunda@gmail.com &nbsp; &nbsp;<em>Tel</em>: (+995) 557 300 700</p>\n\n<p><strong>Temo</strong> - <em>Mail</em>: samsonadze.temo9@gmail.com&nbsp; &nbsp; <em>Tel</em>; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:38:50', '2021-03-31 16:20:57'),
(9, 'ka', 'ცოცვა მესტიაში (ჭალაადი)', '<p>.</p>', '<p>სვანეთი ერთ-ერთი ულამაზესი ადგილია საქართველოში.</p>\n\n<p>შეიძლება იყოს აქტიური სპორტული და ისტორიული და სასიამოვნო ადგილები.</p>\n\n<p>მესტია არის სვანეთის ცენტრი, რომელიც მდებარეობს ზღვის დონიდან 1500 მეტრში და თბილისიდან 460 კმ 8 საათის სავალზე.</p>', '<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', NULL, '<p>ასვლისთვის საუკეთესო დროა ზაფხულის ბოლოს, შემოდგომის დასაწყისი. სალაშქრო ადგილი მარტივია ავტოსტოპით, ის მდებარეობს ქალაქის ცენტრიდან 10 კმ-ის დაშორებით.</p>', '<ol>\n	<li>14 ექსპრესი</li>\n	<li>თოკი 70მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:41:37', '2022-03-09 16:37:29'),
(10, 'ka', 'ცოცვა ძევრში', '<p>.</p>', '<p>საფეხმავლო ხაზები მართლაც ახლოს არის გზასთან და მათ უმეტესობას აქვს კომფორტული სასტარტო პლატფორმა.</p>\n\n<p>შესაძლებელია ცოცვის ზონაში დაბანაკება.</p>\n\n<p>კლდეებთან ახლოს არის რესტორანი და ბაზარი.</p>\n\n<p>ადგილობრივი მოსახლეობა ნამდვილად მეგობრულია მთამსვლელთა საზოგადოების მიმართ.</p>\n\n<p>კლდის სახეს განსხვავებული ექსპოზიცია აქვს და მაშინაც კი, როცა მზეზე ცხელა, შეგიძლიათ იპოვოთ დაჩრდილული ადგილი ასასვლელად.</p>', '<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', '<p>მანძილი თბილისიდან 200 კმ 3 საათი მანქანით.</p>\n\n<p>მანძილი ქუთაისის კოპიტნარის აეროპორტიდან 60 კმ 1 საათი მანქანით. (რუკის ჩვენება)</p>', '<p>ასვლისთვის საუკეთესო სეზონია ზაფხულის დასაწყისი და შემოდგომა, მაგრამ კარგი ამინდის შემთხვევაში ასვლა შესაძლებელია მთელი წლის განმავლობაში.</p>', '<ol>\n	<li>14 ექსპრესი</li>\n	<li>თოკი 60მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:44:09', '2022-04-09 16:42:57'),
(11, 'ka', 'ცოცვა ქუთაისში(ჭომა)', '<p>ქალაქში არ არის ბევრი საცოცი მარშრუტი, მაგრამ დღის სასიამოვნოდ გასატარებლად, ეს სავსებით საკმარისია.</p>', '<p>ქუთაისი საქართველოში სისდიდით მეორე ქალაქია. მოსახლეობა 147&nbsp;635 ადამიანი. იგი 235 კმ - ითაა დაშრებული თბილისიდან. ზ.დ. 125-300მ. ქალაქში არ არის ბევრი საცოცი მარშრუტი, მაგრამ დღის სასიამოვნოდ გასატარებლად, ეს სავსებით საკმარისია. მარშრუტების სირთულე 5A დან 7A- მდე მერყეობს, ამიტომ აქ ცოცვა დამწყებ მეკლდეურთათვის იდეალურია.</p>', NULL, '<p>ქუთაისში ცასვლა საქარტელოს ნებისმიერი ქალაქიდან ძალიან მარტივია. მუნიციპალური ტრანსპორტი აქ ყველა ქალაქიდან მოზრაობს. საკუთარი მანქანით მგზავრობის შემთხვევაში გამოიყენეთ GPS ნავიგატორი. ასევე ქალაქიდან 5 კმ - ში არის აეროპორტი. ფერენები როგორც თბილისიდან, ასევე მსოფლიოს ბევრი სხვა ქვეყნიდანაა.</p>\n\n<p>საცოცო სექტორებთან მისასვლელად იხილეთ რუკა.</p>\n\n<p>ღამის გასათენებლად შეგიძლიათ დარჩეთ ქუთაისში მდებარე ერთ - ერთ სასტუმროშ, ან კარვით დარჩეთ კლდეების მიმდებარე ტერიტორიაზე.</p>', '<p>საუკეთესო დრო აქ საცოცად გაზაფხული და შემოდგომაა. ზაფხულის პერიოდში აქ ზალიან ცხელა. ზამტარში საკმაოდ ცივა, მაგრამ თბილ დღეებში შესაძლებელია კომფორტულად ცოცვა.</p>', '<p>12 ექსპრესი<br />\n60m თოკი<br />\nდაცვის ქამარი<br />\nდაცვის სისტემა<br />\nჩაფხოტი<br />\nსაცოცი ფეხსაცმელი (სკალნები) და მაგნეზიის ტომარა</p>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:46:18', '2021-11-16 16:10:56'),
(12, 'ka', 'ცოცვა არშაში', '<p>ყაზბიგი (სტეპანციმინდა) ზდ 1750მ, ერთერთი ყველაზე პოპულარულია ადგილია საქართველოში. ყოველწლიურად მას უამრავი აქტიური დასვენების მოყვარული სტუმრობს.</p>\n\n<p>&nbsp;</p>', '<p>არშა მდებარეობს ჩრდილოეთ საქართველოში, დაბა სტეფანწმინდიდან 5კმ - ში,&nbsp; ზ.დ. 1500მ. მოსახლეობა 700 ადამიანი. სტეფანწმინდის რეგიონი ძალიან პოპულარულია სათავგადასავლო ტურიზმის მოყვარულთა შორის. აქ შეგიძლია დაკავდეთ, როგორც კლდეზე ცოცვით, ასევე სხვადასხვა აქტივობებით: ტრეკინგი, ველო ტური, ჯომარდობა, ალპინიზმი, პარაპლანი და უამრავი სხვა. ალპინიზმის მოყვარულებს ვურჩევთ წაუხის და მყინვარწვერის მასივებს. აგრეთვე 6 კმ - ში არის სხვა საცოცი რეგიონი ცდო, რომელსაც აგრეთვე შეგიძლიათ ეწვიოთ.</p>\n\n<p>მარშრუტების სირთულე 5A-დან 7A-მდე მერყეობს. აგრეთვე შეგიძლიათ მულტიპიჩის ცოცვაც. არის ბევრი კარგი ნაპრალი სადაც შეიძლება Tred climbing-ის ცოცვა.</p>\n\n<p>&nbsp;</p>', '<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', '<p>არშაში მოსახვედრათ დაგჭირდებათ ყაზბეგისკენ მომავალი მუნიციპალური ტრანსპორტი და ჩამოხვიდეთ სტეფანწმინდამდე 5 კმ - ში. აქედან ფეხით მოგიწევთ 1,5 კმ - ის გავლა. შესაძლებელია ყაზბეგში ტაქსის ქირაობაც.</p>\n\n<p>საკუტარი მანქანით მგზავრობის შემთხვევაში, მანქანა სასურველია იყოს 4X4 -ზე ( იხილეთ რუკა).</p>\n\n<p>არსასი და სტეფანწმინდაში უამრავი სასტუმროა, რომელშიც თქვენ შეგიძლიათ გაჩერდეთ ( ფასები 30 ლარიდან). აგრეთვე შეგიძლიათ გაჩერდეთ არსას კლდეებთან არსებულ საკემპინგე ადგილას, კლდეებიდან 10 &ndash; 15 წუთის სავალზე.</p>', '<p>იმის გამო რომ საცოცი სექტორი მაღალმთიან რეგიონში მდებარეოს, აქ ცოცვა გვიან გაზაფხულზე , ზაფხულში და ადრე შემოდგომაზეა შესაძლებელი. ზამთარში აქ ცოცვა დაბალი ტემპერატურისა და უხვი ნალექის გამო შეუძლებელია. ზამტარში გირჩევთ გველეთის გაყინულ ჩანცქერზე ცოცვას.</p>', '<p>თქვენ გჭირდებათ</p>\n\n<ol>\n	<li>\n	<p>20 ექსპრესი</p>\n	</li>\n	<li>\n	<p>თოკი 60მ</p>\n	</li>\n	<li>\n	<p>დაცვის ქამარი</p>\n	</li>\n	<li>\n	<p>დაცვის სისტემა</p>\n	</li>\n	<li>\n	<p>ჩაფხუტი</p>\n	</li>\n	<li>\n	<p>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</p>\n	</li>\n</ol>', '<p>გუგა - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>თემო - Mail: samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 13:51:02', '2022-09-05 11:47:59'),
(13, 'ka', 'ცოცვა შავი ზღვის სანაპიროზე', '<p>შავი ზღვის სანაპირო გთავაზობთ რამდენიმე სეკტორს ცოცვისთვის.</p>', '<p>თუ გსურთ შავი ზღვის სანაპიროზე კლდეზე ცოცვა, შავი ზღვის სანაპირო გთავაზობთ რამდენიმე შესაძლებლობას.</p>', '<p>მარშრუტების უმრავლესობა დაშლამბურებულია საკმაოდ დიდი ხნის წინ, მაგრამ არის მარშრუტები რომლებიც გაკეთებულია დიდი ხნის წინ. მარშრუტების სირთულე მერყეობს&nbsp;&nbsp;5A-დან 7A-მდე.</p>', '<p>მანძილი დედაქალაქ თბილისიდან ბათუმამდე არის 380 კმ.&nbsp;თქვენ შეგიძლიათ ფრენა ბათუმის აეროპორტში. საცოცი სექტორიები მდებარეობს&nbsp;10 კმ-ში ბათუმიდან. აგრეთვე მიკროავტობუსით მისვლა მარტივად შეგიძლიათ.</p>\n\n<p><strong>Დასარჩენი ადგილი</strong><br />\nშეგიძლიათ დარჩეთ კერძო სახლებში ან სასტუმროებში ბათუმში, სარფში, გონიოში ან კვარიათში. ფასები იწყება 25-30 ლარიდან (დაახლოებით 10-15 ევრო) და იზრდება, სეზონის მიხედვით.</p>', '<p>თქვენ გაქვთ შესაძლებლობა ასვლა როგორც მზეზე, ისე ჩრდილში, თუმცა სიცხე ზაფხულის სეზონზე უნდა გაითვალისწინოთ. Საუკეთესო დრო<br />\nასვლა მაისი, ივნისი, სექტემბერი და ოქტომბერი.</p>', '<ol>\n	<li>10 ექსპრესი</li>\n	<li>თოკი 40მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p><strong>გუგა&nbsp;</strong>- Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>თემო&nbsp;</strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:53:23', '2021-04-11 07:35:06'),
(14, 'ka', 'ცოცვა ბირთვისის კანიონში', '<p>ბირთვისის კანიონი მდებარეობს 40 კმ - ში თბილისიდან.&nbsp;საცოცო რეგიონში მარშრუტების უმეტესი ნაწილი ბოლდერინგია, მაგრამ არის სპორტული მარშრუტებიც.</p>', '<p>ბირთვისის კანიონი მდებარეობს 40 კმ - ში თბილისიდან. ზ.დ 1077 მ. აქ თქვენ შეძლებთ ცოცვით, კანიონინგით და ტრეკინგით დაკავებას. საცოცო რეგიონში მარშრუტების უმეტესი ნაწილი ბოლდერინგია, მაგრამ არის სპორტული მარშრუტებიც.</p>\n\n<p>საფეხმავლო გზა&nbsp;სოფელი ტბისი-დან, საიდანაც უნდა აუყვე&nbsp;ბილიკს. როცა დაინახავ ნიშნულას წარწერით&nbsp;&bdquo;ბირთვისის ბუნებრივი ძეგლი&ldquo;&nbsp;&ndash; ეს ნიშნავს რომ სწორ გზაზე დგახარ. სამწუხაროდ, ბილიკი არ არის მონიშნული და სჯობს&nbsp;Wikiloc-დან ჩამოტვირთო ტრეკი ან&nbsp;დაიხმარო &ndash; Maps.me.</p>', '<p>სპორტული მარშრუტების სირთულე 5A - დან 7C - მდე მერყეობს.&nbsp;ბოლდერინგიდ სირთულე V1-დან V7-მდე მერყეობს. არის პროეკტებიც. ამიტომაც აქ ცოცვა დამწყები და მოყვარული მცოცავებისთვის იდეალურია.</p>\n\n<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', '<p>გზა საცოცი სექტორებისა და კანიონისკენ იწყება სოფელ ტბისიდან, სადაც თქვენ მოგიწევთ მანქანის დატოვება და 45 &ndash; 60 წთ. ფეხით სიარული ტყის ზონაში ( იხილეთ რუკა). მანქანა სასურველია 4X4 - ზე.</p>\n\n<p>რეგიონში გაჩერება მხოლოდ კარვითაა შესაძლებელი. საკარვე ადგილი საცოცი რეგიონების შუაში მდებარეობს და მარშრუტებამდე მისასვლელად მაქსიმუმ 5წთ - ია საჭირო. წყლის ავსება კემპინგიდან ზევით მიმავალ გზასთან არსებულ მდინარეზეა შესაძლებელი.</p>', '<p>საუკეთსეო დრო ბირთვისში საცოცად: გაზაფხული და შემოდგომაა. ზაფხულის პერიოდში კანიონში ძალიან ცხელა. ზამთრის პერიოდში უმეტესად საკმაოდ ცივა, მაგრამ ხშირია თბილი ამინდი, როდესაც შესაძლებელია ცოცვა. ბირთვისის კანიონში ცოცვა&nbsp;წლის ნებისმიერ დროს არის შესაძლებელი. თუმცა, ზაფხულში აქ საკმაოდ ცხელა და მიმდებარედ სასმელი წყალიც შრება. ძლიერი წვიმების შემდეგ სჯობს არ ეწვიო ამ ადგილს, რადგან ზოგიერთ ადგილას ძალიან ტალახიანია და წყლის დონეც მაღლა იწევს.</p>', '<p>სპორტული ცოცვისთვის დაგჭირდებათ:&nbsp;</p>\n\n<ol>\n	<li>50მ თოკი</li>\n	<li>10 ექსპრესი</li>\n	<li>დაცვის სისტემა და დაცვის ქამარი</li>\n	<li>ჩაფხუტი</li>\n</ol>\n\n<p>ბოლდერინგის საცოცად დაგჭირდებათ &#39;ქრეშპადები&#39; (<a href=\"https://images.app.goo.gl/X598rhKxzugftkZA8\">crash bad</a>)</p>', '<p>გუგა: <strong>Tel</strong>. (+995)&nbsp;557 300 700 <strong>Mail</strong>.&nbsp;gugadabrunda@gmail.com</p>\n\n<p>თემო: <strong>Mail</strong>. samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 13:55:39', '2022-01-18 15:29:45'),
(15, 'ka', 'ცოცვა \"თბილისის ბოტანიკურ ბაღში\"', '<p>თბილისის ბოტანიკური ბაღი მშვენიერი ადგილია დასვენებისთვის და დროის სასიამოვნოდ გასატარებლად. ასევე აქ შეგიძლიათ ცოცვითაც დაკავება</p>', '<p>თბილისის ბოტანიკური ბაღი მშვენიერი ადგილია დასვენებისთვის და დროის სასიამოვნოდ გასატარებლად. ასევე აქ შეგიძლიათ ცოცვითაც დაკავება. ამ საცოცო რეგიონს საკმაოდ დიდი ისტორია აქვს. ქართველი სპორცმენები აქ , წინა საუკუნის 70 - იანი წლებიდან დღემდე ვარჯიშობენ. ეს ადგილი მშვენიერია იმ ადამიანებისთვისაც, ვისაც არასდროს უცოცია ბუნებრივ კლდეზე.</p>', '<p>საბაგირო ზედა მარშრუტების გარდა, მარშრუტი No. 9, 10, 13, 14, 15 და 22 არის bolted. No1 და 2 პროექტებია, რომლებიც ჯერ კიდევ არ არის გასწორებული. ლოდის მარშრუტები არ არის მონიშნული კლდეებზე, მაგრამ ისინი ადვილად ამოიცნობენ და შეგიძლიათ იხილოთ აქ განთავსებულ ფოტოებზე.</p>\n\n<p>თქვენ ასევე გაქვთ შესაძლებლობა, რომ ასვლა 80 მ-ზე გაიაროთ ბოტანიკური ბაღის მთის კლდის მთის გავლით.</p>', '<p>აქ მოსახვედრად არსებობს ორი გზა.</p>\n\n<p>პირველი: ბოტანიკური ბაღის მთავარი შესასვლელიდან შემოსვლა შეუძლია ნებისმიერ მსურველს. გაითვალისწინეთ, რომ ცენყტრალური შემოსასვლელიდან შესვლა ფასიანია.</p>\n\n<p>მეორე: ნარიყალას ციხის მხრიდან არსებული შესასვლელიდან. ეს შესასვლელი გამოიყენება მხოლოდ ბაღის ადმინისტრაციისთვის და ქართველი სპორცმენებისთვის, ან მათთვის, ვინც მუდმივად ცოცავს ბოტანიკური ბაღში და ამ ადამიანზე ბაღის დაცვა ფლობს იმფორმაციას.</p>', '<p>ბოტანიკურ ბაღში ცოცვა წლის ნებისმერ დროსაა შესაძლებელი.ზაფხულის პერიოდში ცოცვა ყველაზე სასიამოვნოა საღამოს, როდესაც მზე აღარ ადგას კლდეებს ( 17:00 საათის შემდეგ).</p>\n\n<p>ზამთრის პერიოდში კი პირიქით, დილის საათებშ, 15:00 საატამდე, როდესაც მზე კლდეებს ათბობს და მასზე ცოცვა თბილი და სასიამოვნოა.</p>', '<p>საცოცად დაგჭირდებათ</p>\n\n<ol>\n	<li>60მ თოკი&nbsp;</li>\n	<li>8 ექსპრესი</li>\n	<li>ჩაფხუტი</li>\n	<li>დაცვის სისტემა და დაცვის ქამარი</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 14:29:06', '2021-10-08 13:33:43'),
(16, 'ka', 'ცოცვა ნავარძეთში (ჭიათურა)', '<p>პატარა სოფელი &quot;ნავარძეთი&quot; მდებარეობს ჭიათურიდან 4,5 კმ-ში დაშორებით დიდი საფეხმავლო რეგიონის &quot;კაჩის&quot; მიმართულებით.</p>', '<p>პატარა სოფელი &rdquo;ნავარძეთი&rdquo; მდებარეობს ჭიათურიდან 4,5 კმ-ში დაშორებით დიდი საფეხმავლო რეგიონის &rdquo;კაჩის&rdquo; მიმართულებით.</p>\n\n<p>საფეხმავლო ხაზები ახალია და რეგიონი მიმდინარეობს, ამიტომ დღეისათვის მხოლოდ 7 ხაზი გვაქვს, მაგრამ მალე ჩვენი კლუბი &rdquo;ქართველი ალპინისტების კლუბი&rdquo; აპირებს ყველა შესაძლო ხაზის გადაკეტვას ამ რეგიონში.</p>\n\n<p>კლდის მაღალი 30/40 მ, მაგრამ ხაზების მაქსიმალური სიმაღლე 30 მ.</p>\n\n<p>შეაფასეთ მთავარი გზიდან დაახლოებით 200 მ.</p>\n\n<p>ასვლასთან ახლოს მდებარე მთავარ გზაზე შეგიძლიათ მიიღოთ წყაროს წყალი.</p>\n\n<p>მოგზაურობის დროს დარჩენისთვის შეგიძლიათ გამოიყენოთ ალპინისტები, რომლებიც ბანაკობენ კაჩში</p>', '<p>ამ რეგიონში მუდმივად ვამატებთ ახალ მარშრუტებს. მარშრუტები 5A- დან 8A- მდეა. აგრეთვე გვაქვს 3, 2 თოკიანი მარშრუტი (მულტიპიჩი).</p>\n\n<p><strong>უსაფრთხოების მიზნით, გამოიყენეთ ჩაფხეტი!</strong></p>\n\n<p><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ თქვენ აპირებთ მატშტუტების გაკეთებას ჭიათურაში აუცილებლად შეატყობინეთ ადგილობრივ მეკლდეურებს. წინააღმდეგ შემთხვევაში თქვენი მარშრუტი შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>&quot;Drytooling&quot;-ის ცოცვა რეგიონში სასტიკად აკრძალულია!</strong></p>', '<p>ჭიათურიდან სამარშრუტო ტაქსია ნავარძეთში. ასევე შეგიძლიათ გამოიყენოთ სამარშრუტო ტაქსი კაცხამდე და გადმოხვიდეთ სოფელ ნავარძეთის მახლობლად</p>', '<p>ფართობის დიდი პლუსია ის, რომ კლდე ჩრდილშია 15:00 საათის შემდეგ, ასე რომ ზაფხულში შეგიძლიათ ჩრდილში ასვლა.</p>', '<ol>\n	<li>20 ექსპრესი</li>\n	<li>თოკი 60მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:31:44', '2021-04-07 16:48:06'),
(17, 'ka', 'ცოცვა ცდოში', '<p>ცდო - პატარა სოფელია ყაზბეგთან&nbsp;ეს არის ულამაზესი ადგილი. მშვენიერი ადგილია სპორტული და მრავალსაფეხურიანი ასვლისთვის.</p>', '<p>ყაზბეგში მთავარი ყურადღება მთის ტურიზმს წარმოადგენს (მწვერვალი 5047 მ), თუმცა ასვლაში შესაბამისი ინფრასტრუქტურის შექმნა მიმდინარეობს. სოფელი ცდო კაზბეკიდან 7 კმ-ზე მდებარეობს.</p>', '<p>ამ რეგიონში მუდმივად ვამატებთ ახალ მარშრუტებს. მარშრუტები 5A- დან 8A- მდეა. აგრეთვე გვაქვს 3, 2 თოკიანი მარშრუტი (მულტიპიჩი).</p>\n\n<p><strong>უსაფრთხოების მიზნით, გამოიყენეთ ჩაფხეტი!</strong></p>\n\n<p><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ თქვენ აპირებთ მატშტუტების გაკეთებას ჭიათურაში აუცილებლად შეატყობინეთ ადგილობრივ მეკლდეურებს. წინააღმდეგ შემთხვევაში თქვენი მარშრუტი შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>&quot;Drytooling&quot;-ის ცოცვა რეგიონში სასტიკად აკრძალულია!</strong></p>', NULL, '<p>ივნისი-ოქტომბერი, მაგრამ თუ კარგი ამინდია შესაძლებელია ზამთარშიც ასვლა.</p>', '<ol>\n	<li>20 ექსპრესი</li>\n	<li>თოკი 60მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:36:35', '2021-04-08 15:47:20'),
(18, 'ka', 'ცოცვა ჭაუხის ბანაკში', '<p>ალპინისტური მარშრუტების გარდა თქვენ შეგიძლიათ იცოცოთ სპორტული მარშრუტები ჭაუხის ბანაკში</p>', '<p>ჭაუხის მთები - ხევსურეთის ერთერთი მთის მასივია. უახლოესი სოფელი ჯუთაა. კლდოვანი მთები აგებულია მწვანე ალპური ბორცვებისგან. საბაზო ბანაკიდან ალპინისტური მარშრუტების ძირამდე&nbsp;1-2.5 საათში ადიხართ. აქვთ ყველანაირი მარშრუტი, რომელიც საინტერესოა როგორც დამწყებთათვის, ასევე პროფესიონალებისთვის. (ალპინიზმის შესახებ ინფორმაციისთვის <a href=\"http://climbing.ge/mountaineering\">იხილეთ აქ</a>).</p>\n\n<p>ალპური მარშრუტების გარდა, საბაზო ბანაკში&nbsp;შეგიძლიათ იცოცოთ სპორტული მარშრუტებიც.</p>', '<p>აქ არის მოკლე მარშრუტი (მაქსიმუმ 15 მ), არის რამდენიმე მარშრუტი ქვედა დაცვით და რამდენიმე ზედა დაცვითაც. ეს მარშრუტი ძირითადად დამწყებ ალპინისტებისთვისაა განკუთვნილი რათა მათ ისწავლონ ცოცვის&nbsp;ტექნიკური საფუძვლები, მაგრამ ასვლა კარგია 3-4 საათიანი სარელაქსაციო&nbsp;ცოცვისთვის.&nbsp;</p>', '<p>საპეხმავლო ბილიკი, რომელიც მიემართება ჭაუხის მწვერვალის მასივში,&nbsp;იწყება სოფელ ჯჟუტაში (2100 მ). სოფლის გავლის შემდეგ და მოხვდით ზეტას საბანაკეზე. (ბანაკში შეგიძლიათ მიიღოთ შხაპი, იკვებოთ და ღამე გაატაროთ, მაგრამ ალპინისტური ასცლისთვის საჭიროა უფრო მაღლა ასვლა) ბანაკის შემდენ კვლავ მივუყვებით ბილიკს გზად რამდენჯერმე გვიწევს ბილიკის გადაკვეთა.&nbsp;საპეხმავლო ბილიკი&nbsp;კარგად არის მონიშნული. ბალახოვანი პლატოებისა და ფერდობების გავლის შემდეგ თქვენ მოხვდებით მაღალ პლატოზე (cemp-1 2250 მ), მორენას ძირში აქ არის ჭაუხის მთავარი საბანაკე</p>', '<p>საუკეთესო პერიობი აქ საცოცად ხაპხულია. მაგრამ შესაძლებელია ცოცვა გაზაფხულის ბოლოს და შემოდგომის დასაწყისში.</p>', '<p>საცოცად დადჭირდებათ:</p>\n\n<ol>\n	<li>6 ექსპრესი</li>\n	<li>40m თოკი</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაოფხუტი&nbsp;(გირჩევთ ჩაფხუტის გამოყენებას თქვენივე უსაფრთხოებისთვის)</li>\n	<li>საცოცი ფეხსაცმელი (სკალნები) &amp; მაგნეზიის ტომარა</li>\n</ol>', '<p>მეტუ ინფპრმაციისთვის დაგვოკავშირდით.</p>\n\n<p>თემო - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:39:31', '2021-03-20 03:36:09'),
(19, 'ka', 'Bouldering Club Tbilisi', '<p>ალპინიზმის ფედერაციის დაქვემდებარებაში არსებული ვერის, ბოლდერინგის დარბაზი.</p>', '<p>ვერის საცოცი დარბაზი, არის ალპინიზმის ფედერაციის დაქვემდებარებში არსებული &nbsp;ბოლდერინგის დარბაზი. სიმაღლე 4,5მ. სხვადასხვა სირთულის მარშრუტებით, როგორც დამწყებთათვის, ასევე პროფესიონალი მცოცავებისთვის.</p>\n\n<p>ერთ თვიანი აბონიმენტის ღირებულება - 60 ლარი</p>\n\n<p>ერთჯერადი შემოსვლა - 10 ლარი.</p>\n\n<p>შესაძლებელია ერთჯერადად (1,5 საათი - 20 ლარი), ასევე ინდივიდუალური ვარჯიშებისთვის მწვრთნელის აყვანა.</p>\n\n<p>ასევე არის&nbsp;მწვრთნელი, რომელიც ამეცადინებს&nbsp;შეზღუდული შესაძლებლობის მქონე ბავშვებს და მოზარდებს ცოცვასა და იოგაში</p>\n\n<p>დარბაზი მუაშაობს 11:00 - 23:00 საათამდე, ყოველ დღე.</p>\n\n<p>ქირავდება:&nbsp;<br />\nსაცოცი კეტები -5 ლ<br />\nმაგნეზია - 5 ლ<br />\nტომარა - 5 ლ</p>\n\n<p>გაყიდვაშია:<br />\nმაგნეზია 100გრ &nbsp;- 20 ₾<br />\nმაგნეზია 200გრ - 37 ₾<br />\nმაგნეზიის ტომარა - 40 ₾</p>', NULL, '<p>დარბაზი მდებარეობს ჭადრაკის სასახლის შენობაში, პირველ სართულზე. შესასვლელი უკანა ( პარკინგის) მხრიდან.</p>\n\n<p>მისამართი:&nbsp;თბილისი, მერაბ კოსტავას 37ა.</p>\n\n<p>&nbsp;</p>', NULL, NULL, '<p>ოთო -&nbsp;(+995)&nbsp;514 51 11 77</p>\n\n<p>თემო - (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:49:57', '2022-05-09 09:35:05'),
(20, 'ka', 'S.K.Lucky', '<p>საცოცი დარბაზი s.k.lucky საქართველოში ერთ - ერთი საუკეთესო დარბაზია. აქ თქვენ შეგიძლიათ, როგორც ბოლდერინგის, ასევე სპორტული მარშრუტების ცოცვა</p>', '<p>საცოცი დარბაზი s.k.lucky საქართველოში ერთ - ერთი საუკეთესო დარბაზია. აქ თქვენ შეგიძლიათ, როგორც ბოლდერინგის, ასევე სპორტული მარშრუტების ცოცვა. სპორტული ცოცვის კედლის სიმაღლე 12მ არის, ბოლდერინგის კედლის - 4მ.</p>\n\n<p>აქ თქვენ დაგხვდებიან ინსტრუქტორები, რომლებიც დაგეხმარებიან აგირჩიონ გამოცდილებიდან გამომდინარე სხვადასხვა სირთულის მარშრუტები და დაგაზღვევენ, თუ თქვენ საცოცად მარტო ხართ მისული და მეწყვილე არ გყავთ.</p>', NULL, '<p>დარბაზი მდებარეობს უნივერსიტეტის ქ. N 6, სპორტკომპლექტ არენა ორის მე -2 - რე სართულზე.</p>\n\n<p>დარბაზში მისვლა შესაძლებელია, როგორც საქალაქო ტრანსპორტით, ასევე ტაქსით და ტქვენი საკუთარი მანქანით ( იხილეთ რუკა).</p>', NULL, NULL, '<p>Tel: (+995) 574 80 95 02</p>', NULL, NULL, '2021-03-02 14:52:39', '2021-10-07 12:02:34'),
(21, 'ka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-02 14:54:34', '2021-03-02 14:57:06'),
(22, 'ka', 'ყინულზე ცოცვა გველეთში', '<p>გველეთში ყინულზე ცოცვა ერთ-ერთი საუკეთესო ადგილია ამ საქმიანობისთვის.</p>', '<p>გველეთში ყინულზე ასვლა ერთ-ერთი საუკეთესო ადგილია ამ საქმიანობისთვის. დასაშვებია ვაჭრობა და მშრალი ხელსაწყოებით ასვლა.</p>', NULL, '<p>თბილისიდან უნდა იმოძრაოთ კაზბეკის &quot;სტეფანწმინდაზე&quot; 160 კმ-ზე და შემდეგ მიხვიდეთ კაზბეკიდან ვლადიკავკაზის 9 კმ-ზე და შეუხვიოთ მარცხნივ, სანამ გველეთში ხიდს გადაკვეთთ. შემდეგ პირდაპირ ადით ყინულის შემოდგომაზე. სიარულის დრო 20/30 წუთია, თოვლის მდგომარეობაზეა დამოკიდებული.</p>', '<p>ასვლისთვის საუკეთესო დროა იანვარი, თებერვალი და მარტის დასაწყისი, თუ სიცივეა.</p>', '<ol>\n	<li>თოკი 60 м (2 ც) ან&nbsp;120 м (1 ც)</li>\n	<li>წერაყინი (ყინულის ინსტრუმენტი)</li>\n	<li>წრიაპი</li>\n	<li>დაცვის სისტემა</li>\n	<li>კარაბინი (4-5 шт.)</li>\n	<li>დაცვის ქამარი</li>\n	<li>ჩაფხუტი</li>\n</ol>\n\n<p>ქვედათი ცოცვისთვის</p>\n\n<ol>\n	<li>7-8 ყინულის ბური და&nbsp;ექსპრესი</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:23:10', '2021-03-19 16:40:35'),
(23, 'ka', 'ყინულზე ცოცვა ატენის ხეობაში', '<p>ყინულზე ცოცვა საქართველოში საკმაოდ დამკვიდრებული სპორტული დისციპლინაა. ვარჯიშის ერთ-ერთი საუკეთესო ადგილი ატენის ხეობაშია, სოფელ ბიისთან ახლოს.</p>', '<p>ყინულზე ცოცვა საქართველოში საკმაოდ დამკვიდრებული სპორტული დისციპლინაა. ვარჯიშის ერთ-ერთი საუკეთესო ადგილი ატენის ხეობაშია, სოფელ ბიისთან ახლოს.</p>', NULL, '<p>დაგჭირდებათ თბილისიდან ბიისამდე დაახლოებით 1,5 საათი, მანძილი 130 კმ. მანქანის მთავარი მარშრუტის შემდეგ მარცხნივ შეუხვიეთ და შედიხართ სოფელში. გირჩევთ იმოგზაუროთ 4 &times; 4 მანქანით, რადგან ამ შემთხვევაში თქვენ შეძლებთ დამატებით 300 მეტრში ჩასვლას სოფელში, ქუჩის ცუდი პირობების გამო მთავარ გზაზე გაჩერებული მანქანის დატოვების გარეშე.</p>\n\n<p>თქვენი მანქანის გაჩერებასთან ერთად, 20 წუთის განმავლობაში უნდა იაროთ მდინარის გასწვრივ, სანამ არ მიაღწევთ გაყინული ჩანჩქერი, რომლის სიმაღლეა 60 მ, ხოლო სიგანე 5-15 მ.</p>\n\n<p>ახლომდებარე ქალაქები: 41 &deg; 51&#39;4 &quot;N 43 &deg; 59&#39;6&quot; E</p>\n\n<p>Დასარჩენი ადგილი<br />\nყინულის დაცემის ბოლოში ადგილი ღირსეულად დასაბანაკებლად - მაგრამ გაითვალისწინეთ, რომ ღამით ნამდვილად ცივდება!</p>', '<p>ეს დამოკიდებულია წლის ტემპერატურაზე, თებერვალი საუკეთესო თვეა ყინულზე ასვლისთვის.</p>', '<ol>\n	<li>თოკი 60 м (2 ც) ან&nbsp;120 м (1 ც)</li>\n	<li>წერაყინი (ყინულის ინსტრუმენტი)</li>\n	<li>წრიაპი</li>\n	<li>დაცვის სისტემა</li>\n	<li>კარაბინი (4-5 шт.)</li>\n	<li>დაცვის ქამარი</li>\n	<li>ჩაფხუტი</li>\n</ol>\n\n<p>ქვედათი ცოცვისთვის</p>\n\n<ol>\n	<li>7-8 ყინულის ბური და&nbsp;ექსპრესი</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:47:03', '2021-03-19 17:00:32'),
(24, 'ka', 'სლექლაინი საქართველოში', '<p>საქართველოში slackline არ არის განვითარებული დისციპლინა, მაგრამ ის ვითარდება.</p>', '<p>Slacklining - ეხება სიარულის ან დაბალანსების აქტს ბრტყელი ქსელის შეჩერებული სიგრძის გასწვრივ, რომელიც დაძაბულია ორ წამყვანს შორის. Slacklining მსგავსია თოკზე სიარული და მჭიდრო სიარული. Slacklines განსხვავდება მჭიდრო მავთულხლართებისა და გამკაცრებისგან გამოყენებული მასალის ტიპით და გამოყენების დროს გამოყენებული დაძაბულობით. სლაკლაინები მნიშვნელოვნად ნაკლებია ვიდრე მჭიდები და მავთულები, რათა შეიქმნას დინამიური ხაზი, რომელიც გრძელი და ვიწრო ბატუტივით გაიჭიმება და გადახტება. დაძაბულობის რეგულირება შესაძლებელია მომხმარებლის შესაბამისად, და სხვადასხვა გარემოში შეიძლება გამოყენებულ იქნას სხვადასხვა ქსელის გამოყენება. Slacklining პოპულარულია მისი სიმარტივისა და მრავალფეროვნების გამო; ის შეიძლება გამოყენებულ იქნას სხვადასხვა გარემოში, რამდენიმე კომპონენტით.</p>\n\n<p>სლაკლინი საქართველოში - საქართველოში slackline არ არის განვითარებული დისციპლინა, მაგრამ ის ვითარდება. უკვე მთელ საქართველოში არის რამდენიმე მაღალი ხაზის რეგიონი (მათგან ყველაზე პოპულარული რაჭაში არის &quot;9 ჯვარი&quot;). ვებგვერდზე slacklinegeorgia.wordpress.com შეგიძლიათ იხილოთ დეტალური ინფორმაცია საქართველოს ყველა რეგიონის შესახებ.</p>\n\n<p>როგორ ვიპოვოთ?<br />\nთბილისში, ყველაზე ხშირად ვიკრიბებით &#39;ვაკის პარკში&#39;, &#39;თბილისის ბოტანიკურ ბაღში&#39; (კლდეზე ასვლის ადგილას) და &#39;ლისის ტბაში&#39;. შეხვედრის ადგილის შესახებ ერთმანეთს ვაცნობთ facebook- ის გვერდზე &#39;Sleckline Georgia&#39;.</p>\n\n<p>ბათუმში, ყველაზე ხშირად ვიკრიბებით &#39;6 მაისის პარკში&#39; და &#39;თემ კაფე ბართან&#39;. შეხვედრის ადგილის შესახებ ერთმანეთს ვაცნობთ facebook- ის გვერდზე &#39;Sleckline ბათუმი&#39;.</p>\n\n<p>ქუთაისში ყველაზე ხშირად ვიკრიბებით &#39;ჩომაში&#39; (კლდეზე ასვლის ადგილას). ადგილობრივ ბიჭებს თემო ქუთდიანს შეგიძლიათ დაუკავშირდეთ facebook- ზე.</p>\n\n<p>ინფორმაცია:<br />\nფეისბუქის გვერდი: slackline Georgia</p>\n\n<p>თბილისი -&gt; ფოსტა: Iraklisixarulidze@gmail.com; Facebook: მტვრიანი ჩექმები.</p>\n\n<p>ბათუმი -&gt; ფეისბუქი: ერეკლე ჩიკვაიძე.</p>\n\n<p>ქუთაისი -&gt; ფეისბუქი: თემო ქუთდიანი</p>', NULL, NULL, NULL, NULL, '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 17:01:44', '2021-03-19 17:09:21'),
(25, 'ka', 'ჯავახიშვილის კლასიკური მარშრუტი (3A)', '<p>AD, M1, 300M, 5A (3A - RUS)</p>', '<p>P0 მარშრუტი იწყება ლეონიძე - ჯავახიშვილის კულვარიდან. კულვარიდან ავდივართ უნაგირამდე, სადაც გვხვდება დიდი ქვა. ქვის მარცხნივ ვპოულობთ ქიმზე აწყობილ სადგურს.</p>\n\n<p>P1 - სადგურიდან არის ასვლის 2 ვარიანტი. პირველი - სადგურის მარცხნივ არსებული ბუხარით გავდივართ ტაროზე, სადაც გვხვდება პალოებით აწყობილი სადგური.</p>\n\n<p>მეორე - სადგურის ტავზე არსებული ნაპრალი ავდივართ თაროზე.</p>\n\n<p>P2 - სადგურიდან გავდივართ მარჯვნივ. დახრილი ნაპრალით გავდივართ ბრტყელ გადაწვენილ კლდეზე. კედელზე შესაძლებელია ვიპოვოთ პალოები. ვაგრძლებეთ ცოცვას და ვპოულობთ სამ პალოზე აწყობილ სადგურს.</p>\n\n<p>P3 - სადგურიდან ვაგრძელებთ ცოცვას და გავდივართ თაროზე. ტაროზე ვპოულობთ ქიმზე აწყობილ სადგურს.</p>\n\n<p>Top - სადგურიდან მარცხნივ მივდივართ ბილიკით, რომელსაც გავყავართ მწვერვალ ჯავახიშვილზე.</p>\n\n<p><br />\n&nbsp;</p>\n\n<p>ჯავახიშვილის კლასიკური მარშრუტი, ყველაზე მარტივი გზაა მა მთის მწვერვალზე მოსახვედრად. მარშრუტი საკმაოდ მარტივია, ამიტომ მისი ცოცვა დამწყები ალპინისტისთვის იქნება ყველაზე კარგი. მათთვის ვინც ახლა იწყებს მთაში სიარულს. მარშრუტი სულ სამი თოკისგან შედგება, რომელთა სირთულე 5A -ს (F) არ აღემატება.</p>\n\n<p>პირველი საბანაკედან კულვარის ძირამდე მისასვლელად 2:00 &ndash; 2,5 საათი დაგჭირდებათ.</p>\n\n<p>მეორე საბანაკედან 00: 45 &ndash; 1,5 საათი.</p>\n\n<p>საშუალო დრო კულვარიდან წვერამდე 4- 5 საათი.</p>', NULL, '<p>.</p>', '<p>საუკეთესო დრო მარშრუტის გასავლელად ზაფხულის პერიოდია. ამ დროს ნალექების რაოდენობა ყველაზე მცირეა და ტემპერატურა ყველაზე მაღალი (12-დან - 0-გრადუსამდე), მაგრამ შესაძლებელია ზამთრის პერიოდში მარშრუტზე გასვლა. გასითვალისწინეთ: ზამთარში კულუარში ბევრად მეტი თოვლია და ხშირია ზვავები. ტემპერატურა -15-დან -25-თ გრადუსამდე მერყეობს.</p>', '<p>მარშრუტზე დაგჭირდებათ:</p>\n\n<ol>\n	<li>თოკი 50მ</li>\n	<li>ზაკლადკები 4-5ც საშუალო ზომები</li>\n	<li>ფრენდები 3-4ც საშუალო ზომები</li>\n	<li>ექსპრესი 4-5ც</li>\n	<li>კარაბინი 3-4ც და თვითდაცვა</li>\n	<li>დაცვის-დასაშვები სისტემა</li>\n	<li>დაცვის ქამარი და ჩაფხუტი</li>\n	<li>წრიაპი წერაყინი (დამოკიდებულია თოვლის სიმრავლეზე კულუარში)</li>\n</ol>\n\n<p>ზამთრის პერიოდში დამატებით დაგჭირდებათ&nbsp;</p>\n\n<ol>\n	<li>თოვლის ფეხსაცმელი (სნეგასტუპები)</li>\n	<li>ყინულის ბური 2-3ც</li>\n</ol>', '<p>თემო - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-10-03 03:01:13', '2022-08-23 16:01:04'),
(26, 'ka', 'ცოცვა ბორჯომის მუნიციპალიტეტში', '<p>ბორჯომი მდებარეობს აღმოსავლეთ საქართველოში&nbsp;აქ შეგიძლია დაკავდეთ, როგორც კლდეზე ცოცვით, ასევე სხვადასხვა აქტივობებით.</p>', '<p>ბორჯომი მდებარეობს აღმოსავლეთ საქართველოში,&nbsp; ზ.დ. 900მ. მოსახლეობა ~10000&nbsp;ადამიანი. ბორჯომის მუნიციპალიტეტი&nbsp;ძალიან პოპულარულია სათავგადასავლო ტურიზმის მოყვარულთა შორის. აქ შეგიძლია დაკავდეთ, როგორც კლდეზე ცოცვით, ასევე სხვადასხვა აქტივობებით: ტრეკინგი, ველო ტური, ჯომარდობა, და სხვა.</p>\n\n<p>დაბა ბორჯომიდან 10&nbsp;კმ - ში სოფელ ტიმოთესუბანში&nbsp;არის საცოცი რეგიონი. რეგიონის ისტორია წინა საუკუნის 80-იან წლებში იწყებ მაგრამ ბოლო 20-25 წელი&nbsp;რეგიონში არანაირი აქტიობა არ ჩატარებულა.&nbsp;2021წლის ზამთარში დაიწყო რეგიონის რეაბილიტაცია. დასაწყისისთვის გაკეთდა მხოლოდ 1 სექტორი და 8 მარშრუტი მაგრამ უკვე 2022 წლის ზაფხულისთვის იგეგმება მეტი მარშრუტის გაკეთება.</p>', '<p>2021წლის ზამთარში დაიწყო რეგიონის რეაბილიტაცია. დასაწყისისთვის გაკეთდა მხოლოდ 1 სექტორი და 8 მარშრუტი მაგრამ უკვე 2022 წლის ზაფხულისთვის იგეგმება მეტი მარშრუტის გაკეთება.</p>\n\n<p><strong>ქანი</strong> - ბაზალტი</p>\n\n<h3><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h3>\n\n<h3><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</h3>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>\n\n<h3><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</h3>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', '<p>დაბა ბორჯომიდან მივდივართ ბაკურიანის მიმართულებით 9კმ-ში შეგვხვდება სოფელი წაღვერი. წაღვერის ცენტრალური მოედნიდან ვუხვევთ მარცხნივ და მივდივართ 700-1000მ-ს&nbsp;სოფელ ტიმოთეს უბნისკენ. მარჯვენა მხარეს შეგვხდება წყარო ( და წყაროს ნიშანი). წყაროსთან ვპოულიბთ მდინარეზე გადასასვლელ ხიდს გადავდივართ ხიდზე და ვხვდებით კლდესთან. აქვე შესაძლებელია მანქანის გაჩერება. დეტალური ინფორმაციისთვის იხილეთ რუკა.</p>', '<p>საუკეთესო დრო რეგიონში საცოცად: გვიანი გაზაფხოლი,&nbsp;ზაფხული შემოდგომის შუამდე. კლდე დღის განმავლობაში უმეტესად ჩრდილშია აიტომაც დღის განმავლობაში უმეტესად ცივა. რეგიონში მისვლამდე გირჩევთ გადაამოწმოთ ამინდი.</p>', '<ol>\n	<li>14 ექსპრესი</li>\n	<li>თოკი 60მ</li>\n	<li>დაცვის ქამარი</li>\n	<li>დაცვის სისტემა</li>\n	<li>ჩაფხუტი</li>\n	<li>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</li>\n</ol>', '<p>თემო - Tel - (+995) 598457048.&nbsp;Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-21 14:30:47', '2022-03-09 16:00:24'),
(27, 'ka', 'ახალი საცოცი სექტორები ბორჯომში', '<p>ბორჯომის მუნიციპალიტეტი ერთერთი ყველაზე პოპულარულია ტურისტებისთვის და აქტიური დასვენების მოყვარულთათვის. ცოტახნის წინ აქ დაემატა ახალი საცცი სექტორი.</p>', '<p>დაბა ბორჯომი თბილისიდან 150კმ-ში მდებარეობს. ბორჯომის მუნიციპალიტეტი ერთერთი ყველაზე პოპულარულია ტურისტებისთვის და აქტიური დასვენების მოყვარულთათვის. აქ თქვენ შეგიძლებთ ეწვიოთ ბორჯომ-ხარაგაულის ეროვნულ პარკს, სადაც შეჯიძლიათ რტეკინგით დაკავება შესაძლებელია ასევე ეწვიოთ გოგირდის აბანოებს და დაკავდეთ ჯომარდობით.</p>\n\n<p>ბორჯომიდან 20კმ-ში მდებარეობს სოფელი ცემი სადაც საქაღთველოში ერთერთი ყველაზე ძველი საცოცი სექტორი მდებარეობს. სექტორში შეჯიბრები ჯერ კიდე მე-20 საუკუნის 80 წლებში ტარდებოდა. მიუხედავად ამისა ბოლო 20 წელია რეგიონი სრულიად მივიწყებულია და აქ სპორტული აქტიობები საერთოდ არ ტარდებოდა.&nbsp;საცოც სექტორში არ იყო არანაირი ინფროსტრუქტურა საცოცად რის გამოს აქ ცოცვა შეუძლებელი იყო.</p>\n\n<p>2021 წლის ნოემბრიდან დაიწყო სამუშაოები საცოცი სექტორის გასანახლებლად. პირველ ეტაპზე გაკეთდა მხოლოდ 3 საცოცი მარშრუტი, მაგრამ, უახლოეს მომავალში სამუშაოები გაგრძელდება და აქ გაკეთდება კიდევ უფრო მეტი საცოცი მარშრუტი.</p>\n\n<p><a href=\"https://climbing.ge/outdoor/climbing_near_borjomi\">რეგიონის დეტალური აღწერის სანახავად გადადით ბმულზე.</a></p>', NULL, '<p>საცოც სექტორში მისასვლელად <a href=\"https://www.google.com/maps/d/u/0/edit?mid=1zLk1GegB8K4QLJKiheI4D1odWy6rPffn&amp;ll=41.798991224962606%2C43.490982900000006&amp;z=18\">იხილეთ რუკა</a>.</p>', NULL, NULL, '<p><strong>თემო</strong> &nbsp;<strong>Tel:</strong> (+995) 598 45 70 48. <strong>Mail:</strong> samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-23 13:24:11', '2021-11-23 14:28:29'),
(28, 'ka', 'საცოცი დარბაზი სტეფანწმინდა (ყაზბეგი)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-05-05 14:52:52', '2022-05-05 14:59:15'),
(29, 'ka', '\"ილიაუნი\"', '<p>.</p>', '<p>მარშრუტი იწყება ლეონიძე - ჯავახიშვილის კულუარის მარცხნივ. ნაყარ ფერდზე შევდივართ კედლის ძირამდე, მარცხენა მხარეს ვპოულობთ ნაპრალს. აქედან იწყება მარშრუტი.</p>\n\n<p>P1 - ვიწყებთ ცოცვას ფართე ნაპრალში(დაცვისთვის გამოიყენეთ დიდი ზომის ჩასადები ელემენტები), გავდივართ ვიწრო ტაროზე და ქიმზე ვაკეთებთ დაცვას.</p>\n\n<p>P2- სადგურიდან ვაგრძელებთ ცოცვას მარჯვნივ. 4-5 მეტრიანი კედლით გავდივართ დიდ ტაროზე, გავდივართ მარჯვნივ და ქიმზე ვაკეთებთ სადგურს, დიდი ბუხრის ძირში.</p>\n\n<p>P3 - ვიწყებთ ცოცვას ბუხარში, ბუხრის მარჯვნივ და მარცხენა კედელზე ბევრი ნაპრალია დაცვის გასაკეთებლად. ბუხრის თავზე გვხვდება დიდი ქვების ვარდნის შედეგად წარმოქმნილი ჭერი. მარცხენა მხარეს ვპოულობთ ვიწრო გასაძრომს და გავდივართ თაროზე. გასაძრომის თავთან გვხვდება ქიმზე აწყობილი სადგური.</p>\n\n<p>P3- P4- სადგურის მარცხნივ ერთდროული სიარულით მივუყვებით თაროს, ავდივართ 5მ სიმაღლის მარტივ კედელზე და ვხვდებით დიდი კედლის ძირას არსებულ ტაროზე.</p>\n\n<p>P4- დიდ კედელზე შუაში მივუყვებით ღარს (ყველაზე დიდი ღარი), მივყვებით ღარს, მარჯვენა მხარეს გვხვდება თარო და თაროს 4მ მაღლა ვპოულობთ 2 პალოს, რომელზეც ვაწყობთ სადგურს.</p>\n\n<p>P5 - ვაგრძელებთ ცოცვას ღარში, სანამ არ გადავალთ გადაწვენილ კედელზე, ნატეხი, ნაშალი ქანით.</p>\n\n<p>კედელზე ბევრია ვიწრო ღარი. ვაგრძელებთ ცოცვას კედლის შუაში და ვხვდებით ვიწრო თაროზე, სადაც ვაკეთებთ სადგურს (სადგურის გაკეთება ამაზე მაღლა რეკომენდირებული არ არის).</p>\n\n<p>P6 - ვაგრძელებთ ცოცვას პირდაპირ, სანამ არ მოვხვდებით დიდი ბუხრის ქვემოთ, დიდ თაროზე. ბუხრის ძირში ვაკეთებთ სადგურს.</p>\n\n<p>P7- ვაგრძელებთ ცოცვას დიდ ბუხარში, სანამ არ გავალთ მორიგ დიდ ტაროზე. აქედან ვიწყებთ ერთდროულად სიარულს.</p>\n\n<p>TOP - თაროდან ვაგრძელებთ ერთდროულად ცოცვას, მარტივ რელიეფზე. გავდივართ მწვერვალის ძირში. პატარა თაროზე ვუვლით მწვერვალს დასავლეთის მხრიდან ( მივდივართ მარცხნივ) ვხვდებით ბუხარში. ბუხრიდან გასვლისთანავე ვხვდებით მწვერვალზე.</p>\n\n<p>საშუალო დრო მარშრუტის ძირიტად მწვერვალამდე 4-6 საათი.</p>', NULL, '<p>პირველი ბანაკიდან მარშუტის ძირამდე მისასვლელად 2,0&nbsp;&ndash; 3,0 საათი დაგჭირდებათ.</p>\n\n<p>მეორე ბანაკიდან 0.5&nbsp;-1,0&nbsp;საათი.</p>\n\n<p>&nbsp;</p>', '<p>დილის საათებში მარშრუტი მზეშია, ამიტომ მისი ცოცვა სასიამოვნოა, როგორც ზაფხულში, ასევე ზამტარში.</p>\n\n<p>საუკეთესო დრო მარშრუტის გასავლელად ზაფხულის პერიოდია. ამ დროს ნალექების რაოდენობა ყველაზე მცირეა და ტემპერატურა ყველაზე მაღალი (12-დან - 0-გრადუსამდე), მაგრამ შესაძლებელია ზამთრის პერიოდში მარშრუტზე გასვლა. გასითვალისწინეთ: ზამთარში კულუარში ბევრად მეტი თოვლია და ხშირია ზვავები. ტემპერატურა -15-დან -25-თ გრადუსამდე მერყეობს.</p>', '<p>მარშრუტზე დაგჭირდებათ:</p>\n\n<ol>\n	<li>თოკი 50მ</li>\n	<li>ზაკლადკები 4-5ც საშუალო ზომები</li>\n	<li>ფრენდები 3-4ც საშუალო ზომები</li>\n	<li>ექსპრესი 4-5ც</li>\n	<li>კარაბინი 3-4ც და თვითდაცვა</li>\n	<li>დაცვის-დასაშვები სისტემა</li>\n	<li>დაცვის ქამარი და ჩაფხუტი</li>\n	<li>წრიაპი წერაყინი (დამოკიდებულია თოვლის სიმრავლეზე კულუარში)</li>\n</ol>\n\n<p>ზამთრის პერიოდში დამატებით დაგჭირდებათ&nbsp;</p>\n\n<ol>\n	<li>თოვლის ფეხსაცმელი (სნეგასტუპები)</li>\n	<li>ყინულის ბური 2-3ც</li>\n</ol>', '<p>თემო - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-08-23 15:24:25', '2022-09-21 15:50:39'),
(30, 'ka', 'ლეონიძე კლასიკა (4A)', '<p>მწვერვალი ლეონიძის კლასიკური მარშრუტი. (4A - RUS)</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:35:54', '2022-08-23 15:39:23'),
(31, 'ka', '\"დიდი დიღომი\"', '<p>.</p>', '<p>მარშრუტის პირველი გავლა - ბიძინა გუჯაბიძე და ალექსანდრე გუჯაბიძე</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:40:16', '2022-08-23 15:43:52');
INSERT INTO `locale_articles` (`id`, `locale`, `title`, `short_description`, `text`, `route`, `how_get`, `best_time`, `what_need`, `info`, `price_text`, `address`, `created_at`, `updated_at`) VALUES
(32, 'ka', 'კამერუნი კლასიკა (3A)', '<p>.</p>', '<p>მარშრუტი იწყება ასათიანი - კამერუნის კულუარის დასაწყისში. გრძელი კულუარით ავდივართ უნაგირზე (კულუარის გავლის საშუალო დრო 2-3 საათი.) კულუარის მარცხენა კედელზე ვიყყებთ ცოცვას.</p>\n\n<p>P1 -&nbsp;ვიწყებთ ცოცვას კედელზე, თანდათან შევდვართ მარცხენა მხარეს. ვპოულობთ პალოს და რეფშნურს აქედან ისევ მარცხნივ ცოცვით შევდივართ ქვიან წედზე სადაც ვპოულობთ პალოებზე აწყობილ სადგურს.</p>\n\n<p>P2 -&nbsp;ბაგრძელებთ ცოცვას ზემოთ და გავდივართ ქედზე. ვატრავერსებთ მარცხნივ და ქედის ყველაზე მაღალ წერტილში ვპოულობთ ქიმზე აწყობილ სადგურს.&nbsp;</p>\n\n<p>TOP -&nbsp;სადგურინან ჩავდივართ 6-7 მექრს ქვემოთ და ვხვდებით სწორ ბაქანზე (თაროზე). აწედან ერთდროული სიარულით ვაგრძელებთ მწვერვალისკენ სიარულს. მწვერვალს ვუვლით&nbsp;მარჯვენა მხრიდან და ავდივართ ვიწრო მოსწორებული ზედაპირის მწვერვალზე (მწვერვალზე გვხდება ტური)</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:45:32', '2022-09-22 04:54:02'),
(33, 'ka', 'ასათიანი კლასიკა (4ა)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 16:01:40', '2022-08-23 16:07:39'),
(34, 'ka', 'მყინვარწვერი კლასიკური მარშრუტი (2A)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 17:03:42', '2022-08-23 17:05:23'),
(36, 'ka', 'ცოცვა გველეთში', '<p>.</p>', '<p><strong>გველეთში ცოცვა</strong><br />\nმანძილი ყაზბეკიდან: 8 კმ (დარიალის ხეობაში). ადგილზე არის 2&nbsp;მარშრუტი.</p>', '<p>ამ რეგიონში მუდმივად ვამატებთ ახალ მარშრუტებს. მარშრუტები 5A- დან 7A- მდეა. აგრეთვე გვაქვს 3-4&nbsp;თოკიანი მარშრუტი (მულტიპიჩი).</p>\n\n<p>უსაფრთხოების მიზნით, გამოიყენეთ ჩაფხეტი!</p>\n\n<p>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია !<br />\nიმ შემთხვევაში, თუ თქვენ აპირებთ მატშტუტების გაკეთებას ჭიათურაში აუცილებლად შეატყობინეთ ადგილობრივ მეკლდეურებს. წინააღმდეგ შემთხვევაში თქვენი მარშრუტი შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p>&quot;Drytooling&quot;-ის ცოცვა რეგიონში სასტიკად აკრძალულია!</p>', NULL, '<p>ივნისი-ოქტომბერი, მაგრამ თუ კარგი ამინდია შესაძლებელია ზამთარშიც ცოცვა.</p>', '<p>20 ექსპრესი<br />\nთოკი 60მ<br />\nდაცვის ქამარი<br />\nდაცვის სისტემა<br />\nჩაფხუტი<br />\nსაცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</p>', '<p>თემო: Mail - samsonadze.temo9@gmail.com, Tel - (+995) 598 45 70 48</p>\n\n<p>გუგა: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 09:30:21', '2022-09-05 11:46:26'),
(37, 'ka', 'ცოცვა ანანურთან', '<p>.</p>', '<p>ცოცვა ანანურთან</p>', '<h2><strong>საცოც რეგიონში მარშრუტების კეთება(დაშლამბურება) ადგილობრივი მეკლდეურების გარეშე სასტიკად აკრძალულია&nbsp;!</strong></h2>\n\n<p><br />\nიმ შემთხვევაში, თუ აპირებთ მარშრუტების გაკეთებას ამ რეგიონში, გთხოვთ, აცნობოთ ადგილობრივ მეკლდეურებს თქვენი გეგმების შესახებ, წინააღმდებ შემთხვევაში თქვენი მაშრუტები შესაძლებელია დემონტაჟს დაექვემდებაროს.</p>\n\n<p><strong>აგრეთვე აკრძალულია Drytooling-ის ცოცვაც!</strong></p>\n\n<p><strong>გამოიყენეთ ჩაფხუტი თქვენი უსაფრთხოებისათვის !</strong></p>', NULL, NULL, '<ol>\n	<li>\n	<p>10&nbsp;ექსპრესი</p>\n	</li>\n	<li>\n	<p>თოკი 40მ</p>\n	</li>\n	<li>\n	<p>დაცვის ქამარი</p>\n	</li>\n	<li>\n	<p>დაცვის სისტემა</p>\n	</li>\n	<li>\n	<p>ჩაფხუტი</p>\n	</li>\n	<li>\n	<p>საცოცი ფეხსაცმელი (სკალნი) &amp; მაგნეზიის ტომატა</p>\n	</li>\n</ol>\n\n<p><br />\n&nbsp;</p>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2022-09-05 09:41:36', '2022-09-05 11:58:46'),
(38, 'ka', 'Advanture 2022 - ჭიათურის ტურისტული ფესტივალი', NULL, '<p><img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" />&nbsp;16-17-18 სექტემბერი&nbsp;<img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" /></p>\r\n\r\n<p><img alt=\"🫵\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc8/1/16/1faf5.png\" style=\"height:16px; width:16px\" />&nbsp;მოდი ჭიათურის ტურისტულ ფესტივალზე და მოიწყვე დაუვიწყარი თავგადასავალი</p>\r\n\r\n<p><img alt=\"🚀\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc6/1/16/1f680.png\" style=\"height:16px; width:16px\" />&nbsp;აღმოაჩინე სხვა ჭიათურა:</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ქემფინგი/ Camping;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (მოყვარული) / Rock Climbing (amateur);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (პროფესიონალი) / Rock Climbing (professional);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჰაილაინი / Highline;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ნაოსნობა ტბაზე / Lake Boating;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;სპელეოტურიზმი ძუძუანას და კოტიას მღვიმეში / Speleotourism in Dzudzuana and Kotika caves;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />ველოშეჯიბრი მანდაეთში/Cycling competition in Mandaeti.</p>\r\n\r\n<p>ფესტივალის განრიგი :</p>\r\n\r\n<p>16 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურა</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - პრეზენტაცია და პანელ - დისკუსია, ჭიათურის კულტურის სახლში.</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - ტური &ldquo;ჯორჯიან მანგანეზის&rdquo; ტურისტულ პროექტში (საბაგირო, კორტი და სხვა).</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Loud Speakers</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კაცხი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (მოყვარულები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ჰაილაინის შოუ</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - 17:00 ვია ფერატა ინფო ტური (​​მონაწილეები დაიყოფიან ოთხ ჯგუფად)</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;17 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ძუძუანას და კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;09:00 - 10:00 რეგისტრაცია ლაშქრობაში (მონაწილეები დაიყოფიან სამ ჯგუფად)</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - სპექტაკლი &ldquo;დოვინ დოვენ დოვლი&rdquo;</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;18 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;მანდაეთის ტბა</p>\r\n\r\n<p><img alt=\"🕙\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t31/1/16/1f559.png\" style=\"height:16px; width:16px\" />&nbsp;10:00 ველო შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ნავებით შეჯიბრი ტბაზე</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ცენტრი</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Tamada</p>\r\n\r\n<p><img alt=\"🏕\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tdc/1/16/1f3d5.png\" style=\"height:16px; width:16px\" />16 სექტემბერს 10:00 საათზე გაიხსნება ქემფინგ ზონა ჩამოსული სტუმრებისთვის, რომელიც იფუნქციონირებს 18 სექტემბრის ჩათვლით.</p>\r\n\r\n<p><img alt=\"🎶\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/1/16/1f3b6.png\" style=\"height:16px; width:16px\" />&nbsp;TAMADA და Loudspeakers</p>\r\n\r\n<p><img alt=\"🥪\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tfc/1/16/1f96a.png\" style=\"height:16px; width:16px\" /><img alt=\"🥤\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4f/1/16/1f964.png\" style=\"height:16px; width:16px\" />&nbsp;სთრით ფუდი და გამაგრილებელი სასმელები</p>\r\n\r\n<p><img alt=\"💥\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t40/1/16/1f4a5.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურაში ამაღელვებელი, მომნუსხველი, გემრიელი და მისტიკით სავსე 3 დღე გელოდება.</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ტურისტული ფესტივალის გენერალური სპონსორია &quot;ჯორჯიან მანგანეზი&quot;</p>\r\n\r\n<p><img alt=\"🤝\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t64/1/16/1f91d.png\" style=\"height:16px; width:16px\" />პარტნიორები: ჭიათურის მუნიციპალიტეტის მერია, სვერის სათავგადასავლო ბანაკი და DMO იმერეთი.</p>\r\n\r\n<p><img alt=\"🔗\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\" style=\"height:16px; width:16px\" />&nbsp;მხარდამჭერები: ტურიზმის ეროვნული ადმინისტრაცია.&nbsp;</p>\r\n', NULL, NULL, NULL, NULL, 'guga - ', NULL, NULL, NULL, NULL),
(40, 'ka', 'ახალი საცოცი სექტორი ანანურთან', '<p>.</p>', '<p>გუგა დაბრუნდაშვილმა და სოფო ცელიძემ ანანურთან 3 ახალი მარშრუტი გააკეთეს. ახლა რეგიონში მხოლოდ 3 მარშრუტი, მაგრამ შესაძლებელია მეტი მარშრუტის დამატება.</p>\n\n<p>დამატებითი ინფორმაციისთვის სექტორის მდებარეობისა და მარშრუტების შესახებ იხილეთ ამ - <a href=\"https://climbing.ge/outdoor/Climbing_near_Ananuri\">გვერდზე</a>.</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:06:23', '2022-09-05 12:15:55'),
(41, 'ka', 'ახალი საცოცი მარშრუტები გველეთში', '<p>.</p>', '<p>გუგა დაბრუნდაშვილმა გველეთში ახალი სპორტული ცოცვის მარშრუტები გააკეთა.</p>\n\n<p>იხილეთ მეტი ინფორმაცია კლდეების შესახებ ამ - <a href=\"https://climbing.ge/outdoor/Climbing_in_Gveleti\">გვერდზე</a>.</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:16:02', '2022-09-05 12:23:23'),
(42, 'ka', 'ახალი საცოცი საქტორი და 5 ახალი საცოცი მარშრუტი ბორჯომთან, სოფელ მზეთამზესთან.', '<p>.</p>', '<p>თემო სამსონაძემ სოფელ მზეთამზეში, ბორჯომთან ახლოს, ახალი სექტორი&nbsp;და 5 ახალი სპორტული ცოცვის მარშრუტი გააკრა.</p>\n\n<p>დამატებითი ინფორმაცია ამ ტერიტორიის შესახებ იხილეთ ამ - <a href=\"https://climbing.ge/outdoor/Climbing_near_Borjomi\">გვერდზე</a>.</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:24:07', '2022-09-05 12:36:05'),
(43, 'ka', 'ახალი საცოცი მარშრუტევი ციხისძირში ხღვის ნაპირას', '<p>.</p>', '<p>თემო სამსონაძემ ციხისძირში ზღვის ნაპირას ახალ სექტორში 3 ახალი მარშრუტი გააკეთა.</p>\n\n<p>მეტი ინფორმაცია იხილეთ ამ - <a href=\"https://climbing.ge/outdoor/Climbing_At_The_Black_Sea_Coast\">გვერდზე</a>.</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:36:13', '2022-09-05 12:49:52'),
(44, 'ka', 'ქართველმა მთამსვლელებმა ოქროს წერაყინით დააჯილდოვეს', '<p>ქართველი მთამსვლელები ოქროს წერიყინით&nbsp;დააჯილდოვეს პაკისტანში, პაკისტანში&nbsp;მწვერვალ სარაგრზე&nbsp;ასვლისთვის პირველი ასვლისთვის.</p>', '<p>სარაგრარის ჩრდილო-დასავლეთი, პირველი ასვლა ჩრდილო-დასავლეთით, 2,300 მ,</p>\n\n<p>ED2 ან რუსული/კავკასიური შეფასება 6B, 6b M5+ A3+ 80-90&deg;,</p>\n\n<p>2021 წლის 3-10 სექტემბრიდან.</p>\n\n<p>სარაგრარის მასივს, ავღანეთის საზღვრის სამხრეთით, დღევანდელ&nbsp;პაკისტანის &quot;ჰინდუ კუში&quot; - ს&nbsp;აქვს რამდენიმე მწვერვალი, მაგრამ მხოლოდ რამდენიმე მთამსვლელმა მიაღწია მათ. დღემდე მხოლოდ სარაგრარის ჩრდილო-დასავლეთი კედელზე არც ერთი წარმატებული ასვლა არ ყოფილა (7,300 მ),&nbsp;მიუხედავად ხანგრძლივი და გამბედავი მცდელობებისა სამხრეთ-დასავლეთის კედელზე სამი ძლიერი ესპანური ექსპედიციის მიერ 1970-იან და 80-იანი წლების დასაწყისში. მათგან უკანასკნელი ჩრდილო-დასავლეთის ქედზე დაახლოებით 7150 მეტრზე ავიდა.&nbsp;ამ ადგილს,&nbsp;მათ დაარქვეს სარაგრარი ჩრდილო-დასავლეთი II. ქართველმა ალპინისტებმა&nbsp;არჩილ ბადრიაშვილმა, ბაქარ გელაშვილმა და გიორგი ტეფნაძემ აირჩიეს მანამდე აუსვლელი ჩრდილო-დასავლეთი კედელი.&nbsp;საკვანძოა ამ კედლის&nbsp;ზედა ციცაბო ნაწილი, რომელიც ერწყმის ჩრდილო-დასავლეთ ქედს. მათ&nbsp;გადაწყვიტეს მცდელობა გაეკეთებინათ სექტემბერში, იმაზე უფრო გვიან, ვიდრე ჩვეულებრივ.&nbsp;დღის სიცხის შესამცირებლად და&nbsp;შესაბამისად, ქვათაცვენმის შესამცირებლად.</p>\n\n<p>საბაზო ბანაკიდან დაახლოებით 4200 მეტრზე - &quot;როშ გოლში&quot; და მცირე წინასწარი აკლიმატიზაციის პირობებში სამივე &bdquo;გახურდა&ldquo; სამხრეთის და აღმოსავლეთის ქედის პირველი აღმართით (1800 მ, TD 60&deg;-ყინული 75&deg;- თოვლი) ლანგუტა-ე-ბარფის (6,833 მ), ერთი ბივუაკით 6,400 მ. ეს იყო მწვერვალზე მეოთხე ასვლა, მაგრამ პირველი პაკისტანიდან.</p>\n\n<p>3 სექტემბერს, ალპინისტებმა დაიწყეს ასვლა - სარაგრარის&nbsp;ჩრდილო-დასავლეთის კედელზე. დაახლოებით&nbsp;5000 მეტრზე,&nbsp;განაგრძეს მარშრუტი ყინულის უზარმაზარი კულუარით, რომელიც ახასიათებს ქვედა ნახევარს, რათა გაეკეთებინათ პირველი ბივუაკი 6200 მეტრზე. ზემო ნაწილის უმეტესობას შეადგენდა ციცაბო გრანიტის კედელი. რთული მონაკვეთის თავისუფალი და დამხმარე საყრდენებით ცოცვით&nbsp;გადალახვის შემდენ,&nbsp; მათ ჰქონდათ რამდენიმე ღამის თენება კედელზე: 6400 მეტრზე, 6600 მეტრზე, 6.750 მეტრზე, 6.850 მეტრზე და დაახლოებით 7000 მეტრზე კედლის თავზე. მეხუთე და მეექვსე დღეები დაეთმო 250 მეტრიანი &quot;ჰედვოლის&quot; გადალახვას.</p>\n\n<p>ბოლო ბივუაკი გააკეთეს&nbsp;ესპანეთის მარშრუტის გასასვლელთან და მერვე დღეს ავიდნენ&nbsp;მწვერვალზე. მათ იმავე დღეს შეძლეს დიდი კედლის თავზე&nbsp;მდებარე ბივუაკში&nbsp;დაბრუნება და მეორე დილით დაიწყეს ჩრდილო-დასავლეთის მხარეს დაშვება, რომელიც გაგრძელდა მთელი ღამის განმავლობაში, სანამ არ მიაღწიეს მარშრუტის სათავეს.</p>\n\n<p>Info from -&nbsp;<a href=\"https://pioletsdor.net/index.php/en/home/2022-honoured-ascents/141-saraghrar-northwest-7-300m?mibextid=Zxz2cZ\" target=\"_blank\">pioletsdor.net</a></p>', NULL, NULL, NULL, NULL, '<blockquote>\n<p>&nbsp;</p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Посмотреть эту публикацию в Instagram</a></p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Публикация от UFGM.ORG (@ufgm_org)</a></p>\n</blockquote>', NULL, NULL, '2022-11-20 06:22:07', '2022-11-21 17:52:24'),
(101, 'ru', 'Скалолазание в Свери (Чиатура)', '<p>Зона для скалолазания Свери расположена в небольшой причудливой деревне в районе Имерети на западе Грузии.<br />\nСкалолазание - относительное занятие в этой области.</p>', '<p>Зона для скалолазания Свери расположена в небольшой причудливой деревне в районе Имерети на западе Грузии.<br />\nСкалолазание - относительное занятие в этой области. Большинство маршрутов проложены совсем недавно, а все маршруты были открыты в 2018 году.</p>\n\n<p>ПОХОД</p>\n\n<p>Остановиться можно в палатках возле альпинистской зоны.<br />\nСтавить палатки прямо под камнями не рекомендуется из-за риска.<br />\nкамнепада, а также его небольшая площадь под линиями для установки палатки</p>\n\n<p><br />\nЗАПАСЫ ЕДЫ</p>\n\n<p>Продовольственные магазины можно сделать в городе Чиатура.<br />\nЗдесь есть большой местный фермерский рынок и множество магазинов и множество пекарен, предлагающих свежий хлеб, выпекаемый ежедневно.<br />\nК сожалению, в Свери нет продуктового рынка, поэтому вы должны<br />\nВозьмите с собой все свои принадлежности перед тем, как отправиться в зону восхождения.<br />\nВ качестве источника воды рядом с местом для скалолазания есть несколько источников, или вы можете<br />\nТакже попросите воды у дружелюбных местных жителей.<br />\nВесной дикие фрукты можно найти в случайных местах, или можно спросить дружелюбных местных жителей, не хотите ли вы сорвать фрукты.</p>\n\n<p>ДЕНЬ ОТДЫХА</p>\n\n<p>В Свери есть несколько старых замков и монастырей, а также много хороших мест для посещения.<br />\nТакже город Чиатура - еще одно отличное место для изучения с его длинными<br />\nистория добычи полезных ископаемых, пронизанная множеством пещер.<br />\nЕсть также водопады, в которых можно купаться в жаркие летние дни.<br />\nБольшинство местных жителей очень дружелюбны, как и во всей Грузии.</p>', '<p>К зоне скалолазания относительно легко добраться.<br />\nСкалы в Свери обращены на север. Отсутствие прямого солнечного света в течение дня приводит к тому, что на северных склонах прохладнее.<br />\nБольшинство маршрутов большую часть дня находятся в тени, что делает Свери, а идеальным для лазания являются жаркие летние дни.<br />\nБольшинство маршрутов имеют длину 10-25 метров с отметками от 5A до 7C.<br />\nЭтот район по-прежнему имеет большой потенциал и открыт для новых заведений.<br />\nЕсли вы решите открыть и закрепить новые стропы или вам понадобится гид по скалолазанию, пожалуйста, свяжитесь с нашим местным пионером скалолазания Гуга Дабрунда.</p>\n\n<p>Высота линий от 10м до 25м. Оценки от 5а до 7с Оценка Франции.</p>\n\n<p>Площадка не свободна для крепления !!!<br />\nВ случае, если вы планируете приехать на болтовню в Грузию, сообщите о своих планах местному сообществу.</p>\n\n<p>Любая строка, не соответствующая нашим стандартам, будет удалена.</p>', '<p>Поселок Свери находится недалеко от ближайшего крупного города Чиатура в 170 км от Тбилиси.<br />\nДоехать до Свери можно на маршрутке Маршрутка на станции Дидубе в Тбилиси до города Чиатура. Из Чиатуры вы можете сесть на маршрутку или такси до города Свери, который находится дальше 11 км.<br />\nОт автобусной остановки в деревне Свери до места для скалолазания около 400 метров.<br />\nРайон не обозначен полностью, но совершенно очевидно, что можно найти место для лазания, следя за скалами.<br />\nВ конце села указатель &laquo;Зона скалолазания&raquo;, а оттуда еще метров 50 до скал.<br />\nВы также можете спросить у местных, как добраться до скал &laquo;клдекари&raquo;.</p>', '<p>За исключением зимы, альпинизм в Свери идеален с весны до осени. Северные скалы обеспечивают прохладу даже в жаркие летние дни.<br />\nСредняя температура по Цельсию весной: Высокая 24 &deg; / Низкая 11 &deg;.<br />\nЛетом высокая 30 &deg; / низкая 18 &deg; и осень высокая 20 &deg; / 8 &deg;</p>', NULL, '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 07:04:04', '2021-03-02 15:05:43'),
(106, 'ru', 'Скалолазание в Кацхи (Чиатура)', '<p>Кацхи - одно из самых популярных и лучших мест для восхождения в Грузии. Качество скалы идеально подходит для разных вершин лазания.</p>', '<p>Кацхи - одно из самых популярных и лучших мест в Грузии. Качество скалы отличное для разных видов лазания. Есть как отрицательные маршруты, так и прямые плитки, что позволяет спортсмену максимально развиваться и прочувствовать все стили лазания.</p>', '<p>Маршруты классифицируются по французской системе. С 5а по 8б.</p>\n\n<p><strong>Используйте шлем для вашей безопасности!</strong></p>\n\n<p><strong>напивание маршрутов в регионе, ​​категорически запрещено!</strong><br />\nЕсли вы планируете набивать маршруты в этом регионе, сообщите о своих планах местным скалолазам, в противном случае ваши маршруты могут быть разобраны.</p>\n\n<p><strong>Восхождение с драитулингам также запрещено!</strong></p>', '<p>Кацхи находится примерно в 180 км от столицы Тбилиси. На машине вам понадобится примерно 2 часа.</p>\n\n<p>от аэропорта Кутаиси 1 час</p>', '<p>Кацхи находится на высоте 700 м над уровнем моря. Лучшее время для восхождения с сентября по июль. Август - жаркое время для восхождений, но в тени или пасмурную погоду тоже приятно лазить. Есть солнечные и постоянно тенистые места для лазания.</p>\n\n<p>некоторые трассы покрыты сверху большими деревьями, а некоторые выступают над ними, так что по ним можно подняться даже в дождливую погоду.</p>', '<p>&nbsp;</p>\n\n<ol>\n	<li>12 оттяжка</li>\n	<li>Веревка 60 м</li>\n	<li>Обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:31:38', '2021-03-20 04:15:25'),
(107, 'ru', 'Скалолазание на Монументе Лоткина', '<p>Добро пожаловать к монумент Лоткину. Это 40-метровая бетонная конструкция.</p>', '<p>Добро пожаловать к монумент Лоткину.</p>\n\n<p>Это 40-метровая бетонная конструкция. Здание находится на окраине города, и попасть в суд не составит труда. Проект только начался, но после его завершения можно будет пройти 2-х канатный мульти-питч. Также можно лазить по нитке.</p>', '<p>На сегодняшний день существует 6 трасс для спортивного скалолазания и 1 трасса для скалолазания.</p>\n\n<p>Самая высокая из 2-х линий - 40 м, 1-я линия - 30 м и 3 линии - 20 м. класс 5с / 7а</p>\n\n<p>В середине 40-метровых линий - верхние цепи.</p>', '<p>Из метро &laquo;<a href=\"https://goo.gl/maps/Rjou6mefmr52\">Привокзальная площадь</a>&raquo; к верхнему выходу курсируют автобусы №110, №111, №48 (<a href=\"http://goo.gl/maps/EG9Emms68YN5yGvC7\">stop 1</a>) и №36 (<a href=\"https://goo.gl/maps/2XZPEGVgAEP7QezZ8\">stop 2</a>) до (<a href=\"https://www.google.com/maps/d/u/0/viewer?mid=1Nzu-J7c65ivpXrma5iAdWye1a7nhYslt&amp;ll=41.72681806910237%2C44.81414870000003&amp;z=15\">см. Карту</a>)</p>', '<p>Подниматься сюда можно круглый год, в зависимости от погоды.</p>', '<ol>\n	<li>20 оттяжек</li>\n	<li>(мин.) 40 м - (макс.) Веревка 80 м</li>\n	<li>Обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p><strong>Guga</strong> - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:36:24', '2021-03-20 04:40:19'),
(108, 'ru', 'Скалолазание в Чиатуре', '<p>Чиатура - одно из самых перспективных мест для скалолазания в Грузии.</p>', '<p>Чиатура - одно из самых перспективных мест для скалолазания в Грузии. На этой скале в 70-х и 80-х годах проходили чемпионаты Советского Союза.</p>', '<p>Эта область находится в стадии разработки. Маршруты бывают разной классификации от 5а до 7а. Есть только одна линейка 8а сорт. И еще 3 мульти-питча.</p>\n\n<p><strong>Для вашей безопасности используйте халмет!</strong></p>\n\n<p><strong>Площадка не свободна для крепления !!!</strong><br />\nВ случае, если вы планируете приехать на болтовню в Грузию, сообщите о своих планах местному сообществу.</p>\n\n<p>Любая строка, которая не соответствует нашим стандартам, будет удалена.</p>', '<p>Чиатура находится в 180 км от столицы Тбилиси. На машине вам понадобится примерно 2 часа.</p>\n\n<p>Остановиться в городе можно в общежитии или на альпинистской базе, расположенной в 9 км от центра в селе Кацхи.</p>', '<p>Зависит от погоды.</p>', '<ol>\n	<li>20 оттяжка</li>\n	<li>веревка 60 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:38:50', '2021-03-21 06:56:56'),
(109, 'ru', 'Скалолазание в Местии (Чалаади)', '<p>.</p>', '<p>Сванетия&nbsp;&mdash; одно из самых красивых мест в Грузии.</p>\n\n<p>Есть много видов активного спорта и исторических и красивых мест для посещения.</p>\n\n<p>Местия &mdash; центр Сванетии, расположенный на высоте 1500 м над уровнем моря и в 460 км в 8 часах езды от Тбилиси.</p>', '<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>Используйте шлем для вашей безопасности!</strong></p>', NULL, '<p>Лучшее время для восхождений конец лета начало осени. Район скалолазания легко добраться автостопом, он находится в 10 км от центра города.</p>', '<ol>\n	<li>14 оттяжка</li>\n	<li>веревка 70 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:41:37', '2022-03-09 16:37:29'),
(110, 'ru', 'Скалолазание в Дзеври', '<p>.</p>', '<p>Трассы для скалолазания проходят очень близко к дороге, и большинство из них имеют комфортную стартовую площадку.</p>\n\n<p>Можно разбить лагерь в районе скалолазания.</p>\n\n<p>Рядом со скалами есть ресторан и рынок.</p>\n\n<p>Местные жители очень дружелюбны к сообществу альпинистов.</p>\n\n<p>Скала имеет различную экспозицию и даже в жару на солнце можно найти тенистое место для подъема.</p>', '<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>', '<p>Расстояние от Тбилиси 200 км 3 часа езды.</p>\n\n<p>Расстояние от кутаисского аэропорта &laquo;Копитнари&raquo; 60 км, 1 час езды. (Показать карту)</p>', '<p>Лучший сезон для восхождений начало лета и осень, но при хорошей погоде можно лазить в течение всего года.</p>', '<ol>\n	<li>14 оттяжка</li>\n	<li>веревка 60 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:44:09', '2022-04-09 16:42:40'),
(111, 'ru', 'Скалолазание в Кутаиси(Чома)', '<p>В Кутаиси нет множества скалолазных трасс, но для приятного времяпровождения вполне достаточно. Сложность трасс колеблется от 5А до 7А , что идеально для начинающих скалолазов.</p>', '<p>Кутаиси второй по величине город в Грузии. Население --147&nbsp;635 человек.</p>\n\n<p>Кутаиси расположен на расстоянии 235 км. от Тбилиси. Высота над уровнем моря 125-300 метров. В городе нет множества скалолазных трасс, но для приятного времяпровождения вполне достаточно. Сложность трасс колеблется от 5А до 7А , что идеально для начинающих скалолазов.</p>', NULL, '<p>В Кутаиси можно легко доехать из любого города Грузии. Сюда едет муниципальный транспорт из всех городов Грузии. В случае путешествия на собственном автомобиле используйте GPS навигатор. Также в 5ти километрах от города находится аэропорт. Полёты осуществляются как внутри страны так и за рубеж.</p>\n\n<p>Для похода по скальным маршрутам воспользуйтесь картой.</p>\n\n<p>Заночевать можно в одной из гостинец в Кутаиси или в палатке непосредственно возле скал.</p>\n\n<p>&nbsp;</p>', '<p>Лучшее время для скалолазания здесь &ndash; весна и осень. Летом здесь очень жарко, а зимой холодно. Но в тёплые зимние дни возможно тренироваться.</p>', '<p>12 оттяжка<br />\nВеревка 60 м<br />\nОбвязка<br />\nСтраховочное устройство<br />\nШлем<br />\nОбувь и сумка для мела</p>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:46:18', '2021-11-16 16:10:56'),
(112, 'ru', 'Скалолазание в Арша', '<p>Казбеги (Степанцминда), расположенный на севере и 1750 над уровнем моря, является одним из самых популярных и туристических мест в Грузии.</p>', '<p>Арша расположен на севере Грузии , от конца деревни Степанцминда в 5ти километрах и от Тбилиси в 160 ти километрах. Высота над уровнем моря 1500 метров, население 700 человек.</p>\n\n<p>Среди любителей экстримального туризма регион Степанцминда очень популярен. Здесь можно заняться как скалолазанием так и трекингом , велотуризмом, джомардингом, альпинизмом, полетать на параплане и многое другое. Любителям альпинизма советуем также посетить Казбегский и Чаухский районы. Также в 6ти километрах находится другой скалолазный массив &ndash; Цдо ,который также интересен для альпинистов.</p>\n\n<p>Сложность маршрутов в регионе колеблется от 5А до 7В ,поэтому это отличное место для начинающих спортсменов и любителей. Также возможно мультипическо лазание.</p>\n\n<p><br />\nРасстояние от Казбеги: 3 км. К региону недавно приехали, здесь 30 спортивных трасс. Возможны традиционные и многоступенчатые лазания. Высота стены: 120 м, ширина: 250 м.</p>', '<p>&nbsp;</p>\n\n<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>используете краску для вашей безопасности!!!</strong></p>', '<p>Для приезда в Аршу необходимо от Казбега доехать до Степанцминда муниципальным транспортом и отсюда пешком пройти 1,5 километра. Можно в Казбеги арендовать такси.</p>\n\n<p>В случае поездки на своём автомобиле &ndash; машина должна быть 4х4 (смотрите карту).</p>\n\n<p>В Степанцминда и в Арше множество гостиниц ,в которых можно остановиться на ночлег (цены от 30 лари). В Арше так же есть место для кемпинга ,в 10 -15 минутах ходьбы от скал.</p>', '<p>Так как этот скальный сектор расположен в высокогорном регионе ,тренировки здесь возможны в конце весны , летом и в начале осени. Зимой из за низкой температуры воздуха и частых снегопадов тренировки невозможны. Зимой советуем потренироваться на Гвелетском замёрзшем водопаде.</p>', '<p>Вам понадобится</p>\n\n<ol>\n	<li>\n	<p>20 оттяжка</p>\n	</li>\n	<li>\n	<p>веревка 60 м</p>\n	</li>\n	<li>\n	<p>обвязка</p>\n	</li>\n	<li>\n	<p>Страховочное устройство</p>\n	</li>\n	<li>\n	<p>Шлем (используйте шлем для вашей безопасности!)</p>\n	</li>\n	<li>\n	<p>Обувь и сумка для мела</p>\n	</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:51:02', '2022-09-05 11:47:59'),
(113, 'ru', 'Скалолазание на Черноморском побережье', '<p>Если вы хотите заняться скалолазанием на берегу моря, побережье Черного моря предлагает некоторые возможности для этого.</p>', '<p>Если вы хотите заняться скалолазанием на берегу моря, побережье Черного моря предлагает некоторые возможности для этого.</p>', '<p>На некоторых маршрутах есть новые болты, в то время как большинство было прикручено довольно давно. Поэтому лучше лезть по канату. В остальном качество камня достойное. Сложность маршрутов варьируется от 5А до 7A.</p>', '<p>Расстояние от столицы Тбилиси до Сарпи (прямо у турецкой границы) - 380 км. Как вариант, вы можете прилететь в аэропорт Батуми. Скалы для скалолазания находятся в 10 км отсюда, до них легко добраться на маршрутке.</p>\n\n<p>Где остановиться<br />\nВы можете остановиться в частных домах или в отелях в Сарпи, Гонио или Квариати (все они рядом). Цены начинаются от 25-30 лари (около 10-15 евро) и растут в зависимости от сезона.</p>', '<p>У вас есть возможность лазать как на солнце, так и в тени, хотя в летний сезон следует учитывать жару. Лучшее время<br />\nзаниматься альпинизмом май, июнь, сентябрь и октябрь.</p>', '<ol>\n	<li>20 оттяжка</li>\n	<li>веревка 60 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p><strong>Guga </strong>- Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:53:23', '2021-04-11 07:35:06'),
(114, 'ru', 'скалолазание в биртвиском канёне', '<p>Биртвийский каньон расположен в 40 км.&nbsp;от Тбилиси.&nbsp;На скалолазном участке трассы в основном болдеринговые маршруты , но есть и спортивные трассы.</p>', '<p>Биртвийский каньон расположен в 40 километрах от Тбилиси . высота над уровнем моря&nbsp;1077 метров. Здесь вы сможете заняться скалолазанием ,трекинингом, каньёнингом. На скалолазном участке трассы в основном болдеринговые маршруты , но есть и спортивные трассы.</p>', '<p>Сложность маршрутов колеблется&nbsp;от&nbsp;5А до 7С,&nbsp;болдеринг&nbsp;V1 - V7,&nbsp;&nbsp;поэтому тренировки для любителей и начинающих спортсменов идеальны</p>\n\n<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>используете краску для вашей безопасности!!!</strong></p>', '<p>Дорога до скального сектора и каньона начинается от деревни Тбиси , здесь вам придётся оставить машину и пройти пешком 45 &ndash; 60 минут по лесной зоне ( см. карту ) Необходимо иметь машину 4х4. Ночёвка возможна здесь только в палатках. Место для палаток в середине скального участка и легко и быстро (5минут) можно добраться до маршрута . Воду можно набирать выше кемпинга по дороге протекает река .</p>', '<p>Наилучшее время для посещения Биртвийского каньона - весна и осень .В летнее время в каньоне очень жарко. А зимой достаточно холодно . но бывают и тёплые зимние дни . когда возможна тренировка</p>', '<p>Для спортивного скалолазания вам понадобятся:</p>\n\n<ol>\n	<li>Веревка 50 м</li>\n	<li>10 Экспресс</li>\n	<li>Страховочная устройство и&nbsp;обвязка</li>\n	<li>шлем</li>\n</ol>\n\n<p>Для боулдеринга вам понадобится &quot;крэшпад&quot;&nbsp; (<a href=\"https://images.app.goo.gl/X598rhKxzugftkZA8\">crash bad</a>)</p>', '<p>Guga: <strong>Tel</strong>. (+995)&nbsp;557 300 700 <strong>Mail</strong>.&nbsp;gugadabrunda@gmail.com</p>\n\n<p>Temo: <strong>Mail</strong>. samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 13:55:39', '2021-10-07 15:49:06'),
(115, 'ru', 'Скалолазание в \"Тбилисском ботаническом саду\"', '<p>Грузинские альпинисты всех десятилетий занимались альпинизмом в Ботаническом саду - одном из лучших мест для скалолазания в Тбилиси.</p>', '<p>Ботанический сад прекрасное место для отдыха и хорошего времяпровождения. Основан в 1845 году и занимает площадь 126 гектаров. Ботанический сад расположен в исторической части города Тбилиси южнее хребта Сололаки , в долине реки Легвта - Хеви , из -за перепадов высоты по ходу русла реки можно полюбоваться водопадами.</p>\n\n<p>На территории ботанического сада имеется скалодром. Этот участок для скальных тренировок имеет свою историю. Грузинские спортсмены , прошлого века с 70х годов и до сегодняшнего дня тренируются здесь. Место прекрасное для тех кто никогда не тренировался на природных скалах.</p>', '<p>Помимо верхних веревочных маршрутов, маршрут № 9, 10, 13, 14, 15 и 22 закреплены болтами. № 1 и 2 - это проекты, которые еще предстоит закрепить. Маршруты боулдеринга не обозначены на скалах, но легко узнаваемы, их можно увидеть на фотографиях здесь.</p>\n\n<p>У вас также есть возможность пройти 80-метровый траверс через все скалы Ботанического сада.</p>', '<p>На скалодром можно обраться двумя путями.</p>\n\n<p>Первый : через главный вход ботанического сада войти может каждый желающий. Учтите , что вход платный.</p>\n\n<p>Второй : возле крепости Нарикала есть вход. Этим входом пользуются только работники ботанического сада и грузинские спортсмены &ndash; альпинисты, которые постоянно здесь тренируются и об этих людях у охраны есть сведения</p>', '<p>В ботаническом саду тренироваться можно в любое время года. В летнее время лучше всего тренироваться когда солнце не освещает скалы ( после 17.00). Зимой же наоборот - . в утренние часы до 15.00 , когда солнце согревает скалы и приятно лазать по маршрутам.</p>', '<p>вам понадобятся:</p>\n\n<ol>\n	<li>Веревка 50 м</li>\n	<li>10 Экспресс</li>\n	<li>Страховочная устройство и обвязка</li>\n	<li>шлем</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 14:29:06', '2021-10-08 15:06:14'),
(116, 'ru', 'Скалолазание в Навардзети (чиатура)', '<p>Поселок &laquo;Навардзети&raquo; находится в 4,5 км от Чиатуры в направлении большого альпинистского района &laquo;каччи&raquo;.</p>', '<p>Поселок &laquo;Навардзети&raquo; находится в 4,5 км от Чиатуры в направлении большого альпинистского района &laquo;каччи&raquo;.</p>\n\n<p>Линии для скалолазания новые, регион находится в стадии разработки, поэтому на сегодняшний день у нас есть только 7 маршрутов, но вскоре наш клуб &laquo;Клуб грузинских альпинистов&raquo; планирует закрепить все возможные веревки в районе.</p>\n\n<p>Высота скалы 30/40 м, но максимальная высота линий 30 м.</p>\n\n<p>Оценка от главной дороги пешком около 200 м.</p>\n\n<p>На главной дороге возле площадки для скалолазания можно набрать родниковую воду.</p>\n\n<p>Вы можете использовать кемпинг альпинистов в качи, чтобы остаться во время путешествия.</p>', '<p>Площадка не свободна для крепления !!!<br />\nВ случае, если вы планируете приехать на болтовню в Грузию, сообщите о своих планах местному сообществу.</p>\n\n<p>Любая строка, не соответствующая нашим стандартам, будет удалена.</p>\n\n<p>На территории разрешено восхождение по традиции. В зоне для скалолазания запрещен сухой толлинг.</p>\n\n<p>кемпинг под веревками запрещен !!!!!!!</p>\n\n<p>для вашей безопасности всегда используйте шлем !!!!!!!!!</p>', '<p>Из Чиатуры в Навардзет ходит маршрутка. Также можно воспользоваться маршруткой до Кацхи и сойти возле села Навардзети.</p>', '<p>Большой плюс местности в том, что после 15:00 скала находится в тени, поэтому летом можно лазить в тени.</p>', '<ol>\n	<li>20 оттяжка</li>\n	<li>веревка 60 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:31:44', '2021-04-07 16:48:06'),
(117, 'ru', 'Скалолазание в Цдо', '<p>Цдо - небольшая деревня недалеко от Казбеги, красивое место. Это прекрасное место для занятий спортом и лазанием по многим веревкам.</p>', '<p>Горный туризм является основным направлением в Казбеги (вершина 5047 м), однако создание соответствующей инфраструктуры для восхождений продолжается. Деревня Цдо находится от казбека примерно в 7 км.</p>', '<p>Мы постоянно добавляем новые маршруты в этот регион. Маршруты варьируются от 5А до 8А. Также у нас есть 3, 2 веревочные маршруты (мультиплеер).</p>\n\n<p>В целях безопасности используйте долото!</p>\n\n<p>Совершать маршруты (саботаж) в районе Сацоц без местных слесарей категорически запрещено!<br />\nЕсли вы планируете делать матрас в Чиатуре, обязательно сообщите об этом местным пекарям. В противном случае ваш маршрут может быть разобран.</p>\n\n<p>Скалолазание &quot;Drytooling&quot; в регионе категорически запрещено!</p>', NULL, '<p>Июнь-октябрь, но при хорошей погоде можно подняться даже зимой.</p>', '<ol>\n	<li>20 оттяжка</li>\n	<li>веревка 60 м</li>\n	<li>обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности!)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:36:35', '2021-04-08 15:47:20'),
(118, 'ru', 'Скалолазание в лагере Чаухи', '<p>Помимо альпийского стиля, вы можете пройти спортивный маршрут на базовый лагерь Чаухи.</p>', '<p>Горы Чаухи - место в провинции Хевсурети. Ближайшая деревня - Джута. Скалистые горы построены из зеленых альпийских холмов. Альпинистские навыки очень близки к базовому лагерю. Здесь у очень маленьких массивных альпинистов есть всевозможные маршруты, которые интересны как новичкам, так и профессионалам. (информацию о альпинизме <a href=\"http://climbing.ge/mountaineering\">смотрите здесь</a>).</p>\n\n<p>В дополнение к альпийскому стилю, вы можете отправиться в поход по спортивной тропе в Bace Camp.</p>', '<p>Здесь небольшой маршрут (максимум 15 м), 3 трассы с болтовым креплением и канатная дорога. В основном этот маршрут предназначен для тренировок по восхождению в альпийском стиле, но он хорош для лазания с отдыхом на 3-4 часа. Район подходит для начинающих альпинистов.</p>', '<p>Маршрут Эври, ведущий к массиву Пик Каухи, начинается с одного лагеря (ЛАГЕРЬ 1). Маршрут по горному массиву Чаухи начинается в поселке Джута (2100 м). Пройдя деревню и доберитесь до кладбища Зета. в лагере можно принять душ, поесть и переночевать. От лагеря ехать по реке Каухи, трасса обозначена хорошо. Пройдя через покрытые травой плато и склоны, вы выйдете на высокое плато (cemp-1 2250м), на краю которого стоят три копания (10м). На dhese bolders есть колья и есть возможность заняться скалолазанием. Это плато отлично подходит для кемпинга.</p>', '<p>Лучшее время для восхождения на чаухи - с середины июля до середины сентября.</p>', '<p>Для скалолазания на базовый лагерь чаухи вам понадобятся:</p>\n\n<ol>\n	<li>6 оттяжка</li>\n	<li>Веревка 40 м</li>\n	<li>Обвязка</li>\n	<li>Страховочное устройство</li>\n	<li>Шлем (используйте шлем для вашей безопасности)</li>\n	<li>Обувь и сумка для мела</li>\n</ol>', '<p>For more information about climbing area contact me.</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:39:32', '2021-03-20 03:36:09'),
(119, 'ru', 'Bouldering Club Tbilisi', '<p>Зал болдеринга на Вере при Федерации альпинизма.</p>', '<p>Верийский боулдеринговый зал находится под эгидой федерации альпинизма, это небольшой зал для болдеринга. Высота стены&nbsp;4,5 метра .Здесь множество стен с разнообразными рельефами, много различных маршрутов и приятная дружеская обстановка, которая даст вам стимул для тренировок и совершенствования техники скалолазания.</p>\n\n<p>В зале нет аренды инвентаря для тренировок и поэтому необходимо иметь с собой своё снаряжение.</p>', NULL, '<p>Зал расположен в здании дворца шахмат, на первом этаже. Вход с задней( парковочной )стороны. Адрес : г.Тбилиси улица Мераба Костава 37-А</p>', NULL, NULL, '<p>Oto -&nbsp;(+995)&nbsp;514 51 11 77</p>\n\n<p>Temo - (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:49:57', '2022-05-09 09:35:05'),
(120, 'ru', 'S.K.Lucky', '<p>Зал для тренировок по лазанию S.K.LUCKU. &ndash; один из лучших залов в Грузии. Здесь вы можете заняться как болдерингом так и маршрутами</p>', '<p>Зал для тренировок по лазанию S.K.LUCKU. &ndash; один из лучших залов в Грузии. Здесь вы можете заняться как болдерингом так и маршрутами. Высота стен для спортивного лазания 12 метров, для болдеринга 4 метра. В зале вас встретят инструкторы и помогут вам выбрать маршрут в зависимости от сложности и вашего опыта и обеспечат страховку в случае если вы пришли один и нет у вас партнёра.</p>\n\n<p>В зале есть всё необходимое снаряжение и оборудование для того , что бы вы комфортно себя чувствовали и насладились процессом тренировки. Учтите , что аренда снаряжения платная</p>', NULL, '<p>Зал находится по адресу: Тбилиси улица университетская 6 ,спорт комплекс Арена 2, второй этаж.</p>\n\n<p>Сюда можно добраться как городским транспортом так и на личном автомобиле или такси( смотрите карту).</p>', NULL, NULL, '<p>&nbsp;</p>\n\n<p>Tel: (+995) 574 80 95 02</p>', NULL, NULL, '2021-03-02 14:52:39', '2021-10-07 12:02:34'),
(121, 'ru', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-03-02 14:54:34', '2021-03-02 14:57:06'),
(122, 'ru', 'Ледолазание в Гвелети', '<p>Ледолазание в Гвелети - одно из лучших мест для этого занятия.</p>', '<p>Ледолазание в Гвелети - одно из лучших мест для этого занятия. лазание на территории с использованием традиционных и сухих инструментов разрешено.</p>', NULL, NULL, '<p>Лучшее время для восхождения - январь, февраль и начало марта в холодную погоду.</p>', '<ol>\n	<li>Веревка 60 м (2 шт.) Или 120 м (1 шт.)</li>\n	<li>Ледоруб</li>\n	<li>Кошки</li>\n	<li>Страховочное устройство</li>\n	<li>Карабины (4-5 шт.)</li>\n	<li>Обвязка</li>\n	<li>Шлем</li>\n</ol>\n\n<p>Для лазания</p>\n\n<ol>\n	<li>7-8 Ледобур и Quickdraw</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:23:10', '2021-03-19 16:40:35'),
(123, 'ru', 'Ледолазание в долине Атени', '<p>Ледолазание - довольно устоявшаяся спортивная дисциплина в Грузии. Одно из лучших мест для занятий спортом - в долине Атени недалеко от деревни Бийс.</p>', '<p>Ледолазание - довольно устоявшаяся спортивная дисциплина в Грузии. Одно из лучших мест для занятий спортом - в долине Атени недалеко от деревни Бийс.</p>', NULL, NULL, '<p>В зависимости от температуры года февраль - лучшее время для ледолазания.</p>', '<ol>\n	<li>Веревка 60 м (2 шт.) Или 120 м (1 шт.)</li>\n	<li>Ледоруб</li>\n	<li>Кошки</li>\n	<li>Страховочное устройство</li>\n	<li>Карабины (4-5 шт.)</li>\n	<li>Обвязка</li>\n	<li>Шлем</li>\n</ol>\n\n<p>Для лазания</p>\n\n<ol>\n	<li>7-8 Ледобур и Quickdraw</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:47:03', '2021-03-19 17:00:32');
INSERT INTO `locale_articles` (`id`, `locale`, `title`, `short_description`, `text`, `route`, `how_get`, `best_time`, `what_need`, `info`, `price_text`, `address`, `created_at`, `updated_at`) VALUES
(124, 'ru', 'Слэклайн в Грузии', '<p>В Грузии слэклайн - это не развитая дисциплина, но она развивается.</p>', '<p>Slacklining - это ходьба или балансировка по подвешенной длине плоской лямки, натянутой между двумя анкерами. Слаклайнинг похож на хождение по слабой веревке и хождение по канату. Слаклайны отличаются от канатов и канатов типом используемого материала и величиной натяжения, прилагаемого во время использования. Стропы стропы натянуты значительно меньше, чем канаты или канаты, чтобы создать динамичную стропу, которая будет растягиваться и подпрыгивать, как длинный и узкий батут. Натяжение можно регулировать в соответствии с потребностями пользователя, и в различных обстоятельствах могут использоваться различные ремни. Slacklining популярен благодаря своей простоте и универсальности; его можно использовать в различных средах с небольшим количеством компонентов.</p>\n\n<p>Slacline в Грузии - В Грузии Slackline - это не развитая дисциплина, но она развивается. По всей Грузии уже есть несколько высокогорных регионов (самый популярный из них - &laquo;9 джвари&raquo; в Раче). На сайте slacklinegeorgia.wordpress.com вы можете найти подробную информацию по всем регионам Грузии.</p>\n\n<p>Как найти нас?<br />\nВ Тбилиси мы чаще всего собираемся в &laquo;парке Ваке&raquo;, &laquo;Тбилисском ботаническом саду&raquo; (в районе скалолазания) и на &laquo;озере Лиси&raquo;. Мы информируем друг друга о месте встречи на странице факсбука &laquo;Слеклайн Грузия&raquo;.</p>\n\n<p>В Батуми мы чаще всего собираемся в &laquo;Парке 6 мая&raquo; и возле &laquo;Tema Caffe Bar&raquo;. Мы информируем друг друга о месте встречи на странице факсбука &laquo;Sleckline Batumi&raquo;.</p>\n\n<p>В Кутаиси мы чаще всего собираемся в &laquo;Чома&raquo; (в районе скалолазания). Вы можете связаться с местными парнями Темо Кутдиани на facebook.</p>\n\n<p>Информация:<br />\nСтраница Facebook: Slackline georgia</p>\n\n<p>Тбилиси -&gt; Почта: Iraklisixarulidze@gmail.com; Facebook: пыльные сапоги.</p>\n\n<p>Батуми -&gt; Facebook: Ирекле Чикваидзе.</p>\n\n<p>Кутаиси -&gt; Facebook: Темо Кутдиани.</p>', NULL, NULL, NULL, NULL, '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 17:01:44', '2021-03-19 17:09:21'),
(125, 'ru', 'Джавахишвили класика (3A)', '<p>AD, M1, 300M, 5A (3A - RUS)</p>', '<p>Для восхождения требуется всего три веревки и по сложности маршрут не превосходит 5А</p>\n\n<p>В0 &ndash; маршрут начинается с кулуара Леонидзе-Джавахишвили, оттуда поднимаемся до седловины ,где находится большой камень. Слева от камня видим собранную станцию.</p>\n\n<p>Р1-от станции возможны&nbsp;два варианта подъема Первый- от станции слева ,по камину ,придерживаясь правой стороны -выходим на полку. Второй- над станцией есть трещина, лезть по правой стороне трещины и&nbsp;выходим на полку.</p>\n\n<p>Р2- от станции идем направо по наклонной расщелине и выйдем на тонкую, с положительным уклоном скалу. На стене можно найти, продолжаем лезть и находим станцию, собранную на трех элементах.</p>\n\n<p>Р3- от станции продолжаем подъем и выходим на полку где находим станцию на валуне. От станции налево идем по тропинке ,которая выводит нас на вершину Джавахишвили. Классический маршрут Джавахишвили ,самый простой путь на вершину этой горы.</p>\n\n<p>&nbsp;</p>\n\n<p>Маршрут достаточно прост ,поэтому подойдет для начинающих альпинистов. Для тех кто только начинает восхождения в горах.</p>\n\n<p>От первого лагеря до основания кулуара требуется 2-2,5 часа времени. От второго лагеря 0,45-1,5 часа.</p>\n\n<p>&nbsp;</p>\n\n<p><strong>Среднее время от кулуара до вершины всего 4-5 часов.</strong></p>', NULL, NULL, '<p>Лучшее время для&nbsp;маршрута&nbsp;- лето. В это время выпадает меньше всего осадков, а температура самая высокая (от 12 до 0 градусов), но на маршрут&nbsp;можно выходить&nbsp;и зимой. Примечание: зимой в кулуаре&nbsp;намного больше снега и часты лавины. Диапазон температур от -15 до -25 градусов.</p>', '<p>На маршруте&nbsp;вам понадобятся:</p>\n\n<ol>\n	<li>Веревка 50 м</li>\n	<li>Закладка 4-5&nbsp;(средних размеров)</li>\n	<li>Камелоты 3-4 (средних размеров)</li>\n	<li>Оттяжки 4-5</li>\n	<li>Карабин 3-4с и самостраховка</li>\n	<li>Спусковое - страховачная устроиство</li>\n	<li>Обвязка и шлем</li>\n	<li>Кошки и ледоруб (в зависимости от количества снега в кулуаре)</li>\n</ol>\n\n<p>Зимой вам понадобится дополнительное снарежение</p>\n\n<ol>\n	<li>Снегастапы</li>\n	<li>Ледобуры 2-3 части</li>\n</ol>', '<p>Темо - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-10-03 03:01:13', '2022-08-23 16:01:04'),
(126, 'ru', 'Скалолазание в муниципалитете Боржоми', '<p>Боржоми находится в Восточной Грузии, здесь можно заняться скалолазанием, а также различными видами активного отдыха.</p>', '<p>Боржоми находится в Восточной Грузии, З.Д. 900 м. Население ~ 10 000 человек. Муниципалитет Боржоми очень популярен среди любителей приключенческого туризма. Здесь можно заняться скалолазанием, а также различными видами деятельности: треккингом, велотуром, рафтингом и прочим.</p>\n\n<p>В 10 км от Боржоми находится поселок в поселке Тимотесубани. История области восходит к 80-м годам прошлого века, но в последние 20-25 лет никакой деятельности в области не велось. Зимой 2021 года началась реабилитация района. Изначально был пройден только 1 сектор и 8 маршрутов, но уже к лету 2022 года планируется сделать больше маршрутов.</p>', '<p>Зимой 2021 года началась реабилитация района. Изначально был пройден только 1 сектор и 8 маршрутов, но уже к лету 2022 года планируется сделать больше маршрутов.</p>\n\n<p>Камень - базальт</p>\n\n<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>Используйте шлем для вашей безопасности!!!</strong></p>', '<p>Едем из Боржоми в Бакуриани, через 9 км встретим село Цахвери. С центральной площади Цахвери поворачиваем налево и идем 700-1000 м в район села Тимоте. Справа встретим источник (и отметку источника). У истока находим мост через реку переходим мост и встречаем скалу. Здесь можно поставить машину. Подробности смотрите на карте.</p>', '<p>Лучшее время для лазания в регионе: поздний газ, лето до середины осени. Днем скала в основном находится в тени, поэтому днем ​​в основном холодно. Мы рекомендуем вам проверить погоду до прибытия в регион.</p>', '<ol>\n	<li>14 Экспресс</li>\n	<li>Веревка 60 м</li>\n	<li>Защитный пояс</li>\n	<li>Система защиты</li>\n	<li>шлем</li>\n	<li>Скутеры и помидоры Магнезия</li>\n</ol>', '<p>Temo - Tel - (+995) 598457048.&nbsp;Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-21 14:30:47', '2022-03-09 15:32:46'),
(127, 'ru', 'Новые скалолазные сектора в Боржоми', '<p>Муниципалитет Боржоми - один из самых популярных у туристов и любителей активного отдыха. Недавно здесь был добавлен новый скалолазныей сектор.</p>', '<p>Боржоми находится в 150 км от Тбилиси. Муниципалитет Боржоми - один из самых популярных у туристов и любителей активного отдыха. Здесь можно посетить национальный парк Боржоми-Харагаули, где можно заняться трекингом, а также посетить серные бани и заняться рафтингом.</p>\n\n<p>В 20 км от Боржоми находится село Цеми, где находится одно из старейших скалолазныей сектор&nbsp;Грузии. Соревнования&nbsp;проводились еще в 80-х годах 20 века. Тем не менее, последние 20 лет регион был полностью забыт, и спортивные мероприятия здесь не проводились. Инфраструктуры в секторе не было, поэтому лазить сюда было невозможно.</p>\n\n<p>В ноябре 2021 года начались работы по обновлению сектора. На первом этапе было проложено всего 3 маршрута, но в ближайшее время работы будут продолжены и здесь будет проложено еще больше маршрутов движения.</p>\n\n<p><a href=\"https://climbing.ge/outdoor/climbing_near_borjomi\">Перейдите по ссылке, чтобы увидеть подробное описание региона.</a></p>', NULL, NULL, NULL, NULL, '<p><strong>Temo -</strong>&nbsp;&nbsp;<strong>Tel:</strong> (+995) 598 45 70 48. <strong>Mail:</strong> samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-23 13:24:11', '2021-11-23 14:28:29'),
(128, 'ru', 'Скалодром в Степанцминде (Казбеги)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-05-05 14:52:52', '2022-05-05 14:59:15'),
(129, 'ru', '\"Илиаун\"', '<p>.</p>', '<p>Маршрут начинается слева от кулуара Леонидзе - Джавахишвили. Поднимаемся по крутому склону к низу стены, находим трещину с левой стороны. Отсюда начинается маршрут.</p>\n\n<p>P1 - начинаем лезть в широкую щель (для защиты использовать крупногабаритные вставные элементы), проходим по узкому таро и делаем защиту на хим.</p>\n\n<p>От станции Р2 продолжаем подъем вправо. Проходим через 4-5 метровую стену к большому таро, идем направо и делаем станцию ​​на Химе, у подножия большой трубы.</p>\n\n<p>P3 - Начинаем лезть в камин, на правой и левой стенке камина много щелей, чтобы сделать защиту. Над камином потолок, образованный падением больших камней. С левой стороны находим узкий проход и идем к полке. Рядом с заправкой есть химическая станция.</p>\n\n<p>P3- P4- идем одновременно налево от станции, следуем по полке, взбираемся на простую стену высотой 5м и находим основание большой стены на существующем таро.</p>\n\n<p>P4- идём по канавке посередине большой стены (самая большая канавка), идём по канавке, с правой стороны находим полку и на 4м выше полки находим 2 палоса на которых ставим станцию.</p>\n\n<p>P5 &mdash; Продолжаем карабкаться по канавке, пока не приходим к зеленой стене с разбитой, разбитой скалой.</p>\n\n<p>На стене много узких канавок. Продолжаем карабкаться по середине стены и находим узкую полку, где делаем станцию ​​(выше этого делать станцию ​​не рекомендуется).</p>\n\n<p>P6 &mdash; Продолжаем подниматься прямо, пока не доберемся до большой полки под большим камином. Делаем станцию ​​у подножия камина.</p>\n\n<p>P7- Мы продолжаем подниматься по большому дымоходу, пока не пройдем еще одно большое таро. Отсюда мы начинаем идти одновременно.</p>\n\n<p>ВЕРХ - с полки продолжаем подъем одновременно, по легкому рельефу. Идем к подножию пика. Проходим пику по небольшой полке с западной стороны (идем налево) и находим камин. Как только выходим из камина, оказываемся наверху.</p>\n\n<p>Среднее время от начала маршрута до вершины 4-6 часов.</p>', NULL, NULL, '<p>Лучшее время для&nbsp;маршрута&nbsp;- лето. В это время выпадает меньше всего осадков, а температура самая высокая (от 12 до 0 градусов), но на маршрут&nbsp;можно выходить&nbsp;и зимой. Примечание: зимой в кулуаре&nbsp;намного больше снега и часты лавины. Диапазон температур от -15 до -25 градусов.</p>', '<p>На маршруте&nbsp;вам понадобятся:</p>\n\n<ol>\n	<li>Веревка 50 м</li>\n	<li>Закладка 4-5&nbsp;(средних размеров)</li>\n	<li>Камелоты 3-4 (средних размеров)</li>\n	<li>Оттяжки 4-5</li>\n	<li>Карабин 3-4с и самостраховка</li>\n	<li>Спусковое - страховачная устроиство</li>\n	<li>Обвязка и шлем</li>\n	<li>Кошки и ледоруб (в зависимости от количества снега в кулуаре)</li>\n</ol>\n\n<p>Зимой вам понадобится дополнительное снарежение</p>\n\n<ol>\n	<li>Снегастапы</li>\n	<li>Ледобуры 2-3 части</li>\n</ol>', '<p>Темо - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-08-23 15:24:25', '2022-09-21 15:54:50'),
(130, NULL, 'Леонидзе Классик (4А)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:35:54', '2022-08-23 15:39:23'),
(131, NULL, '\"Didi digomi\"', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:40:16', '2022-08-23 15:43:52'),
(132, NULL, 'Kameruni klasic (3A)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:45:32', '2022-08-23 15:47:25'),
(133, NULL, 'Asatiani clasic (4a)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 16:01:40', '2022-08-23 16:07:39'),
(134, NULL, 'Myinvartsveri clasic (2A)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 17:03:43', '2022-08-23 17:05:22'),
(136, NULL, 'Climbing in Gveleti', '<p>.</p>', '<p><strong>Альпинистский район Гвелети</strong><br />\nРасстояние от Казбека: 8 км &nbsp;Место находится в стадии строительства, в настоящее время проложены 2 маршрута для спортивных восхождений.</p>', NULL, NULL, NULL, NULL, '<p>Temo: Mail - samsonadze.temo9@gmail.com, Tel - (+995) 598 45 70 48</p>\n\n<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 09:30:21', '2022-09-05 11:46:26'),
(137, NULL, 'Скалолазание возле Ананури', '<p>.</p>', '<p>Скалолазание возле Ананури</p>', '<h2><strong>в регионе набивка трасс запришина!!!</strong></h2>\n\n<p>в случае если вы хотите сделать свой маршрут\\трассу свяжитесь&nbsp;с местными спортсменами. в противном случае ваша трасса может быть демантирывана.</p>\n\n<p><strong>драйтулинг в региони также запрещено!!!</strong></p>\n\n<p><strong>используете краску для вашей безопасности!!!</strong></p>', NULL, NULL, '<ol>\n	<li>\n	<p>10 оттяжка</p>\n	</li>\n</ol>\n\n<ol>\n	<li>\n	<p>веревка 40 м</p>\n	</li>\n	<li>\n	<p>обвязка</p>\n	</li>\n	<li>\n	<p>Страховочное устройство</p>\n	</li>\n	<li>\n	<p>Шлем (используйте шлем для вашей безопасности!)</p>\n	</li>\n	<li>\n	<p>Обувь и сумка для мела</p>\n	</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2022-09-05 09:41:36', '2022-09-05 11:58:46'),
(138, NULL, 'Advanture 2022 - ჭიათურის ტურისტული ფესტივალი', '.', '<p><img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" />&nbsp;16-17-18 სექტემბერი&nbsp;<img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" /></p>\r\n\r\n<p><img alt=\"🫵\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc8/1/16/1faf5.png\" style=\"height:16px; width:16px\" />&nbsp;მოდი ჭიათურის ტურისტულ ფესტივალზე და მოიწყვე დაუვიწყარი თავგადასავალი</p>\r\n\r\n<p><img alt=\"🚀\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc6/1/16/1f680.png\" style=\"height:16px; width:16px\" />&nbsp;აღმოაჩინე სხვა ჭიათურა:</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ქემფინგი/ Camping;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (მოყვარული) / Rock Climbing (amateur);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (პროფესიონალი) / Rock Climbing (professional);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჰაილაინი / Highline;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ნაოსნობა ტბაზე / Lake Boating;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;სპელეოტურიზმი ძუძუანას და კოტიას მღვიმეში / Speleotourism in Dzudzuana and Kotika caves;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />ველოშეჯიბრი მანდაეთში/Cycling competition in Mandaeti.</p>\r\n\r\n<p>ფესტივალის განრიგი :</p>\r\n\r\n<p>16 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურა</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - პრეზენტაცია და პანელ - დისკუსია, ჭიათურის კულტურის სახლში.</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - ტური &ldquo;ჯორჯიან მანგანეზის&rdquo; ტურისტულ პროექტში (საბაგირო, კორტი და სხვა).</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Loud Speakers</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კაცხი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (მოყვარულები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ჰაილაინის შოუ</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - 17:00 ვია ფერატა ინფო ტური (​​მონაწილეები დაიყოფიან ოთხ ჯგუფად)</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;17 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ძუძუანას და კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;09:00 - 10:00 რეგისტრაცია ლაშქრობაში (მონაწილეები დაიყოფიან სამ ჯგუფად)</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - სპექტაკლი &ldquo;დოვინ დოვენ დოვლი&rdquo;</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;18 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;მანდაეთის ტბა</p>\r\n\r\n<p><img alt=\"🕙\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t31/1/16/1f559.png\" style=\"height:16px; width:16px\" />&nbsp;10:00 ველო შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ნავებით შეჯიბრი ტბაზე</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ცენტრი</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Tamada</p>\r\n\r\n<p><img alt=\"🏕\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tdc/1/16/1f3d5.png\" style=\"height:16px; width:16px\" />16 სექტემბერს 10:00 საათზე გაიხსნება ქემფინგ ზონა ჩამოსული სტუმრებისთვის, რომელიც იფუნქციონირებს 18 სექტემბრის ჩათვლით.</p>\r\n\r\n<p><img alt=\"🎶\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/1/16/1f3b6.png\" style=\"height:16px; width:16px\" />&nbsp;TAMADA და Loudspeakers</p>\r\n\r\n<p><img alt=\"🥪\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tfc/1/16/1f96a.png\" style=\"height:16px; width:16px\" /><img alt=\"🥤\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4f/1/16/1f964.png\" style=\"height:16px; width:16px\" />&nbsp;სთრით ფუდი და გამაგრილებელი სასმელები</p>\r\n\r\n<p><img alt=\"💥\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t40/1/16/1f4a5.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურაში ამაღელვებელი, მომნუსხველი, გემრიელი და მისტიკით სავსე 3 დღე გელოდება.</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ტურისტული ფესტივალის გენერალური სპონსორია &quot;ჯორჯიან მანგანეზი&quot;</p>\r\n\r\n<p><img alt=\"🤝\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t64/1/16/1f91d.png\" style=\"height:16px; width:16px\" />პარტნიორები: ჭიათურის მუნიციპალიტეტის მერია, სვერის სათავგადასავლო ბანაკი და DMO იმერეთი.</p>\r\n\r\n<p><img alt=\"🔗\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\" style=\"height:16px; width:16px\" />&nbsp;მხარდამჭერები: ტურიზმის ეროვნული ადმინისტრაცია.&nbsp;</p>\r\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, NULL, 'Новый сектор скалолазания, недалеко от Ананури', '<p>.</p>', '<p>Гуга Дабрундашвили и Сопо Челидзе проложили 3 новых маршрута возле Ананури. сейчас тут&nbsp;3 маршрута, но скала большая и можно проложить новые маршруты.</p>\n\n<p>Подробнее о расположении секторов и маршрутах смотрите на этой -&nbsp;<a href=\"https://climbing.ge/outdoor/Climbing_near_Ananuri\">странице</a>.</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:06:23', '2022-09-05 12:15:55'),
(141, NULL, 'Новые, скалолазные маршруты, в Гвелети', '<p>.</p>', '<p>Гуга Дабрундашвили проложил новые маршруты спортивного скалолазания в Гвелети.</p>\n\n<p>Подробнее о маршрутах смотрите на этой - <a href=\"https://climbing.ge/outdoor/Climbing_in_Gveleti\">странице</a>.</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:16:02', '2022-09-05 12:23:36'),
(142, NULL, 'Новый, альпинистский сектор и 5-й спортивный альпинистский маршрут, недалеко от Боржоми', '<p>.</p>', '<p>Темп Самсонадзе создал новый сектор и проложил 5 новых спортивных альпинистских маршрутов возле Боржоми в селе Мзетамзе.</p>\n\n<p>Более подробную информацию об этом районе смотрите на этой - <a href=\"https://climbing.ge/outdoor/Climbing_near_Borjomi\">странице</a>.</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:24:07', '2022-09-05 12:36:05'),
(143, NULL, 'Новый, спортивный сектор скалолазания на берегу Черного моря.', '<p>.</p>', '<p>Новый, спортивный сектор скалолазания на берегу Черного моря.&nbsp;</p>\n\n<p>Темо Самсонадзе проложил 3 новых маршрута&nbsp; в новом секторе на&nbsp;побережья в Цихисдзири.</p>\n\n<p>Смотрите больше информации на этой - <a href=\"https://climbing.ge/outdoor/Climbing_At_The_Black_Sea_Coast\">странице</a>.</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:36:13', '2022-09-05 12:49:52'),
(144, NULL, 'Грузинские альпинисты награждены Золотым ледорубом', '<p>Грузинские альпинисты награждены Золотым ледорубом за восхождение на непокоренный пик Сараграр в Пакистане</p>', '<p>Saragrar Northwest, первопрохождение по северо-западной стене, 2300 м, ED2 или Россия/Кавказ 6B, 6b M5+ A3+ 80-90&deg;, с 3 по 10 сентября 2021 г. Маршрут был спущен с еще одним бивуаком.</p>\n\n<p>В массиве Сараграр, расположенном к югу от афганской границы в сегодняшнем малопосещаемом пакистанском Гиндукуше, есть несколько вершин, но лишь немногие альпинисты когда-либо достигали их. Северо-Западный Сараграр (7300 м) остался непройденным, несмотря на длительные и доблестные попытки трех сильных испанских экспедиций в 1970-х и начале 80-х годов подняться на юго-западный контрфорс. Последний из них достиг вершины около 7150 м на северо-западном гребне, в точке, которую они назвали Сараграр Северо-Запад II. Грузины Арчил Бадриашвили, Бакар Гелашвили и Гиорги Тепнадзе выбрали ранее не опробованную северо-западную стену, в верхней части отвесную скальную стену переходящую в северо-западный гребень. Они также решили сделать попытку в сентябре, позже, чем обычно, чтобы уменьшить дневную жару и, как следствие, камнепад.</p>\n\n<p>Из базового лагеря на высоте около 4200 м в Рош-Голе и с небольшой предварительной акклиматизацией все трое &laquo;разогрелись&raquo;, совершив первопрохождение по южной стене и восточному гребню (1800 м, TD 60&deg;-лед 75&deg;- снег) Лангута-э-Барфи (6833 м), с одним бивуаком на высоте 6400 м. Это было четвертое восхождение на пик, но первое из Пакистана.</p>\n\n<p>Примерно через девять дней, 3 сентября, грузины начали восхождение по северо-западной стене Сараграра Северо-Запад на высоте около 5000 м, продолжая подниматься по огромному снежно-ледяному кулуару, характерному для нижней половины, чтобы сделать первый бивуак на высоте 6200 м. Наверху крутая гранитная стена составляла основу маршрута. Преодолев сложный смешанный, свободный и вспомогательный лазанья, они разбили лагерь на высоте 6400 м, 6600 м, 6750 м, 6850 м и около 7000 м на вершине стены. Пятый и шестой дни были проведены на сложном, вертикальном, 250-метровом хедволле, на один шаг ушло семь часов.</p>\n\n<p>Над вершиной стены лежал длинный сложный карнизный гребень с 300-метровым подъемом по вертикали. Свой последний бивуак трое грузин разбили у выхода на испанский маршрут и на восьмой день с трудом поднялись на вершину. В тот же день они смогли изменить маршрут к бивуаку над головной стеной, а на следующее утро начали спускаться по северо-западной стене, продолжая всю ночь, пока не достигли дна.</p>\n\n<p>Info from -&nbsp;<a href=\"https://pioletsdor.net/index.php/en/home/2022-honoured-ascents/141-saraghrar-northwest-7-300m?mibextid=Zxz2cZ\" target=\"_blank\">pioletsdor.net</a></p>', NULL, NULL, NULL, NULL, '<blockquote>\n<p>&nbsp;</p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Посмотреть эту публикацию в Instagram</a></p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Публикация от UFGM.ORG (@ufgm_org)</a></p>\n</blockquote>', NULL, NULL, '2022-11-20 06:22:07', '2022-11-21 17:52:23'),
(201, 'us', 'Climbing In Sveri(Chiatura)', '<p>Sveri Climbing area is located in a small quaint village in region of Imereti on the western part of Georgia.<br />\nRock climbing is a relatively activity in this area.</p>', '<p>Sveri Climbing area is located in a small quaint village in region of Imereti on the western part of Georgia.<br />\nRock climbing is a relatively activity in this area. Most routes are quite recently established and all routes freshly bolted in 2018.</p>\n\n<h3><strong>CAMPING</strong></h3>\n\n<p><br />\nStaying is possible in the tents nearby climbing area.<br />\nPutting tents straight under the rocks is not recommended because of risk<br />\nof stone fall and also its not big area under the lines to put tent</p>\n\n<h3><br />\n<strong>FOOD &amp; SUPPLIES</strong></h3>\n\n<p><br />\nFood shopping can be done in the town of Chiatura.<br />\nThere is a large local farm market and varieties of stores and plenty of bakeries offering fresh bread baked daily.<br />\nUnfortunately , there is no available food market in Sveri so you must<br />\nbring all your supplies with you prior to going to the climbing area.<br />\nFor water source, there are few springs nearby the climbing area or you can<br />\nalso ask the friendly locals for water.<br />\nDuring Springtime, wild fruits can be found at random places or one can ask friendly locals if you would like to pick some fruits.</p>\n\n<h3><strong>REST DAY ACTIVITIES</strong></h3>\n\n<p><br />\nThere a few old castles and monasteries and many nice area to visit in Sveri.<br />\nAlso the town of Chiatura is another great place to explore with its long<br />\nhistory of mining which riddled the area with many caves.<br />\nThere are also waterfalls where you can go swimming in the hot summer days.<br />\nMost locals are very friendly as it is all over Georgia.</p>', '<p>The Climbing area is relatively easy access.<br />\nThe cliffs in Sveri are facing north. The lack of direct sunlight throughout the day results in north-facing slopes being cooler.<br />\nMajority of the routes are in the shade during most of the day which makes Sveri and ideal climbing are during hot summer days.<br />\nMost routes are 10-25 meters, with grades ranging 5A to 7C.<br />\nThe area has still lots of potential and open for more establishment.<br />\nShould you decide to open and bolt new lines or in need a climbing guide please contact our local climbing pioneer Guga Dabrunda.</p>\n\n<p>High of lines from 10m to 25m. Grades from 5a to 7c France grade.</p>\n\n<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p>&nbsp;</p>\n\n<p><img alt=\"Sveri climbing sectors\" src=\"http://climbing.ge/public/assets/img/uploads/sveri-sectors-1587657036.jpg\" style=\"width:100%\" /></p>', '<p>The Village of Sveri is located near the closest major town Chiatura 170 km from Tbilisi.<br />\nYou can get to Sveri by taking a Marshrutka Bus in Didube Station in Tbilisi to the town of Chiatura. From Chiatura you can then take a minibus or taxi to the town of Sveri which is further 11 km.<br />\nFrom the bus stop in the Sveri village it&rsquo;s around 400 meter hike to the climbing area.<br />\nArea is not marked all the way but it&rsquo;s quite obvious to find the climbing area by keeping an eye out for the cliffs.<br />\nAt the end of village there is a sign &ldquo;Climbing Zone&rdquo; and from there it&rsquo;s about another 50 meters to the cliffs.<br />\nYou can also ask the locals how to get to the rocks named &ldquo;kldekari&rdquo; .</p>', '<p>With the exception of winter, climbing in Sveri is ideal from Spring all the way to Autumn. North facing cliffs assures a cool temperature even on hot summer days.<br />\nAverage temperatures in celsius in Spring is High 24&deg; / Low 11&deg;<br />\nSummertime High 30&deg; / Low 18&deg; and Autumn High 20&deg; / 8&deg;</p>', '<ol>\n	<li>10&nbsp;quickdraw</li>\n	<li>50m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet (Use&nbsp;helmet for your safety !)</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 07:04:04', '2021-03-20 03:39:04'),
(206, 'us', 'Climbing In Katskhi (Chiatura)', '<p>Katskhi&nbsp; is one of the most popular and best&nbsp; places to climb in Georgia. Quality of the rock is perfect for different tip of climbing.</p>', '<p>Katskhi&nbsp; is one of the most popular and best&nbsp; places to climb in Georgia.</p>\n\n<p>Quality of the rock is perfect for different tip of climbing.</p>', '<p>Routes are categorized in French grade and starts from 5a to 8b.</p>\n\n<p>use helmet for your safety !!!!!</p>\n\n<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p>Trad climbing is allowed in area. Dry tolling is not allowed&nbsp; in climbing area.</p>', '<p>Katskhi&nbsp; is located about 180 km from the capital Tbilisi. By car, you will need approximately 2&nbsp; hours.</p>\n\n<p>from&nbsp;Kutaisi&nbsp;airport &nbsp;1 hour</p>', '<p>katskhi is located 700m above sea level. Best time to climb is from September to July. August is hot season for climbing but in shade or cloudy weather its also pleasure to climb.There are sunny and also constantly shady places to climb.</p>\n\n<p>some lines are covered on the top with big trees and some are overhang so you can climb them even in rainy weather.</p>', '<ol>\n	<li>12&nbsp;quickdraw</li>\n	<li>60m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet (Use&nbsp;helmet for your safety !)</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:31:38', '2021-03-20 04:15:25'),
(207, 'us', 'Urban Climbing In Tbilisi', '<p>Welcome to the monument of Lotkin. It is a 40 meter concrete structure.</p>', '<p>Welcome to the monument of Lotkin.</p>\n\n<p>It is a 40 meter concrete structure. The building is located on the outskirts of the city and there will be no difficulties getting to the court. The project has just stared but after completion it will be possible to climb 2-rope multi-pitch. You can also climb the thread.</p>\n\n<p>&nbsp;</p>', '<p>For today there are 6 sport climbing &amp; 1 dry-tooling lines.</p>\n\n<p>Highest&nbsp; of 2 line are 40 m, 1 line 30 m and 3 lines 20 m.&nbsp; grade&nbsp; 5c / 7a</p>\n\n<p>In the middle of the 40 m lines there are top chains.</p>\n\n<p>&nbsp;</p>', '<p>From the <a href=\"https://goo.gl/maps/Rjou6mefmr52\">metro &#39;Station square&#39;</a>&nbsp; top exit rides&nbsp;buses&nbsp;№319</p>', '<p>You can climb here all year depending on the weather.</p>', '<ol>\n	<li>20 quickdraw</li>\n	<li>(Min) 40m - (Max) 80m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet (Use&nbsp;helmet for your safety !)</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p><strong>Guga</strong> - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>\n\n<p>&nbsp;</p>', NULL, NULL, '2021-03-02 13:36:24', '2022-10-06 16:04:52'),
(208, 'us', 'Climbing In Chiatura', '<p>chiatura&nbsp; is one of the most perspective places for climbing in Georgia.</p>', '<p>chiatura&nbsp; is one of the most perspective places for climbing in Georgia. On this rocks&nbsp; in 70s and 80s there were championships of soviet union.</p>', '<p>This area is in progress. Routes are of different classifications from 5a to7a. There is only one line 8a grade.and also 3 multi-pitch.</p>\n\n<p>Use halmet for your safety!</p>\n\n<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p>&nbsp;</p>', '<p>Chiatura is located 180 km from the capital Tbilisi. By car, you will need approximately 2 hours.</p>\n\n<p>Its possible to stay in the town in hostel or in the&nbsp; climbers base located 9 km from the center in village katskhi.</p>', '<p>Depends on the weather.</p>', '<ol>\n	<li>20 quickdraw</li>\n	<li>60m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet (Use&nbsp;helmet for your safety !)</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:38:50', '2021-03-21 06:56:56'),
(209, 'us', 'Climbing In Mestia (Chalaadi)', '<p>.</p>', '<p>Svaneti&nbsp;is one of the most beautiful places in Georgia to see.</p>\n\n<p>There are may kind of active sports and historical and nice places to visit.</p>\n\n<p>Mestia is the center of&nbsp; Svaneti&nbsp; which&nbsp; is located 1500m from sea level and 460km 8 hours drive&nbsp;from Tbilisi.</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans. Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>Near the rock climbing area there is cafe and drinking water.</p>\n\n<p>&nbsp;</p>\n\n<p><strong>Where To Stay</strong></p>\n\n<p><img alt=\"\" src=\"https://climbinggeorgia.files.wordpress.com/2020/05/13754529_1350512078296694_8033447529066489197_n.jpg?w=540\" style=\"width:45%\" /></p>\n\n<p><a href=\"https://www.facebook.com/Guesthouse-Edelweiss-896751453672761/?fref=ts\">facebook</a></p>\n\n<p>Theres lots of guesthouses and hotels in Mestia.</p>\n\n<p>In the center there&rsquo;s one of the best guesthouse is Edelweiss.<br />\naddress:&nbsp;<a href=\"https://www.bing.com/maps/default.aspx?v=2&amp;pc=FACEBK&amp;mid=8100&amp;rtp=%7Epos.43.044850163333_42.72793975_Guesthouse+%22Edelweiss%22&amp;cp=43.044850163333%7E42.72793975&amp;lvl=16&amp;sty=r&amp;rtop=0%7E0%7E0%7E&amp;mode=D&amp;FORM=FBKPL6&amp;mkt=en-US\" target=\"_blank\">Queen Tamar Street 20</a><br />\ncontact:&nbsp; +995&nbsp;99 22 42 64&nbsp;</p>', '<p>Best time for climbing is end of summer, beginning of Autumn. Climbing area is easy to hitchhike, it&rsquo;s located 10km away from the city center.</p>', '<p>&nbsp;Wall is in the shade until 12-1 hour.</p>\n\n<ol>\n	<li>70 m rope,</li>\n	<li>15 quick draw,</li>\n	<li>Helmet.</li>\n	<li>Harnes</li>\n	<li>Belay divice</li>\n	<li>Climbing Shous &amp;&nbsp;Chock bag</li>\n</ol>\n\n<p><br />\n&nbsp;</p>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:41:37', '2022-03-09 16:37:47'),
(210, 'us', 'Climbing In Dzevri', '<p>.</p>', '<p>Climbing lines are really close to the road and most of them has comfort start platform.</p>\n\n<p>It is possible to camp in the climbing area.</p>\n\n<p>There is restaurant and market nearby rocks.</p>\n\n<p>Local people are really friendly to climber community.</p>\n\n<p>Rock face has different exposition and even when its hot on the sun you can find shady place to climb.</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans. Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>Distance from Tbilisi 200 km 3 hour to drive.</p>\n\n<p>Distance from Kutaisi &ldquo;kopitnari&rdquo; airport 60 km 1 hour to drive. (show map)</p>', '<p>Best season for climbing is beginning of summer and autumn but in case of good weather its possible to climb during all year.</p>', '<ol>\n	<li>60 m rope,</li>\n	<li>14&nbsp;quick draw,</li>\n	<li>Helmet.</li>\n	<li>Harnes</li>\n	<li>Belay divice</li>\n	<li>Climbing Shous &amp;&nbsp;Chock bag</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:44:09', '2022-04-09 16:42:40'),
(211, 'us', 'Climbing In Kutaisi(Choma)', '<p>There are not many climbing trails in the city, but it is enough for a pleasant pastime.&nbsp;</p>', '<p>Kutaisi is the second largest city in Georgia. Population - 147 635 people.</p>\n\n<p>Kutaisi is located at a distance of 235 km. from Tbilisi. Height above sea level 125-300 meters. There are not many climbing trails in the city, but it is enough for a pleasant pastime. The difficulty of the slopes ranges from 5A to 7A, which is ideal for beginner climbers.</p>', NULL, '<p>You can easily get to Kutaisi from any city in Georgia. Municipal transport travels here from all cities of Georgia. If you are traveling with your own car, use the GPS navigator. Also in 5 kilometers from the city there is an airport. Flights are carried out both within the country and abroad.</p>\n\n<p>Use the map to hike the rocky trails.</p>\n\n<p>You can spend the night in one of the hotels in Kutaisi or in a tent right next to the rocks.</p>', '<p>The best time for climbing here is spring and autumn. It is very hot in summer and cold in winter. But on warm winter days it is possible to exercise.</p>', '<ol>\n	<li>12 quickdraw</li>\n	<li>60m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet (Use helmet for your safety !)</li>\n	<li>Shoes &amp; Chalk Bag</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 13:46:18', '2021-11-16 16:10:56'),
(212, 'us', 'Climbing In Arsha', '<p>Kazbegi (Stepantsminda)&nbsp;located in the North and 1750 above sea level, &nbsp;is one of the most popular and touristic places in Georgia.</p>', '<p>Kazbegi (Stepantsminda)&nbsp;located in the North and 1750 above sea level, &nbsp;is one of the most popular and touristic places in Georgia.</p>\n\n<p>Distance from Kazbegi: 3 km. The region is newly accessed and there are 30 sport lines. Trad and multi pitch climbing possible. Height of the wall: 120 m, width: 250 m.</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>From Kutaisi kopitnari aeroport its 23km (30 minutes) to centre of Kutaisi. Climbing area is on the street David and konstantine&nbsp;&nbsp; next to Wissol p.s. There is Bagrati church from climbing area 15 minutes to walk on the top of rock.</p>', '<p>Best season for climbing is in spring and autumn. High of the rock is 30 m.</p>', '<ol>\n	<li>\n	<p>60 m rope,</p>\n	</li>\n	<li>\n	<p>20 quick draw,</p>\n	</li>\n	<li>\n	<p>Helmet.</p>\n	</li>\n	<li>\n	<p>Harnes</p>\n	</li>\n	<li>\n	<p>Belay divice</p>\n	</li>\n	<li>\n	<p>Climbing Shous &amp;&nbsp;Chock bag</p>\n	</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:51:02', '2022-09-05 11:49:06'),
(213, 'us', 'Climbing At The Black Sea Coast', '<p>If you want to go rock climbing on the seaside, the Black Sea coast offers some opportunities to do so.</p>', '<p>If you want to go rock climbing on the seaside, the Black Sea coast offers some opportunities to do so.</p>', '<p>Some of the routes have new bolts, whereas most have been bolted quite a time ago. Therefore it is better to climb toprope. Apart from that, the quality of the rock is decent. The difficulties of the routes vary from 5A to 7a.</p>', '<p>The distance from the capital Tbilisi to Sarpi (right next to the Turkish border) is 380&nbsp;km. Alternatively, you can fly to Batumi Airport. The&nbsp;climbing rocks are situated within 10 km from there and are easily reachable by minibus.</p>\n\n<h3><strong>Where to stay</strong></h3>\n\n<p>You can stay in private houses or in hotels in Sarpi, Gonio or Kvariati (which are all nearby).The prices start from GEL 25-30&nbsp;(about 10-15&nbsp;Euro) and go up, depending on the season.</p>', '<p>You have the possibility to climbing in both sun or shadow although you should consider the heat during summer season. The best time<br />\nto go climbing is May, June, September and October.</p>', '<ol>\n	<li>10&nbsp;quickdraw</li>\n	<li>40m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p><strong>Guga </strong>- Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 13:53:23', '2021-04-11 07:35:06'),
(214, 'us', 'Climbing In Birtvisi', '<p>Birtviy canyon is located 40 km. from Tbilisi. On the climbing section of the track there are mainly bouldering routes, but there are also sports tracks.</p>', '<p>The Birtvi canyon is located 40 kilometers from Tbilisi. height above sea level 1077 meters. Here you can go rock climbing, trekking, canyoning. On the climbing section of the track there are mainly bouldering routes, but there are also sports tracks.</p>', '<p>The difficulty of the routes ranges from 5A to 7C, bouldering V1 - V7</p>\n\n<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>The road to the rocky sector and the canyon starts from the village of Tbisi, here you have to leave your car and walk 45 - 60 minutes through the forest zone (see the map) You must have a 4x4 car. Overnight stay is possible here only in tents. Place for tents in the middle of the rocky area and easily and quickly (5 minutes) you can reach the route. Water can be collected above the campsite along the road the river flows.</p>', '<p>The best time to visit the Birtvi canyon is spring and autumn. During the summer, it is very hot in the canyon. And it&#39;s cold enough in winter. but there are also warm winter days. when is training possible</p>', '<p>For sport climbing you will need:</p>\n\n<ol>\n	<li>Rope 50 m</li>\n	<li>10 quickdraw</li>\n	<li>belay device and harness</li>\n	<li>Helmet</li>\n</ol>\n\n<p>For bouldering you need a crash bad</p>', '<p>Guga: Tel. (+995)&nbsp;557 300 700 Mail.&nbsp;gugadabrunda@gmail.com</p>\n\n<p>Temo: Mail. samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-03-02 13:55:39', '2021-10-07 15:49:06'),
(215, 'us', 'Climbing In \"Tbilisi Botanical Garden\"', '<p>Georgian climbers of all decades have been climbing at the Botanical Garden &ndash; one of the best places to go rock climbing in Tbilisi.</p>', '<p>Georgian climbers of all decades have been climbing at the Botanical Garden &ndash; one of the best places to go rock climbing in Tbilisi.</p>', '<p>Apart from the top rope routes, route no. 9, 10, 13, 14, 15 and 22 are bolted. No. 1 and 2 are projects yet to be bolted. The bouldering routes are not marked on the rocks, but are easily recognizable and can be found on the photos here.</p>\n\n<p>You also have the option to climb 80 m traverse through all the rock face at the Botanical Garden.</p>', '<p>From Freedom Square, you can catch the bus no.&nbsp;90 or alternatively take a taxi for 2-3 Lari. In either case, you should drop off at the Institute of Botany (if you don&rsquo;t know the exact spot, don&rsquo;t hesitate to ask the driver). From there, you just follow down the river until you reach the climbing rocks.</p>', '<p>You can climb in the Botanical Garden throughout the whole year, whereas it might get a bit too hot in summer. If you go in the summertime anyway, the best time is in the morning from 9-11. A.M. or in the evening from 7-10 P.M.</p>', '<p>If you want to climb sport style, you will need a</p>\n\n<ol>\n	<li>Rope 60 m</li>\n	<li>10 quickdraw</li>\n	<li>belay device and harness</li>\n	<li>Helmet</li>\n</ol>', '<p>Guga - <strong>Mail</strong>: gugadabrunda@gmail.com &nbsp; &nbsp;<strong>Tel</strong>: (+995) 557 300 700</p>\n\n<p>Temo - <strong>Mail</strong>: samsonadze.temo9@gmail.com&nbsp;</p>', NULL, NULL, '2021-03-02 14:29:06', '2021-10-08 15:06:14');
INSERT INTO `locale_articles` (`id`, `locale`, `title`, `short_description`, `text`, `route`, `how_get`, `best_time`, `what_need`, `info`, `price_text`, `address`, `created_at`, `updated_at`) VALUES
(216, 'us', 'Climbing in Navardzeti(chiatura)', '<p>Small village &ldquo;Navardzeti&rdquo; is located 4.5 km&nbsp;from Chiatura to direction&nbsp; big climbing region &ldquo;kacxi&rdquo;</p>', '<p>Small village &ldquo;Navardzeti&rdquo; is located 4.5 km&nbsp;from Chiatura to direction&nbsp; big climbing region &ldquo;kacxi&rdquo;.</p>\n\n<p>Climbing lines are new and region is in progress so for today we have only 7 lines but soon our club &rdquo; Georgian climbers club&rdquo;&nbsp; is planning to bolt all possible lines around in the area.</p>\n\n<p>High of the rock is 30/40 m&nbsp; but lines are max 30 m high.</p>\n\n<p>Assess from the main road is hiking around 200 m.</p>\n\n<p>On the main road near climbing area you can get spring water.</p>\n\n<p>You can use climbers camping&nbsp;in kacxi to stay during the trip</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p>Trad climbing is allowed in area.<strong>&nbsp;Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>From Chiatura there is a minibus in navardzet.&nbsp;Also you can use the minibus to Katskhi&nbsp;and get off near the village Navardzeti</p>', '<p>Big plus of area is that rock is in the shade after 15:00 so in summer you can climb in the shade.</p>', '<ol>\n	<li>12&nbsp;quickdraw</li>\n	<li>60m roupe&nbsp;</li>\n	<li>Harnes&nbsp;</li>\n	<li>Belay device</li>\n	<li>Helmet</li>\n	<li>Shoes &amp;&nbsp;Chalk Bag</li>\n</ol>', '<p><strong>Guga </strong>- Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p><strong>Temo </strong>- Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:31:44', '2021-04-11 07:10:10'),
(217, 'us', 'Climbing in Tsdo', '<p>Tsdo is a small village near Kazbegi it&#39;s a beautiful place. It&#39;s nice place for&nbsp;sport and multi pitches climbing.</p>', '<p>Mountain tourism is the main focus in Kazbegi (summit 5047 m), however, the establishment of a respective infrastructure for climbing is in progress.village Tsdo is located from kazbek around 7km.</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p>Trad climbing is allowed in area.<strong>&nbsp;Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', NULL, '<p>June-October but if good weather &nbsp;its possible to climb even in the winter.</p>', '<ol>\n	<li>&nbsp;60 m rope,</li>\n	<li>15 quick draw,</li>\n	<li>Helmet.</li>\n	<li>Harnes</li>\n	<li>Belay divice</li>\n	<li>Climbing Shous</li>\n	<li>Chock bag</li>\n</ol>', '<p>Guga - Mail: gugadabrunda@gmail.com &nbsp; &nbsp;Tel: (+995) 557 300 700</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:36:35', '2021-04-08 15:47:20'),
(218, 'us', 'Climbing to Caukhi bace camp', '<p>Besides alpine style you can climb sport route on Chaukhi bace camp.</p>', '<p>Caukhi Mountains are locatins in Khevsureti province. Nearest village is Juta. Rocky mountains are erected from green alpine medows. Mountaineering rotes are very close from Base camp. Here in very small massive climbers can find all kind of routes interesting as for deginners also for high professional. (for mountain ascent info check <a href=\"http://climbing.ge/mountaineering\">here</a>).&nbsp;</p>\n\n<p>Besides alpine style you can climb sport route on bace camp.</p>', '<p>Here are not long route (maximum 15m) bolted and top-rope routes. Mostly this route are for alpine style ascent training, but it&#39;s nice for 3-4 hours rest climbing. Area is good for biginer climbers.</p>', '<p>Trecking route which are going to the Caukhi Peak Massif starts from the one camp (CAMP 1). The route the Chaukhi Peak Massif start in the village Juta (2100 m). Afterpassing the village and reach the cemp Zeta. in the camp youcan take a shower, food and stay at night. From the camp follow the rivwr Caukhi, the track is marked goodly. After passing throw the grassed plateaus and slopes you&#39;ll exit on the high plateau (cemp-1 2250m), on which edge is three dig bolders (10m). On dhese bolders there are stakes and it is possidle to thain for the rock climbing. This plateau is great for camping.</p>', '<p>The best time to climb the chaukhi is mid July to mid September.</p>', '<p>For rock climbin to chaukhi bace camp you nead:</p>\n\n<ol>\n	<li>6 quickdraw</li>\n	<li>40m rope</li>\n	<li>Harnes</li>\n	<li>Belay Device&nbsp;</li>\n	<li>Helmet (Use Helmet For Your Safety)</li>\n	<li>Shoes &amp; Chalk Bag</li>\n</ol>', '<p>For more information about climbing area contact me.</p>\n\n<p>Temo - Mail: samsonadze.temo9@gmail.com&nbsp; &nbsp; Tel; (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:39:32', '2021-03-20 03:36:09'),
(219, 'us', 'Bouldering Club Tbilisi', '<p>Bouldering gym on Vera at the Mountaineering Federation.</p>', '<p>Vera climbing gym, is a small bouldering gym. Height 4.5 m. Here you will find a variety of wall reliefs. There are many different routes and friendly environments that will give you motivation for climbing and training.</p>\n\n<p>There is no rental inventory in the hall, so you will need to use some personal equipment.</p>', NULL, '<p>The hall is located in the building of the chess palace, on the first floor. Entrance from the rear (parking) side. Address: Tbilisi, Merab Kostava street 37-A</p>', NULL, NULL, '<p>Oto -&nbsp;(+995)&nbsp;514 51 11 77</p>\n\n<p>Temo - (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:49:57', '2022-06-14 01:47:17'),
(220, 'us', 'S.K.Lucky', '<p>Climbing gym&nbsp;S.K. LUCKU. - one of the best gym&nbsp;in Georgia. Here you can climb&nbsp;bouldering and sport climbing routs.</p>', '<p>Climbing gym&nbsp;S.K. LUCKU. - one of the best gym&nbsp;in Georgia. Here you can climb&nbsp;bouldering and sport climbing routs. The height of the walls for sport climbing is 12 meters, for bouldering 4 meters. Instructors will meet you in the hall and help you choose a route depending on the complexity and your experience, and provide insurance if you come alone and do not have a partner.</p>\n\n<p>The gym has all the necessary equipment and equipment to make you feel comfortable and enjoy the training process. Please note that equipment rental is paid</p>', NULL, '<p>The gym is located at the address: Tbilisi University street 6, sports complex Arena 2, second floor.</p>\n\n<p>You can get here both by public transport and by private car or taxi (see the map).</p>', NULL, NULL, '<p>Tel: (+995) 574 80 95 02</p>', NULL, NULL, '2021-03-02 14:52:39', '2021-10-07 13:24:49'),
(221, 'us', 'Vake Clibing Gym', '<p>The best place for speed and sport climbing in Tbilisi is the Vake Gym.</p>', '<p>The best place for speed and sport climbing in Tbilisi is the Vake Gym.</p>\n\n<p>The wall has a height of 15 m, you can climb with quickdraws and top-rope aswell. For warming up, there is also a smaller bouldering wall. You can also find the world record wall for speed climbing here.</p>\n\n<p>A certain amount of climbing harnesses and quickdraws are provided in the gym. Anything else (e.g. climbing shoes) should be brought with you.</p>\n\n<p><strong>Opening hours&nbsp;</strong></p>\n\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:100%\">\n	<tbody>\n		<tr>\n			<td>Manday</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Tuesday&nbsp;</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Wednesday&nbsp;</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Thursday&nbsp;</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Friday&nbsp;</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Saturday&nbsp;</td>\n			<td>17.00 - 21.00</td>\n		</tr>\n		<tr>\n			<td>Sunday&nbsp;</td>\n			<td>Closed&nbsp;</td>\n		</tr>\n	</tbody>\n</table>', NULL, '<p>Sports Department, Chavchavadze Ave. 49. Easily reachable by bus (drop off at the bus stop in<br />\nfront of Vake Swimming Pool).</p>', NULL, NULL, '<p>Temo:&nbsp;(+995) 598 45 70 48</p>', NULL, NULL, '2021-03-02 14:54:34', '2021-03-02 14:57:41'),
(222, 'us', 'Ice Climbing In Gveleti (Kazbegi)', '<p>Ice climbing in Gveleti is one of the best places for this activity.</p>', '<p>Ice climbing in Gveleti is one of the best places for this activity. trad and dry tolling climbing in area is allowed.</p>', NULL, '<p>Из Тбилиси вам нужно ехать до казбека &laquo;степанцминда&raquo; 160 км, а затем ехать в направлении владикавказ 9 км от казбека и повернуть налево, пока вы пересекаете мост в гвелети. Затем идите прямо к ледопаду. время прогулки 20/30 минут в зависимости от состояния снега.</p>', '<p>Best time for climbing is January,February &nbsp;and beginning of march if there is cold weather.</p>', '<ol>\n	<li>60M roupe (x2) or 120m (x1)</li>\n	<li>Ice axe</li>\n	<li>Crampons</li>\n	<li>Belay device</li>\n	<li>Crabiners (x4-5)</li>\n	<li>Harness</li>\n	<li>Helmet</li>\n</ol>\n\n<p>For lead climbing</p>\n\n<ol>\n	<li>7-8 Ice screws &amp;&nbsp;Quickdraw</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:23:10', '2021-03-19 16:37:45'),
(223, 'us', 'Ice Climbing In Ateni Valley', '<p>Ice climbing is a rather established sports discipline in Georgia. One of the best places to exercise is in Ateni Valley near the village of Biis.</p>', '<p>Ice climbing is a rather established sports discipline in Georgia. One of the best places to exercise is in Ateni Valley near the village of Biis.</p>', NULL, '<p>От Тбилиси до Бийса потребуется около 1,5 часов, расстояние 130 км. Следуя по главному автомобильному маршруту, вы поворачиваете налево и въезжаете в деревню. Рекомендуется передвигаться на автомобиле 4 &times; 4, так как в этом случае вы сможете проехать еще 300 метров в деревню, не оставляя машину припаркованной на главной дороге из-за плохих уличных условий.</p>\n\n<p>Припарковав машину, вам нужно пройти 20 минут вверх по реке, пока не дойдете до замерзшего водопада высотой 60 метров и шириной 5-15 метров.</p>\n\n<p>Ближайшие города: 41 &deg; 51&#39;4 &quot;N 43 &deg; 59&#39;6&quot; E ►</p>\n\n<p>Где остановиться<br />\nМесто у подножия ледопада вполне подходит для кемпинга, но учтите, что ночью очень холодно!</p>', '<p>Depends on year temperature, February are the best months to go ice climbing.</p>', '<ol>\n	<li>60M roupe (x2) or 120M (x1) (For top rope climbing)</li>\n	<li>Ice axe</li>\n	<li>Crampons</li>\n	<li>Harness</li>\n	<li>Helmet</li>\n	<li>Belay device</li>\n	<li>Crabin (x4-5)</li>\n</ol>\n\n<p>Fol lead</p>\n\n<ol>\n	<li>7-8 ice screws &amp;&nbsp;quickdraw</li>\n</ol>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 16:47:03', '2021-03-19 17:00:32'),
(224, 'us', 'Slackline In Georgia', '<p>In Georgia, slackline is not the developed&nbsp;discipline, but it&#39;s&nbsp;developing.</p>', '<p><strong>Slacklining</strong>&nbsp;- refers to the act of walking or&nbsp;<a href=\"https://en.wikipedia.org/wiki/Balance_(ability)\">balancing</a>&nbsp;along a suspended length of flat&nbsp;<a href=\"https://en.wikipedia.org/wiki/Webbing\">webbing</a>&nbsp;that is&nbsp;<a href=\"https://en.wikipedia.org/wiki/Tension_(physics)\">tensioned</a>&nbsp;between two&nbsp;<a href=\"https://en.wikipedia.org/wiki/Anchor_(climbing)\">anchors</a>. Slacklining is similar to slack rope walking and&nbsp;<a href=\"https://en.wikipedia.org/wiki/Tightrope_walking\">tightrope walking</a>. Slacklines differ from tightwires and tightropes in the type of material used and the amount of tension applied during use. Slacklines are tensioned significantly less than tightropes or tightwires in order to create a dynamic line which will stretch and bounce like a long and narrow&nbsp;<a href=\"https://en.wikipedia.org/wiki/Trampoline\">trampoline</a>. Tension can be adjusted to suit the user, and different webbing may be used in various circumstances. Slacklining is popular because of its simplicity and versatility; it can be used in various environments with few components.</p>\n\n<p>&nbsp;</p>\n\n<p><strong>Slacline in Georgia</strong> -&nbsp;In Georgia, slackline is not the developed&nbsp;discipline, but it&#39;s&nbsp;developing. Already in throughout Georgia are several highline regions(The most popular of them who is &#39;9 jvari&#39; in Racha).&nbsp;On the website <a href=\"https://slacklinegeorgia.wordpress.com/\">slacklinegeorgia.wordpress.com</a> you can find detailed information on all the regions of Georgia.&nbsp;</p>\n\n<p>&nbsp;</p>\n\n<h3>How to find us?</h3>\n\n<p><strong>I</strong>n <strong>Tbilisi</strong>, we gather most often in the &#39;<a href=\"https://goo.gl/maps/eKc8QoHE9i62\">Vake park</a>&#39;, &#39;<a href=\"https://goo.gl/maps/aZPtdvUCcf22\">Tbilis Botanical garden</a>&#39;(<a href=\"http://climbing.ge/outdoor/climbing_in_tbilisi\">in rock climbing area</a>)&nbsp;and the <a href=\"https://goo.gl/maps/y2o74pH4JtA2\">&#39;Lisi lake</a>&#39;. We inform each other about the meeting place on the facbook page &#39;<a href=\"https://www.facebook.com/groups/1406012539710551/\">Sleckline Georgia</a>&#39;.&nbsp;</p>\n\n<p><strong>I</strong>n <strong>Batumi</strong>, we gather most often in the &#39;<a href=\"https://goo.gl/maps/qPTeY2Szba32\">6 May Park</a>&#39; and near &#39;<a href=\"https://goo.gl/maps/1cRys9mKdMA2\">Tema Caffe Bar</a>&#39;. We inform each other about the meeting place on the facbook page &#39;<a href=\"https://www.facebook.com/groups/372828259545512/\">Sleckline Batumi</a>&#39;.&nbsp;</p>\n\n<p>In <strong>Kutaisi,&nbsp;</strong>we gather most often in the &#39;<a href=\"https://goo.gl/maps/oyKttSgyiRs\">Choma</a>&#39;(<a href=\"http://climbing.ge/outdoor/climbing_in_kutaisi\">in&nbsp;rock climbing area</a>). You can&nbsp;contact local guys&nbsp;<a href=\"https://www.facebook.com/abuhdab\">Temo Kutdiani on facebook</a>.</p>\n\n<h3>Info:</h3>\n\n<p>Facebook page:&nbsp;<a href=\"https://www.facebook.com/slacklinegeorgia\" target=\"_blank\">slackline georgia</a></p>\n\n<p>Tbilisi -&gt; Mail: Iraklisixarulidze@gmail.com; Facebook: <a href=\"https://www.facebook.com/dusty.boots.7\">dusty boots</a>.</p>\n\n<p>Batumi -&gt; Facebook:&nbsp;<a href=\"https://www.facebook.com/erekle.chikvaidze?sk=wall&amp;fref=gs&amp;dti=372828259545512&amp;hc_location=group_dialog\">Erekle Chikvaidze</a>.</p>\n\n<p>Kutaisi -&gt; Facebook:&nbsp;<a href=\"https://www.facebook.com/abuhdab\">Temo Kutdiani</a>.</p>', NULL, NULL, NULL, NULL, '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2021-03-19 17:01:44', '2021-03-19 17:09:21'),
(225, 'us', 'Javaxishvili clasic (3A)', '<p>AD, M1, 300M, 5A (3A - RUS)</p>', '<p>Climbing requires only three pitches and the difficulty of the route does not exceed 5A</p>\n\n<p>В0 - the route starts from the Leonidze-Javakhishvili couloir, from there we ascend to the saddle, where there is a large stone. To the left of the stone we see the assembled station.</p>\n\n<p>Р1 - from the station there are two options for ascent The first - from the station to the left, along the fireplace, keeping to the right side - we go to the shelf. Second, there is a crack over the station, climb along the right side of the crack and go out onto the ledge.</p>\n\n<p>P2- from the station we go to the right along an inclined crevice and come out onto a thin rock with a positive slope. You can find it on the wall, continue climbing and find a station, assembled on three elements.</p>\n\n<p>Р3- from the station we continue the ascent and go out onto the ledge where we find the station on a boulder. From the station, go to the left along the path that takes us to the top of Javakhishvili. The classic route of Javakhishvili, the easiest way to the top of this mountain.</p>\n\n<p>The route is quite simple, so it is suitable for beginner climbers. For those who are just starting to climb the mountains.</p>\n\n<p>It takes 2-2.5 hours from the first camp to the base of the couloir. From the second camp 0.45-1.5 hours.</p>\n\n<p>The average time from the couloir to the summit is only 4-5 hours.</p>', NULL, NULL, '<p>The best time for the route is summer. At this time, the least precipitation falls, and the temperature is the highest (from 12 to 0 degrees), but you can go on the route in winter. Note: There is much more snow on the couloir in winter and avalanches are frequent. Temperature range from -15 to -25 degrees.</p>', '<p>On the route you will need:</p>\n\n<ol>\n	<li>Rope 50 m</li>\n	<li>Stopers&nbsp;4-5&nbsp;(medium size)</li>\n	<li>Cams&nbsp;3-4 (medium-sized)</li>\n	<li>&nbsp;Quickdraw&nbsp;4-5</li>\n	<li>3-4s carbine and self-defense</li>\n	<li>Belay device</li>\n	<li>Harness and helmet</li>\n	<li>Crampons and iceax (depending on the amount of snow on the couloir)</li>\n</ol>\n\n<p>In winter, you will need additional equipment</p>\n\n<ol>\n	<li>Snowsues</li>\n	<li>Ice screws 2-3</li>\n</ol>', '<p>Темо - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-10-03 03:01:13', '2022-09-21 15:51:06'),
(226, 'us', 'Climbing near Borjomi', '<p>Borjomi is located in Eastern Georgia. Here you can do rock climbing as well as various activities.</p>', '<p>Borjomi is located in Eastern Georgia, from sea level&nbsp;900 m. Population ~ 10,000 people. Borjomi Municipality is very popular among adventure tourism lovers. Here you can do trekking, cycling tour, rafting, and more.</p>', '<p>In the winter of 2021, the reabilitation of the region is&nbsp;began. Initially only 1 sector and 8 routes were done but already by the summer of 2022 it is planned to do more routes.</p>\n\n<p>Rock - basalt</p>\n\n<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans. Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', '<p>We are going from Borjomi to Bakuriani, in 9 km we will meet the village of Tsaghveri. From the central square of Tsaghveri we turn left and go 700-1000 m to the area of ​​the village Timote. On the right we will meet the source (and the source mark). At the source we find the bridge over the river we cross the bridge and meet the rock. It is possible to park the car here. See map for details.</p>', '<p>Best time to climb in the region: mid-spring to late autumn. The rock is mostly in the shade during the day so it is mostly cold during the day. We recommend that you check the weather before arriving in the region.</p>', '<ol>\n	<li>60 m rope,</li>\n	<li>15 quickdraw,</li>\n	<li>Helmet.</li>\n	<li>Harnes</li>\n	<li>Belay divice</li>\n	<li>Climbing Shous &amp;&nbsp;Chock bag</li>\n</ol>', '<p>Temo - Tel - (+995) 598457048.&nbsp;Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-21 14:30:47', '2022-06-10 16:13:57'),
(227, 'us', 'New climbing sectors near Borjomi', '<p>Borjomi municipality is one of the most popular among tourists and outdoor enthusiasts. A new climbing sector was recently added here.</p>', '<p>Borjomi is located 150 km from Tbilisi. Borjomi municipality is one of the most popular among tourists and outdoor enthusiasts. Here you can visit the Borjomi-Kharagauli National Park, where you can go trekking, visit sulfur baths and go rafting.</p>\n\n<p>20 km from Borjomi is the village of Tsemi, where one of the oldest climbing sectors in Georgia is located. Competitions were held back in the 80s of the 20th century. However, for the last 20 years, the region has been completely forgotten and no sporting events have been held here. There was no infrastructure in the sector, so it was impossible to climb here.</p>\n\n<p>In November 2021, work began on the renewal of the sector. At the first stage, only 3 routes were laid, but in the near future the work will be continued and even more traffic routes will be laid here.</p>\n\n<p>Follow the link to see a detailed description of the region.</p>', NULL, '<p><a href=\"https://www.google.com/maps/d/u/0/edit?mid=1zLk1GegB8K4QLJKiheI4D1odWy6rPffn&amp;ll=41.798991224962606%2C43.490982900000006&amp;z=18\">See the map to find out how to get to the region.</a></p>', NULL, NULL, '<p><strong>Temo -</strong>&nbsp;&nbsp;<strong>Tel:</strong> (+995) 598 45 70 48. <strong>Mail:</strong> samsonadze.temo9@gmail.com</p>', NULL, NULL, '2021-11-23 13:24:11', '2021-11-23 14:28:28'),
(228, 'us', 'Climbing gym in Stepantsminda (Kazbegi)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-05-05 14:52:52', '2022-05-05 14:59:15'),
(229, 'us', '\"Iliauni\"', '<p>.</p>', '<p>The route starts on the left of Leonidze - Javakhishvili lane. We go up the steep slope to the bottom of the wall, we find a crack on the left side. This is where the route starts.</p>\n\n<p>P1 - we start climbing in a wide crack (for protection, use large-sized insert elements), we pass on a narrow taro and make protection on the chem.</p>\n\n<p>From the P2 station, we continue climbing to the right. We go through a 4-5 meter wall to the big taro, go to the right and make a station on the Khim, at the foot of the big fireplace.</p>\n\n<p>P3 - We start climbing into the fireplace, there are many cracks on the right and left walls of the fireplace to make protection. Above the fireplace is a ceiling formed by the fall of large stones. On the left side, we find a narrow passage and go to the shelf. There is a chemical station near the gas station.</p>\n\n<p>P3- P4- walking simultaneously to the left of the station, we follow the shelf, climb a 5m high simple wall and find the base of the big wall on the existing taro.</p>\n\n<p>P4- we follow the groove in the middle of the big wall (the biggest groove), we follow the groove, on the right side we find a shelf and 4m above the shelf we find 2 palos on which we set up a station.</p>\n\n<p>P5 - We continue to climb in the groove until we come to a green wall with broken, broken rock.</p>\n\n<p>There are many narrow grooves on the wall. We continue to climb in the middle of the wall and find a narrow shelf where we make a station (making a station higher than this is not recommended).</p>\n\n<p>P6 - We continue to climb straight until we get to a large shelf below a large fireplace. We make a station at the foot of the fireplace.</p>\n\n<p>P7- We continue to climb the large chimney until we pass another large taro. From here we start walking simultaneously.</p>\n\n<p>TOP - from the shelf we continue to climb at the same time, on easy terrain. We go to the foot of the peak. We pass the peak on a small shelf from the west side (go to the left) and find a fireplace. As soon as we leave the fireplace, we find ourselves at the top.</p>\n\n<p>The average time from the base of the route to the top is 4-6 hours.</p>', NULL, NULL, '<p>The best time for the route is summer. At this time, the least precipitation falls, and the temperature is the highest (from 12 to 0 degrees), but you can go on the route in winter. Note: There is much more snow on the couloir in winter and avalanches are frequent. Temperature range from -15 to -25 degrees.</p>', '<h2>What you need</h2>\n\n<p>On the route you will need:</p>\n\n<ol>\n	<li>Rope 50 m</li>\n	<li>Stopers&nbsp;4-5&nbsp;(medium size)</li>\n	<li>Cams&nbsp;3-4 (medium-sized)</li>\n	<li>&nbsp;Quickdraw&nbsp;4-5</li>\n	<li>3-4s carbine and self-defense</li>\n	<li>Belay device</li>\n	<li>Harness and helmet</li>\n	<li>Crampons and iceax (depending on the amount of snow on the couloir)</li>\n</ol>\n\n<p>In winter, you will need additional equipment</p>\n\n<ol>\n	<li>Snowsues</li>\n	<li>Ice screws 2-3</li>\n</ol>', '<p>.Темо - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-08-23 15:24:25', '2022-09-21 15:50:39'),
(230, NULL, 'Leonidze clasic (4a)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:35:54', '2022-08-23 15:39:23'),
(231, NULL, '\"Didi digomi\"', '<p>.</p>', '<p>First ascent - Bidzina Gujabidze and Alexandre Gujabidze</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:40:16', '2022-08-23 15:43:52'),
(232, NULL, 'Kameruni klasic (3A)', '<p>.</p>', '<p>.</p>', NULL, '<p>.</p>', NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 15:45:32', '2022-08-23 15:47:42'),
(233, NULL, 'Asatiani clasic (4a)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 16:01:40', '2022-08-23 16:07:42'),
(234, NULL, 'Myinvartsveri clasic (2A)', '<p>.</p>', '<p>.</p>', NULL, NULL, NULL, NULL, '<p>.</p>', NULL, NULL, '2022-08-23 17:03:42', '2022-08-23 17:05:22'),
(236, NULL, 'Climbing in Gveleti', '<p>.</p>', '<p>Distance from Kazbek : 8 km (to Dariali Valley).&nbsp;The spot is under development with currently 2 sport climbing routes.&nbsp;Trad climbing possible.</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans.</p>\n\n<p>Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', NULL, '<p>June-October but if good weather &nbsp;its possible to climb even in the winter.</p>', '<ol>\n	<li>60 m rope,</li>\n	<li>15 quickdraw,</li>\n	<li>Helmet.</li>\n	<li>Harnes</li>\n	<li>Belay divice</li>\n	<li>Climbing Shous &amp;&nbsp;Chock bag</li>\n</ol>', '<p>Temo: Mail - samsonadze.temo9@gmail.com, Tel - (+995) 598 45 70 48</p>\n\n<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 09:30:21', '2022-09-05 11:37:38'),
(237, NULL, 'Climbing near Ananuri', '<p>.</p>', '<p>Climbing near Ananuri</p>', '<h2><strong>Area is not free for bolting !!!</strong></h2>\n\n<p>In case if you plan to come for bolting in Georgia please let local community to know about your plans. Any line which does not fit in our standards will be removed.</p>\n\n<p><strong>Dry tolling is not allowed&nbsp; in climbing area</strong>.</p>\n\n<p><strong>camping is not allowed under climbing lines</strong>&nbsp; !!!!!!!</p>\n\n<p><strong>for your safety always use helmet&nbsp;</strong>&nbsp;!!!!!!!!!</p>', NULL, NULL, '<ol>\n	<li>\n	<p>40 m rope,</p>\n	</li>\n	<li>\n	<p>10&nbsp;quick draw,</p>\n	</li>\n	<li>\n	<p>Helmet.</p>\n	</li>\n	<li>\n	<p>Harnes</p>\n	</li>\n	<li>\n	<p>Belay divice</p>\n	</li>\n	<li>\n	<p>Climbing Shous &amp;&nbsp;Chock bag</p>\n	</li>\n</ol>\n\n<p><br />\n&nbsp;</p>', '<p><strong>Guga -</strong>&nbsp;Mail: gugadabrunda@gmail.com&nbsp; &nbsp; Tel: (+995) 557 300 700.</p>\n\n<p><strong>Temo</strong>&nbsp;- Mail; samsonadze.temo9@gmail.com&nbsp; &nbsp; &nbsp;Tel: (+995) 598 45 70 48</p>', NULL, NULL, '2022-09-05 09:41:36', '2022-09-05 11:58:46'),
(238, NULL, 'Advanture 2022', '.', '<p><img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" />&nbsp;16-17-18 სექტემბერი&nbsp;<img alt=\"📢\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t39/1/16/1f4e2.png\" style=\"height:16px; width:16px\" /></p>\r\n\r\n<p><img alt=\"🫵\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc8/1/16/1faf5.png\" style=\"height:16px; width:16px\" />&nbsp;მოდი ჭიათურის ტურისტულ ფესტივალზე და მოიწყვე დაუვიწყარი თავგადასავალი</p>\r\n\r\n<p><img alt=\"🚀\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tc6/1/16/1f680.png\" style=\"height:16px; width:16px\" />&nbsp;აღმოაჩინე სხვა ჭიათურა:</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ქემფინგი/ Camping;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (მოყვარული) / Rock Climbing (amateur);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;კლდეზე ცოცვა (პროფესიონალი) / Rock Climbing (professional);<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჰაილაინი / Highline;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ნაოსნობა ტბაზე / Lake Boating;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;სპელეოტურიზმი ძუძუანას და კოტიას მღვიმეში / Speleotourism in Dzudzuana and Kotika caves;<br />\r\n<img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />ველოშეჯიბრი მანდაეთში/Cycling competition in Mandaeti.</p>\r\n\r\n<p>ფესტივალის განრიგი :</p>\r\n\r\n<p>16 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურა</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - პრეზენტაცია და პანელ - დისკუსია, ჭიათურის კულტურის სახლში.</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - ტური &ldquo;ჯორჯიან მანგანეზის&rdquo; ტურისტულ პროექტში (საბაგირო, კორტი და სხვა).</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Loud Speakers</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კაცხი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (მოყვარულები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ჰაილაინის შოუ</p>\r\n\r\n<p><img alt=\"🕚\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t59/1/16/1f55a.png\" style=\"height:16px; width:16px\" />&nbsp;11:00 - 17:00 ვია ფერატა ინფო ტური (​​მონაწილეები დაიყოფიან ოთხ ჯგუფად)</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;17 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;სვერი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 13:00 რეგისტრაცია კლდეზე ცოცვის შეჯიბრში (პროფესიონალები)</p>\r\n\r\n<p><img alt=\"🕐\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta8/1/16/1f550.png\" style=\"height:16px; width:16px\" />&nbsp;13:00 - 18:00 შეჯიბრი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ძუძუანას და კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;09:00 - 10:00 რეგისტრაცია ლაშქრობაში (მონაწილეები დაიყოფიან სამ ჯგუფად)</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;კოტიას მღვიმე</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - სპექტაკლი &ldquo;დოვინ დოვენ დოვლი&rdquo;</p>\r\n\r\n<p><img alt=\"👉\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\" style=\"height:16px; width:16px\" />&nbsp;18 სექტემბერი</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;მანდაეთის ტბა</p>\r\n\r\n<p><img alt=\"🕙\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t31/1/16/1f559.png\" style=\"height:16px; width:16px\" />&nbsp;10:00 ველო შეჯიბრი</p>\r\n\r\n<p><img alt=\"🕛\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tda/1/16/1f55b.png\" style=\"height:16px; width:16px\" />&nbsp;12:00 - 18:00 ნავებით შეჯიბრი ტბაზე</p>\r\n\r\n<p><img alt=\"📍\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ცენტრი</p>\r\n\r\n<p><img alt=\"🕘\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb0/1/16/1f558.png\" style=\"height:16px; width:16px\" />&nbsp;21:00 - გალა კონცერტი Tamada</p>\r\n\r\n<p><img alt=\"🏕\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tdc/1/16/1f3d5.png\" style=\"height:16px; width:16px\" />16 სექტემბერს 10:00 საათზე გაიხსნება ქემფინგ ზონა ჩამოსული სტუმრებისთვის, რომელიც იფუნქციონირებს 18 სექტემბრის ჩათვლით.</p>\r\n\r\n<p><img alt=\"🎶\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/1/16/1f3b6.png\" style=\"height:16px; width:16px\" />&nbsp;TAMADA და Loudspeakers</p>\r\n\r\n<p><img alt=\"🥪\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tfc/1/16/1f96a.png\" style=\"height:16px; width:16px\" /><img alt=\"🥤\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4f/1/16/1f964.png\" style=\"height:16px; width:16px\" />&nbsp;სთრით ფუდი და გამაგრილებელი სასმელები</p>\r\n\r\n<p><img alt=\"💥\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t40/1/16/1f4a5.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურაში ამაღელვებელი, მომნუსხველი, გემრიელი და მისტიკით სავსე 3 დღე გელოდება.</p>\r\n\r\n<p><img alt=\"✔️\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/2714.png\" style=\"height:16px; width:16px\" />&nbsp;ჭიათურის ტურისტული ფესტივალის გენერალური სპონსორია &quot;ჯორჯიან მანგანეზი&quot;</p>\r\n\r\n<p><img alt=\"🤝\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t64/1/16/1f91d.png\" style=\"height:16px; width:16px\" />პარტნიორები: ჭიათურის მუნიციპალიტეტის მერია, სვერის სათავგადასავლო ბანაკი და DMO იმერეთი.</p>\r\n\r\n<p><img alt=\"🔗\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\" style=\"height:16px; width:16px\" />&nbsp;მხარდამჭერები: ტურიზმის ეროვნული ადმინისტრაცია.&nbsp;</p>\r\n', NULL, NULL, NULL, NULL, 'Guga - ', NULL, NULL, NULL, NULL),
(240, NULL, 'New climbing sector, near Ananuri', '<p>.</p>', '<p>Guga Dabrundashvili and Sopo Celidze boltid 3 new route near ananuri. Naw hear only 3 route but rock is big and poseble boltednew routes.</p>\n\n<p>For more information about sector location and routes see on this - <a href=\"https://climbing.ge/outdoor/Climbing_near_Ananuri\">page</a>.&nbsp;</p>\n\n<p>&nbsp;</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com,&nbsp;Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:06:23', '2022-09-05 12:15:55'),
(241, NULL, 'New climbing routes, in Gveleti', '<p>.</p>', '<p>Guga Dabrundashvili boltid new sport climbing routes in Gveleti.&nbsp;</p>\n\n<p>More information about rocks see on this - <a href=\"https://climbing.ge/outdoor/Climbing_in_Gveleti\">page</a>.</p>', NULL, NULL, NULL, NULL, '<p>Guga: Mail - gugadabrunda@gmail.com, Tel: (+995) 557 300 700.</p>', NULL, NULL, '2022-09-05 12:16:02', '2022-09-05 12:23:23'),
(242, NULL, 'New, climbing sector, and 5 sport climbing route, near Borjomi', '<p>.</p>', '<p>Temo&nbsp;Samsonadze create new sector and bolted 5 new sport climbing route near Borjomi in village Mzetamze</p>\n\n<p>More information about this area see on this - <a href=\"https://climbing.ge/outdoor/Climbing_near_Borjomi\">page</a>.</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:24:07', '2022-09-05 12:36:05'),
(243, NULL, 'New, sport climbing sector at the black sea cast.', '<p>.</p>', '<p>Temo Samsonadze boltid 3 new climbing line in Cixisdziri beach. Near Batumi.</p>\n\n<p>More information see on this - <a href=\"https://climbing.ge/outdoor/Climbing_At_The_Black_Sea_Coast\">page</a>.</p>\n\n<p>&nbsp;</p>', NULL, NULL, NULL, NULL, '<p>Temo - Tel - (+995) 598457048. Email - samsonadze.temo9@gmail.com</p>', NULL, NULL, '2022-09-05 12:36:13', '2022-09-05 12:49:52'),
(244, NULL, 'Georgian mountaineers awarded by Golden Ice Axe', '<p>Georgian mountaineers awarded by Golden Ice Axe for ascending unclimbed Saraghrar peak in Pakistan</p>', '<p><em>Saraghrar Northwest, first ascent via the northwest face, 2,300m, ED2 or Russian/Caucasian 6B, 6b M5+ A3+ 80-90&deg;, from September 3-10, 2021. The route was descended with one further bivouac.</em></p>\n\n<p>The Saraghrar Massif, just south of the Afghan border in today&rsquo;s little visited Pakistan Hindu Kush, has several summits, but few climbers have ever reached them. Saraghrar Northwest (7,300m) remained unclimbed, despite prolonged and valiant attempts on the southwest buttress by three strong Spanish expeditions in the 1970s and early &lsquo;80s. The last of these topped out at around 7,150m on the northwest ridge, at a point they named Saraghrar Northwest II. Georgians Archil Badriashvili, Baqar Gelashvili, and Giorgi Tepnadze chose the previously unattempted northwest face, in its upper part a steep rock wall merging into the northwest ridge. They also opted to make the attempt in September, later in the year than normal, to lessen daytime heat and consequent rockfall.</p>\n\n<p>From a base camp at around 4,200m in the Rosh Gol, and with little pre-acclimatization, the three &ldquo;warmed up&rdquo; by making the first ascent of the south face and east ridge (1,800m, TD 60&deg;-ice 75&deg;-snow) of Languta-e-Barfi (6,833m), with one bivouac at 6,400m. This was the fourth ascent of the peak but the first from Pakistan.</p>\n\n<p>Around nine days later, on September 3, the Georgians began climbing the northwest face of Saraghrar Northwest at around 5,000m, continuing unroped up the huge snow and ice couloir that characterizes the lower half, to make a first bivouac at 6,200m. Above, a steep granite face formed the meat of the route.Overcoming hard mixed, free and aid climbing, they bivouacked at 6,400m, 6,600m, 6,750m, 6,850m, and ca 7,000m at the top of the face. Days five and six were spent on the difficult, vertical, 250m headwall, one pitch taking seven hours to lead.</p>\n\n<p>Above the top of the wall lay a long complex and corniced ridge with 300m of vertical gain. The three Georgians made their last bivouac near the exit to the Spanish route, and on their eighth day slogged up to the summit. The were able to reverse the route to the bivouac above the headwall on the same day, and next morning began rappelling the northwest face, continuing through the night until they reached the bottom.</p>\n\n<p>Info from -&nbsp;<a href=\"https://pioletsdor.net/index.php/en/home/2022-honoured-ascents/141-saraghrar-northwest-7-300m?mibextid=Zxz2cZ\" target=\"_blank\">pioletsdor.net</a></p>', NULL, NULL, NULL, NULL, '<blockquote>\n<p>&nbsp;</p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Посмотреть эту публикацию в Instagram</a></p>\n\n<p><a href=\"https://www.instagram.com/p/ClLYMMXo6HS/?utm_source=ig_embed&amp;utm_campaign=loading\" target=\"_blank\">Публикация от UFGM.ORG (@ufgm_org)</a></p>\n</blockquote>', NULL, NULL, '2022-11-20 06:22:07', '2022-11-21 17:52:24');

-- --------------------------------------------------------

--
-- Структура таблицы `locale_bisneses`
--

CREATE TABLE `locale_bisneses` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `locale_events`
--

CREATE TABLE `locale_events` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `info` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `locale_films`
--

CREATE TABLE `locale_films` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `coutry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'ka', 'Handboard', '<p>.</p>', '<p><strong>HANGBOARD</strong>- თითების სავარჯიშო, სამი სხვადასხვა ზომის ჭრილის მქონე ჰენგბორდი. ანვითარებს თითების, წინამხრის მომხრელ კუნთებს, ბიცეფსს, აძლიერებს მაჯებს და ამყარებს მაჯის მყესებს.</p>\n\n<p>მასალა: ხე.</p>\n\n<p>ზომები: 120X150 მმ (ჭრილების სიღრმეები 18მმ, 25მმ, 30მმ</p>', '2021-03-02 17:05:28', '2022-05-06 10:36:29'),
(2, 'ka', 'Mono hengboard', '<p>.</p>', '<p><strong>MICRO HANGBOARD</strong> - ეს ტრენაჟორი განკუთვნილია ცალი თითების ვარჯიშებისთვის. ის დაგეხმარებათ ხელის მტევნის ცალკეული თითების ვარჯიშებში და მყესების გამაგრებაში. საუკეთესო საშუალებაა ცალ თითებზე აზიდვებში სავარჯიშოდ.</p>\n\n<p>მასალ : ხე.</p>\n\n<p>ზომები: 80X50მმ (ნახვრეტის დიამეტრი 25მმ)</p>', '2021-03-04 16:58:51', '2022-05-06 10:31:07'),
(3, 'ka', 'Climbing Power Pistons', '<p>.</p>', '<p><strong>POWER PISTONS</strong> &ndash; დაგეხმარებათ მაჯების, წინამხრის მომხრელი კუნთების და ბიცეფსის ვარჯიშში.</p>\n\n<p>მასალა:&nbsp; ხე.</p>\n\n<p>ზომები : 200X55მმ</p>\n\n<p>მზადდება შეკვეთით</p>', '2021-03-04 17:03:30', '2022-05-06 10:28:35'),
(4, 'ka', 'Climbing Brush', '<p>.</p>', '<p>საჭიდების საწმენდი ჯაგრისი</p>', '2021-03-04 17:07:14', '2021-03-04 17:10:47'),
(5, 'ka', 'Liquid Chalk For Climbing', '<p>.</p>', '<p>რა არის თხევადი მაგნეზია? თხევადი ასასვლელი ცარცი არის სტანდარტული ცოცვის მაგნეზია შერეული სითხეში - ჩვეულებრივ ალკოჰოლის ფორმა - რომელიც ორთქლდება ჰაერის ზემოქმედებისას. ... დასრულების შემდეგ თქვენ მიიღებთ მაგნეზიის ბრწყინვალე და თანაბარ ფენას, რომელიც თითების ნაპრალებში ძალიან კარგად მოხვდება.</p>', '2021-03-04 17:11:24', '2021-03-04 17:15:25'),
(6, 'ka', 'სახლის სავარჯიშო ზონა', '<p>.</p>', '<p>თავისუფლად მდგომი კონსტრუქცია რომელზეც შესაძლებელია საცოცი ფინგერბორდის და სხვა აქსესუარების დამაგრება და მათი საშვალებით ვარჯიში.</p>\n\n<p>სიმაღლე 240სმ.</p>', '2021-03-20 05:04:41', '2021-03-20 05:12:23'),
(7, 'ka', 'საჭიდი 1', '<p>პატარა საცოცი საჭიდი</p>', '<p>პატარა საცოცი საჭიდი</p>', '2021-05-07 14:48:43', '2021-05-07 14:52:45'),
(8, 'ka', 'საჭიდი 1', '<p>პატარა საცოცი საჭიდი</p>', '<p>პატარა საცოცი საჭიდი</p>', '2021-05-07 14:52:56', '2021-05-07 14:57:25'),
(9, 'ka', 'საჭიდი 3', '<p>დიდი საჭიდი</p>', '<p>დიდი საცოცი საჭიდი</p>', '2021-05-19 15:38:24', '2021-05-19 15:42:00'),
(10, 'ka', 'საჭიდი 4', '<p>საცოცი საჭიდი</p>', '<p>დიდი საცოცი საჭიდი</p>', '2021-05-19 15:42:11', '2021-05-19 15:44:11'),
(11, 'ka', 'საჭიდი 5', '<p>საცოცი საჭიდი</p>', '<p>საცოცი საჭიდი შურუპით</p>', '2021-05-19 15:44:19', '2021-05-19 15:47:09'),
(12, 'ka', 'საჭიდი 6', '<p>პატარა საცოცი საჭიდი</p>', '<p>პატარა საცოცი საჭიდი</p>', '2021-05-19 15:47:15', '2021-05-19 15:50:12'),
(13, 'ka', 'საჭიდი 7', '<p>საცოცი საჭიდი&nbsp;</p>', '<p>საცოცი საჭიდი</p>', '2021-05-19 15:50:19', '2021-05-19 15:52:26'),
(14, 'ka', 'საჭიდების კომპლექტი 1', '<p>საჭიდების კომპლექტი</p>', '<p>საჭიდების კომპლექტი</p>', '2021-05-19 15:52:39', '2021-05-19 15:55:57'),
(15, 'ka', 'საჭიდი 8', '<p>საცოცი საჭიდი</p>', '<p>საცოცი საჭიდი</p>', '2021-05-19 16:06:29', '2021-05-19 16:09:09'),
(16, 'ka', 'საჭიდი 9', '<p>საცოცი საჭიდი</p>', '<p>საცოცი საჭიდი</p>', '2021-05-19 16:09:14', '2021-05-19 16:11:20'),
(17, 'ka', 'საჭიდი 10', '<p>საცოცი საჭიდი</p>', '<p>საცოცი საჭიდი</p>', '2021-05-26 06:35:52', '2021-05-26 06:38:51'),
(18, 'ka', 'საჭიდი 11', '<p>საჭიდი 11</p>', '<p>საჭიდი 11</p>', '2021-08-26 01:20:20', '2021-08-26 01:22:49'),
(19, 'ka', 'საჭიდი 12', '<p>საჭიდი 12</p>', '<p>საჭიდი 12</p>', '2021-08-26 01:23:03', '2021-08-26 01:25:23'),
(20, 'ka', 'საჭიდი 13', '<p>საჭიდი 13</p>', '<p>საჭიდი 13</p>', '2021-08-26 01:25:37', '2021-08-26 01:27:04'),
(21, 'ka', 'საჭიდიs kit 2', '<p>საჭიდიs kit 2</p>', '<p>საჭიდიs kit 2</p>', '2021-08-26 01:27:10', '2021-08-26 01:29:13'),
(22, 'ka', 'საჭიდი 14', '<p>საჭიდი 14</p>', '<p>საჭიდი 14</p>', '2021-08-26 01:29:26', '2021-08-26 01:31:19'),
(23, 'ka', 'საჭიდის ბოლტი (4cm)', '<p>საჭიდის ბოლტი (4cm)</p>', '<p>საჭიდის ბოლტი (4cm)</p>', '2021-08-26 01:31:39', '2021-08-26 01:34:53'),
(24, 'ka', 'საჭიდის ბოლტი(8cm)', '<p>საჭიდის ბოლტი/p>\n\n<p>80mm X 10mm</p>', '<p>საჭიდის ბოლტი/p>\n\n<p>80mm X 10mm</p>', '2021-08-26 11:19:14', '2021-08-26 11:22:21'),
(25, 'ka', 'საჭიდი 15', '<p>საჭიდი 15</p>', '<p>საჭიდი 15</p>', '2021-08-26 11:27:18', '2021-08-26 11:30:29'),
(26, 'ka', 'საჭიდი 16', '<p>საჭიდი 16</p>', '<p>საჭიდი 16</p>', '2021-08-26 11:30:50', '2021-08-26 11:33:46'),
(27, 'ka', 'საჭიდი 17', '<p>საჭიდი 17</p>', '<p>საჭიდი 17</p>', '2021-08-26 11:37:50', '2021-08-26 11:39:16'),
(28, 'ka', 'პაუკი', '<p>პაუკი 10mm</p>', '<p>პაუკი 10mm</p>', '2021-08-26 11:41:06', '2021-08-26 11:43:44'),
(30, 'ka', 'საჭიდი 18', '<p>.</p>', '<p>.</p>', '2021-12-15 17:04:05', '2021-12-15 17:15:03'),
(31, 'ka', 'საჭიდი 19', '<p>.</p>', '<p>.</p>', '2021-12-15 17:15:11', '2021-12-15 17:20:34'),
(32, 'ka', 'საჭიდი 20', '<p>.</p>', '<p>.საშუალო ზომის ხელოვნური საჭიდები</p>\n\n<p>გამოიყენება საცოც დარბაზებში, ხელოვნურ კლდეებზე და სახლში ხელოვნურ კედელზე დასამაგრებლად და სავარჯიშოდ.</p>', '2021-12-15 17:20:52', '2022-05-06 10:48:01'),
(33, 'ka', 'საჭიდი 21', '<p>.</p>', '<p>ხელოვნური საჭიდები, საშუალო ზომის, ჩანაჭდევებით.</p>\n\n<p>გამოიყენება ხელოვნურ კლდეებზე, საცოც&nbsp;დარბაზებში, სახლში საცოც კედელზე დასამაგრებლად და სავარჯიშოდ.</p>', '2021-12-15 17:29:54', '2022-05-06 10:44:50'),
(34, 'ka', 'საჭიდების კომპლექტი 1', '<p>.</p>', '<p>ხელოვნური, ფერადი საჭიდების სეტი, დიდი &nbsp;ზომის, ღრმა&nbsp;&nbsp;ჯიბეებით. გამოიყენება ხელოვნურ კლდეზე დასამაგრებლად (საცოც დარბაზებში, ბაღებში, სკვერებში განთავსებულ ხელოვნურ კლდებზე, ასევე სახლში გაკეთებულ საცოც კედელზე).</p>', '2021-12-15 17:31:53', '2022-05-06 07:54:01'),
(35, 'ka', 'საჭიდების კომპლექტი 2', '<p>.</p>', '<p>ხელოვნური, ფერადი საჭიდების სეტი, პატარა ზომის. გამოიყენება ხელოვნურ კლდეზე დასამაგრებლად (საცოც დარბაზებში, ბაღებში, სკვერებში განთავსებულ ხელოვნურ კლდებზე, ასევე სახლში გაკეთებულ საცოც კედელზე).</p>', '2021-12-15 17:35:38', '2022-05-06 07:52:55'),
(36, 'ka', 'საჭიდების კომპლექტი 3', '<p>.</p>', '<p>ხელოვნური, ფერადი საჭიდების სეტი, დიდი და საშუალო ზომის და სიღრმის ჯიბეებით. გამოიყენება ხელოვნურ კლდეზე, საცოც დარბაზებში, ბაღებში, სკვერებში განთავსებულ ხელოვნურ კლდეებზე, ასევე სახლში გაკეთებულ საცოც კედელზე დასამაგრებლად და სავარჯიშოდ.</p>', '2021-12-16 16:39:23', '2022-05-06 07:50:07'),
(37, 'ka', 'საჭიდი 22', '<p>.</p>', '<p>წითელი ფერის, მოგრძო ფორმის, დიდი ზომის ხელოვნური საჭიდი.</p>\n\n<p>გამოიყენება დარბაზებში, ხელოვნურ კლდეებზე და სახლში გაკეთებულ საცოც კედლებზე დასამაგრებლად.</p>\n\n<p>მზადდება შეკვეთით</p>', '2021-12-16 16:41:51', '2022-05-06 07:48:18'),
(38, 'ka', 'საჭიდი 23', '<p>.</p>', '<p>მრგვალი ფორმის, პატარა ზომის ხელოვნური საჭიდი.</p>\n\n<p>გამოიყენება დარბაზებში, ხელოვნურ კლდეებზე და სახლში გაკეთებულ საცოც კედლებზე დასამაგრებლად.</p>\n\n<p>მზადდება შეკვეთით</p>', '2021-12-16 16:44:49', '2022-05-06 07:47:14'),
(39, 'ka', 'საჭიდი 24', '<p>.</p>', '<p>ლურჯი ფერის ხელოვნური საჭიდი, მცირედი ჩანაჭდევებით.</p>\n\n<p>გამოიყენება დარბაზებში, ხელოვნურ კლდეებზე და სახლში გაკეთებულ საცოც კედლებზე დასამაგრებლად.</p>\n\n<p>მზადდება შეკვეთით</p>', '2021-12-16 16:46:44', '2022-05-06 07:45:13'),
(40, 'ka', 'ძალიან პატარა საჭიდები 1', '<p>1 საჭიდის ღირებულება 2.5 ლარი</p>', '<p>პატარა ზომის ხელოვნური, ფერადი&nbsp; საჭიდები. გამოიყენება, ხელოვნურ კლდეზე დასამაგრებლად და საცოცად. ასეთ მცირე ზომის საჭიდებზე ვარჯიშისას კარგად ვითარდება თითების მომხრელი და გამშლელი კუნთები.</p>\n\n<p>მზადდება შეკვეთით</p>\n\n<p>&nbsp;</p>\n\n<p>1 საჭიდის ღირებულება 2.5 ლარი</p>', '2021-12-16 16:52:41', '2022-05-06 07:41:19'),
(41, 'ka', 'მაგნეზიის ტომარა', '<p>მაგნეზიის ტომარა</p>', '<p>მაგნეზიის ტომარა</p>', '2022-02-07 17:09:14', '2022-02-07 17:13:28'),
(42, 'ka', 'თოკის ჩანთა', '<p>საცოცი თოკის ჩანთა</p>', '<p>საცოცი თოკის ზურგჩანთა, რომელიც გამოიყენება მხოლოდ თოკის ჩასადებად. მარტივად იშლება, აქვს სათავსო ჯიბე.</p>\n\n<p>ზომა: 1x1 მ</p>\n\n<p>მზადდება შეკვეთით</p>\n\n<p>&nbsp;</p>', '2022-02-07 17:13:35', '2022-05-06 07:40:23'),
(43, 'ka', 'ნაქსოვუ ქუდებიუ', '<p>ხელით ნაქსოვი ქუდები და ბაფები</p>', '<p>ხელით ნაქსოვი, ზამთრის ფერადი ქუდები და ბაფები ნაკერის გარეშე,&nbsp; ერთმაგი და ორმაგი ძაფით.</p>\n\n<p>შესაძლებელია შეკვეთა თქვენი დიზაინის მიხედვით.</p>\n\n<p>მზადდება შეკვეთით</p>', '2022-02-07 17:34:23', '2022-05-06 07:39:36'),
(44, 'ka', 'სახლში სავარჯიშო სტენდი', '<p>სახლში სავარჯიშო სტენდი</p>', '<p>სახლში სავარჯიშო ხის &nbsp;სტენდი &bdquo;ტურნიკით&ldquo;, რომელზეც მაგრედება როგორც&nbsp; სხვადასხვა ზომის ხელოვნური საჭიდები, ასევე &bdquo; ჰენგბორდი&rdquo;</p>\n\n<p>დაფის ზომა: 80x40x40 სმ</p>\n\n<p>&quot;ტურნიკის&quot; ზომა 80 სმ</p>\n\n<p>მზადდება შეკვეთით</p>\n\n<p>&nbsp;</p>', '2022-03-01 16:33:31', '2022-05-06 07:37:48'),
(45, 'ka', 'რელიეფები', '<p>საცოცი რელიეფების დამზადება</p>', '<p>საცოცი რელიეფების დამზადება შეკვეთით</p>', '2022-06-17 02:47:58', '2022-06-17 02:55:57'),
(101, 'ru', 'Birtvisi 2', '<p>dd</p>', '<pre>\nHANGBOARD - Упражнение для пальцев, доска с тремя надрезами разного размера. Развивает мышцы пальцев, предплечий, бицепсы, укрепляет запястья и укрепляет лучезапястные сухожилия.\nМатериал: дерево.\nРазмеры: 120X150 мм (глубина насечек 18 мм, 25 мм, 30 мм</pre>', '2021-03-02 17:05:28', '2022-05-06 10:36:29'),
(102, 'ru', 'Mono hengboard', '<p>.</p>', '<pre>\nMICRO HANGBOARD - Этот тренажер предназначен для упражнений одним пальцем. Это поможет вам тренировать отдельные пальцы рук и укреплять сухожилия. Лучший способ &mdash; тренироваться на отдельных пальцах.\nМатериал: дерево.\nРазмеры: 80X50 мм (диаметр отверстия 25 мм)</pre>', '2021-03-04 16:58:51', '2022-05-06 10:31:07'),
(103, 'ru', 'Climbing Power Pistons', '<p>.</p>', '<p>POWER PISTONS - Помогает тренировать запястья, предплечья и бицепсы.</p>\n\n<p>Материал: дерево.</p>\n\n<p>Размеры: 200X55 мм На заказ</p>', '2021-03-04 17:03:30', '2022-05-06 10:33:49'),
(104, 'ru', 'Climbing Brush', '<p>.</p>', '<p>Щетка для чистых зацепок</p>', '2021-03-04 17:07:14', '2021-03-04 17:10:47'),
(105, 'ru', 'Liquid Chalk For Climbing', '<p>.</p>', '<p>Что такое жидкий магнезия? Жидкий магнезия для лазания - это стандартный магнезия для лазания, смешанный с жидкостью - обычно в форме спирта - которая испаряется при контакте с воздухом. ... Как только это будет сделано, вы получите блестящий и ровный слой магнезии, который очень хорошо проникает в щели на ваших пальцах.</p>', '2021-03-04 17:11:24', '2021-03-04 17:15:25'),
(106, 'ru', 'Домашняя тренировочная зона', '<p>.</p>', '<p>Отдельно стоящая конструкция, к которой можно прикрепить фингерборд и другие аксессуары и с их помощью выполнять упражнения.</p>\n\n<p>Высота 240см.</p>', '2021-03-20 05:04:41', '2021-03-20 05:12:23'),
(107, 'ru', 'Зацепка 1', '<p>Малый зацеп для лазанья</p>', '<p>Малый зацеп для лазанья</p>', '2021-05-07 14:48:43', '2021-05-07 14:52:45'),
(108, 'ru', 'Зацепка 1', '<p>Малый зацепка&nbsp;для лазанья</p>', '<p>Малый зацепка&nbsp;для лазанья</p>', '2021-05-07 14:52:56', '2021-05-07 14:57:25'),
(109, 'ru', 'зацепка 3', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 15:38:24', '2021-05-19 15:42:00'),
(110, 'ru', 'Зацепка 4', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 15:42:11', '2021-05-19 15:44:11'),
(111, 'ru', 'Зацепка 1', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 15:44:19', '2021-05-19 15:47:09'),
(112, 'ru', 'Зацепка 6', '<p>Малый зацепка&nbsp;для лазанья</p>', '<p>Малый зацепка&nbsp;для лазанья</p>', '2021-05-19 15:47:15', '2021-05-19 15:50:12'),
(113, 'ru', 'Зацепка 7', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 15:50:19', '2021-05-19 15:52:26'),
(114, 'ru', 'Комплект зацепок 1', '<p>Комплект зацепок для скалолазания</p>', '<p>Комплект зацепок для скалолазания</p>', '2021-05-19 15:52:39', '2021-05-19 15:55:57'),
(115, 'ru', 'Зацепка 8', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 16:06:29', '2021-05-19 16:09:09'),
(116, 'ru', 'Зацепка 9', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-19 16:09:14', '2021-05-19 16:11:20'),
(117, 'ru', 'Зацепка 10', '<p>зацепка&nbsp;для лазанья</p>', '<p>зацепка&nbsp;для лазанья</p>', '2021-05-26 06:35:52', '2021-05-26 06:38:51'),
(118, 'ru', 'Зацепка11', '<p>Зацепка11</p>', '<p>Зацепка11</p>', '2021-08-26 01:20:20', '2021-08-26 01:22:49'),
(119, 'ru', 'Зацепка12', '<p>Зацепка12</p>', '<p>Зацепка12</p>', '2021-08-26 01:23:03', '2021-08-26 01:25:23'),
(120, 'ru', 'Зацепка13', '<p>Зацепка13</p>', '<p>Зацепка13</p>', '2021-08-26 01:25:37', '2021-08-26 01:27:04'),
(121, 'ru', 'Комплект зацепок 2', '<p>Комплект зацепок 2</p>', '<p>Holds kit 2</p>', '2021-08-26 01:27:10', '2021-08-26 01:29:13'),
(122, 'ru', 'Зацепка14', '<p>Зацепка14</p>', '<p>Зацепка14</p>', '2021-08-26 01:29:26', '2021-08-26 01:31:19'),
(123, 'ru', 'Climbing Holds bolt (4cm)', '<p>Climbing Holds bolt (4cm)</p>', '<p>Climbing Holds bolt (4cm)</p>', '2021-08-26 01:31:39', '2021-08-26 01:34:53'),
(124, 'ru', 'Climbing Зацепкаbolt (8cm)', '<p>Climbing Зацепкаbolt</p>\n\n<p>80mm X 10mm</p>', '<p>Climbing Зацепкаbolt</p>\n\n<p>80mm X 10mm</p>', '2021-08-26 11:19:14', '2021-08-26 11:22:21'),
(125, 'ru', 'Зацепка15', '<p>Зацепка15</p>', '<p>Зацепка15</p>', '2021-08-26 11:27:18', '2021-08-26 11:30:29'),
(126, 'ru', 'Зацепка16', '<p>Зацепка16</p>', '<p>Зацепка16</p>', '2021-08-26 11:30:50', '2021-08-26 11:33:46'),
(127, 'ru', 'Зацепка17', '<p>Зацепка17</p>', '<p>Зацепка17</p>', '2021-08-26 11:37:50', '2021-08-26 11:39:16'),
(128, 'ru', 'Паук', '<p>Паук 10mm</p>', '<p>Паук 10mm</p>', '2021-08-26 11:41:06', '2021-08-26 11:43:44'),
(130, 'ru', 'Зацепка18', '<p>.</p>', '<p>.</p>', '2021-12-15 17:04:05', '2021-12-15 17:15:03'),
(131, 'ru', 'Зацепка19', '<p>.</p>', '<p>.</p>', '2021-12-15 17:15:11', '2021-12-15 17:20:34'),
(132, 'ru', 'Зацепка20', '<p>.</p>', '<p>.</p>', '2021-12-15 17:20:52', '2021-12-15 17:26:19'),
(133, 'ru', 'Зацепка21', '<p>.</p>', '<p>.</p>', '2021-12-15 17:29:54', '2021-12-15 17:31:11'),
(134, 'ru', 'Комплект зацепок  1', '<p>.</p>', '<p>.</p>', '2021-12-15 17:31:53', '2021-12-15 17:34:44'),
(135, 'ru', 'Комплект зацепок  2', '<p>.</p>', '<p>.</p>', '2021-12-15 17:35:38', '2021-12-15 17:37:44'),
(136, 'ru', 'Комплект зацепок  3', '<p>.</p>', '<p>.</p>', '2021-12-16 16:39:23', '2021-12-16 16:41:07'),
(137, 'ru', 'Зацепка22', '<p>.</p>', '<p>.</p>', '2021-12-16 16:41:51', '2021-12-16 16:43:57'),
(138, 'ru', 'Зацепка23', '<p>.</p>', '<p>.</p>', '2021-12-16 16:44:49', '2021-12-16 16:46:36'),
(139, 'ru', 'Зацепка24', '<p>.</p>', '<p>.</p>', '2021-12-16 16:46:44', '2021-12-16 16:50:32'),
(140, 'ru', 'Micro holds 1', '<p>1 Зацепкаprice 2.5 GEL</p>', '<p>1 Зацепкаprice 2.5 GEL</p>', '2021-12-16 16:52:41', '2021-12-16 16:54:49'),
(141, 'ru', 'Мешочек для магнезии', '<p>Мешочек для магнезии</p>', '<p>Мешочек для магнезии</p>', '2022-02-07 17:09:14', '2022-02-07 17:13:28'),
(142, 'ru', 'Сумка для верёвки', '<p>Сумка для скалолазной верёвки</p>', '<p>Сумка для скалолазной верёвки</p>', '2022-02-07 17:13:35', '2022-02-07 17:18:45'),
(143, 'ru', 'Вязаные шапки', '<p>Шапки ручной вязки</p>', '<p>Шапки ручной вязки - пошив на заказ индивидуального выборочного цвета.</p>', '2022-02-07 17:34:23', '2022-02-07 17:40:30'),
(144, 'ru', 'Стенд для домашних упражнений', '<p>Стенд для домашних упражнений&nbsp;</p>', '<p>Стенд для домашних упражнений&nbsp;изготавливается на заказ. Для подробной информации, пожалуйста, свяжитесь с нами</p>', '2022-03-01 16:33:31', '2022-03-01 16:47:26'),
(145, 'ru', 'Рельефы', '<p>Изготовление рельефов</p>', '<p>Изготовление рельефов на заказ</p>', '2022-06-17 02:47:58', '2022-06-17 02:55:57'),
(201, 'us', 'Handboard', '<p>.</p>', '<p>HANGBOARD- A finger exercise, a hangboard with three different sized incisions. Develops the muscles of the fingers, forearms, biceps, strengthens the wrists and strengthens the wrist tendons.<br />\nMaterial: wood.<br />\nDimensions: 120X150 mm (depth of incisions 18 mm, 25 mm, 30 mm</p>', '2021-03-02 17:05:28', '2022-05-06 10:36:29'),
(202, 'us', 'Mono hengboard', '<p>.</p>', '<p>MICRO HANGBOARD - This simulator is designed for one-finger exercises. It will help you to exercise the individual fingers of the hand and strengthen the tendons. The best way is to practice lifting on individual fingers.<br />\nMaterial: wood.<br />\nDimensions: 80X50 mm (hole diameter 25 mm)</p>', '2021-03-04 16:58:51', '2022-05-06 10:31:07'),
(203, 'us', 'Climbing Power Pistons', '<p>.</p>', '<p>POWER PISTONS - Helps you train your wrists, forearms and biceps.</p>\n\n<p>Material: wood.</p>\n\n<p>Dimensions: 200X55 mm</p>\n\n<p>Made to order</p>', '2021-03-04 17:03:30', '2022-05-06 10:28:35'),
(204, 'us', 'Climbing Brush', '<p>,</p>', '<p>Brush for clean climbing holds</p>', '2021-03-04 17:07:14', '2021-03-04 17:10:47'),
(205, 'us', 'Liquid Chalk For Climbing', '<p>.</p>', '<p>What Is Liquid Chalk? Liquid climbing chalk is standard climbing chalk mixed with a liquid - normally a form of alcohol - that evaporates when exposed to air. ... Once it&#39;s done you get a brilliant and even layer of chalk that gets into the crevices in your fingers really well.</p>', '2021-03-04 17:11:24', '2021-03-04 17:15:25'),
(206, 'us', 'Home training zone', '<p>.</p>', '<p>A freestanding structure to which you can attach a fingerboard and other accessories for exercise.</p>\n\n<p>Height 240cm.&nbsp;</p>', '2021-03-20 05:04:41', '2021-03-20 05:12:23'),
(207, 'us', 'Hold 1', '<p>Small climbing hold</p>', '<p>Small climbing hold</p>', '2021-05-07 14:48:43', '2021-05-07 14:52:45'),
(208, 'us', 'Hold 2', '<p>Small climbing hold</p>', '<p>Small climbing hold</p>', '2021-05-07 14:52:56', '2021-05-07 14:57:25'),
(209, 'us', 'Hold 3', '<p>Big climbing hold</p>', '<p>Big climbing hold</p>', '2021-05-19 15:38:24', '2021-05-19 15:42:00'),
(210, 'us', 'Hold 4', '<p>Climbing hold</p>', '<p>Big climbing hold</p>', '2021-05-19 15:42:11', '2021-05-19 15:44:11'),
(211, 'us', 'Hold 5', '<p>Climbing holds with screws</p>', '<p>Climbing holds with screws</p>', '2021-05-19 15:44:19', '2021-05-19 15:47:09'),
(212, 'us', 'Hold 6', '<p>Small climbing hold</p>', '<p>Small climbing hold</p>', '2021-05-19 15:47:15', '2021-05-19 15:50:12'),
(213, 'us', 'Hold 7', '<p>Climbing hold</p>', '<p>Climbing hold</p>', '2021-05-19 15:50:19', '2021-05-19 15:52:26'),
(214, 'us', 'Holds kit 1', '<p>Climbing holds kit</p>', '<p>Climbing holds kit</p>', '2021-05-19 15:52:39', '2021-05-19 15:55:57'),
(215, 'us', 'Hold 8', '<p>Climbing hold</p>', '<p>Climbing hold</p>', '2021-05-19 16:06:29', '2021-05-19 16:09:09'),
(216, 'us', 'Hold 9', '<p>Climbing hold</p>', '<p>Climbing hold</p>', '2021-05-19 16:09:14', '2021-05-19 16:11:20'),
(217, 'us', 'Hold 10', '<p>Climbing holds with screws</p>', '<p>Climbing holds with screws</p>', '2021-05-26 06:35:52', '2021-05-26 06:38:51'),
(218, 'us', 'Hold 11', '<p>Hold 11</p>', '<p>Hold 11</p>', '2021-08-26 01:20:20', '2021-08-26 01:22:49'),
(219, 'us', 'Hold 12', '<p>Hold 12</p>', '<p>Hold 12</p>', '2021-08-26 01:23:03', '2021-08-26 01:25:23'),
(220, 'us', 'Hold 13', '<p>Hold 13</p>', '<p>Hold 13</p>', '2021-08-26 01:25:37', '2021-08-26 01:27:04'),
(221, 'us', 'Holds kit 2', '<p>Holds kit 2</p>', '<p>Holds kit 2</p>', '2021-08-26 01:27:10', '2021-08-26 01:29:13'),
(222, 'us', 'Hold 14', '<p>Hold 14</p>', '<p>Hold 14</p>', '2021-08-26 01:29:26', '2021-08-26 01:31:19'),
(223, 'us', 'Climbing Holds bolt (4cm)', '<p>Climbing Holds bolt (4cm)</p>', '<p>Climbing Holds bolt (4cm)</p>', '2021-08-26 01:31:39', '2021-08-26 01:34:53'),
(224, 'us', 'Climbing hold bolt (8cm)', '<p>Climbing hold bolt</p>\n\n<p>80mm X 10mm</p>', '<p>Climbing hold bolt</p>\n\n<p>80mm X 10mm</p>', '2021-08-26 11:19:14', '2021-08-26 11:22:21'),
(225, 'us', 'Hold 15', '<p>Hold 15</p>', '<p>Hold 15</p>', '2021-08-26 11:27:18', '2021-08-26 11:30:29'),
(226, 'us', 'Hold 16', '<p>Hold 16</p>', '<p>Hold 16</p>', '2021-08-26 11:30:50', '2021-08-26 11:33:46'),
(227, 'us', 'Hold 17', '<p>Hold 17</p>', '<p>Hold 17</p>', '2021-08-26 11:37:50', '2021-08-26 11:39:16'),
(228, 'us', 'T-nuts', '<p>T-nuts 10mm</p>', '<p>T-nuts 10mm</p>', '2021-08-26 11:41:06', '2021-08-26 11:43:44'),
(230, 'us', 'hold 18', '<p>.</p>', '<p>.</p>', '2021-12-15 17:04:05', '2021-12-15 17:15:03'),
(231, 'us', 'Hold 19', '<p>.</p>', '<p>.</p>', '2021-12-15 17:15:11', '2021-12-15 17:20:34'),
(232, 'us', 'Hold 20', '<p>.</p>', '<p>.</p>', '2021-12-15 17:20:52', '2021-12-15 17:26:19'),
(233, 'us', 'Hold 21', '<p>.</p>', '<p>.</p>', '2021-12-15 17:29:54', '2021-12-15 17:31:11'),
(234, 'us', 'Holds set 1', '<p>.</p>', '<p>.</p>', '2021-12-15 17:31:53', '2021-12-15 17:34:44'),
(235, 'us', 'Holds set 2', '<p>.</p>', '<p>.</p>', '2021-12-15 17:35:38', '2021-12-15 17:37:44'),
(236, 'us', 'Holds set 3', '<p>.</p>', '<p>.</p>', '2021-12-16 16:39:23', '2021-12-16 16:41:07'),
(237, 'us', 'Hold 22', '<p>.</p>', '<p>.</p>', '2021-12-16 16:41:51', '2021-12-16 16:43:57'),
(238, 'us', 'Hold 23', '<p>.</p>', '<p>.</p>', '2021-12-16 16:44:49', '2021-12-16 16:46:36'),
(239, 'us', 'Hold 24', '<p>.</p>', '<p>.</p>', '2021-12-16 16:46:44', '2021-12-16 16:50:30'),
(240, 'us', 'Micro holds 1', '<p>1 hold price 2.5 GEL</p>', '<p>1 hold price 2.5 GEL</p>', '2021-12-16 16:52:41', '2021-12-16 16:54:49'),
(241, 'us', 'Chalk bag', '<p>chalk bag</p>', '<p>Chalk bag for climbing.</p>', '2022-02-07 17:09:14', '2022-02-07 17:13:28'),
(242, 'us', 'Climbing rope bag', '<p>Climbing rope bag</p>', '<p>Climbing rope bag</p>', '2022-02-07 17:13:35', '2022-02-07 17:18:45'),
(243, 'us', 'Handmade hats', '<p>Handmade hats</p>', '<p>Handmade hats <img alt=\"🧶\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t21/1/16/1f9f6.png\" style=\"height:16px; width:16px\" /><img alt=\"🧵\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/ta0/1/16/1f9f5.png\" style=\"height:16px; width:16px\" /></p>\n\n<p>Different colors</p>\n\n<p>Pick a color of your choice and order</p>', '2022-02-07 17:34:23', '2022-02-07 17:40:30'),
(244, 'us', 'Home exercise stand', '<p>Home exercise stand</p>', '<p>The home exercise stand is made to order. For details please contact us.</p>', '2022-03-01 16:33:31', '2022-03-01 16:47:26'),
(245, 'us', 'Climbing volumes', '<p>Climbing volumes</p>', '<p>Production of climbing volumes to order</p>', '2022-06-17 02:47:58', '2022-06-17 02:55:57');

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

-- --------------------------------------------------------

--
-- Структура таблицы `locale_sites`
--

CREATE TABLE `locale_sites` (
  `id` bigint UNSIGNED NOT NULL,
  `locale` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guid_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guid_description` text COLLATE utf8mb4_unicode_ci,
  `guid_short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `films_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `films_description` text COLLATE utf8mb4_unicode_ci,
  `films_short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forum_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forum_description` text COLLATE utf8mb4_unicode_ci,
  `forum_short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_description` text COLLATE utf8mb4_unicode_ci,
  `shop_short_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_activity_description` text COLLATE utf8mb4_unicode_ci,
  `mount_description` text COLLATE utf8mb4_unicode_ci,
  `event_description` text COLLATE utf8mb4_unicode_ci,
  `tech_tips_description` text COLLATE utf8mb4_unicode_ci,
  `news_description` text COLLATE utf8mb4_unicode_ci,
  `index_gallery_description` text COLLATE utf8mb4_unicode_ci,
  `outdoor_description` text COLLATE utf8mb4_unicode_ci,
  `indoor_description` text COLLATE utf8mb4_unicode_ci,
  `ice_description` text COLLATE utf8mb4_unicode_ci,
  `topo_description` text COLLATE utf8mb4_unicode_ci,
  `what_we_do_description` text COLLATE utf8mb4_unicode_ci,
  `products_description` text COLLATE utf8mb4_unicode_ci,
  `services_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `locale_sites`
--

INSERT INTO `locale_sites` (`id`, `locale`, `guid_title`, `guid_description`, `guid_short_description`, `films_title`, `films_description`, `films_short_description`, `forum_title`, `forum_description`, `forum_short_description`, `shop_title`, `shop_description`, `shop_short_description`, `other_activity_description`, `mount_description`, `event_description`, `tech_tips_description`, `news_description`, `index_gallery_description`, `outdoor_description`, `indoor_description`, `ice_description`, `topo_description`, `what_we_do_description`, `products_description`, `services_description`, `created_at`, `updated_at`) VALUES
(1, 'us', 'Rock climbing In Georgia', 'Rock climbing in Georgia is a developing sport, but the country has a great prospect in rock climbing and in mountaineering. We constantly collect information about promising rocks, we am happy to accept your messages about new and unknown places. \n                                            There are many ready-made regions for all kinds of climbing. Also there are many places to implement and establish new climbing routes and regions, so it is very attractive for those who are searching for new unique places and opportunities.\n                                            If you need any additional information feel free to contact us. We will provide you with all essential details, suggest a concrete way how to get to the site of climbing or offer his services for equipment and take you to the rock climbing areas.\n                                            Alse if you need a climbing guide you can contact us. We can accompany you to any mountaineering and rock climbing regions throughout Georgia. Tour prices depend on the complexity of the route and the size of the group. Write to us for more details. Celk here for write message.\n                                            We are constantly updating information about all regions so that you get full information. \n                                            Information partners. climbingingeorgia.wordpress.com\n                                            See information about climbing areas in neighboring countries.\n                                            Climbing in Armenia - uptherock.com\n                                            Climbing in Turkey - climbing-europe.com/RockClimbingTurkey', 'Rock climbing and mountaineering in Georgia', 'Rock climbing films', 'Climbing movies', 'Climbing movies', 'Georgian Climbing forum', 'Georgian climbing forum', 'Georgian climbing forum', 'Climbing.ge products and services', 'Climbing.ge products', 'Climbing.ge products', 'Other activity for active rest in Georgia', 'Mountaineering in Georgia', 'event_description', 'Climbing tech tips', 'Georgian climbing news', 'Gallery', 'Outdoor rock climbing area in Georgia', 'Indoor climbing gyms in Georgia', 'Ice climbing in Georgia', 'Climbing topo', 'Georgia rock climbing and mountaineering description', 'Climbing.ge products', 'Climbing.ge services', NULL, NULL),
(2, 'ka', 'მეკლდეურობა საქართველოში', 'სერვისი climbing.ge, არის საინფორმაციო საიტი, რომელიც მიზნად ისახავს საქართველოს საცოცი და ალპინისტური მარშრუტების დეტალურ აღწერას და ამ იმფორმაციის მეტად პოპულარიზაციას, რათა ეს იმფორმაცია იყოს მეტად ხელმისაწვდომი, როგორც საქართველოს მოსახლეობისთვის, ასევე სხვა ქვეყნიდან ჩამოსული სპორცმენებისთვის. ჩვენი გუნდი მუდმივად მუსაობს ოიმისთვის, რომ იმფორმაცია ხშირად განახლდეს, დაემატოს მეტი სიახლე და ყველა საცოცი რეგიონი თუ ალპინისტური მარშრუტი იყოს რაც შეიძლება დეტალურად აღწერილი.,\n                                                საქართველოში მეკლდეურობა და სპორტული ცოცვა ჯერჯერობით არ არის განვითარებული სათანადო დონეზე, მაგრამ ჩვენ მუდამ ვცდილობთ ვიპოვოთ და შევქმნათ თქვენთვის საინტერესო საცოცი რეგიონები. აღმოვაჩინოთ ახალი, საცოცად გამოსადეგი კლდეები და ამ ადგილებში რაც შეიძლება მეტი საცოცი მარშრუტი გავაკეთოთ . სამწუხაროდ ჯერჯერობით ყვეკაფერი ეს ჩვენი პირადი ხარჯებით ხორციელდება, ამიტომაც ეს ყველაფერი საკმაოდ ნელი ტემპებით მიმდინარეობს. ჩვენ მუდმივად მზად ვართ პარტნიორობისთის, რათა სპორტის ეს სახეობა უფრო მეტად განვითარდეს. ჩვენ მოხარულები ვიქნებით ყველა ტიპის დახმარებისთვის, იქნება ეს ფინანსური, ტექნიკური თუ გამოცდილების გაზიარება.\n                                                ასევე ჩვენს საიტზე თქვენ შეგიძლიათ იხილოთ ალპინისტური მარშრუტების არწერაც. საქართველოს უდიდესი ნაწილი მტებითაა დაფარული. აქ არის ბევრი ულამაზესი და ტექნიკურად რთული მწვერვალი. როგორიცაა: უშბა, ტეთნულდი, შხარა, მყინვარი, ჭაუხის მასივი და ბევრი სხვა. ამ მთებზე არის ბევრი მარშრუტი . სამწუხაროდ ეს მარშრუტები არ არის აღწერილი. ჩვენ ვცდილობთ შევაგროვოთ და ავღწეროთ ჩვენი თუ სხვის მიერ გავლილი მარშრუტები. თუ თქვენ გაქვთ იმფორმაცია და რომელიმე მარშრუტის აღწერა, თქვენ შეგიძლიათ გამოგვიგზავნოთ ის. ეს ჩვენ ძალიან დაგვეხმარება.\n                                                თუ თქვენ დაგწირდათ დამატებითი იმფორმაცია ან თქვენ გინდათ გიდის აყვანა საქართველოს ნებისმიერ რეგიონში, მოგვწერეთ ქვემოთ მოყვანილი ფორმის მეშვეობით. ჩვენ მალე გიპასუხებთ\n                                                Climbing.ge - ის გუნდი ყველას კარ ცოცვას და ვარჯიშს გისურვებთ.', 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა', 'ცოცვის ფილმები', 'ცოცვის ფილმები', 'ცოცვის ფილმები', 'ქართული ცოცვის ფორუმი', 'ქართული ცოცვის ფორუმი', 'ქართული ცოცვის ფორუმი', 'Climbing.ge-ს საქონელი და მომსახურება', 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ', 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ', 'სხვა აკტიობები', 'ალპინისტური მარშრუტები საქართველოში(კავკასიონის ქედი)', 'ცოცვა ბუნებრივ კლდეზე', 'ცოცვის ტექნიკური რჩევები', 'ცოცვის სიახლეები საქართველოშე', 'გალერეა', 'ცოცვა ბუნებრივ კლდეზე', 'საცოცი დარბაზები', 'ყინულზე ცოცვა', 'საქართველოს საცოცი მარშრუტები რუკაზე', 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა', 'Climbing.ge-ს პროდუქცია', 'Climbing.ge-ს მომსახურება', NULL, NULL),
(3, 'ru', 'Скалолазание в Грузии', 'Сервис Climbing.ge – это информационный сайт цель которого детальное описание альпинистких и скалолазных маршрутов в Грузии, и эту информацию сделать более доступной как для населения Грузии так и для спортсменов приезжающих из других стран,\n                                                Наша команда постоянно работает над обновлением информации и добавляет детальное описание во все скалолазные регионы и альпинсткие маршруты. Старается описать всё как можно подробнее.\n                                                В Грузии спортивное лазание и скалолазание в настоящее время не развито на должном уровне и недостаточно развито по регионам. Мы постоянно стараемся найти и создать для вас интересные маршруты для лазания и разрабатываем новые регионы, стараемся обнаружить новые, пригодные для лазания, скалы и в этом месте создать как можно больше маршрутов для скалолазания.\n                                                К сожалению, пока что, это всё происходит на наши личные средства и поэтому процесс разработки новых трасс и регионов продвигается в медленном темпе .Мы готовы к партнёрству и сотрудничеству для развития данного вида спорта. Мы будем рады помощи любого типа, будь то финансовая, техническая помощь или если вы поделитесь с нами вашим опытом.\n                                                Также на нашем сайте вы можете найти описание альпинистких маршрутов. Большая часть Грузии покрыта горами .Здесь есть красивейшие и технически сложные вершины , такие как: Ушба, Шхара, Казбег, Чаухский массив и многое другое. На склонах этих гор множество маршрутов. К сожалению эти маршруты не описаны. Мы старались собрать и описать пройденные нами или другими альпинистами маршруты. Если у вас есть информация и описание какого-либо маршрута, вы можете прислать эту информацию нам. Нам поможет эта информация в работе.\n                                                Если вам понадобится дополнительная информация или вам необходимо взять гида для любого региона Грузии, напишите нам ,внизу дана форма и мы ответим вам очень быстро,\n                                                CLIMBING.GE- желает вам хороших восхождений и тренировок.', 'Скалолазание и альпинизм в Грузии', 'Фильмы о скалолазании', 'Фильмы о скалолазании', 'Фильмы о скалолазании', 'Форум скалолазание Грузии', 'Форум скалолазание Грузии', 'Форум скалолазание Грузии', 'Товары и услуги Climbing.ge', 'Climbing.ge продукты', 'Climbing.ge продукты', 'Другая деятельность для активного отдыха в Грузии', 'Альпинизм в Грузии', 'Скалолазание на открытом воздухе в Грузии', 'Советы по альпинизму', 'Новости скалолазания в Грузинского ', 'Галерея', 'Скалолазание на открытом воздухе в Грузии', 'Скалолазные залы в грузии', 'Ледолазание в Грузии', 'Восхождение на вершину', 'Описание скалолазания и альпинизма в Грузии', 'Продукция Climbing.ge', 'Услуги Climbing.ge', NULL, NULL);

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
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2021_02_20_205157_create_locale_articles_table', 1),
(7, '2021_02_20_205753_create_locale_products_table', 1),
(8, '2021_03_18_103449_create_locale_services_table', 1),
(9, '2021_06_09_191117_create_locale_mounts_table', 1),
(10, '2021_12_03_055844_create_user_adreses_table', 1),
(11, '2022_05_08_195936_create_permissions_table', 1),
(12, '2022_05_08_200222_create_user_permission_table', 1),
(13, '2022_05_08_205050_create_general_infos_table', 1),
(14, '2022_05_08_205234_create_user_bans_table', 1),
(15, '2022_05_08_205305_create_user_complaints_table', 1),
(16, '2022_05_08_205322_create_user_followers_table', 1),
(17, '2022_05_08_205334_create_user_sites_table', 1),
(18, '2022_05_10_162810_create_service_followers_table', 1),
(19, '2022_05_10_163002_create_regions_table', 1),
(20, '2022_05_10_163156_create_topics_table', 1),
(21, '2022_05_10_183058_create_mounts_table', 1),
(22, '2022_05_10_183208_create_services_table', 1),
(23, '2022_05_11_130859_create_product_categories_table', 1),
(24, '2022_05_11_130860_create_products_table', 1),
(25, '2022_05_11_130861_create_favorite_products_table', 1),
(26, '2022_05_11_130862_create_product_options_table', 1),
(27, '2022_05_11_130869_create_orders_table', 1),
(28, '2022_05_11_130870_create_order_products_table', 1),
(29, '2022_05_11_130873_create_service_images_table', 1),
(30, '2022_05_11_130879_create_articles_table', 1),
(31, '2022_05_11_130886_create_sectors_table', 1),
(32, '2022_05_11_130887_create_sector_images_table', 1),
(33, '2022_05_11_130888_create_article_comments_table', 1),
(34, '2022_05_11_130892_create_mount_route_images_table', 1),
(35, '2022_05_11_130893_create_spot_rocks_images_table', 1),
(36, '2022_05_11_130895_create_option_images_table', 1),
(37, '2022_05_11_130896_create_mtps_table', 1),
(38, '2022_05_11_130897_create_routes_table', 1),
(39, '2022_05_11_130898_create_carts_table', 1),
(40, '2022_05_11_130902_create_mtp_pitchs_table', 1),
(41, '2022_05_28_191905_create_user_requests_table', 1),
(42, '2022_05_28_191906_create_forum_posts_table', 1),
(43, '2022_05_28_191907_create_post_comments_table', 1),
(44, '2022_05_28_191908_create_post_likes_table', 1),
(45, '2022_05_28_191909_create_post_complaints_table', 1),
(46, '2022_10_09_093853_create_film_tags_table', 1),
(47, '2022_10_11_205856_create_locale_films_table', 1),
(48, '2022_10_12_144640_create_favorite_outdoor_areas_table', 1),
(49, '2022_11_02_111052_create_user_notifications_table', 1),
(50, '2022_11_05_124442_create_article_comment_complaints_table', 1),
(51, '2022_11_05_135011_create_article_comment_queries_table', 1),
(52, '2022_11_07_102000_create_shiped_countries_table', 1),
(53, '2022_11_08_192600_create_article_comment_user_table', 1),
(54, '2022_11_08_192601_create_general_info_article_table', 1),
(55, '2022_11_08_192602_create_gallery_categories_table', 1),
(56, '2022_11_08_192603_create_gallery_images_table', 1),
(57, '2022_11_08_211614_create_article_mount_table', 1),
(58, '2022_11_08_211633_create_article_region_table', 1),
(59, '2022_11_08_213857_create_product_comments_table', 1),
(60, '2022_11_12_182438_create_sale_codes_table', 1),
(61, '2022_11_17_130210_create_film_categories_table', 1),
(62, '2022_11_21_195322_create_sector_local_images_table', 1),
(63, '2022_11_21_195414_create_sector_local_image_sector_table', 1),
(64, '2022_11_21_195815_create_locale_events_table', 1),
(65, '2022_11_21_195816_create_events_table', 1),
(66, '2022_11_28_105442_create_gallery_image_article_table', 1),
(67, '2022_12_02_112640_create_locale_bisneses_table', 1),
(68, '2022_12_02_112718_create_suport_local_bisneses_table', 1),
(69, '2022_12_02_112727_create_suport_local_bisnes_images_table', 1),
(70, '2022_12_03_092756_create_locale_sites_table', 1),
(71, '2022_12_03_092757_create_sites_table', 1),
(72, '2022_12_10_135007_create_site_social_links_table', 1),
(73, '2022_12_10_135008_create_films_table', 1),
(74, '2022_12_10_135009_create_user_roles_table', 1),
(75, '2022_12_10_135010_create_role_permissions_table', 1),
(76, '2022_12_10_135011_create_interested_events_table', 1),
(77, '2022_12_10_135012_create_film_files_table', 1),
(78, '2022_12_10_135013_create_favorite_films_table', 1),
(79, '2022_12_10_135014_create_film_reitings_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mounts`
--

CREATE TABLE `mounts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published` int DEFAULT NULL,
  `map` text COLLATE utf8mb4_unicode_ci,
  `weather` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `us_mount_id` bigint UNSIGNED NOT NULL,
  `ka_mount_id` bigint UNSIGNED NOT NULL,
  `ru_mount_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_ascent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mtps`
--

INSERT INTO `mtps` (`id`, `name`, `text`, `height`, `author`, `first_ascent`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(1, 'Spider', NULL, NULL, NULL, NULL, NULL, 15, '2019-05-03 12:53:26', '2019-05-03 05:28:37'),
(2, 'Multu P.', NULL, NULL, NULL, NULL, NULL, 15, '2019-05-03 09:29:07', '2019-05-03 05:29:07'),
(4, 'project (N8)', NULL, NULL, NULL, NULL, NULL, 39, '2019-12-29 16:35:08', '2019-12-29 12:35:08'),
(5, 'project (N9)', NULL, NULL, NULL, NULL, NULL, 39, '2019-12-29 16:36:16', '2019-12-29 12:36:16'),
(6, 'project (N10)', NULL, NULL, NULL, NULL, NULL, 39, '2019-12-29 16:36:30', '2019-12-29 12:36:30'),
(7, 'project (N11)', NULL, NULL, NULL, NULL, NULL, 39, '2019-12-29 16:36:43', '2019-12-29 12:36:43'),
(8, 'project (N12)', NULL, NULL, NULL, NULL, NULL, 39, '2020-01-01 16:00:49', '2019-12-29 12:37:18'),
(9, 'project (N13)', NULL, NULL, NULL, NULL, NULL, 39, '2020-01-01 15:44:30', '2019-12-29 12:37:44');

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
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, 'Multu P.', NULL, '6a+', NULL, '13', 'Guga Dabrundashvili', NULL, '25', NULL, NULL, 1, 2, NULL, NULL),
(2, 'Multu P.', NULL, '6b+', NULL, '9', NULL, NULL, '22', NULL, NULL, 2, 2, NULL, NULL),
(3, '-', NULL, 'project', NULL, NULL, 'Guga Dabrundashvili', NULL, NULL, NULL, NULL, 1, 4, NULL, NULL),
(4, '-', NULL, 'Project', NULL, NULL, 'Guga Dabrundashvili', NULL, NULL, NULL, NULL, 2, 4, NULL, NULL),
(5, 'Spider', NULL, '6b', '6b+', '6', 'Guga Dabrundashvili', NULL, '12', NULL, NULL, 1, 1, NULL, NULL),
(6, 'Spider', NULL, '7b', NULL, '15', 'Guga Dabrundashvili', NULL, '40', NULL, NULL, 2, 1, NULL, NULL),
(7, 'Spider', NULL, '7b', NULL, '15', 'Guga Dabrundashvili', NULL, '40', NULL, NULL, 3, 1, NULL, NULL),
(8, '-', NULL, 'Project', NULL, '9', 'Irakli Bidzishvili', '2019', '25', NULL, NULL, 1, 5, NULL, NULL),
(9, '-', NULL, 'Project', NULL, '9', 'Irakli Bidzishvili', '2019', '30', NULL, NULL, 1, 5, NULL, NULL),
(10, '-', NULL, 'Project', NULL, '7', 'Chuta', '2019', '20', NULL, NULL, 1, 6, NULL, NULL),
(11, '-', NULL, 'Project', NULL, '7', 'Chuta', '2019', '25', NULL, NULL, 1, 6, NULL, NULL),
(12, '-', NULL, 'Project', NULL, '10', 'Chuta', '2019', '20', NULL, NULL, 1, 7, NULL, NULL),
(13, '-', NULL, 'Project', NULL, '10', 'Chuta', '2019', '30', NULL, NULL, 2, 7, NULL, NULL),
(14, '-', NULL, 'Project', NULL, '10', 'Nika Shanava', '2019', '20', NULL, NULL, 1, 8, NULL, NULL),
(15, '-', NULL, 'Project', NULL, '10', 'Nika Sahnava', '2019', '25', NULL, NULL, 2, 8, NULL, NULL),
(16, '-', NULL, 'Project', NULL, '9', 'Irakli bidzishvili', '2019', '20', NULL, NULL, 1, 9, NULL, NULL),
(17, '-', NULL, 'Project', NULL, '9', 'Irakli Bidzishvili', '2019', '25', NULL, NULL, 2, 9, NULL, NULL),
(18, 'Multu P.', NULL, '5c', '5c+', NULL, 'Guga Dabrundashvili', NULL, '25', NULL, NULL, 3, 2, NULL, NULL),
(19, 'Multu P.', NULL, '5b', '5b+', NULL, 'Guga Dabrundashvili', NULL, '25', NULL, NULL, 4, 2, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `confirm` int DEFAULT NULL,
  `payment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shiping` int DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'User', 'manage-users', NULL, NULL);

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
  `sale_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `products` (`id`, `url_title`, `published`, `discount`, `material`, `mead_in_georgia`, `sale_type`, `category_id`, `us_product_id`, `ka_product_id`, `ru_product_id`, `created_at`, `updated_at`) VALUES
(1, 'handboard', 1, NULL, 'Wood', NULL, NULL, 2, 201, 1, 101, '2021-03-02 13:05:28', '2022-05-07 10:00:52'),
(2, 'mono_hengboard', 1, NULL, 'Wood', NULL, NULL, 2, 202, 2, 102, '2021-03-04 12:58:51', '2022-05-07 10:01:06'),
(3, 'climbing_power_pistons', 1, NULL, 'Wood', NULL, NULL, 2, 203, 3, 103, '2021-03-04 13:03:30', '2022-05-07 10:01:22'),
(4, 'climbing_brush', 1, NULL, 'Plastic', NULL, NULL, 2, 204, 4, 104, '2021-03-04 13:07:14', '2022-05-07 10:01:36'),
(5, 'liquid_chalk_for_climbing', 1, NULL, NULL, NULL, NULL, 2, 205, 5, 105, '2021-03-04 13:11:24', '2022-05-29 10:37:44'),
(6, 'home_training_zone', 1, NULL, 'Wood', NULL, NULL, 2, 206, 6, 106, '2021-03-20 01:04:41', '2021-03-20 01:12:23'),
(7, 'hold_1', 1, NULL, 'Plastic', NULL, NULL, 2, 207, 7, 107, '2021-05-07 10:48:43', '2021-05-15 05:44:16'),
(8, 'hold_2', 1, NULL, 'Plastic', NULL, NULL, 2, 208, 8, 108, '2021-05-07 10:52:56', '2021-05-15 05:43:48'),
(9, 'hold_3', 1, NULL, NULL, NULL, NULL, 2, 209, 9, 109, '2021-05-19 11:38:24', '2021-05-19 11:56:40'),
(10, 'hold_4', 1, NULL, NULL, NULL, NULL, 2, 210, 10, 110, '2021-05-19 11:42:11', '2021-05-19 12:12:14'),
(11, 'hold_5', 1, NULL, NULL, NULL, NULL, 2, 211, 11, 111, '2021-05-19 11:44:19', '2021-05-19 12:12:26'),
(12, 'hold_6', 1, NULL, NULL, NULL, NULL, 2, 212, 12, 112, '2021-05-19 11:47:15', '2021-05-19 12:12:37'),
(13, 'hold_7', 1, NULL, NULL, NULL, NULL, 2, 213, 13, 113, '2021-05-19 11:50:19', '2021-05-19 12:13:02'),
(14, 'holds_kit_1', 0, NULL, NULL, NULL, NULL, 2, 214, 14, 114, '2021-05-19 11:52:39', '2021-12-15 13:03:25'),
(15, 'hold_8', 1, NULL, NULL, NULL, NULL, 2, 215, 15, 115, '2021-05-19 12:06:29', '2021-05-19 12:13:23'),
(16, 'hold_9', 1, NULL, NULL, NULL, NULL, 2, 216, 16, 116, '2021-05-19 12:09:14', '2021-05-19 12:13:33'),
(17, 'hold_10', 1, NULL, NULL, NULL, NULL, 2, 217, 17, 117, '2021-05-26 02:35:52', '2021-05-26 02:38:51'),
(18, 'hold_11', 1, NULL, 'Plastic', NULL, NULL, 2, 218, 18, 118, '2021-08-25 21:20:20', '2021-08-25 21:22:56'),
(19, 'hold_12', 1, NULL, 'Plastic', NULL, NULL, 2, 219, 19, 119, '2021-08-25 21:23:03', '2021-08-25 21:25:23'),
(20, 'hold_13', 1, NULL, 'Plastic', NULL, NULL, 2, 220, 20, 120, '2021-08-25 21:25:37', '2021-08-25 21:27:04'),
(21, 'holds_kit_2', 0, NULL, 'Plastic', NULL, NULL, 2, 221, 21, 121, '2021-08-25 21:27:10', '2021-12-15 13:03:09'),
(22, 'hold_14', 1, NULL, 'Plastic', NULL, NULL, 2, 222, 22, 122, '2021-08-25 21:29:26', '2021-08-25 21:31:19'),
(23, 'climbing_holds_bolt_4cm', 1, NULL, 'Metal', NULL, NULL, 2, 223, 23, 123, '2021-08-25 21:31:39', '2021-08-26 12:00:59'),
(24, 'climbing_hold_bolt_8cm', 1, NULL, 'Metal', NULL, NULL, 2, 224, 24, 124, '2021-08-26 07:19:14', '2021-08-26 07:22:21'),
(25, 'hold_15', 1, NULL, 'Plastic', NULL, NULL, 2, 225, 25, 125, '2021-08-26 07:27:18', '2021-08-26 07:30:29'),
(26, 'hold_16', 1, NULL, 'Plastic', NULL, NULL, 2, 226, 26, 126, '2021-08-26 07:30:50', '2021-08-26 07:33:46'),
(27, 'hold_17', 1, NULL, 'Plastic', NULL, NULL, 2, 227, 27, 127, '2021-08-26 07:37:50', '2021-08-26 07:39:16'),
(28, 'tnuts', 1, NULL, 'Metal', NULL, NULL, 2, 228, 28, 128, '2021-08-26 07:41:06', '2021-08-26 07:43:44'),
(30, 'hold_18', 1, NULL, 'Plastic', NULL, NULL, 2, 230, 30, 130, '2021-12-15 13:04:05', '2021-12-15 13:15:03'),
(31, 'hold_19', 1, NULL, 'Plastic', NULL, NULL, 2, 231, 31, 131, '2021-12-15 13:15:11', '2021-12-15 13:20:34'),
(32, 'hold_20', 1, NULL, 'Plastic', NULL, NULL, 2, 232, 32, 132, '2021-12-15 13:20:52', '2021-12-15 13:48:25'),
(33, 'hold_21', 1, NULL, 'Plastic', NULL, NULL, 2, 233, 33, 133, '2021-12-15 13:29:54', '2021-12-15 13:31:29'),
(34, 'holds_set_1', 1, NULL, NULL, NULL, NULL, 2, 234, 34, 134, '2021-12-15 13:31:53', '2022-05-29 10:37:58'),
(35, 'holds_set_2', 1, NULL, NULL, NULL, NULL, 2, 235, 35, 135, '2021-12-15 13:35:38', '2022-05-29 10:38:16'),
(36, 'holds_set_3', 1, NULL, 'Plastic', NULL, NULL, 2, 236, 36, 136, '2021-12-16 12:39:23', '2022-05-29 10:38:32'),
(37, 'hold_22', 1, NULL, 'Plastic', NULL, NULL, 2, 237, 37, 137, '2021-12-16 12:41:51', '2021-12-16 12:43:57'),
(38, 'hold_23', 1, NULL, 'Plastic', NULL, NULL, 2, 238, 38, 138, '2021-12-16 12:44:49', '2021-12-16 12:46:36'),
(39, 'hold_24', 1, NULL, NULL, NULL, NULL, 2, 239, 39, 139, '2021-12-16 12:46:44', '2021-12-16 12:50:30'),
(40, 'micro_holds_1', 1, NULL, 'Plastic', NULL, NULL, 2, 240, 40, 140, '2021-12-16 12:52:41', '2021-12-16 12:54:49'),
(41, 'chalk_bag', 1, NULL, NULL, NULL, NULL, 2, 241, 41, 141, '2022-02-07 13:09:14', '2022-02-07 13:13:28'),
(42, 'climbing_rope_bag', 1, NULL, NULL, NULL, NULL, 2, 242, 42, 142, '2022-02-07 13:13:35', '2022-02-07 13:18:45'),
(43, 'handmade_hats', 1, NULL, NULL, NULL, NULL, 2, 243, 43, 143, '2022-02-07 13:34:23', '2022-02-07 13:40:30'),
(44, 'home_exercise_stand', 1, NULL, 'metal + wood', NULL, NULL, 2, 244, 44, 144, '2022-03-01 12:33:31', '2022-03-01 12:47:26'),
(45, 'climbing_volumes', 1, NULL, 'Wood', NULL, NULL, 2, 245, 45, 145, '2022-06-16 22:47:58', '2022-06-16 22:55:57');

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
(1, 'Climbing trainers', 'Скалолазные тренажеры', 'საცოცი ტრენაჟორები', '2021-03-02 16:53:46', '2021-03-02 16:53:46'),
(2, 'Small climbing holds', 'Маленькие зацепки', 'პატარა საჭიდები', '2021-03-02 16:54:37', '2021-05-19 15:33:24'),
(3, 'Climbing gear', 'Альпинистское снаряжение', 'აღჭურვილობა ცოცვისთვის', '2021-03-02 16:55:28', '2021-03-02 16:55:28'),
(4, 'Pants', 'Брюки', 'შარვალი', '2021-03-02 16:58:09', '2021-03-02 16:58:09'),
(5, 'T-shirt', 'Футболка', 'მაისური', '2021-03-02 16:59:29', '2021-03-02 16:59:29'),
(6, 'Climbing accessories', 'Аксессуары для скалолазания', 'ცოცვაის აქსესუარები', '2021-03-02 17:00:36', '2021-03-02 17:00:36'),
(7, 'Climbing volumes', 'Рельеф для скалолазания', 'რელიეფი ცოცვისთვის', '2021-03-02 17:02:44', '2021-03-02 17:02:44'),
(8, 'Big climbing holds', 'Большие зацепки', 'დიდი საჭიდები', '2021-05-19 15:33:14', '2021-05-19 15:33:14'),
(9, 'Climbing holds with screws', 'Скалолазные зацепки с шурупам', 'შურუპიანი საჭიდები', '2021-05-19 15:36:09', '2021-05-19 15:36:09'),
(10, 'Climbing holds set', 'Комплект зацепок для скалолазания', 'საჭიდების კომპლექტი', '2021-05-19 15:38:04', '2021-12-15 17:03:45'),
(11, 'Climbing board parts', 'части скалодрома', 'საცოცი კედლის ნაწილები', '2021-08-26 11:46:04', '2021-08-26 11:46:04'),
(12, 'Climbing shoes', 'Скалолазная обувь', 'საცოცი ფეხსაცმელი', '2021-11-10 15:44:42', '2021-11-10 15:44:42');

-- --------------------------------------------------------

--
-- Структура таблицы `product_comments`
--

CREATE TABLE `product_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `product_options`
--

CREATE TABLE `product_options` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `map` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'User', 'Users', 'Users', NULL, NULL),
(2, 'Manager', 'Global content manager', 'Global content manager', NULL, NULL),
(3, 'ka_manager', 'Georgian Content mnager', 'Georgian Content mnager', NULL, NULL),
(4, 'ru_mannager', 'Russian content mennager', 'Russian content mennager', NULL, NULL),
(5, 'us_manager', 'English content manager', 'English content manager', NULL, NULL),
(6, 'seller', 'Product Seller', 'Product Seller', NULL, NULL),
(7, 'admin', 'Site Administrator', 'Site Administrator', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_ascent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anchor_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stars` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num` int DEFAULT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `routes`
--

INSERT INTO `routes` (`id`, `name`, `text`, `grade`, `or_grade`, `bolts`, `author`, `creation_data`, `height`, `first_ascent`, `anchor_type`, `category`, `stars`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(1, 'The Bug', NULL, '6a', NULL, '6', 'Temo Samsonadze', NULL, '14', NULL, '0', 'sport climbing', NULL, 1, 1, '2019-03-28 14:26:44', '2020-05-25 02:07:36'),
(2, 'Chris.K', NULL, '6a', NULL, '11', 'Chris', NULL, '17', NULL, '0', 'sport climbing', NULL, 2, 1, '2019-03-28 15:38:10', '2020-05-25 02:07:36'),
(4, 'Samson', '<p>test route description</p>', '5b', NULL, '7', 'Temo samsonadze', NULL, '15', NULL, '0', 'sport climbing', NULL, 3, 1, '2019-03-30 04:32:45', '2020-05-25 02:07:36'),
(5, 'Talalala', NULL, '5b', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 5, 1, '2019-03-30 04:35:33', '2020-05-25 02:07:36'),
(6, 'X. Z.', NULL, '6a+', NULL, '8', 'Shako Mikava', NULL, '17', NULL, '2', 'sport climbing', NULL, 6, 1, '2019-03-30 04:40:24', '2020-05-25 02:07:36'),
(7, 'S.k.Lucky', NULL, '5a', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '1', 'sport climbing', NULL, 4, 1, '2019-03-30 04:42:25', '2020-05-25 02:07:36'),
(8, 'Tsie', NULL, '6a+', NULL, '9', 'Shako Mikava', NULL, '17', NULL, '1', 'sport climbing', NULL, 7, 1, '2019-03-30 04:44:20', '2020-05-25 02:07:36'),
(9, 'Yin & Yang', NULL, '6b', NULL, '7', 'Shako Mikava', NULL, '17', NULL, '1', 'sport climbing', NULL, 8, 1, '2019-03-30 06:39:29', '2020-05-25 02:07:36'),
(10, 'Ioio', NULL, '5b', NULL, '4', 'Guga Dabrundashcili', NULL, '12', NULL, '2', 'sport climbing', NULL, 2, 5, '2019-04-01 18:12:40', '2021-05-09 06:51:01'),
(11, 'Shana', NULL, '6a', NULL, '6', 'Guga Dabrundashcili', NULL, '18', NULL, '2', 'sport climbing', NULL, 3, 5, '2019-04-01 18:14:23', '2021-05-09 06:51:01'),
(12, 'Aladin', NULL, '6a+', NULL, '6', 'Temo samsonadze', NULL, '18', NULL, '2', 'sport climbing', NULL, 4, 5, '2019-04-01 18:17:05', '2021-05-09 06:51:01'),
(13, '99 Problem', NULL, '6b+', NULL, '7', 'Irakli Bidzeishvili', NULL, '20', NULL, '2', 'sport climbing', NULL, 5, 5, '2019-04-01 18:18:06', '2021-05-09 06:51:01'),
(14, 'Ebola', NULL, '6c', NULL, '6', 'Irakli Bidzishvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 6, 5, '2019-04-01 18:18:40', '2021-05-09 06:51:01'),
(15, 'GMGA', NULL, '7c', NULL, '5', 'Guga Dabrundashvili', NULL, '10', 'Temo Samsonadze', '2', 'sport climbing', NULL, 10, 5, '2019-04-01 18:19:31', '2022-09-16 23:08:53'),
(16, 'Titan', NULL, '7b', NULL, '5', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 11, 5, '2019-04-01 18:20:19', '2021-05-09 06:51:01'),
(17, 'Chinka', NULL, '6b+', NULL, '5', 'Nika Sanava', NULL, '10', NULL, '2', 'sport climbing', NULL, 12, 5, '2019-04-01 18:22:58', '2021-05-09 06:51:01'),
(18, 'Caravan', NULL, '6c', NULL, '4', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 13, 5, '2019-04-01 18:24:18', '2021-05-09 06:51:01'),
(19, 'Multy Way', NULL, '6c+', NULL, '5', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 14, 5, '2019-04-01 18:25:08', '2021-05-09 06:51:01'),
(20, 'Lady in Black', NULL, '7c', NULL, '5', 'Guga Dabrundashvili', NULL, '12', 'Salome Meshveliani', '2', 'sport climbing', NULL, 15, 5, '2019-04-01 18:26:58', '2021-05-09 06:51:01'),
(21, 'S. M.', NULL, '6b+', NULL, '5', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 16, 5, '2019-04-01 18:27:49', '2021-05-09 06:51:01'),
(22, 'Try Me', NULL, '6a+', NULL, '5', 'Nika Sanava', NULL, '12', NULL, '2', 'sport climbing', NULL, 17, 5, '2019-04-01 18:28:44', '2022-09-14 18:32:27'),
(23, 'Zgushonka', NULL, '5c+', NULL, '6', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 18, 5, '2019-04-01 18:29:43', '2021-05-09 06:51:01'),
(24, 'Patagonia', NULL, '5c', NULL, '7', 'Nika Sanava', NULL, '16', NULL, '2', 'sport climbing', NULL, 19, 5, '2019-04-01 18:32:17', '2021-05-09 06:51:01'),
(25, 'Ground', NULL, '5c', NULL, '8', 'Guga Dabrundashvili', NULL, '20', NULL, '2', 'sport climbing', NULL, 20, 5, '2019-04-01 18:33:55', '2022-09-14 18:32:04'),
(26, 'Mushrooms', NULL, '6a', NULL, '5', 'Guga Dabrundashcili', NULL, '12', NULL, '2', 'sport climbing', NULL, 1, 6, '2019-04-01 18:35:12', '2020-05-25 02:07:36'),
(27, 'Task', NULL, '6b', NULL, '6', 'Nika Sanava', NULL, '15', NULL, '2', 'sport climbing', NULL, 2, 6, '2019-04-01 18:36:37', '2020-05-25 02:07:36'),
(28, 'Strax', NULL, '6b+', NULL, '6', 'Irakli Bidzeishvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 3, 6, '2019-04-01 18:38:48', '2020-05-25 02:07:36'),
(29, 'Carela Supi', NULL, '6a', NULL, '7', 'Irakli Bidzeishvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 4, 6, '2019-04-01 18:40:21', '2020-05-25 02:07:36'),
(30, 'Salewa', NULL, '6a+', NULL, '6', 'Guga Dabrundashcili', NULL, '15', NULL, '2', 'sport climbing', NULL, 5, 6, '2019-04-01 18:41:04', '2020-05-25 02:07:36'),
(31, 'ACDC', NULL, '6b', NULL, '8', 'Guga Dabrundashvili', NULL, '17', NULL, '2', 'sport climbing', NULL, 9, 1, '2019-04-02 05:03:40', '2020-05-25 02:07:36'),
(32, 'Opera D', NULL, '7a+', NULL, '11', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 11, 1, '2019-04-02 05:04:36', '2021-06-05 23:11:07'),
(33, 'Giliotina', NULL, '6c', NULL, '10', 'Guga Dabrundashvili', NULL, '23', NULL, '2', 'sport climbing', NULL, 12, 1, '2019-04-02 05:06:11', '2021-06-05 23:11:07'),
(34, 'Geoland', NULL, '6b+', NULL, '14', 'Guga Dabrundashvili', NULL, '27', NULL, '2', 'sport climbing', NULL, 13, 1, '2019-04-02 05:07:03', '2021-06-05 23:11:07'),
(35, 'Mummy', NULL, '6c', NULL, '11', 'Guga Dabrundashvili', NULL, '23', NULL, '2', 'sport climbing', NULL, 14, 1, '2019-04-02 05:07:47', '2021-06-05 23:11:07'),
(36, 'John Rambo', NULL, '6b', NULL, '9', 'Guga Dabrundashvili', NULL, '18', NULL, '2', 'sport climbing', NULL, 1, 2, '2019-04-02 05:10:30', '2020-05-25 02:07:36'),
(37, 'Usakhelo', NULL, '6a+', NULL, '9', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 2, 2, '2019-04-02 05:12:00', '2020-05-25 02:07:36'),
(38, 'Sado Mazo P', NULL, '7a+', NULL, '11', 'Guga Dabrundashvili', NULL, '25', NULL, '1', 'sport climbing', NULL, 3, 2, '2019-04-02 05:14:54', '2020-05-25 02:07:36'),
(39, 'KoKoKo', '<p>Long distance between bolts. Need good belayer.&nbsp;</p>', '7a', NULL, '11', 'Guga Dabrundashvili', NULL, '25', NULL, '1', 'sport climbing', NULL, 4, 2, '2019-04-02 05:16:11', '2020-05-25 02:07:36'),
(40, 'Xamichok', NULL, '6a+', NULL, '14', 'Guga Dabrundashvili', NULL, '25', NULL, '1', 'sport climbing', NULL, 7, 2, '2019-04-02 05:17:27', '2022-04-01 00:05:58'),
(41, 'Hole', NULL, '7a', NULL, '7', 'German friends', NULL, '10', NULL, '2', 'sport climbing', NULL, 1, 3, '2019-04-02 05:19:16', '2020-05-25 02:07:36'),
(42, 'Nervi', NULL, '7b+', 'Project', '8', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 2, 3, '2019-04-02 05:19:49', '2022-04-02 04:53:31'),
(43, 'Ara Vchamot Puri', NULL, '6c', NULL, '10', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 3, 3, '2019-04-02 05:20:46', '2020-05-25 02:07:36'),
(44, 'Surprize', NULL, '6b', NULL, '9', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 4, 3, '2019-04-02 05:22:12', '2020-05-25 02:07:36'),
(45, 'Jibe', NULL, '6b+', NULL, '9', 'Guga Dabrundashvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 5, 3, '2019-04-02 05:22:45', '2020-05-25 02:07:36'),
(46, 'Puza', NULL, '7a+', NULL, '9', 'Guga Dabrundashvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 6, 3, '2019-04-02 05:23:43', '2020-05-25 02:07:36'),
(47, 'Butterfly', NULL, '6b+', NULL, '11', 'Guga Dabrundashvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 7, 3, '2019-04-02 05:24:41', '2020-05-25 02:07:36'),
(48, 'Ugachaga', NULL, '7b', NULL, '8', 'Guga Dabrundashvili', NULL, '13', NULL, '2', 'sport climbing', NULL, 8, 3, '2019-04-02 05:25:36', '2020-05-25 02:07:36'),
(49, 'Snake', NULL, '7a+', NULL, '7', 'Guga Dabrundashvili', NULL, '13', NULL, '2', 'sport climbing', NULL, 9, 3, '2019-04-02 05:26:52', '2020-05-25 02:07:36'),
(50, 'Morieli', NULL, '6b+', NULL, '11', 'Guga Dabrundashvili', NULL, '16', NULL, '2', 'sport climbing', NULL, 10, 3, '2019-04-02 05:28:00', '2020-05-25 02:07:36'),
(51, 'Ravi Aba', NULL, '5a', NULL, '5', 'Temo Samsonadze', NULL, '10', NULL, '1', 'sport climbing', NULL, 4, 4, '2019-04-02 05:30:47', '2022-08-12 07:54:15'),
(52, 'Ravi', NULL, '5a', NULL, '5', 'Temo Samsonadze', NULL, '9', NULL, '1', 'sport climbing', NULL, 5, 4, '2019-04-02 05:31:32', '2022-08-12 07:54:08'),
(53, 'Mini Zgarbi', NULL, '5c', NULL, '6', 'Temo Samsonadze', NULL, '9', NULL, '2', 'sport climbing', NULL, 6, 4, '2019-04-02 05:32:14', '2022-08-12 07:54:01'),
(54, 'Ger', NULL, '6a+', NULL, '6', 'Temo Samsonadze', NULL, '9', NULL, '1', 'sport climbing', NULL, 7, 4, '2019-04-02 05:33:21', '2020-05-25 02:07:36'),
(55, 'Scar Face', NULL, '6b', NULL, '5', 'Temo Samsonadze', NULL, '9', NULL, '1', 'sport climbing', NULL, 8, 4, '2019-04-02 05:34:16', '2020-05-25 02:07:36'),
(56, 'Drakula Utold', NULL, '6b', NULL, '5', 'Guga Dabrundashvili', NULL, '10', NULL, '1', 'sport climbing', NULL, 9, 4, '2019-04-02 05:39:35', '2020-05-25 02:07:36'),
(57, 'ID', NULL, '7a+', NULL, '7', 'German friends', NULL, '10', NULL, '0', 'sport climbing', NULL, 11, 4, '2019-04-02 05:40:34', '2020-05-25 02:07:36'),
(58, 'Hame Ha', NULL, '7a', NULL, '6', 'Shako Mikava', NULL, '12', NULL, '2', 'sport climbing', NULL, 12, 4, '2019-04-02 05:41:16', '2020-05-25 02:07:36'),
(59, 'Cunami', NULL, '6c+', NULL, '7', 'Guga Dabrundashvili', NULL, '12', NULL, '1', 'sport climbing', NULL, 14, 4, '2019-04-02 05:42:24', '2020-05-25 02:07:36'),
(60, 'Dano', NULL, '8b+', NULL, '7', 'Guga Dabrundashvili', NULL, '12', 'Hans Rabetzki', '2', 'sport climbing', NULL, 2, 7, '2019-04-02 05:46:43', '2020-05-25 02:07:36'),
(61, 'Grama', NULL, '8a+', NULL, '7', 'Guga Dabrundashvili', NULL, '18', NULL, '2', 'sport climbing', NULL, 3, 7, '2019-04-02 05:48:34', '2020-05-25 02:07:36'),
(62, 'Sabbotage', NULL, '8a', NULL, '6', 'Guga Dabrundashvili', NULL, '17', NULL, '2', 'sport climbing', NULL, 4, 7, '2019-04-02 05:49:57', '2020-05-25 02:07:36'),
(63, 'Luda', '<p>One more route left from &quot;Dano&quot; in the corner</p>', '6a', NULL, '6', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 1, 7, '2019-04-02 05:50:47', '2020-05-25 02:07:36'),
(64, 'Ozzy', NULL, '6b', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 1, 44, '2019-04-02 05:51:49', '2020-05-25 02:07:36'),
(65, 'Hspitali', NULL, '6b+', NULL, '6', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 3, 44, '2019-04-02 05:52:33', '2020-05-25 02:07:36'),
(66, 'Palo', NULL, '6a+', NULL, '7', 'German friends', NULL, '15', NULL, '2', 'sport climbing', NULL, 2, 44, '2019-04-02 05:53:08', '2020-05-25 02:07:36'),
(67, 'Solo', NULL, '6a', NULL, '7', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 5, 44, '2019-04-02 05:54:02', '2020-05-25 02:07:36'),
(68, 'Buia', NULL, '6b+', NULL, '8', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 6, 44, '2019-04-02 05:54:43', '2020-05-25 02:07:36'),
(69, 'Buda', NULL, '6b', NULL, '7', 'Temo Samsonadze', NULL, '12', NULL, '2', 'sport climbing', NULL, 7, 44, '2019-04-02 05:56:42', '2020-05-25 02:07:36'),
(70, 'Pandora', NULL, '6a+', NULL, '7', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 8, 44, '2019-04-02 05:57:36', '2020-05-25 02:07:36'),
(71, 'Babua', NULL, '5a', NULL, '4', 'Guga Dabrundashvili', '2019', '10', NULL, '2', 'sport climbing', NULL, 3, 8, '2019-04-02 06:03:41', '2021-11-16 08:33:21'),
(72, 'Rioni', NULL, '5a', NULL, '7', 'Guga Dabrundashvili', '2019', '15', NULL, '2', 'sport climbing', NULL, 4, 8, '2019-04-02 06:06:50', '2021-11-16 08:34:49'),
(73, 'Jibe', NULL, '6a', NULL, NULL, 'Guga Dabrundashvili', '2019', '15', NULL, '2', 'sport climbing', NULL, 5, 8, '2019-04-02 06:09:29', '2021-11-16 08:36:16'),
(74, 'Remboi', NULL, '6b', NULL, '8', 'Guga Dabrundashvili', '2019', '15', NULL, '2', 'sport climbing', NULL, 6, 8, '2019-04-02 06:10:08', '2021-11-16 08:37:21'),
(75, 'Daugava', NULL, '6a', NULL, NULL, 'Guga Dabrundashvili', '2019', '13', NULL, '0', 'sport climbing', NULL, 7, 8, '2019-04-02 06:11:34', '2021-11-16 08:43:00'),
(76, 'Petzl', NULL, '6c', NULL, '13', 'Guga Dabrundashvili', '2019', '26', NULL, '2', 'sport climbing', NULL, 12, 8, '2019-04-02 06:12:19', '2021-11-16 08:45:08'),
(77, 'Taro', NULL, '6b+', NULL, '12', 'Guga Dabrundashvili', '2019', '25', NULL, '2', 'sport climbing', NULL, 11, 8, '2019-04-02 06:12:54', '2021-11-16 08:42:18'),
(78, 'Mogzauri', NULL, '8a+', NULL, '11', 'Guga Dabrundashcili', NULL, '20', NULL, '2', 'sport climbing', NULL, 1, 9, '2019-04-02 17:54:13', '2020-05-25 02:07:36'),
(79, 'Cerber', NULL, '6b+', NULL, '10', 'Guga Dabrundashcili', NULL, '22', NULL, '2', 'sport climbing', NULL, 2, 9, '2019-04-02 17:55:38', '2020-05-25 02:07:36'),
(80, 'Hades', NULL, '6b', NULL, '11', 'Guga Dabrundashcili', NULL, '25', NULL, '2', 'sport climbing', NULL, 3, 9, '2019-04-02 17:57:17', '2020-05-25 02:07:36'),
(81, 'Corner', NULL, '6a+', NULL, '7', 'Guga Dabrundashcili', NULL, '16', NULL, '2', 'sport climbing', NULL, 4, 9, '2019-04-02 17:57:57', '2020-05-25 02:07:36'),
(82, 'Chimny', NULL, '6a+', NULL, '7', 'Guga Dabrundashcili', NULL, '16', NULL, '2', 'sport climbing', NULL, 5, 9, '2019-04-02 18:00:32', '2020-05-25 02:07:36'),
(83, 'Heracles', NULL, '6c+', NULL, '8', 'Guga Dabrundashcili', NULL, '16', NULL, '2', 'sport climbing', NULL, 6, 9, '2019-04-02 18:01:35', '2020-05-25 02:07:36'),
(84, 'Zeus', NULL, '6c', NULL, '7', 'Guga Dabrundashcili', NULL, '15', NULL, '2', 'sport climbing', NULL, 7, 9, '2019-04-02 18:03:16', '2020-05-25 02:07:36'),
(85, 'Nif-Nif', NULL, '5c', NULL, '7', 'Guga Dabrundashcili', NULL, '14', NULL, '2', 'sport climbing', NULL, 8, 9, '2019-04-02 18:04:24', '2020-05-25 02:07:36'),
(86, 'Nuf-Nuf', NULL, '5c', NULL, '5', 'Guga Dabrundashcili', NULL, '10', NULL, '2', 'sport climbing', NULL, 9, 9, '2019-04-02 18:05:26', '2020-05-25 02:07:36'),
(87, 'Naf-Naf', NULL, '5c+', NULL, '7', 'Guga Dabrundashcili', NULL, '10', NULL, '2', 'sport climbing', NULL, 10, 9, '2019-04-02 18:08:22', '2020-05-25 02:07:36'),
(88, 'Puza', NULL, '6c', NULL, '24', 'Guga Dabrundashcili, Anna Dularidze', NULL, '12', NULL, '2', 'sport climbing', NULL, 11, 9, '2019-04-02 18:09:43', '2020-05-25 02:07:36'),
(89, 'Kiwi', NULL, '6a', NULL, '11', 'Guga Dabrundashcili, Maryam Kitsmarishvili', NULL, '24', NULL, '2', 'sport climbing', NULL, 12, 9, '2019-04-02 18:10:53', '2020-05-25 02:07:36'),
(90, 'Hera', NULL, '6b+', NULL, '12', 'Guga Dabrundashcili', NULL, '24', NULL, '2', 'sport climbing', NULL, 13, 9, '2019-04-02 18:11:31', '2020-05-25 02:07:36'),
(91, 'Mokle', NULL, '6a+', NULL, '12', 'Guga Dabrundashcili', NULL, '30', NULL, '2', 'sport climbing', NULL, 1, 10, '2019-04-02 18:13:39', '2020-05-25 02:07:36'),
(92, 'Simba', NULL, '6b', NULL, '13', 'Guga Dabrundashcili, Arcil Badriashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 2, 10, '2019-04-02 18:15:05', '2020-05-25 02:07:36'),
(93, 'Jangle', NULL, '6a', NULL, '7', 'Guga Dabrundashcili', NULL, '22', NULL, '2', 'sport climbing', NULL, 3, 10, '2019-04-02 18:35:48', '2020-05-25 02:07:36'),
(94, 'Pades', NULL, '6b', NULL, '9', 'Guga Dabrundashcili, Maryam Kitsmarishvili', NULL, '20', NULL, '2', 'sport climbing', NULL, 4, 10, '2019-04-02 18:36:24', '2020-05-25 02:07:36'),
(95, 'M.M.K.', NULL, '6a+', NULL, '8', 'Guga Dabrundashcili', NULL, '20', NULL, '2', 'sport climbing', NULL, 5, 10, '2019-04-02 18:37:05', '2020-05-25 02:07:36'),
(96, 'Bomba', NULL, '6a+', NULL, '10', 'Jahannes', NULL, '22', NULL, '2', 'sport climbing', NULL, 6, 10, '2019-04-02 18:39:30', '2020-05-25 02:07:36'),
(97, 'Bomba', NULL, '6a+', NULL, '10', 'Jahannes', NULL, '22', NULL, '2', 'sport climbing', NULL, 7, 10, '2019-04-02 18:39:35', '2020-05-25 02:07:36'),
(98, 'Lina', NULL, '5c+', NULL, '10', 'Guga Dabrundashcili', NULL, '22', NULL, '2', 'sport climbing', NULL, 8, 10, '2019-04-02 18:40:23', '2020-05-25 02:07:36'),
(99, 'Montis Magia', NULL, '6a', NULL, '9', 'Guga Dabrundashcili', NULL, '22', NULL, '2', 'sport climbing', NULL, 9, 10, '2019-04-02 18:41:03', '2020-05-25 02:07:36'),
(100, 'Ant', NULL, '6b+', NULL, '8', 'Guga Dabrundashcili', NULL, '20', NULL, '2', 'sport climbing', NULL, 1, 11, '2019-04-02 18:41:37', '2020-05-25 02:07:36'),
(101, 'Pomegramt', NULL, '6b', NULL, '7', 'Misho Nadiradze', NULL, '20', NULL, '2', 'sport climbing', NULL, 2, 11, '2019-04-02 18:42:26', '2020-05-25 02:07:36'),
(102, 'Bolo', NULL, '6b', NULL, '8', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 26, 12, '2019-04-08 02:18:05', '2020-05-25 02:07:36'),
(103, 'Tree', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'sport climbing', NULL, 1, 13, '2019-04-08 02:48:38', '2020-05-25 02:07:36'),
(110, 'Spider', NULL, '6a', NULL, '6', 'Guga Dabrundashvili', NULL, '12', NULL, '0', 'sport climbing', NULL, 30, 14, '2019-04-14 02:22:49', '2020-05-25 02:07:36'),
(114, 'No start No finish', NULL, '6c', NULL, '10', 'Guga Dabrundashvili', NULL, '28', NULL, '2', 'sport climbing', NULL, 1, 14, '2019-05-03 03:07:00', '2020-05-25 02:07:36'),
(115, 'Omega', NULL, '6c+', NULL, '11', 'Guga Dabrundashvili', NULL, '28', NULL, '2', 'sport climbing', NULL, 2, 14, '2019-05-03 03:08:35', '2020-05-25 02:07:36'),
(116, 'Log in', NULL, '6b', NULL, '11', NULL, NULL, '25', NULL, '2', 'sport climbing', NULL, 3, 14, '2019-05-03 03:09:23', '2020-05-25 02:07:36'),
(117, 'Patato', NULL, '6a', NULL, '10', 'Guga Dabrundashvili', NULL, '27', NULL, '2', 'sport climbing', NULL, 4, 14, '2019-05-03 03:11:49', '2020-05-25 02:07:36'),
(118, 'Astra', NULL, '6a+', NULL, '10', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 5, 14, '2019-05-03 03:13:06', '2020-05-25 02:07:36'),
(119, 'Deutschland', NULL, '6c', NULL, '25', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 6, 14, '2019-05-03 03:15:27', '2020-05-25 02:07:36'),
(120, 'Sweet tea', NULL, '6a', NULL, '10', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 7, 14, '2019-05-03 03:16:23', '2020-05-25 02:07:36'),
(121, '69', NULL, '7a', NULL, '12', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 9, 14, '2019-05-03 03:17:06', '2020-05-25 02:07:36'),
(122, '5047', NULL, '6c', NULL, '8', NULL, NULL, '18', NULL, '2', 'sport climbing', NULL, 8, 14, '2019-05-03 03:17:46', '2020-05-25 02:07:36'),
(123, 'No name', NULL, '6b+', NULL, '8', NULL, NULL, '20', NULL, '2', 'sport climbing', NULL, 10, 14, '2019-05-03 03:18:25', '2020-05-25 02:07:36'),
(124, 'Rock n roll', NULL, '6b+', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 11, 14, '2019-05-03 03:19:33', '2020-05-25 02:07:36'),
(125, 'Dusty', NULL, '6b+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 12, 14, '2019-05-03 03:20:23', '2020-05-25 02:07:36'),
(126, 'Chire', NULL, '6a', NULL, '6', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 13, 14, '2019-05-03 03:22:13', '2020-05-25 02:07:36'),
(127, 'Tonga', NULL, '6b+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 14, 14, '2019-05-03 03:22:45', '2020-05-25 02:07:36'),
(128, 'Tonga', NULL, '6b+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 15, 14, '2019-05-03 03:24:14', '2020-05-25 02:07:36'),
(129, 'Nest', NULL, '6b+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 16, 14, '2019-05-03 03:25:06', '2020-05-25 02:07:36'),
(130, 'Fixe', NULL, '6b+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '2', 'sport climbing', NULL, 17, 14, '2019-05-03 03:29:23', '2020-05-25 02:07:36'),
(131, 'Arsha', NULL, '5b', NULL, '7', 'Guga Dabrundashvili', NULL, '20', NULL, '2', 'sport climbing', NULL, 18, 14, '2019-05-03 03:30:09', '2020-05-25 02:07:36'),
(132, 'Beast', NULL, '6a+', NULL, '10', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 19, 14, '2019-05-03 03:33:13', '2020-05-25 02:07:36'),
(133, 'Grey', NULL, '6a', NULL, '10', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 20, 14, '2019-05-03 03:34:15', '2020-05-25 02:07:36'),
(134, 'Ele', NULL, '5b', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 21, 14, '2019-05-03 03:34:47', '2020-05-25 02:07:36'),
(135, 'Red left', NULL, '5a', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 22, 14, '2019-05-03 03:35:46', '2020-05-25 02:07:36'),
(136, 'Red right', NULL, '5b', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 23, 14, '2019-05-03 03:36:29', '2020-05-25 02:07:36'),
(137, 'Cannon', NULL, '5b', NULL, '9', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 24, 14, '2019-05-03 03:37:20', '2020-05-25 02:07:36'),
(138, 'Multu. p', NULL, '6a+', NULL, '9', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 25, 14, '2019-05-03 03:38:03', '2020-05-25 02:07:36'),
(139, 'Top', NULL, '5b', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 26, 14, '2019-05-03 03:38:36', '2020-05-25 02:07:36'),
(140, 'Sika', NULL, '5c', NULL, '8', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 27, 14, '2019-05-03 03:39:47', '2020-05-25 02:07:36'),
(141, 'New', NULL, '6a', NULL, '9', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 28, 14, '2019-05-03 03:40:51', '2020-05-25 02:07:36'),
(142, 'Bolo', NULL, '5c', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 29, 14, '2019-05-03 03:41:19', '2020-05-25 02:07:36'),
(143, 'Snake', NULL, '6b', NULL, '11', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 5, 16, '2019-05-03 03:43:29', '2022-09-11 23:14:43'),
(144, 'Scorpion', NULL, '7a', NULL, '9', 'Guga Dabrundashvili', NULL, '17', NULL, '2', 'sport climbing', NULL, 7, 16, '2019-05-03 03:44:16', '2022-09-11 23:14:43'),
(145, 'Blizarrd', NULL, '6a', NULL, '7', 'Brat.m', NULL, '10', NULL, '2', 'sport climbing', NULL, 6, 18, '2019-05-03 03:54:42', '2021-08-03 07:41:55'),
(146, 'Blue wave', NULL, '6a+', NULL, '7', 'Vento', NULL, '10', NULL, '2', 'sport climbing', NULL, 5, 18, '2019-05-03 03:55:04', '2021-08-03 07:41:09'),
(147, 'Meduza', NULL, '6b', NULL, '8', 'Guga Dabrundasvili', '2021', '12', NULL, '2', 'sport climbing', NULL, 4, 18, '2019-05-03 03:55:31', '2021-08-03 07:44:42'),
(148, 'Chees', NULL, '5a', NULL, '10', 'Guga Dabrundashvili', NULL, '6', NULL, '0', 'sport climbing', NULL, 6, 17, '2019-05-03 03:56:31', '2022-06-27 10:08:05'),
(149, 'Barabulka', NULL, '6a', '6a+', '7', NULL, NULL, '11', NULL, '0', 'sport climbing', NULL, 4, 17, '2019-05-03 03:57:16', '2021-08-03 07:36:24'),
(150, 'Covid19', NULL, '6a', NULL, '9', 'Guga Dabrundashvili', NULL, '17', NULL, '2', 'sport climbing', NULL, 3, 18, '2019-05-03 03:57:39', '2021-08-03 07:44:42'),
(152, 'Diuba', NULL, '6a', NULL, '9', NULL, NULL, '18', NULL, '2', 'sport climbing', NULL, 3, 17, '2019-05-03 03:58:50', '2021-08-03 07:34:53'),
(153, 'Sergia', NULL, '6a', NULL, '7', 'Shako Mikava', NULL, '20', NULL, '0', 'sport climbing', NULL, 1, 19, '2019-05-03 04:15:54', '2020-05-25 02:07:36'),
(154, 'Boloki', NULL, '6a+', NULL, '8', 'Luka Lomashvili', NULL, '20', NULL, '0', 'sport climbing', NULL, 2, 19, '2019-05-03 04:17:13', '2020-05-25 02:07:36'),
(155, 'Nali', NULL, '5c+', NULL, '8', 'Temo Samsonadze', NULL, '22', NULL, '0', 'sport climbing', NULL, 3, 19, '2019-05-03 04:18:03', '2020-05-25 02:07:36'),
(156, 'Dady', NULL, '6a', NULL, '10', 'Guga Dabrundashvili', NULL, '22', NULL, '0', 'sport climbing', NULL, 4, 19, '2019-05-03 04:19:23', '2020-05-25 02:07:36'),
(157, 'Slopy', NULL, '6a+', NULL, '7', 'Guga Dabrundashvili', NULL, '15', NULL, '0', 'sport climbing', NULL, 5, 19, '2019-05-03 04:20:24', '2020-05-25 02:07:36'),
(158, 'Malou', NULL, '5c', NULL, '6', 'Guga Dabrundashvili', NULL, '15', NULL, '0', 'sport climbing', NULL, 6, 19, '2019-05-03 04:21:07', '2020-05-25 02:07:36'),
(159, '-', NULL, '6b+', NULL, '13', 'Luka Lomashvili', NULL, '35', NULL, '2', 'sport climbing', NULL, 7, 19, '2019-05-03 04:21:50', '2020-05-25 02:07:36'),
(160, '-', NULL, '6c', NULL, '13', 'Shako Mikava', NULL, '30', NULL, '0', 'sport climbing', NULL, 8, 19, '2019-05-03 04:22:33', '2020-05-25 02:07:36'),
(161, '-', NULL, '6a+', NULL, '8', 'Guga Dabrundashvili', NULL, '25', NULL, '0', 'sport climbing', NULL, 9, 19, '2019-05-03 04:23:33', '2020-05-25 02:07:36'),
(162, '-', NULL, '6a+', NULL, '8', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 10, 19, '2019-05-03 04:23:59', '2020-05-25 02:07:36'),
(163, '-', NULL, '6b', NULL, '12', 'Guga Dabrundashvili', NULL, '28', NULL, '0', 'sport climbing', NULL, 11, 19, '2019-05-03 04:24:49', '2020-05-25 02:07:36'),
(164, '-', NULL, '?', NULL, '8', 'Temo Samsonadze', NULL, '13', NULL, '0', 'sport climbing', NULL, 1, 20, '2019-05-03 04:25:20', '2021-05-09 07:18:27'),
(165, '-', NULL, '?', NULL, '6', 'Temo Samsonadze', NULL, '13', NULL, '0', 'sport climbing', NULL, 2, 20, '2019-05-03 04:26:07', '2021-05-09 07:18:02'),
(166, '-', NULL, '?', NULL, '8', 'Guga Dabrundashvili', NULL, '13', NULL, '0', 'sport climbing', NULL, 3, 20, '2019-05-03 04:27:36', '2021-05-09 07:17:36'),
(167, 'Demoni', NULL, '7b+', NULL, '8', 'Guga Dabrundashvili', NULL, '13', NULL, '0', 'sport climbing', NULL, 4, 20, '2019-05-03 04:28:09', '2021-05-09 07:17:23'),
(168, '-', NULL, '?', NULL, '11', 'Oto maisuradze', NULL, '15', NULL, '2', 'sport climbing', NULL, 1, 21, '2019-05-03 22:14:36', '2020-05-25 02:07:36'),
(169, '-', NULL, '?', NULL, '14', 'Oto Maisuradze', NULL, '20', NULL, '2', 'sport climbing', NULL, 2, 21, '2019-05-03 22:15:19', '2020-05-25 02:07:36'),
(170, '-', NULL, '6c', NULL, '7', 'Oto Maisuradze', NULL, '15', NULL, '2', 'sport climbing', NULL, 3, 21, '2019-05-03 22:16:21', '2020-05-25 02:07:36'),
(171, '-', NULL, '6a+', NULL, '12', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 4, 21, '2019-05-03 22:17:03', '2020-05-25 02:07:36'),
(172, '-', NULL, '5c', NULL, NULL, 'Archil Cincadze', NULL, '25', NULL, '2', 'sport climbing', NULL, 5, 21, '2019-05-03 22:17:37', '2020-05-25 02:07:36'),
(173, '-', NULL, '?', NULL, NULL, NULL, NULL, NULL, NULL, '2', 'sport climbing', NULL, 6, 21, '2019-05-03 22:18:07', '2020-05-25 02:07:36'),
(174, '-', NULL, '7a', NULL, '11', 'Guga Dabrundashvili', NULL, '30', NULL, '2', 'sport climbing', NULL, 1, 22, '2019-05-03 22:21:15', '2020-05-25 02:07:36'),
(175, '-', NULL, '6b+', NULL, '12', 'Ilia Berulava', NULL, '30', NULL, '2', 'sport climbing', NULL, 2, 22, '2019-05-03 22:21:53', '2020-05-25 02:07:36'),
(176, '-', NULL, '?', NULL, NULL, 'Guga Dabrundashvili', NULL, '30', NULL, '2', 'sport climbing', NULL, 3, 22, '2019-05-03 22:22:19', '2020-05-25 02:07:36'),
(177, '-', NULL, '?', NULL, NULL, 'Guga Dabrundashvili', NULL, '30', NULL, '2', 'sport climbing', NULL, 4, 22, '2019-05-03 22:22:40', '2020-05-25 02:07:36'),
(178, '-', NULL, '5c', NULL, '6', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 5, 22, '2019-05-03 22:23:27', '2020-05-25 02:07:36'),
(179, '-', NULL, '5c+', NULL, '7', 'Archil Cincadze', NULL, '15', NULL, '2', 'sport climbing', NULL, 6, 22, '2019-05-03 22:24:01', '2020-05-25 02:07:36'),
(180, 'Corner', NULL, 'V1', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 2, 13, '2019-05-03 22:52:47', '2020-05-25 02:07:36'),
(181, 'Tango', NULL, 'V4', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 3, 13, '2019-05-03 22:57:34', '2020-05-25 02:07:36'),
(182, 'Jump', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 4, 13, '2019-05-03 22:58:06', '2020-05-25 02:07:36'),
(183, 'Kibe', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 5, 13, '2019-05-03 22:58:36', '2020-05-25 02:07:36'),
(184, 'Slopy', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 6, 13, '2019-05-03 22:59:30', '2020-05-25 02:07:36'),
(185, 'Traverse', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 7, 13, '2019-05-03 23:00:25', '2020-05-25 02:07:36'),
(186, 'Roof', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 8, 13, '2019-05-03 23:01:07', '2020-05-25 02:07:36'),
(187, 'Corner Roof', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 9, 13, '2019-05-03 23:03:16', '2020-05-25 02:07:36'),
(188, 'Classic', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 10, 13, '2019-05-03 23:05:39', '2020-05-25 02:07:36'),
(189, 'Green', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 11, 13, '2019-05-03 23:08:09', '2020-05-25 02:07:36'),
(190, 'Glue', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 12, 13, '2019-05-03 23:08:40', '2020-05-25 02:07:36'),
(191, 'One Hand', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 13, 13, '2019-05-03 23:09:21', '2020-05-25 02:07:36'),
(192, 'Theangle', NULL, 'V2', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 14, 13, '2019-05-03 23:11:01', '2020-05-25 02:07:36'),
(193, 'Nose', NULL, 'V3', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 15, 13, '2019-05-03 23:11:33', '2020-05-25 02:07:36'),
(194, 'Lamzira', NULL, 'V4', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 16, 13, '2019-05-03 23:12:01', '2020-05-25 02:07:36'),
(195, 'Sisi', NULL, 'V4', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 17, 13, '2019-05-03 23:12:24', '2020-05-25 02:07:36'),
(196, 'No start No finish', NULL, '6b', NULL, '4', 'Guga Dabrundashvili', NULL, '8', NULL, '2', 'sport climbing', NULL, 1, 12, '2019-05-03 23:45:44', '2020-05-25 02:07:36'),
(197, '3 Stone', NULL, '6b', NULL, '4', 'Guga Dabrundashvili', NULL, '9', NULL, '2', 'sport climbing', NULL, 2, 12, '2019-05-03 23:46:32', '2020-05-25 02:07:36'),
(198, 'Never give up', NULL, '6a+', NULL, '5', 'Guga Dabrundashvili', NULL, '9', NULL, '1', 'sport climbing', NULL, 3, 12, '2019-05-03 23:48:30', '2020-05-25 02:07:36'),
(199, 'axe', NULL, '6a', NULL, '6', 'Guga Dabrundashvili', NULL, '9', NULL, '2', 'sport climbing', NULL, 4, 12, '2019-05-03 23:49:14', '2020-05-25 02:07:36'),
(200, 'Beal', NULL, '6a+', NULL, '6', 'Guga Dabrundashvili', NULL, '10', NULL, '1', 'sport climbing', NULL, 5, 12, '2019-05-03 23:50:18', '2020-05-25 02:07:36'),
(201, 'Saxli (Singing rock)', NULL, '6b+', NULL, '6', 'Irakli Soselia', NULL, '8', NULL, '1', 'sport climbing', NULL, 6, 12, '2019-05-03 23:51:42', '2020-05-25 02:07:36'),
(202, 'Kutxe', NULL, '5a', NULL, NULL, NULL, NULL, '12', NULL, '2', 'sport climbing', NULL, 7, 12, '2019-05-03 23:56:12', '2020-05-25 02:07:36'),
(203, 'Buia', NULL, '5c', NULL, NULL, NULL, NULL, '12', NULL, '2', 'sport climbing', NULL, 8, 12, '2019-05-03 23:56:54', '2020-05-25 02:07:36'),
(204, 'Streli', NULL, '6a', NULL, NULL, NULL, NULL, '13', NULL, '2', 'sport climbing', NULL, 9, 12, '2019-05-03 23:57:32', '2020-05-25 02:07:36'),
(205, 'Rusalka', NULL, '6a+', NULL, NULL, NULL, NULL, '12', NULL, '2', 'sport climbing', NULL, 10, 12, '2019-05-03 23:58:07', '2020-05-25 02:07:36'),
(206, 'Samkutxedi', NULL, '5c', NULL, NULL, NULL, NULL, '12', NULL, '2', 'sport climbing', NULL, 11, 12, '2019-05-03 23:59:02', '2020-05-25 02:07:36'),
(207, 'Classic', NULL, '5b', NULL, NULL, NULL, NULL, '11', NULL, '2', 'sport climbing', NULL, 12, 12, '2019-05-03 23:59:45', '2020-05-25 02:07:36'),
(208, 'Plita', NULL, '6a', NULL, '4', 'Guga Dabrundashvili', NULL, '10', NULL, '1', 'sport climbing', NULL, 13, 12, '2019-05-04 00:01:20', '2020-05-25 02:07:36'),
(209, 'Ghari', NULL, '5a', NULL, '4', 'Guga Dabrundashvili', NULL, '8', NULL, '1', 'sport climbing', NULL, 14, 12, '2019-05-04 00:03:10', '2020-05-25 02:07:36'),
(210, 'Luxi', NULL, '6a+', NULL, NULL, NULL, NULL, '12', NULL, '2', 'sport climbing', NULL, 15, 12, '2019-05-04 00:03:54', '2020-05-25 02:07:36'),
(231, 'Superman', NULL, 'V7', NULL, NULL, NULL, NULL, '2', NULL, '2', 'bouldering', NULL, 3, 29, '2019-05-04 15:46:28', '2020-05-25 02:07:36'),
(232, 'Rac mamas unda (Hard)', NULL, 'V5', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 4, 50, '2019-05-04 15:48:53', '2022-04-09 07:32:33'),
(233, 'Rac mamas unda (Medium)', NULL, 'V4', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 5, 50, '2019-05-04 15:49:46', '2022-04-09 07:32:03'),
(234, 'Rac mamas unda (Easy)', NULL, 'V3', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 6, 50, '2019-05-04 15:51:26', '2022-04-09 07:31:34'),
(235, 'Pikachu', NULL, 'V4', NULL, NULL, 'Shako Mikava', NULL, '6', NULL, NULL, 'bouldering', NULL, 7, 50, '2019-05-04 15:52:12', '2022-04-09 07:56:41'),
(236, 'Marto', NULL, 'V3', NULL, NULL, NULL, NULL, '4', NULL, '2', 'bouldering', NULL, 1, 30, '2019-05-04 15:52:51', '2020-05-25 02:07:36'),
(237, 'Cave', NULL, 'V9', NULL, NULL, NULL, NULL, '7', NULL, '2', 'bouldering', NULL, 2, 30, '2019-05-04 15:54:32', '2020-05-25 02:07:36'),
(238, 'Spider', NULL, 'V7', NULL, NULL, NULL, NULL, '8', NULL, '2', 'bouldering', NULL, 1, 31, '2019-05-04 15:55:39', '2020-05-25 02:07:36'),
(239, 'Spring', NULL, 'V3', NULL, NULL, NULL, NULL, '8', NULL, '2', 'bouldering', NULL, 2, 31, '2019-05-04 15:57:26', '2020-05-25 02:07:36'),
(240, 'Slopy', NULL, 'V4', NULL, NULL, NULL, NULL, '6', NULL, '2', 'bouldering', NULL, 3, 31, '2019-05-04 15:58:16', '2020-05-25 02:07:36'),
(241, 'Super slopy', NULL, 'V5', NULL, NULL, NULL, NULL, '6', NULL, '2', 'bouldering', NULL, 4, 31, '2019-05-04 15:58:51', '2020-05-25 02:07:36'),
(242, 'Travers', NULL, 'V3', NULL, NULL, NULL, NULL, '3', NULL, '2', 'bouldering', NULL, 1, 32, '2019-05-04 16:02:53', '2020-05-25 02:07:36'),
(243, 'Next', NULL, 'V4', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 2, 32, '2019-05-04 16:03:16', '2020-05-25 02:07:36'),
(244, 'Puza', NULL, 'V5', NULL, NULL, NULL, NULL, '5', NULL, '2', 'bouldering', NULL, 3, 32, '2019-05-04 16:03:40', '2020-05-25 02:07:36'),
(245, 'Camel', NULL, 'V3', NULL, NULL, NULL, NULL, '6', NULL, '2', 'bouldering', NULL, 4, 32, '2019-05-04 16:04:17', '2020-05-25 02:07:36'),
(246, 'Stone', NULL, 'V4', NULL, NULL, NULL, NULL, '8', NULL, NULL, 'bouldering', NULL, 1, 52, '2019-05-04 16:05:00', '2022-04-09 08:03:17'),
(247, 'Light', NULL, 'V2', NULL, NULL, NULL, NULL, '8', NULL, NULL, 'bouldering', NULL, 2, 52, '2019-05-04 16:05:24', '2022-04-09 08:02:54'),
(248, 'Easy', NULL, 'V2', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 3, 53, '2019-05-04 16:06:24', '2022-04-09 08:09:29'),
(249, 'Jump', NULL, 'V6', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 4, 53, '2019-05-04 16:06:54', '2022-04-09 08:08:16'),
(250, '-', NULL, 'V2', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 5, 53, '2019-05-04 16:08:15', '2022-04-09 08:07:47'),
(251, 'Globus', NULL, 'V3', NULL, NULL, NULL, NULL, '4', NULL, NULL, 'bouldering', NULL, 6, 54, '2019-05-04 16:08:53', '2022-04-09 08:12:12'),
(252, 'Chip & Dale', NULL, 'V2', NULL, NULL, NULL, NULL, '6', NULL, NULL, 'bouldering', NULL, 7, 54, '2019-05-04 16:09:46', '2022-04-09 08:12:36'),
(253, '-', NULL, '7a+', NULL, NULL, NULL, NULL, '60', NULL, '2', 'sport climbing', NULL, 1, 34, '2019-05-04 16:36:11', '2020-05-25 02:07:36'),
(254, '-', NULL, '7b+', NULL, NULL, NULL, NULL, '60', NULL, '2', 'sport climbing', NULL, 2, 34, '2019-05-04 16:37:02', '2020-05-25 02:07:36'),
(259, 'Kids', NULL, '5b', NULL, '7', 'Guga Dabrundashvili', NULL, '16', NULL, '1', 'sport climbing', NULL, 1, 5, '2019-05-14 23:38:59', '2021-05-09 06:51:01'),
(260, 'Dio', NULL, '5b', NULL, '5', 'Guga Dabrundashvili', NULL, '10', NULL, '2', 'sport climbing', NULL, 1, 35, '2019-05-15 02:51:12', '2020-05-25 02:07:36'),
(261, 'Metalica', NULL, '6c', NULL, '11', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 2, 35, '2019-05-15 02:51:50', '2020-05-25 02:07:36'),
(262, 'Sepultura', NULL, '6b+', NULL, '10', 'Guga Dabrundashvili', NULL, '26', NULL, '2', 'sport climbing', NULL, 3, 35, '2019-05-15 02:53:18', '2020-05-25 02:07:36'),
(263, 'Soulfly', NULL, '6c+', NULL, '10', 'Guga Dabrundashvili', NULL, '26', NULL, '2', 'sport climbing', NULL, 4, 35, '2019-05-15 02:54:19', '2020-05-25 02:07:36'),
(264, 'Pantera', NULL, '6a', NULL, '9', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 5, 35, '2019-05-15 02:55:07', '2020-05-25 02:07:36'),
(265, 'Chutelanjelo', NULL, '7a', NULL, '7', 'Guga Dabrundashvili', NULL, '22', NULL, '2', 'sport climbing', NULL, 6, 35, '2019-05-15 02:55:45', '2020-05-25 02:07:36'),
(266, 'Frog party', NULL, '6c+', NULL, '10', 'Irakli Bidzishvili', NULL, '28', NULL, '2', 'sport climbing', NULL, 7, 35, '2019-05-15 02:56:46', '2020-05-25 02:07:36'),
(267, '-', NULL, '4', NULL, '4', 'Temo Samsonadze', NULL, '8', 'Guga Dabrundashvili', '1', 'sport climbing', NULL, 1, 4, '2019-07-14 18:48:14', '2020-05-25 02:07:36'),
(268, '-', NULL, '6a+', NULL, '6', NULL, NULL, '9', 'Guga Dabrundashvili', '1', 'sport climbing', NULL, 2, 4, '2019-07-14 18:48:49', '2020-05-25 02:07:36'),
(269, 'Bat', NULL, '6b+', NULL, '5', 'Guga Dabrundashvili', NULL, '9', 'Guga Dabrundashvili', '1', 'sport climbing', NULL, 3, 4, '2019-07-14 18:50:00', '2020-05-25 02:07:36'),
(270, '-', NULL, '5a', NULL, NULL, 'Guga dabrundashvili', '2016', '9', NULL, '2', 'sport climbing', NULL, 1, 49, '2019-07-14 19:48:49', '2022-04-09 07:38:45'),
(271, '-', NULL, '5a', NULL, NULL, 'Guga dabrundashvili', '2016', '9', NULL, '2', 'sport climbing', NULL, 2, 49, '2019-07-14 19:49:22', '2022-04-09 07:39:35'),
(272, '-', NULL, '5b+', NULL, '6', NULL, NULL, '9', NULL, '2', 'sport climbing', NULL, 3, 36, '2019-07-14 19:50:02', '2020-05-25 02:07:36'),
(273, '-', NULL, '5b', NULL, '6', NULL, NULL, '9', NULL, '0', 'sport climbing', NULL, 4, 36, '2019-07-14 19:50:39', '2020-05-25 02:07:36'),
(274, '-', NULL, '6a+', NULL, '6', NULL, NULL, '8', NULL, '0', 'sport climbing', NULL, 1, 37, '2019-07-14 19:51:50', '2020-05-25 02:07:36'),
(275, '-', NULL, '6a', NULL, NULL, NULL, NULL, '8', NULL, '2', 'sport climbing', NULL, 2, 37, '2019-07-14 19:52:26', '2020-05-25 02:07:36'),
(276, '-', NULL, '6a', NULL, NULL, NULL, NULL, '8', NULL, '2', 'sport climbing', NULL, 3, 37, '2019-07-14 19:52:47', '2020-05-25 02:07:36'),
(277, '-', NULL, '6a+', NULL, NULL, NULL, NULL, '8', NULL, '2', 'sport climbing', NULL, 4, 37, '2019-07-14 19:53:10', '2020-05-25 02:07:36'),
(278, '-', NULL, '6b', NULL, NULL, NULL, NULL, '8', NULL, '2', 'sport climbing', NULL, 5, 37, '2019-07-14 19:54:05', '2020-05-25 02:07:36'),
(279, '-', NULL, '4', NULL, '6', NULL, NULL, '9', NULL, '0', 'sport climbing', NULL, 6, 37, '2019-07-14 19:54:44', '2020-05-25 02:07:36'),
(280, 'Ikarus', NULL, '6c', NULL, '7', 'Guga Dabrundashvili', NULL, '10', NULL, '0', 'sport climbing', NULL, 7, 5, '2019-12-16 20:59:26', '2021-05-09 06:51:01'),
(281, 'Meteor', NULL, '6b+', NULL, '10', 'Guga Dabrundashvili', NULL, '18', NULL, '1', 'sport climbing', NULL, 8, 5, '2019-12-16 21:00:08', '2021-05-09 06:51:01'),
(282, 'Chuta', NULL, '6a', NULL, '6', 'Guga Dabrundashvili', NULL, '10', NULL, '1', 'sport climbing', NULL, 9, 5, '2019-12-16 21:00:46', '2021-05-09 06:51:01'),
(283, 'warm up', NULL, '5c', NULL, '6', 'Guga Dabrundashcili', NULL, '10', NULL, '2', 'sport climbing', NULL, 1, 38, '2019-12-29 04:18:59', '2020-05-25 02:07:36'),
(284, 'Tsdo', NULL, '6a+', NULL, '6', 'Guga Dabrundashcili', NULL, '14', NULL, '2', 'sport climbing', NULL, 2, 38, '2019-12-29 04:22:04', '2020-05-25 02:07:36'),
(285, 'Cobra', NULL, '6b', NULL, '7', 'Nika Sanava', NULL, '14', NULL, '2', 'sport climbing', NULL, 3, 38, '2019-12-29 04:23:40', '2020-05-25 02:07:36'),
(286, 'პროგრესირებადი პატრიოტი', NULL, '6b', NULL, '8', 'Giorgi Gomiashvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 4, 38, '2019-12-29 04:24:35', '2020-05-25 02:07:36'),
(287, 'Pomelo', NULL, '6a', NULL, '6', 'Chuta', NULL, '13', NULL, '2', 'sport climbing', NULL, 5, 38, '2019-12-29 04:25:34', '2020-05-25 02:07:36'),
(288, '1Dam', NULL, '6a', NULL, '6', 'Nika Sanava', NULL, '11', NULL, '2', 'sport climbing', NULL, 6, 38, '2019-12-29 04:26:10', '2020-05-25 02:07:36'),
(301, 'Jaga', NULL, '6a', NULL, '7', NULL, '2019', '0', NULL, '1', 'sport climbing', NULL, 8, 40, '2020-02-29 03:06:50', '2021-08-03 07:59:08'),
(302, 'Juja', NULL, '6a', NULL, '4', NULL, '2019', '7', NULL, '1', 'sport climbing', NULL, 7, 40, '2020-02-29 03:07:37', '2021-08-03 07:59:08'),
(303, 'Hera', NULL, '6b', NULL, '7', NULL, '2019', '14', NULL, '1', 'sport climbing', NULL, 6, 40, '2020-02-29 03:08:00', '2021-08-03 07:59:08'),
(304, 'Argo', NULL, '6a', NULL, '7', NULL, '2019', '15', NULL, '1', 'sport climbing', NULL, 5, 40, '2020-02-29 03:08:26', '2021-08-03 07:59:08'),
(305, 'Gragon', NULL, '6a+', NULL, '7', NULL, '2019', '13', NULL, '1', 'sport climbing', NULL, 4, 40, '2020-02-29 03:09:01', '2021-08-03 07:59:08'),
(306, 'Hera', NULL, '5c', NULL, '6', NULL, '2019', '14', NULL, '1', 'sport climbing', NULL, 3, 40, '2020-02-29 03:09:23', '2021-08-03 07:59:08'),
(307, 'Poseidon', NULL, '6b+', NULL, '6', NULL, '2019', '14', NULL, '1', 'sport climbing', NULL, 2, 40, '2020-02-29 03:09:50', '2021-08-03 07:59:08'),
(308, 'Zeus', NULL, '6b', NULL, '6', NULL, '2019', '14', NULL, '2', 'sport climbing', NULL, 1, 40, '2020-02-29 03:10:16', '2021-08-03 07:59:08'),
(309, 'Hulk', NULL, '5a', NULL, '5', 'Irakli Bidzishvili', NULL, '8', NULL, '2', 'sport climbing', NULL, 1, 41, '2020-04-22 22:19:57', '2020-05-25 02:07:36'),
(310, 'Tetris', NULL, '5c', NULL, '5', 'Guga Dabrundashvili', NULL, '8', NULL, '2', 'sport climbing', NULL, 2, 41, '2020-04-22 22:21:21', '2020-05-25 02:07:36'),
(311, 'Game Boy', NULL, '6a', NULL, '5', 'Guga Dabrundashvili', NULL, '7', NULL, '2', 'sport climbing', NULL, 3, 41, '2020-04-22 22:22:35', '2020-05-25 02:07:36'),
(312, 'Spiderman', NULL, '6a', NULL, '7', 'Nika Sanava', NULL, '11', NULL, '2', 'sport climbing', NULL, 4, 41, '2020-04-22 22:24:43', '2020-05-25 02:07:36'),
(313, 'Batman', NULL, '6b+', NULL, '11', 'Nika Sanava', NULL, '14', NULL, '2', 'sport climbing', NULL, 5, 41, '2020-04-22 22:28:47', '2020-05-25 02:07:36'),
(314, 'Fataliti', NULL, '6c', NULL, '9', 'Chuta', NULL, '14', NULL, '2', 'sport climbing', NULL, 6, 41, '2020-04-22 22:29:59', '2020-05-25 02:07:36'),
(315, 'Sonyc', NULL, '6b', NULL, '7', 'Irakli Bidzishvili', NULL, '17', NULL, '2', 'sport climbing', NULL, 7, 41, '2020-04-22 22:31:09', '2020-05-25 02:07:36'),
(316, 'Mortal Combat', NULL, '6a', NULL, '7', 'Guga Dabrundashvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 8, 41, '2020-04-22 22:32:05', '2020-05-25 02:07:36'),
(317, 'Tekken', NULL, '6a+', NULL, '7', 'Irakli Bidzishvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 9, 41, '2020-04-22 22:33:11', '2020-05-25 02:07:36'),
(318, 'Cush Bush', NULL, '6a+', NULL, '7', 'Irakli Bidzishvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 10, 41, '2020-04-22 22:34:23', '2020-05-25 02:07:36'),
(319, 'Mario', NULL, '5c+', NULL, '6', 'Super Mario', NULL, '11', NULL, '2', 'sport climbing', NULL, 11, 41, '2020-04-22 22:35:21', '2020-05-25 02:07:36'),
(320, 'Cveti', NULL, '5b', NULL, '10', 'Guga Dabrundashvili', NULL, '18', NULL, '1', 'sport climbing', NULL, 1, 42, '2020-04-22 22:45:26', '2020-05-25 02:07:36'),
(321, 'Shua', NULL, '5c', NULL, '10', 'Guga Dabrundashvili', NULL, '18', NULL, '2', 'sport climbing', NULL, 2, 42, '2020-04-22 22:46:48', '2020-05-25 02:07:36'),
(322, 'Maxata', NULL, '5c+', NULL, '6', 'Guga Dabrundashvili', NULL, '14', NULL, '2', 'sport climbing', NULL, 3, 42, '2020-04-22 22:47:45', '2020-05-25 02:07:36'),
(323, 'ARA', NULL, '7b', NULL, '6', 'Temo Samsonadze', NULL, '10', NULL, '2', 'sport climbing', NULL, 10, 4, '2020-04-22 22:54:10', '2020-05-25 02:07:36'),
(324, 'Pirosmani', NULL, '8a+', NULL, '9', 'Zorbei.A', NULL, '15', 'Zorbei.A', '2', 'sport climbing', NULL, 13, 4, '2020-04-22 22:55:38', '2020-05-25 02:07:36'),
(325, 'No Chance', NULL, '7c', NULL, '7', 'Temo Samsonadze', NULL, '12', NULL, '2', 'sport climbing', NULL, 15, 4, '2020-04-22 22:56:57', '2020-05-25 02:07:36'),
(326, 'Egg', NULL, '6b+', NULL, '6', 'Temo Samsonadze', NULL, '11', NULL, '2', 'sport climbing', NULL, 16, 4, '2020-04-22 22:58:02', '2020-05-25 02:07:36'),
(327, 'Emi', NULL, '6a+', NULL, '5', 'Temo Samsonadze', NULL, '10', NULL, '2', 'sport climbing', NULL, 17, 4, '2020-04-22 22:59:07', '2020-05-25 02:07:36'),
(328, 'Help Me', NULL, '6a', NULL, '6', 'Temo Samsonadze', NULL, '10', NULL, '2', 'sport climbing', NULL, 18, 4, '2020-04-22 23:00:11', '2020-05-25 02:07:36'),
(329, 'Zig Zag', NULL, '6a', NULL, '10', 'Irakli Bidzishvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 1, 45, '2020-04-22 23:09:45', '2021-05-09 05:56:41'),
(330, 'Rout 66', NULL, '6b+', NULL, '10', 'Guga Dabrundashvili', NULL, '28', NULL, '2', 'sport climbing', NULL, 2, 45, '2020-04-22 23:12:07', '2021-05-09 05:56:41'),
(331, 'Gulo', NULL, '7a', NULL, '12', 'Chuta', NULL, '16', NULL, '2', 'sport climbing', NULL, 3, 45, '2020-04-22 23:13:39', '2020-05-25 02:07:36'),
(332, 'Fantastic', NULL, '7a', NULL, '11', 'Guga Dabrundashvili', NULL, '27', NULL, '2', 'sport climbing', NULL, 4, 45, '2020-04-22 23:15:04', '2020-05-25 02:07:36'),
(333, 'Dirty Boy', NULL, '6a+', NULL, '12', 'Nika Sanava', NULL, '28', NULL, '2', 'sport climbing', NULL, 5, 45, '2020-04-22 23:16:41', '2020-05-25 02:07:36'),
(334, 'Mono', NULL, '6b', NULL, '11', 'Irakli Bidzishvili', NULL, '27', NULL, '2', 'sport climbing', NULL, 6, 45, '2020-04-22 23:17:41', '2020-05-25 02:07:36'),
(335, 'Mars', NULL, '6b+', NULL, '25', 'Irakli Bidzishvili', NULL, '11', NULL, '2', 'sport climbing', NULL, 1, 43, '2020-04-22 23:23:18', '2020-05-25 02:07:36'),
(336, 'Jupiter', NULL, '6b', NULL, '10', 'Chuta', NULL, '25', NULL, '2', 'sport climbing', NULL, 2, 43, '2020-04-22 23:47:31', '2020-05-25 02:07:36'),
(337, 'Pluton', NULL, '6b+', NULL, '11', 'Nika Sanava', NULL, '26', NULL, '2', 'sport climbing', NULL, 3, 43, '2020-04-22 23:48:29', '2020-05-25 02:07:36'),
(338, 'Saturn', NULL, '6b+', NULL, '12', 'Guga Dabrundashvili', NULL, '25', NULL, '2', 'sport climbing', NULL, 4, 43, '2020-04-22 23:49:29', '2020-05-25 02:07:36'),
(339, 'Porcno', NULL, '6c', NULL, '8', 'Grizly n’ bear', NULL, '15', 'Temo Samsonadze', '2', 'sport climbing', NULL, 4, 44, '2020-05-24 06:50:57', '2020-05-25 02:07:36'),
(340, 'Groot', NULL, '5c', NULL, '5', 'Irakli Bidzishvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 1, 46, '2020-05-24 07:01:36', '2020-05-25 02:07:36'),
(341, 'Frankenjura', NULL, '6c', NULL, '5', 'Irakli Bidzishvili', NULL, '12', NULL, '2', 'sport climbing', NULL, 2, 46, '2020-05-25 01:57:59', '2020-05-25 02:07:36'),
(342, 'Kekela', NULL, '6a+', NULL, '7', 'Zura Kvestadze', NULL, '13', NULL, '2', 'sport climbing', NULL, 3, 46, '2020-05-25 01:59:19', '2020-05-25 02:07:36'),
(343, 'Maro', NULL, '6a', NULL, '8', 'Misho Nadiradze', NULL, '14', NULL, '2', 'sport climbing', NULL, 4, 46, '2020-05-25 02:01:00', '2020-05-25 02:07:36'),
(344, 'Ah Stoya', NULL, '6c', NULL, '6', 'Chuta', NULL, '13', NULL, '2', 'sport climbing', NULL, 5, 46, '2020-05-25 02:02:51', '2020-05-25 02:07:36'),
(345, 'Voodo', NULL, '7a+', NULL, '6', 'Nika Sanava', NULL, '13', NULL, '2', 'sport climbing', NULL, 6, 46, '2020-05-25 02:04:09', '2020-05-25 02:07:36'),
(346, 'Shark', NULL, '7a', NULL, '7', 'Guga Dabrundashvili', NULL, '13', NULL, '2', 'sport climbing', NULL, 7, 46, '2020-05-25 02:05:08', '2020-05-25 02:07:36'),
(347, 'Pussy Power', NULL, '6b+', NULL, '6', 'Guga Dabrundashvili', NULL, '13', NULL, '2', 'sport climbing', NULL, 8, 46, '2020-05-25 02:06:09', '2020-05-25 02:07:36'),
(348, '-', NULL, '6a+', NULL, '17', 'Guga Dabrundashvili', '2018', '40', NULL, '1', 'sport climbing', NULL, 1, 47, '2021-03-21 07:12:48', '2022-06-10 07:23:18'),
(349, '-', NULL, '7b', '7b+', '13', 'Guga Dabrundashvili', '2018', '30', NULL, '0', 'sport climbing', NULL, 2, 47, '2021-03-21 07:16:45', '2022-06-10 07:22:32'),
(350, '-', NULL, '5b', NULL, '8', 'Guga Dabrundashvili', '2018', '20', NULL, '1', 'sport climbing', NULL, 3, 47, '2021-03-21 07:17:35', '2022-06-10 07:23:56'),
(351, '-', NULL, '5c', '5c', '9', 'Guga Dabrundashvili', '2019', '20', NULL, '1', 'sport climbing', NULL, 4, 47, '2021-03-21 07:18:28', '2021-07-13 00:25:32'),
(352, '-', NULL, '6b', NULL, '9', 'Guga Dabrundashvili', '2019', '20', NULL, '1', 'sport climbing', NULL, 5, 47, '2021-03-21 07:20:52', '2021-07-13 00:25:32'),
(353, '-', NULL, '6b+', '6c', '9', 'Gega dabrundashvili', '2019', '20', NULL, NULL, 'sport climbing', NULL, 6, 47, '2021-03-21 07:26:41', '2021-07-13 00:25:32'),
(354, '-', NULL, '6b+', NULL, '18', 'Guga dabrundashvili - Temo Samsonadze - XZ Guro', '2021', '40', NULL, '1', 'sport climbing', NULL, 7, 47, '2021-03-21 07:27:31', '2021-08-03 08:05:52'),
(355, 'Super mario', NULL, '6a', NULL, '7', 'Irkli Bidzishvili', '2020', '12', NULL, NULL, 'sport climbing', NULL, 12, 41, '2021-05-09 06:04:00', '2021-05-09 06:04:00'),
(356, 'Pirania', NULL, '6a', NULL, '9', 'Temo Samsonadze', '2020', '17', NULL, NULL, 'sport climbing', NULL, 2, 17, '2021-05-09 07:22:02', '2021-08-03 07:35:41'),
(357, 'Nu Pagadi', NULL, '7a+', NULL, '13', 'Temo Samsonadze', '2018', '29', 'Temo Samsonadze', NULL, 'sport climbing', NULL, 10, 1, '2021-06-05 23:10:25', '2021-06-05 23:11:07'),
(358, '-', NULL, '6c', '6c+', '14', 'Guga Dabrundashvili', '2021', '30', NULL, '0', 'sport climbing', NULL, 8, 47, '2021-07-13 00:25:15', '2021-11-21 07:14:07'),
(359, 'Black sea', NULL, '6b', '6b+', '9', 'Temo samsonadze', '20.06.2021', '17', NULL, NULL, 'sport climbing', NULL, 1, 17, '2021-08-01 04:42:14', '2021-08-01 04:46:15'),
(360, 'Batumi', NULL, '6c', NULL, '6', 'Temo samsonadze', '20.06.2021', '13', NULL, NULL, 'sport climbing', NULL, 2, 18, '2021-08-01 04:44:15', '2021-08-03 07:44:42'),
(361, '.', NULL, '6a+', '6b', '9', 'Temo Samsonadze', '20.06.2021', '17', NULL, NULL, 'sport climbing', NULL, 1, 18, '2021-08-01 04:45:19', '2021-08-03 07:44:42'),
(362, 'Toilet', NULL, '5c+', NULL, '8', 'Guga Dabrundashvili', '2020', '20', NULL, NULL, 'sport climbing', NULL, 7, 18, '2021-08-03 07:46:21', '2021-08-03 07:46:21'),
(363, '-', NULL, '5a', NULL, '0', NULL, NULL, '20', NULL, NULL, 'sport climbing', NULL, 8, 18, '2021-08-03 07:47:21', '2021-08-03 07:47:21'),
(364, 'Small', NULL, '5b', NULL, NULL, 'Guga dabrundashvili', '2018', '10', NULL, NULL, 'sport climbing', NULL, 1, 8, '2021-11-16 08:25:47', '2021-11-16 08:43:55');
INSERT INTO `routes` (`id`, `name`, `text`, `grade`, `or_grade`, `bolts`, `author`, `creation_data`, `height`, `first_ascent`, `anchor_type`, `category`, `stars`, `num`, `sector_id`, `created_at`, `updated_at`) VALUES
(365, 'Small 2', NULL, '5a', NULL, NULL, 'Guga dabrundashvili', '2019', '10', NULL, NULL, 'sport climbing', NULL, 2, 8, '2021-11-16 08:26:40', '2021-11-16 08:27:52'),
(366, 'Yellow corner', NULL, '6c', NULL, '12', 'Guga dabrundashvili', '2019', '26', NULL, NULL, 'sport climbing', NULL, 13, 8, '2021-11-16 08:32:04', '2021-11-16 08:42:18'),
(367, 'Ajika', NULL, '6b', NULL, '8', 'Temo Qurdiani', '2019', '15', NULL, NULL, 'sport climbing', NULL, 8, 8, '2021-11-16 08:39:27', '2021-11-16 08:42:18'),
(368, '9 Maisi', NULL, '6a+', NULL, '9', 'Guga Dabrundashvili', '2019', '25', NULL, NULL, 'sport climbing', NULL, 9, 8, '2021-11-16 08:40:30', '2021-11-16 08:42:18'),
(369, 'Bukhari', NULL, '6c+', NULL, '10', 'Guga Dabrundashvili', '2019', '25', NULL, NULL, 'sport climbing', NULL, 10, 8, '2021-11-16 08:41:30', '2021-11-16 08:42:18'),
(370, '.', NULL, '5b', NULL, NULL, 'Guga Dabrundashvili', '2021', '30', NULL, NULL, 'sport climbing', NULL, 9, 47, '2021-11-21 07:08:44', '2021-11-21 07:08:44'),
(371, '.', NULL, '5a', NULL, NULL, 'Guga Dabrundashvili - Temo Samsonadze', '2021', '20', NULL, NULL, 'sport climbing', NULL, 10, 47, '2021-11-21 07:09:51', '2021-11-21 07:09:51'),
(372, '.', NULL, '6c', '6c+', NULL, 'Temo Samsonadze', '2021', '30', NULL, '1', 'sport climbing', NULL, 11, 47, '2021-11-21 07:10:41', '2021-11-21 07:10:41'),
(373, '.', NULL, '6c', '6c', NULL, 'Temo Samsonadze', '2021', '30', NULL, NULL, 'sport climbing', NULL, 12, 47, '2021-11-21 07:11:33', '2021-11-21 07:11:33'),
(374, '.', NULL, '6a', NULL, '12', 'Guga Dabrundashvili', '2021', '30', NULL, '1', 'sport climbing', NULL, 13, 47, '2021-11-21 07:12:40', '2022-06-10 07:21:19'),
(375, 'Start', NULL, '6c+', NULL, '11', 'Temo Samsonadze & Emili', '2021', '27', 'Temo Samsonade', NULL, 'sport climbing', NULL, 3, 48, '2021-11-25 07:51:36', '2022-06-10 07:10:44'),
(376, 'Borjomi', NULL, '7a', '7a+', '13', 'Temo Samsonadze & Emili', '2021', '30', NULL, NULL, 'sport climbing', NULL, 4, 48, '2021-11-25 07:53:00', '2022-06-10 06:56:28'),
(377, 'Hotel Transylvania', NULL, '6c', NULL, '12', 'Temo Samsonadze & Emilu', '2021', '29', 'Temo Samsonadze', NULL, 'sport climbing', NULL, 5, 48, '2021-11-25 07:55:53', '2022-06-10 06:52:24'),
(378, 'Boulder party', NULL, '7a+', NULL, '12', 'Temo Samsonadze & emily', '2021', '28', NULL, '1', 'sport climbing', NULL, 6, 48, '2021-11-25 07:57:01', '2022-06-10 07:10:11'),
(379, 'Finish', NULL, '7b+', NULL, '12', 'Temo Samsonadze & Emili', '2021', '23', 'Temo Samsonadze', '1', 'sport climbing', NULL, 7, 48, '2021-11-25 07:58:43', '2022-06-10 07:11:15'),
(380, 'Dragonito', NULL, '8a', 'Project', NULL, 'Temo Samsonadze & Emili', '2021', '28', NULL, NULL, 'top rope', NULL, 8, 48, '2021-11-25 08:00:26', '2022-06-10 06:53:50'),
(381, '-', NULL, '6b+', NULL, '12', 'Temo Samsonadze & Emili', '2021', '30', NULL, '1', 'sport climbing', NULL, 9, 48, '2021-11-25 08:01:20', '2022-06-10 06:54:35'),
(382, 'Black hole', NULL, '7b', NULL, NULL, 'Temo samsonadze', '2018', '29', NULL, NULL, 'sport climbing', NULL, 5, 2, '2022-04-01 00:04:54', '2022-09-16 20:05:32'),
(383, '-', NULL, '7c', 'Project', NULL, 'Temo samsonadze', '2018', '29', NULL, NULL, 'sport climbing', NULL, 6, 2, '2022-04-01 00:05:30', '2022-04-01 00:05:58'),
(384, '-', NULL, '7b', 'Project', NULL, 'Temo Samsonadze', '2021', '12', NULL, NULL, 'sport climbing', NULL, 2, 28, '2022-04-09 07:28:29', '2022-04-09 07:28:29'),
(385, 'bora bora', NULL, '6c', NULL, NULL, 'Temo samsonadze', '2021', '19', NULL, NULL, 'sport climbing', NULL, 3, 28, '2022-04-09 07:30:21', '2022-04-09 07:30:21'),
(386, '-', NULL, 'V7+', 'Project', NULL, NULL, NULL, '10', NULL, NULL, 'bouldering', NULL, 1, 51, '2022-04-09 07:52:09', '2022-04-09 07:52:09'),
(387, 'Gogsa', NULL, '6a+', NULL, '7', 'Temo samsonadze', '2021', '16', NULL, '1', 'sport climbing', NULL, 1, 48, '2022-06-10 06:44:29', '2022-06-10 06:46:17'),
(388, 'Dragon age', NULL, '6b', NULL, '8', 'Temo Samsonadze', '2021', '20', NULL, '1', 'sport climbing', NULL, 2, 48, '2022-06-10 06:45:48', '2022-06-10 06:46:17'),
(389, '-', NULL, '5b', NULL, '6', 'Guga dabrundashvili', '2021', '10', NULL, NULL, 'sport climbing', NULL, 5, 17, '2022-06-27 10:06:31', '2022-06-27 10:08:29'),
(390, '-', NULL, '5a', NULL, '4', 'Guga dabrundashvili', '2021', '8', NULL, NULL, 'sport climbing', NULL, 7, 17, '2022-06-27 10:07:19', '2022-06-27 10:07:19'),
(391, 'rock fall', NULL, '5a', NULL, '8', 'Temo Samsonadze', '13.07.2021', '10', NULL, '1', 'sport climbing', NULL, 1, 56, '2022-07-19 10:15:27', '2022-07-19 10:15:27'),
(392, 'zone N9', NULL, '6a', 'Project', '7', 'Temo Samsonadze', '13.07.2022', '10', NULL, '1', 'sport climbing', NULL, 2, 56, '2022-07-19 10:20:41', '2022-07-19 10:25:09'),
(393, 'Rest time', NULL, '6a', 'Project', '7', 'Temo Samsonadze', '13.07.2022', '10', NULL, '1', 'sport climbing', NULL, 3, 56, '2022-07-19 10:24:55', '2022-07-19 10:24:55'),
(394, 'Anubi', NULL, '5b', NULL, NULL, NULL, NULL, '8', NULL, NULL, 'sport climbing', NULL, 1, 57, '2022-07-31 07:21:39', '2022-07-31 07:32:01'),
(395, 'Osiris', NULL, '5c', NULL, NULL, NULL, NULL, '8', NULL, NULL, 'sport climbing', NULL, 2, 57, '2022-07-31 07:22:13', '2022-07-31 07:31:55'),
(396, 'Isis', NULL, '6a', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 3, 57, '2022-07-31 07:22:52', '2022-07-31 07:22:52'),
(397, 'Bast', NULL, '6a', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 4, 57, '2022-07-31 07:23:24', '2022-07-31 07:23:24'),
(398, 'Horus', NULL, '6c', NULL, NULL, NULL, NULL, '14', NULL, NULL, 'sport climbing', NULL, 5, 57, '2022-07-31 07:25:56', '2022-07-31 07:25:56'),
(399, 'Anudis', NULL, '6a', NULL, NULL, NULL, NULL, '12', NULL, NULL, 'sport climbing', NULL, 6, 57, '2022-07-31 07:26:29', '2022-07-31 07:26:29'),
(400, 'Sobek', NULL, '5b', NULL, NULL, NULL, NULL, '11', NULL, NULL, 'sport climbing', NULL, 7, 57, '2022-07-31 07:26:57', '2022-07-31 07:26:57'),
(401, 'Khonshu', NULL, '5c', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 8, 57, '2022-07-31 07:27:40', '2022-07-31 07:27:40'),
(402, 'Thoth', NULL, '6a', NULL, NULL, NULL, NULL, '12', NULL, NULL, 'sport climbing', NULL, 9, 57, '2022-07-31 07:28:09', '2022-07-31 07:28:09'),
(403, 'Sekhmet', NULL, '6a', NULL, NULL, NULL, NULL, '12', NULL, NULL, 'sport climbing', NULL, 10, 57, '2022-07-31 07:28:46', '2022-07-31 07:28:46'),
(404, 'Seth', NULL, '6a', NULL, NULL, NULL, NULL, '12', NULL, NULL, 'sport climbing', NULL, 11, 57, '2022-07-31 07:29:15', '2022-07-31 07:29:15'),
(405, 'Orgia', NULL, '5b', NULL, NULL, NULL, NULL, '11', NULL, NULL, 'sport climbing', NULL, 12, 57, '2022-07-31 07:30:00', '2022-07-31 07:30:00'),
(406, 'Tomazooma', NULL, '5b', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 13, 57, '2022-07-31 07:30:33', '2022-07-31 07:30:33'),
(407, 'Tawa', NULL, '5c', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 14, 57, '2022-07-31 07:31:04', '2022-07-31 07:31:04'),
(408, 'Manitou', NULL, '6b', NULL, NULL, NULL, NULL, '10', NULL, NULL, 'sport climbing', NULL, 15, 57, '2022-07-31 07:31:29', '2022-07-31 07:31:29'),
(409, 'Rock erotic', NULL, '8a', 'Project', '14', 'Temo samsonadze', '10.08.2022', '22', NULL, NULL, 'sport climbing', NULL, 1, 58, '2022-08-10 08:18:24', '2022-08-10 08:18:24'),
(410, 'Climbing performance', NULL, '7c', 'Project', '11', 'Temo Samsonadze', '10.08.2022', '22', NULL, NULL, 'sport climbing', NULL, 2, 58, '2022-08-10 08:19:33', '2022-08-10 08:19:33'),
(411, 'Miura edition', NULL, '8b', 'Project', '11', 'Temo Samsonadze', '10.08.2022', '20', NULL, NULL, 'sport climbing', NULL, 3, 58, '2022-08-10 08:21:00', '2022-08-10 08:21:00'),
(412, 'Magic corner', NULL, '6c+', 'Project', '11', 'Temo Samsonadze', '10.08.2022', '25', NULL, NULL, 'sport climbing', NULL, 4, 58, '2022-08-10 08:22:26', '2022-08-10 08:22:26'),
(413, 'Rocky', NULL, '6c', 'Project', '8', 'Temo Samsonadze', '10.08.2022', '20', NULL, NULL, 'sport climbing', NULL, 5, 58, '2022-08-10 08:23:24', '2022-08-10 08:23:24'),
(414, '-', NULL, '6a+', NULL, '9', 'Nika.m', '2022', '18', NULL, '1', 'sport climbing', NULL, 6, 16, '2022-09-05 03:19:38', '2022-09-11 23:15:17'),
(415, 'Swing', NULL, '5c+', NULL, '10', 'Guga dabrundashvili', '2022', '23', NULL, '1', 'sport climbing', NULL, 4, 16, '2022-09-05 03:20:22', '2022-09-11 23:14:42'),
(416, 'Mordor', NULL, '6a+', NULL, '14', 'Guga dabrundashvili', '2022', '28', NULL, '1', 'sport climbing', NULL, 3, 16, '2022-09-05 03:21:16', '2022-09-11 23:13:19'),
(417, 'Muza', NULL, '5c', NULL, '11', 'Guga Dabrundashvili', '2022', '27', NULL, '1', 'sport climbing', NULL, 2, 16, '2022-09-05 03:23:09', '2022-09-11 23:14:42'),
(418, 'Nika', NULL, '6a', NULL, '12', 'Nika.m', '2022', '27', NULL, '1', 'sport climbing', NULL, 1, 16, '2022-09-05 03:24:35', '2022-09-11 23:14:42'),
(419, '-', NULL, '6a+', NULL, '5', 'Guga Dabrundashvili', '2022', '12', NULL, '1', 'sport climbing', NULL, 1, 59, '2022-09-05 03:28:06', '2022-09-05 03:28:06'),
(420, '-', NULL, '6a+', NULL, '7', 'Guga Dabrundashvili', '2022', '13', NULL, '1', 'sport climbing', NULL, 2, 59, '2022-09-05 03:28:57', '2022-09-05 03:28:57'),
(421, '-', NULL, '6b', NULL, '5', 'Guga Dabrundashvili', '2022', '11', NULL, '1', 'sport climbing', NULL, 3, 59, '2022-09-05 03:29:36', '2022-09-05 03:29:36');

-- --------------------------------------------------------

--
-- Структура таблицы `sale_codes`
--

CREATE TABLE `sale_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `one_time_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `action_data` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sectors`
--

CREATE TABLE `sectors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `all_day_in_shade` int DEFAULT NULL,
  `all_day_in_sun` int DEFAULT NULL,
  `in_the_shade_afternoon` int DEFAULT NULL,
  `in_the_shade_befornoon` int DEFAULT NULL,
  `in_shade_after_10` int DEFAULT NULL,
  `in_shade_after_15` int DEFAULT NULL,
  `for_kids` int DEFAULT NULL,
  `for_family` int DEFAULT NULL,
  `wolking_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roof` int DEFAULT NULL,
  `overhang` int DEFAULT NULL,
  `slabby` int DEFAULT NULL,
  `vertical` int DEFAULT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `published` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sectors`
--

INSERT INTO `sectors` (`id`, `name`, `text`, `all_day_in_shade`, `all_day_in_sun`, `in_the_shade_afternoon`, `in_the_shade_befornoon`, `in_shade_after_10`, `in_shade_after_15`, `for_kids`, `for_family`, `wolking_time`, `roof`, `overhang`, `slabby`, `vertical`, `article_id`, `published`, `created_at`, `updated_at`) VALUES
(1, 'The Offise', '<p>Until 1-2pm, the sector is shaded and comfortable to climb in the summer. In winter, we recommend climbing after 2pm.</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the biginers and middle class climbers.</p>', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 1, '2019-03-28 22:23:53', '2021-04-10 15:09:09'),
(2, 'Cave', '<p>Until 3-4pm, the sector is shaded and comfortable to climb in the summer. In winter, we recommend climbing after 5pm.</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the middle class climbers.</p>', NULL, NULL, NULL, 0, NULL, 1, NULL, NULL, NULL, NULL, 0, 1, 1, 6, 1, '2019-03-29 14:11:00', '2021-04-10 15:09:29'),
(3, 'Canion', '<p>Until 1-3pm, the sector is shaded and hiding behind the rocks after 5-6pm comfortable to climb in the summer. In winter, we recommend climbing after 2pm to 4pm.</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the experienced climbers.</p>', NULL, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, 1, 6, 1, '2019-03-31 06:14:32', '2021-04-08 15:55:49'),
(4, 'Tsunami', '<p>Until 1-3pm, the sector is shaded and comfortable to climb in the summer. In winter, we recommend climbing after 2pm.</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the middle class and experienced climbers.</p>', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 6, 1, '2019-03-31 06:15:50', '2021-04-08 15:53:19'),
(5, '+18', '<p>The sector is in the shade after 2-3 hours of the day and you can climb all day. Not suitable for climbing in winter.&nbsp;</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 1, '2019-04-02 02:08:41', '2021-04-10 15:58:20'),
(6, 'ATA', '<p>The sector is in the shade after 4-5.30 hours of the day and you can climb all day. Not suitable for climbing in winter</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2019-04-02 02:11:15', '2021-04-10 15:55:42'),
(7, 'Dano', '<p>Until 1-3pm, the sector is shaded and comfortable to climb in the summer. In winter, we recommend climbing after 2pm.</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the experienced and professional climbers.</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, '2019-04-02 13:45:29', '2021-04-10 15:10:20'),
(8, 'Choma', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 11, 1, '2019-04-02 14:02:46', '2021-11-16 16:18:19'),
(9, 'Black', '<p>In the summer, because of the sun, the rocks bask and it&rsquo;s not possible to climb on them, but the sun goes over the rocks in 4-5pm After that, the temperature here is excellent for climbing. In winter, despite the cold due to the sun, the rocks are very warm and it&rsquo;s nice to climb them while the sun warms them (5-6 pm)</p>', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 8, 1, '2019-04-03 01:50:02', '2021-04-10 15:03:44'),
(10, 'Width', NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 8, 1, '2019-04-03 01:51:56', '2021-04-10 15:06:04'),
(11, 'Clock', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 8, 1, '2019-04-03 01:53:09', '2021-04-10 15:04:40'),
(12, 'Sport sector', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 15, 1, '2019-04-08 10:17:36', '2021-04-08 15:58:45'),
(13, 'Bouldering sector', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 15, 1, '2019-04-08 10:48:11', '2021-04-08 15:57:58'),
(14, 'Arsha (Sport climbing)', '<p>Until 2pm, the sector is shaded and comfortable to climb in the summer. In winter, climb not passable.&nbsp;</p>\n\n<p>&nbsp;</p>\n\n<p>The sector is good for the biginer and middle class climbers.</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 1, '2019-04-10 08:19:32', '2021-03-21 10:38:51'),
(15, 'Arsha (Multi pitch)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 1, '2019-04-10 08:21:17', '2019-04-10 08:21:17'),
(16, 'Gveleti', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 36, 1, '2019-05-03 11:42:07', '2022-09-05 10:58:21'),
(17, 'Fuel station', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 13, 1, '2019-05-03 11:49:30', '2021-08-01 12:30:46'),
(18, 'Parking', NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 13, 1, '2019-05-03 11:51:43', '2021-08-01 12:39:53'),
(19, 'Chalaadi (Sector 1)', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 9, 1, '2019-05-03 12:10:58', '2022-03-09 17:31:49'),
(20, 'Chalaadi (Sector 2)', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 9, 1, '2019-05-03 12:11:33', '2022-03-09 17:28:10'),
(21, 'Dzevri (sector 1)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 1, '2019-05-04 06:04:37', '2021-11-16 15:32:35'),
(22, 'Dzevri (sector 2)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 10, 1, '2019-05-04 06:06:00', '2022-04-09 16:59:24'),
(24, 'Chip & Deil', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 1, '2019-05-04 09:58:30', '2019-05-04 10:05:49'),
(25, 'Cave (lead)', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2019-05-04 09:59:54', '2022-04-09 16:19:34'),
(26, 'Superman (lead)', NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2019-05-04 10:01:22', '2021-11-16 15:30:42'),
(27, 'Caridor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2019-05-04 10:02:19', '2021-11-16 15:29:30'),
(28, 'Palanga (lead)', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2019-05-04 10:03:02', '2021-11-16 13:46:33'),
(29, 'Superman (Bouldering)', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 14, 1, '2019-05-04 10:14:05', '2021-11-16 15:37:53'),
(30, 'Cave (Bouldering)', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 14, 1, '2019-05-04 10:15:53', '2021-11-16 15:37:10'),
(31, 'Palalanga (Bouldering)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 1, '2019-05-04 10:16:55', '2022-04-09 16:14:23'),
(32, 'Camel', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2019-05-04 10:17:55', '2021-11-16 14:03:22'),
(34, 'Travers', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 15, 1, '2019-05-05 00:34:58', '2021-04-08 16:01:12'),
(35, 'Navardzeti 1', '<p>The sector is in the shade after 3-4 hours of the day and you can climb all day. Not suitable for climbing in winter</p>', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 16, 1, '2019-05-15 10:49:30', '2021-11-16 14:18:34'),
(36, 'Tower', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 18, 1, '2019-07-15 02:16:39', '2021-04-11 12:36:11'),
(37, 'Boulder', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 18, 1, '2019-07-15 02:19:14', '2021-04-11 12:36:44'),
(38, 'Tsdo (Sport sector)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 17, 1, '2019-12-29 12:12:00', '2021-04-10 15:00:04'),
(39, 'Tsdo (Multe-pitch sector)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 17, 1, '2019-12-29 12:13:21', '2021-04-10 14:59:36'),
(40, 'Old road', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 13, 1, '2020-02-29 11:02:50', '2021-08-01 12:40:30'),
(41, 'Game Zone', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-04-23 06:18:11', '2021-04-10 15:50:12'),
(42, 'Canion (2)', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 1, '2020-04-23 06:43:03', '2021-04-10 15:37:49'),
(43, 'Bazi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 1, '2020-04-23 07:20:29', '2021-04-10 15:38:29'),
(44, 'Ozzy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 1, '2020-05-24 14:36:04', '2021-04-10 15:42:59'),
(45, 'Gulo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, 1, '2020-05-24 14:36:56', '2021-04-10 15:47:08'),
(46, 'Kovida', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-05-24 14:59:58', '2021-04-10 15:54:53'),
(47, 'Monument', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, 1, '2021-03-21 15:03:05', '2021-11-16 16:11:53'),
(48, 'Dragon', NULL, 0, NULL, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, 1, 26, 1, '2021-11-24 13:18:18', '2022-06-10 14:43:03'),
(49, 'Camping', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 14, 1, '2022-04-09 15:11:29', '2022-04-09 15:12:33'),
(50, 'რაც მამას უნდა', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 14, 1, '2022-04-09 15:13:21', '2022-04-09 15:14:47'),
(51, 'Highball', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2022-04-09 15:46:23', '2022-04-09 15:51:26'),
(52, 'Stone', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2022-04-09 16:01:48', '2022-04-09 16:02:30'),
(53, 'Easy', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 14, 1, '2022-04-09 16:06:18', '2022-04-09 16:56:18'),
(54, 'Globus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 1, '2022-04-09 16:10:16', '2022-04-09 16:11:39'),
(56, 'Tsixidziri', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 13, 1, '2022-07-19 18:11:11', '2022-07-19 18:38:45'),
(57, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 1, '2022-07-31 15:18:46', '2022-07-31 15:20:20'),
(58, 'Mzetamze', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 26, 1, '2022-08-10 16:16:05', '2022-08-10 16:16:54'),
(59, 'Ananuri', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 37, 1, '2022-09-05 10:53:02', '2022-09-05 10:53:34');

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

-- --------------------------------------------------------

--
-- Структура таблицы `sector_local_images`
--

CREATE TABLE `sector_local_images` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sector_local_image_sector`
--

CREATE TABLE `sector_local_image_sector` (
  `id` bigint UNSIGNED NOT NULL,
  `sector_id` bigint UNSIGNED NOT NULL,
  `image_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Структура таблицы `service_followers`
--

CREATE TABLE `service_followers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `service_images`
--

CREATE TABLE `service_images` (
  `id` bigint UNSIGNED NOT NULL,
  `titlr` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `shiped_countries`
--

CREATE TABLE `shiped_countries` (
  `id` bigint UNSIGNED NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shiping_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `free_shiping_price_after` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sites`
--

CREATE TABLE `sites` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map` text COLLATE utf8mb4_unicode_ci,
  `us_info_id` bigint UNSIGNED NOT NULL,
  `ka_info_id` bigint UNSIGNED NOT NULL,
  `ru_info_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sites`
--

INSERT INTO `sites` (`id`, `email`, `number`, `map`, `us_info_id`, `ka_info_id`, `ru_info_id`, `created_at`, `updated_at`) VALUES
(1, 'info@climbing.loc', '(+995) 598 45 70 48', '<iframe src=\"https://www.google.com/maps/d/embed?mid=1vp_Rgh8UmlN7nZGFGHChh_rRy1t5GdOv\" width=\"100%\" height=\"480\"></iframe>', 1, 2, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `site_social_links`
--

CREATE TABLE `site_social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Структура таблицы `suport_local_bisneses`
--

CREATE TABLE `suport_local_bisneses` (
  `id` bigint UNSIGNED NOT NULL,
  `url_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_data` int DEFAULT NULL,
  `public_totaly` int DEFAULT NULL,
  `article_id` bigint UNSIGNED NOT NULL,
  `us_bisnes_id` bigint UNSIGNED NOT NULL,
  `ka_bisnes_id` bigint UNSIGNED NOT NULL,
  `ru_bisnes_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `suport_local_bisnes_images`
--

CREATE TABLE `suport_local_bisnes_images` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bisnes_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE `topics` (
  `id` bigint UNSIGNED NOT NULL,
  `us_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ru_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ka_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', NULL, NULL, NULL, NULL, NULL, 'us', 'yds', 'admin@climbing.loc', NULL, '$2y$10$yYJ9ozJ11U34MO5UpifStOT8eX24ZthYnMolvoiMSHBUtr/0MvkyK', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_adreses`
--

CREATE TABLE `user_adreses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entrance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `favorite_outdoor` int DEFAULT NULL,
  `favorite_product` int DEFAULT NULL,
  `favorite_film` int DEFAULT NULL,
  `interested_event` int DEFAULT NULL,
  `add_new_sector` int DEFAULT NULL,
  `add_new_outdoor_spot` int DEFAULT NULL,
  `add_new_ice_spot` int DEFAULT NULL,
  `add_new_techtip` int DEFAULT NULL,
  `add_new_gym` int DEFAULT NULL,
  `add_new_product` int DEFAULT NULL,
  `add_new_service` int DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_permission`
--

CREATE TABLE `user_permission` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_requests`
--

CREATE TABLE `user_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `request_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_sites`
--

CREATE TABLE `user_sites` (
  `id` bigint UNSIGNED NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_comments_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `article_comment_complaints`
--
ALTER TABLE `article_comment_complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_comment_complaints_user_id_foreign` (`user_id`),
  ADD KEY `article_comment_complaints_comment_id_foreign` (`comment_id`);

--
-- Индексы таблицы `article_comment_queries`
--
ALTER TABLE `article_comment_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_comment_queries_user_id_foreign` (`user_id`),
  ADD KEY `article_comment_queries_comment_id_foreign` (`comment_id`);

--
-- Индексы таблицы `article_comment_user`
--
ALTER TABLE `article_comment_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_comment_user_user_id_foreign` (`user_id`),
  ADD KEY `article_comment_user_comment_id_foreign` (`comment_id`);

--
-- Индексы таблицы `article_mount`
--
ALTER TABLE `article_mount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_mount_article_id_foreign` (`article_id`),
  ADD KEY `article_mount_mount_id_foreign` (`mount_id`);

--
-- Индексы таблицы `article_region`
--
ALTER TABLE `article_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_region_article_id_foreign` (`article_id`),
  ADD KEY `article_region_region_id_foreign` (`region_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_us_event_id_foreign` (`us_event_id`),
  ADD KEY `events_ka_event_id_foreign` (`ka_event_id`),
  ADD KEY `events_ru_event_id_foreign` (`ru_event_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite_films`
--
ALTER TABLE `favorite_films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorite_films_user_id_foreign` (`user_id`),
  ADD KEY `favorite_films_film_id_foreign` (`film_id`);

--
-- Индексы таблицы `favorite_outdoor_areas`
--
ALTER TABLE `favorite_outdoor_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorite_outdoor_areas_user_id_foreign` (`user_id`),
  ADD KEY `favorite_outdoor_areas_article_id_foreign` (`article_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `films_us_film_id_foreign` (`us_film_id`),
  ADD KEY `films_ka_film_id_foreign` (`ka_film_id`),
  ADD KEY `films_ru_film_id_foreign` (`ru_film_id`);

--
-- Индексы таблицы `film_categories`
--
ALTER TABLE `film_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `film_files`
--
ALTER TABLE `film_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_files_film_id_foreign` (`film_id`);

--
-- Индексы таблицы `film_reitings`
--
ALTER TABLE `film_reitings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_reitings_user_id_foreign` (`user_id`),
  ADD KEY `film_reitings_film_id_foreign` (`film_id`);

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
  ADD KEY `gallery_images_category_id_foreign` (`category_id`);

--
-- Индексы таблицы `gallery_image_article`
--
ALTER TABLE `gallery_image_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_image_article_image_id_foreign` (`image_id`),
  ADD KEY `gallery_image_article_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `general_infos`
--
ALTER TABLE `general_infos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `general_info_article`
--
ALTER TABLE `general_info_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `general_info_article_info_id_foreign` (`info_id`),
  ADD KEY `general_info_article_article_id_foreign` (`article_id`);

--
-- Индексы таблицы `interested_events`
--
ALTER TABLE `interested_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interested_events_event_id_foreign` (`event_id`),
  ADD KEY `interested_events_user_id_foreign` (`user_id`);

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
  ADD KEY `option_images_option_id_foreign` (`option_id`);

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
  ADD KEY `products_category_id_foreign` (`category_id`),
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_comments_user_id_foreign` (`user_id`),
  ADD KEY `product_comments_product_id_foreign` (`product_id`);

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
-- Индексы таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `sector_local_image_sector_sector_id_foreign` (`sector_id`),
  ADD KEY `sector_local_image_sector_image_id_foreign` (`image_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `sites_us_info_id_foreign` (`us_info_id`),
  ADD KEY `sites_ka_info_id_foreign` (`ka_info_id`),
  ADD KEY `sites_ru_info_id_foreign` (`ru_info_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suport_local_bisneses_url_title_unique` (`url_title`),
  ADD KEY `suport_local_bisneses_article_id_foreign` (`article_id`),
  ADD KEY `suport_local_bisneses_us_bisnes_id_foreign` (`us_bisnes_id`),
  ADD KEY `suport_local_bisneses_ka_bisnes_id_foreign` (`ka_bisnes_id`),
  ADD KEY `suport_local_bisneses_ru_bisnes_id_foreign` (`ru_bisnes_id`);

--
-- Индексы таблицы `suport_local_bisnes_images`
--
ALTER TABLE `suport_local_bisnes_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suport_local_bisnes_images_bisnes_id_foreign` (`bisnes_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_permission_user_id_foreign` (`user_id`),
  ADD KEY `user_permission_permission_id_foreign` (`permission_id`);

--
-- Индексы таблицы `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_requests_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `article_comment_complaints`
--
ALTER TABLE `article_comment_complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `article_comment_queries`
--
ALTER TABLE `article_comment_queries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `article_comment_user`
--
ALTER TABLE `article_comment_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `article_mount`
--
ALTER TABLE `article_mount`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `article_region`
--
ALTER TABLE `article_region`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorite_films`
--
ALTER TABLE `favorite_films`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorite_outdoor_areas`
--
ALTER TABLE `favorite_outdoor_areas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `films`
--
ALTER TABLE `films`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `film_categories`
--
ALTER TABLE `film_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `film_files`
--
ALTER TABLE `film_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `film_reitings`
--
ALTER TABLE `film_reitings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `film_tags`
--
ALTER TABLE `film_tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT для таблицы `gallery_image_article`
--
ALTER TABLE `gallery_image_article`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `general_infos`
--
ALTER TABLE `general_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `general_info_article`
--
ALTER TABLE `general_info_article`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `interested_events`
--
ALTER TABLE `interested_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_articles`
--
ALTER TABLE `locale_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT для таблицы `locale_bisneses`
--
ALTER TABLE `locale_bisneses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_events`
--
ALTER TABLE `locale_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_films`
--
ALTER TABLE `locale_films`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_mounts`
--
ALTER TABLE `locale_mounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_products`
--
ALTER TABLE `locale_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT для таблицы `locale_services`
--
ALTER TABLE `locale_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `locale_sites`
--
ALTER TABLE `locale_sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT для таблицы `mounts`
--
ALTER TABLE `mounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mount_route_images`
--
ALTER TABLE `mount_route_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mtps`
--
ALTER TABLE `mtps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `mtp_pitchs`
--
ALTER TABLE `mtp_pitchs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `option_images`
--
ALTER TABLE `option_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_complaints`
--
ALTER TABLE `post_complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `product_comments`
--
ALTER TABLE `product_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=422;

--
-- AUTO_INCREMENT для таблицы `sale_codes`
--
ALTER TABLE `sale_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sectors`
--
ALTER TABLE `sectors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT для таблицы `sector_images`
--
ALTER TABLE `sector_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sector_local_images`
--
ALTER TABLE `sector_local_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sector_local_image_sector`
--
ALTER TABLE `sector_local_image_sector`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `service_followers`
--
ALTER TABLE `service_followers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `service_images`
--
ALTER TABLE `service_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `shiped_countries`
--
ALTER TABLE `shiped_countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `site_social_links`
--
ALTER TABLE `site_social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `spot_rocks_images`
--
ALTER TABLE `spot_rocks_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `suport_local_bisneses`
--
ALTER TABLE `suport_local_bisneses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `suport_local_bisnes_images`
--
ALTER TABLE `suport_local_bisnes_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `user_adreses`
--
ALTER TABLE `user_adreses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_permission`
--
ALTER TABLE `user_permission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_sites`
--
ALTER TABLE `user_sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Ограничения внешнего ключа таблицы `article_comments`
--
ALTER TABLE `article_comments`
  ADD CONSTRAINT `article_comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Ограничения внешнего ключа таблицы `article_comment_complaints`
--
ALTER TABLE `article_comment_complaints`
  ADD CONSTRAINT `article_comment_complaints_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `article_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_comment_complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `article_comment_queries`
--
ALTER TABLE `article_comment_queries`
  ADD CONSTRAINT `article_comment_queries_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `article_comments` (`id`),
  ADD CONSTRAINT `article_comment_queries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `article_comment_user`
--
ALTER TABLE `article_comment_user`
  ADD CONSTRAINT `article_comment_user_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `article_comments` (`id`),
  ADD CONSTRAINT `article_comment_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `article_mount`
--
ALTER TABLE `article_mount`
  ADD CONSTRAINT `article_mount_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_mount_mount_id_foreign` FOREIGN KEY (`mount_id`) REFERENCES `mounts` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `article_region`
--
ALTER TABLE `article_region`
  ADD CONSTRAINT `article_region_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_region_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `product_options` (`id`),
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ka_event_id_foreign` FOREIGN KEY (`ka_event_id`) REFERENCES `locale_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ru_event_id_foreign` FOREIGN KEY (`ru_event_id`) REFERENCES `locale_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_us_event_id_foreign` FOREIGN KEY (`us_event_id`) REFERENCES `locale_events` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite_films`
--
ALTER TABLE `favorite_films`
  ADD CONSTRAINT `favorite_films_film_id_foreign` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_films_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite_outdoor_areas`
--
ALTER TABLE `favorite_outdoor_areas`
  ADD CONSTRAINT `favorite_outdoor_areas_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_outdoor_areas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite_products`
--
ALTER TABLE `favorite_products`
  ADD CONSTRAINT `favorite_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `favorite_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `films_ka_film_id_foreign` FOREIGN KEY (`ka_film_id`) REFERENCES `locale_films` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `films_ru_film_id_foreign` FOREIGN KEY (`ru_film_id`) REFERENCES `locale_films` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `films_us_film_id_foreign` FOREIGN KEY (`us_film_id`) REFERENCES `locale_films` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `film_files`
--
ALTER TABLE `film_files`
  ADD CONSTRAINT `film_files_film_id_foreign` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `film_reitings`
--
ALTER TABLE `film_reitings`
  ADD CONSTRAINT `film_reitings_film_id_foreign` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `film_reitings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `forum_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `gallery_image_article`
--
ALTER TABLE `gallery_image_article`
  ADD CONSTRAINT `gallery_image_article_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `gallery_image_article_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `gallery_images` (`id`);

--
-- Ограничения внешнего ключа таблицы `general_info_article`
--
ALTER TABLE `general_info_article`
  ADD CONSTRAINT `general_info_article_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `general_info_article_info_id_foreign` FOREIGN KEY (`info_id`) REFERENCES `general_infos` (`id`);

--
-- Ограничения внешнего ключа таблицы `interested_events`
--
ALTER TABLE `interested_events`
  ADD CONSTRAINT `interested_events_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `option_images_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_adres_id_foreign` FOREIGN KEY (`adres_id`) REFERENCES `user_adreses` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
  ADD CONSTRAINT `post_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`),
  ADD CONSTRAINT `post_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `post_complaints`
--
ALTER TABLE `post_complaints`
  ADD CONSTRAINT `post_complaints_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`),
  ADD CONSTRAINT `post_complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  ADD CONSTRAINT `products_ka_product_id_foreign` FOREIGN KEY (`ka_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ru_product_id_foreign` FOREIGN KEY (`ru_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_us_product_id_foreign` FOREIGN KEY (`us_product_id`) REFERENCES `locale_products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_comments`
--
ALTER TABLE `product_comments`
  ADD CONSTRAINT `product_comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `product_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sectors`
--
ALTER TABLE `sectors`
  ADD CONSTRAINT `sectors_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Ограничения внешнего ключа таблицы `sector_images`
--
ALTER TABLE `sector_images`
  ADD CONSTRAINT `sector_images_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`);

--
-- Ограничения внешнего ключа таблицы `sector_local_image_sector`
--
ALTER TABLE `sector_local_image_sector`
  ADD CONSTRAINT `sector_local_image_sector_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `sector_local_images` (`id`),
  ADD CONSTRAINT `sector_local_image_sector_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`);

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
-- Ограничения внешнего ключа таблицы `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `sites_ka_info_id_foreign` FOREIGN KEY (`ka_info_id`) REFERENCES `locale_sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sites_ru_info_id_foreign` FOREIGN KEY (`ru_info_id`) REFERENCES `locale_sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sites_us_info_id_foreign` FOREIGN KEY (`us_info_id`) REFERENCES `locale_sites` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `spot_rocks_images`
--
ALTER TABLE `spot_rocks_images`
  ADD CONSTRAINT `spot_rocks_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Ограничения внешнего ключа таблицы `suport_local_bisneses`
--
ALTER TABLE `suport_local_bisneses`
  ADD CONSTRAINT `suport_local_bisneses_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `suport_local_bisneses_ka_bisnes_id_foreign` FOREIGN KEY (`ka_bisnes_id`) REFERENCES `locale_bisneses` (`id`),
  ADD CONSTRAINT `suport_local_bisneses_ru_bisnes_id_foreign` FOREIGN KEY (`ru_bisnes_id`) REFERENCES `locale_bisneses` (`id`),
  ADD CONSTRAINT `suport_local_bisneses_us_bisnes_id_foreign` FOREIGN KEY (`us_bisnes_id`) REFERENCES `locale_bisneses` (`id`);

--
-- Ограничения внешнего ключа таблицы `suport_local_bisnes_images`
--
ALTER TABLE `suport_local_bisnes_images`
  ADD CONSTRAINT `suport_local_bisnes_images_bisnes_id_foreign` FOREIGN KEY (`bisnes_id`) REFERENCES `suport_local_bisneses` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_adreses`
--
ALTER TABLE `user_adreses`
  ADD CONSTRAINT `user_adreses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
-- Ограничения внешнего ключа таблицы `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_permission`
--
ALTER TABLE `user_permission`
  ADD CONSTRAINT `user_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permission_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_requests`
--
ALTER TABLE `user_requests`
  ADD CONSTRAINT `user_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_sites`
--
ALTER TABLE `user_sites`
  ADD CONSTRAINT `user_sites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
