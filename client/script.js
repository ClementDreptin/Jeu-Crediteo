const axiosInstance = axios.create({ baseURL: 'http://localhost:8080/' });

let startButton = document.querySelector('#start-game-btn');
startButton.onclick = startGame;
let nameInput = document.querySelector('#character-input');
let characterSelect = document.querySelector('#character-select');
let createCharacterButton = document.querySelector('#create-character-btn');
createCharacterButton.onclick = createCharacter;
let charactersDiv = document.querySelector('#characters');
let playButton = document.querySelector('#play-btn');
playButton.onclick = play;
let feedbackDiv = document.querySelector('#feedback');

function startGame()
{
	startButton.innerHTML = "Recommencer la partie";
	resetGame();
	axiosInstance.post('game')
		.then(response => feedbackDiv.innerHTML = response.data.message)
		.catch(error => alert(error.response.data.message));
}

function createCharacter()
{
	const type = characterSelect.value;
	const name = nameInput.value;

	if (!type || !name)
		return alert("Veuillez choisir un nom et un type pour votre personnage.");
	
	axiosInstance.post(`characters/${type}`, { name: name })
		.then(response => charactersDiv.innerHTML += characterObjectToHTML(response.data.character))
		.catch(error => alert(error.response.data.message));
}

function play()
{
	axiosInstance.post('play')
		.then(response => {
			feedbackDiv.innerHTML += `<div>${response.data.message}</div>`;
			charactersDiv.innerHTML = "";
			response.data.characters.forEach(character => {
				charactersDiv.innerHTML += characterObjectToHTML(character);
			});

			if (response.data.characters.length == 1)
			{
				alert(`${response.data.characters[0].name} a gagné !`);
				startButton.innerHTML = "Commencer la partie";
				resetGame();
			}
		})
		.catch(error => alert(error.response.data.message));
}

function resetGame()
{
	feedbackDiv.innerHTML = "";
	charactersDiv.innerHTML = "";
}

function characterObjectToHTML(characterObject)
{
	let html = "<div>";
	html += `<div><strong>Type :</strong> ${characterObject.type === "witch" ? "sorcière" : characterObject.type}</div>`;
	html += `<div><strong>Nom :</strong> ${characterObject.name}</div>`;
	html += `<div><strong>Point de vie :</strong> ${characterObject.pv}</div>`;

	if (characterObject.remainingPoisonedRounds > 0)
		html += `<div><strong>Tour empoisonnés restants :</strong> ${characterObject.remainingPoisonedRounds}</div>`;
	
	html += "</div>"
	return html;
}