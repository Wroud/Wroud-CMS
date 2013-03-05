<?php

class SCL_MINISQL {

    private $connect;
    private $host;
    private $port;
    private $user;
    private $pass;

    function SCL_MINISQL($array) {
        $this->host = $array[0];
        $this->port = $array[1];
        $this->user = $array[2];
        $this->pass = $array[3];
    }

    private function Con() {
        $this->connect = new mysqli($this->host . ':' . $this->port, $this->user, $this->pass);
        if ($this->connect->connect_errno)
            return false;
        $this->connect->query("set character_set_client='" . BB_E . "'");
        $this->connect->query("set character_set_results='" . BB_E . "'");
        $this->connect->query("set collation_connection='" . BB_E . "_general_ci'");
        return true;
    }

    private function Convert_query($funcArgs, $numArgs, $query_type, $sort = '') {
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

        if (!$this->Con())
            return false;
        $query = $this->connect->query($safe_sql);
        if (!$query) {
            Core::$Debug->AddMessage("SQL: " . $safe_sql, $this->connect->error, 3);
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
        SCL_DATABASE::$FULL_queryTime += $queryTime;
        SCL_DATABASE::$SQLS++;
        Core::$Debug->AddMessage("SQL::" . $this->host . ":" . $this->port, sprintf('[%s мс]: %s', $queryTime, $safe_sql), 1);
        $this->connect->close();
        return $result;
    }

    function selectAss($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, 7);
    }

    function selectCell($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SINGLE_CELL);
    }

    function selectRow($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SINGLE_ROW);
    }

    function select($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, MULTIPLY_ROW);
    }

    function selectID($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, MULTIPLY_ID, $funcArgs[1]);
    }

    function query($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SQL_QUERY);
    }

    function RawQuery($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, SQL_RAW_QUERY);
    }

    function selectObject($query) {
        $funcArgs = func_get_args();
        $numArgs = func_num_args();
        return self::Convert_query($funcArgs, $numArgs, OBJECT_QUERY);
    }

    function SQL_Set_Db($DB) {
        $this->connect->select_db($DB);
    }

    function escape($s) {
        return $this->connect->escape_string($s);
    }

}

?>
