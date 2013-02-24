<?php

class Page {

    function Init() {
        $page = (isset(TC::$args[2])) ? TC::$args[2] : 0;
        $page = (is_numeric($page)) ? $page : 0;
        $news = SCL_DATABASE::select(SQL_GET_NEWS, TOPAGE * TC::$patch, TOPAGE);
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

}

?>
