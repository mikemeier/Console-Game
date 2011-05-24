<?php

	namespace Console\Entity;
	
	/**
	 * @Entity(repositoryClass="Console\Repository\MessageType")
	 * @Table(name="message_type")
	 */
	class MessageType {
		
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
		 * @param string $value 
		 */
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