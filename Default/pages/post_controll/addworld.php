<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author Алексей
 */
class Post {

    public static function Init() {

        if (TPL_INDEX::$USER != null && TPL_INDEX::$USER['group'] == 1)
            switch (TC::$POST['send']) {
                case'edit':
                    SCL_DATABASE::query(SQL_SET_WORLD, TC::$POST['name'], TC::$POST['limit'], TC::$POST['ip'], TC::$POST['port'], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world'], TC::$POST['id']);
                    $worl = new TC('controll/world');
                    $worl->set(array(
                        '{ID}' => TC::$POST['id']
                        , '{NAME}' => TC::$POST['name']
                        , '{MAX}' => TC::$POST['limit']
                        , '{IP}' => TC::$POST['ip']
                        , '{PORT}' => TC::$POST['port']
                        , '{AUTH}' => TC::$POST['auth']
                        , '{CHAR}' => TC::$POST['characters']
                        , '{WORLD}' => TC::$POST['world']
                    ));
                    return json_encode(array('html', $worl->render(), "Мир отредактирован"));
                    break;
                case'delete':
                    SCL_DATABASE::query(SQL_DEL_WORLD, TC::$POST['id']);
                    return json_encode(array('html', " ", "Мир удалён"));
                    break;
                case'add':
                    foreach (TPL_INDEX::$realms as $realm)
                        if ($realm['title'] == TC::$POST['name'] || TC::$POST['name'] == null || TC::$POST['limit'] == null || TC::$POST['ip'] == null || TC::$POST['port'] == null || TC::$POST['auth'] == null || TC::$POST['characters'] == null || TC::$POST['world'] == null || !is_numeric(TC::$POST['port']) || !is_numeric(TC::$POST['limit']))
                            return json_encode(array(false, 'Не удалось добавить мир'));
                    SCL_DATABASE::query(SQL_ADD_WORLD, TC::$POST['name'], TC::$POST['limit'], TC::$POST['ip'], TC::$POST['port'], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world']);
                    $a = SCL_DATABASE::selectRow(SQL_GET_WORLD, TC::$POST['name'], TC::$POST['limit'], TC::$POST['ip'], TC::$POST['port'], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world']);
                    $addworld = new TC('controll/addworld');
                    $temp = $addworld->get(array('world' => 'WORLD'));
                    $temp = $temp['world'];
                    $worl = new TC($temp, true);
                    $worl->set(array(
                        '{ID}' => $a['id']
                        , '{NAME}' => TC::$POST['name']
                        , '{MAX}' => TC::$POST['limit']
                        , '{IP}' => TC::$POST['ip']
                        , '{PORT}' => TC::$POST['port']
                        , '{AUTH}' => TC::$POST['auth']
                        , '{CHAR}' => TC::$POST['characters']
                        , '{WORLD}' => TC::$POST['world']
                    ));
                    $html = $worl->render();
                    $html .= '<tr class="worlds" id ="new" style="display: none;"></tr>';
                    return json_encode(array('html', $html, "Мир добавлен"));
                    break;
            }
        return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
    }

}

?>
