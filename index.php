<?php
/**
 *@project    YouthScienceCamp
 *@name       index.php
 *@author     AndrewLiu<jsmonkey250@gmail.com>
 *@time       2015-8
 */ 
	require(dirname(__FILE__)."/App.php");
	date_default_timezone_set("PRC");
	set_time_limit(20*60);
	if(connection_status() != 0) die;
	header("Content-type:text/html;Charset=utf-8");
	
	$config = parse_ini_file("config.ini", true);
	$app = new App($config, true);	
	
	$teams = $app->GetTeam();
	$tmp = array();
	foreach ($teams as $k=>$v)
	{
		$tmp[$k] = $v;
		$tmp[$k]['choosetimes'] = $app->GetChooseTimesByTeamId($v['holdteam']);
	}
	$teams = $tmp;
	require ('views/index.html');
	
	//$requestUri = parse_url($_SERVER['REQUEST_URI']);
	//if ($requestUri['path'] == "/" || $requestUri['path'] == "/index.php")
/*	{
		echo "<pre>";
		$ip = trim($vote->GetIP());
		$cookies['ip'] = array('name' => 'clientip', 'value' => $ip);
		$cookies['ipvotes'] = array('name' => 'ipvotes', 'value' => $vote->GetVoteTimesByIP($ip));
		//var_dump($vote->GetVoteTimes());
		
		$rowArr = array();
		foreach($vote->GetVoteTimes() as $row)
		{
			array_push($rowArr, implode('@', $row));
		}
		
		$cookies['votes'] = array('name' => 'votes', 'value' => implode('|', $rowArr));
		var_dump($cookies);//die;
		$vote->setVoteCookie($cookies);
		echo "</pre>";
		include_once "./index.html";
		exit;
	}
*/