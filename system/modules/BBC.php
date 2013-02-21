<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BBC
 *
 * @author Алексей
 */
class SMO_BBC {

    static $text;

    static function parse($text, $un = false) {
        self::$text = $text;
        self::$text = self::replace(array(
                    '[b]' => '<b>'
                    , '[/b]' => '</b>'
                    , '[u]' => '<u>'
                    , '[/u]' => '</u>'
                    , '[i]' => '<i>'
                    , '[/i]' => '</i>'
                        ), $un);
        if ($un)
            self::$text = self::replace(array(
                        '!<div align="center">(.*?)</div>!si' => '[c]$1[/c]'
                        , '!<div align="left">(.*?)</div>!si' => '[l]$1[/l]'
                        , '!<div align="right">(.*?)</div>!si' => '[r]$1[/r]'
                        , '!<font color="(.*?)">(.*?)</font>!si' => '[color=$1]$2[/color]'
                        , '!<font size="(.*?)">(.*?)</font>!si' => '[size=$1]$2[/size]'
                        , '!<img src="(.*?)">!si' => '[img]$1[/img]'
                        , '!<a href="(.*?)">(.*?)</a>!si' => '[url=$1]$2[/url]'
                            ), true, true);
        else
            self::$text = self::replace(array(
                        '!\[c\](.*?)\[/c\]!si' => '<div align="center">$1</div>'
                        , '!\[l\](.*?)\[/l\]!si' => '<div align="left">$1</div>'
                        , '!\[r\](.*?)\[/r\]!si' => '<div align="right">$1</div>'
                        , '!\[color=(.*?)\](.*?)\[/color\]!si' => '<font color="$1">$2</font>'
                        , '!\[size=(.*?)\](.*?)\[/size\]!si' => '<font size="$1">$2</font>'
                        , '!\[img\](.*?)\[/img\]!si' => '<img src="$1">'
                        , '!\[url=(.*?)\](.*?)\[/url\]!si' => '<a href="$1">$2</a>'
                            ), false, true);

        return self::$text;
    }

    static function replace($vars, $un, $preg = false) {
        foreach ($vars as $blockname => $value) {
            if ($un) {
                if ($preg)
                    self::$text = preg_replace($blockname, $value, self::$text);
                else
                    self::$text = str_replace($value, $blockname, self::$text);
            }elseif ($preg)
                self::$text = preg_replace($blockname, $value, self::$text);
            else
                self::$text = str_replace($blockname, $value, self::$text);
        }
        return self::$text;
    }

}

?>
