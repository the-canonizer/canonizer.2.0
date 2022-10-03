<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class ActionController extends Controller
{
  
   public function copydatabase(){
   		ini_set('max_execution_time', 0);
   		$dbhost = env('PDB_HOST');
		$dbuser = env('PDB_USER');
		$dbpass = env('PDB_PASS');
		$dbname = env('PDB_NAME');
		$dbport = env('PDB_PORT');
		$db_host = env('DB_HOST');
		$dbexportPath = "$dbname.sql";
		if(function_exists('exec')) {
			
			  $mysqldump =  (stristr(PHP_OS, 'WIN')) ? exec("where mysqldump") : exec("which mysqldump");
   			try{
				 $command = "$mysqldump --column-statistics=0  -P $dbport -h $dbhost -u$dbuser '-p$dbpass' $dbname > $dbexportPath";
				 echo "$command $db_host"; die;
				 $output=array();
				exec($command,$output,$worked);
			}catch(\Exception $e){
					echo "<pre>"; print_r($e->getMessage()); die;
			}
		   
		   switch($worked){
			case 0:
			 $this->importDatabase($dbexportPath);
			break;
			case 1:
			echo 'An error occurred when exporting <b>' .$dbname .'</b>  '.getcwd().'/' .$dbexportPath .'</b>';
			break;
			case 2:
			echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$dbname .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$dbuser .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$dbhost .'</b></td></tr></table>';
			break;
			}
		}
		
	}

	public function importDatabase($file){
		$dbhost = getenv('SGDB_HOST');
		$dbuser = getenv('SGDB_USER');
		$dbpass = getenv('SGDB_PASS');
		$dbname = getenv('SGDB_NAME');
		$dbport = getenv('SGDB_PORT');
		$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$dbname'";
        $db = DB::select($query);
        $link = mysqli_connect($dbhost, $dbuser, $dbpass);
         $mysql =  (stristr(PHP_OS, 'WIN')) ? exec("where mysql") : exec("which mysql");
        $command = "$mysql  -P $dbport -h $dbhost -u$dbuser -p$dbpass  $dbname < $file"; 
        if(empty($db)){
        	$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
			if (mysqli_query($link,$sql)) {
				$output = array();
			   exec($command,$output, $worked);
			} else {
			    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
			}
        }else{
        	if (!$link) {
			    die('Could not connect: ' . mysqli_error($link));
			}

			$sql = "DROP DATABASE $dbname";
			if (mysqli_query($link,$sql)) {
				$sql1 = "CREATE DATABASE IF NOT EXISTS $dbname";
				if (mysqli_query($link,$sql1)) {
					$output = array();
				   exec($command,$output, $worked);
				} else {
				    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
				}
			} else {
			    echo 'Error dropping database: ' . mysqli_error($link) . "\n";
			}
        }
        switch($worked){
			case 0:
			echo "SUCCESS";
			break;
			case 1:
			echo 'An error occurred when importing <b>' .$dbname .'</b>  '.getcwd().'/' .$file .'</b>';
			break;
			case 2:
			echo 'An export error has occurred, please check the following information: <br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$dbname .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$dbuser .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$dbhost .'</b></td></tr></table>';
			break;
			}
        exit;
            
	}

	public function archievefiles(){
		try{
			if(function_exists('exec')){
				exec('zip -r archive.zip "files/"',$output,$worked);
			}else{
				echo "ERROR";
			}
			
			if($worked == 0){
				echo "SUCCESS";
			}else{
				echo "ERROR";
			}
			exit;
		}catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}
	}
	
	public function removearchievefiles(){
		try{
			if(function_exists('exec')){
				exec('rm -rf archive.zip',$output,$worked);
			}else{
				echo "ERROR";
			}
			
			if($worked == 0){
				echo "SUCCESS";
			}else{
				echo "ERROR";
			}
			exit;
		}catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}
	}
	
	public function copyfiles(){
			ini_set('max_execution_time', 0);
			try{
				  $url = 'https://canonizer.com/archive.zip';
				  $flag = copy($url,"files1.zip");
				  if($flag){
				  	exec('unzip -o  files1.zip',$output,$worked);
				  	switch($worked){
						case 0:
						echo 'Copy Pase Worked';
						exec('rm -rf files1.zip',$output,$worked);
						break;
						case 1:
						echo 'An error occurred Copying data';
						break;
						case 2:
						echo 'An export error has occurred';
						break;
						}
				  }
				 

			  }catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}

			exit;
		
			
	}

}
