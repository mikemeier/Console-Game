<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	
	use Console\Entity\User as UserEntity;
	
	use Console\Entity\Ip;
	use Console\Entity\IpType;
	
	use Console\Entity\Message;
	use Console\Entity\MessageType;
	
	class EntityFactory extends AbstractService {
		
		protected $entityManager;
		
		/**
		 * @param Doctrine\ORM\EntityManager $entityManager
		 */
		public function __construct(EntityManager $entityManager){
			$this->entityManager = $entityManager;
		}
		
		/**
		 * @param string $username
		 * @param string $password
		 * @return Console\Entity\User
		 */
		public function getUser($username, $password){
			return new UserEntity($username, $password);
		}
		
		/**
		 * @param string $type
		 * @param string $value
		 * @return Console\Entity\Message
		 */
		public function getMessage($type, $value){
			if(!$messageType = $this->entityManager->getRepository('Console\Entity\MessageType')->findOneBy(array('value' => $type)))
				$messageType = new MessageType($type);
			return new Message($messageType, $value);
		}
		
		/**
		 * @param string $type
		 * @param string $value
		 * @return Console\Entity\Ip
		 */
		public function getIp($type, $value){
			if(!$ipType = $this->entityManager->getRepository('Console\Entity\IpType')->findOneBy(array('value' => $type)))
				$ipType = new IpType($type);
			return new Ip($ipType, $value);
		}

	}