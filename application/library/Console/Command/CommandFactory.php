<?php

	namespace Console\Command;
	
	use Console\Service\Manager;
	
	use Console\Command\Command;
	
	use Console\Command\Standard\ProxyCommand;
	use Console\Command\Standard\NotFoundCommand;
	
	class CommandFactory {
		
		protected $serviceManager;
		
		public function __construct(Manager $serviceManager){
			$this->serviceManager = $serviceManager;
		}
		
		public function getStandardCommand($name){
			$className = "Console\\Command\\Standard\\". $name ."Command";
			return new $className($this->serviceManager);
		}
		
		public function getConcreteCommand($name){
			$className = "Console\\Command\\Concrete\\". $name ."Command";
			if($this->serviceManager->getAutoLoader()->canLoadClass($className))
				return new $className($this->serviceManager);
			return new NotFoundCommand($this->serviceManager);
		}
		
		public function getProxyCommand(Command $command, $methodName){
			$proxy = new ProxyCommand($this->serviceManager);
			$proxy->setCommand($command, $methodName);
			return $proxy;
		}
		
	}