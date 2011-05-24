<?php

	define('APPLICATION_ENV', 'development');
	require_once 'application/Bootstrap.php';
	
	$em = $diContainer->entityManager;
	
	$user = $em->find('Console\Entity\User', 1);
	$type = $em->find('Console\Entity\MessageType', 1);
	
	$message = new \Console\Entity\Message();
	$message->setValue('TestMessage');
	$message->setType($type);
	
	$user->addMessage($message);
	
	$em->persist($user);
	$em->flush();