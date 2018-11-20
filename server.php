<?php
    $host = "10.99.106.6";
    $port = 8089;
    set_time_limit(0);

    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_bind($sock,$host,$port);
    socket_listen($sock);
    echo "Listening for connections \n";

    class Chat{
        function readline(){
            return rtrim(fgets(STDIN));
        }
    }
    while(true){
        $accept = socket_accept($sock);
        $msg = socket_read($accept,1024);

        $msg = trim($msg);
        echo "Client Says:\t".$msg."\n";
        $msg = '<?xml version="1.0" encoding="utf-8"?>
        <GALLERY>
        <IMAGE TITLE="school">image1.jpg</IMAGE>
        <IMAGE TITLE="garden">image2.jpg</IMAGE>
        <IMAGE TITLE="shop">image3.jpg</IMAGE>
        </GALLERY>';

        // $line = new Chat();
        // echo "Enter Reply:\t";
        // $reply = $line->readline();

        socket_write($accept,$msg);
    

    }

    socket_close($accept);
    socket_close($sock);
?>