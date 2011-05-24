<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class PrepareUserCommand extends AbstractCommand {
		
		public function execute(Request $request, Response $response){
			$userService = $this->getUserService();
			if(!$user = $userService->getUser())
				return;
			$userService->setUserLastAction();
			foreach($user->getReceivedMessages() as $userMessage){
				$userMessage->setIsRead();
				$response->newLine('Message from '. $userMessage->getSender()->getUsername(), array('message'));
				$response->newLine($userMessage->getMessage()->getValue(), array('message'));
			}
		}

	}