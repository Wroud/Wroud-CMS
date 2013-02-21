<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function Init_tpl($patch, $args) {
    if (!is_array(TPL_INDEX::$realms))
        return '';
    $page = (isset($args[4])) ? $args[4] : 0;
    $page = (is_numeric($page)) ? $page : 0;
    $top = (isset($args[3])) ? $args[3] : '';
    $PLHL = "";
    $TOHL = "";
    $column = "";
    $tname = "";
        $realms = TPL_INDEX::Realms($args, 'Топ');
    switch ($top) {
        case'Богачи':
            $column = 'money';
            $tname = 'Золото';
            break;
        case'Убийства':
            $column = 'totalkills';
            $tname = 'Убийств';
            break;
        default :
            $column = 'level';
            $tname = 'Уровень';
            break;
    }
    $TOHL = "<a href='/Топ/" . $realms[2] . "/Уровень/'>Уровень</a> | ";
    $TOHL.= "<a href='/Топ/" . $realms[2] . "/Богачи/'>Богачи</a> | ";
    $TOHL.= "<a href='/Топ/" . $realms[2] . "/Убийства/'>Убийства</a>";

    $players = SCL_DATABASE::select(SQL_GET_TOP, $realms[1], $realms[1], $realms[1], $column, PLPAGE * $page, PLPAGE * ($page + 1));
    $ponline = SCL_DATABASE::selectRow(SQL_GET_STATUS, $realms[1]);
    $top = new TC('pages/top');
    $temp = $top->get(array('player' => 'PLAYER'));
    $temp = $temp['player'];
    $i = 1;
    if (is_array($players))
        foreach ($players as $player) {
            $status = new TC($temp, true);
            $guild = ($player['leaderguid'] == $player['guid']) ? "Лидер " . $player['guild'] : $player['guild'];
            $status->set(array(
                '{NAME}' => $player['name']
                , '{POS}' => PLPAGE * $page + $i
                , '{TOP}' => ($column == 'money') ? TPL_INDEX::MoneyFormat($player[$column]) : $player[$column]
                , '{LEVEL}' => $player['level']
                , '{RACE}' => $player['race']
                , '{RACEIMG}' => SMO_Parser::Image('/images/wow/race/' . $player['race'] . '-' . $player['gender'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                , '{RACENAME}' => TPL_LANGUAGE::$race[$player['race']][0]
                , '{CLASS}' => $player['class']
                , '{CLASSIMG}' => SMO_Parser::Image('/images/wow/class/' . $player['class'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                , '{CLASSNAME}' => TPL_LANGUAGE::$class[$player['class']][0]
                , '{GUILD}' => ($guild == null) ? "Не состоит" : $guild
                , '{MAP}' => TPL_LANGUAGE::$map[$player['map']]
                , '{TIME}' => date("Hч. iм. sс.", $player['totaltime'])
            ));
            $PLHL.=$status->render();
            $i++;
        }
    $top->set(array(
        '{REALMS}' => $realms[0]
        , '{TNAME}' => $tname
        , '{TOPS}' => $TOHL
        , '{PAGE}' => SMO_Tools::PG($ponline['num'], PLPAGE, '/Топ/' . $realms[2] . '/' . $tname . '/', $page)
    ));
    $top->set_preg(array('PLAYER' => $PLHL));
    return $top->render();
}

?>
