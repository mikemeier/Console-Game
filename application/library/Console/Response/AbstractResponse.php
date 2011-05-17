<?php

	namespace Console\Response;

	use Console\Response\Line\Line;
	
	use Console\Request\Request;

	abstract class AbstractResponse implements Response {

		protected $lines				= array();
		protected $standardLineClass	= null;
		protected $request				= null;

		protected $outputs		= array();
		protected $container	= array();

		abstract protected function setResponseHeader();
		abstract protected function getResponseOutput(array $output);

		public function addLine(Line $line){
			$this->lines[] = $line;
		}
		
		public function setStandardLineClass($className){
			$this->standardLineClass = $className;
		}

		public function setRequest(Request $request){
			$this->request = $request;
		}

		public function newLine($string, array $classes = array()){
			if($this->standardLineClass)
				$classes[] = $this->standardLineClass;
			$this->addLine(new Line($string, $classes));
		}

		public function hasLines(){
			return count($this->lines) > 0;
		}

		public function getLines(){
			return $this->lines;
		}

		public function getCleanLineObjects(){
			$cleanLines = array();
			foreach($this->lines as $line){
				$cleanLine = new \stdClass();
				$cleanLine->parts = array();
				foreach($line->getParts() as $part){
					$cleanPart = new \stdClass();
					$cleanPart->string		= $part->getString();
					$cleanPart->classes		= $part->getClasses();
					$cleanLine->parts[]		= $cleanPart;
				}
				$cleanLines[] = $cleanLine;
			}
			return $cleanLines;
		}

		public function setHeader(array $additionalHeader = array()){
			$this->setResponseHeader();
			foreach($additionalHeader as $key => $value)
				header($key.': '. $value);
		}

		public function addOutput($output){
			$this->outputs[] = $output;
		}

		public function __set($key, $value){
			$this->container[$key] = $value;
		}

		public function __get($key){
			return isset($this->container[$key]) ? $this->container[$key] : null;
		}

		public function getOutput(){
			$output = array(
				'dateTime'	=> date('Y-m-d H:i:s'),
				'data'		=> array(),
				'output'	=> $this->outputs,
				'container'	=> $this->container
			);
			if($this->request){
				$output['command']		= $this->request->getCommand();
				$output['parameters']	= $this->request->getParameters();
				$output['queryString']	= $this->request->getQueryString();
			}
			$output['format']			= $this->getFormat();
			$output['data']['lines']	= $this->getCleanLineObjects();
			return $this->getResponseOutput($output);
		}
		
		protected function getFormat(){
			return get_class($this);
		}

	}