<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	
	class Dhcp extends AbstractService {
		
		protected $entityManager;
		
		public function __construct(EntityManager $entityManager){
			$this->entityManager = $entityManager;
		}
		
		public function getNewUserIp(){
			$ips = $this->entityManager->getRepository('Console\Entity\User')
				->getUserIps();
			return $this->generateNewIp(array(255, 255, 1, 10), $ips);
		}
		
		protected function generateNewIp(array $startIp, array $disallowedIps = array()){
			$ip = $startIp;
			
			do {
				$ipString = implode(".", $ip);
				
			}while(in_array($ip, $disallowedIps));
			
			return $ipString;
		}
		
	}