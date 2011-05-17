<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class NotFoundCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$response->newLine('Command not found', array('error'));
		}

	}