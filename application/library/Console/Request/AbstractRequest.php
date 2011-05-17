<?php

	namespace Console\Request;

	abstract class AbstractRequest implements Request {
		
		protected $queryString, $command, $parameters = array();

		public function setQueryString($queryString){
			$this->queryString = $queryString;
		}
		
		public function setCommand($command){
			$this->command = $command;
		}

		public function setParameters(array $parameters){
			$this->parameters = $parameters;
		}
		
		public function getQueryString($withCommand = true){
			if(true == $withCommand)
				return $this->queryString;
			return substr($this->queryString, mb_strlen($this->getCommand())+1);
		}

		public function getCommand($strToLower = false){
			if(true === $strToLower)
				return mb_strtolower($this->command);
			return $this->command;
		}

		public function getParameters(){
			return $this->parameters;
		}

		public function getParameter($number){
			return isset($this->parameters[$number-1]) ? $this->parameters[$number-1] : null;
		}

	}