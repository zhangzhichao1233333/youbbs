<?php
//exit('nil');
define('IN_SAESPOT', 1);
@header("content-Type: text/html; charset=UTF-8");

$sqlfile = dirname(__FILE__) . '/yunbbs_mysql.sql';
//if(!is_readable($sqlfile)) {
//	exit('���ݿ��ļ������ڻ��߶�ȡʧ��');
//}
$fp = fopen($sqlfile, 'rb');
$sql = fread($fp, 2048000);
fclose($fp);

include (dirname(__FILE__) . '/config.php');
include (dirname(__FILE__) . '/include/mysql.class.php');

$DBS = new DB_MySQL;
$DBS->connect($servername, $dbport, $dbusername, $dbpassword, $dbname);
unset($servername, $dbusername, $dbpassword);

$DBS->select_db($dbname);
if($DBS->geterrdesc()) {
	if(mysql_get_server_info() > '4.1') {
		$DBS->query("CREATE DATABASE $dbname DEFAULT CHARACTER SET $dbcharset");
	} else {
		$DBS->query("CREATE DATABASE $dbname");
	}

	if($DBS->geterrdesc()) {
		exit('ָ�������ݿⲻ����, ϵͳҲ�޷��Զ�����, �޷���װ.<br />');
	} else {
		$DBS->select_db($dbname);
		//�ɹ�����ָ�����ݿ�
	}
}

$DBS->query("SELECT COUNT(*) FROM yunbbs_settings", 'SILENT');
if(!$DBS->geterrdesc()) {
	header('location: /');
	exit('�����Ѿ�װ���ˣ� �����ظ���װ�� ��Ҫ��װ����ɾ��mysql ��ȫ�����ݡ� <a href="/">����ֱ�ӽ�����ҳ</a><br />');
}

runquery($sql);

$timestamp = time();
$DBS->unbuffered_query("UPDATE yunbbs_settings SET value='$timestamp' WHERE title='site_create'");

$DBS->close();

// '<br /> ˳����װ��ɣ�<br /><a href="/">���������ҳ</a>';

function runquery($sql) {
	global $dbcharset, $DBS;

	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				//echo '������ '.$name.' ... �ɹ�<br />';
				$DBS->query(createtable($query, $dbcharset));
			} else {
				$DBS->query($query);
			}
		}
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

header('location: /');

?>
