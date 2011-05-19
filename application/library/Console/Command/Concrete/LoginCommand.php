<?php

	namespace Console\Command\Concrete;
	
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	
	use Console\Request\Request;
	use Console\Response\Response;
	
	use Console\Service\Type\User	as UserService;
	use Console\Entity\User			as UserEntity;
	

	class LoginCommand extends AbstractLifecycleCommand {
		
		protected $lifecycleOptions = array (
			'user'	=> 'askForPass',
			'pass'	=> 'checkLogin'
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
			$this->setStoredVar('username', $request->getCommand());
			$this->setLifecycleStatus('pass');
			$response->newLine('Passwort:');
		}
		
		public function checkLogin(Request $request, Response $response){
			$this->destroyLifecycle();
			
			$username	= $this->getStoredVar('username');
			$password	= $request->getCommand();
			$user		= $this->getUserService()->loginUser($username, $password);
			
			if($user instanceof UserEntity){
				$response->newLine('Welcome '. $user->getUsername(), array('info'));
				$response->newLine('Last action: '. $user->getLastAction()->format('Y-m-d H:i:s'), array('info'));
				$response->newLine('Your IP: '. $user->getIp()->getValue(), array('info'));
				return;
			}
			
			switch($user){
				case UserService::ERROR_USER_PASS:
					$response->newLine('Username and/or password wrong', array('error'));
					return;
				break;
				case UserService::ERROR_IP:
					$response->newLine('DHCP is out of IPs', array('error'));
					return;
				break;
				default:
					$response->newLine('Unknown error', array('error'));
					return;
				break;
			}
		}

	}