<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class ConnectCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$sM	= $this->getServiceManager();
			if($sM->isConnected()){
				$response->newLine('Already connected', array('info'));
				return;
			}
			;
			$host	= $sM->host;
			$port	= $sM->port;
			if($request->getParameter(1) == $host.":".$port){
				$response->openConnection = true;
				$sM->setIsConnected(true);
			}else{
				$response->closeConnection = true;
				$sM->setIsConnected(false);
			}
			sleep(2);
		}

	}