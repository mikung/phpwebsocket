<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="" method="post">
            <table>
                <tr>
                    <td>
                        <label for="">Enter Message</label>
                        <input type="text" name="txtMessage">
                        <input type="submit" name="btnSend" value="Send">
                    </td>
                </tr>
                <?php
                    $host = "10.99.106.6";
                    $port = 8089;
                    if(isset($_POST['btnSend']))
                    {
                        $msg = $_REQUEST['txtMessage'];
                        $sock = socket_create(AF_INET, SOCK_STREAM, 0);
                        socket_connect($sock,$host,$port);
                        socket_write($sock,$msg,strlen($msg));
                        $reply = socket_read($sock,1024);
                        $reply = trim($reply);
                        $reply = "Server says:\t ".$reply;
                    }
                ?>
                <tr>
                    <td>
                        <textarea name="" id="" cols="30" rows="10"><?= @$reply ?></textarea>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>