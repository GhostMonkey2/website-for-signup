<?php
require_once('Excel/reader.php');
header("Content-type:text/html;Charset=utf-8");
$file_name=$_REQUEST['file1'];

$config = parse_ini_file('config.ini');

$conn = mysql_connect($config['host'],$config['user'], $config['password']);
mysql_select_db($config['dbname'], $conn);
mysql_query('set names utf8');
 
if($file_name!="")
{
		//enctype="multipart/form-data" 
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('UTF-8');
    //echo ($file_name);
    $data->read($file_name);
     
    error_reporting(E_ALL ^ E_NOTICE);    
    
   for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { 
    	$sql = "INSERT INTO sc_user VALUES('". 
			$data->sheets[0]['cells'][$i][1]."','". 
			$data->sheets[0]['cells'][$i][2]."','".
			$data->sheets[0]['cells'][$i][3]."','".
			$data->sheets[0]['cells'][$i][4]."','". 
			$data->sheets[0]['cells'][$i][5]."','". 
			$data->sheets[0]['cells'][$i][6]."','". 
			$data->sheets[0]['cells'][$i][7]."','". 
			$data->sheets[0]['cells'][$i][8]."')"; 
			//echo $sql.'< br />'; 
			$res = mysql_query($sql); 
		}
if ($res > 0) {
	require('views/upload_success.html');
} else {
	require('views/upload_fail.html');
}
}

?>