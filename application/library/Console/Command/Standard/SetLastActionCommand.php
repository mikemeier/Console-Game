<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class SetLastActionCommand extends AbstractCommand {
		
		public function execute(Request $request, Response $response){
			$sM = $this->getServiceManager();
			if($sM->isLoggedin())
				$sM->setUserLastAction();
		}

	}