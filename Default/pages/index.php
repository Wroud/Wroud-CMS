<?php

function Init_tpl_i($index, $content) {
    $login = new TC('blocks/login');
    $block[] = null;
    if (TPL_INDEX::$USER != null) {
        $temp = $login->get(array('server' => 'SERVER'));
        $temp = $temp['server'];
        $login->set_preg(array('SERVER' => '{SERVER}'));
        $login->set(IN_Index::Login($temp));
        $block = array('NOLOGIN', 'LOGIN');
    } else
        $block = array('LOGIN', 'NOLOGIN');
    $login->set_preg(array($block[0] => ''));
    $login->set(array('[' . $block[1] . ']' => '', '[/' . $block[1] . ']' => ''));

    $index->set(array(
        '{TITLE}' => Core::$settings['name']
        , '{LOGIN}' => $login->render()
        , '{CONTENT}' => $content
        , '{LOAD}' => round(microtime(true) - Core::$microtime, 4)
    ));

    return $index;
}

class IN_Index {

    static function Login($temp) {
        $HTML = "";
        if (is_array(TPL_INDEX::$realms))
            foreach (TPL_INDEX::$realms as $realm) {
                $re = new TC($temp, true);
                $re->set(array('{TITLE}' => $realm['title']));
                $HTML .= $re->render();
            }
        return array(
            '{NAME}' => TPL_INDEX::$USER['username']
            , '{GROUP}' => TPL_INDEX::$GROUP[TPL_INDEX::$USER['group']]['name']
            , '{SERVER}' => $HTML
        );
    }

}

?>
