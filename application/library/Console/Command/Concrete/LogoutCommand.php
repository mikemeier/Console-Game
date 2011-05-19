<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class LogoutCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$this->getUserService()->logoutUser();
			$response->newLine('Goodbye', array('info'));
		}

	}