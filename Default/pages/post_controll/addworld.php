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
                    $adres = explode(':', TC::$POST['ip']);
                    $bdres = explode(':', TC::$POST['bhost']);
                    SCL_DATABASE::query(SQL_SET_WORLD, TC::$POST['name'], TC::$POST['limit'], $adres[0], $adres[1], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world'], $bdres[0], $bdres[1], TC::$POST['buser'], TC::$POST['bpass'], TC::$POST['id']);
                    $worl = new TC('controll/world');
                    $worl->set(array(
                        '{ID}' => TC::$POST['id']
                        , '{NAME}' => TC::$POST['name']
                        , '{MAX}' => TC::$POST['limit']
                        , '{IP}' => $adres[0]
                        , '{PORT}' => $adres[1]
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
                    $adres = explode(':', TC::$POST['ip']);
                    $bdres = explode(':', TC::$POST['bhost']);
                    foreach (TPL_INDEX::$realms as $realm)
                        if ($realm['title'] == TC::$POST['name'] || TC::$POST['name'] == null || TC::$POST['limit'] == null || $adres[0] == null || $adres[1] == null || $bdres[0] == null || $bdres[1] == null || TC::$POST['auth'] == null || TC::$POST['characters'] == null || TC::$POST['world'] == null || !is_numeric($adres[1]) || !is_numeric($bdres[1]) || !is_numeric(TC::$POST['limit']))
                            return json_encode(array(false, 'Не удалось добавить мир'));
                    SCL_DATABASE::query(SQL_ADD_WORLD, TC::$POST['name'], TC::$POST['limit'], $adres[0], $adres[1], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world'], $bdres[0], $bdres[1], TC::$POST['buser'], TC::$POST['bpass']);
                    $a = SCL_DATABASE::selectRow(SQL_GET_WORLD, TC::$POST['name'], TC::$POST['limit'], $adres[0], $adres[1], TC::$POST['auth'], TC::$POST['characters'], TC::$POST['world'], $bdres[0], $bdres[1], TC::$POST['buser'], TC::$POST['bpass']);
                    $addworld = new TC('controll/addworld');
                    $temp = $addworld->get(array('world' => 'WORLD'));
                    $temp = $temp['world'];
                    $worl = new TC($temp, true);
                    $worl->set(array(
                        '{ID}' => $a['id']
                        , '{NAME}' => TC::$POST['name']
                        , '{MAX}' => TC::$POST['limit']
                        , '{IP}' => $adres[0]
                        , '{PORT}' => $adres[1]
                        , '{AUTH}' => TC::$POST['auth']
                        , '{CHAR}' => TC::$POST['characters']
                        , '{WORLD}' => TC::$POST['world']
                    , '{BHOST}' => $bdres[0]
                    , '{BPORT}' => $bdres[1]
                    , '{BUSER}' => TC::$POST['buser']
                    , '{BPASS}' => TC::$POST['bpass']
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
