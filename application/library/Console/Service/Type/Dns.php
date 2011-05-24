<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	
	use Console\Entity\Ip;
	
	class Dns extends AbstractService {
		
		protected $entityManager;
		
		public function __construct(EntityManager $entityManager){
			$this->entityManager = $entityManager;
		}
		
		/**
		 * @param string $name
		 * @return mixed
		 */
		public function getResource($name){
			if($ipString = filter_var($name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
				if($ip = $this->entityManager->getRepository('Console\Entity\Ip')->findOneBy(array('value' => $ipString)))
					return $this->getResourceFromIp($ip);
				return false;
			}
			return $this->getResourceFromName($name);
		}
		
		protected function getResourceFromIp(Ip $ip){
			switch($ip->getType()->getValue()){
				case 'user':
					return $this->entityManager->getRepository('Console\Entity\User')->findOneBy(array('ip' => $ip->getId()));
				break;
			}
			return false;
		}
		
		protected function getResourceFromName($name){
			if($resource = $this->entityManager->getRepository('Console\Entity\User')->findOneBy(array('username' => $name)))
				return $resource;
			return false;
		}

	}