<?php

	define('APPLICATION_ENV', 'development');
	require_once 'application/Bootstrap.php';
	
	$em = $diContainer->entityManager;
	
	$ipType = $em->find('Console\Entity\User', array('username' => 'mYkon'));
	
	echo $ipType->getUsername();