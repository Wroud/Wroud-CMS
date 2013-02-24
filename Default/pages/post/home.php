<?php

class Post {

    function Init() {
        if (TPL_INDEX::$USER != null && TPL_INDEX::$USER['group'] == 1)
            switch (TC::$POST['send']) {
                case'add':
                    if (SCL_DATABASE::query(SQL_ADD_NEWS, TC::$POST['title'], TC::$POST['message'], time()))
                        return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                    else
                        return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                    break;
                case'edit':
                    if (SCL_DATABASE::query(SQL_SET_NEWS, TC::$POST['title'], TC::$POST['message'], TC::$POST['id']))
                        return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                    else
                        return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                    break;
                case'delete':
                    if (SCL_DATABASE::query(SQL_DEL_NEWS, TC::$POST['id']))
                        return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                    else
                        return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                    break;
            }
        return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
    }

}

?>
