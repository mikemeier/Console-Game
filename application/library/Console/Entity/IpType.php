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
		
		public function __construct($value){
			$this->value = $value;
		}
		
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
		
	}