<?php
// Connection To SQL Server
ini_set('display_errors', 1);
error_reporting(~0);

$serverName = "your_server_address";
$userName = "your_username";
$userPassword = "your_password";
$dbName = "your_database_name";

$connectionInfo = array(
    "Database" => $dbName, 
    "UID" => $userName, 
    "PWD" => $userPassword,
    "MultipleActiveResultSets" => true,
    "CharacterSet" => 'UTF-8'
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>