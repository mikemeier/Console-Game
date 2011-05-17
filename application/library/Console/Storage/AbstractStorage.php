<?php

	namespace Console\Storage;

	abstract class AbstractStorage implements Storage {

		public function __get($key){
			return $this->get($key);
		}

		public function __set($key, $value){
			return $this->set($key, $value);
		}

	}