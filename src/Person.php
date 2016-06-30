<?php

class Person implements Greetable
{
	private $firstName;

	public static function withFirstName($firstName)
	{
		$person = new static();
		$person->firstName = $firstName;

		return $person;
	}

	public function getSalutaryName()
	{
		return $this->firstName;
	}
}
