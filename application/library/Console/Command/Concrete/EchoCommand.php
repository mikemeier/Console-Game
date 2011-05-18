<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class EchoCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$response->newLine($request->getQueryString(false), array('echo'));
		}

	}