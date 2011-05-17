<?php

	namespace Console\Command;

	use Console\Logger\Logger;

	class CommandObserver implements \SplObserver {

		protected $loggers = array();

		public function __construct(array $loggers){
			foreach($loggers as $logger)
				$this->addLogger($logger);
		}
		
		public function addLogger(Logger $logger){
			$this->loggers[] = $logger;
		}

		public function update(\SplSubject $subject){
			foreach($this->loggers as $logger)
				$logger->log($subject->getCurrentAction());
		}

	}