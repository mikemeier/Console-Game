<?php

	namespace Console\Repository;
	
	use Doctrine\ORM\EntityRepository;
	
	class User extends EntityRepository {
		 
		protected $onlineUserTimeout = 60;

		public function getOnlineUsers($timeout = null){
			if(null == $timeout)
				$timeout = $this->onlineUserTimeout;
			return $this->_em->createQuery("SELECT u FROM Console\Entity\User u WHERE u.lastAction >= :date")
					->setParameter('date', date("Y-m-d H:i:s", (strtotime("now") - (int)$timeout)))
					->getResult();
		}
		
		public function isOnline($userId, $timeout = null){
			if(null == $timeout)
				$timeout = $this->onlineUserTimeout;
			return (bool)$this->_em->createQuery("SELECT u.id FROM Console\Entity\User u WHERE u.lastAction >= :date AND u.id = :id")
					->setParameter('date', date("Y-m-d H:i:s", (strtotime("now") - (int)$timeout)))
					->setParameter('id', $userId)
					->getResult();
		}
		
	}