<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DATE
 *
 * @author Алексей
 */
class SMO_DATE {

    static function date_name($type, $date = null) {

        $type = self::stripquotes($type);
        if (!$type)
            $type = 'YmdHis';

        switch ($type) {
            case 1:
                if (date('Y', $date) != date('Y', time()))
                    $type = 'j F Y в H:i';
                else
                    $type = 'j F в H:i';
                break;
            case 2:
                $type = 'j-F-Y H:i:s';
                break;
        }

        if (!$date)
            $date = time();

        if (date('Ymd', $date) == date('Ymd', time())) // Сегодня
            $output = 'Сегодня в ' . self::langdate('H:i', $date);
        elseif (date('Ymd', $date) == date('Ymd', (time() - 86400))) // Вчера
            $output = 'Вчера в ' . self::langdate('H:i', $date);
        else // Просто дата
            $output = self::langdate($type, $date);

        return $output;
    }

    static function stripquotes($text) {
        return str_replace(array("'", '"'), '', stripslashes(trim($text)));
    }
    static function langdate($format, $stamp = false) {
        $langdate = array(
            'January' => 'Января',
            'February' => 'Февраля',
            'March' => 'Марта',
            'April' => 'Апреля',
            'May' => 'Мая',
            'June' => 'Июня',
            'July' => 'Июля',
            'August' => 'Августа',
            'September' => 'Сентября',
            'October' => 'Октября',
            'November' => 'Ноября',
            'December' => 'Декабря',
            '_01' => 'января',
            '_02' => 'февраля',
            '_03' => 'марта',
            '_04' => 'апреля',
            '_05' => 'мая',
            '_06' => 'июня',
            '_07' => 'июля',
            '_08' => 'августа',
            '_09' => 'сентября',
            '_10' => 'октября',
            '_11' => 'ноября',
            '_12' => 'декабря',
            'Jan' => 'янв',
            'Feb' => 'фев',
            'Mar' => 'мар',
            'Apr' => 'апр',
            'May' => 'мая',
            'Jun' => 'июн',
            'Jul' => 'июл',
            'Aug' => 'авг',
            'Sep' => 'сен',
            'Oct' => 'окт',
            'Nov' => 'ноя',
            'Dec' => 'дек',
            'Sunday' => 'Воскресенье',
            'Monday' => 'Понедельник',
            'Tuesday' => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday' => 'Четверг',
            'Friday' => 'Пятница',
            'Saturday' => 'Суббота',
            'Sun' => 'Вс',
            'Mon' => 'Пн',
            'Tue' => 'Вт',
            'Wed' => 'Ср',
            'Thu' => 'Чт',
            'Fri' => 'Пт',
            'Sat' => 'Сб',
            'm_01' => 'Январь',
            'm_02' => 'Февраль',
            'm_03' => 'Март',
            'm_04' => 'Апрель',
            'm_05' => 'Май',
            'm_06' => 'Июнь',
            'm_07' => 'Июль',
            'm_08' => 'Август',
            'm_09' => 'Сентябрь',
            'm_10' => 'Октябрь',
            'm_11' => 'Ноябрь',
            'm_12' => 'Декабрь',
            'sm_01' => 'январь',
            'sm_02' => 'февраль',
            'sm_03' => 'март',
            'sm_04' => 'апрель',
            'sm_05' => 'май',
            'sm_06' => 'июнь',
            'sm_07' => 'июль',
            'sm_08' => 'август',
            'sm_09' => 'сентябрь',
            'sm_10' => 'октябрь',
            'sm_11' => 'ноябрь',
            'sm_12' => 'декабрь'
        );

        if (!$stamp)
            $stamp = time();
        return strtr(@date($format, $stamp), $langdate);
    }
}

?>
