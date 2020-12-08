<?php
namespace App\Model;

class Goblin extends Character
{
	public function __construct(string $name)
	{
		$this->type = self::TYPE_GOBLIN;
		parent::__construct($name);
	}

	public function randomAction(Character $character): string
	{
		$randomInt = random_int(1, 4);

		if ($randomInt > 2)
			return $this->attack($character);
		else
			return $this->superAttack($character);
	}

	private function attack(Character $character): string
	{
		$character->hurt(5);

		return "$this->name a donné une attaque à $character->name et lui a fait perdre 5 pv.";
	}

	private function superAttack(Character $character): string
	{
		$character->hurt(10);
		$this->hurt(3);

		return "$this->name a donné une super attaque à $character->name et lui a fait perdre 10 pv mais a aussi perdu 3 pv.";
	}
}