<?php

	namespace Console\Entity;

	use Doctrine\Common\Collections\ArrayCollection;
	
	/**
	 * @Table(name="user")
	 * @Entity(repositoryClass="Console\Repository\User")
	 * @HasLifecycleCallbacks
	 */
	class User {
		
		const PASSWORD_SALT = 'P!k&,vR1rh}nM/Uc~2Bh<)0tD>Uk7uu8k<{3}|h$*4qo?&)yCe3eq!N}@][U oM!';
		const PASSWORD_ALGO	= 'sha256';
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 * @var string
		 */
		protected $id;

		/**
		 * @Column(type="string", length=50, unique=true)
		 * @var string
		 */
		protected $username;

		/**
		 * @Column(type="string", length=64)
		 * @var string
		 */
		protected $password;

		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		/**
		 * @Column(type="datetime")
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
		
		public function setLastAction(\DateTime $dateTime = null){
			$this->lastAction = ($dateTime) ? $dateTime : new \DateTime("now");
		}
		
		public function setUsername($username){
			$this->username = $username;
		}
		
		public function setPassword($password){
			$this->password = hash_hmac(self::PASSWORD_ALGO, $password, self::PASSWORD_SALT);
		}
		
		public function getUsername(){
			return $this->username;
		}
		
		public function getPassword(){
			return $this->password;
		}
		
		public function getId(){
			return $this->id;
		}
		
		public function getLastAction(){
			return $this->lastAction;
		}
		
	}