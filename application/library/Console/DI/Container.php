<?php

	namespace Console\DI;

	class Container implements \ArrayAccess {

		protected $values = array();

		public function __set($key, $value){
			$this->values[$key] = $value;
		}

		public function __get($id){
			if(!isset($this->values[$id]))
				throw new Exception("id $id not found");
			if(\is_callable($this->values[$id]))
				return $this->values[$id]($this);
			return $this->values[$id];
		}

		public function asShared(\Closure $lambda){
			return function($container) use ($lambda){
				static $object;
				if(!is_null($object))
					return $object;
				return $object = $lambda($container);
			};
		}

		public function offsetSet($offset, $value){
			return $this->__set($offset, $value);
		}

		public function offsetExists($offset){
			return isset($this->values[$offset]);
		}

		public function offsetUnset($offset){
			unset($this->values[$offset]);
		}

		public function offsetGet($offset){
			return $this->__get($offset);
		}

	}