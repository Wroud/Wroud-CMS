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
class Xml {

    public static function Init() {
        $HTML = "";
        if (is_array(TPL_INDEX::$realms))
            foreach (TPL_INDEX::$realms as $realm) {
                $stat = "off";
                $database = new SCL_MINISQL(array($realm['bhost'], $realm['bport'], $realm['buser'], $realm['bpass']));
                $online = $database->selectRow(SQL_GET_STATUS, $realm['characters']);
                if(!$online)
                    continue;
                if (@fsockopen($realm['ip'], $realm['port'])) {
                    $stat = "on";
                }
                $status = new TC('blocks/status');
                $status->set(array(
                    '{TITLE}' => $realm['title']
                    , '{ONLINE}' => $online['num'] . ' / ' . $realm['limit']
                    , '{ROUND}' => round($online['num'] / (($online['num'] > $realm['limit']) ? $online['num'] : $realm['limit']), 2) * 100
                    , '{STATUS}' => $stat
                ));
                $HTML.=$status->render();
            }
        return $HTML;
    }

}

?>
