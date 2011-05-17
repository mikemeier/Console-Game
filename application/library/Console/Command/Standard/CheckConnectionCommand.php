<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class CheckConnectionCommand extends AbstractCommand {
		
		protected $allowedCommands = array (
			'connect'
		);

		public function execute(Request $request, Response $response){
			if(($isConnected = $response->isConnected = $this->getServiceManager()->isConnected()))
				return;
			if(!\in_array($request->getCommand(true), $this->allowedCommands)){
				$response->newLine('Not connected', array('error'));
				return Command::COMMAND_CHAIN_STOP;
			}
		}

	}