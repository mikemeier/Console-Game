<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	
	use Console\Entity\User as UserEntity;
	
	class Message extends AbstractService {
		
		protected $entityManager, $entityFactory;
		
		public function __construct(EntityManager $entityManager, EntityFactory $entityFactory){
			$this->entityManager = $entityManager;
			$this->entityFactory = $entityFactory;
		}
		
		/**
		 * @param mixed $resource
		 * @param string $message
		 */
		public function send($sender, $receiver, $message, $mustBeOnline = true){
			switch(true){
				case ($receiver instanceof UserEntity):
					return $this->sendUserMessage($sender, $receiver, $message, $mustBeOnline);
				break;
			}
			return false;
		}
		
		protected function sendUserMessage(UserEntity $sender, UserEntity $receiver, $value, $mustBeOnline){
			if($mustBeOnline && !$this->getServiceManager()->getUserService()->isUserOnline($receiver))
				return false;
			$message = $this->entityFactory->getMessage('user', $value);
			$sender->sendMessage($receiver, $message);
			$this->entityManager->persist($sender);
			$this->entityManager->flush();
			return true;
		}
		
	}