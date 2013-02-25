/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50529
Source Host           : localhost:3306
Source Database       : wcms

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2013-02-25 15:55:16
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `realms`
-- ----------------------------
DROP TABLE IF EXISTS `realms`;
CREATE TABLE `realms` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `discription` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `characters` varchar(255) NOT NULL,
  `world` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of realms
-- ----------------------------
INSERT INTO `realms` VALUES ('1', 'Дракономор', 'PvE сервер', '15', 'auth', 'chars', 'world', '127.0.0.1', '80');
INSERT INTO `realms` VALUES ('2', 'Лич', 'PvP сервер', '10', 'auth', 'characters', 'world', '127.0.0.1', '8085');
