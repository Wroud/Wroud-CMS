<?php

function Init_tpl($patch, $args) {
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

function POST($patch, $POST) {
    if ($POST['send'] == 'reg' && TPL_INDEX::$USER == null) {
        if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $POST['email']))
            return json_encode(array(false, TPL_LANGUAGE::$messages[3]));

        if (strlen($POST['username']) < 3 || strlen($POST['username']) > 10)
            return json_encode(array(false, TPL_LANGUAGE::$messages[1]));

        if (strlen($POST['password']) < 3 || strlen($POST['password']) > 16)
            return json_encode(array(false, TPL_LANGUAGE::$messages[2]));

        $user = SCL_DATABASE::selectRow(SQL_GET_PROVUSER, $POST['email'], $POST['username']);
        if (is_array($user)) {
            if ($user['user'] && $user['email'] == $POST['email'])
                return json_encode(array(false, TPL_LANGUAGE::$messages[6]));
            else if ($user['user'])
                return json_encode(array(false, TPL_LANGUAGE::$messages[7]));
        }else
            return json_encode(array(false, TPL_LANGUAGE::$messages[8]));

        if (SCL_DATABASE::query(SQL_SET_USER, $POST['username'], md5($POST['password']), $POST['email'])) {
            $session = md5($POST['username'] . '/' . $POST['email'] . '/' . time());
            setcookie('user', $session, strtotime('NEXT YEAR'), '/');
            SCL_DATABASE::query(SQL_SET_USER_SESSION, $session, $POST['email']);
            return json_encode(array(true, TPL_LANGUAGE::$messages[12]));
        }else
            return json_encode(array(false, TPL_LANGUAGE::$messages[8]));
    }
    return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
}

?>
