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
        $page = ($page != null) ? $page : 0;
        $pagging = '';
        if ($newsnum > $tup) {
            $pagging = '<div style="margin:5px -3px 0 0;"><div class="page">';
            $pnum = ceil(($newsnum + $tup) / $tup);
            $prer = ($pnum > 10) ? true : false;
            if ($page > 8) {
                for ($i = 1; $i < $pnum; $i++) {
                    $a = $i * $tup;
                    if ($page == ($i - 1))
                        $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                    else
                        $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
                    if ($prer && $i == 2)
                        break;
                }
                if ($page <= $pnum - 3) {
                    $pagging .= '<span class="swch"><span>...</span></span>';
                    $min = ($page > $pnum - 6) ? (8 + ($page - $pnum + 2)) : 3;
                    for ($i = $page - $min; $i < $pnum - 2; $i++) {
                        $a = $i * $tup;
                        if ($page == ($i - 1))
                            $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                        else
                            $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
                        if ($prer && $i == $page + 4)
                            break;
                    }
                }
            }else
                for ($i = 1; $i < $pnum; $i++) {
                    $a = $i * $tup;
                    if ($page == ($i - 1))
                        $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                    else
                        $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
                    if ($prer && $i == 10)
                        break;
                }
            if ($prer) {
                $pagging .= '<span class="swch"><span>...</span></span>';
                if ($page >= $pnum - 3)
                    $pgs = ($pnum - 10);
                else
                    $pgs = ($pnum - 2);
                for ($i = $pgs; $i < $pnum; $i++) {
                    $a = $i * $tup;
                    if ($page == ($i - 1))
                        $pagging .= '<b><span>' . ($a - $tup + 1) . '-' . $a . '</span></b>';
                    else
                        $pagging .='<a href="' . $url . ($i - 1) . '" rel="nofollow"><span>' . ($a - $tup + 1) . '-' . $a . '</span></a>';
                }
            }
            $pagging .= '</div></div>';
        }
        return $pagging;
    }

}

?>
