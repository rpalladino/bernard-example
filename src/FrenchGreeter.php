<?php

class FrenchGreeter implements Greeter
{
	public function greet(Greetable $person) {
		echo "Bounjour, {$person->getSalutaryName()}\n";
	}
}
