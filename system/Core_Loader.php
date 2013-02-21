<?php

session_start();
error_reporting(E_ALL);
define('DIR', dirname(dirname(__FILE__)) . '\\');
if (!defined('DIR') || !DIR)
    die('<strong>Крит. Ошибка</strong>: не удалось определить базовый каталог!');
	
define('DEBUG', DIR . '_DEBUGS_/');
define('Sy_DIR', DIR . 'system/');
define('Temp_DIR', DIR . 'Temp/');

define('Core_DIR', Sy_DIR . 'core/');
define('Mo_DIR', Sy_DIR . 'modules/');
define('Sq_DIR', Sy_DIR . 'sql/');

define('Cl_DIR', Core_DIR . 'classes/');

include (Core_DIR . "core.php");
?>
