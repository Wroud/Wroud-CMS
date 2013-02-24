<?php

class Post {

    function Init() {
        if (TC::$POST['send'] == 'reg' && TPL_INDEX::$USER == null) {
            if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", TC::$POST['email']))
                return json_encode(array(false, TPL_LANGUAGE::$messages[3]));

            if (strlen(TC::$POST['username']) < 3 || strlen(TC::$POST['username']) > 10)
                return json_encode(array(false, TPL_LANGUAGE::$messages[1]));

            if (strlen(TC::$POST['password']) < 3 || strlen(TC::$POST['password']) > 16)
                return json_encode(array(false, TPL_LANGUAGE::$messages[2]));

            $user = SCL_DATABASE::selectRow(SQL_GET_PROVUSER, TC::$POST['email'], TC::$POST['username']);
            if (is_array($user)) {
                if ($user['user'] && $user['email'] == TC::$POST['email'])
                    return json_encode(array(false, TPL_LANGUAGE::$messages[6]));
                else if ($user['user'])
                    return json_encode(array(false, TPL_LANGUAGE::$messages[7]));
            }else
                return json_encode(array(false, TPL_LANGUAGE::$messages[8]));

            if (SCL_DATABASE::query(SQL_SET_USER, TC::$POST['username'], md5(TC::$POST['password']), TC::$POST['email'])) {
                $session = md5(TC::$POST['username'] . '/' . TC::$POST['email'] . '/' . time());
                setcookie('user', $session, strtotime('NEXT YEAR'), '/');
                SCL_DATABASE::query(SQL_SET_USER_SESSION, $session, TC::$POST['email']);
                return json_encode(array(true, TPL_LANGUAGE::$messages[12]));
            }else
                return json_encode(array(false, TPL_LANGUAGE::$messages[8]));
        }
        return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
    }

}

?>
