-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 08:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

--
-- Dumping data for table `pma__designer_settings`
--

INSERT INTO `pma__designer_settings` (`username`, `settings_data`) VALUES
('root', '{\"snap_to_grid\":\"off\",\"angular_direct\":\"direct\",\"relation_lines\":\"true\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"recipe_database\",\"table\":\"recipe\"},{\"db\":\"user_management\",\"table\":\"users\"},{\"db\":\"recipe_database\",\"table\":\"recipes\"},{\"db\":\"mysql\",\"table\":\"user\"},{\"db\":\"performance_schema\",\"table\":\"accounts\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2024-11-07 15:57:29', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `recipe_database`
--
CREATE DATABASE IF NOT EXISTS `recipe_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `recipe_database`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `recipe_id`, `user_id`, `rating`, `comment_text`, `created_at`) VALUES
(7, 9, 1, 4, 'อร่อยมากครับ', '2024-11-10 20:26:13'),
(8, 9, 3, 5, 'สุดยอดดดด', '2024-11-10 20:26:56'),
(9, 9, 4, 5, 'น่ากิน', '2024-11-11 10:08:18'),
(10, 3, 3, 5, 'ทำง่ายมากครับ', '2024-11-11 10:13:27'),
(11, 6, 5, 2, 'ไม่ค่อยอร่อย', '2024-11-13 18:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `id` int(11) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `ingredients` text NOT NULL,
  `steps` text NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `food_category` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `rating` decimal(2,1) DEFAULT 5.0,
  `user_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`id`, `recipe_name`, `ingredients`, `steps`, `source`, `food_category`, `image_path`, `created_at`, `rating`, `user_id`, `updated_at`) VALUES
