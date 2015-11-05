-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 05 2015 г., 19:22
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='клиенты магазина';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='заказы';

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
  `name` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id родительской категории',
  `sort_order` int(10) unsigned NOT NULL COMMENT 'сортировка внутри родительской категории',
  `status` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `shop_products`
--

CREATE TABLE IF NOT EXISTS `shop_products` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL COMMENT 'название модели',
  `category_id` int(10) unsigned NOT NULL COMMENT 'категория',
  `price` decimal(10,2) NOT NULL COMMENT 'цена',
  `quantity` int(10) unsigned NOT NULL COMMENT 'количество',
  `image` varchar(255) DEFAULT NULL COMMENT 'фото',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL COMMENT 'дата добавления',
  `date_modified` datetime NOT NULL COMMENT 'дата редактирования'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='товары';

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `shop_products`
--
ALTER TABLE `shop_products`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
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
