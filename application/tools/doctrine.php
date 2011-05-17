<?php

	define('APPLICATION_ENV', 'development');

	require_once '../Bootstrap.php';

	$classLoader = new Doctrine\Common\ClassLoader('Symfony', $libPath .'Doctrine');
	$classLoader->register();

	$helperSet = new Symfony\Component\Console\Helper\HelperSet(array(
		'em' => new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
	));

	Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);