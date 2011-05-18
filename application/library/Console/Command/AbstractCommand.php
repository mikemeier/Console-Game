<?php

	namespace Console\Command;

	use Console\Service\Manager;

	use Console\Request\Request;
	use Console\Response\Response;

	abstract class AbstractCommand implements Command {

		protected $serviceManager	= null;
		
		public function __construct(Manager $serviceManager = null){
			if($serviceManager)
				$this->setServiceManager($serviceManager);
		}
		
		public function getName(){
			$explode = explode("\\",  get_class($this));
			return mb_strtolower($explode[count($explode)-1]);
		}
		
		protected function setServiceManager(Manager $serviceManager){
			$this->serviceManager = $serviceManager;
		}
		
		/**
		 * @return Console\Service\Manager
		 */
		protected function getServiceManager(){
			return $this->serviceManager;
		}
		
		/**
		 * @return Console\Service\Type\User 
		 */
		protected function getUserService(){
			return $this->getServiceManager()->getUserService();
		}

	}