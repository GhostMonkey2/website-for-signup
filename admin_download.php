<?php
/**
 *@project    YouthScienceCamp
 *@name       admin_download.php
 *@author     AndrewLiu<jsmonkey250@gmail.com>
 *@time       2015-8
 */ 

	require(dirname(__FILE__)."/App.php");
	date_default_timezone_set("PRC");
	set_time_limit(20*60);
	
	#header("Content-type:text/html;Charset=utf-8");
	@session_start();
	if (isset($_SESSION['admin']) && $_SESSION['admin'] === true)
	{
		$id = $_GET['id'];
		$config = parse_ini_file("config.ini", true);
		$app = new App($config);
        $team = $app->GetTeamNameById($id);
        $teamname = $team['holdteam'];
		$users = $app->GetUsersTeamid($teamname);
		
		$excel = new Excel();
		$excel->addHeader(array('姓名','性别','高中学校','电话', '寝室', 'QQ'));
		
		$excel->addBody($users);
		$excel->downLoad();
	}
	else
	{
		header("Location: /admin");
		exit;
	}

class Excel
{
    private $head;

    private $body;

    public function addHeader($arr){

        foreach($arr as $headVal){

            $headVal = $this->charset($headVal);

            $this->head .= "{$headVal}\t ";

        }
        $this->head .= "\n";
    }

    public function addBody($arr){

        foreach($arr as $arrBody){
            foreach($arrBody as $bodyVal){
                $bodyVal = $this->charset($bodyVal);
				if (is_numeric($bodyVal))
					$bodyVal = trim('\''.$bodyVal);
                $this->body .= "{$bodyVal}\t ";

            }
            $this->body .= "\n";
        }
    }

    public function downLoad($filename=''){

        if(!$filename)
            $filename = date('YmdHis',time()).'.xls';
			
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$filename");
        header("Content-Type:charset=gb2312");
        if($this->head)
            echo $this->head;
        echo $this->body;
    }

    public function charset($string)
	{

        return iconv("utf-8", "gb2312", $string);
    }
}
