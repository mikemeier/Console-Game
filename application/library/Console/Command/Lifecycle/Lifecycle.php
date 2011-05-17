<?php

	namespace Console\Command\Lifecycle;
	
	use Console\Command\Command;
	use Console\Command\Lifecycle\AbstractLifecycleCommand;
	
	class Lifecycle {
		
		protected $command, $stati = array();
		
		public function __construct(AbstractLifecycleCommand $command){
			$this->command	= $command;
		}
		
		public function setStatus($status, $methodName){
			$this->stati[$status] = $methodName;
		}
		
		public function removeStatus($status){
			unset($this->stati[$status]);
		}
		
		/**
		 * @return Console\Command\Lifecycle\AbstractLifecycleCommand;
		 */
		public function getCommand(){
			return $this->command;
		}
		
		/**
		 * @return string $methodName
		 */
		public function getMethodName(){
			$status = $this->getCommand()->getLifecycleStatus();
			return isset($this->stati[$status]) ? $this->stati[$status] : Command::COMMAND_EXECUTE;
		}
		
	}