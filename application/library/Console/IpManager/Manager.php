<?php

	namespace Console\IpManager;
	
	class Manager {
		
		protected $usedIps = array();
		
		public function __construct(array $usedIps){
			$this->usedIps = $usedIps;
		}
		
		public function getNewIp(){
			$ip = array(255, 255, 1, 1);
			while(in_array(implode('', $ip), $this->usedIps)){
				if($ip[3] > 255){
					$ip[3]++;
					continue;
				}
				if($ip[2] > 255)
					throw new Exception('out of ips');
				$ip[2]++;
				$ip[3] = 1;
			}
			return implode('', $ip);
		}
		
	}