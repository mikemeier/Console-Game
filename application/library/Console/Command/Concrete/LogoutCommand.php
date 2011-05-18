<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class LogoutCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$response->newLine('Goodbye', array('info'));
			$this->getUserService()->setIsLoggedin(false);
		}

	}