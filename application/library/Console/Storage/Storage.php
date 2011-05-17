<?php

	namespace Console\Storage;

	interface Storage {

		public function __set($key, $value);
		public function __get($key);
		
		public function has($key);

		public function get($key);
		public function set($key, $value);
		
		public function delete($key);

	}