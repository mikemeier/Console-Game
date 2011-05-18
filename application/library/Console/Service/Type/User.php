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
		
		public function __construct(EntityManager $entityManager, Storage $storage){
			$this->storage			= $storage;
			$this->entityManager	= $entityManager;
			if($storage->has('id'))
				$this->loginFromId($storage->get('id'));
		}
		
		
		public function __destruct(){
			if($this->user){
				$this->entityManager->persist($this->user);
				$this->entityManager->flush();
				if(true == $this->setStorageId)
					$this->storage->set('id', $this->user->getId());
			}
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
			return (bool)$this->storage->get('isLoggedin');
		}
		
		/**
		 * @param bool $flag
		 * @return Console\Service\Manager; 
		 */
		public function setIsConnected($flag){
			if(false == $flag)
				$this->logoutUser();
			$this->storage->set('isConnected', (bool)$flag);
			return $this;
		}
		
		/**
		 * @param bool $flag
		 * @return Console\Service\Manager 
		 */
		public function setIsLoggedin($flag){
			if(false == $flag)
				$this->logoutUser();
			$this->storage->set('isLoggedin', (bool)$flag);
			return $this;
		}
		
		/**
		 * @param Console\Entity\User;
		 * @return Console\ServiceManager; 
		 */
		public function setUserLastAction(User $user){
			$user->setLastAction();
			$this->entityManager->persist($user);
			$this->entityManager->flush();
			return $this;
		}
		
		/**
		 * @param string $username
		 * @param string $password
		 * @return Console\Entity\User or false 
		 */
		public function loginUser($username, $password){
			$tmpUser = new UserEntity();
			$tmpUser->setPassword($password);
			
			$options = array(
				'username' => $username,
				'password' => $tmpUser->getPassword()
			);
			
			if($user = $this->entityManager->find('Console\Entity\User', $options)){
				$this->setStorageId = true;
				$this->setUser($user);
				return $user;
			}
			return false;
		}
		
		/**
		 * @param string $username
		 * @param string $password 
		 * @return Console\Service\Manager 
		 * @todo Implement db-locking for ipManager
		 */
		public function registerUser($username, $password){
			$user = new UserEntity();
			$user->setUsername($username);
			$user->setPassword($password);
			$user->setIp($this->getServiceManager()->getDhcpService()->getNewUserIp());
			$this->entityManager->persist($user);
			$this->entityManager->flush();
		}
		
		/**
		 * @return Console\Service\Manager 
		 */
		public function unregisterUser(){
			if($user = $this->getUser()){
				$this->entityManager->remove($user);
				$this->entityManager->flush();
			}
			$this->logoutUser();
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
		
		/**
		 * @return Console\Service\Manager 
		 */
		protected function logoutUser(){
			$this->storage->delete('id');
			$this->storage->set('isLoggedin', false);
			$this->user = null;
			return $this;
		}
		
		/**
		 * @param Console\Entity\User $user
		 * @return Console\Service\Manager 
		 */
		protected function setUser(User $user){
			$this->user	= $user;
			$this->setIsLoggedin(true);
			return $this;
		}
		
		protected function loginFromId($id){
			if($user = $this->entityManager->find('Console\Entity\User', $id))
				$this->setUser($user);
		}
		
		
	}