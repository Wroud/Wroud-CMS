<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of POST
 *
 * @author Алексей
 */
class TPL_POST {

    static function Init($patch, $POST) {
        $p="";
        switch ($patch) {
            case 'Игра':
                $p = 'user';
                break;
            case 'Регистрация':
                $p = 'register';
                break;
            case 'Аккаунт':
                $p = 'user';
                break;
            case 'Выход':
                $p = 'user';
                break;
            case 'Новости':
                $p = 'home';
                break;
        }

        if (file_exists(We_DIR . "pages/" . $p . ".php")) {
            include(We_DIR . "pages/" . $p . ".php");
            echo POST($patch, $POST);
        } else {
            $content = new TC('404');
            echo $content->render();
        }
    }

}

?>
