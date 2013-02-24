<?php

class TC {

    private $content = NULL;
    public static $buffer = array();
    public static $patch = "";
    public static $args = array();
    public static $POST = array();

    static function Init($pa, $ar) {
        self::$patch = $pa;
        self::$args = $ar;
        self::$POST = $_POST;
        include (We_DIR . "Functions/DEFINE.php");
        TPL_INDEX::Init();

        $page = SCL_DATABASE::selectRow(SQL_GET_PAGE, self::$patch);
        if ($page == null || !file_exists(We_DIR . "pages/" . $page['type'] . "/" . $page['name'] . ".php")) {
            $content = new TC('404');
            echo $content->render();
        } else {
            include (We_DIR . "pages/" . $page['type'] . "/" . $page['name'] . ".php");
            $content = null;
            switch ($page['type']) {
                case 'xml':
                    echo Xml::Init();
                    break;
                case 'post':
                    if (self::$POST != NULL)
                        echo Post::Init();
                    break;
                case 'html':
                    $content = Page::Init();
                    include(We_DIR . "pages/index.php");
                    $index = new TC('index');
                    $index = Init_tpl_i($index, $content);
                    echo $index->render();
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
