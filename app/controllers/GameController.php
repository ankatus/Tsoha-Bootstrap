<?php
class GameController extends BaseController {


	public static function gamesList() {
		if (isset($_GET['userOnly'])) {
			$games = Game::getAllGamesForAccount(parent::get_user_logged_in()->id);
			View::make('game/gamesList.html', array('games' => $games, 'userOnly' => 1));
		} else {
			$games = Game::getAllGames();
			View::make('game/gamesList.html', array('games' => $games));
		}
	}

	public static function addGameForm() {
		$games = Game::getAllGames();
		View::make('game/addGame.html', array('games' => $games));
	}

	//creates a new game object and a Account_game object and saves them to the DB
	public static function addGame() {
		$params = $_POST;

		$newGame = new Game(array(
			'name' => $params['name'],
			'url' => $params['url'],
			'desc' => $params['desc']
			));

		$errors = $newGame->errors();

		if (count($errors) == 0) {
			$gameId = $newGame->save();
			$accountId = parent::get_user_logged_in()->id;
			Kint::dump(array($gameId, $accountId));
			$accountGame = new Account_game(array(
				'accountId' => $accountId,
				'gameId' => $gameId
				));
			$accountGame->save();
			$messages = array('game added');
			Redirect::to('/addGame', array('messages' => $messages));
		} else {
			Redirect::to('/addGame', array('errors' => $errors));
		}
	}

	//only adds a new Account_game entry to the DB
	public static function addGameUserOnly() {
		$accountId = parent::get_user_logged_in()->id;
		if (isset($_POST['list'])) {
			$gameId = $_POST['list'];
		} else {
			Redirect::to('/addGame', array('errors' => array('nothing selected')));
		}

		if (!Account_game::checkIfAccountOwnsGame($accountId, $gameId)) {
			$accountGame = new Account_game(array(
				'accountId' => $accountId,
				'gameId' => $gameId
			));
			$accountGame->save();
			Redirect::to('/addGame', array('messages' => array('game added to your account')));
		} else if (Game::getGame($gameId) == null) {
			$errors = array("game doesn't exist");
			Redirect::to('/addGame', array('errors' => $errors));
		} else {
			$errors = array('you already own that game');
			Redirect::to('/addGame', array('errors' => $errors));
		}
	}

	public static function viewGame($id) {
		$game = Game::getGame($id);
		View::make('game/viewGame.html', array('game' => $game));
	}

	//depending on if 'friendsOnly' is set or not, makes the view with a list of accounts that own the game that are either friends only or not
	public static function gameOwners($id) {
		$friendsOnly = $_POST['friendsOnly'];
		$game = Game::getGame($id);
		if ($friendsOnly) {
			$accounts = Account::getFriendsOwnersOfGame($id, parent::get_user_logged_in()->id);
		} else {
			$accounts = Account::getAllOwnersOfGame($id);
		}
		View::make('game/gameOwners.html', array('game' => $game, 'accounts' => $accounts, 'friendsOnly' => $friendsOnly));
	}

	public static function removeGameFromAccount() {
		$gameId = $_POST['gameId'];
		$accountId = parent::get_user_logged_in()->id;
		Account_game::remove($accountId, $gameId);
		$messages = array('game removed');
		Redirect::to('/gamesList?userOnly=1', array('messages' => $messages));
	}

	public static function sandbox() {
		$account = parent::get_user_logged_in();
		$link = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=steamapikeygoeshere&steamids=76561198043686750');
		$myarray = json_decode($link, true);
		Kint::dump($myarray);
		echo $myarray['response']['players'][0]['steamid'];
	}
}