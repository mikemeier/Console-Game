<?php

	namespace Console\Request;

	use Console\Request\Parser\Parser;
	use Console\Request\Request;
	use Console\Response\Response;

	use Console\Command\Command;
	use Console\Command\CommandChain;
	use Console\Command\CommandFactory;

	use Console\Service\Manager;

	use Console\Command\CommandObserver;
	use Console\Logger\Logger;

	class Handler {

		protected $parser, $serviceManager, $commandFactory, $loggers = array();

		public function __construct(Parser $parser, Manager $serviceManager){
			$this->parser			= $parser;
			$this->serviceManager	= $serviceManager;
			$this->commandFactory	= new CommandFactory($serviceManager);
		}

		public function addLogger(Logger $logger){
			$this->loggers[] = $logger;
		}

		public function handle(Request $request, Response $response){
			$this->parser->parse($request);
			
			$this->getNewCommandChain()
				->addCommand($this->getCheckBreakCommand())
				->addCommand($this->getCheckConnectionCommand())
				->addCommand($this->getPrepareUserCommand())
				->addCommand($this->getStoredLifecycleCommand())
				->addCommand($this->getAuthCommand())
				->addCommand($this->getCheckEmptyCommand())
				->addClosureCommand($this->getConcreteCommandClosure())
				->execute($request, $response);

			try {
				$this->serviceManager->flushEntityManager();
			}catch(Exception $e){
				$response->newLine($e->getMessage(), array('exception'));
			}
			
			$response->setHeader();
			echo $response->getOutput();
		}
		
		protected function getNewCommandChain(){
			$commandChain = new CommandChain();
			if($this->loggers)
				$commandChain->attach(new CommandObserver($this->loggers));
			return $commandChain;
		}
		
		protected function getCheckBreakCommand(){
			return $this->commandFactory->getStandardCommand('CheckBreak');
		}
		
		protected function getCheckConnectionCommand(){
			return $this->commandFactory->getStandardCommand('CheckConnection');
		}
		
		protected function getPrepareUserCommand(){
			return $this->commandFactory->getStandardCommand('PrepareUser');
		}
		
		protected function getStoredLifecycleCommand(){
			$cF = $this->commandFactory;
			if($lifecycle = $this->serviceManager->getLifecycleSerivce()->getStoredLifecycle())
				return $cF->getProxyCommand($lifecycle->getCommand(), $lifecycle->getMethodName());
			return $cF->getStandardCommand('Nullable');
		}
		
		protected function getAuthCommand(){
			return $this->commandFactory->getStandardCommand('CheckAuth');
		}
		
		protected function getCheckEmptyCommand(){
			return $this->commandFactory->getStandardCommand('CheckEmpty');
		}
		
		protected function getConcreteCommandClosure(){
			$cF = $this->commandFactory;
			return function(Request $request, Response $response) use ($cF){
				return $cF->getConcreteCommand(ucfirst(mb_strtolower($request->getCommand())));
			};
		}

	}