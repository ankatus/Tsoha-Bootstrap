<?php
class GameController extends BaseController {


	public static function currentAccountGameList() {
		$accountId = parent::get_user_logged_in()->id;
		$games = Game::getAllGamesForAccount($accountId);
		View::make('game/list.html', array('games' => $games));
	}

	public static function addGameForm() {
		$games = Game::getAllGames();
		View::make('game/addGame.html', array('games' => $games));
	}

	public static function addGame() {
		$params = $_POST;

		$newGame = new Game(array(
			'name' => $params['name'],
			'url' => $params['url'],
			'desc' => $params['desc']
			));

		$errors = $newGame->errors();

		if (count($errors) == 0) {

			$game_id = $newGame->save();
			$account_id = parent::get_user_logged_in()->id;
			$account_game = new Account_game(array(
				'account_id' => $account_id,
				'game_id' => $game_id
				));
			$account_game->save();
			$messages = array('game added');
			Redirect::to('/addGame', array('messages' => $messages));
		} else {
			Redirect::to('/addGame', array('errors' => $errors));
		}
	}

	public static function addGameUserOnly() {
		$account_id = parent::get_user_logged_in()->id;
		$game_id = $_POST['list'];
		$account_game = new Account_game(array(
			'account_id' => $account_id,
			'game_id' => $game_id
			));
		$account_game->save();

		Redirect::to('/addGame');
	}

	public static function sandbox() {
		$account = parent::get_user_logged_in();
		Kint::dump($account);
	}
}