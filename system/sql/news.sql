/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50529
Source Host           : localhost:3306
Source Database       : wcms

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2013-02-10 19:21:54
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES ('1', 'Название тестовой новости', 'А тут длинный текст длдывфд лжфывжлдвыдл жфывждлыф олыфлшов лыфвд фшлыов дшлфыовдшл фыовдшло фыдшлво ышдлфво шлыфво шлыво ыфдвшо дыфшловдыфшлов шфыов дшлыофв шлоыв шолыфвдошл ывфдвыф ошдыфв дшолыфвд шоыфвдшолвыфшо фдывшодвшоы шдофывошфывошдывфошв фыошфыво ыфв дфыошвыф дыф как-то так!', '0');
INSERT INTO `news` VALUES ('2', 'Название тестовой новости', 'А тут длинный текст длдывфд лжфывжлдвыдл жфывждлыф олыфлшов лыфвд фшлыов дшлфыовдшл фыовдшло фыдшлво ышдлфво шлыфво шлыво ыфдвшо дыфшловдыфшлов шфыов дшлыофв шлоыв шолыфвдошл ывфдвыф ошдыфв дшолыфвд шоыфвдшолвыфшо фдывшодвшоы шдофывошфывошдывфошв фыошфыво ыфв дфыошвыф дыф как-то так!', '1');
INSERT INTO `news` VALUES ('3', 'Тест название новости', 'Тест текст новости', '112');
INSERT INTO `news` VALUES ('4', 'Тест 2 название новости', 'Тест 2 содержание новости', '1360500526');
