<?php
/**
 *@project    YouthScienceCamp
 *@name       index.php
 *@author     AndrewLiu<jsmonkey250@gmail.com>
 *@time       2015-7
 */ 
	require(dirname(__FILE__)."/App.php");
	date_default_timezone_set("PRC");
	set_time_limit(20*60);
	if(connection_status() != 0) die;
	header("Content-type:text/html;Charset=utf-8");
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
	{
		require ('views/login.html');
	}
	else if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		if ($username == 'admin' && $password == 'admin')
		{
			@session_start();
			$_SESSION['admin'] = true;
			$config = parse_ini_file("config.ini", true);
			$app = new App($config);
			$teams = $app->GetTeam();
			$tmp = array();
			foreach ($teams as $k=>$v)
			{
				$tmp[$k] = $v;
				$tmp[$k]['choosetimes'] = $app->GetChooseTimesByTeamId($v['holdteam']);
			}
			$teams = $tmp;
			
			require ('views/admin.html');
		}
		else
		{
			echo '<script>alert("用户名或密码错误!");history.back();</script>';
			exit;
		}
	}