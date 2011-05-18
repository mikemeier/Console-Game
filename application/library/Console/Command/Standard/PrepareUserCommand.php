<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class PrepareUserCommand extends AbstractCommand {
		
		public function execute(Request $request, Response $response){
			$userService = $this->getUserService();
			if($userService->isLoggedin() && $user = $userService->getUser()){
				$userService->setUserLastAction();
			}
		}

	}