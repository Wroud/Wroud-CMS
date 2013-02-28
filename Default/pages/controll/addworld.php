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
class Page {

    public static function Init() {
        $addworld = new TC('controll/addworld');
        $temp = $addworld->get(array('world' => 'WORLD'));
        $temp = $temp['world'];
        $HTML = "";

        if (TPL_INDEX::$realms != null)
            foreach (TPL_INDEX::$realms as $world) {
                $worl = new TC($temp, true);
                $worl->set(array(
                    '{ID}' => $world['id']
                    , '{NAME}' => $world['title']
                    , '{MAX}' => $world['limit']
                    , '{IP}' => $world['ip']
                    , '{PORT}' => $world['port']
                    , '{AUTH}' => $world['auth']
                    , '{CHAR}' => $world['characters']
                    , '{WORLD}' => $world['world']
                    , '{DESCR}' => $world['discription']
                ));
                $HTML .= $worl->render();
            }
        $addworld->set_preg(array('WORLD' => $HTML));
        return $addworld->render();
    }

}

?>
