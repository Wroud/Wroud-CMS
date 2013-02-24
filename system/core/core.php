<?php

class Core {

    public static $settings = array();
    public static $Debug;
    public static $microtime;

    static function Init() {
        self::$microtime = microtime(true);
        include (Core_DIR . "Logging_System.php");
        include (Sy_DIR . "Configuration.php");
        self::$Debug = new LS('log', true);
        $patch = "";
        $args = array();
        if ($_SERVER['REQUEST_URI'] == "/") {
            $patch = "home";
        } else {
            $args = explode("/", urldecode($_SERVER['REQUEST_URI']));
            $patch = $args[1];
        }
        switch (strtolower($patch)) {
            case 'debug':
                self::$Debug->Get_Logs(LOGTOPAGE);
                break;
            case 'system_media':
                unset($args[0]);
                unset($args[1]);
                if (file_exists(Me_DIR . implode('/', $args))) {
                    $file_handle = fopen(Me_DIR . implode('/', $args), "r");
                    $sorce = "";
                    while (!feof($file_handle)) {
                        $sorce.= fgets($file_handle);
                    }
                    fclose($file_handle);
                    echo $sorce;
                }else
                    header("HTTP/1.0 404 Not Found");
                break;
            default :
                include (Core_DIR . "Template_Controller.php");
                spl_autoload_register('L_Cl');

                SCL_Sql::Init();
                SCL_DATABASE::Init();

                if (self::$settings['version'] < VERSION)
                    exit("<font style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;'>Поддерживаемая версия БД: <strong style='color:green;'>" . VERSION . "</strong><br/> Установленная версия БД: <strong style='color:red;'>" . self::$settings['version'] . "<br/>Обновите БД!   <a href='#' style='color:#0085FF;text-decoration:none'>Обновить</a></font>");

                define('LOCALE', self::$settings['locale']);
                define('We_DIR', DIR . self::$settings['template'] . '/');
                define('Te_DIR', We_DIR . 'Htmls/');

                $cms_Lo = (isset($_COOKIE['LOCALE']) ? $_COOKIE['LOCALE'] : LOCALE);
                setcookie('LOCALE', $cms_Lo, strtotime('NEXT YEAR'), '/');

                TC::Init($patch, $args);
                self::$Debug->End(LOGGING);
                break;
        }
    }

}

function L_Cl($className) {
    if (!preg_match('/swift_(.*)/i', $className)) {
        $type = explode("_", $className);
        $true = true;
        switch ($type[0]) {
            case 'SCL':
                $FileName = Cl_DIR . strtolower($type[1]) . '.php';
                break;
            case 'SMO':
                $FileName = Mo_DIR . strtolower($type[1]) . '.php';
                break;
            case 'TPL':
                $FileName = We_DIR . "Functions/" . strtolower($type[1]) . '.php';
                break;
            default :
                $true = false;
                break;
        }
        if ($true && file_exists($FileName))
            include ($FileName);
        else
            Core::$Debug->AddMessage('<strong>Заг. Классов крит. ошибка:</strong> при попытке загрузить ' . $className, null, 1, false);
    }
}

?>
