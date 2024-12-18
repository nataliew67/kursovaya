-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 18 2024 г., 23:23
-- Версия сервера: 10.11.10-MariaDB-ubu2204
-- Версия PHP: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `grechushnikova_flowless_k`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `paint_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id_cart`, `user_id`, `paint_id`, `count`, `order_id`) VALUES
(1, 4, 6, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `date_create` date NOT NULL DEFAULT current_timestamp(),
  `payment` enum('наличными','банковской картой') NOT NULL DEFAULT 'банковской картой'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id_order`, `phone`, `address`, `date_create`, `payment`) VALUES
(1, '+75584343523', 'Непокоренных 36', '2024-12-19', 'банковской картой');

-- --------------------------------------------------------

--
-- Структура таблицы `paintings`
--

CREATE TABLE `paintings` (
  `id_painting` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `paintings`
--

INSERT INTO `paintings` (`id_painting`, `name`, `type_id`, `description`, `photo`, `price`) VALUES
(2, 'Картина \"У окна\"', 2, 'На портрете изображена супруга покупателя картин импрессионистов Виктора Шоке, занимавшего пост таможенного чиновника. Мадам Шоке изображена в квартире на улице Риволи в Париже на фоне большого французского окна, из которого открывается вид в сад Тюильри.', '78e3a3314be9c1097f5a36dfb0b446f41b9ef0847200b7992498533b6f8140d3.jpg', 12999),
(6, 'Картина \"Искренность\"', 4, 'На портрете изображены лошади', 'a4549ec7d32f0f70aebcb19cfcfedecf4837c6f7e131ad928a1be09c03396871.jpg', 4999),
(7, 'Картина \"Корзина фруктов\"', 3, 'На портрете изображены фрукты', 'd3dc88ae24a3be59843285c31e59c42286e0724f6698aad5e14217416ab05c25.jpg', 2500),
(8, 'Картина \"Фрукты\"', 3, 'На портрете изображены фрукты', 'd3dc88ae24a3be59843285c31e59c42286e0724f6698aad5e14217416ab05c25.jpg', 2799);

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`type_id`, `name`) VALUES
(1, 'Пейзаж'),
(2, 'Портрет'),
(3, 'Натюрморт'),
(4, 'Анималистика ');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `username` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `admin` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `name`, `last_name`, `username`, `phone`, `email`, `password`, `token`, `admin`) VALUES
(2, 'Иван', 'Петров', 'Ivan', '+79001789321', 'user@mail.ru', '$2y$13$KFmDg5jKSyQq3fwayUeoBuMPQ9zwUk/Bo/lIgXi6xAUGAY2XZzXCa', NULL, 'user'),
(3, 'Александра', 'Петрова', 'Alexandrus', '+79001789321', 'alexa@mail.ru', '$2y$13$xgElcyjM0mDijWbZDEXqveKteCI5..QmWTyuo8uHLGGxY00eT0s3m', NULL, 'admin'),
(4, 'Марина', 'Иавнченко', 'Marinka', '+79001123321', 'Marinkanew@mail.ru', '$2y$13$t5yy.u3AOaIO80HpZBif6uk3tzbxjtmAJ/xr0KF4QDDvb8q1JD4mi', 'imnRe7AUc_u_Hl--WqH0S8YXENYdtHOP', 'admin'),
(5, 'Саша', 'Новикова', 'sashok', '+79001456321', 'sasha@mail.ru', '$2y$13$q1AvxrsOwqDTrvtfb/c9v.fGSBEqWlVvEvR3K.PGyqUOIWG1rbf46', NULL, 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `user_id` (`user_id`,`paint_id`,`order_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`);

--
-- Индексы таблицы `paintings`
--
ALTER TABLE `paintings`
  ADD PRIMARY KEY (`id_painting`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `paintings`
--
ALTER TABLE `paintings`
  MODIFY `id_painting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`paint_id`) REFERENCES `paintings` (`id_painting`);

--
-- Ограничения внешнего ключа таблицы `paintings`
--
ALTER TABLE `paintings`
  ADD CONSTRAINT `paintings_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
