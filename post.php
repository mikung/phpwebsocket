<!DOCTYPE html>
<html>
    <head>
        <title>PHP Real Time</title>
        <!-- <link type="text/css" rel="stylesheet" href="style.css" /> -->
        <!-- <script src="jquery-1.7.2.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    </head>

    <style type="text/css">

		body {
			font:12px arial;
			color: #222;
			text-align:center;
			padding:35px; 
		}

    </style>
    <body>
        <div id="ws_support"></div>
		<div id="connect"></div>
        <div id="wrapper">
		Input Name and Email!<br><br>
               Name <input name="txtName" type="text" id="txtName" size="20"/>
                Email <input name="txtEmail" type="text" id="txtEmail" size="20"/>
                <input name="btnSend" type="button"  id="btnSend" value="Send" />
    </body>
</html>

<script language="javascript">

var socket;

function webSocketSupport()
{
    if (browserSupportsWebSockets() === false) {
        $('#ws_support').html('<h2>Sorry! Your web browser does not supports web sockets</h2>');
        $('#wrapper').hide();
        return;
    }

	// Open Connection
    socket = new WebSocket('ws://10.99.106.6:8889');

    socket.onopen = function(e) {
        $('#connect').html("You have have successfully connected to the server<br><br>");
    };

    socket.onmessage = function(e) {
       //
    };

    socket.onerror = function(e) {
        onError(e)
    };

}

function onError(e) {
    alert('Error!!');
}

function doSend() {
	var jsonSend = JSON.stringify({ "name": $('#txtName').val(), "email" : $('#txtEmail').val() });
	// {"name":"wisarut","email":"wisarut@thaicreate.com"}

	if ($('#txtName').val() == '') {
        alert('Enter your [Name]');
		$('#txtName').focus();
		return '';
    } else if ($('#txtEmail').val() == '') {
        alert('Enter your [Email]');
		$('#txtEmail').focus();
		return '';
    }
	
    socket.send(jsonSend);

    $('#txtName').val('');
	$('#txtEmail').val('');

	// alert('Done!!');
}

function browserSupportsWebSockets() {
    if ("WebSocket" in window)
    { return true; }
    else
    {  return false; }
}

$(document).ready(function() {
		webSocketSupport();
}); 

$('#btnSend').click(function () {
	doSend();
});

</script>