(3, 'ไข่ตุ๋น', '1.ไข่ไก่ 2 ฟอง\r\n2.น้ำเปล่า 1 ถ้วย\r\n3.ซีอิ๊วขาว 1 ช้อนโต๊ะ\r\n4.แครอทหั่นเต๋า 4 ช้อนโต๊ะ\r\n5.กุ้ง 6 ตัว\r\n6.ปูอัด 1 เส้น\r\n7.ปูอัด\r\n8.แฮมแผ่น\r\n9.สาหร่าย\r\n10.แครอท ', '1.เตรียมเนื้อสัตว์และผักไว้ โดยทำไปทำความสะอาด หั่น และปรุงสุกรอไว้สำหรับท็อปบนไข่ตุ๋น\r\n2.ตอกไข่ลงไปในชามผสม เติมน้ำเปล่าและซีอิ๊วขาว ตีให้เข้ากันก่อนนำไปวางในหม้อนึ่ง ปิดถ้วยด้วยฟอยล์และนึ่งประมาณ 30 นาที\r\nTIP : วิธีทำไข่ตุ๋นให้เนียนนั้นคือการนำไข่ไปกรองผ่านกระชอนก่อน 1 ครั้ง\r\n3.นำไข่ตุ๋นมาตกแต่งด้วย ปูอัด กุ้งลวกสุก และแครอท พร้อมเสิร์ฟร้อน ๆ ', 'dog', 'อาหารคาว', 'maindish_recipe/ไข่ตุ๋น.jpg', '2024-11-07 21:04:06', 5.0, 3, '2024-11-12 11:18:19'),
(4, 'ข้าวเหนียวมะม่วง', '1. ข้าวเหนียวเขี้ยวงู 1 กิโลกรัม\r\n2. น้ำตาลทรายขาว 2 ช้อนโต๊ะ\r\n3. กะทิ 100% 750 มิลลิลิตร\r\n4. เกลือ 1 ช้อนชา\r\n5. ใบเตยสำหรับจัดเสิร์ฟ', '1.นึ่งข้าวเหนียว ซาวข้าวเหนียวเขี้ยวงู และแช่ทิ้งไว้จนครบสามชั่วโมง ตั้งหม้อใส่น้ำสำหรับนึ่ง จากนั้นนำผ้าขาวบางมาปูในซึ้ง ตักข้าวเหนียวที่ซาวไว้ใส่ลงไปโดยเว้นตรงกลางเป็นวงกลม แล้วปิดผ้าขาวบางห่อข้าวเหนียวเอาไว้ นึ่งประมาณ 20 นาที พอครบ 20 นาที ใช้ทัพพีกลับข้าวเหนียวเพื่อให้สุกทั่วถึง แล้วนึ่งต่ออีก 10 นาที\r\n\r\n2.มูนข้าวเหนียว นำ อัมพวา กะทิ 100% น้ำตาลทราย และเกลือป่น เทผสมให้เข้ากัน นำไปตั้งไฟอ่อน ๆ เคี่ยวจนน้ำตาลละลายหมด นำข้าวเหนียวที่นึ่งสุกแล้วใส่ชาม จากนั้นเทน้ำกะทิลงไป คนให้เข้ากัน จากนั้นนำผ้าขาวบางปิดไว้ พักข้าว 30 นาทีเพื่อให้ข้าวเหนียวมูนดูดน้ำกะท\r\n\r\n3.ทำน้ำราดกะทิและจัดเสิร์ฟ นำ อัมพวา กะทิ 100% เกลือ และแป้งข้าวเจ้ามาตั้งไฟอ่อน ๆ หมั่นคนเรื่อย ๆ พอกะทิข้นขึ้น ยกลงพักไว้ ปอกมะม่วงสุกจัดใส่จาน เสิร์ฟพร้อมข้าวเหนียวมูน ราดด้วยน้ำกะทิ เท่านี้ก็พร้อมเสิร์ฟแล้ว', 'cat', 'ของหวาน', 'dessert_recipe/ข้าวเหนียวมะม่วง.png', '2024-11-07 21:18:14', 5.0, 1, '2024-11-12 11:18:19'),
(5, 'ขนมชั้นใบเตยโรล', '1.แป้งข้าวเจ้า 2 ช้อนโต๊ะ\r\n2.แป้งมัน 1 ถ้วย\r\n3.แป้งเท้ายายม่อม 3 ถ้วย\r\n4.น้ำเชื่อมเข้มข้นสูตรดั้งเดิม ตรามิตรผล 1/4 ถ้วย\r\n5.เกลือป่น 1/4 ช้อนชา\r\n6.หัวกะทิ 1/2 ถ้วย\r\n7.หางกะทิ 1/3 ถ้วย\r\n8.น้ำใบเตยเข้มข้น 1/3 ถ้วย\r\n9.น้ำมันพืช 1 ช้อนโต๊ะ', '1.ผสมแป้งข้าวเจ้า แป้งมัน แป้งเท้ายายม่อม น้ำเชื่อมเข้มข้นสูตรดั้งเดิม ตรามิตรผลและเกลือเข้าด้วยกัน\r\n2.ใส่หัวกะทิลงนวดให้เข้ากัน\r\n3.กรองด้วยผ้าขาวบาง แบ่งแป้งเป็น 2 ส่วน ส่วนที่ 1 ผสมกับหางกะทิ ส่วนที่ 2 ผสมกับน้ำใบเตย\r\n4.ทาน้ำมันที่ถาดสำหรับนึ่งให้ทั่ว นำไปนึ่งในน้ำเดือด 10 นาที เปิดฝาตักแป้งที่ผสมน้ำใบเตยใส่ประมาณ 1/2 ถ้วย ปิดฝานึ่งประมาณ 7 นาที\r\n5.เปิดฝาใส่แป้งที่ผสมกับหางกะทิลงไป นึ่งต่ออีกประมาณ 7 นาที ยกลงจากเตาพักไว้ให้เย็น\r\n6.นำขนมชั้นมาม้วนให้แน่น ตัดตามขวางหนา 1 นิ้ว จัดใส่จาน', 'Admin', 'ของหวาน', 'dessert_recipe/ใบเตยโรล.png', '2024-11-08 01:21:09', 5.0, 4, '2024-11-12 11:18:19'),
(6, 'ทองม้วนใบเตยมะพร้าวอ่อน', '1.หัวกะทิ 1 ถ้วย\r\n2.น้ำใบเตย  1/3 ถ้วย\r\n3.แป้งมัน 1 ถ้วย\r\n4.แป้งข้าวเจ้า 2 ช้อนโต๊ะ\r\n5.งาขาวและงาดำ 2 ช้อนโต๊ะ\r\n6.เกลือป่น 1/4 ช้อนชา\r\n7.น้ำตาลอ้อยผสมน้ำตาลมะพร้าวตรามิตรผล 3/4 ถ้วย\r\n8.เนื้อมะพร้าวอ่อนหั่นเส้น 1/2 ถ้วย\r\n9.ไข่เป็ด 1 ฟอง', '1.ผสมแป้งมัน แป้งข้าวเจ้า และเกลือ ค่อยๆ ใส่กะทิ คนให้แป้ง\r\n  ละลายเข้ากันจนกะทิหมด\r\n2.ใส่น้ำตาลอ้อยผสมน้ำตาลมะพร้าว ตรามิตรผล ไข่เป็ด ขยำให้เข้ากันจนน้ำตาลละลายหมด\r\n3.กรองให้เป็นเนื้อเดียวกัน ใส่น้ำใบเตย เนื้อมะพร้าวอ่อน งาขาว\r\n  และงาดำ คนให้เข้ากัน พักแป้งไว้ 30 นาที\r\n4.ตั้งกระทะเทฟลอนด้วยไฟปานกลางค่อนข้างอ่อนทาไขมันบางๆ\r\n  พอกระทะร้อน\r\n5.ตักส่วนผสมแป้งหยอดให้เป็นแผ่นจนสุก กลับด้าน รอให้แป้งสุก\r\n  หอมๆ ม้วนหรือพับ จัดใส่จาน เสิร์ฟ', 'Admin', 'ของหวาน', 'dessert_recipe/ทองม้วนใบเตยมะพร้าวอ่อน.png', '2024-11-08 01:34:48', 2.0, 4, '2024-11-13 18:46:39'),
(7, 'อาลัวกุหลาบนมสด', '1.แป้งเค้ก 1/2 ถ้วย\r\n2.กะทิ 1/4 ถ้วย\r\n3.นมสด 1 ถ้วย\r\n4.น้ำตาลทรายขาวบริสุทธิ์ ตรามิตรผล 1/3 ถ้วย\r\n5.สีผสมอาหารสีเขียวและสีชมพู', '1.ผสมแป้งเค้กกับน้ำตาลทรายขาวบริสุทธิ์ ตรามิตรผล ใส่กะทิ คนให้เข้ากัน\r\n  ใส่นมสด คนให้แป้งละลายเข้ากัน กรองด้วยกระชอนใส่กระทะเทฟลอน\r\n2.ยกกระทะตั้งไฟปานกลาง กวนให้สุกจนข้น ยกลง\r\n3.แบ่งส่วนผสมแป้งเล็กน้อยใส่สีเขียว คนให้เข้ากัน ตักใส่ถุงบีบหัวใบไม้\r\n4.ส่วนแป้งที่เหลือใส่สีชมพู คนให้เข้ากัน ใส่ถุงบีบหัวกลีบกุหลาบ\r\n5.วางกระดาษรองอบบนร่มแต่งหน้าเค้ก (อุปกรณ์สำหรับทำกุหลาบแต่งหน้าเค้ก)บีบสีชมพูวนให้เป็นกลีบกุหลาบชั้นใน แล้วบีบต่อให้เป็นกลีบกุหลาบชั้นนอกให้ซ้อนกันเล็กน้อยอย่างสวยงาม\r\n6.นำขนมออกจากร่ม เรียงใส่ถาดอบ นำไปอบลมร้อนที่อุณหภูมิ 50-60\r\nองศาเซลเซียสประมาณ 5-6 ชั่วโมง (ถ้าไม่มีเตาอบสามารถตากแดดจัดๆ 1-2 แดด)จนแป้งด้านนอกแห้ง เรียงใส่ภาชนะมีฝา แล้วนำไปอบควันเทียนไว้ให้หอมๆ', 'Admin', 'ของหวาน', 'dessert_recipe/อาลัวกุหลาบนมสด.png', '2024-11-08 01:43:39', 5.0, 4, '2024-11-12 11:18:19'),
(9, 'ลูกตาลลอยแก้ว', '1.ลูกตาลอ่อน 1 กิโลกรัม\r\n2.ใบเตยสดหั่นท่อนยาว 3-4 นิ้ว 4-5 ใบ\r\n3.น้ำตาลทรายขาว 1 ถ้วย\r\n4.น้ำตาลกรวด ตรามิตรผล 1/2 ถ้วย\r\n5.น้ำเปล่า 2 ถ้วย', '1.นำลูกตาลใส่ภาชนะแล้วแช่ช่องแช่แข็งไว้ 1 คืน\r\n2.ใส่ลูกตาลแช่แข็งลงแช่ในน้ำให้ลูกตาลแยกออกเป็นชิ้นๆ\r\n3.ค่อยๆใช้มือบี้ให้เปลือกลูกตาลออกซึ่งจะลอกออกง่ายมาก\r\n4.หั่นเนื้อลูกตาลเป็นชิ้นบางๆพักไว้\r\n5.ต้มน้ำกับ น้ำตาลทราย น้ำตาลกรวด ตรามิตรผล ใส่ใบเตยต้มต่อให้น้ำตาลละลายหมดและมีกลิ่นหอมของใบเตย ตักใบเตยออก\r\n6.ใส่เนื้อลูกตาลที่หั่นไว้ คนให้เข้ากัน\r\n7.ตักลูกตาลลอยแก้วใส่ถ้วย ใส่น้ำแข็ง เสิร์ฟเย็นๆ', 'Admin', 'ของหวาน', 'dessert_recipe/ลูกตาลลอยแก้ว.png', '2024-11-08 23:57:03', 4.7, 4, '2024-11-12 11:18:19'),
(13, 'กะเพรา', '1.เนื้อสับ 120 กรัม\r\n2.น้ำมันหอย 2 ช้อนโต๊ะ\r\n3.น้ำปลา ½ ช้อนโต๊ะ\r\n4.ซอสถั่วเหลือง ½ ช้อนโต๊ะ\r\n5.น้ำตาลปี๊บ ½ ช้อนชา\r\n6.กระเทียมสับ 5 กรัม\r\n7.พริกขี้หนู 5 เม็ด\r\n8.ใบกะเพรา 10 กรัม (เต็มหยิบมือ)\r\n9.ลูกผักชีคั่ว และยี่หร่าป่น (สำหรับโรยหน้า)\r\n10.น้ำมันปรุงอาหาร\r\n11.ข้าวสวย 1 จาน\r\n12.ไข่ 1 ฟอง', '1.ใส่น้ำมันในกระทะร้อน\r\n2.ผัดพริกและกระเทียมในน้ำมันจนขึ้นสีอ่อน ๆ\r\n3.ใส่เนื้อสับและผัดต่อจนสุก 75%\r\n4.ใส่เครื่องปรุงรสแล้วผัดให้เข้ากัน\r\n5.ใส่ใบกะเพราท้ายสุด คลุกให้เข้ากันดีแล้วปิดเตาทันที\r\n6.ทอดไข่ดาวโดยเลือกความสุกตามใจชอบ\r\n7.นำข้าวสวยใส่จาน ราดด้วยผัดกะเพรา แล้วตบท้ายด้วยไข่ดาวที่เตรียมไว้\r\n8.โรยหน้าด้วยลูกผักชีคั่วและยี่หร่าป่น', 'Admin', 'อาหารคาว', 'maindish_recipe/กะเพา.png', '2024-11-13 19:05:16', 5.0, 4, '2024-11-13 12:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_text` text NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_management`.`users` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_management`.`users` (`id`) ON DELETE CASCADE;
--
-- Database: `user_management`
--
CREATE DATABASE IF NOT EXISTS `user_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `user_management`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `role` varchar(10) DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `gender`, `profile_image`, `birth_date`, `role`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'cat', 'user1@gmail.com', '$2y$10$YFOz23rs3rgPa0Gf2ffG7u4fZ9UPyNIcPNF.C8nKRbsIIrAoE/fwO', '0981111111', 'ชาย', 'uploads/profile_6730ae1cd11765.69495551.jpg', '2002-05-03', 'user', NULL, NULL),
(3, 'dog', 'user2@gmail.com', '$2y$10$spZwqVmdIL/s.4Z.yAp.rOE35jtzDjNl3zSbLb0qd/dufz183ypeW', '0981999999', 'หญิง', 'uploads/Maltese_puppy.jpeg.jpeg', NULL, 'user', NULL, NULL),
(4, 'Admin', 'tanatdith2545@gmail.com', '$2y$10$4y66du5pOmKocx09V/aa4.Gj8/qXFZAb4w/vCC3853eLCrGp08yE6', '0987654321', 'ชาย', 'uploads/admin.png', '2002-05-03', 'admin', 'a2139e2a6cee11762bc1351a4bd39837b54f801045238610de76718c2fd00c53b383e21d2cadee38f4f486d5f47ee5d38d6d', '2024-11-14 02:28:48'),
(5, 'user3', 'user3@gmail.com', '$2y$10$6oNonF.pbITCK4o.MIjY7eg2Lo98nksQvFVy5d/9MQaz5QIMWmf1y', '0981999999', 'ชาย', NULL, '2007-05-18', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
