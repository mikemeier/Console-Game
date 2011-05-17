<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class CheckEmptyCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			if(\trim($request->getCommand()))
				return;
			return Command::COMMAND_CHAIN_STOP;
		}

	}