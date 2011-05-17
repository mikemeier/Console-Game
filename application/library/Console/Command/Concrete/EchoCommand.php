<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class EchoCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			if(!($echo = trim($request->getQueryString(false))))
				return;
			$response->newLine($echo, array('echo'));
		}

	}