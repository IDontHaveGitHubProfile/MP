-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 20 2025 г., 14:38
-- Версия сервера: 10.3.36-MariaDB
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `motoprofi`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cart_quantity` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `cart_added_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category_id`, `parent_id`, `category_name`, `category_description`, `category_image`) VALUES
(1, NULL, 'Двигатель', 'Запчасти для двигателя мотоцикла', 'engine.jpg'),
(2, NULL, 'Трансмиссия', 'Компоненты трансмиссии мотоцикла', 'transmission.jpg'),
(3, NULL, 'Ходовая часть', 'Детали ходовой части мотоцикла', 'chassis.jpg'),
(4, NULL, 'Электрика', 'Электрооборудование мотоцикла', 'electrics.jpg'),
(5, NULL, 'Тормозная система', 'Компоненты тормозной системы', 'brakes.jpg'),
(6, NULL, 'Кузов и оптика', 'Элементы кузова и освещения', 'body.jpg'),
(7, NULL, 'Фильтры и масла', 'Фильтры и смазочные материалы', 'filters.jpg'),
(8, NULL, 'Рулевое управление', 'Компоненты рулевого управления', 'steering.jpg'),
(9, NULL, 'Защитная экипировка', 'Одежда и защита для мотоциклистов', 'protection.jpg'),
(10, NULL, 'Инструменты и уход', 'Инструменты и средства по уходу', 'tools.jpg'),
(101, 1, 'Поршневая группа', 'Поршни, кольца, пальцы', NULL),
(102, 1, 'Головка блока цилиндров', 'Клапаны, распредвалы, прокладки', NULL),
(103, 1, 'Коленвал и шатуны', 'Коленчатые валы, шатуны, подшипники', NULL),
(104, 1, 'Система охлаждения', 'Радиаторы, помпы, патрубки', NULL),
(105, 1, 'Система выпуска', 'Глушители, выхлопные трубы', NULL),
(106, 1, 'Карбюраторы и инжекторы', 'Компоненты системы питания', NULL),
(201, 2, 'Сцепление', 'Диски сцепления, корзины, пружины', NULL),
(202, 2, 'Коробка передач', 'Шестерни, вилки переключения', NULL),
(203, 2, 'Цепь и звезды', 'Приводные цепи и звездочки', NULL),
(204, 2, 'Ремни и шкивы', 'Ремни ГРМ и приводные ремни', NULL),
(209, 2, 'Главная передача', 'Карданные валы, редукторы', NULL),
(210, 2, 'Дифференциалы', 'Дифференциалы для мотоциклов с коляской', NULL),
(301, 3, 'Амортизаторы', 'Передние и задние амортизаторы', NULL),
(302, 3, 'Рычаги подвески', 'Рычаги маятниковые и передней вилки', NULL),
(303, 3, 'Пружины подвески', 'Пружины амортизаторов', NULL),
(304, 3, 'Подшипники колес', 'Подшипники ступиц колес', NULL),
(309, 3, 'Колеса и шины', 'Диски, спицы, покрышки', NULL),
(310, 3, 'Торсионы', 'Торсионные валы подвески', NULL),
(401, 4, 'Аккумуляторы', 'Мотоциклетные аккумуляторы', NULL),
(402, 4, 'Генераторы', 'Генераторы и компоненты', NULL),
(403, 4, 'Стартеры', 'Стартеры и комплектующие', NULL),
(404, 4, 'Проводка', 'Жгуты проводов, разъемы', NULL),
(405, 4, 'Освещение', 'Лампы, фары, поворотники', NULL),
(409, 4, 'Электронные аксессуары', 'Гаджеты и электроника', NULL),
(410, 4, 'Системы безопасности', 'Сигнализации, иммобилайзеры', NULL),
(501, 5, 'Тормозные диски', 'Диски передние и задние', NULL),
(502, 5, 'Тормозные колодки', 'Колодки для различных моделей', NULL),
(503, 5, 'Тормозные суппорты', 'Суппорта и их компоненты', NULL),
(504, 5, 'Тормозные шланги', 'Гибкие тормозные шланги', NULL),
(505, 5, 'Главные тормозные цилиндры', 'Цилиндры ручки и педали тормоза', NULL),
(509, 5, 'ABS системы', 'Компоненты антиблокировочной системы', NULL),
(510, 5, 'Тормозные жидкости', 'Жидкости для гидравлических систем', NULL),
(601, 6, 'Багажники и кофры', 'Багажные системы для мотоциклов', NULL),
(602, 6, 'Зеркала', 'Зеркала заднего вида', NULL),
(603, 6, 'Обтекатели', 'Пластиковые обтекатели', NULL),
(604, 6, 'Сиденья', 'Седла и сиденья', NULL),
(605, 6, 'Фары', 'Передние фары и комплектующие', NULL),
(606, 6, 'Поворотники', 'Сигналы поворота', NULL),
(607, 1, 'Система смазки', 'Масляные насосы, радиаторы, трубки', NULL),
(608, 1, 'Система зажигания', 'Катушки, свечи, провода', NULL),
(611, 6, 'Декоративные элементы', 'Накладки, эмблемы, шильдики', NULL),
(612, 6, 'Звуковые сигналы', 'Клаксоны и звуковые устройства', NULL),
(701, 7, 'Воздушные фильтры', 'Фильтры воздушные бумажные и поролоновые', NULL),
(702, 7, 'Масляные фильтры', 'Фильтры для моторного масла', NULL),
(703, 7, 'Топливные фильтры', 'Фильтры очистки топлива', NULL),
(704, 7, 'Моторные масла', 'Смазочные материалы для двигателя', NULL),
(705, 7, 'Трансмиссионные масла', 'Масла для коробки передач', NULL),
(801, 8, 'Рулевые колонки', 'Компоненты рулевой колонки', NULL),
(802, 8, 'Рулевые тяги', 'Тяги и наконечники рулевого управления', NULL),
(803, 8, 'Подшипники руля', 'Подшипники рулевой колонки', NULL),
(804, 8, 'Рукоятки руля', 'Грипсы и ручки управления', NULL),
(901, 9, 'Шлемы', 'Мотошлемы различных типов', NULL),
(902, 9, 'Куртки', 'Защитные мото-куртки', NULL),
(903, 9, 'Перчатки', 'Мото-перчатки', NULL),
(904, 9, 'Ботинки', 'Мото-обувь', NULL),
(905, 9, 'Защита тела', 'Наколенники, панцири', NULL),
(910, 9, 'Защита шеи', 'Системы защиты шейного отдела', NULL),
(911, 9, 'Дождевая экипировка', 'Непромокаемые костюмы и аксессуары', NULL),
(1001, 10, 'Ручной инструмент', 'Ключи, отвертки, съемники', NULL),
(1002, 10, 'Электроинструмент', 'Гайковерты, дрели', NULL),
(1003, 10, 'Измерительные приборы', 'Мультиметры, манометры', NULL),
(1004, 10, 'Чистящие средства', 'Шампуни, полироли', NULL),
(1005, 10, 'Смазки и аэрозоли', 'WD-40, графитовые смазки', NULL),
(1010, 10, 'Подъемное оборудование', 'Подставки, домкраты', NULL),
(1011, 10, 'Станки и стенды', 'Ремонтные стенды для мотоциклов', NULL),
(10101, 101, 'Поршни', 'Поршни для различных моделей мотоциклов', NULL),
(10102, 101, 'Поршневые кольца', 'Компрессионные и маслосъемные кольца', NULL),
(10103, 101, 'Поршневые пальцы', 'Пальцы поршней и стопорные кольца', NULL),
(10104, 101, 'Комплекты поршней', 'Полные комплекты поршневой группы', NULL),
(10201, 102, 'Клапаны', 'Впускные и выпускные клапаны', NULL),
(10202, 102, 'Направляющие клапанов', 'Направляющие втулки клапанов', NULL),
(10203, 102, 'Прокладки ГБЦ', 'Прокладки головки блока цилиндров', NULL),
(10204, 102, 'Распредвалы', 'Распределительные валы', NULL),
(10401, 104, 'Радиаторы', 'Основные и масляные радиаторы', NULL),
(10402, 104, 'Водяные помпы', 'Помпы системы охлаждения', NULL),
(10403, 104, 'Термостаты', 'Терморегуляторы охлаждающей жидкости', NULL),
(10404, 104, 'Патрубки', 'Резиновые и силиконовые патрубки', NULL),
(10405, 104, 'Вентиляторы охлаждения', 'Электровентиляторы и датчики включения', NULL),
(10501, 105, 'Глушители', 'Штатные и спортивные глушители', NULL),
(10502, 105, 'Выпускные коллекторы', 'Трубы выпускной системы', NULL),
(10503, 105, 'Катализаторы', 'Каталитические нейтрализаторы', NULL),
(10504, 105, 'Крепления глушителей', 'Хомуты и кронштейны', NULL),
(10601, 106, 'Карбюраторы', 'Полные карбюраторы', NULL),
(10602, 106, 'Ремкомплекты карбюраторов', 'Наборы для ремонта', NULL),
(10603, 106, 'Дроссельные заслонки', 'Компоненты инжекторных систем', NULL),
(10604, 106, 'Топливные форсунки', 'Форсунки инжекторных систем', NULL),
(10605, 106, 'ТНВД', 'Топливные насосы высокого давления', NULL),
(20101, 201, 'Диски сцепления', 'Фрикционные и стальные диски', NULL),
(20102, 201, 'Корзины сцепления', 'Ведущие корзины сцепления', NULL),
(20103, 201, 'Выжимные подшипники', 'Подшипники выжима сцепления', NULL),
(20104, 201, 'Комплекты сцепления', 'Полные комплекты сцепления', NULL),
(20201, 202, 'Шестерни КПП', 'Ведомые и ведущие шестерни', NULL),
(20202, 202, 'Вилки переключения', 'Вилки переключения передач', NULL),
(20203, 202, 'Подшипники КПП', 'Подшипники коробки передач', NULL),
(20204, 202, 'Сальники КПП', 'Уплотнители валов КПП', NULL),
(20301, 203, 'Ведущие звёзды', 'Звёзды первичного вала', NULL),
(20302, 203, 'Ведомые звёзды', 'Задние звёзды', NULL),
(20303, 203, 'Цепи приводные', 'Роликовые цепи', NULL),
(20304, 203, 'Натяжители цепи', 'Механические и автоматические', NULL),
(20305, 203, 'Защита цепи', 'Кожухи и крышки', NULL),
(30101, 301, 'Передние амортизаторы', 'Амортизаторы вилки', NULL),
(30102, 301, 'Задние амортизаторы', 'Моноамортизаторы и двухтрубные', NULL),
(30103, 301, 'Ремкомплекты амортизаторов', 'Наборы для ремонта', NULL),
(30104, 301, 'Масло для амортизаторов', 'Специальные жидкости', NULL),
(30901, 309, 'Мотоциклетные диски', 'Литые и спицованные колесные диски', NULL),
(30902, 309, 'Комплекты спиц', 'Спицы и ниппели для колес', NULL),
(30903, 309, 'Покрышки', 'Мотоциклетные шины различных типов', NULL),
(30904, 309, 'Камеры', 'Внутренние камеры для колес', NULL),
(40501, 405, 'Лампы головного света', 'Галогенные, LED, ксеноновые', NULL),
(40502, 405, 'Противотуманные фары', 'Дополнительное освещение', NULL),
(40503, 405, 'Стоп-сигналы', 'Задние сигналы торможения', NULL),
(40504, 405, 'Дневные ходовые огни', 'LED-подсветка', NULL),
(40505, 405, 'Блоки управления светом', 'Реле и контроллеры', NULL),
(50101, 501, 'Перфорированные диски', 'Диски с отверстиями', NULL),
(50102, 501, 'Волнистые диски', 'Диски с волнистым краем', NULL),
(50103, 501, 'Составные диски', 'Двухкомпонентные диски', NULL),
(50104, 501, 'Защитные крышки дисков', 'Декоративные элементы', NULL),
(60301, 603, 'Ветровые стекла', 'Лобовые стекла', NULL),
(60302, 603, 'Боковые панели', 'Пластиковые панели', NULL),
(60303, 603, 'Спойлеры', 'Аэродинамические элементы', NULL),
(60304, 603, 'Защитные дуги', 'Краш-бары и дуги', NULL),
(60305, 603, 'Декоративные наклейки', 'Виниловые стикеры', NULL),
(60701, 607, 'Масляные насосы', 'Насосы системы смазки двигателя', NULL),
(60702, 607, 'Масляные радиаторы', 'Радиаторы охлаждения масла', NULL),
(60703, 607, 'Масляные трубки', 'Металлические и гибкие маслопроводы', NULL),
(60704, 607, 'Масляные фильтры', 'Фильтры системы смазки', NULL),
(60801, 608, 'Катушки зажигания', 'Катушки и модули зажигания', NULL),
(60802, 608, 'Свечи зажигания', 'Иридиевые и платиновые свечи', NULL),
(60803, 608, 'Провода высокого напряжения', 'Высоковольтные провода', NULL),
(60804, 608, 'ЭБУ зажигания', 'Блоки управления системой зажигания', NULL),
(70101, 701, 'Бумажные фильтры', 'Одноразовые воздушные фильтры', NULL),
(70102, 701, 'Поролоновые фильтры', 'Многоразовые с пропиткой', NULL),
(70103, 701, 'Многослойные фильтры', 'Комбинированные материалы', NULL),
(70104, 701, 'Фильтры нулевого сопротивления', 'Спортивные фильтры', NULL),
(70105, 701, 'Корпуса воздушных фильтров', 'Пластиковые и металлические', NULL),
(80401, 804, 'Резиновые грипсы', 'Стандартные рукоятки', NULL),
(80402, 804, 'Гелевые грипсы', 'С мягким покрытием', NULL),
(80403, 804, 'Гребешки', 'Удлинители руля', NULL),
(80404, 804, 'Подогрев ручек', 'Электрообогрев руля', NULL),
(80405, 804, 'Защита рук', 'Леера и ветровики', NULL);

--
-- Триггеры `categories`
--
DELIMITER $$
CREATE TRIGGER `before_category_insert` BEFORE INSERT ON `categories` FOR EACH ROW BEGIN
    IF NEW.parent_id IS NOT NULL AND NEW.category_image IS NOT NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'У подкатегории не может быть фото!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_method` varchar(100) NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_city` varchar(100) NOT NULL,
  `delivery_postcode` varchar(20) DEFAULT NULL,
  `delivery_phone` varchar(20) NOT NULL,
  `delivery_status` varchar(50) DEFAULT 'not_sent',
  `delivery_created_at` timestamp NULL DEFAULT current_timestamp(),
  `delivery_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_type` enum('percentage','amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `discount_created_at` timestamp NULL DEFAULT current_timestamp(),
  `discount_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `discounts`
