<?php

class Page {

    function Init() {
        if (!is_array(TPL_INDEX::$realms))
            return '';
        switch (TC::$patch) {
            case'Гильдии':
                return Guild::GList(TC::$args);
                break;
            case'Гильдия':
                break;
        }
    }

}

class Guild {

    static function GList($args) {
        $page = (isset($args[3])) ? $args[3] : 0;
        $page = (is_numeric($page)) ? $page : 0;
        $realms = TPL_INDEX::Realms($args, 'Гильдии');
        $guilds = SCL_DATABASE::select(SQL_GET_GUILDS, $realms[1], PLPAGE * $page, PLPAGE);
        $guildc = SCL_DATABASE::selectCell(SQL_GET_GOUNT_GUILDS, $realms[1]);
        $leaders = array();
        $guids = array();
        foreach ($guilds as $gu) {
            $leaders[] = $gu['leaderguid'];
            $guids[] = $gu['guildid'];
        }
        $leaname = SCL_DATABASE::selectID(SQL_GET_LEADER_GUILDS, 'guid', $realms[1], implode(',', $leaders));
        $members = SCL_DATABASE::selectID(SQL_GET_MEMBERS_GUILD, 'guildid', $realms[1], implode(',', $guids));
        $tpl = new TC('pages/guilds');
        $guil = $tpl->get(array('guild' => 'GUILD'));
        $guil = $guil['guild'];
        $HTML = '';
        foreach ($guilds as $guild) {
            $tplg = new TC($guil, true);
            $tplg->set(array(
                '{NAME}' => $guild['name']
                , '{MONEY}' => TPL_INDEX::MoneyFormat($guild['bankMoney'])
                , '{MEMBERS}' => (isset($members[$guild['guildid']]['con'])) ? $members[$guild['guildid']]['con'] : 0
                , '{LEADER}' => (isset($leaname[$guild['leaderguid']]['name'])) ? $leaname[$guild['leaderguid']]['name'] : 'Нету'
            ));
            $HTML .=$tplg->render();
        }
        $tpl->set(array(
            '{REALMS}' => $realms[0]
            , '{PAGE}' => SMO_Tools::PG($guildc, PLPAGE, '/Гильдии/' . $realms[2] . '/', $page)
        ));
        $tpl->set_preg(array('GUILD' => $HTML));
        return $tpl->render();
    }

}

?>
