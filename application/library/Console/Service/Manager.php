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
		
		protected function getService($id){
			return $this->serviceDIContainer->$id;
		}

		protected function setupServiceDIContainer(){
			$container			= new DIContainer();
			
			$manager			= $this;
			$eM					= $this->entityManager;
			$userStorage		= $this->userStorage;
			$lifecycleStorage	= $this->lifecycleStorage;
			
			//Setup User
			$container['user'] = $container->asShared(function($c) use ($manager, $eM, $userStorage){
				$userService = new Type\User($eM, $userStorage);
				$userService->setServiceManager($manager);
				return $userService;
			});
			
			//Setup Lifecycle
			$container['lifecycle'] = $container->asShared(function($c) use ($manager, $lifecycleStorage){
				$lifecycleService = new Type\Lifecycle($lifecycleStorage);
				$lifecycleService->setServiceManager($manager);
				return $lifecycleService;
			});
			
			//Setup IpManager
			$container['dhcp'] = $container->asShared(function($c) use ($manager, $eM){
				$dhcpService = new Type\Dhcp($eM);
				$dhcpService->setServiceManager($manager);
				return $dhcpService;
			});
			
			$this->serviceDIContainer = $container;
		}
		
	}