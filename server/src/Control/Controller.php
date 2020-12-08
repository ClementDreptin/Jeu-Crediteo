<?php
namespace App\Control;

use App\Model\Character;
use App\Model\Goblin;
use App\Model\Witch;
use App\Model\Orcq;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils\JSON;
use App\Utils\CharacterUtils;

class Controller
{
	protected $container;

	public function __construct(\Slim\Container $container = null)
	{
		$this->container = $container;
	}

	public function createGame(Request $request, Response $response, $args): Response
	{
		// On crée le fichier json des personnages et on place un tableau vide dedans.
		file_put_contents($this->container->settings['pathToCharacters'], '[]');

		return JSON::successResponse($response, 201, [
			"message" => "Partie créée."
		]);
	}

	public function createGoblin(Request $request, Response $response, $args): Response
	{
		$body = $request->getParsedBody();

		if (!isset($body['name']))
			return JSON::errorResponse($response, 400, "Format incorrect dans les paramètres. Veuillez spécifier un nom.");

		$goblin = new Goblin($body['name']);
		$this->createCharacter($goblin);

		return JSON::successResponse($response, 201, [
			"character" => $goblin
		]);
	}

	public function createWitch(Request $request, Response $response, $args): Response
	{
		$body = $request->getParsedBody();

		if (!isset($body['name']))
			return JSON::errorResponse($response, 400, "Format incorrect dans les paramètres. Veuillez spécifier un nom.");

		$witch = new Witch($body['name']);
		$this->createCharacter($witch);

		return JSON::successResponse($response, 201, [
			"character" => $witch
		]);
	}

	public function createOrcq(Request $request, Response $response, $args): Response
	{
		$body = $request->getParsedBody();

		if (!isset($body['name']))
			return JSON::errorResponse($response, 400, "Format incorrect dans les paramètres. Veuillez spécifier un nom.");

		$orcq = new Orcq($body['name']);
		$this->createCharacter($orcq);

		return JSON::successResponse($response, 201, [
			"character" => $orcq
		]);
	}

	public function doRound(Request $request, Response $response, $args): Response
	{
		$currentCharacter = null;

		$allCharacters = $this->getCharacters();
		if (count($allCharacters) == 0)
			return JSON::errorResponse($response, 400, "Il n'y a pas de personnage avec lequel jouer.");

		if (count($allCharacters) == 1)
			return JSON::errorResponse($response, 400, "Il n'y a pas assez de personnages pour jouer, il en faut au moins 2.");
		
		// On enlève 3 pv à tous les personnages empoisonnés.
		foreach ($allCharacters as $character)
		{
			if ($character->remainingPoisonedRounds > 0)
			{
				$character->pv -= 3;
				$character->remainingPoisonedRounds--;
			}
		}

		// C'est un peu contre-intuitif mais $previousCharacter est un tableau avec un seul personnage, pas juste un personnage.
		$previousCharacter = array_filter($allCharacters, function ($character) {
			return $character->justPlayed;
		});

		if (empty($previousCharacter))
		{
			$currentCharacter = $allCharacters[0];
			$currentCharacter->justPlayed = true;
		}
		else
		{
			// On dit que le personnage n'est plus celui qui vient de jouer.
			$index = array_key_first($previousCharacter);
			$allCharacters[$index]->justPlayed = false;

			// Si l'index du personnage suivant est supérieur ou égal au nombre de personnage alors c'est à nouveau au premier.
			$index++;
			if ($index >= count($allCharacters))
				$index = 0;
			
			$currentCharacter = $allCharacters[$index];

			$currentCharacter->justPlayed = true;
		}

		// On détermine un ennemi aléatoire que le personnage courant va attaquer (qui n'est pas lui même évidemment).
		$randomEnemy = CharacterUtils::getRandomEnemy($allCharacters, $currentCharacter);

		// On récupère le message de log généré par l'action.
		$message = $currentCharacter->randomAction($randomEnemy);

		// Si un personnage est mort (pv <= 0) alors il est retiré du tableau.
		foreach ($allCharacters as $character)
		{
			if ($character->pv <= 0)
			{
				$message .= " $character->name est mort.";
				$key = array_search($character, $allCharacters, true);
				array_splice($allCharacters, $key, 1);
			}
		}

		// Si après la suppression des personnages mort il ne reste plus qu'un personnage alors la partie est finie.
		if (count($allCharacters) == 1)
		{
			$this->deleteGame();
		}
		else
		{
			$this->updateCharacters($allCharacters);
		}

		return JSON::successResponse($response, 200, [
			"message" => $message,
			"characters" => $allCharacters
		]);
	}

	private function getCharacters(): array
	{
		// Les personnages sont récupérés depuis le fichier json et décodés.
		$arrayStdCharacters = json_decode(file_get_contents($this->container->settings['pathToCharacters']));

		// Le problème est que les objets récupérés du fichier sont considérés comme des instances de stdClass
		// il faut donc créer un tableau d'instances de la classe Character à partir des objets du fichier.
		$characters = [];
		foreach ($arrayStdCharacters as $character)
			array_push($characters, CharacterUtils::hydrateCharacter($character));

		return $characters;
	}

	private function createCharacter(Character $character)
	{
		// On décode les personnages du fichier json
		$characters = json_decode(file_get_contents($this->container->settings['pathToCharacters']));

		// On insère notre nouveau personnage
		array_push($characters, $character);

		// On ré-encode notre tableau en json et on écrase le contenu du fichier json avec le tableau.
		file_put_contents($this->container->settings['pathToCharacters'], json_encode($characters));
	}

	private function updateCharacters(array $characters)
	{
		file_put_contents($this->container->settings['pathToCharacters'], json_encode($characters));
	}

	private function deleteGame()
	{
		if (!file_exists($this->container->settings['pathToCharacters']))
			return;
		
		// On supprime le fichier json avec les personnages quand la partie est terminée.
		unlink($this->container->settings['pathToCharacters']);
	}
}