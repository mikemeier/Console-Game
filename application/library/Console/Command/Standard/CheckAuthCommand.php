<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class CheckAuthCommand extends AbstractCommand {
		
		protected $allowedCommands = array (
			'connect', 'disconnect', 'login', 'register'
		);

		public function execute(Request $request, Response $response){
			if($this->getServiceManager()->isLoggedin())
				return;
			if(!\in_array($request->getCommand(true), $this->allowedCommands)){
				$response->newLine('Not loggedin', array('error'));
				return Command::COMMAND_CHAIN_STOP;
			}
		}

	}