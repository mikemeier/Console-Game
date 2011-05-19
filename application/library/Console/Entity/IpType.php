<?php

	namespace Console\Entity;
	
	/**
	 * @Entity(repositoryClass="Console\Repository\IpType")
	 * @Table(name="ip_type")
	 */
	class IpType {
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 */
		protected $id;

		/**
		 * @Column(type="string", length=50, unique=true)
		 */
		protected $value;
		
		/**
		 * @return int 
		 */
		public function getId(){
			return $this->id;
		}
		
		/**
		 * @return string 
		 */
		public function getValue(){
			return $this->value;
		}
		
		/**
		 * @param string $value 
		 */
		public function setValue($value){
			$this->value = $value;
		}
		
	}