<?php
$serverName = "localhost";
$userName = "root";
$userPass = "";
$dbName = "rbh_report";

$dsn= "mysql:host=$serverName;dbname=$dbName";
$mysql = new PDO($dsn, $userName, $userPass);

$sqlinsert = "insert into mytable (name,email) values ('bb','cc') ";
$stmtinsert = $mysql->prepare($sqlinsert);
$stmtinsert->execute();

?>