<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;

	class RegisterCommand extends AbstractLifecycleCommand {
		
		protected $lifecycleOptions = array (
			'user'	=> 'askForPass',
			'pass'	=> 'askForPass2',
			'pass2'	=> 'register'
		);
		
		public function execute(Request $request, Response $response){
			if($this->getUserService()->isLoggedin()){
				$response->newLine('Already loggedin', array('info'));
				return;
			}
			$this->createLifecycle('user');
			$response->newLine('Username:');
		}
		
		public function askForPass(Request $request, Response $response){
			$username = $request->getCommand();
			if($username = $request->getCommand()){
				if(!$this->getUserService()->isUsernameAlreadyInUse($username)){
					$this->setStoredVar('username', $username);
					$this->setLifecycleStatus('pass');
					$response->newLine('Passwort:');
					return;
				}
			}
			$response->newLine('Username invalid (Already in use or empty)', array('error'));
			$response->newLine('Username:');
		}
		
		public function askForPass2(Request $request, Response $response){
			if($password = $request->getCommand()){
				$this->setStoredVar('password', $password);
				$this->setLifecycleStatus('pass2');
				$response->newLine('Repeat password:');
				return;
			}
			$response->newLine('Password invalid', array('error'));
			$response->newLine('Password:');
		}
		
		public function register(Request $request, Response $response){
			$username	= $this->getStoredVar('username');
			$password	= $this->getStoredVar('password');
			$password2	= $request->getCommand();
			
			if($password != $password2){
				$this->setLifecycleStatus('pass');
				$response->newLine('Passwords do not match', array('error'));
				$response->newLine('Password:');
				return;
			}
			$this->destroyLifecycle();
			
			if(!$this->getUserService()->registerUser($username, $password)){
				$response->newLine('Registration failed', array('error'));
				return;
			}
					
			$response->newLine('Registration done', array('info'));
			$response->newLine('Please login', array('info'));
		}

	}