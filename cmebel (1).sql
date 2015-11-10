-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 10 2015 г., 13:26
-- Версия сервера: 5.6.23
-- Версия PHP: 5.6.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cmebel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(63) NOT NULL,
  `email` varchar(127) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='клиенты магазина';

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `email`) VALUES
(1, 'Берлинский Алексе', '+79003562799', 'al.berlinskiy@yandex.ru'),
(2, 'Иванов Олег', '+79807896655', 'oleg@mail.ru'),
(3, 'Лонова Анна', '+74832567434', 'anna@ya.ru'),
(5, 'Князев Светодар', '+79003456789', 'svet@yandex.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1446465997),
('m140209_132017_init', 1446466006),
('m140403_174025_create_account_table', 1446466008),
('m140504_113157_update_tables', 1446466014),
('m140504_130429_create_token_table', 1446466016),
('m140830_171933_fix_ip_field', 1446466017),
('m140830_172703_change_account_table_name', 1446466017),
('m141222_110026_update_ip_field', 1446466018),
('m141222_135246_alter_username_length', 1446466019),
('m150614_103145_update_social_account_table', 1446466022),
('m150623_212711_fix_username_notnull', 1446466022),
('m151104_081257_edit_profile_table', 1446625285),
('m151104_082546_edit_profile_table_new', 1446625572),
('m151104_083433_drop_columns_profile_table', 1446626175);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL,
  `order_opencart_id` int(10) unsigned NOT NULL COMMENT 'id заказа из opencart',
  `version` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'версия',
  `client_id` int(10) unsigned NOT NULL COMMENT 'клиент',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `total` decimal(10,2) unsigned NOT NULL COMMENT 'итого',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_modified` datetime NOT NULL COMMENT 'дата изменения'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='заказы';

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `order_opencart_id`, `version`, `client_id`, `status`, `total`, `date_added`, `date_modified`) VALUES
(5, 1, 1, 1, 1, '1707.00', '2015-11-04 18:32:21', '2015-11-04 18:32:24'),
(6, 2, 1, 2, 1, '105.00', '2015-11-06 15:54:43', '2015-11-06 15:54:46'),
(7, 3, 1, 3, 1, '85.00', '2015-11-06 15:56:08', '2015-11-06 15:56:10'),
(8, 4, 1, 5, 1, '106.00', '2015-11-06 15:58:11', '2015-11-06 15:58:12');

-- --------------------------------------------------------

--
-- Структура таблицы `order_files`
--

CREATE TABLE IF NOT EXISTS `order_files` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'название файла',
  `order_id` int(10) unsigned NOT NULL COMMENT 'id заказа',
  `file` varchar(255) NOT NULL COMMENT 'путь к файлу'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='файлы к заказам';

-- --------------------------------------------------------

--
-- Структура таблицы `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL COMMENT 'id заказа',
  `product_id` int(10) unsigned NOT NULL COMMENT 'id товара',
  `quantity` smallint(5) unsigned NOT NULL COMMENT 'количество'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товары заказов';

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE IF NOT EXISTS `order_status` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='статусы заказов';

-- --------------------------------------------------------

--
-- Структура таблицы `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `profile`
--

INSERT INTO `profile` (`user_id`, `name`, `phone`) VALUES
(1, NULL, NULL),
(2, 'Берлинский Алексей Васильевич', '+7(900) 356-27-99');

-- --------------------------------------------------------

--
-- Структура таблицы `shop_categories`
--

CREATE TABLE IF NOT EXISTS `shop_categories` (
  `id` int(10) unsigned NOT NULL,
  `opencart_id` int(10) unsigned DEFAULT NULL COMMENT 'ID из интернет-магазина',
  `name` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id родительской категории',
  `status` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `shop_categories`
--

INSERT INTO `shop_categories` (`id`, `opencart_id`, `name`, `parent_id`, `status`) VALUES
(1, 25, 'Компоненты', 0, 1),
(2, 27, 'Mac', 3, 1),
(3, 20, 'Компьютеры', 0, 1),
(4, 24, 'Телефоны и PDA', 0, 0),
(5, 18, 'Ноутбуки', 0, 1),
(6, 17, 'Програмное обеспечение', 0, 1),
(7, 28, 'Мониторы', 1, 1),
(8, 26, 'PC', 3, 1),
(9, 29, 'Мышки', 1, 1),
(10, 30, 'Принтеры', 1, 1),
(11, 31, 'Сканеры', 1, 1),
(12, 32, 'Веб-камеры', 1, 1),
(13, 33, 'Камеры', 0, 1),
(14, 34, 'MP3 Плееры', 0, 1),
(15, 35, 'test 1', 7, 1),
(16, 36, 'test 2', 7, 1),
(17, 37, 'test 5', 14, 1),
(18, 38, 'test 4', 14, 1),
(19, 39, 'test 6', 14, 1),
(20, 40, 'test 7', 14, 1),
(21, 41, 'test 8', 14, 1),
(22, 42, 'test 9', 14, 1),
(23, 43, 'test 11', 14, 1),
(24, 44, 'test 12', 14, 1),
(25, 45, 'Windows', 5, 1),
(26, 46, 'Macs', 5, 1),
(27, 47, 'test 15', 14, 1),
(28, 48, 'test 16', 14, 1),
(29, 49, 'test 17', 14, 1),
(30, 50, 'test 18', 14, 1),
(31, 51, 'test 19', 14, 1),
(32, 52, 'test 20', 14, 1),
(33, 53, 'test 21', 14, 1),
(34, 54, 'test 22', 14, 1),
(35, 55, 'test 23', 14, 1),
(36, 56, 'test 24', 14, 1),
(37, 57, 'Планшеты', 0, 1),
(38, 58, 'test 25', 32, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `shop_products`
--

CREATE TABLE IF NOT EXISTS `shop_products` (
  `id` int(10) unsigned NOT NULL,
  `opencart_id` int(10) unsigned DEFAULT NULL COMMENT 'ID из интернет-магазина',
  `name` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL COMMENT 'название модели',
  `category_id` int(10) unsigned NOT NULL COMMENT 'категория',
  `price` decimal(10,2) NOT NULL COMMENT 'цена',
  `quantity` int(10) unsigned NOT NULL COMMENT 'количество',
  `image` varchar(255) DEFAULT NULL COMMENT 'фото',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_modified` datetime NOT NULL COMMENT 'дата редактирования'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='товары';

--
-- Дамп данных таблицы `shop_products`
--

INSERT INTO `shop_products` (`id`, `opencart_id`, `name`, `model`, `category_id`, `price`, `quantity`, `image`, `status`, `date_added`, `date_modified`) VALUES
(1, 43, 'MacBook', 'Товар 16', 5, '500.00', 929, 'catalog/demo/macbook_1.jpg', 0, '2009-02-03 21:07:49', '2011-09-30 01:05:46'),
(2, 47, 'HP LP3065', 'Товар 21', 5, '100.00', 1001, 'catalog/demo/hp_1.jpg', 0, '2009-02-03 21:08:40', '2015-11-10 10:20:05'),
(3, 46, 'Sony VAIO', 'Товар 19', 5, '1000.00', 1000, 'catalog/demo/sony_vaio_1.jpg', 1, '2009-02-03 21:08:29', '2011-09-30 01:06:39'),
(4, 45, 'MacBook Pro', 'Товар 18', 5, '2000.00', 998, 'catalog/demo/macbook_pro_1.jpg', 1, '2009-02-03 21:08:17', '2011-09-15 22:22:01'),
(5, 44, 'MacBook Air', 'Товар 17', 5, '1000.00', 1000, 'catalog/demo/macbook_air_1.jpg', 1, '2009-02-03 21:08:00', '2011-09-30 01:05:53'),
(6, 28, 'HTC Touch HD', 'Товар 1', 3, '100.00', 939, 'catalog/demo/htc_touch_hd_1.jpg', 1, '2009-02-03 16:06:50', '2011-09-30 01:05:39'),
(7, 33, 'Samsung SyncMaster 941BW', 'Товар 6', 3, '200.00', 1000, 'catalog/demo/samsung_syncmaster_941bw.jpg', 1, '2009-02-03 17:08:31', '2011-09-30 01:06:29'),
(8, 42, 'Apple Cinema 30&quot;', 'Товар 15', 3, '100.00', 990, 'catalog/demo/apple_cinema_30.jpg', 1, '2009-02-03 21:07:37', '2011-09-30 00:46:19'),
(9, 48, 'iPod Classic', 'Товар 20', 3, '100.00', 995, 'catalog/demo/ipod_classic_1.jpg', 1, '2009-02-08 17:21:51', '2011-09-30 01:07:06'),
(10, 40, 'iPhone', 'Товар 11', 3, '101.00', 970, 'catalog/demo/iphone_1.jpg', 1, '2009-02-03 21:07:12', '2011-09-30 01:06:53'),
(11, 30, 'Canon EOS 5D', 'Товар 3', 3, '100.00', 7, 'catalog/demo/canon_eos_5d_1.jpg', 1, '2009-02-03 16:59:00', '2011-09-30 01:05:23'),
(12, 29, 'Palm Treo Pro', 'Товар 2', 3, '279.99', 999, 'catalog/demo/palm_treo_pro_1.jpg', 1, '2009-02-03 16:42:17', '2011-09-30 01:06:08'),
(13, 35, 'Product 8', 'Товар 8', 3, '100.00', 1000, '', 1, '2009-02-03 18:08:31', '2011-09-30 01:06:17'),
(14, 41, 'iMac', 'Товар 14', 2, '100.00', 977, 'catalog/demo/imac_1.jpg', 1, '2009-02-03 21:07:26', '2011-09-30 01:06:44'),
(15, 31, 'Nikon D300', 'Товар 4', 13, '80.00', 1000, 'catalog/demo/nikon_d300_1.jpg', 1, '2009-02-03 17:00:10', '2011-09-30 01:06:00'),
(16, 34, 'iPod Shuffle', 'Товар 7', 14, '100.00', 1000, 'catalog/demo/ipod_shuffle_1.jpg', 1, '2009-02-03 18:07:54', '2011-09-30 01:07:17'),
(17, 32, 'iPod Touch', 'Товар 5', 14, '100.00', 999, 'catalog/demo/ipod_touch_1.jpg', 1, '2009-02-03 17:07:26', '2011-09-30 01:07:22'),
(18, 36, 'iPod Nano', 'Товар 9', 14, '100.00', 994, 'catalog/demo/ipod_nano_1.jpg', 1, '2009-02-03 18:09:19', '2011-09-30 01:07:12'),
(19, 49, 'Samsung Galaxy Tab 10.1', 'SAM1', 37, '199.99', 0, 'catalog/demo/samsung_tab_1.jpg', 1, '2011-04-26 08:57:34', '2011-09-30 01:06:23');

-- --------------------------------------------------------

--
-- Структура таблицы `social_account`
--

CREATE TABLE IF NOT EXISTS `social_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL,
  `user_id` smallint(5) unsigned NOT NULL COMMENT 'исполнитель',
  `order_opencart_id` int(10) unsigned NOT NULL COMMENT 'id заказа из opencart',
  `serial_number` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'порядковый номер в рамках заказа',
  `text` text NOT NULL COMMENT 'формулировка',
  `comment` text NOT NULL COMMENT 'комментарий',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_start` datetime NOT NULL COMMENT 'дата начала',
  `date_end` datetime NOT NULL COMMENT 'дата окончания',
  `date_closed` datetime NOT NULL COMMENT 'дата закрытия'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='задачи';

-- --------------------------------------------------------

--
-- Структура таблицы `task_files`
--

CREATE TABLE IF NOT EXISTS `task_files` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'название файла',
  `task_id` int(10) unsigned NOT NULL COMMENT 'id задачи',
  `file` varchar(255) NOT NULL COMMENT 'путь к файлу'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='файлы к задачам';

-- --------------------------------------------------------

--
-- Структура таблицы `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`) VALUES
(1, 'admin', 'al.berlinskiy@ya.ru', '$2y$10$i14LrxW3OQQFWNbLbw4AQeREGm9Hy6apRiWZ7vbFf.GNz01XewdZq', '8yeCpIhNOcFyPhZuBiXfiKvzS_SaFctj', 1446467552, NULL, NULL, '127.0.0.1', 1446467553, 1446623128, 0),
(2, 'manager', 'aleksey203@gmail.com', '$2y$10$wMJMK7re9muVtWYBCrCF6Osi1mq8tjutnGk1KhYlP/pfraAgPkM7O', 'v0dOMkTHl7ojPnyHQSMCD2epibkIVZ2a', 1446622109, NULL, NULL, '127.0.0.1', 1446622109, 1446629047, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `order_opencart_id` (`order_opencart_id`,`version`);

--
-- Индексы таблицы `order_files`
--
ALTER TABLE `order_files`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `order_id` (`order_id`,`file`);

--
-- Индексы таблицы `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `order_id` (`order_id`,`product_id`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop_products`
--
ALTER TABLE `shop_products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `social_account`
--
ALTER TABLE `social_account`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `account_unique` (`provider`,`client_id`), ADD UNIQUE KEY `account_unique_code` (`code`), ADD KEY `fk_user_account` (`user_id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_id` (`user_id`,`order_opencart_id`,`serial_number`);

--
-- Индексы таблицы `task_files`
--
ALTER TABLE `task_files`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `order_id` (`task_id`,`file`);

--
-- Индексы таблицы `token`
--
ALTER TABLE `token`
  ADD UNIQUE KEY `token_unique` (`user_id`,`code`,`type`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_unique_email` (`email`), ADD UNIQUE KEY `user_unique_username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `order_files`
--
ALTER TABLE `order_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `shop_categories`
--
ALTER TABLE `shop_categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT для таблицы `shop_products`
--
ALTER TABLE `shop_products`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT для таблицы `social_account`
--
ALTER TABLE `social_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `task_files`
--
ALTER TABLE `task_files`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `profile`
--
ALTER TABLE `profile`
ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `social_account`
--
ALTER TABLE `social_account`
ADD CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `token`
--
ALTER TABLE `token`
ADD CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