--

INSERT INTO `discounts` (`discount_id`, `discount_name`, `discount_value`, `discount_type`, `start_date`, `end_date`, `status`, `discount_created_at`, `discount_updated_at`) VALUES
(1, 'Скидка 50% на всё', '50.00', 'percentage', '2025-06-02 10:50:12', '2025-06-18 10:50:12', 'active', '2025-05-17 19:09:23', '2025-06-17 06:50:27'),
(2, 'Скидка 200₽ на KTM Duke', '200.00', 'amount', '2025-06-10 10:50:43', '2025-06-25 10:50:43', 'active', '2025-05-17 19:09:23', '2025-06-17 06:51:01'),
(3, 'Весенняя 15%', '15.00', 'percentage', '2025-06-16 10:50:43', '2025-06-22 10:50:43', 'active', '2025-05-17 19:09:23', '2025-06-17 06:51:01'),
(4, 'Минус 100₽ на прокладки', '100.00', 'amount', '2025-06-12 10:50:43', '2025-06-18 10:50:43', 'active', '2025-05-17 19:09:23', '2025-06-17 06:51:01');

-- --------------------------------------------------------

--
-- Структура таблицы `discount_product`
--

CREATE TABLE `discount_product` (
  `discount_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `discount_product`
--

INSERT INTO `discount_product` (`discount_id`, `product_id`) VALUES
(3, 17);

-- --------------------------------------------------------

--
-- Структура таблицы `favourites`
--

CREATE TABLE `favourites` (
  `favourite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `favourite_added_to` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT 'Не оплачен',
  `order_total_price` decimal(10,2) NOT NULL,
  `order_comment` text DEFAULT NULL,
  `order_created_at` timestamp NULL DEFAULT current_timestamp(),
  `order_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_item_quantity` int(11) NOT NULL DEFAULT 1,
  `order_item_price` decimal(10,2) NOT NULL,
  `order_item_created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `persistent_logins`
--

CREATE TABLE `persistent_logins` (
  `login_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `login_created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_quantity` int(11) NOT NULL DEFAULT 0,
  `product_rating` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `product_sku`, `product_description`, `product_price`, `product_quantity`, `product_rating`) VALUES
