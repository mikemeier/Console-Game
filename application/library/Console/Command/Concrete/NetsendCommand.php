<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\AbstractCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class NetsendCommand extends AbstractCommand {

		public function execute(Request $request, Response $response){
			if(!$resourceName = $request->getParameter(1)){
				$response->newLine('Need valid target', array('error'));
				return;
			}
			if(!$message = $request->getParameter(2)){
				$response->newLine('Need valid message', array('error'));
				return;
			}
			if(!$resource = $this->getServiceManager()->getDnsService()->getResource($resourceName)){
				$response->newLine($resourceName .' not found', array('error'));
				return;
			}
			$sender = $this->getUserService()->getUser();
			if(!$this->getServiceManager()->getMessageService()->send($sender, $resource, $message, true)){
				$response->newLine('No response from target', array('error'));
				return;
			}
			$response->newLine('Message sent', array('info'));
		}

	}