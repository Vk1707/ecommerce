<?php 

include_once("inc/chkAuth.php");
include_once("../inc/dbclass.php");

// Instantiate the Database class
$db = new Database();
$db_name="sonepatn_shop";
// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$tablesResult = $db->select($sql);
$varT="Tables_in_".$db_name;
foreach ($tablesResult as $row) {
    $tables[] = $row[$varT]; // Adjust based on your DB name if needed
}

$sqlScript = "";
foreach ($tables as $table) {
    
    // Prepare SQL script for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $createTableResult = $db->select_single($query);
    
    $sqlScript .= "\n\n" . $createTableResult['Create Table'] . ";\n\n"; // Assuming 'Create Table' is the key
    
    // Prepare SQL script for dumping data for each table
    $query = "SELECT * FROM $table";
    $rows = $db->select($query);
    
    if (!empty($rows)) {
        $columnCount = count($rows[0]); // Assuming all rows have the same number of columns
        
        foreach ($rows as $row) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            $values = array_map(function ($value) {
                return isset($value) ? '"' . addslashes($value) . '"' : '""';
            }, $row);
            $sqlScript .= implode(", ", $values);
            $sqlScript .= ");\n";
        }
    }
    
    $sqlScript .= "\n"; 
}

if (!empty($sqlScript)) {
    // Save the SQL script to a backup file
    $backup_file_name = 'hotshoppingdeals_database_backup_' . date("Y-m-d") . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    fwrite($fileHandler, $sqlScript);
    fclose($fileHandler);

    // Download the SQL backup file to the browser
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backup_file_name));
    ob_clean();
    flush();
    readfile($backup_file_name);
    unlink($backup_file_name); 
}


/*include("../inc/connection.php");
include("../inc/chkAuth.php");

// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {
    
    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";
    
    
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    
    $columnCount = mysqli_num_fields($result);
    
    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i ++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j ++) {
                $row[$j] = $row[$j];
                
                if (isset($row[$j])) {
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }
    
    $sqlScript .= "\n"; 
}

if(!empty($sqlScript))
{
    // Save the SQL script to a backup file
   // $backup_file_name = $dbname . '_backup_' . time() . '.sql';
    $backup_file_name = 'sonipatnews_database_backup_' . date("Y-m-d") . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler); 

    // Download the SQL backup file to the browser
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($backup_file_name));
    ob_clean();
    flush();
    readfile($backup_file_name);
    unlink($backup_file_name); 
}*/
?>