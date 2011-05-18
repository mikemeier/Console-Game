<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class LoginCommand extends AbstractLifecycleCommand {
		
		protected $lifecycleOptions = array (
			'user'	=> 'askForPass',
			'pass'	=> 'checkLogin'
		);
		
		public function execute(Request $request, Response $response){
			if($this->getServiceManager()->isLoggedin()){
				$response->newLine('Already loggedin', array('info'));
				return;
			}
			$this->createLifecycle('user');
			$response->newLine('Username:');
		}
		
		public function askForPass(Request $request, Response $response){
			$this->setStoredVar('username', $request->getCommand());
			$this->setLifecycleStatus('pass');
			$response->newLine('Passwort:');
		}
		
		public function checkLogin(Request $request, Response $response){
			$this->destroyLifecycle();
			
			if(!$user = $this->getServiceManager()->loginUser($this->getStoredVar('username'), $request->getCommand())){
				$response->newLine('Username and/or password wrong', array('error'));
				return;
			}
			
			$response->newLine('Welcome '. $user->getUsername(), array('info'));
			$response->newLine('Last action: '. $user->getLastAction()->format('Y-m-d H:i:s'), array('info'));
			$response->newLine('Your IP: '. $user->getIp(), array('info'));
		}

	}