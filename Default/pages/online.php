<?php

function Init_tpl($patch, $args) {
    if (!is_array(TPL_INDEX::$realms))
        return '';
    $page = (isset($args[3])) ? $args[3] : 0;
    $page = (is_numeric($page)) ? $page : 0;
    $PLHL = "";
    $bd = "";
    $title = "";
    $rea = TPL_INDEX::Realms($args, 'Онлайн');

    $players = SCL_DATABASE::select(SQL_GET_ONLINE, $rea[1], $rea[1], $rea[1], PLPAGE * $page, PLPAGE);
    $ponline = SCL_DATABASE::selectRow(SQL_GET_STATUS, $rea[1]);
    $online = new TC('pages/online');
    $temp = $online->get(array('player' => 'PLAYER'));
    $temp = $temp['player'];
    if (is_array($players))
        foreach ($players as $player) {
            $status = new TC($temp, true);
            $guild = ($player['leaderguid'] == $player['guid']) ? "Лидер " . $player['guild'] : $player['guild'];
            $status->set(array(
                '{NAME}' => $player['name']
                , '{LEVEL}' => $player['level']
                , '{RACEIMG}' => SMO_Parser::Image('/images/wow/race/' . $player['race'] . '-' . $player['gender'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                , '{RACE}' => $player['race']
                , '{RACENAME}' => TPL_LANGUAGE::$race[$player['race']][0]
                , '{CLASS}' => $player['class']
                , '{CLASSNAME}' => TPL_LANGUAGE::$class[$player['class']][0]
                , '{CLASSIMG}' => SMO_Parser::Image('/images/wow/class/' . $player['class'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                , '{GENDER}' => $player['gender']
                , '{GUILD}' => ($guild == null) ? "Не состоит" : $guild
                , '{MAP}' => TPL_LANGUAGE::$map[$player['map']]
                , '{TIME}' => date("Hч. iм. sс.", $player['totaltime'])
            ));
            $PLHL.=$status->render();
        }
    $online->set(array(
        '{REALMS}' => $rea[0]
        , '{PAGE}' => SMO_Tools::PG($ponline['num'], PLPAGE, '/Онлайн/' . $rea[2] . '/', $page)
    ));
    $online->set_preg(array('PLAYER' => $PLHL));
    return $online->render();
}

?>
