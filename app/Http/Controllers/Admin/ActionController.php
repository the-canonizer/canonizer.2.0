<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use SSH;

class ActionController extends Controller
{
  
   public function copydatabase(){
   		ini_set('max_execution_time', 0);
   		$dbhost = 'canoniser-db.czjgaatug9jz.us-east-2.rds.amazonaws.com';
		$dbuser = 'dbinstanceuser';
		$dbpass = '!C+4niZ3rDB!2329#';
		$dbname = 'production';
		$dbport = '3306';
		$dbexportPath = "$dbname.sql";
		if(function_exists('exec')) {
			  $mysqldump =  (PHP_OS_FAMILY =='Windows') ? exec("where mysqldump") : exec("which mysqldump");
   			try{
				 $command = "$mysqldump -P $dbport -h $dbhost -u$dbuser -p$dbpass $dbname > $dbexportPath"; 
				exec($command,$output=array(),$worked);
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
		$dbhost = 'canoniser-db.czjgaatug9jz.us-east-2.rds.amazonaws.com';
		$dbuser = 'dbinstanceuser';
		$dbpass = '!C+4niZ3rDB!2329#';
		$dbname = 'staging';
		$dbport = '3306';
		$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$dbname'";
        $db = DB::select($query);
        $link = mysqli_connect($dbhost, $dbuser, $dbpass);
         $mysql =  (PHP_OS_FAMILY =='Windows') ? exec("where mysql") : exec("which mysql");
        $command = "$mysql -P $dbport -h $dbhost -u$dbuser -p$dbpass  $dbname < $file"; 
        if(empty($db)){
        	$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
			if (mysqli_query($link,$sql)) {
			   exec($command,$output = array(), $worked);
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
				   exec($command,$output = array(), $worked);
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
				echo "exec function exists"; die;
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
	public function downloadZipFile($url, $filepath){
     		 $ch = curl_init($url);
		     curl_setopt($ch, CURLOPT_HEADER, 1);
		     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		     curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		     $raw_file_data = curl_exec($ch);

		     if(curl_errno($ch)){
		        echo 'error:' . curl_error($ch);
		     }
		     curl_close($ch);

		     file_put_contents($filepath, $raw_file_data);
		     return (filesize($filepath) > 0)? true : false;
		 }
	public function copyfiles(){
			try{
			  // creating zip using curl
				  $url = 'https://staging.canonizer.com/public/archieve.zip';
				  $this->downloadZipFile($url,"files1/");
			  }catch(\Exception $e){
				echo "<pre> my message "; print_r($e->getMessage()); die;
			}

exit;
		 //   $command = 'g:\software\wget\wget  -r  http://staging.canonizer.com/files';
			// try{
			// 	exec($command,$output=array(),$worked);
			// }catch(\Exception $e){
			// 	echo "<pre>"; print_r($e->getMessage()); die;
			// }
			
			//exit;

			// switch($worked){
			// case 0:
			// echo 'Copy Pase Worked';
			// break;
			// case 1:
			// echo 'An error occurred Copying data';
			// break;
			// case 2:
			// echo 'An export error has occurred';
			// break;
			// }
	}

}
