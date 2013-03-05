<?php

class Page extends Guild {

    static function Init() {
        if (!is_array(TPL_INDEX::$realms))
            return '';
        switch (TC::$patch) {
            case'Гильдии':
                return self::GList(TC::$args);
                break;
            case'Гильдия':
                break;
        }
    }

}

class Guild {

    public static $page = 0;
    public static $leaders = array();
    public static $guids = array();
    public static $tpl;
    public static $HTML = "";

    static function GList($args) {
        self::$page = (isset($args[3])) ? $args[3] : 0;
        self::$page = (is_numeric(self::$page)) ? self::$page : 0;

        $realms = TPL_INDEX::Realms($args, 'Гильдии');
        $database = new SCL_MINISQL($realms[1][3]);
        $guilds = $database->select(SQL_GET_GUILDS, $realms[1][1], PLPAGE * self::$page, PLPAGE);
        $guildc = $database->selectCell(SQL_GET_COUNT_GUILDS, $realms[1][1]);

        foreach ($guilds as $gu) {
            self::$leaders[] = $gu['leaderguid'];
            self::$guids[] = $gu['guildid'];
        }
        $leaname = $database->selectID(SQL_GET_LEADER_GUILDS, 'guid', $realms[1][1], implode(',', self::$leaders));
        $members = $database->selectID(SQL_GET_MEMBERS_GUILD, 'guildid', $realms[1][1], implode(',', self::$guids));

        self::$tpl = new TC('pages/guilds');
        $guil = self::$tpl->get(array('guild' => 'GUILD'));
        $guil = $guil['guild'];
        foreach ($guilds as $guild) {
            $tplg = new TC($guil, true);
            $tplg->set(array(
                '{NAME}' => $guild['name']
                , '{MONEY}' => TPL_INDEX::MoneyFormat($guild['bankMoney'])
                , '{MEMBERS}' => (isset($members[$guild['guildid']]['con'])) ? $members[$guild['guildid']]['con'] : 0
                , '{LEADER}' => (isset($leaname[$guild['leaderguid']]['name'])) ? $leaname[$guild['leaderguid']]['name'] : 'Нету'
            ));
            self::$HTML .= $tplg->render();
        }
        self::$tpl->set(array(
            '{REALMS}' => $realms[0]
            , '{PAGE}' => SMO_Tools::PG($guildc, PLPAGE, '/Гильдии/' . $realms[2] . '/', self::$page)
        ));
        self::$tpl->set_preg(array('GUILD' => self::$HTML));
        return self::$tpl->render();
    }

}

?>
