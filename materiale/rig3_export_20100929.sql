# --------------------------------------------------------
# Host:                         127.0.0.1
# Database:                     rigorix_rigorix
# Server version:               5.1.41
# Server OS:                    Win32
# HeidiSQL version:             5.0.0.3272
# Date/time:                    2010-09-29 15:36:35
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table rigorix_rigorix.messaggi
CREATE TABLE IF NOT EXISTS `messaggi` (
  `id_mess` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_sender` bigint(20) unsigned NOT NULL,
  `id_receiver` bigint(20) unsigned NOT NULL,
  `oggetto` varchar(255) NOT NULL,
  `testo` text NOT NULL,
  `letto` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dta_mess` datetime NOT NULL,
  `report` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_mess`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.messaggi: 185 rows
/*!40000 ALTER TABLE `messaggi` DISABLE KEYS */;
INSERT INTO `messaggi` (`id_mess`, `id_sender`, `id_receiver`, `oggetto`, `testo`, `letto`, `dta_mess`, `report`) VALUES (4, 0, 5686, 'rty si &egrave; iscritto al tuo torneo <strong>amaro 18</strong>', '', 1, '2010-09-20 11:40:08', 0), (5, 0, 5686, 'fgh si &egrave; iscritto al tuo torneo <strong>amaro 18</strong>', '', 0, '2010-09-20 15:02:44', 0), (6, 0, 5686, 'vbn si &egrave; iscritto al tuo torneo <strong>amaro 18</strong>', '', 0, '2010-09-20 15:03:21', 0), (7, 0, 5686, 'asdasd si &egrave; iscritto al tuo torneo <strong>amaro 18</strong>', '', 0, '2010-09-20 15:04:11', 0), (8, 0, 5686, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (9, 0, 5685, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (10, 0, 5688, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (11, 0, 5687, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (12, 0, 5690, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (13, 0, 5691, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (14, 0, 5692, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (15, 0, 5695, 'E\' cominciato il torneo <strong>amaro 18</strong>', 'E\' partito. corri a giocare', 0, '2010-09-20 15:04:11', 0), (16, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (17, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (18, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (19, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (20, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (21, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (22, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (23, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:02:28', 0), (24, 0, 5686, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (25, 0, 5685, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (26, 0, 5688, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (27, 0, 5687, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (28, 0, 5690, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (29, 0, 5691, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (30, 0, 5692, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (31, 0, 5695, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:02:54', 0), (32, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (33, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (34, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (35, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (36, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (37, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (38, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (39, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:26:28', 0), (40, 0, 5686, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (41, 0, 5685, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (42, 0, 5688, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (43, 0, 5687, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (44, 0, 5690, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (45, 0, 5691, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (46, 0, 5692, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (47, 0, 5695, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:27:05', 0), (48, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (49, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (50, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (51, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (52, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (53, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (54, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (55, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:37:00', 0), (56, 0, 5686, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (57, 0, 5685, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (58, 0, 5688, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (59, 0, 5687, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (60, 0, 5690, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (61, 0, 5691, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (62, 0, 5692, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (63, 0, 5695, 'Il torneo <strong>amaro 18</strong> si e\' concluso', 'Chiuso un torneo se ne fa un altro', 0, '2010-09-20 16:38:06', 0), (64, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (65, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (66, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (67, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (68, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (69, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (70, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (71, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:41:40', 0), (72, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (73, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (74, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (75, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (76, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (77, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (78, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (79, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:42:35', 0), (80, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (81, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (82, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (83, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (84, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (85, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (86, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (87, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:43:14', 0), (88, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (89, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (90, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (91, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (92, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (93, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (94, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (95, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:44:50', 0), (96, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (97, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (98, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (99, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (100, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (101, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (102, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (103, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:46:35', 0), (104, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (105, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (106, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (107, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (108, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (109, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (110, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (111, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:05', 0), (112, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (113, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (114, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (115, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (116, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (117, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (118, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (119, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:47:54', 0), (120, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (121, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (122, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (123, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (124, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (125, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (126, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (127, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:07', 0), (128, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (129, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (130, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (131, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (132, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (133, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (134, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (135, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:53:46', 0), (136, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (137, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (138, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (139, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (140, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (141, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (142, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (143, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:54:35', 0), (144, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (145, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (146, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (147, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (148, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (149, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (150, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (151, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:05', 0), (152, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (153, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (154, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (155, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (156, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (157, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (158, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (159, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:55:29', 0), (160, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (161, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (162, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (163, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (164, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (165, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (166, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (167, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:56:05', 0), (168, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (169, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (170, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (171, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (172, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (173, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (174, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (175, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 16:59:28', 0), (176, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (177, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (178, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (179, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (180, 0, 5688, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (181, 0, 5691, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (182, 0, 5687, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (183, 0, 5695, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:32:16', 0), (184, 0, 5692, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:47:58', 0), (185, 0, 5690, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:47:58', 0), (186, 0, 5685, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:47:58', 0), (187, 0, 5686, 'Ti sei qualilficato ai playoff del torneo <strong>amaro 18</strong>', 'Corri a giocarti i playoff', 0, '2010-09-20 17:47:58', 0), (190, 0, 5689, '123 si &egrave; iscritto al tuo torneo <strong>pippelandia</strong>', '', 0, '2010-09-27 15:56:03', 0);
/*!40000 ALTER TABLE `messaggi` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.tornei
CREATE TABLE IF NOT EXISTS `tornei` (
  `id_torneo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_admin` bigint(20) unsigned NOT NULL,
  `nome` varchar(25) NOT NULL,
  `descrizione` text,
  `logo` varchar(255) DEFAULT NULL,
  `edizione` varchar(255) DEFAULT NULL,
  `ranking` int(10) unsigned DEFAULT '0',
  `premio` tinyint(3) unsigned DEFAULT '0',
  `pubblico` tinyint(3) unsigned NOT NULL,
  `playoff` tinyint(3) unsigned NOT NULL,
  `playoff_num` tinyint(3) unsigned NOT NULL,
  `girone_italiana` tinyint(3) unsigned NOT NULL,
  `tipo_partenza` tinyint(3) unsigned DEFAULT '0' COMMENT '0: data inizio fissa, 1:a raggiungimento partecipanti',
  `partecipanti_minimo` tinyint(3) unsigned DEFAULT NULL,
  `partecipanti_massimo` tinyint(3) unsigned DEFAULT NULL,
  `data_creazione` datetime DEFAULT NULL,
  `data_inizio` date DEFAULT NULL,
  `data_chiusura_campionato` date DEFAULT NULL,
  `data_chiusura` date DEFAULT NULL,
  `only_regione` varchar(255) DEFAULT NULL,
  `only_provincia` varchar(255) DEFAULT NULL,
  `only_eta_minima` varchar(255) DEFAULT NULL,
  `only_eta_massima` varchar(255) DEFAULT NULL,
  `only_sesso` varchar(1) DEFAULT NULL COMMENT 'x: entrambi, M: maschi, F: femmine',
  `only_utenti_ranking` varchar(255) DEFAULT NULL,
  `only_utenti_special_ranking` varchar(255) DEFAULT NULL,
  `only_utenti_gold` varchar(3) DEFAULT NULL COMMENT 'X: entrambi, 0: non gold, 1: gold',
  `stato` tinyint(3) unsigned DEFAULT '0' COMMENT '0: lanciato, 1: partito, 2: concluso, 3: annullato',
  `fase` tinyint(3) unsigned DEFAULT '0' COMMENT '0: campionato, 1: playoff',
  `fase_playoff` tinyint(3) unsigned DEFAULT '0',
  `id_vincitore` bigint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_torneo`),
  UNIQUE KEY `id_torneo` (`id_torneo`),
  KEY `id_torneo_2` (`id_torneo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.tornei: 0 rows
/*!40000 ALTER TABLE `tornei` DISABLE KEYS */;
/*!40000 ALTER TABLE `tornei` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.tornei_inviti
CREATE TABLE IF NOT EXISTS `tornei_inviti` (
  `id_invito` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `id_torneo` bigint(20) unsigned NOT NULL,
  `id_utente` bigint(20) unsigned NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `data_invito` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `stato` tinyint(3) unsigned DEFAULT '0' COMMENT '0: invito lanciato, 1: invito accettato, 2: invito rifiutato, 3: invito annullato',
  PRIMARY KEY (`id_invito`),
  UNIQUE KEY `id_invito` (`id_invito`),
  KEY `id_invito_2` (`id_invito`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.tornei_inviti: 0 rows
/*!40000 ALTER TABLE `tornei_inviti` DISABLE KEYS */;
/*!40000 ALTER TABLE `tornei_inviti` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.tornei_iscrizioni
CREATE TABLE IF NOT EXISTS `tornei_iscrizioni` (
  `id_iscrizione` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_torneo` bigint(20) unsigned NOT NULL,
  `id_utente` bigint(20) unsigned NOT NULL,
  `data_iscrizione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stato` tinyint(3) unsigned DEFAULT '0' COMMENT '0: richiesta iscrizione ma dispari (solo per tornei a inizio fissato), quindi in attesa, 1: iscritto',
  PRIMARY KEY (`id_iscrizione`),
  UNIQUE KEY `id_iscrizione` (`id_iscrizione`),
  KEY `id_iscrizione_2` (`id_iscrizione`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.tornei_iscrizioni: 0 rows
/*!40000 ALTER TABLE `tornei_iscrizioni` DISABLE KEYS */;
/*!40000 ALTER TABLE `tornei_iscrizioni` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.tornei_sfide
CREATE TABLE IF NOT EXISTS `tornei_sfide` (
  `id_sfida` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_torneo` bigint(20) unsigned NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `due_date` date DEFAULT NULL,
  `tipo_competizione` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0: campionato, 1: playoff',
  `fase_playoff` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_sfidante` bigint(20) unsigned NOT NULL,
  `id_sfidato` bigint(20) unsigned NOT NULL,
  `id_vincitore` bigint(20) unsigned NOT NULL,
  `a_tavolino` tinyint(20) unsigned NOT NULL COMMENT '0: no, 1: si',
  `data_lancio` datetime DEFAULT NULL,
  `data_risposta` datetime DEFAULT NULL,
  `data_chiusura` date DEFAULT NULL,
  `risultato` varchar(10) DEFAULT NULL COMMENT 'formato: 5,3',
  `stato` tinyint(3) unsigned DEFAULT '0' COMMENT '0: inserita, 1: lanciata, 2: risposta e  chiusa, 3: annullata, 4: persa tavolino sfidante, 5: persa tavolino sfidato',
  PRIMARY KEY (`id_sfida`),
  UNIQUE KEY `id_sfida` (`id_sfida`),
  KEY `id_sfida_2` (`id_sfida`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.tornei_sfide: 0 rows
/*!40000 ALTER TABLE `tornei_sfide` DISABLE KEYS */;
/*!40000 ALTER TABLE `tornei_sfide` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.tornei_voti
CREATE TABLE IF NOT EXISTS `tornei_voti` (
  `id_voto` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `id_torneo` bigint(20) unsigned NOT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  `voto` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_voto`),
  UNIQUE KEY `id_voto` (`id_voto`),
  KEY `id_voto_2` (`id_voto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.tornei_voti: 0 rows
/*!40000 ALTER TABLE `tornei_voti` DISABLE KEYS */;
/*!40000 ALTER TABLE `tornei_voti` ENABLE KEYS */;


# Dumping structure for table rigorix_rigorix.utente
CREATE TABLE IF NOT EXISTS `utente` (
  `id_utente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `cap` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL COMMENT 'DEPRECATO',
  `nome` varchar(255) DEFAULT NULL,
  `cognome` varchar(255) DEFAULT NULL,
  `data_nascita` date NOT NULL,
  `sesso` char(1) NOT NULL,
  `citta` varchar(255) DEFAULT NULL,
  `prov` varchar(2) DEFAULT NULL,
  `nazione` varchar(2) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `punteggio` float NOT NULL DEFAULT '0',
  `punteggio_totale` float unsigned NOT NULL DEFAULT '0',
  `ranking` int(10) unsigned NOT NULL DEFAULT '0',
  `special_ranking` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` tinyint(3) unsigned DEFAULT '0',
  `dollarix` tinyint(3) unsigned DEFAULT '0',
  `id_invitante` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dta_reg` datetime NOT NULL,
  `stato` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0: in registrazione, 1: registrato',
  `colore_maglietta` varchar(10) NOT NULL DEFAULT '#FF0000',
  `tipo_maglietta` tinyint(4) NOT NULL DEFAULT '1',
  `numero_maglietta` bigint(20) NOT NULL DEFAULT '1',
  `colore_pantaloncini` varchar(10) NOT NULL DEFAULT '#000000',
  `colore_calzini` varchar(10) NOT NULL DEFAULT '#FF0000',
  `dta_activ` datetime DEFAULT NULL,
  `hobby` text NOT NULL,
  `frase` text NOT NULL,
  `giocatore` varchar(255) NOT NULL,
  `squadra` varchar(255) NOT NULL,
  `tipo_alert` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) DEFAULT NULL,
  `ip_last_change` datetime DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  PRIMARY KEY (`id_utente`),
  KEY `dta_activ` (`dta_activ`)
) ENGINE=MyISAM AUTO_INCREMENT=5700 DEFAULT CHARSET=latin1;

# Dumping data for table rigorix_rigorix.utente: 15 rows
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` (`id_utente`, `username`, `passwd`, `picture`, `cap`, `mobile`, `nome`, `cognome`, `data_nascita`, `sesso`, `citta`, `prov`, `nazione`, `email`, `punteggio`, `punteggio_totale`, `ranking`, `special_ranking`, `gold`, `dollarix`, `id_invitante`, `dta_reg`, `stato`, `colore_maglietta`, `tipo_maglietta`, `numero_maglietta`, `colore_pantaloncini`, `colore_calzini`, `dta_activ`, `hobby`, `frase`, `giocatore`, `squadra`, `tipo_alert`, `ip`, `ip_last_change`, `last_login`) VALUES (5685, 'bitter', 'test', '', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, ' asdasd as asd', '', '', '', 0, NULL, NULL, NULL), (5686, 'asd', 'test', 'muriel_43453_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5687, 'qwe', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5688, 'zxc', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5689, '123', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5690, 'rty', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5691, 'fgh', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5692, 'vbn', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5693, 'uio', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5694, 'jkl', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5695, 'asdasd', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5696, 'qweqwe', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5697, 'zxczxc', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'F', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5698, '123123', 'test', 'conny_34533_7.jpg', NULL, '123123123', 'paolo', 'moretti', '1980-07-21', 'M', NULL, 'TV', 'AL', 'littlebrown@gmail.com', 0, 0, 0, 0, 0, 0, 0, '2010-08-23 11:32:11', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL), (5699, '111111', 'test', NULL, NULL, '1231231234', 'q1', 'q1', '1980-07-22', 'F', NULL, 'CH', 'BO', 'paolo@rigorix.com', 0, 0, 0, 0, 0, 0, 0, '2010-09-14 10:19:39', 1, '#FF0000', 1, 1, '#000000', '#FF0000', NULL, '', '', '', '', 0, NULL, NULL, NULL);
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
