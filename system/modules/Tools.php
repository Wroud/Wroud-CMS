<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author Алексей
 */
class SMO_Tools {

    static function PG($newsnum, $tup, $url, $page) {
        if ($newsnum < $tup)
            return "";
        $pagging = '<div style="margin:5px -3px 0 0;"><div class="page">';
        $max = round(($newsnum + $tup) / $tup);

        for ($i = 1; $i < 3; $i++) {
            $a = $i * $tup;
            if ($page == ($i - 1))
                $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
            else
                $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
        }
        if ($max > 2) {

            $start = ($page - 2 < 3) ? 3 : $page - 2;
            if ($start > 3)
                $pagging .= '<span class="swch"><span>...</span></span>';
            for ($i = $start; $i < $page + 5; $i++) {
                if ($i > $max - 3)
                    break;
                $a = $i * $tup;
                if ($page == ($i - 1))
                    $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                else
                    $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
            }
            if ($start + 7 < $max - 2)
                $pagging .= '<span class="swch"><span>...</span></span>';
            for ($i = $max - 2; $i < $max; $i++) {
                $a = $i * $tup;
                if ($page == ($i - 1))
                    $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                else
                    $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
            }
        }
        $pagging .= '</div></div>';
        return $pagging;
    }

}

?>
