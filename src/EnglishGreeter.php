<?php

class EnglishGreeter implements Greeter
{
	public function greet(Greetable $person)
	{
		echo "Hello, {$person->getSalutaryName()}\n";
	}
}
