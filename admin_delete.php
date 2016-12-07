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
	
	@session_start();
	if (isset($_SESSION['admin']) && $_SESSION['admin'] === true)
	{
		$id = $_GET['id'];
		$config = parse_ini_file("config.ini", true);
		$app = new App($config);
		$status = $app->DeleteTeam($id);
		if ($status)
		{
			echo '<script>alert("删除成功!");window.location.href="/admin";</script>';
		}
		else
		{
			echo '<script>alert("删除失败!");history.back();</script>';
		}
	}
	else
	{
		header("Location: /admin");
		exit;
	}