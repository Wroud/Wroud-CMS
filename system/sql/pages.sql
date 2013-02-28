/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50529
Source Host           : localhost:3306
Source Database       : wcms

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2013-02-28 17:17:08
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `pages`
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES ('home', 'home', 'html', '1');
INSERT INTO `pages` VALUES ('worlds', 'worlds', 'xml', '1');
INSERT INTO `pages` VALUES ('Аккаунт', 'user', 'post', '1');
INSERT INTO `pages` VALUES ('Выход', 'user', 'post', '1');
INSERT INTO `pages` VALUES ('Гильдии', 'guild', 'html', '1');
INSERT INTO `pages` VALUES ('Добавить мир', 'addworld', 'post_controll', '1');
INSERT INTO `pages` VALUES ('Игра', 'user', 'post', '1');
INSERT INTO `pages` VALUES ('Миры', 'addworld', 'controll', '1');
INSERT INTO `pages` VALUES ('Новости', 'home', 'post', '1');
INSERT INTO `pages` VALUES ('Онлайн', 'online', 'html', '1');
INSERT INTO `pages` VALUES ('Регистрация', 'register', 'html', '1');
INSERT INTO `pages` VALUES ('Регистрацияp', 'register', 'post', '1');
INSERT INTO `pages` VALUES ('Редактировать мир', 'addworld', 'post_controll', '1');
INSERT INTO `pages` VALUES ('Статистика', 'statistics', 'html', '1');
INSERT INTO `pages` VALUES ('Страница', 'home', 'html', '1');
INSERT INTO `pages` VALUES ('Топ', 'top', 'html', '1');
INSERT INTO `pages` VALUES ('Удалить мир', 'addworld', 'post_controll', '1');
