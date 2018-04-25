<?php

// Constants

    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $database = 'canonizer';
    $port = '3306';

    $backup_path = '/home/ashutosh/dbBackup/';

    //Backup Files according to the Date and Time
    $backup_file = $backup_path . $database . date("Y-m-d-H-i-s") . '.gz';

    $command = "mysqldump --opt -h $host -u $user -p$pass ". "$database | gzip > $backup_file";

    // Run the system command from script
    system($command);
    
    /*
     * Delete old Files from the backup Path.
     * Script Will only keep the recent 3 files.
     */

    if ($handle = opendir($backup_path)) {
	    
        $db_backup_files = array();
	
	while (false !== ($db_file = readdir($handle))) {
	    
	    if (!is_dir($db_file)){

	        $db_backup_files[$db_file] = filemtime($backup_path . $db_file);
	    } // End IF
	} // End While

	asort($db_backup_files, SORT_NUMERIC); // Sort Files according to the creation date

	$db_backup_files = array_keys($db_backup_files);

	for ($i = 0; $i < (count($db_backup_files) - 3); $i++)
	{
	    // Delete the Files from the Backup Path
	    unlink($backup_path . $db_backup_files[$i]);
        }
    }
?>
