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
		 * @ManyToOne(targetEntity="Console\Entity\IpType", cascade={"all"})
		 */
		protected $type;

		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		public function __construct(IpType $type, $value){
			$this->type		= $type;
			if(!$this->value = self::getValidIp($value))
				throw new Exception("$value is not a valid ip");
		}
		
		/** @PrePersist */
		public function prePersist(){
			$this->created = new \DateTime("now");
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
		 * @return Console\Entity\IpType 
		 */
		public function getType(){
			return $this->type;
		}
		
		public static function getValidIp($ip){
			return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		}
		
	}