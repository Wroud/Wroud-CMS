<?php

class Page {

    function Init() {
        if (TPL_INDEX::$USER != null)
            header('Location:/');
        $HTML = "";
        if (!is_array(TPL_INDEX::$realms))
            return '';
        $register = new TC('pages/register');
        $temp = $register->get(array('server' => 'SERVER'));
        $temp = $temp['server'];
        foreach (TPL_INDEX::$realms as $realm) {
            $re = new TC($temp, true);
            $re->set(array('{TITLE}' => $realm['title']));
            $HTML .= $re->render();
        }
        $register->set_preg(array('SERVER' => $HTML));
        return $register->render();
    }

}

?>
