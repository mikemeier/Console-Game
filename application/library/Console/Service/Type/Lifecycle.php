<?php

	namespace Console\Service\Type;
	
	use Console\Service\AbstractService;
	
	use Console\Storage\Storage;
	
	use Console\Command\Command;
	use Console\Command\Lifecycle\Manager as LifecycleManager;
	
	class Lifecycle extends AbstractService {
		
		protected $storage;
		
		public function __construct(Storage $lifecycleStorage){
			$this->storage = $lifecycleStorage;
		}
		
		/**
		 * @param Console\Command\Command $command
		 * @param array $lifecycleOptions
		 * @return Console\Service\Manager 
		 */
		public function createLifecycle(Command $command, array $lifecycleOptions){
			$lifecycle = new LifecycleManager($command);
			foreach($lifecycleOptions as $status => $methodName)
				$lifecycle->setStatus($status, $methodName);
			$this->storeLifecycle($lifecycle);
			return $this;
		}
		
		/**
		 * @return Console\Command\Lifecycle\Lifecycle 
		 */
		public function getStoredLifecycle(){
			if($lifecycle = $this->storage->get('lifecycle'))
				$lifecycle->getCommand()->__construct($this->getServiceManager());
			return $lifecycle;
		}
		
		/**
		 * @return Console\Service\Manager 
		 */
		public function destroyStoredLifecycle(){
			$this->storage->delete('lifecycle');
			return $this;
		}
		
		/**
		 * @param Console\Command\Lifecycle\Lifecycle $lifecycle
		 * @return Console\Service\Manager 
		 */
		protected function storeLifecycle(LifecycleManager $lifecycle){
			$this->storage->set('lifecycle', $lifecycle);
			return $this;
		}
		
	}