<?php
/**
 * Created by PhpStorm.
 * User: Mikung
 * Date: 09/11/2561
 * Time: 11:01
 */
$serverName = "35.197.139.20";
$userName = "websocket";
$userPass = "itishappy";
$dbName = "hygge";
$conn = mysqli_connect($serverName,$userName,$userPass,$dbName);
$myArray = array();

$sql = "select CONCAT(q.prefix,'-',LPAD(q.qid,3,0)) as q,h.name,q.timestamp from quetoday q LEFT JOIN hygge_hospcode h on q.hospcode = h.hospcode ORDER BY q.`timestamp` desc limit 10;";
$query = mysqli_query($conn,$sql);
while($result = mysqli_fetch_array($query,MYSQLI_ASSOC)){
    $myArray[] = $result;
}
$json = json_encode($myArray);
print_r($json);

mysqli_close($conn);

?>