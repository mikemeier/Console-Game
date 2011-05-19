<?php

	namespace Console\Entity;
	
	/**
	 * @Entity(repositoryClass="Console\Repository\Ip")
	 * @Table(name="ip")
	 * @HasLifecycleCallbacks
	 */
	class Ip {
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 */
		protected $id;

		/**
		 * @Column(type="string", length=15, unique=true)
		 */
		protected $value;
		
		/**
		 * @ManyToOne(targetEntity="Console\Entity\IpType", cascade={"persist"})
		 */
		protected $type;

		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		/** @PrePersist */
		public function prePersist(){
			$this->created = $this->lastAction = new \DateTime("now");
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
		
		/**
		 * @param string $value 
		 */
		public function setValue($value){
			if(!$ip = $this->checkIp($value))
				throw new Exception("$value is not a valid ip");
			$this->value = $ip;
		}
		
		/**
		 * @return Console\Entity\IpType 
		 */
		public function getType(){
			return $this->type;
		}
		
		/**
		 * @param Console\Entity\IpType  $type 
		 */
		public function setType(IpType $type){
			$this->type = $type;
		}
		
		protected function checkIp($ip){
			return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		}
		
	}