<?php
namespace App\Model;

class Witch extends Character
{
	public function __construct(string $name)
	{
		$this->type = self::TYPE_WITCH;
		parent::__construct($name);
	}

	public function randomAction(Character $character): string
	{
		$randomInt = random_int(1, 4);

		if ($randomInt > 2)
			return $this->poison($character);
		else
			return $this->heal();
	}

	private function poison(Character $character): string
	{
		$character->hurt(3);
		$character->remainingPoisonedRounds = 3;

		return "$this->name a empoisonné $character->name et lui a fait perdre 3 pv. Il perdra 3 pv pendant les 3 prochains tours.";
	}

	private function heal(): string
	{
		$this->pv += 4;
		
		return "$this->name s'est soigné et a regagné 4 pv.";
	}
}