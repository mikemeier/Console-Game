<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class IpscanCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			$response->newLine('Online:', array('info'));
			$userService	= $this->getUserService();
			$onlineUsers	= $userService->getOnlineUsers($userService->getUser());
			
			foreach($onlineUsers as $user)
				$response->newLine($user->getIp()->getValue() ." \t\t ". $user->getUsername(), array('info'));
			if(count($onlineUsers) == 0)
				$response->newLine('Nobody is online', array('warning'));
		}

	}