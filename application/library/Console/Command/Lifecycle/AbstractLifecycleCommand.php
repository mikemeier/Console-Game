<?php

	namespace Console\Command\Lifecycle;
	
	use Console\Command\Command;
	use Console\Command\AbstractCommand;

	abstract class AbstractLifecycleCommand extends AbstractCommand {
		
		protected $lifecycleOptions = array();
		
		protected $lifecycleStatus	= Command::COMMAND_STATUS_UNINITIALIZED;
		protected $lifecycleVars	= array();
		
		public function getLifecycleStatus(){
			return $this->lifecycleStatus;
		}
		
		public function __sleep(){
			return array('lifecycleStatus', 'lifecycleVars');
		}
		
		protected function setStoredVar($key, $value){
			$this->lifecycleVars[$key] = $value;
		}
		
		protected function getStoredVar($key){
			return isset($this->lifecycleVars[$key]) ? $this->lifecycleVars[$key]: null;
		}
		
		protected function unsetStoredVar($key){
			unset($this->lifecycleVars[$key]);
		}
		
		protected function createLifecycle($status){
			$this->setLifecycleStatus($status);
			$this->getLifecycleService()->createLifecycle($this, $this->lifecycleOptions);
		}
		
		protected function setLifecycleStatus($status){
			if(!isset($this->lifecycleOptions[$status]))
				throw new Exception("$status has no defined lifecycle method in ". get_class($this));
			$this->lifecycleStatus = $status;
		}
		
		protected function destroyLifecycle(){
			$this->getLifecycleService()->destroyStoredLifecycle();
		}
		
		/**
		 * @return Console\Service\Type\Lifecycle 
		 */
		protected function getLifecycleService(){
			return $this->getServiceManager()->getLifecycleSerivce();
		}

	}