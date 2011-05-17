<?php

	namespace Console\Command;

	use Console\Request\Request;
	use Console\Response\Response;

	class CommandChain implements \SplSubject {

		protected $commands	= array();
		protected $observers	= array();

		protected $currentAction = null;

		public function addCommand(Command $command){
			$this->commands[] = $command;
			$this->action('add command '. $command->getName() .' to chain');
			return $this;
		}
		
		public function addClosureCommand(\Closure $closure){
			$this->commands[] = $closure;
			$this->action('add closureCommand to chain');
			return $this;
		}
		
		public function clearCommands(){
			$this->commands = array();
			$this->action('clear commands');
			return $this;
		}

		public function execute(Request $request, Response $response){
			$this->action('start request "'. $request->getQueryString() .'"');
			foreach($this->commands as $command){
				if(\is_callable($command))
					$command = $command($request, $response);
				$response->setStandardLineClass($command->getName());
				$result = $command->execute($request, $response);
				$this->action('execute "'. $command->getName() .' | response: '. ($result ? $result : 'null'));
				if($result === Command::COMMAND_CHAIN_STOP){
					$this->action('stop command chain: '. $result);
					break;
				}
			}
			$this->action('chain done');
			return $this;
		}

		public function attach(\SplObserver $observer){
			$this->observers[] = $observer;
			return $this;
		}

		public function detach(\SplObserver $observer){
			foreach($this->observers as $key => $tmpObserver){
				if($observer === $tmpObserver){
					unset($this->observers[$key]);
					return $this;
				}
			}
			return $this;
		}

		public function notify(){
			foreach($this->observers as $observer)
				$observer->update($this);
			return $this;
		}

		public function getCurrentAction(){
			return $this->currentAction;
		}

		protected function action($action){
			$this->currentAction = $action;
			$this->notify();
		}

	}