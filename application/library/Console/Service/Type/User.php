<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	use Console\Storage\Storage;
	
	use Console\Entity\User as UserEntity;
	
	class User extends AbstractService {
		
		protected $entityManager, $entityFactory, $storage;
		
		protected $user			= null;
		protected $setStorageId	= false;
		
		const ERROR_USER_PASS	= 1;
		const ERROR_IP			= 2;
		
		public function __construct(EntityManager $entityManager, EntityFactory $entityFactory, Storage $storage){
			$this->entityManager	= $entityManager;
			$this->entityFactory	= $entityFactory;
			$this->storage			= $storage;
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
		 * @return Console\Service\Type\User
		 */
		public function setIsConnected($flag){
			$this->storage->set('isConnected', (bool)$flag);
			if(!$flag)
				$this->logoutUser();
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
			$tmpUser = $this->entityFactory->getUser($username, $password);	
			$options = array(
				'username' => $tmpUser->getUsername(),
				'password' => $tmpUser->getPassword()
			);
			if(!$user = $this->entityManager->find('Console\Entity\User', $options))
				return self::ERROR_USER_PASS;
			if(!$ip = $this->getServiceManager()->getDhcpService()->getNewUserIp())
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
			$user = $this->entityFactory->getUser($username, $password);
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
		public function getOnlineUsers(UserEntity $self = null, $timeout = null){
			$onlineUsers = $this->entityManager
							->getRepository('Console\Entity\User')
							->getOnlineUsers($timeout);
			if(!$self)
				return $onlineUsers;
			foreach($onlineUsers as $key => $onlineUser){
				if($onlineUser == $self){
					unset($onlineUsers[$key]);
					break;
				}
			}
			return $onlineUsers;
		}
		
		/**
		 * @param string $username
		 * @return bool
		 */
		public function isUsernameAlreadyInUse($username){
			return  (bool)$this->entityManager
						->getRepository('Console\Entity\User')
						->findOneBy(array('username' => $username));
		}
		
		public function isUserOnline(UserEntity $user){
			return  (bool)$this->entityManager
						->getRepository('Console\Entity\User')
						->isOnline($user->getId());
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
			if($user = $this->entityManager->find('Console\Entity\User', $id))
				$this->setUser($user);
			return $this;
		}
		
		
	}