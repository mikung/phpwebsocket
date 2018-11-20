<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Connection implements MessageComponentInterface {

    protected $clients;
    public $user = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Congratulations! the server is now running\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        //print_r($conn);
//        $this->clients->send('test');
        foreach ($this->clients as $client) {
            $client->send("mikung");
        }

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection1 %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        echo "socket nubmer : ".$from->resourceId." => Say : ";

        $obj = json_decode($msg);
        echo $msg."\n";
        //echo sprintf('data %s '."\n",$obj->name);
        $serverName = "35.197.139.20";
        $userName = "root";
        $userPass = "itishappy";
        $dbName = "hygge";

        // $serverName = "localhost";
        // $userName = "root";
        // $userPass = "";
        // $dbName = "rbh_report";

        // $dsn= "mysql:host=$serverName;dbname=$dbName";
        // $mysql = new PDO($dsn, $userName, $userPass);

        // $sqlinsert = "insert into mytable (name,email) values ('".$obj->name."','".$obj->email."') ";
        // $stmtinsert = $mysql->prepare($sqlinsert);
        // $stmtinsert->execute();

        // $sql = "select * from mytable order by id asc";
        // $stmt = $mysql->prepare($sql);
        // $stmt->execute();
        // $result = $sth->fetch(PDO::FETCH_ASSOC);

        // $json = json_encode($result);
        // $mysql = null;

        $conn = mysqli_connect($serverName,$userName,$userPass,$dbName);
        // $sqlinsert = "insert into mytable (name,email) values ('".$obj->name."','".$obj->email."') ";
        // $query = mysqli_query($conn,$sqlinsert);

        $myArray = array();

        //$sql = "select * from mytable order by id asc ";
        $sql = "select CONCAT(q.prefix,'-',LPAD(q.qid,3,0)) as q,h.name,q.timestamp from quetoday q LEFT JOIN hygge_hospcode h on q.hospcode = h.hospcode ORDER BY q.`timestamp` desc limit 10;";
        $query = mysqli_query($conn,$sql);
        while($result = mysqli_fetch_array($query,MYSQLI_ASSOC)){
            $myArray[] = $result;
            // echo "while \n ";
        }
        $json = json_encode($myArray);

        mysqli_close($conn);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connecte
                $client->send($json);
            }else{
                $client->send($json);
            }

        }
        //$this->clients->send($json);
        //$mysql = null;
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
?>