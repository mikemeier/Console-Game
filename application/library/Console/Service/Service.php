<?php

	namespace Console\Service;
	
	interface Service {

		public function setServiceManager(Manager $serviceManager);
		public function getServiceManager();
		
	}