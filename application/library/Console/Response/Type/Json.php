<?php

	namespace Console\Response\Type;
	
	use Console\Response\AbstractResponse;

	class Json extends AbstractResponse {

		protected $charset;

		public function __construct($charset){
			$this->charset = $charset;
		}

		protected function setResponseHeader(){
			header('Content-Type: application/'. mb_strtolower($this->getFormat(), $this->charset) .'; charset='. $this->charset);
		}
		
		protected function getResponseOutput(array $output){
			return json_encode($output);
		}

	}