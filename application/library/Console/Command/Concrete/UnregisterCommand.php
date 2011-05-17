<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class UnregisterCommand extends AbstractLifecycleCommand {
		
		protected $lifecycleOptions = array (
			'really' => 'delete'
		);
		
		public function execute(Request $request, Response $response){
			$this->createLifecycle('really');
			$response->newLine('Really delete account? (Y/N)', array('warning'));
		}
		
		public function delete(Request $request, Response $response){
			$this->destroyLifecycle();
			if($request->getCommand(true) != "y"){
				$response->newLine('Action cancelled', array('info'));
				return;
			}
			$this->getServiceManager()->unregisterUser();
			$response->newLine('Useraccount deleted', array('info'));
			$response->newLine('Goodbye', array('info'));
		}

	}