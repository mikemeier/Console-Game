<?php

	namespace Console\Service;

	use Doctrine\ORM\EntityManager;
	use Doctrine\Common\ClassLoader;
	
	use Console\Storage\Storage;
	
	use Console\DI\Container as DIContainer;
	
	class Manager {

		protected $entityManager, $autoLoader, $userStorage, $lifecycleStorage, $options = array();
		protected $serviceDIContainer;

		/**
		 * @param Doctrine\ORM\EntityManager $entityManager
		 * @param Doctrine\Common\ClassLoader $autoLoader
		 * @param Console\Storage\Storage $userStorage
		 * @param Console\Storage\Storage $lifecycleStorage
		 * @param array $options 
		 */
		public function __construct(
			EntityManager $entityManager, 
			ClassLoader $autoLoader, 
			Storage $userStorage, 
			Storage $lifecycleStorage, 
			array $options = array()
		){
			$this->entityManager	= $entityManager;
			$this->autoLoader		= $autoLoader;
			$this->userStorage		= $userStorage;
			$this->lifecycleStorage	= $lifecycleStorage;
			$this->options			= $options;
			$this->setupServiceDIContainer();
		}
		
		/**
		 * @return Console\Service\Manager 
		 */
		public function flushEntityManager(){
			$this->entityManager->flush();
			return $this;
		}

		/**
		 * @param string $key
		 * @return mixed $value
		 */
		public function getOption($key){
			return isset($this->options[$key]) ? $this->options[$key] : null;
		}
		
		/**
		 * @return Doctrine\Common\ClassLoader
		 */
		public function getAutoLoader(){
			return $this->autoLoader;
		}
		
		/**
		 * @return Console\Service\Type\User
		 */
		public function getUserService(){
			return $this->getService('user');
		}
		
		/**
		 * @return Console\Service\Type\Lifecycle
		 */
		public function getLifecycleSerivce(){
			return $this->getService('lifecycle');
		}
		
		/**
		 * @return Console\Service\Type\Dhcp
		 */
		public function getDhcpService(){
			return $this->getService('dhcp');
		}
		
		/**
		 * @return Console\Service\Type\Dns
		 */
		public function getDnsService(){
			return $this->getService('dns');
		}
		
		/**
		 * @return Console\Service\Type\EntityFactory 
		 */
		public function getEntityFactoryService(){
			return $this->getService('entityFactory');
		}
		
		/**
		 * @return Console\Service\Type\Message 
		 */
		public function getMessageService(){
			return $this->getService('message');
		}
		
		protected function getService($id){
			return $this->serviceDIContainer->$id;
		}

		protected function setupServiceDIContainer(){
			$container			= new DIContainer();
			
			$manager			= $this;
			$eM					= $this->entityManager;
			$userStorage		= $this->userStorage;
			$lifecycleStorage	= $this->lifecycleStorage;
			
			//Setup Factory
			$container['entityFactory'] = $container->asShared(function($c) use ($manager, $eM){
				$entityFactory = new Type\EntityFactory($eM);
				$entityFactory->setServiceManager($manager);
				return $entityFactory;
			});
			
			//Setup User
			$container['user'] = $container->asShared(function($c) use ($manager, $eM, $userStorage){
				$userService = new Type\User($eM, $c->entityFactory, $userStorage);
				$userService->setServiceManager($manager);
				return $userService;
			});
			
			//Setup Lifecycle
			$container['lifecycle'] = $container->asShared(function($c) use ($manager, $lifecycleStorage){
				$lifecycleService = new Type\Lifecycle($lifecycleStorage);
				$lifecycleService->setServiceManager($manager);
				return $lifecycleService;
			});
			
			//Setup Dhcp
			$container['dhcp'] = $container->asShared(function($c) use ($manager, $eM){
				$dhcpService = new Type\Dhcp($eM, $c->entityFactory);
				$dhcpService->setServiceManager($manager);
				return $dhcpService;
			});
			
			//Setup Dns
			$container['dns'] = $container->asShared(function($c) use ($manager, $eM){
				$dnsService = new Type\Dns($eM);
				$dnsService->setServiceManager($manager);
				return $dnsService;
			});
			
			//Setup Message
			$container['message'] = $container->asShared(function($c) use ($manager, $eM){
				$messageService = new Type\Message($eM, $c->entityFactory);
				$messageService->setServiceManager($manager);
				return $messageService;
			});
			
			$this->serviceDIContainer = $container;
		}
		
	}