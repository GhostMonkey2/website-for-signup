<?php
/**
 *@project    YouthScienceCamp
 *@name       App.php
 *@author     AndrewLiu<jsmonkey250@gmail.com>
 *@time       2015-8
 */ 
 
class App
{
	private $_dbh;
	
	private $_config;
	
	private $_continue;
	
	function __construct($config, $continue = false)
	{	
		$this->_config = $config;
		$this->_continue = $continue;
	}
	
	function ConnectDB()
	{
		try
		{
			$this->_dbh = @mysql_connect(
								$this->_config['db']['host'].":".$this->_config['db']['port'],
								$this->_config['db']['user'],
								$this->_config['db']['password']								
							);
		if(!mysql_select_db($this->_config['db']['dbname'],$this->_dbh)) {					
		mysql_query("create database sciencecamp default character set = UTF8",$this->_dbh);
		}
		
		if(!mysql_select_db($this->_config['db']['dbname'], $this->_dbh)) die(mysql_error());
			$sql_create_team = "create table if not exists sc_team 
			(
			 id int(5), name varchar(15) not null, intro varchar(50), teacher varchar(10), holdteam varchar(50), userlimit int(10), budget int(10), isdel int(5) default 0, primary key(name))";
			$sql_create_user = 'create table if not exists sc_user (pid int(10), uname varchar(10), school varchar(30), sex varchar(5), phone varchar(30),roomnum varchar(20),qqnum varchar(30), team varchar(50) default null,primary key(pid))';
			if(!mysql_query($sql_create_team, $this->_dbh))die(mysql_error());
			if(!mysql_query($sql_create_user, $this->_dbh))die(mysql_error());
			//mysql_query("SET NAMES 'UTF8'");					
			mysql_select_db($this->_config['db']['dbname'], $this->_dbh);
			mysql_query("SET NAMES 'UTF8'");
		}
		catch(Exception $e)
		{
			die('Connection db failed: ' . $e->getMessage());
		}
	}
	
	public function __destruct()
	{
		if (isset($this->_dbh) && $this->_continue === false)
		{
			mysql_close($this->_dbh);
			unset($this->_dbh);
		}
	}
	
	public function GetTeam()
	{
		$this->ConnectDB();
		$sql = 'SELECT * FROM sc_team 
				WHERE isdel = \'0\'';
				
		$result = mysql_query($sql, $this->_dbh);
		$rtn = array();
		while($row = mysql_fetch_assoc($result))
		{
			array_push($rtn,$row);
		}
		return $rtn;
	}
	
	public function GetLimitByTeamId($teamid)
	{
		$this->ConnectDB();
		$sql = "select userlimit from sc_team where id = '$teamid' and isdel = '0'";
		
		//$sql = 'SELECT userlimit FROM sc_team 
		//		WHERE id = \''. mysql_escape_string($teamid) .'\' AND isdel = \'0\'\'';
				
		$result = mysql_query($sql, $this->_dbh);
		$result = mysql_fetch_assoc($result);
		return $result['userlimit'];
	}
	
	public function GetChooseTimesByTeamId($team)
	{
		$this->ConnectDB();
		
		$sql = "SELECT COUNT(*) AS choosetimes FROM sc_user where team = '$team'";
		//$sql = 'SELECT COUNT(*) AS choosetimes FROM sc_user 
			//	WHERE team = \''. mysql_escape_string($teamid) .'\'';
				
		$result = mysql_query($sql, $this->_dbh);
		$result = mysql_fetch_assoc($result);
		return $result['choosetimes'];
	}
	
	public function GetUserByPid($pid)
	{
		$this->ConnectDB();
		$sql = "select * from sc_user where pid = '$pid'";
	//	$sql = "select * from sc_user where pid = '$pid' and team is null";
	//	$sql = 'SELECT * FROM sc_user 
	//			WHERE pid = \''. mysql_escape_string($pid) .'\' AND team IS NULL';
	//	$sql = 'SELECT * FROM sc_user 
	//				WHERE pid = \''. mysql_escape_string($pid);	
	
	//	set_cookie("123", mysql_escape_string($pid), time() + 3600000);
		$result = mysql_query($sql, $this->_dbh);
		return mysql_fetch_assoc($result);
	}
	
	
	public function GetTeamNameById($teamid) {
		$this->ConnectDB();
		$sql = "select * from sc_team where id = '$teamid'";
		$result = mysql_query($sql, $this->_dbh);
		return mysql_fetch_assoc($result);
	} 
	
	
	public function Choose($pid, $team)
	{
		$this->ConnectDB();
		//$sql = 'UPDATE sc_user SET team = \''.
		//		mysql_escape_string($team).'\' WHERE pid = \''.
		//		mysql_escape_string($pid). '\'';
		
		$sql = "update sc_user set team = '$team' where pid = '$pid'";
		
		$status = mysql_query($sql, $this->_dbh);
		
		return mysql_affected_rows();
	}
	
	public function GetUsersTeamid($teamname)
	{
		$this->ConnectDB();
		//$sql = 'SELECT pid,uname,phone FROM sc_user 
			//	WHERE team=\''.$id.'\'';
		
		$sql = "select uname, sex, school, phone, roomnum, qqnum from sc_user where team = '$teamname'";		
				
		$result = mysql_query($sql, $this->_dbh);
		$rtn = array();
		while($row = mysql_fetch_assoc($result))
		{
			array_push($rtn,$row);
		}
		return $rtn;
	}
	
	public function DeleteTeam($id)
	{
		$this->ConnectDB();
		echo$sql = 'UPDATE sc_team SET isdel = \'1\' WHERE id = \''.
				mysql_escape_string($id). '\'';
		
		$status = mysql_query($sql, $this->_dbh);
		
		return $status;
	}
	
	
	public function GetTimesBy()
	{
		$this->ConnectDB();
		$sql = 'SELECT selection, COUNT(*) AS votetimes FROM vote WHERE isdel = \'0\'
				GROUP BY selection';
		
		$result = mysql_query($sql, $this->_dbh);
		$rtnArr = array();
		while($row = mysql_fetch_assoc($result))
		{
			array_push($rtnArr, $row);
		}
		return $rtnArr;
	}
	
	function GetIP()
	{
		$ip = "127.0.0.1";
		if (isset($_SERVER['HTTP_X_REAL_IP']))
		{
			$ip = $_SERVER["HTTP_X_REAL_IP"];
		}
		else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else if (isset($_SERVER["REMOTE_ADDR"]))
		{
			$ip = $_SERVER["REMOTE_ADDR"];
		}
		return $ip;
	}
	
	function setVoteCookie($cookies)
	{
		foreach($cookies as $cookie)
		{
			setcookie($cookie['name'], $cookie['value'], time() + 5*60, '/');  //'oshynsong.duapp.com'
		}
	}
	
}