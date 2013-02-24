/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : cms_site

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2012-09-11 15:24:46
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cms_settings`
-- ----------------------------
DROP TABLE IF EXISTS `cms_settings`;
CREATE TABLE `cms_settings` (
  `name` text NOT NULL,
  `version` text NOT NULL,
  `template` text NOT NULL,
  `locale` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_settings
-- ----------------------------
INSERT INTO `cms_settings` VALUES ('World of Warcraft', '0.03', 'Default', 'ru_RU');
