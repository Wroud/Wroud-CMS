<?php

class TC {

    private $content = NULL;
    public static $buffer = array();

    static function Init() {
        include (We_DIR . "Controllers/DEFINE.php");
        TPL_INDEX::Init();
        if ($_SERVER['REQUEST_URI'] == "/")
            TPL_TPL::Init("home", null);
        else {
            $args = explode("/", urldecode($_SERVER['REQUEST_URI']));
            switch ($args[1]) {
                case 'debug':
                    Core::$Debug->Get_Logs(100);
                    break;
                case 'xml':
                    TPL_XML::Init($args[1], $args);
                    break;
                default :
                    if ($_POST != NULL)
                        TPL_POST::Init($args[1], $_POST);
                    else
                        TPL_TPL::Init($args[1], $args);
                    break;
            }
        }
    }

    function TC($tpl_name, $bool = false) {
        if ($bool)
            $this->content = $tpl_name;
        elseif (array_key_exists($tpl_name, TC::$buffer))
            $this->content = TC::$buffer[$tpl_name];
        else {
            if (count(TC::$buffer) > 3)
                TC::$buffer = array();
            if (!file_exists(Te_DIR . $tpl_name . '.html')) {
                Core::$Debug->AddMessage('Файл шаблона не найден: ' . $tpl_name . '.html', null, 1, false);
                return;
            }
            $content = file_get_contents(Te_DIR . $tpl_name . '.html');
            TC::$buffer[$tpl_name] = $content;
            $this->content = $content;
        }
    }

    function set($vars) {

        foreach ($vars as $blockname => $value)
            $this->content = str_replace($blockname, $value, $this->content);
    }

    function set_preg($vars) {

        foreach ($vars as $blockname => $value)
            $this->content = preg_replace("'\[" . $blockname . "\](.*?)\[/" . $blockname . "\]'si", $value, $this->content);
    }

    function get($vars) {
        $res[] = null;
        foreach ($vars as $index => $value) {
            preg_match("'\[" . $value . "\](.*?)\[/" . $value . "\]'si", $this->content, $matches);
            $res[$index] = $matches[1];
        }
        return $res;
    }

    function render() {
        return $this->content;
    }

}

?>
