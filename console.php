<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="style/main.css" />
		<script type="text/javascript" src="javascript/library/Core.js"></script>
		<script type="text/javascript" src="javascript/library/Console/Manager.js"></script>
		<script type="text/javascript" src="javascript/library/Request/Handler.js"></script>
		<script type="text/javascript" src="javascript/library/Request/Parser.js"></script>
		<script type="text/javascript" src="javascript/library/Request/Request.js"></script>
		<script type="text/javascript" src="javascript/library/Response/Handler.js"></script>
		<script type="text/javascript" src="javascript/library/Response/Response.js"></script>
		<script type="text/javascript" src="javascript/library/Response/Output/Line.js"></script>
		<script type="text/javascript" src="javascript/library/Response/Output/LinePart.js"></script>
		<script type="text/javascript" src="javascript/library/TransportProtocol/Factory.js"></script>
		<script type="text/javascript" src="javascript/library/TransportProtocol/Base.js"></script>
		<script type="text/javascript" src="javascript/library/TransportProtocol/WebSocket.js"></script>
		<script type="text/javascript" src="javascript/library/TransportProtocol/Ajax.js"></script>
		<script type="text/javascript" src="javascript/library/TransportProtocol/Ajax/Request.js"></script>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Console-Game</title>
		<script type="text/javascript">
			function main(){
				var consoleInput	= document.getElementById('consoleInput');
				var consoleOutput	= document.getElementById('consoleOutput');
				var body			= document.getElementById('body');
				var allowSocket		= false;
				var Manager			= new ConsoleManager(consoleInput, consoleOutput, body, allowSocket);
				Manager.init();
			}
		</script>
	</head>
	<body onload="main();" id="body">
		<div id="console">
			<div id="consoleOutput">Waiting for input</div>
		</div>
		<div id="input">
			<input type="text" id="consoleInput" name="consoleInput" value="" />
		</div>
	</body>
</html>