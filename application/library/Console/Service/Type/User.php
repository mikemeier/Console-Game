<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	use Console\Storage\Storage;
	
	use Console\Entity\User as UserEntity;
	
	class User extends AbstractService {
		
		protected $storage, $entityManager;
		
		protected $user			= null;
		protected $setStorageId	= false;
		
		const ERROR_USER_PASS	= 1;
		const ERROR_IP			= 2;
		
		public function __construct(EntityManager $entityManager, Storage $storage){
			$this->storage			= $storage;
			$this->entityManager	= $entityManager;
			if($storage->has('id'))
				$this->loginFromId($storage->get('id'));
		}
		
		/**
		 * @return Console\Entity\User 
		 */
		public function getUser(){
			return $this->user;
		}
		
		/**
		 * @return bool 
		 */
		public function isConnected(){
			return (bool)$this->storage->get('isConnected');
		}
		
		/**
		 * @return bool 
		 */
		public function isLoggedin(){
			return $this->storage->get('id') && $this->getUser();
		}
		
		/**
		 * @param bool $flag
		 * @return Console\Service\Type\User; 
		 */
		public function setIsConnected($flag){
			$this->storage->set('isConnected', (bool)$flag);
			return $this;
		}
		
		/**
		 * @return Console\Service\Type\User 
		 */
		public function setUserLastAction(){
			if($user = $this->getUser())
				$user->setLastAction();
			return $this;
		}
		
		/**
		 * @param string $username
		 * @param string $password
		 * @return Console\Entity\User or Console\Service\Type\User::ERROR_* 
		 */
		public function loginUser($username, $password){
			$tmpUser = new UserEntity();
			$tmpUser->setPassword($password);
			
			$options = array(
				'username' => $username,
				'password' => $tmpUser->getPassword()
			);
			
			if(!$user = $this->entityManager->find('Console\Entity\User', $options))
				return self::ERROR_USER_PASS;
			if(!$ip = $this->getServiceManager()->getDhcpService()->getUserIp())
				return self::ERROR_IP;
			
			$user->setIp($ip);
			$this->setUser($user);
			
			return $user;
		}
		
		/**
		 * @return Console\Service\Type\User
		 */
		public function logoutUser(){
			$this->unsetUser();
			return $this;
		}
		
		/**
		 * @param string $username
		 * @param string $password 
		 * @return Console\Entity\User
		 */
		public function registerUser($username, $password){
			$user = new UserEntity();
			$user->setUsername($username);
			$user->setPassword($password);
			$this->entityManager->persist($user);
			return $user;
		}
		
		/**
		 * @return Console\Service\Type\User 
		 */
		public function unregisterUser(){
			if($user = $this->getUser())
				$this->entityManager->remove($user);
			$this->unsetUser();
			return $this;
		}
		
		/**
		 * @param int $timeout
		 * @return array $onlineUsers 
		 */
		public function getOnlineUsers($timeout = null){
			return $this->entityManager->getRepository('Console\Entity\User')
					->getOnlineUsers($timeout);
		}
		
		/**
		 * @param string $username
		 * @return bool 
		 */
		public function isUsernameAlreadyInUse($username){
			return  (bool)$this->entityManager->getRepository('Console\Entity\User')
						->findOneBy(array('username' => $username));
		}
		
		protected function unsetUser(){
			$this->storage->delete('id');
			$this->user = null;
			return $this;
		}
		
		protected function setUser(UserEntity $user){
			$this->setIsConnected(true);
			$this->storage->set('id', $user->getId());
			$this->entityManager->persist($user);
			$this->user = $user;
			return $this;
		}
		
		/**
		 * @return Console\Service\Type\User 
		 */
		protected function loginFromId($id){
			if($user = $this->entityManager->find('Console\Entity\User', $id)){
				$this->setUser($user);
				$this->storage->set('id', $user->getId());
			}
			return $this;
		}
		
		
	}