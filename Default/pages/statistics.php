<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function Init_tpl($patch, $args) {
    if (!is_array(TPL_INDEX::$realms))
        return '';
    $rea = TPL_INDEX::Realms($args, 'Статистика');
    $ponline = SCL_DATABASE::selectRow(SQL_GET_STATUS, $rea[1]);
    $characters = SCL_DATABASE::selectID(SQL_GET_CHARACTERS, 'guid', $rea[1], $rea[1], $rea[1]);
    $oll = count($characters);
    $array = array(
        array('nguild', 'guild', null, false)
        , array('gender', 'gender', 0, false)
        , array('aliance', 'race', array(1, 3, 4, 7, 11, 22, 25), false)
        , array('horge', 'race', array(2, 5, 6, 8, 9, 10, 26), false)
        , array('race', 'race', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 22, 25, 26), true)
        , array('class', 'class', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11), true)
    );
    $STAT = array();
    foreach ($characters as $char) {
        foreach ($array as $short) {
            if (is_array($short[2])) {
                if (in_array($char[$short[1]], $short[2]))
                    if ($short[3]) {
                        $STAT[$short[0]][$char[$short[1]]][0] = $char[$short[1]];
                        if (isset($STAT[$short[0]][$char[$short[1]]][1]))
                            $STAT[$short[0]][$char[$short[1]]][1]++;
                        else
                            $STAT[$short[0]][$char[$short[1]]][1] = 1;
                    }elseif (!isset($STAT[$short[0]]))
                        $STAT[$short[0]] = 1;
                    else
                        $STAT[$short[0]]++;
            }elseif ($char[$short[1]] == $short[2])
                if (!isset($STAT[$short[0]]))
                    $STAT[$short[0]] = 1;
                else
                    $STAT[$short[0]]++;
        }
        if ($char['leaderguid'] == $char['guid'])
            $STAT['guilds']++;
    }
    $statistics = new TC('pages/statistics');
    $setarray = array();
    foreach ($array[4][2] as $id) {
        if (!isset($STAT['race'][$id]))
            $STAT['race'][$id] = array($id, 0);
        $setarray['{RACE' . $STAT['race'][$id][0] . '}'] = $STAT['race'][$id][1];
        $setarray['{RACENAME' . $STAT['race'][$id][0] . '}'] = TPL_LANGUAGE::$race[$STAT['race'][$id][0]][0];
        $setarray['{RACECOLOR' . $STAT['race'][$id][0] . '}'] = TPL_LANGUAGE::$race[$STAT['race'][$id][0]][2];
    }
    foreach ($array[5][2] as $id) {
        if (!isset($STAT['class'][$id]))
            $STAT['class'][$id] = array($id, 0);
        $setarray['{CLASS' . $STAT['class'][$id][0] . '}'] = $STAT['class'][$id][1];
        $setarray['{CLASSNAME' . $STAT['class'][$id][0] . '}'] = TPL_LANGUAGE::$class[$STAT['class'][$id][0]][0];
        $setarray['{CLASSCOLOR' . $STAT['class'][$id][0] . '}'] = TPL_LANGUAGE::$class[$STAT['class'][$id][0]][1];
    }
    $statistics->set($setarray);
    $statistics->set(array(
        '{REALMS}' => $rea[0]
        , '{INGUILD}' => ($oll - $STAT['nguild'])
        , '{BIGUILD}' => $STAT['nguild']
        , '{PLAYERS}' => $oll
        , '{WOMEN}' => ($oll - $STAT['gender'])
        , '{MEN}' => $STAT['gender']
        , '{INGAME}' => $ponline['num']
        , '{BIGAME}' => ($oll - $ponline['num'])
        , '{ALIANCE}' => $STAT['aliance']
        , '{HORGE}' => $STAT['horge']
        , '{GUILDS}' => $STAT['guilds']
    ));
    return $statistics->render();
}

?>
