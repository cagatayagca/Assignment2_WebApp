-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 26 Şub 2015, 23:46:00
-- Sunucu sürümü: 5.6.21
-- PHP Sürümü: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `blog`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
`blog_id` int(11) NOT NULL,
  `baslik` varchar(300) NOT NULL,
  `yazi` text NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uye_id` int(11) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `blog`
--

INSERT INTO `blog` (`blog_id`, `baslik`, `yazi`, `tarih`, `uye_id`) VALUES
(2, 'basligi degistirdim', 'Bu ikinci içerik yazım', '2015-02-19 15:57:16', 1),
(3, 'Üçüncü blog başlığım', 'Deneme yazısı\r\n', '2015-02-19 15:57:16', 1),
(4, 'as', 'asdasdasdsad\r\nasd\r\nad', '2015-02-20 17:22:09', 1),
(5, 'dsadasd', 'asdasdadasdassadasdsadasda\r\nsd\r\nasds\r\nas\r\ndasasdasda\r\ndas\r\nfasdasdas', '2015-02-20 17:47:32', 1),
(6, 'title', 'deneme', '2015-02-21 19:34:16', 1),
(7, '', '', '2015-02-26 21:26:48', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uye`
--

CREATE TABLE IF NOT EXISTS `uye` (
`uye_id` int(11) NOT NULL,
  `email` varchar(96) NOT NULL,
  `sifre` varchar(40) NOT NULL,
  `ad` varchar(32) NOT NULL,
  `durum` int(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `uye`
--

INSERT INTO `uye` (`uye_id`, `email`, `sifre`, `ad`, `durum`) VALUES
(1, 'test1@test.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Rıza ÇELİK', 1),
(2, 'test2@test.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Ceren GEZER', 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorum`
--

CREATE TABLE IF NOT EXISTS `yorum` (
`yorum_id` int(11) NOT NULL,
  `mesaj` text NOT NULL,
  `yazan` varchar(80) DEFAULT 'anonim',
  `tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blog_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `yorum`
--

INSERT INTO `yorum` (`yorum_id`, `mesaj`, `yazan`, `tarih`, `blog_id`) VALUES
(1, 'asdasda', 'asdad', '2015-02-20 18:24:18', 5),
(2, 'dasda', 'bayram', '2015-02-20 19:13:04', 5),
(3, 'Yorum', 'Lütfü', '2015-02-21 18:17:55', 2);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `blog`
--
ALTER TABLE `blog`
 ADD PRIMARY KEY (`blog_id`);

--
-- Tablo için indeksler `uye`
--
ALTER TABLE `uye`
 ADD PRIMARY KEY (`uye_id`), ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `yorum`
--
ALTER TABLE `yorum`
 ADD PRIMARY KEY (`yorum_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `blog`
--
ALTER TABLE `blog`
MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Tablo için AUTO_INCREMENT değeri `uye`
--
ALTER TABLE `uye`
MODIFY `uye_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Tablo için AUTO_INCREMENT değeri `yorum`
--
ALTER TABLE `yorum`
MODIFY `yorum_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
