<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Алексей
 */
class Post {

    static function Init() {
        switch (TC::$POST['send']) {
            case'game':
                if (TPL_INDEX::$USER == null)
                    return json_encode(array(false, TPL_LANGUAGE::$messages[0]));

                if (strlen(TC::$POST['gamepassword']) < 3 || strlen(TC::$POST['gamepassword']) > 16)
                    return json_encode(array(false, TPL_LANGUAGE::$messages[2]));
                $realm = 0;
                foreach (TPL_INDEX::$realms as $rea)
                    if (isset(TC::$POST['realm']) && $rea['title'] == TC::$POST['realm'])
                        $realm = $rea['id'];
                if (!$realm)
                    return json_encode(array(false, TPL_LANGUAGE::$messages[5]));
                if (!SCL_DATABASE::query(SQL_SET_ACCOUNT, TPL_INDEX::$realms[$realm]['auth'], TPL_INDEX::$USER['username'], sha1(TC::$POST['gamepassword']), TPL_INDEX::$USER['email']))
                    return json_encode(array(false, TPL_LANGUAGE::$messages[8]));
                $sql = SCL_DATABASE::selectCell(SQL_GET_ACCOUNT, TPL_INDEX::$realms[$realm]['auth'], TPL_INDEX::$USER['username']);
                if ($sql == null)
                    return json_encode(array(false, TPL_LANGUAGE::$messages[8]));
                if (TPL_INDEX::$USER['accounts'] == null)
                    TPL_INDEX::$USER['accounts'] = $realm . ':' . $sql;
                else {
                    $reacs = explode(' ', TPL_INDEX::$USER['accounts']);
                    $full = array();
                    $isfind = false;
                    foreach ($reacs as $pc) {
                        $real = explode(':', $pc);
                        if ($real[0] == $realm) {
                            $accounts = explode(',', $real[1]);
                            if (!in_array($sql, $accounts)) {
                                $pc = $real[0] . ':' . $real[1] . ',' . $sql;
                                $isfind = true;
                            }
                        }
                        $full[] = $pc;
                    }
                    if (!$isfind)
                        TPL_INDEX::$USER['accounts'] .= " " . $realm . ":" . $sql;
                    else
                        TPL_INDEX::$USER['accounts'] = implode(' ', $full);
                }
                if (SCL_DATABASE::query(SQL_SET_USER_ACCOUNT, TPL_INDEX::$USER['accounts'], TPL_INDEX::$USER['email']))
                    if (TC::$patch == 'Регистрация')
                        return json_encode(array(true, TPL_LANGUAGE::$messages[9]));
                    else
                        return json_encode(array(true, TPL_LANGUAGE::$messages[10]));
                break;
            case'off':
                setcookie('user', '', '-1', '/');
                return json_encode(array(true, TPL_LANGUAGE::$messages[11]));
                break;
            case'login':
                $user = SCL_DATABASE::selectRow(SQL_GET_USER, TC::$POST['username']);
                if ($user['password'] == md5(TC::$POST['password'])) {
                    $session = md5($user['username'] . '/' . $user['email'] . '/' . time());
                    setcookie('user', $session, strtotime('NEXT YEAR'), '/');
                    if (!SCL_DATABASE::query(SQL_SET_USER_SESSION, $session, $user['email']))
                        return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                    return json_encode(array(true, TPL_LANGUAGE::$messages[11]));
                }else
                    return json_encode(array(false, TPL_LANGUAGE::$messages[4]));
                break;
        }
        return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
    }

}

?>
