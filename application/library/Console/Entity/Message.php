<?php

	namespace Console\Entity;
	
	use Doctrine\Common\Collections\ArrayCollection;
	
	/**
	 * @Entity(repositoryClass="Console\Repository\Message")
	 * @Table(name="message")
	 * @HasLifecycleCallbacks
	 */
	class Message {
		
		/**
		 * @Id 
		 * @GeneratedValue 
		 * @Column(type="integer")
		 */
		protected $id;

		/**
		 * @Column(type="text")
		 */
		protected $value;
		
		/**
		 * @ManyToOne(targetEntity="Console\Entity\MessageType", cascade={"persist"})
		 */
		protected $type;

		/**
		 * @OneToMany(targetEntity="Console\Entity\UserMessage", mappedBy="message", cascade={"all"}, orphanRemoval=true)
		 */
		protected $userMessages;
		
		/**
		 * @Column(type="datetime")
		 */
		protected $created;
		
		/**
		 * @param Console\Entity\MessageType $type
		 * @param string $value
		 */
		public function __construct(MessageType $type, $value){
			$this->type			= $type;
			$this->value		= $value;
			$this->userMessages = new ArrayCollection();
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
		 * @return Console\Entity\MessageType 
		 */
		public function getType(){
			return $this->type;
		}
		
	}