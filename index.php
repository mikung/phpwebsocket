<!DOCTYPE html>
<html>
    <head>
        <title>PHP WebSocket</title>
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
		<center>
				<table width="400" border="1" id="myTable">
				<!-- head table -->
				<thead>
				  <tr>
					<td width="200"> <div align="center">Name </div></td>
					<td width="200"> <div align="center">Email </div></td>
				  </tr>
				</thead>
				<!-- body dynamic rows -->
				<tbody></tbody>
				</table>
			</center>
         
        </div>
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
        onMessage(e)
    };

    socket.onerror = function(e) {
        onError(e)
    };

}

function onMessage(e) {

			$('#myTable > tbody:last').empty();
			var obj = jQuery.parseJSON(e.data);
			$.each(obj, function (key, val) {
				var name = val["name"];
				var email = val["email"];
				var tr = "<tr>";
				tr = tr + "<td>" + name + "</td>";
				tr = tr + "<td>" + email + "</td>";
				tr = tr + "</tr>";
				$('#myTable > tbody:last').append(tr);
			});
}

function onError(e) {
    alert('Error!!');
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


</script>