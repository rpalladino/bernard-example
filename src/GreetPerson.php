<?php

use Bernard\Message;

class GreetPerson
{
	private $greeter;

	public function __construct(Greeter $greeter)
	{
		$this->greeter = $greeter;
	}

	public function greetPerson(Message $message)
	{
		$person = Person::withFirstName($message->name);
		$this->greeter->greet($person);
	}
}
