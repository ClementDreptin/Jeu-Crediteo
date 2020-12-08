<?php
namespace App\Model;

class Orcq extends Character
{
	public function __construct(string $name)
	{
		$this->type = self::TYPE_ORCQ;
		parent::__construct($name);
	}

	public function randomAction(Character $character): string
	{
		return $this->attack($character);
	}

	private function attack(Character $character)
	{
		$character->hurt(5);

		return "$this->name a donné une attaque à $character->name et lui a fait perdre 5 pv.";
	}
}