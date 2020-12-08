<?php
namespace App\Utils;

use App\Model\Character;
use App\Model\Goblin;
use App\Model\Orcq;
use App\Model\Witch;
use stdClass;

class CharacterUtils
{
	static public function getRandomEnemy(array $characters, Character $currentCharacter): Character
	{
		// On supprime le personnage du courant de nos personnages pour ne pas s'auto-attaquer.
		$localCharacters = $characters;
		$key = array_search($currentCharacter, $characters, true);
		array_splice($localCharacters, $key, 1);

		// On choisit un index aléatoire parmi les personnages restants.
		$index = random_int(0, count($localCharacters) - 1);

		return $localCharacters[$index];
	}

	static public function hydrateCharacter(stdClass $object): Character
	{
		// Bricolage pour transformer un tableau d'instance de stdClass en tableau d'instance de Character.
		// Merci le typage dynamique de PHP...
		switch($object->type) {
			case Character::TYPE_GOBLIN:
				$newCharacter = new Goblin($object->name);
			break;
			case Character::TYPE_ORCQ:
				$newCharacter = new Orcq($object->name);
			break;
			case Character::TYPE_WITCH:
				$newCharacter = new Witch($object->name);
			break;
		}
		
		$newCharacter->pv = $object->pv;
		$newCharacter->remainingPoisonedRounds = $object->remainingPoisonedRounds;
		$newCharacter->justPlayed = $object->justPlayed;

		return $newCharacter;
	}
}