<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Connection implements MessageComponentInterface {

    protected $clients;
    protected $user = array();


    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Congratulations! the server is now running\n";

    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection1 %d sending message "%s" to %d other connection%s' . "\n"
                , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        echo "socket nubmer : ".$from->resourceId." => Say : ";
//        {"dep" : "001","status": "002"}
        $obj = json_decode($msg);

        $this->user[$from->resourceId] = (array)$obj;
        echo $obj->dep ." department \n";
        //print_r($obj);\
        print_r($this->user);
        echo count($this->user) . "\n";
        echo $msg."\n";

        $serverName = "35.197.139.20";
        $userName = "root";
        $userPass = "itishappy";
        $dbName = "hygge";



        $conn = mysqli_connect($serverName,$userName,$userPass,$dbName);
        $myArray = array();

        $ins = "SELECT
quetoday.id AS ID,
Concat(quetoday.prefix,'-',LPAD(quetoday.qid,3,0)) AS QID,
quetoday.register_point AS RegisterPoint,quetoday.`timestamp` AS TimeDispense
FROM
quetoday
WHERE
quetoday.department = $obj->dep
ORDER BY
quetoday.`timestamp` DESC
Limit 1 ";
        $dst = "SELECT
quetoday.id AS ID,
Concat(quetoday.prefix,'-',LPAD(quetoday.qid,3,0)) AS QID,
quetoday.confirm_point AS ConfirmPoint,quetoday.time_confirm AS TimeConfirm
FROM
quetoday
WHERE
quetoday.department = $obj->dep
AND quetoday.status = 'CF'

ORDER BY
quetoday.`timestamp` DESC

Limit 1 ";

        $vst = "SELECT
quetoday.id AS ID,
Concat(quetoday.prefix,'-',LPAD(quetoday.qid,3,0)) AS QID,
quetoday.point_update1 AS ConfirmPoint,quetoday.time_update1 AS TimeConfirm,
quetoday.vr_room AS Room
FROM
quetoday
WHERE
quetoday.department = $obj->dep
AND quetoday.vr_status = 'CF'

ORDER BY
quetoday.time_update1 DESC

Limit 1";
        $sst = "SELECT
quetoday.id AS ID,
Concat(quetoday.prefix,'-',LPAD(quetoday.qid,3,0)) AS QID,
quetoday.point_update2 AS ConfirmPoint,quetoday.time_update2 AS TimeConfirm,
quetoday.sc_room AS Room
FROM
quetoday
WHERE
quetoday.sc_status = 'CF' AND
quetoday.department = $obj->dep

ORDER BY
quetoday.time_update2 DESC

Limit 1";
        $vrcheck = "SELECT 
quetoday.department AS Department,
sum(quetoday.vr_status = 'RQ') AS RQ,
sum(quetoday.vr_status = 'CL') AS CL,
sum(quetoday.vr_status = 'CF') AS CF
FROM
quetoday
WHERE
quetoday.department = $obj->dep ";
        $sql = "";
        //$sql = "select CONCAT(q.prefix,'-',LPAD(q.qid,3,0)) as q,h.name,q.timestamp from quetoday q LEFT JOIN hygge_hospcode h on q.hospcode = h.hospcode ORDER BY q.`timestamp` desc limit 10;";
        if($obj->status == 'INS'){
            $sql = $ins;
        }
        if($obj->status == 'DST'){
            $sql = $dst;
        }
        if($obj->status == 'VST'){
            $sql = $vst;
        }
        if($obj->status == 'SST'){
            $sql = $sst;
        }

        $query = mysqli_query($conn,$sql);
        while($result = mysqli_fetch_array($query,MYSQLI_ASSOC)){
            $myArray[] = $result;
        }
        $json = json_encode($myArray);

        mysqli_close($conn);

        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                echo $client->resourceId;
//                $client->send($json);
//            }else{
//                echo $from->resourceId;
//            }
            if($this->user[$from->resourceId]['dep'] == $this->user[$client->resourceId]['dep']  && $this->user[$from->resourceId]['status'] == $this->user[$client->resourceId]['status']){
//                $client->send($msg.$from->resourceId);
                $client->send($json);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        unset($this->user[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
?>