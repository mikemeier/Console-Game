<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Doctrine\ORM\EntityManager;
	
	use Console\Entity\Ip;
	use Console\Entity\IpType;
	
	class Dhcp extends AbstractService {
		
		protected $entityManager;
		
		protected $startIps = array (
			'router' => array(192, 168, 1, 1),
			'user' => array(192, 168, 1, 50),
		);
		protected $endIps = array (
			'router' => array(192, 168, 1, 49),
			'user' => array(192, 168, 10, 255)
		);
		
		public function __construct(EntityManager $entityManager){
			$this->entityManager = $entityManager;
		}
		
		/**
		 * @return Console\Entity\Ip or false
		 */
		public function getUserIp(){
			return $this->getIp('user');
		}
		
		/**
		 * @param string $type
		 * @return Console\Entity\Ip or false 
		 */
		protected function getIp($type){
			$disallowedIps = $this->entityManager->getRepository('Console\Entity\Ip')
				->getIps();
			if(!$ip = $this->generateNewIp($disallowedIps, $this->startIps[$type], $this->endIps[$type]))
				return false;
			if(!$ipType = $this->entityManager->getRepository('Console\Entity\IpType')->findOneBy(array('value' => $type))){
				$ipType = new IpType();
				$ipType->setValue($type);
			}
			$ip->setType($ipType);
			return $ip;
		}
		
		/**
		 * @param array $disallowedIps
		 * @param array $startIp
		 * @param array $endIp
		 * @return Console\Entity\Ip or false 
		 */
		protected function generateNewIp(array $disallowedIps, array $startIp, array $endIp){
			$ipArray		= $startIp;
			$endIpReversed	= array_reverse($endIp);
			while(in_array(implode('.', $ipArray), $disallowedIps)){
				$ipArrayReversed	= array_reverse($ipArray);
				$continue			= false;
				foreach($ipArrayReversed as $key => $value){
					if($ipArrayReversed[$key] < $endIpReversed[$key]){
						$ipArrayReversed[$key]++;
						while($key > 0){
							$key--;
							$ipArrayReversed[$key] = 1;
						}
						$ipArray	= array_reverse($ipArrayReversed);
						$continue	= true;
						break;
					}
				}
				if(true == $continue)
					continue;
				return false;
			}
			$ip = new Ip();
			$ip->setValue(implode(".", $ipArray));
			return $ip;
		}
		
	}