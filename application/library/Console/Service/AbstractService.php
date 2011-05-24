<?php

	namespace Console\Service;
	
	abstract class AbstractService implements Service {
		
		/**
		 * @var Console\Service\Manager 
		 */
		protected $serviceManager;
		
		/**
		 * @param Console\Service\Manager $serviceManager 
		 */
		public function setServiceManager(Manager $serviceManager){
			$this->serviceManager = $serviceManager;
		}
		
		/**
		 * @return Console\Service\Manager
		 */
		public function getServiceManager(){
			return $this->serviceManager;
		}
		
	}