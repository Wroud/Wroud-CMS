<?php

define('SQL_GET_REALMS', "SELECT * FROM `BD_S`.`realms`");
define('SQL_GET_STATUS', "SELECT `name`,`race`,`gender`,`class`,COUNT(*) AS `num` FROM `%s`.`characters` WHERE `online`='1'");
define('SQL_GET_ONLINE', "SELECT `characters`.`guid`,`characters`.`name`,`race`,`gender`,`class`,`level`,`map`,`totaltime`,`guild`.`name` AS `guild`,`leaderguid` FROM `%s`.`characters` LEFT JOIN `%s`.`guild_member` ON `guild_member`.`guid`= `characters`.`guid` LEFT JOIN `%s`.`guild` ON `guild`.`guildid`=`guild_member`.`guildid` WHERE `online`='1' ORDER BY `name` LIMIT %d,%d");
define('SQL_GET_CHARACTERS', "SELECT `characters`.`guid`,`race`,`gender`,`class`,`guild`.`name` AS `guild`,`leaderguid` FROM `%s`.`characters` LEFT JOIN `%s`.`guild_member` ON `guild_member`.`guid`= `characters`.`guid` LEFT JOIN `%s`.`guild` ON `guild`.`guildid`=`guild_member`.`guildid");
define('SQL_GET_TOP', "SELECT `characters`.`guid`,`characters`.`name`,`race`,`gender`,`class`,`level`,`map`,`totaltime`,`money`,`totalkills`,`guild`.`name` AS `guild`,`leaderguid` FROM `%s`.`characters` LEFT JOIN `%s`.`guild_member` ON `guild_member`.`guid`= `characters`.`guid` LEFT JOIN `%s`.`guild` ON `guild`.`guildid`=`guild_member`.`guildid` ORDER BY `%s` DESC LIMIT %s,%s");
define('SQL_GET_NEWS', "SELECT `id`,`title`,`message` FROM `BD_S`.`news` ORDER BY `time` DESC LIMIT %d,%d");
define('SQL_GET_GUILDS', "SELECT `guildid`,`name`,`leaderguid`,`bankMoney` FROM `%s`.`guild` LIMIT %s,%s");
define('SQL_GET_LEADER_GUILDS', "SELECT `guid`,`name` FROM `%s`.`characters` WHERE `guid` IN (%s)");
define('SQL_GET_MEMBERS_GUILD', "SELECT `guildid`,count(`guid`)as `con` FROM `%s`.`guild_member` WHERE `guildid` IN (%s) GROUP BY `guildid`");
define('SQL_GET_GOUNT_GUILDS', "SELECT count(*) FROM `%s`.`guild`");
define('SQL_SET_NEWS', "UPDATE `BD_S`.`news` SET `title`='%s',`message`='%s' WHERE `id`='%s'");
define('SQL_ADD_NEWS', "INSERT INTO `BD_S`.`news` (`title`,`message`,`time`)VALUES('%s','%s','%s')");
define('SQL_DEL_NEWS', "DELETE FROM `BD_S`.`news` WHERE `id`='%s'");
define('SQL_GET_NEWSCO', "SELECT COUNT(*) AS `news` FROM `BD_S`.`news`");
define('SQL_SET_USER', "INSERT INTO `BD_S`.`users` (`username`,`password`,`email`,`group`)VALUES('%s','%s','%s',5)");
define('SQL_GET_PROVUSER', "SELECT COUNT(*) AS `user`,`email` FROM `BD_S`.`users` WHERE `email`='%s' OR `username`='%s'");
define('SQL_SET_USER_SESSION', "UPDATE `BD_S`.`users` SET `session`='%s' WHERE `email`='%s'");
define('SQL_GET_USER_BY_SESSION', "SELECT * FROM `BD_S`.`users` WHERE `session`='%s'");
define('SQL_SET_ACCOUNT', "INSERT INTO `%s`.`account` (`username`,`sha_pass_hash`,`email`,`expansion`) VALUES ('%s','%s','%s','3')");
define('SQL_SET_USER_ACCOUNT', "UPDATE `BD_S`.`users` SET `accounts`='%s' WHERE `email`='%s'");
define('SQL_GET_ACCOUNT', "SELECT * FROM `%s`.`account` WHERE `username`='%s'");
define('SQL_GET_USER_GROUP', "SELECT * FROM `BD_S`.`users_group`");
define('SQL_GET_USER', "SELECT `password`,`email`,`username` FROM `BD_S`.`users` WHERE `username`='%s'");
?>
