<?php

	namespace Console\Entity;
	
	use Doctrine\Common\Collections\ArrayCollection;
	
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
		 * @OneToOne(targetEntity="Console\Entity\Ip", cascade={"all"}, orphanRemoval=true)
		 */
		protected $ip = null;
		
		/**
		 * @OneToMany(targetEntity="Console\Entity\UserMessage", mappedBy="sender", cascade={"all"}, orphanRemoval=true)
		 */
		protected $sentMessages;
		
		/**
		 * @OneToMany(targetEntity="Console\Entity\UserMessage", mappedBy="receiver", cascade={"all"}, orphanRemoval=true)
		 */
		protected $receivedMessages;

		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		/**
		 * @Column(type="datetime", name="last_action", nullable=true)
		 */
		protected $lastAction;
		
		public function __construct($username, $password){
			$this->username			= $username;
			$this->password			= hash_hmac(self::PASSWORD_ALGO, $password, self::PASSWORD_SALT);
			$this->sentMessages		= new ArrayCollection();
			$this->receivedMessages = new ArrayCollection();
		}
		
		/** @PreUpdate */
		public function preUpdate(){
			$this->lastAction = new \DateTime("now");
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
		public function getUsername(){
			return $this->username;
		}
		
		/**
		 * @return string 
		 */
		public function getPassword(){
			return $this->password;
		}
		
		/**
		 * @return Console\Entity\Ip 
		 */
		public function getIp(){
			return $this->ip;
		}
		
		/**
		 * @return Doctrine\Common\Collections\ArrayCollection 
		 */
		public function getReceivedMessages(){
			return $this->receivedMessages;
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
		 * @param Console\Entity\User $receiver
		 * @param Message $message
		 * @param type $isRead 
		 */
		public function sendMessage(User $receiver, Message $message, $isRead = false){
			$this->sentMessages[] = new UserMessage($this, $receiver, $message, $isRead);
		}
		
		/**
		 * @param \DateTime $dateTime 
		 */
		public function setLastAction(\DateTime $dateTime = null){
			$this->lastAction = ($dateTime) ? $dateTime : new \DateTime("now");
		}
		
		public function setIp(Ip $ip){
			$this->ip = $ip;
		}
		
		public function removeIp(){
			$this->ip = null;
		}
		
	}