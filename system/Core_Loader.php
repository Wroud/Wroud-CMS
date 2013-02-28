<?php

session_start();
error_reporting(E_ALL);
define('S', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . S);
if (!defined('DIR') || !DIR)
    die('<strong>Крит. Ошибка</strong>: не удалось определить базовый каталог!');

define('DEBUG', DIR . '_DEBUGS_' . S);
define('Sy_DIR', DIR . 'system' . S);
define('Temp_DIR', DIR . 'Temp' . S);

define('Core_DIR', Sy_DIR . 'core' . S);
define('Mo_DIR', Sy_DIR . 'modules' . S);
define('Sq_DIR', Sy_DIR . 'sql' . S);
define('Me_DIR', Sy_DIR . 'media' . S);

define('Cl_DIR', Core_DIR . 'classes' . S);

include (Core_DIR . "core.php");
?>
