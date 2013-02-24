<?php

class SCL_Sql {

    public static function Init() {
        define('SQL_LOAD_SETTINGS', "SELECT * FROM `BD_S`.`cms_settings`");
        define('SQL_SHOW_TABLES', "SHOW TABLES FROM `BD_S`");
        define('SQL_GET_PAGE', "SELECT * FROM `BD_S`.`pages` WHERE `url`='%s'");
        define('SQL_', "");
    }

}

?>
