<?php

	namespace Console\Storage\Type;

	use Console\Storage\AbstractStorage;
	
	class Session extends AbstractStorage {

		protected $namespace;
		
		public function __construct($namespace){
			$this->namespace = $namespace;
			@\session_start();
		}

		public function get($key){
			return isset($_SESSION[$this->namespace][$key]) ? $_SESSION[$this->namespace][$key] : null;
		}

		public function set($key, $value){
			$_SESSION[$this->namespace][$key] = $value;
		}

		public function has($key){
			return isset($_SESSION[$this->namespace][$key]);
		}
		
		public function delete($key){
			unset($_SESSION[$this->namespace][$key]);
		}

	}