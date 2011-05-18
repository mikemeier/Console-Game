<?php

	namespace Console\Logger\Type;

	use Console\Logger\Logger;
	use Console\Response\Response as ResponseInterface;

	class Response implements Logger {

		protected $response;

		public function __construct(ResponseInterface $response){
			$this->response = $response;
		}

		public function log($message, $level = Logger::LOG_LEVEL_NOTICE){
			$this->response->addOutput($level .'|'. date('H:i:s') .'|'. $message);
		}

	}