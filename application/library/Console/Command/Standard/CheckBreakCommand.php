<?php

	namespace Console\Command\Standard;
	
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class CheckBreakCommand extends AbstractLifecycleCommand {
		
		public function execute(Request $request, Response $response){
			if($request->getCommand(true) != "break")
				return;				
			$this->destroyLifecycle();
			return Command::COMMAND_CHAIN_STOP;
		}

	}