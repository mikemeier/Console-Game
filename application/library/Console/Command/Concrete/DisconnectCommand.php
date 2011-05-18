<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class DisconnectCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$userService = $this->getUserService();
			if(!$userService->isConnected()){
				$response->newLine('Not connected', array('info'));
				return;
			}
			$response->isConnected = false;
			$userService->setIsConnected(false);
		}

	}