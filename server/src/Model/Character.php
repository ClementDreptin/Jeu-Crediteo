<?php
namespace App\Model;

abstract class Character
{
	const TYPE_GOBLIN = 'goblin';
	const TYPE_ORCQ = 'orcq';
	const TYPE_WITCH = 'witch';

	public $pv = 20;

	public $name = null;

	public $remainingPoisonedRounds = 0;

	public $justPlayed = false;

	public $type = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	abstract public function randomAction(Character $character);

	protected function hurt(int $damage)
	{
		$this->pv -= $damage;
	}
}