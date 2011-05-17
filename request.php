<?php
	
	define('APPLICATION_ENV', 'development');
	require_once 'application/Bootstrap.php';
	
	$diContainer->requestHandler->handle($diContainer->request, $diContainer->response);