<?php

define('SINGLE_CELL', 0x01);
define('SINGLE_ROW', 0x02);
define('MULTIPLY_ROW', 0x03);
define('SQL_QUERY', 0x04);
define('OBJECT_QUERY', 0x05);
define('SQL_RAW_QUERY', 0x06);
define('MULTIPLY_ID', 0x08);

class SCL_DATABASE {

    public static $connect;
    public static $FULL_queryTime = 0;
    public static $SQLS = 0;

    static function Init() {
        SCL_DATABASE::Con();
        SCL_DATABASE::DB_Repair();
        SCL_DATABASE::L_Sett();
    }

    private static function L_Sett() {
        Core::$settings = SCL_DATABASE::selectRow(SQL_LOAD_SETTINGS);
    }

    private static function Con() {
        self::$connect = new mysqli(DB_H . ':' . DB_P, DB_U, DB_Pa);
        if (self::$connect->connect_errno)
            exit("Не удалось соединиться с бд!</br>");
        self::$connect->query("set character_set_client='" . BB_E . "'");
        self::$connect->query("set character_set_results='" . BB_E . "'");
        self::$connect->query("set collation_connection='" . BB_E . "_general_ci'");
    }

    private static function Convert_query($funcArgs, $numArgs, $query_type, $sort = '') {
        $make_array = array();
        $result = false;
        $query_start = microtime(true);
        if ($query_type != SQL_RAW_QUERY)
            for ($i = 1; $i < $numArgs; $i++) {
                if ($query_type == MULTIPLY_ID && $i == 1) {
                    unset($funcArgs[$i]);
                    continue;
                }
                if (is_string($funcArgs[$i]))
                    $funcArgs[$i] = addslashes($funcArgs[$i]);
                elseif (is_array($funcArgs[$i]))
                    $funcArgs[$i] = self::ConvertArray($funcArgs[$i]);
            }
        $safe_sql = call_user_func_array('sprintf', $funcArgs);
        $safe_sql = str_replace(array('BD_S'), array(BD_S), $safe_sql);

        $query = self::$connect->query($safe_sql);
        if (!$query) {
            Core::$Debug->AddMessage("SQL: " . @mysqli_error(self::$connect), $safe_sql, 3);
            return false;
        }
        switch ($query_type) {
            case SINGLE_CELL:
                $tmp = $query->fetch_array();
                $result = $tmp[0];
                unset($tmp);
                break;
            case SINGLE_ROW:
                $result = $query->fetch_assoc();
                if (is_array($result)) {
                    foreach ($result as $rKey => $rValue)
                        if (is_string($rKey))
                            $make_array[$rKey] = $rValue;
                    $result = $make_array;
                }
                break;
            case MULTIPLY_ROW:
                $result = array();
                while ($_result = $query->fetch_assoc()) {
                    if (is_array($_result)) {
                        foreach ($_result as $rKey => $rValue)
                            if (is_string($rKey))
                                $make_array[$rKey] = $rValue;
                        $result[] = $make_array;
                    } else
                        $result[] = $_result;
                }
                break;
            case OBJECT_QUERY:
                $result = array();
                while ($_result = $query->fetch_object($query))
                    $result[] = $_result;
                break;
            case SQL_QUERY:
                if ($query)
                    $result = true;
                break;
            case 7:
                while ($_result = $query->fetch_assoc()) {
                    if (is_array($_result)) {
                        foreach ($_result as $rKey => $rValue)
                            if (is_string($rKey))
                                $make_array[] = $rValue;
                        $result = $make_array;
                    } else
                        $result[] = $_result;
                }
                break;
            case MULTIPLY_ID:
                $result = array();
                while ($_result = $query->fetch_assoc()) {
                    if (is_array($_result)) {
                        foreach ($_result as $rKey => $rValue)
                            if (is_string($rKey))
                                $make_array[$rKey] = $rValue;
                        $result[$_result[$sort]] = $make_array;
                    } else
                        $result[] = $_result;
                }
                break;
            default:
                $result = false;
                break;
        }
        $query_end = microtime(true);
        $queryTime = round($query_end - $query_start, 4);
        self::$FULL_queryTime += $queryTime;
        self::$SQLS++;
        Core::$Debug->AddMessage("SQL", sprintf('[%s мс]: %s', $queryTime, $safe_sql), 1);
        return $result;
    }

    public static function selectAss($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, 7);
    }

    public static function selectCell($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SINGLE_CELL);
    }

    public static function selectRow($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SINGLE_ROW);
    }

    public static function select($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, MULTIPLY_ROW);
    }

    public static function selectID($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, MULTIPLY_ID, $funcArgs[1]);
    }

    public static function query($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SQL_QUERY);
    }

    public static function RawQuery($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SQL_RAW_QUERY);
    }

    public static function selectObject($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, OBJECT_QUERY);
    }

    public static function SQL_Set_Db($DB) {
        self::$connect->select_db($DB);
    }

    public static function escape($s) {
        return self::$connect->escape_string($s);
    }

    private static function DB_Repair() {
        $t_list = self::selectAss(SQL_SHOW_TABLES);
        foreach (glob(Sq_DIR . "*.sql") as $file)
            $F_list[] = basename($file, ".sql");
        if (is_array($t_list))
            $F_list = array_diff($F_list, $t_list);

        self::SQL_Set_Db(BD_S);
        foreach ($F_list as $name) {
            if ($name == 'new')
                continue;
            if (!file_exists(Sq_DIR . $name . ".sql"))
                die('<strong>Ошибка:</strong> файл не найден ' . $classFileName . '!<br/> <strong>173:DATABASE.php</strong>');
            $queries = file(Sq_DIR . $name . ".sql");
            $queries = implode("", $queries);
            $queries = explode(";", $queries);
            foreach ($queries as $q)
                self::query($q);
        }
    }

}

?>
