<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class DateCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$response->newLine(\date('Y-m-d H:i:s'), array('info'));
		}

	}