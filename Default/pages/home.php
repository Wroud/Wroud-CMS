<?php

function Init_tpl($patch, $args) {
    $page = (isset($args[2])) ? $args[2] : 0;
    $page = (is_numeric($page)) ? $page : 0;
    $news = SCL_DATABASE::select(SQL_GET_NEWS, TOPAGE * $page, TOPAGE * ($page + 1));
    $count = SCL_DATABASE::selectCell(SQL_GET_NEWSCO);
    $HTML = "";
    if (is_array($news))
        foreach ($news as $new) {
            $templ = new TC('news/message');
            $templ->set(array(
                '{TITLE}' => $new['title']
                , '{MESSAGE}' => $new['message']
                , '{ID}' => $new['id']
            ));
            $HTML.= $templ->render();
        }
    $home = new TC('content');
    $home->set(array(
        '{NEWS}' => $HTML
        , '{PAGING}' => SMO_Tools::PG($count, TOPAGE, '/Страница/', $page)
    ));
    if (TPL_INDEX::$USER != null && TPL_INDEX::$USER['group'] == 1)
        $home->set(array(
            '[EDIT]' => ''
            , '[/EDIT]' => ''
        ));
    else
        $home->set_preg(array('EDIT' => ''));
    return $home->render();
}

function POST($patch, $POST) {
    if (TPL_INDEX::$USER != null && TPL_INDEX::$USER['group'] == 1)
        switch ($POST['send']) {
            case'add':
                if (SCL_DATABASE::query(SQL_ADD_NEWS, $POST['title'], $POST['message'], time()))
                    return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                else
                    return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                break;
            case'edit':
                if (SCL_DATABASE::query(SQL_SET_NEWS, $POST['title'], $POST['message'], $POST['id']))
                    return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                else
                    return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                break;
            case'delete':
                if (SCL_DATABASE::query(SQL_DEL_NEWS, $POST['id']))
                    return json_encode(array(true, TPL_LANGUAGE::$messages[14]));
                else
                    return json_encode(array(false, TPL_LANGUAGE::$messages[15]));
                break;
        }
    return json_encode(array(false, TPL_LANGUAGE::$messages[13]));
}

?>
