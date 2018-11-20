<?php
/**
 * Created by PhpStorm.
 * User: Mikung
 * Date: 09/11/2561
 * Time: 13:43
 */

$serverName = "35.197.139.20";
$userName = "websocket";
$userPass = "itishappy";
$dbName = "hygge";
$dbh = new PDO("mysql:dbname=$dbName;host=$serverName;charset=utf8", $userName, $userPass);
$sql = "select CONCAT(q.prefix,'-',LPAD(q.qid,3,0)) as q,h.name,q.timestamp from quetoday q LEFT JOIN hygge_hospcode h on q.hospcode = h.hospcode ORDER BY q.`timestamp` desc limit 10;";
$stmt =  $dbh->prepare($sql);
$stmt->execute();
$row = $stmt->fetchAll();
//echo "<pre>";
//print_r($row);
//echo "</pre>";
header('Content-Type: application/json');
echo json_encode($row);
$dbh = null;