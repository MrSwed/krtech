CREATE TABLE services
(
    id INT(9) PRIMARY KEY NOT NULL,
    name VARCHAR(500)
);
INSERT INTO services (id, name) VALUES (1, 'Service 1');
INSERT INTO services (id, name) VALUES (2, 'Service 2');
INSERT INTO services (id, name) VALUES (3, 'Service 3');
INSERT INTO services (id, name) VALUES (4, 'Service 4');
INSERT INTO services (id, name) VALUES (5, 'Service 5');


CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(9) NOT NULL,
  `percent` int(3) DEFAULT NULL,
  `services` varchar(500) DEFAULT NULL,
  `birthday_before` int(11) DEFAULT NULL,
  `birthday_after` int(11) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `gender` varchar(5) DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `discounts`
--

INSERT INTO `discounts` (`id`, `percent`, `services`, `birthday_before`, `birthday_after`, `phone`, `gender`, `date_start`, `date_end`) VALUES
(1, 25, '1,3', 604800, 604800, '1', 'M', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 30, '1,4', 604800, 604800, '1', 'F', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 20, '1,2,3', 604800, 604800, '4564$', 'M', '0000-00-00 00:00:00', '2016-11-14 11:00:31'),
(4, 15, '1,4', 604800, 604800, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 10, '1,2,3', NULL, 604800, '1', 'F', '0000-00-00 00:00:00', '2016-11-14 11:00:31'),
(6, 5, '2,4,6', 604800, NULL, '1', 'M', '0000-00-00 00:00:00', '2016-11-14 11:00:31');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);
