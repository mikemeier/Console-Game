<?php

	error_reporting(E_ALL);

	if(!defined('APPLICATION_ENV'))
		define('APPLICATION_ENV', 'production');
	
	if(APPLICATION_ENV == "development"){
		ini_set('display_errors', true);
	}else{
		ini_set('display_errors', false);
	}
	
	date_default_timezone_set('Europe/Zurich');
	setlocale(LC_ALL, 'de_CH.UTF-8');
	
	//Register Autoloader
	$appPath = __DIR__ .'/';
	$libPath = $appPath . 'library/';
	require_once $libPath . 'Doctrine/Common/ClassLoader.php';
	
	$doctrineClassLoader = new Doctrine\Common\ClassLoader('Doctrine', $libPath);
	$doctrineClassLoader->register();
	$consoleClassLoader = new Doctrine\Common\ClassLoader('Console', $libPath);
	$consoleClassLoader->register();

	$config = new Doctrine\ORM\Configuration();

	//Proxy Configuration
	$config->setProxyDir($libPath . 'Console/Proxy');
	$config->setProxyNamespace('Console\Proxy');
	$config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));

	//Mapping Configuration
	$driverImpl = $config->newDefaultAnnotationDriver($libPath . 'Console/Entity');
	$config->setMetadataDriverImpl($driverImpl);

	//Caching Configuration
	if(APPLICATION_ENV == "development"){
		$cache = new Doctrine\Common\Cache\ArrayCache();
	}else{
		$cache = new Doctrine\Common\Cache\ApcCache();
	}
	$config->setMetadataCacheImpl($cache);
	$config->setQueryCacheImpl($cache);

	//Database Configuration Parameters
	$connectionParams = array(
		'dbname'	=> 'consolegame',
		'user'		=> 'consolegame',
		'password'	=> 'consolegame',
		'host'		=> 'localhost',
		'driver'	=> 'pdo_mysql',
	);

	//Obtaining the EntityManager
	$evm			= new Doctrine\Common\EventManager();
	$entityManager	= Doctrine\ORM\EntityManager::create($connectionParams, $config, $evm);
	
	//Obtaining the DI-Container
	$diContainer = new Console\DI\Container();
	
	//Configure ResponseClassName
	$format		= isset($_GET['format']) ? ucfirst(mb_strtolower($_GET['format'])) : 'Json';
	$className	= "Console\\Response\\Type\\". $format;
	$diContainer['responseClassName'] = $consoleClassLoader->canLoadClass($className) ? $className : "Console\\Response\\Type\\Json";
	
	//Configure RequestClassName
	$diContainer['requestClassName'] = "Console\\Request\Type\\Post";
	
	//Configure EntityManager
	$diContainer['entityManager'] = $entityManager;
	
	//Configure Autoloader
	$diContainer['classLoader'] = $consoleClassLoader;

	//Configure userStorage
	$diContainer['userStorage'] = $diContainer->asShared(function($c){
		return new Console\Storage\Type\Session('user');
	});
	
	//Configure commandStorage
	$diContainer['commandStorage'] = $diContainer->asShared(function($c){
		return new Console\Storage\Type\Session('command');
	});

	//Configure ServiceManager
	$diContainer['serviceManager'] = $diContainer->asShared(function($c){
		return new Console\Service\Manager(
			$c->entityManager,
			$c->classLoader,
			$c->userStorage,
			$c->commandStorage,
			array(
				'host' => 'localhost',
				'port' => '1414'
			)
		);
	});

	//Configure RequestParser
	$diContainer['requestParser'] = $diContainer->asShared(function($c){
		return new Console\Request\Parser\Parser(
			function(Console\Request\Request $request){
				return preg_split(
					'#(?:"(.*?)"|[^\w\.\:])#',
					$request->getQueryString(),
					-1,
					PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
				);
			}
		);
	});

	//Configure Request
	$diContainer['request'] = $diContainer->asShared(function($c){
		$requestClass = $c->requestClassName;
		return new $requestClass('q');
	});

	//Configure Response
	$diContainer['response'] = $diContainer->asShared(function($c){
		$responseClass	= $c->responseClassName;
		$response		= new $responseClass('utf-8');
		$response->setRequest($c->request);
		return $response;
	});

	//Configure Request-Loggers
	$diContainer['requestLoggers'] = $diContainer->asShared(function($c){
		return array(
			new Console\Logger\Type\Response($c->response)
		);
	});

	//Configure RequestHandler
	$diContainer['requestHandler'] = $diContainer->asShared(function($c){
		$handler = new Console\Request\Handler($c->requestParser, $c->serviceManager);
		foreach($c->requestLoggers as $logger)
			$handler->addLogger($logger);
		return $handler;
	});