<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of INDEX
 *
 * @author Алексей
 */
class TPL_INDEX {

    public static $USER = NULL;
    public static $realms = NULL;
    public static $GROUP = NULL;

    static function Init() {
        self::$GROUP = SCL_DATABASE::selectID(SQL_GET_USER_GROUP, 'id');
        self::$realms = SCL_DATABASE::selectID(SQL_GET_REALMS, 'id');
        if (isset($_COOKIE['user'])) {
            self::$USER = SCL_DATABASE::selectRow(SQL_GET_USER_BY_SESSION, $_COOKIE['user']);
            if (self::$USER == null)
                setcookie('user', '', '-1', '/');
        }
    }

    static function Realms($args, $to) {
        $REHL = "";
        $bd = array();
        $title = "";
        foreach (self::$realms as $realm) {
            if ($args[2] == $realm['title']) {
                $REHL .= "<b>" . $realm['title'] . "</b> | ";
                $bd = array(
                    $realm['auth']
                    , $realm['characters']
                    , $realm['world']
                    , array(
                        $realm['bhost']
                        , $realm['bport']
                        , $realm['buser']
                        , $realm['bpass']
                    )
                );
                $title = $realm['title'];
            }else
                $REHL .="<a href='/" . $to . "/" . $realm['title'] . "/'>" . $realm['title'] . "</a> | ";
        }
        if ($bd == null) {
            $bd = array(
                self::$realms[1]['auth']
                , self::$realms[1]['characters']
                , self::$realms[1]['world']
                , array(
                    self::$realms[1]['bhost']
                    , self::$realms[1]['bport']
                    , self::$realms[1]['buser']
                    , self::$realms[1]['bpass']
                )
            );
            $title = self::$realms[1]['title'];
        }
        return array($REHL, $bd, $title);
    }

    static function MoneyFormat($money) {
        $gold = intval(substr($money, -11, -4));
        $silver = intval(substr($money, -4, -2));
        $copper = intval(substr($money, -2));
        $html = $gold . ' <img src="/system_media/images/gold.gif"> ';
        $html .= $silver . ' <img src="/system_media/images/silver.gif"> ';
        $html .= $copper . ' <img src="/system_media/images/copper.gif">';
        return $html;
    }

}

?>
