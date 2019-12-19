-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 19 2019 г., 19:03
-- Версия сервера: 5.7.25-log
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `host1777291`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addition`
--

CREATE TABLE `addition` (
  `id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Название',
  `cost` decimal(12,2) NOT NULL COMMENT 'Стоимость',
  `about` text COMMENT 'Описание',
  `type` tinyint(1) NOT NULL COMMENT 'Тип'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `addition`
--

INSERT INTO `addition` (`id`, `created_at`, `updated_at`, `name`, `cost`, `about`, `type`) VALUES
(1, 1565016015, 1565016015, 'POS терминал', '900.00', '', 1),
(2, 1565016383, 1565016383, 'Кассовый аппарат', '1200.00', '', 0),
(3, 1567082635, 1567082635, 'Доп. услуга 1', '250.00', '', 1),
(4, 1567082645, 1567082667, 'Доп. услуга 2', '500.00', '', 0),
(5, 1567082657, 1567599301, 'Доп. услуга 3', '750.00', '', 1),
(6, 1567082682, 1567599071, 'Доп. услуга 4', '1050.00', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `message_to_paid`
--

CREATE TABLE `message_to_paid` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` tinyint(1) NOT NULL COMMENT 'Услуга',
  `service_id` int(11) NOT NULL COMMENT 'ID Услуги',
  `date_to_paid` date NOT NULL COMMENT 'Дата оплаты',
  `amount` decimal(12,2) NOT NULL COMMENT 'Сумма',
  `debtor` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Должник'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_to_paid`
--

INSERT INTO `message_to_paid` (`id`, `user_id`, `service_type`, `service_id`, `date_to_paid`, `amount`, `debtor`) VALUES
(7, 2, 1, 40, '2019-11-10', '2600.00', 0),
(53, 2, 2, 104, '2019-10-12', '0.00', 0),
(54, 2, 2, 105, '2019-10-12', '900.00', 0),
(55, 2, 2, 106, '2019-10-15', '0.00', 0),
(67, 2, 2, 118, '2019-10-13', '250.00', 0),
(68, 2, 2, 119, '2019-10-14', '250.00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1557563360),
('m180819_140916_create_user_table', 1557563362),
('m180819_150916_create_user_settings_table', 1557920878),
('m190729_160402_create_tariff_table', 1564417622),
('m190730_124619_create_shops_table', 1564503073),
('m190731_170525_create_addition_table', 1564657657),
('m190731_173534_create_junction_table_for_tariff_and_addition_tables', 1564658044),
('m190803_144302_create_junction_table_for_shops_and_addition_tables', 1564843583),
('m190808_112601_create_service_table', 1565264950),
('m190808_114201_create_payments_table', 1565264950),
('m190813_152133_create_tickets_table', 1565711018),
('m190813_152150_create_tickets_text_table', 1565711019),
('m190813_152601_create_tickets_files_table', 1565711020),
('m190817_133112_create_transaction_table', 1566049008),
('m190827_140040_create_message_to_paid_table', 1566914800),
('m190828_095244_add_column_old_service_id_to_service_table', 1566995386),
('m190828_151133_create_tariff_addition_quantity_table', 1567007206),
('m190830_155039_add_index_to_db', 1567346428);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID Бренда',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID Магазина',
  `type_service` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Тип услуги',
  `service_id` int(11) NOT NULL DEFAULT '0' COMMENT 'ID услуги',
  `type` tinyint(1) NOT NULL COMMENT 'Тип операции',
  `way` tinyint(1) NOT NULL COMMENT 'Способ оплаты',
  `date` date NOT NULL COMMENT 'Дата платежа',
  `invoice_number` int(11) DEFAULT NULL COMMENT 'Номер счета',
  `invoice_date` date NOT NULL COMMENT 'Дата выставления счета',
  `amount` decimal(12,2) NOT NULL COMMENT 'Сумма',
  `description` text COMMENT 'Описание',
  `status` tinyint(1) NOT NULL COMMENT 'Статус платежа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `shop_id`, `type_service`, `service_id`, `type`, `way`, `date`, `invoice_number`, `invoice_date`, `amount`, `description`, `status`) VALUES
(1, 2, 12, 1, 4, 0, 2, '2019-08-09', NULL, '2019-08-09', '1900.00', 'Списание с баланса оплаты за тариф', 1),
(11, 2, 12, 2, 2, 0, 2, '2019-08-16', NULL, '2019-08-16', '4800.00', 'Списание с баланса оплаты за услугу', 1),
(12, 2, 12, 0, 0, 1, 0, '2019-08-09', NULL, '2019-08-09', '200.00', 'Пополнение баланса с карты', 1),
(14, 2, 0, 0, 0, 1, 0, '2019-08-17', NULL, '2019-08-17', '300.00', 'Пополнение баланса с карты', 1),
(15, 2, 0, 0, 0, 1, 0, '2019-08-17', NULL, '2019-08-17', '400.00', 'Пополнение баланса с карты', 1),
(16, 2, 0, 0, 0, 1, 0, '2019-08-17', NULL, '2019-08-17', '500.00', 'Пополнение баланса с карты', 1),
(45, 2, 0, 0, 0, 1, 1, '2019-10-09', 10, '2019-08-28', '200.00', 'Пополнение баланса через счет', 1),
(48, 2, 12, 1, 4, 0, 2, '2019-09-04', NULL, '2019-09-04', '1900.00', 'Списание с баланса оплаты за тариф', 1),
(50, 2, 0, 0, 0, 1, 0, '2019-09-04', NULL, '2019-09-04', '5000.00', 'Пополнение баланса с карты', 1),
(51, 2, 0, 0, 0, 1, 0, '2019-09-04', NULL, '2019-09-04', '5000.00', 'Пополнение баланса с карты', 1),
(52, 2, 12, 1, 5, 0, 2, '2019-09-04', NULL, '2019-09-04', '2600.00', 'Списание с баланса оплаты за тариф', 1),
(55, 2, 12, 2, 4, 0, 2, '2019-09-04', NULL, '2019-09-04', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(56, 2, 12, 2, 4, 0, 2, '2019-09-04', NULL, '2019-09-04', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(57, 2, 12, 2, 6, 0, 2, '2019-09-04', NULL, '2019-09-04', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(58, 2, 12, 2, 5, 0, 2, '2019-09-04', NULL, '2019-09-04', '750.00', 'Списание с баланса оплаты за доп. услугу', 1),
(59, 2, 12, 2, 3, 0, 2, '2019-09-04', NULL, '2019-09-04', '250.00', 'Списание с баланса оплаты за доп. услугу', 1),
(60, 2, 12, 2, 3, 0, 2, '2019-09-04', NULL, '2019-09-04', '250.00', 'Списание с баланса оплаты за доп. услугу', 1),
(61, 2, 12, 2, 2, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(62, 2, 12, 2, 2, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(63, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(64, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(65, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '750.00', 'Списание с баланса оплаты за доп. услугу', 1),
(66, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '750.00', 'Списание с баланса оплаты за доп. услугу', 1),
(67, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '750.00', 'Списание с баланса оплаты за доп. услугу', 1),
(68, 2, 12, 2, 4, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(69, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(70, 2, 12, 2, 5, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(71, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(72, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(73, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(74, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(75, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(76, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(77, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(78, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(79, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(80, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(81, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(82, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(83, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '1050.00', 'Списание с баланса оплаты за доп. услугу', 1),
(84, 2, 12, 2, 6, 0, 2, '2019-09-05', NULL, '2019-09-05', '0.00', 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу', 1),
(85, 2, 0, 0, 0, 1, 1, '2019-10-09', 11, '2019-10-09', '5000.00', 'Пополнение баланса через счет', 1),
(86, 2, 12, 1, 5, 0, 2, '2019-10-11', NULL, '2019-10-11', '2600.00', 'Списание с баланса оплаты за тариф', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID Бренда',
  `shop_id` int(11) NOT NULL COMMENT 'ID Магазина',
  `type_service` tinyint(1) NOT NULL COMMENT 'Тип услуги',
  `type_serviceId` int(11) NOT NULL COMMENT 'ID Услуги на которую планируется списание',
  `connection_date` date NOT NULL COMMENT 'Дата подключения',
  `writeoff_date` date NOT NULL COMMENT 'Дата списания',
  `writeoff_amount` decimal(12,2) NOT NULL COMMENT 'Цена списания',
  `agree` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Подтверждение',
  `repeat_service` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Повторяющийся',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Удален',
  `old_service_id` int(11) DEFAULT NULL COMMENT 'Старый ID Услуги',
  `old_connection_date` date DEFAULT NULL COMMENT 'Старая дата подключения',
  `old_writeoff_date` date DEFAULT NULL COMMENT 'Старая дата списания',
  `old_writeoff_amount` decimal(12,2) DEFAULT NULL COMMENT 'Старая сумма списания',
  `edit_description` varchar(255) DEFAULT NULL COMMENT 'Описание изменения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `user_id`, `shop_id`, `type_service`, `type_serviceId`, `connection_date`, `writeoff_date`, `writeoff_amount`, `agree`, `repeat_service`, `deleted`, `old_service_id`, `old_connection_date`, `old_writeoff_date`, `old_writeoff_amount`, `edit_description`) VALUES
(40, 2, 12, 1, 5, '2019-09-04', '2019-11-10', '2600.00', 1, 1, 0, 4, '2019-09-04', '2019-10-04', '1900.00', 'Изменение тарифа'),
(104, 2, 12, 2, 1, '2019-09-05', '2019-10-12', '0.00', 1, 1, 0, NULL, NULL, NULL, '0.00', 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа'),
(105, 2, 12, 2, 1, '2019-09-05', '2019-10-12', '900.00', 1, 1, 0, NULL, NULL, NULL, NULL, 'Добавление услуги'),
(106, 2, 12, 2, 2, '2019-09-05', '2019-10-13', '0.00', 1, 0, 0, NULL, NULL, NULL, '0.00', 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа'),
(118, 2, 12, 2, 3, '2019-09-05', '2019-10-13', '250.00', 1, 1, 0, NULL, NULL, NULL, NULL, 'Добавление услуги'),
(119, 2, 12, 2, 3, '2019-09-05', '2019-10-14', '250.00', 1, 1, 0, NULL, NULL, NULL, NULL, 'Добавление услуги');

-- --------------------------------------------------------

--
-- Структура таблицы `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `address` varchar(255) NOT NULL COMMENT 'Адрес магазина',
  `tariff_id` int(11) NOT NULL COMMENT 'Привязка к тарифу',
  `user_id` int(11) NOT NULL COMMENT 'Привязка к бренду',
  `on_check` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'На проверке',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Удален'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `shops`
--

INSERT INTO `shops` (`id`, `created_at`, `updated_at`, `address`, `tariff_id`, `user_id`, `on_check`, `deleted`) VALUES
(12, 1565096569, 1566828769, 'Адрес нового магазина', 5, 2, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `shops_addition`
--

CREATE TABLE `shops_addition` (
  `shop_id` int(11) NOT NULL,
  `addition_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT '1' COMMENT 'Количество'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `shops_addition`
--

INSERT INTO `shops_addition` (`shop_id`, `addition_id`, `quantity`) VALUES
(12, 1, 2),
(12, 2, 1),
(12, 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `tariff`
--

CREATE TABLE `tariff` (
  `id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost` decimal(12,2) NOT NULL COMMENT 'Стоимость обслуживания (ежемесячно)',
  `about` text COMMENT 'Описание',
  `drop` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Запрещает подключать тариф хуже ',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Статус',
  `maximum` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Максимальный тариф',
  `term` int(11) DEFAULT NULL COMMENT 'Промо тариф'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariff`
--

INSERT INTO `tariff` (`id`, `created_at`, `updated_at`, `name`, `cost`, `about`, `drop`, `status`, `maximum`, `term`) VALUES
(2, 1564749452, 1564833246, 'Light', '900.00', '', 0, 1, 0, 20),
(3, 1564749482, 1564833260, 'Medium', '1500.00', '', 1, 1, 0, NULL),
(4, 1564749503, 1568025592, 'Maxi', '1900.00', '<p>Прекрасный тариф для старта бизнеса.</p>\r\n\r\n<p><em>Во-первых:</em></p>\r\n\r\n<ol>\r\n	<li>раз</li>\r\n	<li>два</li>\r\n	<li>три</li>\r\n</ol>\r\n\r\n<p><strong>Во-вторых:</strong></p>\r\n\r\n<ul>\r\n	<li>раз</li>\r\n	<li>два</li>\r\n	<li>три</li>\r\n</ul>\r\n', 0, 1, 0, NULL),
(5, 1564749532, 1567701947, 'Ultimate', '2600.00', '', 0, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tariff_addition`
--

CREATE TABLE `tariff_addition` (
  `tariff_id` int(11) NOT NULL,
  `addition_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL COMMENT 'Количество которое можно подключить в данном тарифе'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariff_addition`
--

INSERT INTO `tariff_addition` (`tariff_id`, `addition_id`, `quantity`) VALUES
(4, 1, 2),
(4, 2, 2),
(5, 1, 1),
(5, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tariff_addition_quantity`
--

CREATE TABLE `tariff_addition_quantity` (
  `tariff_id` int(11) NOT NULL,
  `addition_id` int(11) NOT NULL,
  `status_con` int(11) DEFAULT NULL COMMENT 'Количество которое можно подключить в данном тарифе'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariff_addition_quantity`
--

INSERT INTO `tariff_addition_quantity` (`tariff_id`, `addition_id`, `status_con`) VALUES
(4, 1, 10),
(4, 2, 10),
(5, 1, 2),
(5, 3, 3),
(5, 4, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID Бренда',
  `subject` varchar(255) NOT NULL COMMENT 'Тема',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Открыто_закрыто обращение',
  `new_text` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Есть новое сообщение'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `subject`, `status`, `new_text`) VALUES
(6, 2, 'Тема обращения', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tickets_files`
--

CREATE TABLE `tickets_files` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL COMMENT 'ID Обращения',
  `ticket_text_id` int(11) NOT NULL COMMENT 'ID Обращения текста',
  `type_file` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Тип файла',
  `file` varchar(255) NOT NULL COMMENT 'Файл',
  `name_file` varchar(255) NOT NULL COMMENT 'Имя файла'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `tickets_files`
--

INSERT INTO `tickets_files` (`id`, `ticket_id`, `ticket_text_id`, `type_file`, `file`, `name_file`) VALUES
(20, 6, 49, 0, 'upload/tickets/1.jpg', '1'),
(21, 6, 49, 0, 'upload/tickets/czKkHNaZE9Q.jpg', 'czKkHNaZE9Q'),
(22, 6, 50, 0, 'upload/tickets/GkGqCBFdank.jpg', 'GkGqCBFdank'),
(23, 6, 51, 0, 'upload/tickets/1.jpg', '1'),
(24, 6, 51, 0, 'upload/tickets/czKkHNaZE9Q.jpg', 'czKkHNaZE9Q'),
(25, 6, 51, 0, 'upload/tickets/wovpT8eESiQ.jpg', 'wovpT8eESiQ'),
(26, 6, 55, 0, 'upload/tickets/1566824292.jpg', '1566824292'),
(27, 6, 55, 1, 'upload/tickets/Pravila_oformleniya.doc', 'Pravila_oformleniya'),
(28, 6, 65, 0, 'upload/tickets/1570009295.jpg', '1570009295.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets_text`
--

CREATE TABLE `tickets_text` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL COMMENT 'ID Обращения',
  `date_time` datetime NOT NULL COMMENT 'Дата и вермя',
  `text` text NOT NULL COMMENT 'Текст',
  `user_type` int(1) NOT NULL COMMENT 'Тип пользователя'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tickets_text`
--

INSERT INTO `tickets_text` (`id`, `ticket_id`, `date_time`, `text`, `user_type`) VALUES
(6, 6, '2019-08-19 19:14:08', 'Текст Текст Текст Текст Текст Текст Текст Текст Текст Текст Текст Текст Текст ', 0),
(8, 6, '2019-08-20 18:18:47', 'Тут снова много текста, очень много текста', 0),
(26, 6, '2019-08-21 21:35:56', 'Очень много текста', 0),
(49, 6, '2019-08-21 22:28:57', 'Должны быть фото', 0),
(50, 6, '2019-08-21 22:32:39', 'Снова фото', 0),
(51, 6, '2019-08-21 22:33:27', 'А теперь 3 фото\r\nФото Фото Фото Фото Фото Фото Фото Фото Фото Фото Фото Фото Фото', 0),
(55, 6, '2019-08-26 15:58:12', 'Еще одна картинка и файл', 0),
(63, 6, '2019-08-28 10:45:23', 'Текст', 0),
(64, 6, '2019-10-02 09:32:13', 'Ответ тех. потдержки', 1),
(65, 6, '2019-10-02 09:41:35', 'Еще какое-то сообщение от тех. потдержки', 1),
(66, 6, '2019-10-02 09:44:01', 'И еще одно сообщение', 1),
(67, 6, '2019-10-09 10:34:37', 'Ответ', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `transaction`
--

CREATE TABLE `transaction` (
  `user_id` int(11) NOT NULL COMMENT 'ID Бренда',
  `transaction_id` varchar(255) NOT NULL COMMENT 'ID Транзации',
  `payment_id` int(11) NOT NULL COMMENT 'ID Зачисления',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL COMMENT 'E-mail',
  `phone` varchar(255) NOT NULL COMMENT 'Телефон',
  `company_name` varchar(255) NOT NULL COMMENT 'Название бренда',
  `password_hash` varchar(255) NOT NULL COMMENT 'Пароль пользователя',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT 'Токен на восстановление пароля',
  `auth_key` varchar(32) NOT NULL COMMENT 'Уникальный ключ авторизации',
  `status` tinyint(6) DEFAULT NULL COMMENT 'Статус пользователя',
  `avatar` varchar(255) NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Баланс',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Роль'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `phone`, `company_name`, `password_hash`, `password_reset_token`, `auth_key`, `status`, `avatar`, `balance`, `created_at`, `updated_at`, `role`) VALUES
(2, 'admin@admin.ru', '+7 (912) 999-88-77', 'Бренд', '$2y$13$W0NSO7azVEDSX0AJ.v/H3OqDiWxbTfPcfUHQ0Xt05G0tBOEQH45eu', 'xVf8YrFWP1IfwEt9m8HvkkK2TIEl5Aff_1566578374', 'Hb7PV62nbCmWgw_-DwR0-tjgZW46qdOs', 10, 'upload/user/1566574848.jpg', '4550.00', 1557912292, 1576760948, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID пользователя',
  `doc_num` varchar(255) DEFAULT NULL COMMENT 'Номер договора',
  `type_org` varchar(255) DEFAULT NULL COMMENT 'Тип организации',
  `name_org` varchar(255) DEFAULT NULL COMMENT 'Название организации',
  `ur_addr_org` text COMMENT 'Юр адрес организации',
  `ogrn` varchar(255) DEFAULT NULL COMMENT 'ОГРН',
  `inn` varchar(255) DEFAULT NULL COMMENT 'ИНН',
  `kpp` varchar(255) DEFAULT NULL COMMENT 'КПП',
  `bik_banka` varchar(255) DEFAULT NULL COMMENT 'БИК Банка',
  `name_bank` varchar(255) DEFAULT NULL COMMENT 'Название банка',
  `kor_schet` varchar(255) DEFAULT NULL COMMENT 'Кор счет',
  `rass_schet` varchar(255) DEFAULT NULL COMMENT 'Рассчетный счет'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `doc_num`, `type_org`, `name_org`, `ur_addr_org`, `ogrn`, `inn`, `kpp`, `bik_banka`, `name_bank`, `kor_schet`, `rass_schet`) VALUES
(1, 2, '359359248', 'ooo', 'ООО \"АКАДЕМИЯ ВИСКИ\"', '190000, ГОРОД САНКТ-ПЕТЕРБУРГ, УЛИЦА КАЗАНСКАЯ, ДОМ 45, ЛИТЕРА ЛИТ. А, ПОМЕЩЕНИЕ 13Н', '1107847204310', '7838445593', '783801001', '044030904', 'ООО БАНК ОРАНЖЕВЫЙ', '30101810000000000904', '4865248643579845');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `addition`
--
ALTER TABLE `addition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index-addition-id` (`id`);

--
-- Индексы таблицы `message_to_paid`
--
ALTER TABLE `message_to_paid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messageUserId` (`user_id`),
  ADD KEY `messageServiceId` (`service_id`),
  ADD KEY `index-message_to_paid-id` (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paymentsUserId` (`user_id`),
  ADD KEY `paymentsShopId` (`shop_id`),
  ADD KEY `index-payments-id` (`id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serviceUserId` (`user_id`),
  ADD KEY `index-service-id` (`id`),
  ADD KEY `serviceShopId` (`shop_id`);

--
-- Индексы таблицы `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shopsUserId` (`user_id`),
  ADD KEY `shopsTariffId` (`tariff_id`),
  ADD KEY `index-shops-id` (`id`);

--
-- Индексы таблицы `shops_addition`
--
ALTER TABLE `shops_addition`
  ADD PRIMARY KEY (`shop_id`,`addition_id`),
  ADD KEY `idx-shops_addition-shop_id` (`shop_id`),
  ADD KEY `idx-shops_addition-addition_id` (`addition_id`);

--
-- Индексы таблицы `tariff`
--
ALTER TABLE `tariff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index-tariff-id` (`id`);

--
-- Индексы таблицы `tariff_addition`
--
ALTER TABLE `tariff_addition`
  ADD PRIMARY KEY (`tariff_id`,`addition_id`),
  ADD KEY `idx-tariff_addition-tariff_id` (`tariff_id`),
  ADD KEY `idx-tariff_addition-addition_id` (`addition_id`);

--
-- Индексы таблицы `tariff_addition_quantity`
--
ALTER TABLE `tariff_addition_quantity`
  ADD PRIMARY KEY (`tariff_id`,`addition_id`),
  ADD KEY `idx-tariff_addition_quantity-tariff_id` (`tariff_id`),
  ADD KEY `idx-tariff_addition_quantity-addition_id` (`addition_id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticketsUserId` (`user_id`),
  ADD KEY `index-tickets-id` (`id`);

--
-- Индексы таблицы `tickets_files`
--
ALTER TABLE `tickets_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticketsGalleryUserId` (`ticket_id`),
  ADD KEY `index-tickets_files-id` (`id`),
  ADD KEY `index-tickets_files-ticket_text_id` (`ticket_text_id`);

--
-- Индексы таблицы `tickets_text`
--
ALTER TABLE `tickets_text`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticketsTextUserId` (`ticket_id`),
  ADD KEY `index-tickets_text-id` (`id`);

--
-- Индексы таблицы `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `index-transaction-id` (`user_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `index-user-id` (`id`);

--
-- Индексы таблицы `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index-user_settings-user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `addition`
--
ALTER TABLE `addition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `message_to_paid`
--
ALTER TABLE `message_to_paid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT для таблицы `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `tariff`
--
ALTER TABLE `tariff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `tickets_files`
--
ALTER TABLE `tickets_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `tickets_text`
--
ALTER TABLE `tickets_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `message_to_paid`
--
ALTER TABLE `message_to_paid`
  ADD CONSTRAINT `messageServiceId` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messageUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `paymentsUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `serviceShopId` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serviceUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shopsTariffId` FOREIGN KEY (`tariff_id`) REFERENCES `tariff` (`id`),
  ADD CONSTRAINT `shopsUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `shops_addition`
--
ALTER TABLE `shops_addition`
  ADD CONSTRAINT `fk-shops_addition-addition_id` FOREIGN KEY (`addition_id`) REFERENCES `addition` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-shops_addition-shop_id` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tariff_addition`
--
ALTER TABLE `tariff_addition`
  ADD CONSTRAINT `fk-tariff_addition-addition_id` FOREIGN KEY (`addition_id`) REFERENCES `addition` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tariff_addition-tariff_id` FOREIGN KEY (`tariff_id`) REFERENCES `tariff` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tariff_addition_quantity`
--
ALTER TABLE `tariff_addition_quantity`
  ADD CONSTRAINT `fk-tariff_addition_quantity-addition_id` FOREIGN KEY (`addition_id`) REFERENCES `addition` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tariff_addition_quantity-tariff_id` FOREIGN KEY (`tariff_id`) REFERENCES `tariff` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `ticketsUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tickets_files`
--
ALTER TABLE `tickets_files`
  ADD CONSTRAINT `ticketsGalleryUserId` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tickets_text`
--
ALTER TABLE `tickets_text`
  ADD CONSTRAINT `ticketsTextUserId` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transactionUserId` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
