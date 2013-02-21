<?php

class LS {

    private $Events = array();
    private $LogFolder = false;
    private $WriteFileOnAnyError = false;
    private $StartTime;
    private $WasErrors = false;

    public function __construct($LogFolder = null, $WriteFileOnAnyError = false) {
        $this->StartTime = time();
        $this->AddMessage('Начало логирования', null, 0, true);

        if ($LogFolder != null && is_dir($LogFolder)) {
            $this->LogFolder = $LogFolder;
            $this->WriteFileOnAnyError = $WriteFileOnAnyError;
        }

        set_error_handler(array(&$this, 'ErrorHandler'));
    }

    public function ErrorHandler($errno, $errstr, $errfile, $errline) {
        $this->AddMessage('Ошибка ' . $errno . '!', 'Error <strong>' . $errno . '</strong>! <strong>' . $errstr . '</strong> in file <strong>' . $errfile . '</strong> on line <strong>' . $errline . '</strong>.', 2, true);
        $this->WasErrors = true;
        if ($this->WasErrors == true && $this->WriteFileOnAnyError == true)
            $this->OutputToFile();
        return true;
    }

    public function AddMessage($Title, $Text = null, $EventID = 0, $AddPredefinedVariables = false) {
        if ($AddPredefinedVariables == true) {
            $PV = array();
            foreach ($_SERVER as $k => $v)
                $PV[] = '$_SERVER[\'' . $k . '\'] = ' . $v;
            foreach ($_GET as $k => $v)
                $PV[] = '$_GET[\'' . $k . '\'] = ' . $v;
            foreach ($_POST as $k => $v)
                $PV[] = '$_POST[\'' . $k . '\'] = ' . $v;
            foreach ($_COOKIE as $k => $v)
                $PV[] = '$_COOKIE[\'' . $k . '\'] = ' . $v;
            foreach ($_FILES as $k => $v)
                $PV[] = '$_FILES[\'' . $k . '\'] = ' . $v;
            foreach ($_ENV as $k => $v)
                $PV[] = '$_ENV[\'' . $k . '\'] = ' . $v;
        } else
            $PV = null;

        $this->Events[] = array('eventid' => $EventID, 'timestamp' => time(), 'title' => $Title, 'text' => $Text, 'pv' => $PV);
        if ($this->WasErrors == true && $this->WriteFileOnAnyError == true)
            $this->OutputToFile();
    }

    public function OutputToFile($File = DEBUG) {
        $FileContent = null;
        $DivID = 0;

        foreach ($this->Events as $Event) {
            $DivID++;

            if ($DivID % 2 != 0)
                $DivBG = '#F5F5F5';
            else
                $DivBG = '#EBEBEB';

            switch ($Event['eventid']) {
                case 1:
                    $DivBG = '#D3FF91';
                    break;
                case 2:
                    $DivBG = '#FA9898';
                    break;
                case 3:
                    $DivBG = '#EEB6FF';
                    break;
            }

            $FileContent .= '<div style="background-color:' . $DivBG . '; border-bottom: solid 1px #CCC; padding:5px"><strong style="color:black">' . $Event['title'] . '</strong>' . (!is_null($Event['text']) ? '<br />' : null) . $Event['text'];
            if ($Event['pv'] != null) {
                $FileContent .= '<br /><a onclick="MinMax({ID})" style="cursor:pointer;">Показать/Скрыть информацию о системе</a>';
                $FileContent .= '<div id="{ID}" style="display:none; background-color:#EEFAFF; border:1px dashed; margin-left:20px; padding:5px"  title="[' . date('d.m.Y H:i:s', $Event['timestamp']) . ']">';
                foreach ($Event['pv'] as $PV)
                    $FileContent .= $PV . '<br />';
                $FileContent.= '</div>';
            }
            $FileContent .= '</div>';
        }

        $FileContent = str_replace("\t", "", $FileContent);

        $FileID = fopen(DEBUG . time() . '.' . rand(0, 100) . ".log", 'w');
        fwrite($FileID, $FileContent);
        fclose($FileID);
    }

    public function Get_Logs($num) {
        if (!LOGGING)
            return false;
        $file_list = glob(DEBUG . "*.log");
        $logs = "";
        $count = count($file_list);
        for ($i = 0; $i < $num && $count > $i; $i++) {
            $file = fopen($file_list[$i], "rb");
            $logs .= preg_replace('/{ID}/i', $i, fread($file, filesize($file_list[$i])));
        }

        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
            <title>СИСТЕМА ЛОГИРОВАНЯ</title>
                </head>
                <script type="text/javascript">
                function MinMax(ID) {
                div = document.getElementById(ID);
                if(div.style.display == \'none\') {
                div.style.display = \'block\';
                } else {
                div.style.display = \'none\';
                }
                }
                </script>
                <body style="margin: 0px; padding: 0px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px">
                <h1>СИСТЕМА ЛОГИРОВАНЯ</h1>' . $logs . '
                    </body>
                    </html>';
    }

    public function End($OutputToFile = false) {
        if (SCL_DATABASE::$FULL_queryTime >= 1)
            $color = "red";
        elseif (SCL_DATABASE::$FULL_queryTime >= 0.5 && SCL_DATABASE::$FULL_queryTime < 1)
            $color = "orange";
        elseif (SCL_DATABASE::$FULL_queryTime <= 0.5)
            $color = "green";
        $this->AddMessage("<strong style='color:$color'>Общее время выполнения SQL запросов: </strong>" . SCL_DATABASE::$FULL_queryTime . "<br/><strong style='color:$color'>Всего запросов: </strong>" . SCL_DATABASE::$SQLS . "<br/><strong style='color:$color'>В среднем времени на</strong> 1<strong style='color:$color'> запрос: </strong>" . (SCL_DATABASE::$FULL_queryTime / SCL_DATABASE::$SQLS) . "<br/>", null, 0, true);
        $this->AddMessage('Конец логирования');
        if ((LOGLEVEL || $this->WasErrors) && $OutputToFile)
            $this->OutputToFile();
    }

}

?>
