<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class CheckBreakCommand extends AbstractCommand {
		
		public function execute(Request $request, Response $response){
			if($request->getCommand(true) != "break")
				return;				
			$this->getServiceManager()->destroyStoredLifecycle();
			return Command::COMMAND_CHAIN_STOP;
		}

	}