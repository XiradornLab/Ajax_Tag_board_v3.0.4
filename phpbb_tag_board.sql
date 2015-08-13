-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Ago 13, 2015 alle 23:41
-- Versione del server: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpbb3012`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `phpbb_tag_board`
--

CREATE TABLE IF NOT EXISTS `phpbb_tag_board` (
  `tb_post_id` mediumint(8) unsigned NOT NULL,
  `tb_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tb_post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `tb_post_username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tb_post_text` mediumtext COLLATE utf8_bin NOT NULL,
  `tb_bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tb_bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tb_flags` int(1) unsigned NOT NULL DEFAULT '3'
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `phpbb_tag_board`
--
ALTER TABLE `phpbb_tag_board`
  ADD PRIMARY KEY (`tb_post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phpbb_tag_board`
--
ALTER TABLE `phpbb_tag_board`
  MODIFY `tb_post_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=138;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
