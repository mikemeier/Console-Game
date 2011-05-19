<?php

	namespace Console\Repository;
	
	use Doctrine\ORM\EntityRepository;
	
	class Ip extends EntityRepository {
		
		public function getIps(){
			if(!$results = $this->_em->createQuery("SELECT i.value FROM Console\Entity\Ip i")
					->getResult())
				return array();
			$ips = array();
			foreach($results as $result)
				$ips[] = $result['value'];
			return $ips;
		}
		
	}