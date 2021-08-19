-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Трв 15 2021 р., 15:06
-- Версія сервера: 10.4.18-MariaDB
-- Версія PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `wptest`
--

-- --------------------------------------------------------

--
-- Структура таблиці `wp_qtt_answers`
--

CREATE TABLE IF NOT EXISTS `wp_qtt_answers` (
  `idanswers` int(11) NOT NULL AUTO_INCREMENT,
  `idquestions` int(11) DEFAULT NULL,
  `answer` tinytext DEFAULT NULL,
  `nextquestion` int(11) DEFAULT NULL,
  `idattachimg` int(11) DEFAULT NULL,
  PRIMARY KEY (`idanswers`),
  KEY `fk_answers_1_idx` (`idquestions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `wp_qtt_questions`
--

CREATE TABLE IF NOT EXISTS `wp_qtt_questions` (
  `idquestions` int(11) NOT NULL AUTO_INCREMENT,
  `idtest` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `grup` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idquestions`),
  KEY `fk_questions_1_idx` (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `wp_qtt_results`
--

CREATE TABLE IF NOT EXISTS `wp_qtt_results` (
  `idresults` int(11) NOT NULL,
  `idtest` int(11) DEFAULT NULL,
  `result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`result`)),
  PRIMARY KEY (`idresults`),
  KEY `fk_results_1_idx` (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблиці `wp_qtt_tests`
--

CREATE TABLE IF NOT EXISTS `wp_qtt_tests` (
  `idtest` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `length` int(11) NOT NULL,
  PRIMARY KEY (`idtest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `wp_qtt_answers`
--
ALTER TABLE `wp_qtt_answers`
  ADD CONSTRAINT `fk_answers_1` FOREIGN KEY (`idquestions`) REFERENCES `wp_qtt_questions` (`idquestions`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `wp_qtt_questions`
--
ALTER TABLE `wp_qtt_questions`
  ADD CONSTRAINT `fk_questions_1` FOREIGN KEY (`idtest`) REFERENCES `wp_qtt_tests` (`idtest`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `wp_qtt_results`
--
ALTER TABLE `wp_qtt_results`
  ADD CONSTRAINT `fk_results_1` FOREIGN KEY (`idtest`) REFERENCES `wp_qtt_tests` (`idtest`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
