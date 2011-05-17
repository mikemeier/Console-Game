<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<script type="text/javascript">
			function openWindow(){
				var openWindow = window.open('console.php', 'Console-Game', 'width=600,height=400');
				openWindow.focus();
				return false;
			}
		</script>
		<title>Console-Game</title>
	</head>
	<body>
		<a href="" onclick="return openWindow();">start</a>
	</body>
</html>