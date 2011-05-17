<?php

	namespace Console\Command\Standard;
	
	use Console\Command\AbstractCommand;
	use Console\Command\Command;
	
	use Console\Request\Request;
	use Console\Response\Response;
	
	use Console\Command\Exception;

	class ProxyCommand extends AbstractCommand {
		
		protected $command	= null;
		protected $methodName = null;
		
		public function setCommand(Command $command, $methodName){
			$this->command		= $command;
			$this->methodName	= $methodName;
		}
		
		public function execute(Request $request, Response $response){
			if($this->command && $this->methodName){
				if($return = call_user_func_array(array($this->getCommand(), $this->getMethodName()), array($request, $response)))
					return $return;
				return Command::COMMAND_CHAIN_STOP;
			}
			throw new Exception("no command to delegate");
		}
		
		public function getName(){
			if($command = $this->getCommand())
				return $command->getName();
			throw new Exception("no command to delegate");
		}
		
		/**
		 * @return Console\Command\Command 
		 */
		public function getCommand(){
			return $this->command;
		}
		
		public function getMethodName(){
			return $this->methodName;
		}

	}