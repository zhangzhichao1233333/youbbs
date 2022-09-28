<?php

if(!defined('IN_SAESPOT')) exit('Access Denied');

class DB_MySQL  {

    var $querycount = 0;
    var $link;

    function connect($servername, $dbport, $dbusername, $dbpassword, $dbname) {
        $this->link = mysqli_connect($servername .":". $dbport, $dbusername, $dbpassword, $dbname);
        if(!$this->link) {
            die("连接错误: " . mysqli_connect_error());
        }

        if($this->version() > '4.1') {
            global $charset, $dbcharset;
            if(!$dbcharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8'))) {
                $dbcharset = str_replace('-', '', $charset);
            }
            if($dbcharset) {
                mysqli_query( $this->link, "SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary");
            }

            if($this->version() > '5.0.1') {
                mysqli_query( $this->link, "SET sql_mode=''");
                #mysqli_query("SET sql_mode=''", $this->link);
            }
        }
    }


    function geterrdesc() {
        return mysqli_error($this->link);
    }

    function geterrno() {
        return  mysqli_errno($this->link);
    }

    function insert_id() {
        return ($id = mysqli_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_array($query, $result_type = MYSQLI_ASSOC) {
        return mysqli_fetch_array($query, $result_type);
    }

    function query($sql, $type = '') {

        $func = $type == 'UNBUFFERED' && @function_exists('mysqli_unbuffered_query') ? 'mysqli_unbuffered_query' : 'mysqli_query';
        if ($func == 'mysqli_query') {
            if(!($query = $func($this->link, $sql)) && $type != 'SILENT') {
                $this->halt('MySQL Query Error', $sql);
            }
        } else {
            if(!($query = $func($sql)) && $type != 'SILENT') {
                $this->halt('MySQL Query Error', $sql);
            }
        }
        $this->querycount++;
        return $query;
    }

    function unbuffered_query($sql) {
        $query = $this->query($sql, 'UNBUFFERED');
        return $query;
    }

    function select_db($dbname) {
        return mysqli_select_db($dbname, $this->link);
    }

    function fetch_row($query) {
        $query = mysqli_fetch_row($query);
        return $query;
    }

    function fetch_one_array($query) {
        $result = $this->query($query);
        $record = $this->fetch_array($result);
        return $record;
    }

    function num_rows($query) {
        $query = mysqli_num_rows($query);
        return $query;
    }

    function num_fields($query) {
        return mysqli_num_fields($query);
    }

    function result($query, $row) {
        $query = @mysqli_result($query, $row);
        return $query;
    }

    function free_result($query) {
        $query = mysqli_free_result($query);
        return $query;
    }

    function version() {
        return mysqli_get_server_info($this->link);
    }

    function close() {
        return mysqli_close($this->link);
    }

    function halt($msg ='', $sql=''){
        $message = "<html>\n<head>\n";
        $message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
        $message .= "<style type=\"text/css\">\n";
        $message .=  "body,p,pre {\n";
        $message .=  "font:12px Verdana;\n";
        $message .=  "}\n";
        $message .=  "</style>\n";
        $message .= "</head>\n";
        $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

        $message .= "<p>数据库出错:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
        $message .= "<b>Mysql error description</b>: ".htmlspecialchars($this->geterrdesc())."\n<br />";
        $message .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br />";
        $message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
        $message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n<br />";

        $message .= "</body>\n</html>";
        @header("content-Type: text/html; charset=UTF-8");
        echo $message;
        exit;
    }
}
?>
