<?php

	namespace Console\Service;

	use Doctrine\ORM\EntityManager;
	use Doctrine\Common\ClassLoader;
	
	use Console\Storage\Storage;
	
	use Console\Command\Command;
	use Console\Command\Lifecycle\Lifecycle;
	
	use Console\Entity\User;
	
	class Manager {

		protected $entityManager, $autoLoader, $userStorage, $commandStorage, $options = array();
		
		protected $user = null;
		protected $setUserStorageId = false;

		/**
		 *
		 * @param Doctrine\ORM\EntityManager $entityManager
		 * @param Doctrine\Common\ClassLoader $autoLoader
		 * @param Console\Storage\Storage $userStorage
		 * @param Console\Storage\Storage $commandStorage
		 * @param array $options 
		 */
		public function __construct(EntityManager $entityManager, ClassLoader $autoLoader, Storage $userStorage, Storage $commandStorage, array $options = array()){
			$this->entityManager	= $entityManager;
			$this->autoLoader		= $autoLoader;
			$this->userStorage		= $userStorage;
			$this->commandStorage	= $commandStorage;
			$this->options			= $options;
			
			if(
				$this->userStorage->has('id')
				&&
				($user = $this->entityManager->find('Console\Entity\User', $this->userStorage->get('id')))
			){
				$user->setLastAction();
				$this->user = $user;
			}
		}
		
		public function __destruct(){
			if($this->user){
				$this->entityManager->persist($this->user);
				$this->entityManager->flush();
				if($this->setUserStorageId == true)
					$this->userStorage->set('id', $this->user->getId());
			}
		}

		/**
		 *
		 * @param string $key
		 * @return mixed 
		 */
		public function __get($key){
			return isset($this->options[$key]) ? $this->options[$key] : null;
		}

		/**
		 *
		 * @param mixed $key
		 * @param mixed $value
		 * @return Console\Service\Manager 
		 */
		public function __set($key, $value){
			$this->options[$key] = $value;
			return $this;
		}
		
		/**
		 *
		 * @param string $username
		 * @return bool 
		 */
		public function isUsernameAlreadyInUse($username){
			if($this->entityManager->getRepository('Console\Entity\User')->findOneBy(array('username' => $username)))
				return true;
			return false;
		}
		
		/**
		 * 
		 * @return Doctrine\Common\ClassLoader
		 */
		public function getAutoLoader(){
			return $this->autoLoader;
		}

		/**
		 * 
		 * @return Console\Entity\User 
		 */
		public function getUser(){
			return $this->user;
		}
		
		/**
		 * 
		 * @return bool 
		 */
		public function isConnected(){
			return (bool)$this->userStorage->get('isConnected');
		}
		
		/**
		 * 
		 * @return bool 
		 */
		public function isLoggedin(){
			return (bool)$this->userStorage->get('isLoggedin');
		}
		
		/**
		 *
		 * @param bool $flag
		 * @return Console\Service\Manager; 
		 */
		public function setIsConnected($flag){
			if(false == $flag)
				$this->logoutUser();
			$this->userStorage->set('isConnected', (bool)$flag);
			return $this;
		}
		
		/**
		 *
		 * @param bool $flag
		 * @return Console\Service\Manager 
		 */
		public function setIsLoggedin($flag){
			if(false == $flag)
				$this->logoutUser();
			$this->userStorage->set('isLoggedin', (bool)$flag);
			return $this;
		}
		
		public function createLifecycle(Command $command, array $lifecycleOptions){
			$lifecycle = new Lifecycle($command);
			foreach($lifecycleOptions as $status => $methodName)
				$lifecycle->setStatus($status, $methodName);
			$this->storeLifecycle($lifecycle);
			return $this;
		}
		
		/**
		 * 
		 * @return Console\Command\Lifecycle\Lifecycle 
		 */
		public function getStoredLifecycle(){
			if($lifecycle = $this->commandStorage->get('lifecycle'))
				$lifecycle->getCommand()->__construct($this);
			return $lifecycle;
		}
		
		/**
		 *
		 * @return Console\Service\Manager 
		 */
		public function destroyStoredLifecycle(){
			$this->commandStorage->delete('lifecycle');
			return $this;
		}
		
		/**
		 *
		 * @return Console\Service\Manager 
		 */
		public function unregisterUser(){
			if($user = $this->getUser())
				$this->entityManager->remove($user);
			$this->logoutUser();
			return $this;
		}
		
		public function loginUser($username, $password){
			$tmpUser = new User();
			$tmpUser->setPassword($password);
			
			$options = array(
				'username' => $username,
				'password' => $tmpUser->getPassword()
			);
			
			if($user = $this->entityManager->find('Console\Entity\User', $options)){
				$this->setUser($user);
				return $user;
			}
			return false;
		}
		
		/**
		 *
		 * @param string $username
		 * @param string $password 
		 * @return Console\Service\Manager 
		 */
		public function registerUser($username, $password){
			$user = new User();
			$user->setUsername($username);
			$user->setPassword($password);
			$this->setUser($user);
		}
		
		/**
		 *
		 * @param Console\Command\Lifecycle\Lifecycle $lifecycle
		 * @return Console\Service\Manager 
		 */
		protected function storeLifecycle(Lifecycle $lifecycle){
			$this->commandStorage->set('lifecycle', $lifecycle);
			return $this;
		}
		
		/**
		 *
		 * @return Console\Service\Manager 
		 */
		protected function logoutUser(){
			$this->userStorage->delete('id');
			$this->userStorage->set('isLoggedin', false);
			$this->user = null;
			return $this;
		}
		
		/**
		 *
		 * @param Console\Entity\User $user
		 * @return Console\Service\Manager 
		 */
		protected function setUser(User $user){
			$this->user				= $user;
			$this->setUserStorageId = true;
			$this->setIsLoggedin(true);
			return $this;
		}
		
	}