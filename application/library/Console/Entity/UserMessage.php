<?php

	namespace Console\Entity;
	
	/**
	 * @Entity
	 * @Table(name="user_message")
	 * @HasLifecycleCallbacks
	 */
	class UserMessage {
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 */
		protected $id;
		
		/**
		 * @ManyToOne(targetEntity="Console\Entity\User", cascade={"all"})
		 */
		protected $sender;
		
		/**
		 * @ManyToOne(targetEntity="Console\Entity\User", cascade={"all"})
		 */
		protected $receiver;
		
		/**
		 * @ManyToOne(targetEntity="Console\Entity\Message", cascade={"all"})
		 */
		protected $message;
		
		/**
		 * @Column(name="is_read", type="boolean")
		 */
		protected $isRead = false;
		
		/**
		 * @Column(name="read_at", type="datetime", nullable=true)
		 */
		protected $readAt = null;
		
		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		public function __construct(User $sender, User $receiver, Message $message, $isRead = false){
			$this->sender	= $sender;
			$this->receiver = $receiver;
			$this->message	= $message;
			if($isRead)
				$this->setIsRead();
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
		 * @return Console\Entity\User 
		 */
		public function getSender(){
			return $this->sender;
		}
		
		/**
		 * @return Console\Entity\User 
		 */
		public function getReceiver(){
			return $this->receiver;
		}
		
		/**
		 * @return Console\Entity\Message 
		 */
		public function getMessage(){
			return $this->message;
		}
		
		/**
		 * @return boolean 
		 */
		public function isRead(){
			return $this->isRead;
		}
		
		/**
		 * @return \DateTime 
		 */
		public function readAt(){
			return $this->readAt;
		}
		
		public function setIsRead(){
			$this->isRead = true;
			$this->readAt = new \DateTime("now");
		}
		
		public function setUnread(){
			$this->isRead = false;
			$this->readAt = null;
		}
		
	}