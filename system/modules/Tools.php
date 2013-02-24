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
        $max = floor($newsnum / $tup);

        if ($newsnum % $tup > 0)
            $max++;
        $mimax = $max - 1;

        if ($page == 0)
            $pagging .= '<span class="first">1-' . $tup . '</span>';
        else
            $pagging .='<a href="' . $url . '0" class="first" rel="nofollow">1-' . $tup . '</a>';

        if ($max > 1) {
            if ($page >= $mimax - 6)
                $start = ($mimax - 6 < 2) ? 2 : $mimax - 6;
            else
                $start = ($page - 2 < 2) ? 2 : $page - 2;
            $last = 1;
            if ($start > 2 && 2 < $mimax)
                $pagging .= '<span class="swch">...</span>';
            for ($i = $start; $i < $start + 7; $i++) {
                if ($i == $max)
                    break;
                $a = $i * $tup;
                if ($page == ($i - 1))
                    $pagging .= '<span>' . ($a - $tup + 1) . '-' . $a . '</span>';
                else
                    $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow">' . ($a - $tup + 1) . '-' . $a . '</a>';
                $last = $i;
            }
            if ($last < $mimax)
                $pagging .= '<span class="swch">...</span>';
            if ($page == $mimax)
                $pagging .= '<span class="last">' . (($mimax * $tup) + 1) . '-' . $newsnum . '</span>';
            else
                $pagging .='<a href="' . $url . $mimax . '" rel="nofollow" class="last">' . (($mimax * $tup) + 1) . '-' . $newsnum . '</a>';
        }
        $pagging .= '</div></div>';
        return $pagging;
    }

}

?>
