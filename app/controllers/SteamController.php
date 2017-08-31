<?php
class SteamController extends BaseController {

	public static function getGamesForCurrentUser() {
		$apiKey = trim(file_get_contents('apikey.txt'));
		$steamId = parent::get_user_logged_in()->steamid;

		$games = Steam::getOwnedGames($steamId, $apiKey);

		if ($games == null) {
			$errors = array('steam id is bad :(');
			Redirect::to('/addGame', array('errors' => $errors));
			return null;
		}

		foreach ($games as $game) {
			if (count($game->errors()) == 0) {
				$gameId = $game->save();
			} else {
				$query = DB::connection()->prepare('SELECT game_id FROM Game WHERE game_name = :name');
				$query->execute(array('name' => $game->name));
				$result = $query->fetch();
				$gameId = $result['game_id'];
			}
			if (!Account_game::checkIfAccountOwnsGame(parent::get_user_logged_in()->id, $gameId)) {
				$accountGame = new Account_game(array(
					'accountId' => parent::get_user_logged_in()->id,
					'gameId' => $gameId
					));
				$accountGame->save();
			}
		}
		Redirect::to('/gamesList');
	}
}