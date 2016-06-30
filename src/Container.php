<?php

use Bernard\Consumer;
use Bernard\Driver\PredisDriver;
use Bernard\EventListener\ErrorLogSubscriber;
use Bernard\EventListener\FailureSubscriber;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\PimpleAwareRouter;
use Bernard\Serializer;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Container extends Pimple
{
	public function __construct() {

		$this['languages'] = function () {
			return ['english', 'french'];
		};

		$this->configureGreetingServices();
		$this->configureBernard();
	}

	private function configureBernard()
	{
		$this['bernard:driver'] = function () {
			return new PredisDriver(new Client(null, ['prefix' => 'bernard:']));
		};

		$this['bernard:factory'] = function ($container) {
			return new PersistentFactory(
				$container['bernard:driver'],
				new Serializer()
			);
		};

		$this['bernard:dispatcher'] = function ($container) {
			$dispatcher = new EventDispatcher();
			$dispatcher->addSubscriber(new ErrorLogSubscriber());
			$dispatcher->addSubscriber(new FailureSubscriber(
				$container['bernard:factory']
			));

			return $dispatcher;
		};

		$this['bernard:producer'] = function ($container) {
			return new Producer(
				$container['bernard:factory'],
				$container['bernard:dispatcher']
			);
		};

		$this['bernard:router'] = function ($container) {
			$router = new PimpleAwareRouter($container);
			$router->add('GreetInFrench', 'service:greet-person-in-french');
			$router->add('GreetInEnglish', 'service:greet-person-in-english');

			return $router;
		};

		$this['bernard:consumer'] = function ($container) {
			return new Consumer(
				$container['bernard:router'],
				$container['bernard:dispatcher']
			);
		};
	}

	public function configureGreetingServices()
	{
		$this['service:greet-person-in-french'] = function ($container) {
			$greeter = new GreetPerson($container['service:french-greeter']);
			return [$greeter, 'greetPerson'];
		};

		$this['service:greet-person-in-english'] = function ($container) {
			$greeter = new GreetPerson($container['service:english-greeter']);
			return [$greeter, 'greetPerson'];
		};

		$this['service:french-greeter'] = function () {
			return new FrenchGreeter();
		};

		$this['service:english-greeter'] = function () {
			return new EnglishGreeter();
		};
	}
}
