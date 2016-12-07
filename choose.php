<?php
/**
 *@project    YouthScienceCamp
 *@name       choose.php
 *@author     AndrewLiu<jsmonkey250@gmail.com>
 *@time       2015-8
 */ 
 
	require(dirname(__FILE__)."/App.php");
	header("Content-type:text/html;Charset=utf-8");
	$config = parse_ini_file("config.ini", true);
	$app = new App($config);

	$rtn = array('status' => '', 'info' => '');
	//if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
	//{
		$pid = trim($_POST['inputPID']);
		$name = trim($_POST['inputName']);
		$teamid = trim($_POST['id']);
		
		$team = $app->GetTeamNameById($teamid);
		$teamname = $team['holdteam'];		
		
		$teamlimit = $app->GetLimitByTeamId($teamid);
		$choosetimes = $app->GetChooseTimesByTeamId($teamname);	
		
		if ($choosetimes >= $teamlimit)
		{
			$rtn['status'] = '0';
			$rtn['info'] = "this project is full";
		}
		else
		{
			$user = $app->GetUserByPid($pid);			
			$uname = $user['uname'];
			//echo("$pidd");
			//echo("$team");
			//echo("$teamname");
			
			if (isset($user['pid']) && $name == $uname) //存在用户
			{
				$result = $app->Choose($user['pid'], $teamname);
				
				if ($result > 0)
				{
					$rtn['status'] = '1';
					$rtn['info'] = 'choose successfully';
				}
				else
				{
					$rtn['status'] = '0';
					$rtn['info'] = 'failed try again';
				}
			}
			else
			{
				
				$rtn['status'] = '0';
				$rtn['info'] = 'user didnt exists';
			//$rtn['info'] = $user['pid'];
			}
		}
	
//	else
//	{
//		$rtn['status'] = '0';
//		$rtn['info'] = '';
//	}
	$rt = $rtn['status'];
	
	if ($rt == 1) {
		require ('views/success.html');
		
	} else {
		require ('views/fail.html');
	}
	
	?>
	