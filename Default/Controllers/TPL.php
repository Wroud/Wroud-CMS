<?php

class TPL_TPL {

    static function Init($patch, $args) {
        $p = $patch;
        switch ($patch) {
            case 'Онлайн':
                $p = 'online';
                break;
            case 'Регистрация':
                if (TPL_INDEX::$USER != null)
                    header('Location:/');
                $p = 'register';
                break;
            case 'Страница':
                $p = 'home';
                break;
            case 'Топ':
                $p = 'top';
                break;
            case'Статистика':
                $p = 'statistics';
                break;
            case'Гильдии':
                $p = 'guild';
                break;
        }

        if (file_exists(We_DIR . "pages/" . $p . ".php")) {
            include(We_DIR . "pages/" . $p . ".php");
            $content = Init_tpl($patch, $args);
        } else {
            $content = new TC('404');
            $content = $content->render();
        }
        include(We_DIR . "pages/index.php");
        $index = new TC('index');
        $index = Init_tpl_i($index, $content, $patch);
        echo $index->render();
    }

}

?>
