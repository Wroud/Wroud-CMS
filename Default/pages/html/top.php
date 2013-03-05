<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Page {

    static function Init() {
        if (!is_array(TPL_INDEX::$realms))
            return '';
        $page = (isset(TC::$args[4])) ? TC::$args[4] : 0;
        $page = (is_numeric($page)) ? $page : 0;
        $page = ($page < 0) ? 0 : $page;
        $top = (isset(TC::$args[3])) ? TC::$args[3] : '';
        $PLHL = "";
        $TOHL = "";
        $column = "";
        $tname = "";
        $url = "";
        $realms = TPL_INDEX::Realms(TC::$args, 'Топ');
        $tops = array(
            'Богачи' => array('Богачи', 'money', 'Золото')
            , 'Убийства' => array('Убийства', 'totalkills', 'Убийства')
            , 'Время в игре' => array('Время в игре', 'totaltime', 'Время в игре')
        );
        foreach ($tops as $to) {
            if ($to[0] == $top) {
                $TOHL .= "<b>" . $to[0] . "</b> | ";
                $column = $to[1];
                $tname = $to[2];
                $url = $to[0];
            }else
                $TOHL .= "<a href='/Топ/" . $realms[2] . "/" . $to[0] . "'>" . $to[0] . "</a> | ";
        }
        if ($column == "") {
            $url = $tops['Время в игре'][0];
            $column = $tops['Время в игре'][1];
            $tname = $tops['Время в игре'][2];
        }

        $database = new SCL_MINISQL($realms[1][3]);
        $ponline = $database->select(SQL_GET_CHARACTERS, $realms[1][1], $realms[1][1], $realms[1][1]);
        $ponline = count($ponline);
        $page = ($page > floor($ponline / PLPAGE)) ? floor($ponline / PLPAGE) : $page;
        $players = $database->select(SQL_GET_TOP, $realms[1][1], $realms[1][1], $realms[1][1], $column, PLPAGE * $page, PLPAGE);
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
                    , '{TOP}' => ($column == 'money') ? TPL_INDEX::MoneyFormat($player[$column]) : (($column == 'totaltime') ? TPL_Top::ttime($player[$column]) : $player[$column])
                    , '{LEVEL}' => $player['level']
                    , '{RACE}' => $player['race']
                    , '{RACEIMG}' => SMO_Parser::Image('/images/wow/race/' . $player['race'] . '-' . $player['gender'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                    , '{RACENAME}' => TPL_LANGUAGE::$race[$player['race']][0]
                    , '{CLASS}' => $player['class']
                    , '{CLASSIMG}' => SMO_Parser::Image('/images/wow/class/' . $player['class'] . '.jpg', 'http://eu.battle.net/wow/static/local-common/')
                    , '{CLASSNAME}' => TPL_LANGUAGE::$class[$player['class']][0]
                    , '{GUILD}' => ($guild == null) ? "Не состоит" : $guild
                    , '{MAP}' => TPL_LANGUAGE::$map[$player['map']]
                ));
                $PLHL.=$status->render();
                $i++;
            }
        $top->set(array(
            '{REALMS}' => $realms[0]
            , '{TNAME}' => $tname
            , '{TOPS}' => $TOHL
            , '{PAGE}' => SMO_Tools::PG($ponline, PLPAGE, '/Топ/' . $realms[2] . '/' . $url . '/', $page)
        ));
        $top->set_preg(array('PLAYER' => $PLHL));
        return $top->render();
    }

}

class TPL_Top {

    public static function ttime($value) {
        $hh = floor($value / 3600);
        $min = floor(($value - $hh * 3600) / 60);
        $sec = $value - $hh * 3600 - $min * 60;
        $l = sprintf('%02d', $hh) . ' ч. ' . sprintf('%02d', $min) . ' м. ' . sprintf('%02d', $sec) . ' с.';
        return $l;
    }

}

?>