(1, 10101, 'Поршень мотоциклетный Honda CBR600RR', 'PST001', 'Поршень для Honda CBR600RR, диаметр 67мм, кованый алюминий', '89.99', 11, 5),
(2, 10101, 'Поршень Yamaha R6 2006-2016', 'PST002', 'Оригинальный поршень для Yamaha YZF-R6 2006-2016', '95.50', 0, 4),
(3, 10102, 'Комплект колец поршневых Suzuki GSX-R750', 'RNG001', 'Комплект поршневых колец для Suzuki GSX-R750 2011-2015', '45.99', 18, 5),
(4, 10103, 'Палец поршневой Kawasaki Ninja 650', 'PN001', 'Поршневой палец для Kawasaki Ninja 650 2017-2020', '18.75', 0, 4),
(5, 10104, 'Комплект поршневой BMW S1000RR', 'PSTK001', 'Полный комплект поршневой группы для BMW S1000RR', '320.00', 2, 5),
(6, 10201, 'Клапан впускной Ducati Monster 821', 'VLV001', 'Впускной клапан для Ducati Monster 821', '32.99', 4, 4),
(8, 10203, 'Прокладка ГБЦ Aprilia RSV4', 'HG001', 'Прокладка головки блока цилиндров Aprilia RSV4', '55.00', 7, 5),
(9, 10204, 'Распредвал Harley-Davidson Sportster', 'CAM001', 'Распределительный вал для Harley-Davidson Sportster', '145.99', 0, 4),
(10, 10401, 'Радиатор охлаждения Honda CB500F', 'RAD001', 'Алюминиевый радиатор для Honda CB500F 2013-2019', '120.00', 6, 5),
(11, 10402, 'Водяная помпа Yamaha MT-07', 'WP001', 'Водяная помпа для Yamaha MT-07/FZ-07', '65.50', 11, 4),
(12, 10403, 'Термостат Suzuki V-Strom 650', 'TH001', 'Термостат с корпусом для Suzuki V-Strom 650', '42.99', 18, 4),
(13, 10404, 'Патрубок радиатора Kawasaki Z900', 'HOSE001', 'Резиновый патрубок радиатора Kawasaki Z900', '15.99', 0, 3),
(14, 10405, 'Вентилятор охлаждения Triumph Street Triple', 'FAN001', 'Электровентилятор радиатора Triumph Street Triple 675', '85.00', 6, 5),
(15, 10501, 'Глушитель Akrapovic для BMW R1250GS', 'EXH001', 'Титановый глушитель Akrapovic для BMW R1250GS', '899.00', 0, 5),
(16, 10502, 'Выпускной коллектор Kawasaki Ninja 400', 'HEAD001', 'Выпускной коллектор для Kawasaki Ninja 400', '135.00', 5, 4),
(17, 10503, 'Катализатор Yamaha MT-09', 'CAT001', 'Каталитический нейтрализатор для Yamaha MT-09', '250.00', 3, 3),
(18, 10504, 'Хомут крепления глушителя Suzuki GSX-S750', 'CLAMP001', 'Хомут для крепления глушителя Suzuki GSX-S750', '12.99', 0, 4),
(19, 10601, 'Карбюратор Keihin CVK40 для Kawasaki Vulcan', 'CARB001', 'Карбюратор Keihin CVK40 для Kawasaki Vulcan 800', '199.00', 5, 4),
(20, 10602, 'Ремкомплект карбюратора Mikuni BS34', 'CRK001', 'Ремонтный комплект для карбюратора Mikuni BS34', '35.99', 20, 5),
(21, 10603, 'Дроссельная заслонка Honda CBR1000RR', 'TB001', 'Дроссельная заслонка для Honda CBR1000RR 2008-2012', '75.50', 8, 4),
(22, 10604, 'Форсунка инжектора Yamaha R1', 'INJ001', 'Топливная форсунка для Yamaha YZF-R1 2015-2019', '89.99', 11, 5),
(23, 10605, 'ТНВД Ducati Panigale 1299', 'FP001', 'Топливный насос высокого давления Ducati Panigale 1299', '320.00', 1, 5),
(24, 902, 'Куртка Rev\'it! Sand 3', 'JKT003', 'Текстильная куртка Rev\'it! Sand 3', '299.00', 13, 4),
(25, 903, 'Перчатки Five RFX-1', 'GLV003', 'Перчатки Five RFX-1 с защитой', '79.99', 0, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(5) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `user_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `news_subscription_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_subscribed_to_news` tinyint(1) DEFAULT 0,
  `news_subscription_updated_at` datetime DEFAULT NULL,
  `user_email_verified` tinyint(1) DEFAULT 0,
  `user_email_verified_at` datetime DEFAULT NULL,
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_token_expires` datetime DEFAULT NULL,
  `user_surname` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_updated_at` datetime DEFAULT current_timestamp(),
  `user_created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `name_updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `fk_categories_parent` (`parent_id`);

--
-- Индексы таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Индексы таблицы `discount_product`
--
ALTER TABLE `discount_product`
  ADD PRIMARY KEY (`discount_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`favourite_id`),
  ADD KEY `fk_favourites_user` (`user_id`),
  ADD KEY `fk_favourites_product` (`product_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `persistent_logins`
--
ALTER TABLE `persistent_logins`
  ADD PRIMARY KEY (`login_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_reviews_users` (`user_id`),
  ADD KEY `fk_reviews_products` (`product_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_phone` (`user_phone`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80414;

--
-- AUTO_INCREMENT для таблицы `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `favourites`
--
ALTER TABLE `favourites`
  MODIFY `favourite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `persistent_logins`
--
ALTER TABLE `persistent_logins`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `discount_product`
--
ALTER TABLE `discount_product`
  ADD CONSTRAINT `discount_product_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`discount_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `fk_favourites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favourites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `persistent_logins`
--
ALTER TABLE `persistent_logins`
  ADD CONSTRAINT `persistent_logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
