<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class ConnectCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$serviceManager	= $this->getServiceManager();
			$userService	= $this->getUserService();
			if($userService->isConnected()){
				$response->newLine('Already connected', array('info'));
				return;
			}
			$host	= $serviceManager->getOption('host');
			$port	= $serviceManager->getOption('port');
			if($request->getParameter(1) == $host.":".$port){
				$response->openConnection = true;
				$userService->setIsConnected(true);
			}else{
				$response->closeConnection = true;
				$userService->setIsConnected(false);
			}
			sleep(mt_rand(1, 3));
		}

	}