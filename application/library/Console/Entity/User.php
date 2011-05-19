<?php

	namespace Console\Entity;
	
	/**
	 * @Entity(repositoryClass="Console\Repository\User")
	 * @Table(name="user")
	 * @HasLifecycleCallbacks
	 */
	class User {
		
		const PASSWORD_SALT = 'P!k&,vR1rh}nM/Uc~2Bh<)0tD>Uk7uu8k<{3}|h$*4qo?&)yCe3eq!N}@][U oM!';
		const PASSWORD_ALGO	= 'sha256';
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 */
		protected $id;

		/**
		 * @Column(type="string", length=50, unique=true)
		 */
		protected $username;

		/**
		 * @Column(type="string", length=64)
		 */
		protected $password;
		
		/**
		 * @OneToOne(targetEntity="Console\Entity\Ip", orphanRemoval=true, cascade={"all"})
		 */
		protected $ip;

		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		/**
		 * @Column(type="datetime", name="last_action")
		 */
		protected $lastAction;
		
		/** @PreUpdate */
		public function preUpdate(){
			$this->lastAction = new \DateTime("now");
		}
		
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
		public function getUsername(){
			return $this->username;
		}
		
		/**
		 * @param string $username 
		 */
		public function setUsername($username){
			$this->username = $username;
		}
		
		/**
		 * @return string 
		 */
		public function getPassword(){
			return $this->password;
		}
		
		/**
		 * @param string $password 
		 */
		public function setPassword($password){
			$this->password = hash_hmac(self::PASSWORD_ALGO, $password, self::PASSWORD_SALT);
		}
		
		/**
		 * @return Console\Entity\Ip 
		 */
		public function getIp(){
			return $this->ip;
		}
		
		/**
		 * @param Console\Entity\Ip $ip 
		 */
		public function setIp(Ip $ip){
			$this->ip = $ip;
		}
		
		public function removeIp(){
			$this->ip = null;
		}
		
		/**
		 * @return \DateTime 
		 */
		public function getCreated(){
			return $this->created;
		}
		
		/**
		 * @return \DateTime 
		 */
		public function getLastAction(){
			return $this->lastAction;
		}
		
		/**
		 * @param \DateTime $dateTime 
		 */
		public function setLastAction(\DateTime $dateTime = null){
			$this->lastAction = ($dateTime) ? $dateTime : new \DateTime("now");
		}
		
	}